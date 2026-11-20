<?php
/**
 * Página não encontrada.
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>
<section class="section-page">
	<h1 class="h1 page-title grunge">Ops! Página não encontrada</h1>
	<div class="entry-content"><p>O endereço pode ter mudado. Que tal voltar para o início e escolher sua turma?</p></div>
	<a class="btn3 brush page-cta" href="<?php echo esc_url( home_url( '/' ) ); ?>">VOLTAR AO INÍCIO</a>
</section>
<?php
get_footer();
