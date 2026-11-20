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
		'hero_cta_url'       => '/contato/',
		'hero_photo'         => 0,

		// Turmas
		'turmas_kicker'      => 'NOSSAS',
		'turmas_title'       => 'TURMAS',
		'turmas_page_intro'  => 'Escolha o estilo e o horário que melhor se encaixam na sua rotina e venha dançar com a gente.',

		// Eventos
		'eventos_title'      => 'EVENTOS',
		'eventos_page_intro' => 'Transforme o seu evento em uma experiência única: apresentações culturais, festivais, aniversários, casamentos, eventos corporativos e muito mais.',

		// Sobre
		'sobre_kicker'       => 'SOBRE A',
		'sobre_title'        => 'PROFESSORA',
		'sobre_text'         => 'Dija Ferreira é apaixonada pela cultura nordestina e pela arte de ensinar através da dança. Com anos de experiência, transforma cada aula em um momento de conexão, alegria e movimento.',
		'sobre_highlight'    => "AQUI, VOCÊ NÃO APRENDE\nAPENAS A DANÇAR.",
		'sobre_accent'       => 'VOCÊ VIVE A CULTURA!',
		'sobre_photo'        => 0,

		// Contato
		'contato_title'      => 'CONTATO',
		'contato_text'       => "TIRE SUAS DÚVIDAS,\nFAÇA SUA MATRÍCULA OU\nCONTRATE PARA SEU EVENTO!",
		'contato_button'     => 'VAMOS DANÇAR!',
		'contato_success'    => 'Mensagem enviada! Em breve entraremos em contato.',
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

/** Link do WhatsApp a partir do número digitado (assume Brasil, DDI 55). */
function dija_whatsapp_url(): string {
	$digits = preg_replace( '/\D+/', '', (string) dija_mod( 'whatsapp' ) );
	if ( '' === $digits ) {
		return '';
	}
	if ( strlen( $digits ) <= 11 ) {
		$digits = '55' . $digits;
	}
	return 'https://wa.me/' . $digits;
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
