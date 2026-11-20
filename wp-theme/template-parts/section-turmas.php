<?php
/**
 * Seção de turmas da página inicial: cards das turmas em andamento.
 * (A página /turmas completa fica em archive-turma.php.)
 */

defined( 'ABSPATH' ) || exit;

$dija_turmas = dija_get_turmas( 'andamento' );
?>
<section class="section" id="turmas">
	<div class="turmas-title">
		<h2 class="turmas-heading">
			<span class="h2"><span class="ico ico-rays" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_kicker' ) ); ?><span class="ico ico-rays is-flipped" aria-hidden="true"></span></span>
			<span class="h1 grunge"><?php echo esc_html( dija_mod( 'turmas_title' ) ); ?></span>
		</h2>
		<a class="img img-scroll" href="<?php echo esc_url( (string) get_post_type_archive_link( 'turma' ) ); ?>" aria-label="Ver todas as turmas e locais"></a>
	</div>

	<?php if ( $dija_turmas ) : ?>
		<ul class="cards">
			<?php foreach ( $dija_turmas as $dija_turma ) {
				get_template_part( 'template-parts/turma-card', null, array( 'post' => $dija_turma ) );
			} ?>
		</ul>
	<?php else : ?>
		<p class="cards-empty"><?php echo esc_html( dija_mod( 'turmas_now_empty' ) ); ?></p>
	<?php endif; ?>
</section>
