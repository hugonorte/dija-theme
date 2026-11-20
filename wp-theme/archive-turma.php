<?php
/**
 * Página /turmas:
 *   1. Hero: título + carrossel com as imagens de divulgação (16:9) das
 *      próximas turmas.
 *   2. Botões-âncora para "Turmas em andamento" e "Próximas turmas".
 *   3. Turmas em andamento, por local e dia da semana.
 *   4. Próximas turmas, por local e dia da semana.
 *   5. Contato.
 */

defined( 'ABSPATH' ) || exit;

$dija_now   = dija_get_turmas( 'andamento' );
$dija_next  = dija_get_turmas( 'proxima' );
$dija_promo = array_values( array_filter( $dija_next, 'has_post_thumbnail' ) );
$dija_multi = count( $dija_promo ) > 1;

get_header();
?>
<section class="sec-turmas-hero<?php echo $dija_promo ? '' : ' sec-turmas-hero--no-promo'; ?>" id="topo-turmas">
	<header class="turmas-page-head">
		<h1 class="turmas-heading">
			<span class="h2"><span class="ico ico-rays" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_kicker' ) ); ?><span class="ico ico-rays is-flipped" aria-hidden="true"></span></span>
			<span class="h1 grunge"><?php echo esc_html( dija_mod( 'turmas_title' ) ); ?></span>
		</h1>
		<?php if ( dija_mod( 'turmas_page_intro' ) ) : ?>
			<p class="t1"><?php echo esc_html( dija_mod( 'turmas_page_intro' ) ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( $dija_promo ) : ?>
		<div class="carousel promo-carousel" data-carousel aria-roledescription="carrossel" aria-label="Divulgação das próximas turmas">
			<button class="btn4 carousel-prev" type="button" aria-label="Divulgação anterior"<?php echo $dija_multi ? '' : ' hidden'; ?>><span class="ico ico-chevron" aria-hidden="true"></span></button>
			<div class="slides">
				<?php foreach ( $dija_promo as $i => $dija_turma ) :
					$dija_alt = trim( dija_field( $dija_turma->ID, 'ritmo' ) . ' ' . dija_field( $dija_turma->ID, 'nivel' ) . ' — ' . dija_weekday_label( dija_field( $dija_turma->ID, 'dia' ) ) . ', ' . dija_field( $dija_turma->ID, 'horario' ) ); ?>
					<figure class="slide promo-slide<?php echo 0 === $i ? ' is-active' : ''; ?>" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' de ' . count( $dija_promo ) ); ?>">
						<a href="#proximas-turmas">
							<?php echo get_the_post_thumbnail( $dija_turma, 'full', array( 'alt' => $dija_alt, 'loading' => 0 === $i ? 'eager' : 'lazy', 'fetchpriority' => 0 === $i ? 'high' : 'auto', 'sizes' => '(max-width: 767px) 100vw, 1240px' ) ); ?>
						</a>
					</figure>
				<?php endforeach; ?>
			</div>
			<button class="btn4 carousel-next" type="button" aria-label="Próxima divulgação"<?php echo $dija_multi ? '' : ' hidden'; ?>><span class="ico ico-chevron is-flipped" aria-hidden="true"></span></button>
		</div>
		<?php if ( $dija_multi ) : ?>
			<div class="carousel-dots" data-carousel-dots aria-hidden="true">
				<?php foreach ( $dija_promo as $i => $unused ) : ?><span class="<?php echo 0 === $i ? 'is-active' : ''; ?>"></span><?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</section>

<section class="sec-turmas" id="em-andamento">
	<nav class="turmas-jump" aria-label="Seções da página">
		<a class="turmas-jump-btn turmas-jump-btn--now brush" href="#turmas-em-andamento"><?php echo esc_html( dija_mod( 'turmas_now_title' ) ); ?></a>
		<a class="turmas-jump-btn turmas-jump-btn--next brush" href="#proximas-turmas"><?php echo esc_html( dija_mod( 'turmas_next_title' ) ); ?></a>
	</nav>

	<h2 class="turmas-group-title h1" id="turmas-em-andamento"><?php echo esc_html( dija_mod( 'turmas_now_title' ) ); ?></h2>
	<?php if ( $dija_now ) : ?>
		<div class="locais">
			<?php get_template_part( 'template-parts/turmas-by-local', null, array( 'turmas' => $dija_now ) ); ?>
		</div>
	<?php else : ?>
		<p class="turmas-empty"><?php echo esc_html( dija_mod( 'turmas_now_empty' ) ); ?></p>
	<?php endif; ?>
</section>

<section class="sec-proximas" id="proximas-turmas">
	<h2 class="turmas-group-title h1 grunge"><span class="ico ico-rays2" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_next_title' ) ); ?><span class="ico ico-rays2 is-flipped" aria-hidden="true"></span></h2>

	<?php if ( $dija_next ) : ?>
		<?php if ( dija_mod( 'turmas_next_intro' ) ) : ?>
			<p class="proximas-intro t1"><?php echo esc_html( dija_mod( 'turmas_next_intro' ) ); ?></p>
		<?php endif; ?>
		<div class="locais locais--dark">
			<?php get_template_part( 'template-parts/turmas-by-local', null, array( 'turmas' => $dija_next, 'show_start' => true ) ); ?>
		</div>
	<?php else : ?>
		<p class="turmas-empty"><?php echo esc_html( dija_mod( 'turmas_next_empty' ) ); ?></p>
	<?php endif; ?>
</section>
<?php
get_template_part( 'template-parts/section-contato' );
get_footer();
