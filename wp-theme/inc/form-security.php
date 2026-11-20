<?php
/**
 * Proteção do formulário de contato contra envios automáticos.
 *
 * Três camadas, todas nativas (sem plugins):
 *   1. Honeypot: campo invisível; quem o preenche é tratado como robô e o IP
 *      fica BLOQUEADO TEMPORARIAMENTE para o formulário (padrão: 72 h).
 *   2. Armadilha de tempo: envios feitos menos de 3 s após a página abrir
 *      (ou sem o carimbo de tempo assinado) são descartados.
 *   3. Limite de envios: no máximo 5 mensagens por hora por IP.
 *
 * O bloqueio vale só para o formulário (não para o site inteiro) e expira
 * sozinho. A lista fica em Contatos → IPs bloqueados, com opção de
 * desbloquear. Endereços IPv6 são tratados por faixa (/64), já que cada
 * conexão recebe uma faixa inteira.
 *
 * IP do visitante: por padrão, o endereço real da conexão (REMOTE_ADDR).
 * Cabeçalhos como X-Forwarded-For podem ser forjados pelo visitante e por
 * isso são ignorados. Se o site ficar atrás de um proxy confiável, defina no
 * wp-config.php o cabeçalho que ele envia, por exemplo (Cloudflare):
 *   define( 'DIJA_TRUSTED_IP_HEADER', 'HTTP_CF_CONNECTING_IP' );
 */

defined( 'ABSPATH' ) || exit;

const DIJA_BLOCKS_OPTION   = 'dija_blocked_ips';
const DIJA_MIN_FILL_SECS   = 3;
const DIJA_RATE_MAX        = 5;
const DIJA_RATE_WINDOW     = HOUR_IN_SECONDS;

/** Duração do bloqueio (padrão 72 h; ajustável pelo filtro "dija_block_ttl"). */
function dija_block_ttl(): int {
	return (int) apply_filters( 'dija_block_ttl', 3 * DAY_IN_SECONDS );
}

/* -------------------------------------------------------------------------
 * IP do visitante
 * ---------------------------------------------------------------------- */

function dija_get_client_ip(): string {
	$ip = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' );
	if ( defined( 'DIJA_TRUSTED_IP_HEADER' ) && ! empty( $_SERVER[ DIJA_TRUSTED_IP_HEADER ] ) ) {
		$first = trim( explode( ',', (string) $_SERVER[ DIJA_TRUSTED_IP_HEADER ] )[0] );
		if ( filter_var( $first, FILTER_VALIDATE_IP ) ) {
			$ip = $first;
		}
	}
	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
}

/** Chave do bloqueio: o próprio IPv4, ou a faixa /64 do IPv6. */
function dija_ip_key( string $ip ): string {
	if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
		$bin = inet_pton( $ip );
		return inet_ntop( substr( $bin, 0, 8 ) . str_repeat( "\0", 8 ) ) . '/64';
	}
	return $ip;
}

/* -------------------------------------------------------------------------
 * 1. Bloqueio temporário
 * ---------------------------------------------------------------------- */

/** Bloqueios ativos (os vencidos são descartados na leitura). */
function dija_get_blocks(): array {
	$blocks = get_option( DIJA_BLOCKS_OPTION, array() );
	$blocks = is_array( $blocks ) ? $blocks : array();
	$active = array_filter( $blocks, fn( $b ) => isset( $b['until'] ) && $b['until'] > time() );
	if ( count( $active ) !== count( $blocks ) ) {
		update_option( DIJA_BLOCKS_OPTION, $active, false );
	}
	return $active;
}

function dija_is_ip_blocked( string $ip ): bool {
	return '' !== $ip && isset( dija_get_blocks()[ dija_ip_key( $ip ) ] );
}

function dija_block_ip( string $ip, string $reason ): void {
	if ( '' === $ip ) {
		return;
	}
	$blocks                       = dija_get_blocks();
	$blocks[ dija_ip_key( $ip ) ] = array(
		'until'  => time() + dija_block_ttl(),
		'since'  => time(),
		'reason' => $reason,
	);
	update_option( DIJA_BLOCKS_OPTION, $blocks, false ); // sem autoload: só é lido no envio do formulário
}

function dija_unblock_ip( string $key ): void {
	$blocks = dija_get_blocks();
	unset( $blocks[ $key ] );
	update_option( DIJA_BLOCKS_OPTION, $blocks, false );
}

/* -------------------------------------------------------------------------
 * 2. Armadilha de tempo (carimbo assinado no formulário)
 * ---------------------------------------------------------------------- */

/** Carimbo "hora.assinatura" impresso no formulário. */
function dija_form_timestamp(): string {
	$ts = (string) time();
	return $ts . '.' . hash_hmac( 'sha256', $ts, wp_salt( 'nonce' ) );
}

