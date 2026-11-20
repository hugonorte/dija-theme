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

/** Situação da turma: define em qual seção da página /turmas ela aparece. */
function dija_turma_status(): array {
	return array(
		'andamento' => 'Em andamento',
		'proxima'   => 'Próxima turma (ainda vai começar)',
	);
}

/** Dias da semana, na ordem em que aparecem no site. */
function dija_weekdays(): array {
	return array(
		'seg' => 'SEGUNDA',
		'ter' => 'TERÇA',
		'qua' => 'QUARTA',
		'qui' => 'QUINTA',
		'sex' => 'SEXTA',
		'sab' => 'SÁBADO',
		'dom' => 'DOMINGO',
	);
}

/**
 * Converte qualquer forma de dia ("seg", "Segunda", "SEGUNDA-FEIRA", "Sábado")
 * na chave usada pelo tema. Mantém compatíveis as turmas antigas, em que o
 * dia era texto livre.
 */
function dija_weekday_key( string $value ): string {
	$key = substr( strtolower( remove_accents( trim( $value ) ) ), 0, 3 );
	return array_key_exists( $key, dija_weekdays() ) ? $key : '';
}

function dija_weekday_label( string $value ): string {
	$key = dija_weekday_key( $value );
	return $key ? dija_weekdays()[ $key ] : strtoupper( $value );
}

/** Ritmos / estilos de dança (várias opções por turma). */
function dija_ritmos(): array {
	return array(
		'samba'                   => 'SAMBA',
		'forro'                   => 'FORRÓ',
		'danca-de-salao'          => 'DANÇA DE SALÃO',
		'soltinho'                => 'SOLTINHO',
		'zouk'                    => 'ZOUK',
		'bolero'                  => 'BOLERO',
		'carimbo'                 => 'CARIMBÓ',
		'tango'                   => 'TANGO',
		'kizomba'                 => 'KIZOMBA',
		'sertanejo-universitario' => 'SERTANEJO UNIVERSITÁRIO',
		'bachata'                 => 'BACHATA',
	);
}

/** Níveis (uma opção por turma). */
function dija_niveis(): array {
	return array(
		'iniciante'     => 'INICIANTE',
		'intermediario' => 'INTERMEDIÁRIO',
		'avancado'      => 'AVANÇADO',
		'infantil'      => 'INFANTIL',
	);
}

/**
 * Encontra a opção correspondente a um texto ("Forró", "FORRÓ", "forro"...).
 * Usado para reconhecer turmas antigas, em que o campo era texto livre.
 */
function dija_option_key( string $text, array $options ): string {
	$slug = sanitize_title( remove_accents( trim( $text ) ) );
	if ( '' === $slug ) {
		return '';
	}
	foreach ( $options as $key => $label ) {
		if ( $slug === $key || $slug === sanitize_title( remove_accents( $label ) ) ) {
			return (string) $key;
		}
	}
	return '';
}

/** Chaves de ritmo salvas ("samba,forro"), na ordem da lista. Vazio se houver valor fora da lista. */
function dija_ritmo_keys( string $raw ): array {
	if ( '' === trim( $raw ) ) {
		return array();
	}
	$keys = array();
	foreach ( preg_split( '/\s*,\s*/', trim( $raw ) ) as $part ) {
		$key = dija_option_key( $part, dija_ritmos() );
		if ( '' === $key ) {
			return array(); // valor antigo, fora da lista
		}
		$keys[] = $key;
	}
	return array_values( array_intersect( array_keys( dija_ritmos() ), $keys ) );
}

/** "A", "A e B", "A, B e C". */
function dija_join_e( array $items ): string {
	$items = array_values( array_filter( $items, 'strlen' ) );
	$last  = array_pop( $items );
	return $items ? implode( ', ', $items ) . ' e ' . $last : (string) $last;
}

/** Ritmos da turma para exibir: "SAMBA e FORRÓ" (ou o texto antigo, se fora da lista). */
function dija_turma_ritmo( int $post_id ): string {
	$raw  = (string) get_post_meta( $post_id, 'dija_ritmo', true );
	$keys = dija_ritmo_keys( $raw );
	return $keys ? dija_join_e( array_map( fn( $k ) => dija_ritmos()[ $k ], $keys ) ) : $raw;
}

