<?php
/**
 * Card de uma turma.
 *
 * $args: post (WP_Post), show_desc (bool)
 */

defined( 'ABSPATH' ) || exit;

$dija_id    = $args['post']->ID;
$dija_cor   = dija_field( $dija_id, 'cor' );
$dija_icone = dija_field( $dija_id, 'icone' );
$dija_desc  = dija_field( $dija_id, 'descricao' );
?>
<li class="card card--<?php echo esc_attr( $dija_cor ); ?>">
	<span class="ico card-icon ico-<?php echo esc_attr( $dija_icone ); ?>" aria-hidden="true"></span>
	<h3 class="h3"><?php echo esc_html( dija_field( $dija_id, 'dia' ) ); ?></h3>
	<p class="t1"><?php echo esc_html( dija_field( $dija_id, 'horario' ) ); ?></p>
	<p class="t2">
		<span><?php echo esc_html( dija_field( $dija_id, 'ritmo' ) ); ?></span>
		<span><?php echo esc_html( dija_field( $dija_id, 'nivel' ) ); ?></span>
	</p>
	<?php if ( ! empty( $args['show_desc'] ) && $dija_desc ) : ?>
		<p class="card-desc"><?php echo esc_html( $dija_desc ); ?></p>
	<?php endif; ?>
	<span class="ico ico-star-sm" aria-hidden="true"></span>
</li>
