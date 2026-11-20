<?php
/**
 * Card de uma turma (página inicial) + botão "Quero participar" (WhatsApp).
 *
 * $args: post (WP_Post)
 */

defined( 'ABSPATH' ) || exit;

$dija_turma = $args['post'];
$dija_id    = $dija_turma->ID;
$dija_terms = get_the_terms( $dija_turma, 'local' );
$dija_local = ( $dija_terms && ! is_wp_error( $dija_terms ) ) ? $dija_terms[0] : null;
$dija_name  = $dija_local ? $dija_local->name : '';
$dija_addr  = $dija_local ? (string) get_term_meta( $dija_local->term_id, 'dija_endereco', true ) : '';
$dija_dia   = dija_weekday_label( dija_field( $dija_id, 'dia' ) );
?>
<li class="card-item">
	<article class="card card--<?php echo esc_attr( dija_field( $dija_id, 'cor' ) ); ?>">
		<span class="ico card-icon ico-<?php echo esc_attr( dija_field( $dija_id, 'icone' ) ); ?>" aria-hidden="true"></span>
		<h3 class="h3"><?php echo esc_html( $dija_dia ); ?></h3>
		<p class="t1"><?php echo esc_html( dija_field( $dija_id, 'horario' ) ); ?></p>
		<p class="t2">
			<span><?php echo esc_html( dija_field( $dija_id, 'ritmo' ) ); ?></span>
			<span><?php echo esc_html( dija_field( $dija_id, 'nivel' ) ); ?></span>
		</p>
		<span class="ico ico-star-sm" aria-hidden="true"></span>
		<?php if ( $dija_name || $dija_addr ) : ?>
			<address class="card-address">
				<?php if ( $dija_name ) : ?><strong><?php echo esc_html( $dija_name ); ?></strong><?php endif; ?>
				<?php if ( $dija_addr ) : ?><span><?php echo esc_html( preg_replace( '/\s+/', ' ', $dija_addr ) ); ?></span><?php endif; ?>
			</address>
		<?php endif; ?>
	</article>
	<a class="mini-card-cta card-cta brush" href="<?php echo dija_esc_link( dija_turma_whatsapp_url( $dija_turma, $dija_name ?: 'local a definir', $dija_addr ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( dija_mod( 'turmas_cta_label' ) . ': ' . dija_field( $dija_id, 'ritmo' ) . ', ' . $dija_dia . ', ' . dija_field( $dija_id, 'horario' ) . ' (abre o WhatsApp)' ); ?>"><span class="ico ico-whatsapp" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_cta_label' ) ); ?></a>
</li>
