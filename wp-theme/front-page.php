<?php
/**
 * Página inicial (landing page).
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/section-hero' );
get_template_part( 'template-parts/section-turmas' );
get_template_part( 'template-parts/section-eventos' );
get_template_part( 'template-parts/section-sobre' );
get_template_part( 'template-parts/section-contato' );
get_footer();
