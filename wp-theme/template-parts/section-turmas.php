<?php
/**
 * Seção de turmas (cards).
 *
 * $args:
 *   page (bool) Página /turmas: título vira <h1>, mostra texto de abertura e descrições.
 */

defined( 'ABSPATH' ) || exit;

$dija_is_page = ! empty( $args['page'] );
$dija_turmas  = dija_get_items( 'turma' );
$dija_tag     = $dija_is_page ? 'h1' : 'h2';
?>
<section class="section" id="turmas">
	<div class="turmas-title">
		<<?php echo $dija_tag; ?> class="turmas-heading">
			<span class="h2"><span class="ico ico-rays" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_kicker' ) ); ?><span class="ico ico-rays is-flipped" aria-hidden="true"></span></span>
			<span class="h1 grunge"><?php echo esc_html( dija_mod( 'turmas_title' ) ); ?></span>
		</<?php echo $dija_tag; ?>>
		<?php if ( $dija_is_page && dija_mod( 'turmas_page_intro' ) ) : ?>
			<p class="t1"><?php echo esc_html( dija_mod( 'turmas_page_intro' ) ); ?></p>
		<?php endif; ?>
		<span class="img img-scroll" aria-hidden="true"></span>
	</div>

	<?php if ( $dija_turmas ) : ?>
		<ul class="cards">
			<?php foreach ( $dija_turmas as $dija_turma ) {
				get_template_part( 'template-parts/turma-card', null, array( 'post' => $dija_turma, 'show_desc' => $dija_is_page ) );
			} ?>
		</ul>
	<?php else : ?>
		<p class="cards-empty">Novas turmas em breve. Fale com a gente pelo contato!</p>
	<?php endif; ?>
</section>
