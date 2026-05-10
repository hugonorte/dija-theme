<?php
/**
 * Tema DIJA - Carregador do Frontend Nuxt
 */

$request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$request_path = trim(rawurldecode($request_path), '/');

$candidate_paths = array();

if ($request_path !== '') {
    $candidate_paths[] = __DIR__ . '/' . $request_path . '/index.html';
}

$candidate_paths[] = __DIR__ . '/index.html';
$candidate_paths[] = __DIR__ . '/../../../index.html';

foreach ($candidate_paths as $index_html) {
    if (file_exists($index_html)) {
        // Carrega o HTML gerado pelo Nuxt
        echo file_get_contents($index_html);
        return;
    }
}

wp_die('Erro: index.html não encontrado para a rota solicitada. Verifique se o build do Nuxt foi concluído e enviado para a pasta do tema.');
