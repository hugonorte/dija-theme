<?php
/**
 * Seção de destaque da página inicial.
 */

defined( 'ABSPATH' ) || exit;

$dija_tagline = dija_lines( dija_mod( 'hero_tagline' ) );
$dija_last    = count( $dija_tagline ) - 1;
?>
<section class="hero" id="inicio">
	<div class="hero-text">
		<h1 class="hero-name">
			<span class="h1 hero-name-1 grunge"><?php echo esc_html( dija_mod( 'hero_name_1' ) ); ?></span>
			<span class="h1 hero-name-2 grunge"><?php echo esc_html( dija_mod( 'hero_name_2' ) ); ?></span>
		</h1>

		<p class="rail brush">
			<span class="ico ico-star" aria-hidden="true"></span>
			<span class="t3"><?php echo esc_html( dija_mod( 'hero_badge' ) ); ?></span>
			<span class="ico ico-star" aria-hidden="true"></span>
		</p>

		<?php if ( $dija_tagline ) : ?>
			<p class="hero-tagline">
				<span class="ico ico-arrow" aria-hidden="true"></span>
				<span class="hero-tagline-text">
					<?php foreach ( $dija_tagline as $i => $line ) : ?>
						<span class="h3"><?php echo esc_html( $line ); ?><?php if ( $i === $dija_last ) : ?><span class="ico ico-heart" aria-hidden="true"></span><?php endif; ?></span>
					<?php endforeach; ?>
				</span>
			</p>
		<?php endif; ?>

		<a class="btn3 brush hero-cta" href="<?php echo esc_url( dija_link( dija_mod( 'hero_cta_url' ) ) ); ?>"><?php echo esc_html( dija_mod( 'hero_cta_label' ) ); ?></a>
	</div>

	<figure class="media">
		<?php
		$dija_name = trim( dija_mod( 'hero_name_1' ) . ' ' . dija_mod( 'hero_name_2' ) );
		$dija_id   = (int) dija_mod( 'hero_photo' );
		if ( $dija_id && wp_attachment_is_image( $dija_id ) ) {
			echo wp_get_attachment_image( $dija_id, 'full', false, array( 'alt' => $dija_name, 'fetchpriority' => 'high' ) );
		} else {
			printf(
				'<picture><source srcset="%1$s" type="image/avif"><img src="%2$s" alt="%3$s" fetchpriority="high"></picture>',
				esc_url( dija_asset( 'dija-hero-clean.avif' ) ),
				esc_url( dija_asset( 'dija-hero-clean.png' ) ),
				esc_attr( $dija_name )
			);
		}
		?>
	</figure>
</section>
