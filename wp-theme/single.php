<?php
/**
 * Post do blog (notícias).
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post(); ?>
	<article <?php post_class( 'section-page' ); ?>>
		<h1 class="h1 page-title grunge"><?php the_title(); ?></h1>
		<p class="page-meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
		<div class="entry-content">
			<?php if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large' );
			} ?>
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile;
get_footer();
