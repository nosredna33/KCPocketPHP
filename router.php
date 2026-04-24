<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/public' . $uri;

// Se for um arquivo estático real, serve ele
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    return false;
}

// Todo o resto vai para o index.php
require_once __DIR__ . '/public/index.php';
