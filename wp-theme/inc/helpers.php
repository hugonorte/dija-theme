<?php
/**
 * Funções de apoio usadas pelos templates.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Textos e valores padrão do Personalizador.
 * São usados enquanto a professora não edita o campo correspondente.
 */
function dija_defaults(): array {
	return array(
		// Geral
		'logo_text'          => 'DIJA FERREIRA',
		'nav_cta_label'      => 'MATRICULE-SE',
		'nav_cta_url'        => '/contato/',
		'instagram'          => '@dijaferreira.danca',
		'whatsapp'           => '(81) 99999-9938',

		// Hero
		'hero_name_1'        => 'Dija',
		'hero_name_2'        => 'Ferreira',
		'hero_badge'         => 'PROFESSORA DE DANÇA DE SALÃO',
		'hero_tagline'       => "Venha dançar com\na gente e ser feliz",
		'hero_cta_label'     => 'QUERO DANÇAR!',
		'hero_cta_url'       => '/turmas/',
		'hero_photo'         => 0,

		// Turmas
		'turmas_kicker'      => 'NOSSAS',
		'turmas_title'       => 'TURMAS',
		'turmas_page_intro'  => 'Escolha o estilo e o horário que melhor se encaixam na sua rotina e venha dançar com a gente.',
		'turmas_now_title'   => 'TURMAS EM ANDAMENTO',
		'turmas_now_empty'   => 'Novas turmas em breve. Fale com a gente pelo contato!',
		'turmas_next_title'  => 'PRÓXIMAS TURMAS',
		'turmas_next_intro'  => 'Garanta sua vaga nas turmas que vão começar.',
		'turmas_next_empty'  => 'Nenhuma turma nova agendada no momento. Siga o Instagram para saber das próximas!',
		'turmas_cta_label'   => 'QUERO PARTICIPAR',
		'turmas_wa_message'  => "Olá, Dija! Quero participar da turma de {turma}.\nLocal: {local}\nDia: {dia}\nHorário: {horario}",

		// Eventos
		'eventos_title'      => 'EVENTOS',
		'eventos_page_intro' => 'Transforme o seu evento em uma experiência única: apresentações culturais, festivais, aniversários, casamentos, eventos corporativos e muito mais.',
		'eventos_wa_message' => "Olá, Dija! Tenho interesse em saber mais sobre eventos: {evento}",
		'fotos_title'        => 'FOTOS',
		'fotos_intro'        => 'Aqui você confere momentos dos eventos de que participamos: apresentações, festivais, casamentos e muito mais. Use os filtros para encontrar as fotos por ano, evento ou tema.',
		'fotos_empty'        => 'Nenhuma foto encontrada com esses filtros.',

		// Sobre
		'sobre_kicker'       => 'SOBRE A',
		'sobre_title'        => 'PROFESSORA',
		'sobre_text'         => 'Dija Ferreira é apaixonada pela cultura nordestina e pela arte de ensinar através da dança. Com anos de experiência, transforma cada aula em um momento de conexão, alegria e movimento.',
		'sobre_highlight'    => "AQUI, VOCÊ NÃO APRENDE\nAPENAS A DANÇAR.",
		'sobre_accent'       => 'VOCÊ VIVE A CULTURA!',
		'sobre_photo'        => 0,
		'sobre_cta_label'    => 'SAIBA MAIS',
		'sobre_cta_url'      => '/bio/',

		// Contato
		'contato_title'      => 'CONTATO',
		'contato_text'       => "TIRE SUAS DÚVIDAS,\nFAÇA SUA MATRÍCULA OU\nCONTRATE PARA SEU EVENTO!",
		'contato_button'     => 'VAMOS DANÇAR!',
		'contato_success'    => 'Mensagem enviada! Em breve entraremos em contato.',
		'contato_email'      => 'contato@dija.com.br',
	);
}

/** Valor de um campo do Personalizador (com padrão). */
function dija_mod( string $key ) {
	$defaults = dija_defaults();
	$value    = get_theme_mod( 'dija_' . $key, $defaults[ $key ] ?? '' );
	// Campo apagado no Personalizador → volta ao padrão (evita seção vazia).
	if ( '' === $value && isset( $defaults[ $key ] ) && is_string( $defaults[ $key ] ) ) {
		return $defaults[ $key ];
	}
	return $value;
}

/** Quebra um texto de várias linhas em linhas não vazias. */
function dija_lines( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/', $text ) ), 'strlen' ) );
}

/** Imprime cada linha de um texto como <span>, escapando o conteúdo. */
function dija_echo_lines( string $text, string $class = '' ): void {
	foreach ( dija_lines( $text ) as $line ) {
		printf( '<span%s>%s</span>', $class ? ' class="' . esc_attr( $class ) . '"' : '', esc_html( $line ) );
	}
}

