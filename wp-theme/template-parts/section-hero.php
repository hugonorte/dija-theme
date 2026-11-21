<?php
/**
 * Seção de destaque da página inicial.
 */

defined( 'ABSPATH' ) || exit;

$dija_tagline = dija_lines( dija_mod( 'hero_tagline' ) );
$dija_last    = count( $dija_tagline ) - 1;

/**
 * Camada decorativa do fundo animado (imagem leve, baixa prioridade de carregamento).
 * width/height reservam o espaço antes de a imagem chegar: sem eles, a camada
 * "pularia" para o lugar ao carregar (deslocamento de layout, métrica CLS).
 * decoding="auto": a camada aparece junto com o fundo, sem "pipocar" depois.
 */
$dija_layer = static function ( string $file, string $class ): void {
	$sizes = array(
		'sol.webp'             => array( 660, 660 ),
		'passaro-1.webp'       => array( 185, 173 ),
		'passaro-2.webp'       => array( 185, 173 ),
		'cacto-alto.webp'      => array( 280, 793 ),
		'cactos-esquerda.webp' => array( 272, 427 ),
		'folhas.webp'          => array( 379, 288 ),
	);
	printf(
		'<img class="sc %1$s" src="%2$s" width="%3$d" height="%4$d" alt="" decoding="auto" fetchpriority="low" draggable="false">',
		esc_attr( $class ),
		esc_url( get_theme_file_uri( 'assets/img/anim/' . $file ) ),
		$sizes[ $file ][0],
		$sizes[ $file ][1]
	);
};
?>
<section class="hero" id="inicio">
	<?php
	/*
	 * Cena animada do fundo: o fundo (sem sóis, pássaros e cactos) e cada
	 * elemento numa camada própria, na mesma caixa, para ficarem alinhados.
	 * Animações só com transform/opacity (GPU), iniciadas depois do carregamento,
	 * pausadas fora da tela e desligadas com "reduzir movimento" (ver app.js).
	 */
	?>
	<div class="hero-scene" aria-hidden="true">
		<div class="hero-scene-box" data-hero-scene>
			<?php
			$dija_layer( 'sol.webp', 'sc-sun sc-sun--left' );
			$dija_layer( 'sol.webp', 'sc-sun sc-sun--right' );
			for ( $dija_i = 1; $dija_i <= 4; $dija_i++ ) : ?>
				<span class="bird-lane bird-lane--<?php echo (int) $dija_i; ?>" data-bird>
					<span class="bird-bob">
						<?php $dija_layer( 'passaro-1.webp', 'bird bird--up' ); ?>
						<?php $dija_layer( 'passaro-2.webp', 'bird bird--down' ); ?>
					</span>
				</span>
			<?php endfor;
			$dija_layer( 'cactos-esquerda.webp', 'sc-cactos-left' );
			$dija_layer( 'folhas.webp', 'sc-folhas sc-folhas--left' );
			$dija_layer( 'cacto-alto.webp', 'sc-cacto' );
			$dija_layer( 'folhas.webp', 'sc-folhas sc-folhas--center' );
			?>
		</div>
	</div>

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
