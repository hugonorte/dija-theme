<?php
/**
 * Lista de posts (blog/notícias, busca e arquivos). Também é o modelo de
 * reserva exigido pelo WordPress.
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>
<section class="section-page">
	<h1 class="h1 page-title grunge"><?php
		if ( is_home() ) {
			$dija_posts_page = (int) get_option( 'page_for_posts' );
			echo esc_html( $dija_posts_page ? get_the_title( $dija_posts_page ) : 'Notícias' );
		} elseif ( is_search() ) {
			echo esc_html( 'Busca: ' . get_search_query() );
		} else {
			echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
		}
	?></h1>

	<?php if ( have_posts() ) : ?>
		<div class="posts-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class( 'post-card' ); ?>>
					<h2 class="h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="page-meta"><?php echo esc_html( get_the_date() ); ?></p>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				</article>
			<?php endwhile; ?>
		</div>
		<nav class="pagination" aria-label="Paginação">
			<?php previous_posts_link( '← Mais recentes' ); ?>
			<?php next_posts_link( 'Mais antigos →' ); ?>
		</nav>
	<?php else : ?>
		<div class="entry-content"><p>Nada publicado por aqui ainda.</p></div>
	<?php endif; ?>
</section>
<?php
get_footer();
