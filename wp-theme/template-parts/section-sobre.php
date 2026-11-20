<?php
/**
 * Seção "Sobre a professora".
 *
 * $args:
 *   page (bool) Página /bio: título vira <h1> e o texto vem do editor da página.
 */

defined( 'ABSPATH' ) || exit;

$dija_is_page = ! empty( $args['page'] );
$dija_tag     = $dija_is_page ? 'h1' : 'h2';

// Foto: na página /bio, a imagem destacada da página tem prioridade.
$dija_photo_id = $dija_is_page && has_post_thumbnail() ? (int) get_post_thumbnail_id() : (int) dija_mod( 'sobre_photo' );
$dija_has_page_text = $dija_is_page && '' !== trim( (string) get_the_content() );
?>
<section class="section3<?php echo $dija_is_page ? ' section3--page' : ''; ?>" id="sobre">
	<figure class="media3">
		<span class="media3-photo">
			<?php dija_photo( $dija_photo_id, 'dija-sobre', 'jpg', 'Dija Ferreira sorrindo' ); ?>
		</span>
		<span class="img img-flores" aria-hidden="true"></span>
	</figure>

	<div class="sobre-text">
		<<?php echo $dija_tag; ?> class="sobre-heading">
			<span class="h2"><span class="ico ico-rays" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'sobre_kicker' ) ); ?><span class="ico ico-rays is-flipped" aria-hidden="true"></span></span>
			<span class="h1 grunge"><?php echo esc_html( dija_mod( 'sobre_title' ) ); ?></span>
		</<?php echo $dija_tag; ?>>

		<?php if ( $dija_has_page_text ) : ?>
			<div class="entry-content"><?php the_content(); ?></div>
		<?php else : ?>
			<p class="t1"><?php echo esc_html( dija_mod( 'sobre_text' ) ); ?></p>
		<?php endif; ?>

		<?php if ( dija_mod( 'sobre_highlight' ) ) : ?>
			<p class="h3"><?php dija_echo_lines( dija_mod( 'sobre_highlight' ) ); ?></p>
		<?php endif; ?>
		<?php if ( dija_mod( 'sobre_accent' ) ) : ?>
			<p class="h3 h3-accent"><?php echo esc_html( dija_mod( 'sobre_accent' ) ); ?></p>
		<?php endif; ?>
	</div>

	<figure class="media4">
		<span class="img img-escultura" aria-hidden="true"></span>
	</figure>
</section>
