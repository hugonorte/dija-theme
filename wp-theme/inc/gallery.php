<?php
/**
 * Galeria de fotos de eventos (seção "Fotos" da página /eventos).
 *
 * Painel: Eventos → Fotos (uma foto por item, enviada pela Biblioteca de
 * Mídia), Eventos → Eventos das fotos e Eventos → Tags das fotos.
 * Só APIs nativas: register_post_type, register_taxonomy, post meta.
 *
 * Filtros na URL (funcionam sem JavaScript):
 *   ?foto-ano=2026&foto-evento=<slug>&foto-tag=<slug>&fotos-pagina=2
 * Os nomes têm prefixo para não colidir com variáveis do WordPress
 * ("year", "tag" e "evento" já são usados por ele).
 */

defined( 'ABSPATH' ) || exit;

const DIJA_FOTOS_PER_PAGE = 24;

/* -------------------------------------------------------------------------
 * Registro
 * ---------------------------------------------------------------------- */

add_action( 'init', function () {
	register_post_type( 'foto', array(
		'labels'             => array(
			'name'                  => 'Fotos',
			'singular_name'         => 'Foto',
			'menu_name'             => 'Fotos',
			'all_items'             => 'Fotos da galeria',
			'add_new'               => 'Adicionar foto',
			'add_new_item'          => 'Adicionar foto',
			'edit_item'             => 'Editar foto',
			'not_found'             => 'Nenhuma foto cadastrada.',
			'featured_image'        => 'Foto',
			'set_featured_image'    => 'Enviar ou escolher a foto',
			'remove_featured_image' => 'Remover foto',
			'use_featured_image'    => 'Usar esta foto',
		),
		'public'             => false,
		'show_ui'            => true,
		'show_in_menu'       => 'edit.php?post_type=evento', // submenu de "Eventos"
		'show_in_rest'       => true,
		'supports'           => array( 'thumbnail' ),      // título é gerado sozinho
		'menu_icon'          => 'dashicons-format-gallery',
	) );

	register_taxonomy( 'foto_evento', 'foto', array(
		'labels'            => array(
			'name'          => 'Eventos das fotos',
			'singular_name' => 'Evento da foto',
			'menu_name'     => 'Eventos das fotos',
			'all_items'     => 'Todos os eventos das fotos',
			'edit_item'     => 'Editar evento',
			'update_item'   => 'Atualizar evento',
			'add_new_item'  => 'Adicionar evento',
			'new_item_name' => 'Nome do evento',
			'not_found'     => 'Nenhum evento cadastrado.',
			'back_to_items' => '← Voltar aos eventos das fotos',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'hierarchical'      => true, // seleção por ID no seletor de opção única
		'rewrite'           => false,
		'meta_box_cb'       => fn( WP_Post $post ) => dija_render_single_term_box( $post, 'foto_evento', 'Nenhum', 'Cadastrar ou editar eventos das fotos' ),
	) );

	register_taxonomy( 'foto_tag', 'foto', array(
		'labels'            => array(
			'name'                       => 'Tags das fotos',
			'singular_name'              => 'Tag',
			'menu_name'                  => 'Tags das fotos',
			'add_new_item'               => 'Adicionar tag',
			'separate_items_with_commas' => 'Separe as tags com vírgulas (ex.: casamento, forró, Recife)',
			'choose_from_most_used'      => 'Escolher entre as mais usadas',
			'not_found'                  => 'Nenhuma tag.',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'hierarchical'      => false, // caixa nativa de tags, com autocompletar
		'rewrite'           => false,
	) );
} );

/** As telas das listas ficam como submenus de "Eventos". */
add_action( 'admin_menu', function () {
	add_submenu_page( 'edit.php?post_type=evento', 'Eventos das fotos', 'Eventos das fotos', 'manage_categories', 'edit-tags.php?taxonomy=foto_evento&post_type=foto' );
	add_submenu_page( 'edit.php?post_type=evento', 'Tags das fotos', 'Tags das fotos', 'manage_categories', 'edit-tags.php?taxonomy=foto_tag&post_type=foto' );
} );

/** Mantém o menu "Eventos" aberto nas telas dessas listas. */
add_filter( 'parent_file', function ( $parent ) {
	$screen = get_current_screen();
	if ( $screen && in_array( $screen->taxonomy, array( 'foto_evento', 'foto_tag' ), true ) ) {
		return 'edit.php?post_type=evento';
	}
	return $parent;
} );

/** Mês e título gerados no salvamento: "Festival de Forró · 11/2026". */
add_filter( 'wp_insert_post_data', function ( array $data, array $postarr ) {
	if ( 'foto' !== $data['post_type'] || ! dija_fields_request_is_valid( (int) ( $postarr['ID'] ?? 0 ) ) ) {
		return $data;
	}
	$month = isset( $_POST['dija_fields']['data'] ) ? sanitize_text_field( wp_unslash( $_POST['dija_fields']['data'] ) ) : '';
	$ids   = isset( $_POST['tax_input']['foto_evento'] ) ? array_filter( array_map( 'absint', (array) wp_unslash( $_POST['tax_input']['foto_evento'] ) ) ) : array();
	$term  = $ids ? get_term( (int) reset( $ids ), 'foto_evento' ) : null;
	$parts = array_filter( array(
		$term instanceof WP_Term ? $term->name : 'Foto',
		preg_match( '/^(\d{4})-(\d{2})$/', $month, $m ) ? $m[2] . '/' . $m[1] : '',
	) );
	$data['post_title'] = implode( ' · ', $parts );
	return $data;
}, 10, 2 );

/** Foto sem mês informado recebe o mês atual (a ordem da galeria depende disso). */
add_action( 'save_post_foto', function ( int $post_id ) {
	if ( wp_is_post_revision( $post_id ) || '' !== (string) get_post_meta( $post_id, 'dija_data', true ) ) {
		return;
	}
	if ( 'auto-draft' !== get_post_status( $post_id ) ) {
		update_post_meta( $post_id, 'dija_data', wp_date( 'Y-m' ) );
	}
}, 20 );

/* -------------------------------------------------------------------------
 * Colunas da lista "Fotos da galeria"
 * ---------------------------------------------------------------------- */

add_filter( 'manage_foto_posts_columns', function ( array $cols ) {
	return array(
		'cb'                      => $cols['cb'],
		'foto'                    => 'Foto',
		'title'                   => 'Título',
		'taxonomy-foto_evento'    => 'Evento',
		'data_evento'             => 'Mês/ano',
		'taxonomy-foto_tag'       => 'Tags',
	);
} );
add_action( 'manage_foto_posts_custom_column', function ( string $col, int $post_id ) {
	if ( 'foto' === $col ) {
		echo has_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, array( 80, 60 ) ) : '<span aria-hidden="true">—</span>';
	}
	if ( 'data_evento' === $col ) {
		echo esc_html( dija_foto_badge( $post_id ) );
	}
}, 10, 2 );
add_filter( 'manage_edit-foto_sortable_columns', fn( array $cols ) => $cols + array( 'data_evento' => 'data_evento' ) );
add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( is_admin() && $q->is_main_query() && 'foto' === $q->get( 'post_type' ) ) {
		$q->set( 'meta_key', 'dija_data' );
		$q->set( 'orderby', array( 'meta_value' => 'data_evento' === $q->get( 'orderby' ) && 'asc' === strtolower( (string) $q->get( 'order' ) ) ? 'ASC' : 'DESC', 'date' => 'DESC' ) );
	}
} );

