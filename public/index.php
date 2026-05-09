<?php
/**
 * Tema DIJA - Carregador do Frontend Nuxt
 */
$candidate_paths = array(
    __DIR__ . '/index.html',
    __DIR__ . '/../../../index.html'
);

foreach ($candidate_paths as $index_html) {
    if (file_exists($index_html)) {
        // Carrega o HTML gerado pelo Nuxt
        echo file_get_contents($index_html);
        return;
    }
}

wp_die('Erro: index.html não encontrado. Verifique se o build do Nuxt foi concluído e enviado para a pasta do tema ou para a raiz do site.');