/** URL de um arquivo de imagem do tema. */
function dija_asset( string $file ): string {
	return get_theme_file_uri( 'assets/img/' . $file );
}

/**
 * Link interno: aceita URL completa ou caminho ("/contato/") relativo ao site.
 */
function dija_link( string $url ): string {
	if ( '' === $url ) {
		return home_url( '/' );
	}
	if ( str_starts_with( $url, '/' ) && ! str_starts_with( $url, '//' ) ) {
		return home_url( $url );
	}
	return $url;
}

/**
 * Foto editável: usa a imagem escolhida na Biblioteca de Mídia (com srcset);
 * se não houver, a imagem padrão do tema em AVIF com reserva.
 */
function dija_photo( int $attachment_id, string $default_base, string $default_fallback_ext, string $alt, string $size = 'large' ): void {
	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		echo wp_get_attachment_image( $attachment_id, $size, false, array( 'alt' => $alt, 'loading' => 'lazy', 'decoding' => 'async' ) );
		return;
	}
	printf(
		'<picture><source srcset="%1$s" type="image/avif"><img src="%2$s" alt="%3$s" loading="lazy" decoding="async"></picture>',
		esc_url( dija_asset( $default_base . '.avif' ) ),
		esc_url( dija_asset( $default_base . '.' . $default_fallback_ext ) ),
		esc_attr( $alt )
	);
}

/** Link do WhatsApp a partir do número digitado (assume Brasil, DDI 55), com mensagem opcional (?text=). */
function dija_whatsapp_url( string $message = '' ): string {
	$digits = preg_replace( '/\D+/', '', (string) dija_mod( 'whatsapp' ) );
	if ( '' === $digits ) {
		return '';
	}
	if ( strlen( $digits ) <= 11 ) {
		$digits = '55' . $digits;
	}
	$url = 'https://wa.me/' . $digits;
	return '' === $message ? $url : $url . '?text=' . rawurlencode( $message );
}

/**
 * Link do botão "Quero participar" de uma turma: WhatsApp com a mensagem do
 * Personalizador, preenchendo {turma}, {local}, {endereco}, {dia}, {horario}
 * e {inicio}. Sem WhatsApp cadastrado, leva à página de contato.
 */
function dija_turma_whatsapp_url( WP_Post $turma, string $local, string $address = '' ): string {
	$replace = array(
		'{turma}'    => trim( dija_field( $turma->ID, 'ritmo' ) . ' ' . dija_field( $turma->ID, 'nivel' ) ),
		'{local}'    => $local,
		'{endereco}' => preg_replace( '/\s+/', ' ', $address ),
		'{dia}'      => dija_weekday_label( dija_field( $turma->ID, 'dia' ) ),
		'{horario}'  => dija_field( $turma->ID, 'horario' ),
		'{inicio}'   => dija_field( $turma->ID, 'inicio' ),
	);
	$message = trim( strtr( (string) dija_mod( 'turmas_wa_message' ), $replace ) );
	return dija_whatsapp_url( $message ) ?: dija_link( dija_mod( 'nav_cta_url' ) );
}

/** Link do botão "Saiba mais" na página /eventos: WhatsApp citando o evento. */
function dija_evento_whatsapp_url( WP_Post $evento ): string {
	$message = trim( strtr( (string) dija_mod( 'eventos_wa_message' ), array( '{evento}' => get_the_title( $evento ) ) ) );
	return dija_whatsapp_url( $message ) ?: dija_link( dija_mod( 'nav_cta_url' ) );
}

/**
 * Escapa um link para atributo href preservando as quebras de linha (%0A) da
 * mensagem do WhatsApp — esc_url() as removeria. Só links wa.me montados pelo
 * próprio tema (número só com dígitos, texto via rawurlencode) recebem esse
 * tratamento; qualquer outro passa por esc_url().
 */
function dija_esc_link( string $url ): string {
	if ( preg_match( '#^https://wa\.me/\d+(\?text=[A-Za-z0-9%._~-]*)?$#', $url ) ) {
		return esc_attr( $url );
	}
	return esc_url( $url );
}

/** Link do Instagram a partir do @usuário (ou URL completa). */
function dija_instagram_url(): string {
	$handle = trim( (string) dija_mod( 'instagram' ) );
	if ( '' === $handle ) {
		return '';
	}
	if ( str_starts_with( $handle, 'http' ) ) {
		return $handle;
	}
	return 'https://instagram.com/' . ltrim( $handle, '@' );
}