/** Nível da turma para exibir (ou o texto antigo, se fora da lista). */
function dija_turma_nivel( int $post_id ): string {
	$raw = (string) get_post_meta( $post_id, 'dija_nivel', true );
	$key = dija_option_key( $raw, dija_niveis() );
	return $key ? dija_niveis()[ $key ] : $raw;
}

/**
 * Campos de cada tipo.
 * type: text | textarea | select | url ; max: limite de caracteres sugerido.
 */
function dija_fields( string $post_type ): array {
	if ( 'turma' === $post_type ) {
		return array(
			'status'  => array( 'label' => 'Situação', 'type' => 'select', 'options' => dija_turma_status(), 'default' => 'andamento', 'help' => '“Em andamento” aparece na página inicial e na primeira parte de /turmas. “Próxima turma” aparece em “Próximas turmas”, com a imagem de divulgação.' ),
			'dia'     => array( 'label' => 'Dia da semana', 'type' => 'select', 'options' => dija_weekdays(), 'default' => 'seg' ),
			'horario' => array( 'label' => 'Horário', 'type' => 'text', 'max' => 16, 'placeholder' => '19h às 20h', 'help' => 'Comece pela hora de início: as turmas do mesmo dia são ordenadas por ela.' ),
			'ritmo'   => array( 'label' => 'Ritmo / estilo de dança', 'type' => 'checkboxes', 'options' => dija_ritmos(), 'help' => 'Marque um ou mais. No site, aparecem juntos: “SAMBA e FORRÓ”.' ),
			'nivel'   => array( 'label' => 'Nível', 'type' => 'select', 'options' => dija_niveis(), 'choose' => 'Selecione o nível' ),
			'inicio'  => array( 'label' => 'Início (só próximas turmas)', 'type' => 'text', 'max' => 24, 'placeholder' => 'Início em 05/01', 'help' => 'Opcional.' ),
			'cor'     => array( 'label' => 'Cor do card', 'type' => 'select', 'options' => dija_turma_colors(), 'default' => 'red' ),
			'icone'   => array( 'label' => 'Ícone', 'type' => 'select', 'options' => dija_turma_icons(), 'default' => 'sun' ),
		);
	}
	if ( 'foto' === $post_type ) {
		return array(
			'data' => array( 'label' => 'Mês e ano do evento', 'type' => 'month', 'help' => 'Obrigatório. Aparece no selo da foto e define a ordem da galeria (mais recentes primeiro).' ),
		);
	}
	if ( 'evento' === $post_type ) {
		return array(
			'descricao'   => array( 'label' => 'Descrição', 'type' => 'textarea', 'max' => 220, 'placeholder' => 'Apresentações culturais, festivais, aniversários…' ),
			'botao_texto' => array( 'label' => 'Texto do botão', 'type' => 'text', 'max' => 22, 'placeholder' => 'SAIBA MAIS', 'default' => 'SAIBA MAIS' ),
			'botao_link'  => array( 'label' => 'Link do botão', 'type' => 'url', 'placeholder' => '/eventos/', 'default' => '/eventos/', 'help' => 'Endereço completo ou caminho do site. Padrão: /eventos/ (página de eventos).' ),
		);
	}
	return array();
}

/** Valor de um campo, com padrão. */
function dija_field( int $post_id, string $key ): string {
	$post_type = get_post_type( $post_id );
	$def       = dija_fields( (string) $post_type )[ $key ] ?? array();
	$value     = (string) get_post_meta( $post_id, 'dija_' . $key, true );
	if ( 'dia' === $key && '' !== $value ) {
		$value = dija_weekday_key( $value ) ?: $value; // turmas antigas: dia em texto livre
	}
	if ( 'turma' === $post_type && 'ritmo' === $key && '' !== $value ) {
		$value = implode( ',', dija_ritmo_keys( $value ) ) ?: $value; // turmas antigas: texto livre
	}
	if ( 'turma' === $post_type && 'nivel' === $key && '' !== $value ) {
		$value = dija_option_key( $value, dija_niveis() ) ?: $value;
	}
	return '' !== $value ? $value : (string) ( $def['default'] ?? '' );
}

