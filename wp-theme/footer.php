<?php
/**
 * Rodapé: faixa de padrão geométrico.
 */

defined( 'ABSPATH' ) || exit;
?>
	</main>

	<div class="rail2" aria-hidden="true"></div>
	<footer class="site-footer">
		© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
