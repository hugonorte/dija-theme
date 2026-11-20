<?php
/**
 * Textos fixos do site em Aparência > Personalizar > "Conteúdo do site".
 * (Turmas e eventos ficam nos menus próprios do painel.)
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {
	$defaults = dija_defaults();

	$wp_customize->add_panel( 'dija_content', array(
		'title'       => 'Conteúdo do site',
		'description' => 'Textos e fotos das seções. Turmas e eventos são editados nos menus “Turmas” e “Eventos” do painel.',
		'priority'    => 20,
	) );

	$sections = array(
		'dija_geral'   => 'Topo e contatos',
		'dija_hero'    => 'Início (destaque)',
		'dija_turmas'  => 'Turmas',
		'dija_eventos' => 'Eventos',
		'dija_sobre'   => 'Sobre a professora',
		'dija_contato' => 'Contato',
	);
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'dija_content' ) );
	}

	// [chave, seção, rótulo, tipo, descrição]
	$fields = array(
		array( 'logo_text', 'dija_geral', 'Nome no topo', 'text', '' ),
		array( 'nav_cta_label', 'dija_geral', 'Botão do topo — texto', 'text', '' ),
		array( 'nav_cta_url', 'dija_geral', 'Botão do topo — link', 'link', 'Ex.: /contato/' ),
		array( 'instagram', 'dija_geral', 'Instagram', 'text', 'Ex.: @dijaferreira.danca' ),
		array( 'whatsapp', 'dija_geral', 'WhatsApp', 'text', 'Ex.: (81) 99999-9999' ),

		array( 'hero_name_1', 'dija_hero', 'Nome — 1ª linha', 'text', '' ),
		array( 'hero_name_2', 'dija_hero', 'Nome — 2ª linha', 'text', '' ),
		array( 'hero_badge', 'dija_hero', 'Faixa roxa', 'text', 'Até ~32 caracteres.' ),
		array( 'hero_tagline', 'dija_hero', 'Frase', 'textarea', 'Cada linha do campo vira uma linha no site.' ),
		array( 'hero_cta_label', 'dija_hero', 'Botão — texto', 'text', '' ),
		array( 'hero_cta_url', 'dija_hero', 'Botão — link', 'link', 'Ex.: /contato/' ),
		array( 'hero_photo', 'dija_hero', 'Foto', 'image', 'PNG ou AVIF com fundo transparente, retrato (aprox. 3:4).' ),

		array( 'turmas_kicker', 'dija_turmas', 'Título pequeno', 'text', '' ),
		array( 'turmas_title', 'dija_turmas', 'Título grande', 'text', '' ),
		array( 'turmas_page_intro', 'dija_turmas', 'Texto de abertura da página /turmas', 'textarea', '' ),

		array( 'eventos_title', 'dija_eventos', 'Título', 'text', '' ),
		array( 'eventos_page_intro', 'dija_eventos', 'Texto de abertura da página /eventos', 'textarea', '' ),

		array( 'sobre_kicker', 'dija_sobre', 'Título pequeno', 'text', '' ),
		array( 'sobre_title', 'dija_sobre', 'Título grande', 'text', '' ),
		array( 'sobre_text', 'dija_sobre', 'Parágrafo (página inicial)', 'textarea', 'Até ~260 caracteres. O texto completo fica na página “Bio”.' ),
		array( 'sobre_highlight', 'dija_sobre', 'Destaque roxo', 'textarea', 'Cada linha do campo vira uma linha no site.' ),
		array( 'sobre_accent', 'dija_sobre', 'Destaque final', 'text', '' ),
		array( 'sobre_photo', 'dija_sobre', 'Foto', 'image', 'Foto quase quadrada; o rosto deve ficar no terço de cima.' ),

		array( 'contato_title', 'dija_contato', 'Título', 'text', '' ),
		array( 'contato_text', 'dija_contato', 'Texto', 'textarea', 'Cada linha do campo vira uma linha no site.' ),
		array( 'contato_button', 'dija_contato', 'Botão do formulário', 'text', '' ),
		array( 'contato_success', 'dija_contato', 'Mensagem após o envio', 'text', '' ),
	);

	foreach ( $fields as [ $key, $section, $label, $type, $desc ] ) {
		$setting = 'dija_' . $key;
		$sanitize = match ( $type ) {
			'textarea' => 'sanitize_textarea_field',
			'image'    => 'absint',
			'link'     => 'dija_sanitize_link',
			default    => 'sanitize_text_field',
		};
		$wp_customize->add_setting( $setting, array(
			'default'           => $defaults[ $key ] ?? '',
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
		) );

		if ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $setting, array(
				'label'       => $label,
				'description' => $desc,
				'section'     => $section,
				'mime_type'   => 'image',
			) ) );
			continue;
		}
		$wp_customize->add_control( $setting, array(
			'label'       => $label,
			'description' => $desc,
			'section'     => $section,
			'type'        => 'textarea' === $type ? 'textarea' : 'text',
		) );
	}
} );

/** Aceita URL completa ou caminho interno ("/contato/"). */
function dija_sanitize_link( $value ): string {
	$value = trim( (string) $value );
	if ( str_starts_with( $value, '/' ) || str_starts_with( $value, '#' ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value );
}
