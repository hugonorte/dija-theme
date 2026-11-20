<?php
/**
 * Página /turmas: todas as turmas + contato.
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/section-turmas', null, array( 'page' => true ) );
get_template_part( 'template-parts/section-contato' );
get_footer();
