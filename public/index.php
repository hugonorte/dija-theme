<?php
/**
 * Tema DIJA - Carregador do Frontend Nuxt
 */
$index_html = __DIR__ . '/index.html';

if (file_exists($index_html)) {
    // Carrega o HTML gerado pelo Nuxt
    echo file_get_contents($index_html);
} else {
    wp_die('Erro: index.html não encontrado. Verifique se o build do Nuxt foi concluído e enviado para a pasta do tema.');
}