function dija_sanitize_field( array $def, $raw ): string {
	if ( 'checkboxes' === $def['type'] ) {
		// Formulário envia lista; REST pode enviar "samba,forro".
		$list = is_array( $raw ) ? wp_unslash( $raw ) : explode( ',', (string) $raw );
		$keys = array_map( fn( $v ) => sanitize_key( (string) $v ), $list );
		return implode( ',', array_values( array_intersect( array_keys( $def['options'] ), $keys ) ) );
	}
	$raw = is_string( $raw ) ? wp_unslash( $raw ) : '';
	switch ( $def['type'] ) {
		case 'textarea':
			return sanitize_textarea_field( $raw );
		case 'select':
			return array_key_exists( $raw, $def['options'] ) ? $raw : (string) ( $def['default'] ?? '' );
		case 'month':
			$raw = trim( $raw );
			return preg_match( '/^\d{4}-(0[1-9]|1[0-2])$/', $raw ) ? $raw : '';
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
			'attributes'    => 'Atributos',
			'featured_image'        => 'Imagem de divulgação (16:9)',
			'set_featured_image'    => 'Escolher imagem de divulgação',
			'remove_featured_image' => 'Remover imagem de divulgação',
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
		'supports'      => array( 'thumbnail', 'page-attributes' ),
	) );

	// Locais onde as turmas acontecem (Turmas → Locais).
	register_taxonomy( 'local', 'turma', array(
		'labels'            => array(
			'name'          => 'Locais',
			'singular_name' => 'Local',
			'menu_name'     => 'Locais',
			'all_items'     => 'Todos os locais',
			'edit_item'     => 'Editar local',
			'update_item'   => 'Atualizar local',
			'add_new_item'  => 'Adicionar local',
			'new_item_name' => 'Nome do novo local',
			'not_found'     => 'Nenhum local cadastrado.',
			'back_to_items' => '← Voltar aos locais',
		),
		'public'            => false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'hierarchical'      => true, // seleção por ID (usada pelo seletor de opção única)
		'rewrite'           => false,
		'meta_box_cb'       => 'dija_render_local_box',
	) );
	register_term_meta( 'local', 'dija_endereco', array(
		'type' => 'string', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	register_term_meta( 'local', 'dija_ordem', array(
		'type' => 'integer', 'single' => true, 'show_in_rest' => true,
		'sanitize_callback' => 'absint',
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
			'attributes'    => 'Atributos',
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

	foreach ( array( 'turma', 'evento', 'foto' ) as $post_type ) {
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

/**
 * Turmas de uma situação ("andamento" ou "proxima"), ordenadas por dia da
 * semana e hora de início. Turmas antigas, sem situação salva, contam como
 * "andamento".
 *
 * @return WP_Post[]
 */
function dija_get_turmas( string $status ): array {
	$turmas = array_filter(
		dija_get_items( 'turma', 200 ),
		fn( WP_Post $t ) => dija_field( $t->ID, 'status' ) === $status
	);
	usort( $turmas, fn( WP_Post $a, WP_Post $b ) => dija_turma_sort_key( $a ) <=> dija_turma_sort_key( $b ) );
	return array_values( $turmas );
}

/** Chave de ordenação: [posição do dia, minutos do início, ordem manual]. */
function dija_turma_sort_key( WP_Post $t ): array {
	$days = array_keys( dija_weekdays() );
	$pos  = array_search( dija_field( $t->ID, 'dia' ), $days, true );
	return array( false === $pos ? 99 : $pos, dija_start_minutes( dija_field( $t->ID, 'horario' ) ), (int) $t->menu_order );
}

/** "19h às 20h" → 1140; "8:30 - 10h" → 510; sem hora reconhecível → fim da lista. */
function dija_start_minutes( string $horario ): int {
	if ( preg_match( '/(\d{1,2})\s*(?:h|:)\s*(\d{2})?/i', $horario, $m ) ) {
		return (int) $m[1] * 60 + (int) ( $m[2] ?? 0 );
	}
	return 24 * 60;
}

/**
 * Agrupa turmas por local e, dentro de cada local, por dia da semana.
 * Locais na ordem do campo "Ordem" (depois por nome); turmas sem local
 * ficam num grupo "Local a definir", no fim.
 *
 * @param WP_Post[] $turmas Já ordenadas por dia/horário.
 * @return array<int, array{term: ?WP_Term, name: string, address: string, days: array<string, WP_Post[]>}>
 */
function dija_group_turmas( array $turmas ): array {
	$groups = array();
	foreach ( $turmas as $turma ) {
		$terms = get_the_terms( $turma, 'local' );
		$term  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
		$key   = $term ? $term->term_id : 0;
		if ( ! isset( $groups[ $key ] ) ) {
			$groups[ $key ] = array(
				'term'    => $term,
				'name'    => $term ? $term->name : 'Local a definir',
				'address' => $term ? (string) get_term_meta( $term->term_id, 'dija_endereco', true ) : '',
				'order'   => $term ? (int) get_term_meta( $term->term_id, 'dija_ordem', true ) : PHP_INT_MAX,
				'days'    => array(),
			);
		}
		$groups[ $key ]['days'][ dija_field( $turma->ID, 'dia' ) ][] = $turma;
	}
	uasort( $groups, fn( $a, $b ) => array( $a['order'], $a['name'] ) <=> array( $b['order'], $b['name'] ) );
	return array_values( $groups );
}

/** Link do Google Maps para um endereço. */
function dija_maps_url( string $address ): string {
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( preg_replace( '/\s+/', ' ', $address ) );
}

/* -------------------------------------------------------------------------
 * Tela de edição (meta box)
 * ---------------------------------------------------------------------- */

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'dija_turma_fields', 'Dados da turma', 'dija_render_fields_box', 'turma', 'normal', 'high' );
	add_meta_box( 'dija_evento_fields', 'Texto do evento', 'dija_render_fields_box', 'evento', 'normal', 'high' );
	add_meta_box( 'dija_foto_fields', 'Dados da foto', 'dija_render_fields_box', 'foto', 'normal', 'high' );
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
			case 'checkboxes':
				$checked = '' === $value ? array() : explode( ',', $value );
				// Campo vazio garante que desmarcar tudo também seja salvo.
				echo '<input type="hidden" name="dija_fields[' . esc_attr( $key ) . '][]" value="">';
				echo '<fieldset id="' . esc_attr( $id ) . '" style="display:flex;flex-wrap:wrap;gap:6px 22px;max-width:720px"><legend class="screen-reader-text">' . esc_html( $def['label'] ) . '</legend>';
				foreach ( $def['options'] as $opt => $label ) {
					echo '<label style="min-width:200px"><input type="checkbox" name="dija_fields[' . esc_attr( $key ) . '][]" value="' . esc_attr( $opt ) . '"' . checked( in_array( $opt, $checked, true ), true, false ) . '> ' . esc_html( $label ) . '</label>';
				}
				echo '</fieldset>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']"' . ( isset( $def['choose'] ) ? ' required' : '' ) . '>';
				if ( isset( $def['choose'] ) ) {
					echo '<option value=""' . selected( array_key_exists( $value, $def['options'] ), false, false ) . '>' . esc_html( $def['choose'] ) . '</option>';
				}
				foreach ( $def['options'] as $opt => $label ) {
					echo '<option value="' . esc_attr( $opt ) . '"' . selected( $value, $opt, false ) . '>' . esc_html( $label ) . '</option>';
				}
				echo '</select>';
				break;
			case 'month':
				echo '<input type="month" id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '" required>';
				break;
			default:
				echo '<input type="text" class="regular-text" id="' . esc_attr( $id ) . '" name="dija_fields[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '"' . $max . $ph . '>';
		}
		// Valor digitado antes das listas e que não corresponde a nenhuma opção.
		$stored = (string) get_post_meta( $post->ID, 'dija_' . $key, true );
		if ( isset( $def['options'] ) && '' !== $stored && ( 'checkboxes' === $def['type'] ? ! dija_ritmo_keys( $stored ) : ! array_key_exists( $value, $def['options'] ) ) ) {
			echo '<p class="description" style="color:#b32d2e"><strong>Valor antigo: “' . esc_html( $stored ) . '”.</strong> Ele continua aparecendo no site até você escolher uma opção da lista e atualizar.</p>';
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
		echo '<p class="description">Escolha o local na caixa “Locais”, ao lado (cadastre locais em Turmas → Locais). Turmas do mesmo dia são ordenadas pelo horário. Para próximas turmas, envie a divulgação em “Imagem de divulgação (16:9)”, também ao lado: ela entra no carrossel da página /turmas.</p>';
	}
}

function dija_fields_request_is_valid( int $post_id ): bool {
	return isset( $_POST['dija_fields_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dija_fields_nonce'] ) ), 'dija_save_fields' )
		&& ! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		&& current_user_can( 'edit_post', $post_id );
}

