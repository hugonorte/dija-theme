<?php
/**
 * Dija Theme — ponto de entrada.
 *
 * Copyright (c) 2026 Hugo Norte — https://github.com/hugonorte/dija-theme
 * Código PHP/JS licenciado sob a GNU GPL v2 ou posterior (ver COPYING).
 * Layout, CSS, imagens e ícones: todos os direitos reservados (ver LICENSE).
 *
 * Tudo usa apenas APIs nativas e estáveis do WordPress (sem plugins):
 *   inc/helpers.php        Funções de apoio (textos padrão, imagens, links).
 *   inc/setup.php          Suportes do tema, scripts/estilos, menu.
 *   inc/content-types.php  Tipos de conteúdo "Turmas" e "Eventos" + campos.
 *   inc/gallery.php        Galeria de fotos de eventos (filtros por ano, evento e tag).
 *   inc/customizer.php     Textos fixos editáveis em Aparência > Personalizar.
 *   inc/form-security.php  Anti-robô do formulário: honeypot, tempo, limite e bloqueio temporário.
 *   inc/contact.php        Formulário de contato (REST), mensagens e SMTP.
 */

defined( 'ABSPATH' ) || exit;

define( 'DIJA_THEME_VERSION', '2.0.0' );

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/content-types.php';
require_once __DIR__ . '/inc/gallery.php';
require_once __DIR__ . '/inc/customizer.php';
require_once __DIR__ . '/inc/form-security.php';
require_once __DIR__ . '/inc/contact.php';
