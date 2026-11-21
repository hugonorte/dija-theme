<?php
/**
 * Cabeçalho: <head> + topo com menu (hambúrguer abaixo de 768px).
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="sr-only" href="#conteudo">Pular para o conteúdo</a>
<div class="page">

	<header class="nav">
		<a class="t1 logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( dija_mod( 'logo_text' ) ); ?></a>
		<button class="nav-toggle" type="button" aria-label="Abrir menu" aria-expanded="false" aria-controls="menu-principal">
			<span></span><span></span><span></span>
		</button>
		<nav class="nav-links" id="menu-principal" aria-label="Principal">
			<?php foreach ( dija_nav_items() as $item ) :
				$current = dija_is_current_url( $item['url'] ); ?>
				<a class="<?php echo $current ? 'btn is-active' : 'lnk'; ?>" href="<?php echo esc_url( $item['url'] ); ?>"<?php echo $current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
			<a class="btn2 brush" href="<?php echo esc_url( dija_link( dija_mod( 'nav_cta_url' ) ) ); ?>"><?php echo esc_html( dija_mod( 'nav_cta_label' ) ); ?></a>
		</nav>
		<?php if ( dija_instagram_url() ) : // Instagram de "Topo e contatos", no Personalizador ?>
			<a class="nav-social" href="<?php echo esc_url( dija_instagram_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( 'Instagram ' . dija_mod( 'instagram' ) . ' (abre em nova aba)' ); ?>">
				<span class="ico ico-instagram" aria-hidden="true"></span>
			</a>
		<?php endif; ?>
	</header>

	<main id="conteudo">
