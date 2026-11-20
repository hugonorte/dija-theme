<?php
/**
 * Seção "Fotos" (fim da página /eventos): galeria com filtros por ano,
 * evento e tag, sempre da foto mais recente para a mais antiga.
 */

defined( 'ABSPATH' ) || exit;

$dija_f      = dija_foto_filters();
$dija_fotos  = dija_query_fotos( $dija_f );
$dija_years  = dija_foto_years();
$dija_events = get_terms( array( 'taxonomy' => 'foto_evento', 'hide_empty' => true ) );
$dija_tags   = get_terms( array( 'taxonomy' => 'foto_tag', 'hide_empty' => true, 'orderby' => 'name' ) );
$dija_has_filter = $dija_f['ano'] || $dija_f['evento'] || $dija_f['tag'];

// Sem nenhuma foto cadastrada, a seção não aparece.
if ( ! $dija_fotos->have_posts() && ! $dija_has_filter ) {
	return;
}
?>
<section class="sec-fotos" id="fotos">
	<h2 class="fotos-title h1 grunge"><span class="ico ico-rays" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'fotos_title' ) ); ?><span class="ico ico-rays is-flipped" aria-hidden="true"></span></h2>
	<?php if ( dija_mod( 'fotos_intro' ) ) : ?>
		<p class="fotos-intro t1"><?php echo esc_html( dija_mod( 'fotos_intro' ) ); ?></p>
	<?php endif; ?>

	<form class="fotos-filtros" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'evento' ) ); ?>#fotos" data-fotos-filtros>
		<label class="fotos-filtro">
			<span>Ano</span>
			<select name="foto-ano">
				<option value="">Todos</option>
				<?php foreach ( $dija_years as $dija_y ) : ?>
					<option value="<?php echo esc_attr( $dija_y ); ?>"<?php selected( $dija_f['ano'], $dija_y ); ?>><?php echo esc_html( $dija_y ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fotos-filtro">
			<span>Evento</span>
			<select name="foto-evento">
				<option value="">Todos</option>
				<?php foreach ( is_array( $dija_events ) ? $dija_events : array() as $dija_t ) : ?>
					<option value="<?php echo esc_attr( $dija_t->slug ); ?>"<?php selected( $dija_f['evento'], $dija_t->slug ); ?>><?php echo esc_html( $dija_t->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<label class="fotos-filtro">
			<span>Tag</span>
			<select name="foto-tag">
				<option value="">Todas</option>
				<?php foreach ( is_array( $dija_tags ) ? $dija_tags : array() as $dija_t ) : ?>
					<option value="<?php echo esc_attr( $dija_t->slug ); ?>"<?php selected( $dija_f['tag'], $dija_t->slug ); ?>><?php echo esc_html( $dija_t->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</label>
		<button class="fotos-filtrar brush" type="submit">FILTRAR</button>
		<?php if ( $dija_has_filter ) : ?>
			<a class="fotos-limpar" href="<?php echo esc_url( get_post_type_archive_link( 'evento' ) . '#fotos' ); ?>">Limpar filtros</a>
		<?php endif; ?>
	</form>

	<?php if ( $dija_fotos->have_posts() ) : ?>
		<p class="fotos-count" role="status"><?php echo esc_html( sprintf( _n( '%d foto', '%d fotos', (int) $dija_fotos->found_posts, 'dija-theme' ), (int) $dija_fotos->found_posts ) ); ?></p>
		<ul class="fotos-lista">
			<?php while ( $dija_fotos->have_posts() ) : $dija_fotos->the_post();
				$dija_id     = get_the_ID();
				$dija_badge  = dija_foto_badge( $dija_id );
				$dija_ev     = get_the_terms( $dija_id, 'foto_evento' );
				$dija_ev     = ( $dija_ev && ! is_wp_error( $dija_ev ) ) ? $dija_ev[0]->name : '';
				$dija_thumb  = (int) get_post_thumbnail_id();
				$dija_alt    = trim( (string) get_post_meta( $dija_thumb, '_wp_attachment_image_alt', true ) ) ?: trim( 'Foto ' . ( $dija_ev ? 'de ' . $dija_ev : 'de evento' ) . ( $dija_badge ? ', ' . strtolower( $dija_badge ) : '' ) );
				$dija_full   = wp_get_attachment_image_url( $dija_thumb, 'large' ); ?>
				<li class="foto">
					<a class="foto-link" href="<?php echo esc_url( (string) $dija_full ); ?>" data-foto-full data-foto-caption="<?php echo esc_attr( trim( $dija_ev . ( $dija_badge ? ' · ' . $dija_badge : '' ), ' ·' ) ); ?>">
						<?php echo wp_get_attachment_image( $dija_thumb, 'medium_large', false, array( 'alt' => $dija_alt, 'loading' => 'lazy', 'sizes' => '(max-width: 767px) 50vw, 340px' ) ); ?>
						<?php if ( $dija_badge ) : ?>
							<span class="foto-badge"><?php echo esc_html( $dija_badge ); ?></span>
						<?php endif; ?>
					</a>
					<?php if ( $dija_ev ) : ?>
						<p class="foto-evento"><?php echo esc_html( $dija_ev ); ?></p>
					<?php endif; ?>
				</li>
			<?php endwhile; wp_reset_postdata(); ?>
		</ul>

		<?php if ( $dija_fotos->max_num_pages > 1 ) : ?>
			<nav class="fotos-paginas" aria-label="Páginas da galeria">
				<?php for ( $dija_p = 1; $dija_p <= $dija_fotos->max_num_pages; $dija_p++ ) : ?>
					<a href="<?php echo esc_url( dija_fotos_url( $dija_f, array( 'pagina' => $dija_p ) ) ); ?>"<?php echo $dija_p === $dija_f['pagina'] ? ' aria-current="page" class="is-current"' : ''; ?>><?php echo (int) $dija_p; ?></a>
				<?php endfor; ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="fotos-vazio"><?php echo esc_html( dija_mod( 'fotos_empty' ) ); ?></p>
	<?php endif; ?>

	<dialog class="foto-zoom" data-foto-zoom aria-label="Foto ampliada">
		<button class="foto-zoom-fechar" type="button" data-foto-close aria-label="Fechar">×</button>
		<img src="" alt="">
		<p class="foto-zoom-legenda"></p>
	</dialog>
</section>
