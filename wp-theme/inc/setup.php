<?php
/**
 * Suportes do tema, carregamento de estilos/scripts e menu principal.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'dija-theme', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus( array(
		'primary' => __( 'Menu principal (topo)', 'dija-theme' ),
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	$dir = get_template_directory();

	wp_enqueue_style(
		'dija-fonts',
		'https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Roboto+Condensed:wght@400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'dija-theme', get_theme_file_uri( 'assets/styles.css' ), array( 'dija-fonts' ), (string) filemtime( $dir . '/assets/styles.css' ) );

	wp_enqueue_script(
		'dija-theme',
		get_theme_file_uri( 'assets/app.js' ),
		array(),
		(string) filemtime( $dir . '/assets/app.js' ),
		array( 'in_footer' => true, 'strategy' => 'defer' )
	);
} );

/** Pré-conexão com o Google Fonts (carrega as fontes mais cedo). */
add_filter( 'wp_resource_hints', function ( array $urls, string $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

/**
 * Itens do menu do topo.
 * Se um menu for atribuído em Aparência > Menus ("Menu principal"), ele é usado;
 * senão, o tema mostra os links padrão do site.
 *
 * @return array<int, array{label:string, url:string}>
 */
function dija_nav_items(): array {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );
		if ( $items ) {
			$out = array();
			foreach ( $items as $item ) {
				if ( 0 === (int) $item->menu_item_parent ) { // submenus não cabem no layout
					$out[] = array( 'label' => $item->title, 'url' => $item->url );
				}
			}
			return $out;
		}
	}

	$bio = get_page_by_path( 'bio' );
	return array(
		array( 'label' => 'INÍCIO', 'url' => home_url( '/' ) ),
		array( 'label' => 'TURMAS', 'url' => (string) get_post_type_archive_link( 'turma' ) ),
		array( 'label' => 'EVENTOS', 'url' => (string) get_post_type_archive_link( 'evento' ) ),
		array( 'label' => 'SOBRE', 'url' => $bio ? get_permalink( $bio ) : home_url( '/#sobre' ) ),
	);
}

/** O link aponta para a página atual? (destaque "INÍCIO" sublinhado no menu) */
function dija_is_current_url( string $url ): bool {
	global $wp;
	$current = untrailingslashit( home_url( $wp->request ?? '' ) );
	$target  = untrailingslashit( strtok( $url, '#?' ) ?: $url );
	return $current === $target;
}

/** Limita o resumo dos posts do blog. */
add_filter( 'excerpt_length', fn() => 30 );
add_filter( 'excerpt_more', fn() => '…' );

/**
 * Favicon do tema (coração), enquanto não houver "Ícone do site" definido em
 * Aparência → Personalizar → Identidade do site (que tem prioridade):
 *   - icon.svg: navegadores modernos, nítido em qualquer tamanho;
 *   - favicon.ico (16/32/48 px): navegadores antigos;
 *   - apple-touch-icon.png (180 px): atalho na tela inicial do iPhone/iPad.
 * Fonte do desenho: design-systems/dija-ferreira-home/assets/icon.svg.
 */
function dija_favicon_tags(): void {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" href="%s" sizes="32x32">' . "\n", esc_url( get_theme_file_uri( 'assets/favicon.ico' ) ) );
	printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_theme_file_uri( 'assets/icon.svg' ) ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/apple-touch-icon.png' ) ) );
}
add_action( 'wp_head', 'dija_favicon_tags' );
add_action( 'admin_head', 'dija_favicon_tags' ); // a aba do painel também mostra o ícone
add_action( 'login_head', 'dija_favicon_tags' );

/**
 * Pedidos diretos a /favicon.ico (feitos por alguns navegadores e pelo Google)
 * recebem o ícone do tema, em vez do logotipo padrão do WordPress.
 * Só vale quando não existe um arquivo favicon.ico físico na raiz do site.
 */
add_action( 'do_favicon', function () {
	if ( ! has_site_icon() ) {
		wp_safe_redirect( get_theme_file_uri( 'assets/favicon.ico' ), 302 );
		exit;
	}
}, 1 ); // antes da função padrão do WordPress (prioridade 10), que enviaria o logotipo dele