foreach ( array( 'turma', 'evento', 'foto' ) as $dija_post_type ) {
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
	$defs   = dija_fields( 'turma' );
	$input  = $_POST['dija_fields']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitizado abaixo.
	$dia    = dija_sanitize_field( $defs['dia'], $input['dia'] ?? '' );
	$hora   = dija_sanitize_field( $defs['horario'], $input['horario'] ?? '' );
	$ritmos = dija_ritmo_keys( dija_sanitize_field( $defs['ritmo'], $input['ritmo'] ?? array() ) );
	$nivel  = dija_sanitize_field( $defs['nivel'], $input['nivel'] ?? '' );
	$ritmo  = dija_join_e( array_map( fn( $k ) => dija_ritmos()[ $k ], $ritmos ) );
	$parts  = array_filter( array( dija_weekday_label( $dia ), $hora, trim( $ritmo . ' ' . ( dija_niveis()[ $nivel ] ?? '' ) ) ) );
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
		'status' => 'Situação',
		'taxonomy-local' => 'Local',
		'imagem' => 'Divulgação',
		'cor'    => 'Cor',
		'ordem'  => 'Ordem',
		'date'   => $cols['date'],
	);
} );
add_action( 'manage_turma_posts_custom_column', function ( string $col, int $post_id ) {
	if ( 'cor' === $col ) {
		echo esc_html( dija_turma_colors()[ dija_field( $post_id, 'cor' ) ] ?? '—' );
	}
	if ( 'status' === $col ) {
		echo 'proxima' === dija_field( $post_id, 'status' ) ? '<strong>Próxima</strong>' : 'Em andamento';
	}
	if ( 'imagem' === $col ) {
		echo has_post_thumbnail( $post_id ) ? get_the_post_thumbnail( $post_id, array( 80, 45 ) ) : '<span aria-hidden="true">—</span>';
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

/* -------------------------------------------------------------------------
 * Locais: seletor de opção única na turma + endereço e ordem no cadastro
 * ---------------------------------------------------------------------- */

/** Caixa "Locais" na edição da turma: um único local por turma. */
function dija_render_local_box( WP_Post $post ): void {
	dija_render_single_term_box( $post, 'local', 'Nenhum (local a definir)', 'Cadastrar ou editar locais' );
}

/**
 * Seletor de opção única para uma taxonomia hierárquica (envia IDs em
 * tax_input, como a caixa nativa de categorias).
 */
function dija_render_single_term_box( WP_Post $post, string $taxonomy, string $none_label, string $manage_label ): void {
	$terms   = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
	$current = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
	$current = $current && ! is_wp_error( $current ) ? (int) $current[0] : 0;
	$name    = 'tax_input[' . $taxonomy . '][]';

	// Campo vazio garante que "Nenhum" remova o termo salvo.
	echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="0">';
	if ( ! $terms || is_wp_error( $terms ) ) {
		echo '<p>Nenhum cadastrado ainda.</p>';
	} else {
		echo '<ul style="margin:0;max-height:240px;overflow:auto">';
		foreach ( $terms as $term ) {
			printf(
				'<li><label><input type="radio" name="%1$s" value="%2$d"%3$s> %4$s</label></li>',
				esc_attr( $name ),
				(int) $term->term_id,
				checked( $current, $term->term_id, false ),
				esc_html( $term->name )
			);
		}
		printf( '<li><label><input type="radio" name="%1$s" value="0"%2$s> <em>%3$s</em></label></li>', esc_attr( $name ), checked( $current, 0, false ), esc_html( $none_label ) );
		echo '</ul>';
	}
	$post_type = get_taxonomy( $taxonomy )->object_type[0] ?? '';
	printf( '<p><a href="%s">%s</a></p>', esc_url( admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=' . $post_type ) ), esc_html( $manage_label ) );
}

/** Campos extras do local: endereço e ordem. */
function dija_local_fields_html( ?WP_Term $term ): array {
	$address = $term ? (string) get_term_meta( $term->term_id, 'dija_endereco', true ) : '';
	$order   = $term ? (int) get_term_meta( $term->term_id, 'dija_ordem', true ) : 0;
	return array(
		'address' => '<textarea name="dija_endereco" id="dija_endereco" rows="3" class="large-text">' . esc_textarea( $address ) . '</textarea>'
			. '<p class="description">Endereço completo, como deve aparecer no site. Vira um link para o Google Maps.</p>',
		'order'   => '<input type="number" min="0" step="1" name="dija_ordem" id="dija_ordem" value="' . esc_attr( (string) $order ) . '" class="small-text">'
			. '<p class="description">Posição do local na página /turmas: números menores aparecem primeiro.</p>',
	);
}

add_action( 'local_add_form_fields', function () {
	$f = dija_local_fields_html( null );
	wp_nonce_field( 'dija_save_local', 'dija_local_nonce' );
	echo '<div class="form-field"><label for="dija_endereco">Endereço</label>' . $f['address'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- montado com esc_* acima.
	echo '<div class="form-field"><label for="dija_ordem">Ordem</label>' . $f['order'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
} );

add_action( 'local_edit_form_fields', function ( WP_Term $term ) {
	$f = dija_local_fields_html( $term );
	wp_nonce_field( 'dija_save_local', 'dija_local_nonce' );
	echo '<tr class="form-field"><th scope="row"><label for="dija_endereco">Endereço</label></th><td>' . $f['address'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<tr class="form-field"><th scope="row"><label for="dija_ordem">Ordem</label></th><td>' . $f['order'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
} );

function dija_save_local_fields( int $term_id ): void {
	if ( ! isset( $_POST['dija_local_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['dija_local_nonce'] ) ), 'dija_save_local' )
		|| ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	if ( isset( $_POST['dija_endereco'] ) ) {
		update_term_meta( $term_id, 'dija_endereco', sanitize_textarea_field( wp_unslash( $_POST['dija_endereco'] ) ) );
	}
	if ( isset( $_POST['dija_ordem'] ) ) {
		update_term_meta( $term_id, 'dija_ordem', absint( $_POST['dija_ordem'] ) );
	}
}
add_action( 'created_local', 'dija_save_local_fields' );
add_action( 'edited_local', 'dija_save_local_fields' );

/** Lista de locais: mostra endereço e ordem; esconde a coluna "Descrição", não usada. */
add_filter( 'manage_edit-local_columns', function ( array $cols ) {
	unset( $cols['description'], $cols['slug'] );
	$cols['endereco'] = 'Endereço';
	$cols['ordem']    = 'Ordem';
	return $cols;
} );
add_filter( 'manage_local_custom_column', function ( string $out, string $col, int $term_id ) {
	if ( 'endereco' === $col ) {
		return esc_html( (string) get_term_meta( $term_id, 'dija_endereco', true ) );
	}
	if ( 'ordem' === $col ) {
		return (string) (int) get_term_meta( $term_id, 'dija_ordem', true );
	}
	return $out;
}, 10, 3 );

/** Tela de Locais: o campo nativo "Descrição" não é usado pelo site. */
add_action( 'admin_head-edit-tags.php', 'dija_hide_local_description' );
add_action( 'admin_head-term.php', 'dija_hide_local_description' );
function dija_hide_local_description(): void {
	if ( 'local' === ( get_current_screen()->taxonomy ?? '' ) ) {
		echo '<style>.term-description-wrap{display:none}</style>';
	}
}
