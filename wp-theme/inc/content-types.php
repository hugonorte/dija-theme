<?php
/**
 * Tipos de conteúdo do site: Turmas e Eventos.
 *
 * Os campos usam formulários fixos (sem editor livre), para que a
 * professora edite o conteúdo sem conseguir quebrar o layout.
 * Só APIs nativas: register_post_type, register_post_meta, add_meta_box.
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Definição dos campos
 * ---------------------------------------------------------------------- */

function dija_turma_colors(): array {
	return array(
		'red'    => 'Vermelho',
		'teal'   => 'Verde-azulado',
		'purple' => 'Roxo',
		'ochre'  => 'Amarelo-ocre',
	);
}

function dija_turma_icons(): array {
	return array(
		'sun'      => 'Sol claro',
		'moon'     => 'Lua com estrelas',
		'star-big' => 'Estrela',
		'sun2'     => 'Sol escuro',
	);
}

/**
 * Campos de cada tipo.
 * type: text | textarea | select | url ; max: limite de caracteres sugerido.
 */
function dija_fields( string $post_type ): array {
	if ( 'turma' === $post_type ) {
		return array(
			'dia'       => array( 'label' => 'Dia da semana', 'type' => 'text', 'max' => 14, 'placeholder' => 'SEGUNDA', 'help' => 'Aparece em destaque no topo do card.' ),
			'horario'   => array( 'label' => 'Horário', 'type' => 'text', 'max' => 16, 'placeholder' => '19h às 20h' ),
			'ritmo'     => array( 'label' => 'Ritmo', 'type' => 'text', 'max' => 16, 'placeholder' => 'FORRÓ' ),
			'nivel'     => array( 'label' => 'Nível', 'type' => 'text', 'max' => 16, 'placeholder' => 'INICIANTE' ),
			'descricao' => array( 'label' => 'Descrição curta (opcional)', 'type' => 'textarea', 'max' => 140, 'help' => 'Mostrada só na página /turmas.' ),
			'cor'       => array( 'label' => 'Cor do card', 'type' => 'select', 'options' => dija_turma_colors(), 'default' => 'red' ),
			'icone'     => array( 'label' => 'Ícone', 'type' => 'select', 'options' => dija_turma_icons(), 'default' => 'sun' ),
		);
	}
	if ( 'evento' === $post_type ) {
		return array(
			'descricao'   => array( 'label' => 'Descrição', 'type' => 'textarea', 'max' => 220, 'placeholder' => 'Apresentações culturais, festivais, aniversários…' ),
			'botao_texto' => array( 'label' => 'Texto do botão', 'type' => 'text', 'max' => 22, 'placeholder' => 'SAIBA MAIS', 'default' => 'SAIBA MAIS' ),
			'botao_link'  => array( 'label' => 'Link do botão', 'type' => 'url', 'placeholder' => '/contato/', 'default' => '/contato/', 'help' => 'Endereço completo ou caminho do site, ex.: /contato/' ),
		);
	}
	return array();
}

/** Valor de um campo, com padrão. */
function dija_field( int $post_id, string $key ): string {
	$post_type = get_post_type( $post_id );
	$def       = dija_fields( (string) $post_type )[ $key ] ?? array();
	$value     = (string) get_post_meta( $post_id, 'dija_' . $key, true );
	return '' !== $value ? $value : (string) ( $def['default'] ?? '' );
}

function dija_sanitize_field( array $def, $raw ): string {
	$raw = is_string( $raw ) ? wp_unslash( $raw ) : '';
	switch ( $def['type'] ) {
		case 'textarea':
			return sanitize_textarea_field( $raw );
		case 'select':
			return array_key_exists( $raw, $def['options'] ) ? $raw : (string) ( $def['default'] ?? '' );
		case 'url':
			$raw = trim( $raw );
			return str_starts_with( $raw, '/' ) ? sanitize_text_field( $raw ) : esc_url_raw( $raw );
		default:
			return sanitize_text_field( $raw );
	}
}

/* -------------------------------------------------------------------------
 * Registro dos tipos e campos
 * ---------------------------------------------------------------------- */

