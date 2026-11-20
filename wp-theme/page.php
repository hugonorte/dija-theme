<?php
/**
 * Página genérica criada no painel.
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post(); ?>
	<article <?php post_class( 'section-page' ); ?>>
		<h1 class="h1 page-title grunge"><?php the_title(); ?></h1>
		<div class="entry-content"><?php the_content(); ?></div>
	</article>
<?php endwhile;
get_footer();