/* -------------------------------------------------------------------------
 * Consultas e apoio aos templates
 * ---------------------------------------------------------------------- */

/** "2026-11" → "NOV 2026" (selo da foto). */
function dija_foto_badge( int $post_id ): string {
	$months = array( 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' );
	$value  = (string) get_post_meta( $post_id, 'dija_data', true );
	if ( ! preg_match( '/^(\d{4})-(\d{2})$/', $value, $m ) ) {
		return '';
	}
	return $months[ (int) $m[2] - 1 ] . ' ' . $m[1];
}

/** Filtros pedidos na URL, já validados. */
function dija_foto_filters(): array {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- filtros públicos de leitura (GET).
	$year  = isset( $_GET['foto-ano'] ) ? (string) absint( $_GET['foto-ano'] ) : '';
	$event = isset( $_GET['foto-evento'] ) ? sanitize_title( wp_unslash( $_GET['foto-evento'] ) ) : '';
	$tag   = isset( $_GET['foto-tag'] ) ? sanitize_title( wp_unslash( $_GET['foto-tag'] ) ) : '';
	$page  = isset( $_GET['fotos-pagina'] ) ? max( 1, absint( $_GET['fotos-pagina'] ) ) : 1;
	// phpcs:enable
	return array(
		'ano'    => 4 === strlen( $year ) ? $year : '',
		'evento' => $event && term_exists( $event, 'foto_evento' ) ? $event : '',
		'tag'    => $tag && term_exists( $tag, 'foto_tag' ) ? $tag : '',
		'pagina' => $page,
	);
}

/** Fotos publicadas, da mais recente para a mais antiga, com os filtros. */
function dija_query_fotos( array $f ): WP_Query {
	$args = array(
		'post_type'      => 'foto',
		'post_status'    => 'publish',
		'posts_per_page' => DIJA_FOTOS_PER_PAGE,
		'paged'          => $f['pagina'],
		'meta_key'       => 'dija_data',
		'orderby'        => array( 'meta_value' => 'DESC', 'date' => 'DESC' ),
		'meta_query'     => array( array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ) ),
		'tax_query'      => array(),
	);
	if ( $f['ano'] ) {
		$args['meta_query'][] = array( 'key' => 'dija_data', 'value' => '^' . $f['ano'] . '-', 'compare' => 'REGEXP' );
	}
	if ( $f['evento'] ) {
		$args['tax_query'][] = array( 'taxonomy' => 'foto_evento', 'field' => 'slug', 'terms' => $f['evento'] );
	}
	if ( $f['tag'] ) {
		$args['tax_query'][] = array( 'taxonomy' => 'foto_tag', 'field' => 'slug', 'terms' => $f['tag'] );
	}
	return new WP_Query( $args );
}

/** Anos que têm fotos publicadas, do mais recente ao mais antigo. */
function dija_foto_years(): array {
	global $wpdb;
	$years = $wpdb->get_col( $wpdb->prepare(
		"SELECT DISTINCT LEFT(pm.meta_value, 4) FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key = %s AND p.post_type = %s AND p.post_status = 'publish'
		 ORDER BY 1 DESC",
		'dija_data',
		'foto'
	) );
	return array_values( array_filter( $years, fn( $y ) => preg_match( '/^\d{4}$/', (string) $y ) ) );
}

/** URL da galeria com filtros (mantém o usuário na seção #fotos). */
function dija_fotos_url( array $f, array $override = array() ): string {
	$f    = array_merge( $f, $override );
	$args = array_filter( array(
		'foto-ano'     => $f['ano'],
		'foto-evento'  => $f['evento'],
		'foto-tag'     => $f['tag'],
		'fotos-pagina' => $f['pagina'] > 1 ? $f['pagina'] : '',
	) );
	return add_query_arg( $args, get_post_type_archive_link( 'evento' ) ) . '#fotos';
}