function dija_register_content_types(): void {
	register_post_type( 'turma', array(
		'labels'        => array(
			'name'          => 'Turmas',
			'singular_name' => 'Turma',
			'menu_name'     => 'Turmas',
			'add_new'       => 'Adicionar turma',
			'add_new_item'  => 'Adicionar turma',
			'edit_item'     => 'Editar turma',
			'new_item'      => 'Nova turma',
			'all_items'     => 'Todas as turmas',
			'not_found'     => 'Nenhuma turma cadastrada.',
		),
		'public'        => true,
		'publicly_queryable' => true,
		'exclude_from_search' => true,
		'show_in_rest'  => true,
		'has_archive'   => 'turmas',
		'rewrite'       => array( 'slug' => 'turmas', 'with_front' => false ),
		'menu_icon'     => 'dashicons-calendar-alt',
		'menu_position' => 20,
		// Sem título/editor: o título é gerado a partir dos campos.
		'supports'      => array( 'page-attributes' ),
	) );

	register_post_type( 'evento', array(
		'labels'        => array(
			'name'               => 'Eventos',
			'singular_name'      => 'Evento',
			'menu_name'          => 'Eventos',
			'add_new'            => 'Adicionar evento',
			'add_new_item'       => 'Adicionar evento',
			'edit_item'          => 'Editar evento',
			'new_item'           => 'Novo evento',
			'all_items'          => 'Todos os eventos',
			'not_found'          => 'Nenhum evento cadastrado.',
			'featured_image'     => 'Ilustração do evento',
			'set_featured_image' => 'Escolher ilustração',
		),
		'public'        => true,
		'exclude_from_search' => true,
		'show_in_rest'  => true,
		'has_archive'   => 'eventos',
		'rewrite'       => array( 'slug' => 'eventos', 'with_front' => false ),
		'menu_icon'     => 'dashicons-tickets-alt',
		'menu_position' => 21,
		'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
	) );

	foreach ( array( 'turma', 'evento' ) as $post_type ) {
		foreach ( dija_fields( $post_type ) as $key => $def ) {
			register_post_meta( $post_type, 'dija_' . $key, array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => fn( $value ) => dija_sanitize_field( $def, $value ),
				'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
			) );
		}
	}
}
add_action( 'init', 'dija_register_content_types' );

/** Endereços /turmas e /eventos passam a existir ao ativar o tema. */
add_action( 'after_switch_theme', function () {
	dija_register_content_types();
	flush_rewrite_rules();
} );

/** Listas sempre na ordem definida no campo "Ordem", sem paginação. */
add_action( 'pre_get_posts', function ( WP_Query $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_post_type_archive( array( 'turma', 'evento' ) ) ) {
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'date' => 'ASC' ) );
		$q->set( 'posts_per_page', 50 );
	}
} );

/** Turmas e eventos não têm página própria: o endereço individual leva à lista. */
add_action( 'template_redirect', function () {
	if ( is_singular( array( 'turma', 'evento' ) ) ) {
		wp_safe_redirect( get_post_type_archive_link( get_post_type() ), 301 );
		exit;
	}
} );

/**
 * Consulta usada na home e nas páginas.
 *
 * @return WP_Post[]
 */
function dija_get_items( string $post_type, int $limit = 50 ): array {
	return get_posts( array(
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'no_found_rows'  => true,
	) );
}

/* -------------------------------------------------------------------------
 * Tela de edição (meta box)
 * ---------------------------------------------------------------------- */

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'dija_turma_fields', 'Dados da turma', 'dija_render_fields_box', 'turma', 'normal', 'high' );
	add_meta_box( 'dija_evento_fields', 'Texto do evento', 'dija_render_fields_box', 'evento', 'normal', 'high' );
} );

