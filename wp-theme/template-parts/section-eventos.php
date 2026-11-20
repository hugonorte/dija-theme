<?php
/**
 * Seção de eventos da página inicial (carrossel: um slide por evento).
 */

defined( 'ABSPATH' ) || exit;

$dija_eventos = dija_get_items( 'evento', 12 );
if ( ! $dija_eventos ) {
	return;
}
$dija_multi = count( $dija_eventos ) > 1;
?>
<section class="section2" id="eventos">
	<div class="carousel" data-carousel>
		<button class="btn4 carousel-prev" type="button" aria-label="Evento anterior"<?php echo $dija_multi ? '' : ' hidden'; ?>><span class="ico ico-chevron" aria-hidden="true"></span></button>

		<div class="slides">
			<?php foreach ( $dija_eventos as $i => $dija_evento ) :
				$dija_desc = dija_field( $dija_evento->ID, 'descricao' ); ?>
				<div class="slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
					<div class="eventos-text">
						<h2 class="h1 grunge"><span class="ico ico-rays2" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'eventos_title' ) ); ?><span class="ico ico-rays2 is-flipped" aria-hidden="true"></span></h2>
						<h3 class="h3"><?php echo esc_html( get_the_title( $dija_evento ) ); ?></h3>
						<?php if ( $dija_desc ) : ?>
							<p class="t1"><?php echo nl2br( esc_html( $dija_desc ) ); ?></p>
						<?php endif; ?>
						<a class="btn3 brush" href="<?php echo esc_url( dija_link( dija_field( $dija_evento->ID, 'botao_link' ) ) ); ?>"><?php echo esc_html( dija_field( $dija_evento->ID, 'botao_texto' ) ); ?></a>
					</div>
					<?php get_template_part( 'template-parts/evento-media', null, array( 'post' => $dija_evento ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<button class="btn4 carousel-next" type="button" aria-label="Próximo evento"<?php echo $dija_multi ? '' : ' hidden'; ?>><span class="ico ico-chevron is-flipped" aria-hidden="true"></span></button>
	</div>
</section>
