<?php
/**
 * Turmas agrupadas por local → dia da semana → cards (página /turmas).
 *
 * $args:
 *   turmas (WP_Post[]) Turmas já ordenadas (dija_get_turmas()).
 *   show_start (bool)  Mostra o campo "Início" (próximas turmas).
 */

defined( 'ABSPATH' ) || exit;

$dija_show_start = ! empty( $args['show_start'] );

foreach ( dija_group_turmas( $args['turmas'] ) as $dija_local ) : ?>
	<section class="local">
		<header class="local-head">
			<h3 class="local-name h3"><?php echo esc_html( $dija_local['name'] ); ?></h3>
			<?php if ( $dija_local['address'] ) : ?>
				<p class="local-address t1">
					<span class="ico ico-pin" aria-hidden="true"></span>
					<a href="<?php echo esc_url( dija_maps_url( $dija_local['address'] ) ); ?>" target="_blank" rel="noopener"><?php echo nl2br( esc_html( $dija_local['address'] ) ); ?></a>
				</p>
			<?php endif; ?>
		</header>

		<?php foreach ( $dija_local['days'] as $dija_day => $dija_turmas ) : ?>
			<div class="local-day">
				<h4 class="local-day-name"><?php echo esc_html( dija_weekday_label( (string) $dija_day ) ); ?></h4>
				<ul class="mini-cards">
					<?php foreach ( $dija_turmas as $dija_turma ) :
						$dija_id    = $dija_turma->ID;
						$dija_start = $dija_show_start ? dija_field( $dija_id, 'inicio' ) : ''; ?>
						<li class="card mini-card card--<?php echo esc_attr( dija_field( $dija_id, 'cor' ) ); ?>">
							<span class="ico card-icon ico-<?php echo esc_attr( dija_field( $dija_id, 'icone' ) ); ?>" aria-hidden="true"></span>
							<p class="h3"><?php echo esc_html( dija_field( $dija_id, 'ritmo' ) ); ?></p>
							<?php if ( dija_field( $dija_id, 'nivel' ) ) : ?>
								<p class="t2"><?php echo esc_html( dija_field( $dija_id, 'nivel' ) ); ?></p>
							<?php endif; ?>
							<p class="t1"><?php echo esc_html( dija_field( $dija_id, 'horario' ) ); ?></p>
							<?php if ( $dija_start ) : ?>
								<p class="mini-card-start"><?php echo esc_html( $dija_start ); ?></p>
							<?php endif; ?>
							<a class="mini-card-cta brush" href="<?php echo dija_esc_link( dija_turma_whatsapp_url( $dija_turma, $dija_local['name'], $dija_local['address'] ) ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( dija_mod( 'turmas_cta_label' ) . ': ' . dija_field( $dija_id, 'ritmo' ) . ', ' . dija_weekday_label( (string) $dija_day ) . ', ' . dija_field( $dija_id, 'horario' ) . ' (abre o WhatsApp)' ); ?>"><span class="ico ico-whatsapp" aria-hidden="true"></span><?php echo esc_html( dija_mod( 'turmas_cta_label' ) ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</section>
<?php endforeach;