function dija_render_fields_box( WP_Post $post ): void {
	wp_nonce_field( 'dija_save_fields', 'dija_fields_nonce' );
	$fields = dija_fields( $post->post_type );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $fields as $key => $def ) {
		$id    = 'dija_' . $key;
		$value = dija_field( $post->ID, $key );
		echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $def['label'] ) . '</label></th><td>';
		$max = isset( $def['max'] ) ? ' maxlength="' . (int) $def['max'] . '"' : '';
		$ph  = isset( $def['placeholder'] ) ? ' placeholder="' . esc_attr( $def['placeholder'] ) . '"' : '';
		switch ( $def['type'] ) {
			case 'textarea':
				echo '<textarea class="large-text" rows="3" id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']"' . $max . $ph . '>' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']">';
				foreach ( $def['options'] as $opt => $label ) {
					echo '<option value="' . esc_attr( $opt ) . '"' . selected( $value, $opt, false ) . '>' . esc_html( $label ) . '</option>';
				}
				echo '</select>';
				break;
			default:
				echo '<input type="text" class="regular-text" id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '"' . $max . $ph . '>';
		}
		if ( isset( $def['max'] ) ) {
			echo '<p class="description">' . esc_html( ( $def['help'] ?? '' ) . ' Até ' . (int) $def['max'] . ' caracteres.' ) . '</p>';
		} elseif ( isset( $def['help'] ) ) {
			echo '<p class="description">' . esc_html( $def['help'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	if ( 'evento' === $post->post_type ) {
		echo '<p class="description">O título do evento (acima) aparece como chamada, ex.: “LEVAMOS A DANÇA ATÉ VOCÊ!”. A ilustração é a “Imagem destacada”, na lateral. Sem ilustração, o site usa a arte padrão dos músicos.</p>';
	}
	if ( 'turma' === $post->post_type ) {
		echo '<p class="description">Use o campo “Ordem” (caixa “Atributos”) para definir a posição do card: números menores aparecem primeiro.</p>';
	}
}

function dija_fields_request_is_valid( int $post_id ): bool {
	return isset( $_POST['dija_fields_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dija_fields_nonce'] ) ), 'dija_save_fields' )
		&& ! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		&& current_user_can( 'edit_post', $post_id );
}

foreach ( array( 'turma', 'evento' ) as $dija_post_type ) {
	add_action( 'save_post_' . $dija_post_type, function ( int $post_id, WP_Post $post ) {
		if ( ! dija_fields_request_is_valid( $post_id ) || empty( $_POST['dija_fields'] ) || ! is_array( $_POST['dija_fields'] ) ) {
			return;
		}
		$input = $_POST['dija_fields']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitizado campo a campo abaixo.
		foreach ( dija_fields( $post->post_type ) as $key => $def ) {
			if ( array_key_exists( $key, $input ) ) {
				update_post_meta( $post_id, 'dija_' . $key, dija_sanitize_field( $def, $input[ $key ] ) );
			}
		}
	}, 10, 2 );
}

/** Título automático da turma (aparece na lista do painel). */
add_filter( 'wp_insert_post_data', function ( array $data, array $postarr ) {
	if ( 'turma' !== $data['post_type'] || empty( $_POST['dija_fields'] ) || ! is_array( $_POST['dija_fields'] ) ) {
		return $data;
	}
	if ( ! dija_fields_request_is_valid( (int) ( $postarr['ID'] ?? 0 ) ) ) {
		return $data;
	}
	$f     = array_map( fn( $v ) => sanitize_text_field( wp_unslash( (string) $v ) ), $_POST['dija_fields'] );
	$parts = array_filter( array( $f['dia'] ?? '', $f['horario'] ?? '', trim( ( $f['ritmo'] ?? '' ) . ' ' . ( $f['nivel'] ?? '' ) ) ) );
	if ( $parts ) {
		$data['post_title'] = implode( ' · ', $parts );
	}
	return $data;
}, 10, 2 );

/* -------------------------------------------------------------------------
 * Colunas na lista do painel
 * ---------------------------------------------------------------------- */

add_filter( 'manage_turma_posts_columns', function ( array $cols ) {
	return array(
		'cb'     => $cols['cb'],
		'title'  => 'Turma',
		'cor'    => 'Cor',
		'ordem'  => 'Ordem',
		'date'   => $cols['date'],
	);
} );
add_action( 'manage_turma_posts_custom_column', function ( string $col, int $post_id ) {
	if ( 'cor' === $col ) {
		echo esc_html( dija_turma_colors()[ dija_field( $post_id, 'cor' ) ] ?? '—' );
	}
	if ( 'ordem' === $col ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}, 10, 2 );

add_filter( 'manage_evento_posts_columns', function ( array $cols ) {
	return array(
		'cb'     => $cols['cb'],
		'imagem' => 'Ilustração',
		'title'  => 'Evento',
		'ordem'  => 'Ordem',
		'date'   => $cols['date'],
	);
} );
add_action( 'manage_evento_posts_custom_column', function ( string $col, int $post_id ) {
	if ( 'imagem' === $col ) {
		echo has_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, array( 60, 60 ) ) : '<span aria-hidden="true">—</span>';
	}
	if ( 'ordem' === $col ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}, 10, 2 );
