<?php
/**
 * Router script for PHP Built-in Development Web Server
 * Run with: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve index.html for root path
if (($uri === '/' || $uri === '' || $uri === '/index.html') && file_exists(__DIR__ . '/index.html')) {
    header("Content-Type: text/html; charset=UTF-8");
    header("Content-Length: " . filesize(__DIR__ . '/index.html'));
    readfile(__DIR__ . '/index.html');
    exit;
}

// Check in public directory first
$file = __DIR__ . '/public' . $uri;

// Also check root directory for root assets like img/
if (!file_exists($file) || is_dir($file)) {
    $file = __DIR__ . $uri;
}

// If the static file exists, serve it with correct MIME type
if ($uri !== '/' && file_exists($file) && !is_dir($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
        'pdf' => 'application/pdf',
        'map' => 'application/json'
    ];

    $mime = $mimes[$ext] ?? (function_exists('mime_content_type') ? mime_content_type($file) : 'application/octet-stream');
    header("Content-Type: {$mime}");
    header("Content-Length: " . filesize($file));
    readfile($file);
    exit;
}

// Forward all other requests to public/index.php
require_once __DIR__ . '/public/index.php';
