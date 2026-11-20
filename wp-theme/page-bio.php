<?php
/**
 * Página "Bio" (endereço /bio): sobre a professora com o texto completo
 * escrito no editor da página + contato.
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/section-sobre', null, array( 'page' => true ) );
}
get_template_part( 'template-parts/section-contato' );
get_footer();
