<?php
/**
 * Ilustração de um evento: imagem destacada ou a arte padrão dos músicos.
 *
 * $args: post (WP_Post)
 */

defined( 'ABSPATH' ) || exit;
?>
<figure class="media2">
	<?php if ( has_post_thumbnail( $args['post'] ) ) : ?>
		<?php echo get_the_post_thumbnail( $args['post'], 'large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
	<?php else : ?>
		<span class="img img-musicos" aria-hidden="true"></span>
	<?php endif; ?>
</figure>