/**
 * Confere o carimbo enviado: assinatura válida e preenchimento em pelo menos
 * DIJA_MIN_FILL_SECS segundos. Não há prazo máximo (página aberta há horas,
 * ou servida por cache, continua valendo).
 */
function dija_form_timestamp_ok( string $value ): bool {
	if ( ! preg_match( '/^(\d{9,12})\.([a-f0-9]{64})$/', $value, $m ) ) {
		return false;
	}
	if ( ! hash_equals( hash_hmac( 'sha256', $m[1], wp_salt( 'nonce' ) ), $m[2] ) ) {
		return false;
	}
	return ( time() - (int) $m[1] ) >= DIJA_MIN_FILL_SECS;
}

/* -------------------------------------------------------------------------
 * 3. Limite de envios por IP
 * ---------------------------------------------------------------------- */

/** Conta o envio e informa se o IP passou do limite na janela atual. */
function dija_rate_limited( string $ip ): bool {
	if ( '' === $ip ) {
		return false;
	}
	$key  = 'dija_rate_' . md5( dija_ip_key( $ip ) );
	$data = get_transient( $key );
	$data = is_array( $data ) ? $data : array( 'count' => 0, 'start' => time() );
	if ( $data['count'] >= DIJA_RATE_MAX ) {
		return true;
	}
	$data['count']++;
	// A janela conta a partir do primeiro envio; o transient expira sozinho.
	set_transient( $key, $data, max( 60, DIJA_RATE_WINDOW - ( time() - $data['start'] ) ) );
	return false;
}

/* -------------------------------------------------------------------------
 * Tela no painel: Contatos → IPs bloqueados
 * ---------------------------------------------------------------------- */

add_action( 'admin_menu', function () {
	add_submenu_page(
		'edit.php?post_type=contact_message',
		'IPs bloqueados',
		'IPs bloqueados',
		'manage_options',
		'dija-ips-bloqueados',
		'dija_render_blocks_page'
	);
} );

add_action( 'admin_post_dija_unblock_ip', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Sem permissão.' );
	}
	check_admin_referer( 'dija_unblock_ip' );
	$key = isset( $_POST['ip_key'] ) ? sanitize_text_field( wp_unslash( $_POST['ip_key'] ) ) : '';
	if ( '' !== $key ) {
		dija_unblock_ip( $key );
	}
	wp_safe_redirect( admin_url( 'edit.php?post_type=contact_message&page=dija-ips-bloqueados&desbloqueado=1' ) );
	exit;
} );

function dija_render_blocks_page(): void {
	$blocks  = dija_get_blocks();
	$reasons = array(
		'honeypot' => 'Preencheu o campo-armadilha (robô)',
	);
	echo '<div class="wrap"><h1>IPs bloqueados no formulário de contato</h1>';
	if ( isset( $_GET['desbloqueado'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- só exibe aviso.
		echo '<div class="notice notice-success is-dismissible"><p>IP desbloqueado.</p></div>';
	}
	printf(
		'<p>Endereços que caíram na armadilha anti-robô ficam impedidos de enviar o formulário por %d horas. O bloqueio vale só para o formulário (o site continua acessível) e expira sozinho.</p>',
		(int) round( dija_block_ttl() / HOUR_IN_SECONDS )
	);
	if ( ! $blocks ) {
		echo '<p><em>Nenhum IP bloqueado no momento.</em></p></div>';
		return;
	}
	echo '<table class="widefat striped"><thead><tr><th>IP / faixa</th><th>Motivo</th><th>Bloqueado em</th><th>Expira em</th><th></th></tr></thead><tbody>';
	foreach ( $blocks as $key => $b ) {
		echo '<tr>';
		echo '<td><code>' . esc_html( (string) $key ) . '</code></td>';
		echo '<td>' . esc_html( $reasons[ $b['reason'] ?? '' ] ?? (string) ( $b['reason'] ?? '' ) ) . '</td>';
		echo '<td>' . esc_html( wp_date( 'd/m/Y H:i', (int) ( $b['since'] ?? 0 ) ) ) . '</td>';
		echo '<td>' . esc_html( wp_date( 'd/m/Y H:i', (int) $b['until'] ) ) . '</td>';
		echo '<td><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
		wp_nonce_field( 'dija_unblock_ip' );
		echo '<input type="hidden" name="action" value="dija_unblock_ip"><input type="hidden" name="ip_key" value="' . esc_attr( (string) $key ) . '">';
		echo '<button class="button">Desbloquear</button></form></td>';
		echo '</tr>';
	}
	echo '</tbody></table></div>';
}
