<?php
/**
 * Dija Theme — ponto de entrada.
 *
 * Tudo usa apenas APIs nativas e estáveis do WordPress (sem plugins):
 *   inc/helpers.php        Funções de apoio (textos padrão, imagens, links).
 *   inc/setup.php          Suportes do tema, scripts/estilos, menu.
 *   inc/content-types.php  Tipos de conteúdo "Turmas" e "Eventos" + campos.
 *   inc/customizer.php     Textos fixos editáveis em Aparência > Personalizar.
 *   inc/contact.php        Formulário de contato (REST), mensagens e SMTP.
 */

defined( 'ABSPATH' ) || exit;

define( 'DIJA_THEME_VERSION', '2.0.0' );

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/content-types.php';
require_once __DIR__ . '/inc/customizer.php';
require_once __DIR__ . '/inc/contact.php';
