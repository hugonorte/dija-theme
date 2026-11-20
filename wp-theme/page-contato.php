<?php
/**
 * Página "Contato" (endereço /contato).
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/section-contato', null, array( 'page' => true ) );
get_footer();
