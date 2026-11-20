<?php
/**
 * Página /eventos: lista de todos os eventos + galeria de fotos + contato.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section2" id="eventos">
	<header class="eventos-page-head">
		<h1 class="h1 grunge"><span class="ico ico-rays2" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'eventos_title' ) ); ?><span class="ico ico-rays2 is-flipped" aria-hidden="true"></span></h1>
		<?php if ( dija_mod( 'eventos_page_intro' ) ) : ?>
			<p class="t1"><?php echo esc_html( dija_mod( 'eventos_page_intro' ) ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="eventos-list">
			<?php while ( have_posts() ) : the_post();
				$dija_desc = dija_field( get_the_ID(), 'descricao' ); ?>
				<article class="evento-item">
					<?php get_template_part( 'template-parts/evento-media', null, array( 'post' => get_post() ) ); ?>
					<div class="eventos-text">
						<h2 class="h3"><?php the_title(); ?></h2>
						<?php if ( $dija_desc ) : ?>
							<p class="t1"><?php echo nl2br( esc_html( $dija_desc ) ); ?></p>
						<?php endif; ?>
						<?php // Nesta página, o botão abre o WhatsApp citando o evento (mensagem no Personalizador). ?>
						<a class="btn3 brush" href="<?php echo dija_esc_link( dija_evento_whatsapp_url( get_post() ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( dija_field( get_the_ID(), 'botao_texto' ) . ': ' . get_the_title() . ' (abre o WhatsApp)' ); ?>"><?php echo esc_html( dija_field( get_the_ID(), 'botao_texto' ) ); ?></a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<div class="eventos-page-head"><p class="t1">Novos eventos em breve.</p></div>
	<?php endif; ?>
</section>
<?php
get_template_part( 'template-parts/section-fotos' );
get_template_part( 'template-parts/section-contato' );
get_footer();
