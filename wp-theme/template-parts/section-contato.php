<?php
/**
 * Seção de contato com formulário (envia para /wp-json/dija/v1/contact).
 *
 * $args:
 *   page (bool) Página /contato: título vira <h1>.
 */

defined( 'ABSPATH' ) || exit;

$dija_tag       = ! empty( $args['page'] ) ? 'h1' : 'h2';
$dija_instagram = dija_instagram_url();
$dija_whatsapp  = dija_whatsapp_url();
?>
<section class="section4" id="contato">
	<div class="contato-info">
		<<?php echo $dija_tag; ?> class="h1 grunge"><?php echo esc_html( dija_mod( 'contato_title' ) ); ?></<?php echo $dija_tag; ?>>
		<span class="ico ico-heart-rays" aria-hidden="true"></span>
		<p class="t3"><?php
			$dija_lines = dija_lines( dija_mod( 'contato_text' ) );
			echo implode( '<br>', array_map( 'esc_html', $dija_lines ) );
		?></p>
		<ul class="contato-social">
			<?php if ( $dija_instagram ) : ?>
				<li><span class="ico ico-instagram" aria-hidden="true"></span><a class="t2" href="<?php echo esc_url( $dija_instagram ); ?>" target="_blank" rel="noopener"><?php echo esc_html( dija_mod( 'instagram' ) ); ?></a></li>
			<?php endif; ?>
			<?php if ( $dija_whatsapp ) : ?>
				<li><span class="ico ico-whatsapp" aria-hidden="true"></span><a class="t2" href="<?php echo esc_url( $dija_whatsapp ); ?>" target="_blank" rel="noopener"><?php echo esc_html( dija_mod( 'whatsapp' ) ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>

	<form class="form" data-contact-form data-endpoint="<?php echo esc_url( rest_url( 'dija/v1/contact' ) ); ?>" data-success="<?php echo esc_attr( dija_mod( 'contato_success' ) ); ?>" novalidate>
		<label class="sr-only" for="f-nome">Nome</label>
		<input class="in" id="f-nome" name="name" type="text" placeholder="NOME" autocomplete="name" required>
		<label class="sr-only" for="f-whats">WhatsApp</label>
		<input class="in" id="f-whats" name="whatsapp" type="tel" placeholder="WHATSAPP" autocomplete="tel">
		<label class="sr-only" for="f-email">E-mail</label>
		<input class="in" id="f-email" name="email" type="email" placeholder="E-MAIL" autocomplete="email" required>
		<label class="sr-only" for="f-msg">Mensagem</label>
		<textarea class="in" id="f-msg" name="message" placeholder="SUA MENSAGEM" required></textarea>
		<div class="form-hp" aria-hidden="true">
			<label for="f-fax">Não preencha</label>
			<input id="f-fax" name="fax_number" type="text" tabindex="-1" autocomplete="off">
		</div>
		<button class="btn5 brush" type="submit"><?php echo esc_html( dija_mod( 'contato_button' ) ); ?><span class="ico ico-heart2" aria-hidden="true"></span></button>
		<p class="form-status" role="status" aria-live="polite"></p>
	</form>
</section>
