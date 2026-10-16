<?php
namespace App\Core;

class Response {
    public static function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function redirect(string $path, int $statusCode = 302): void {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $basePath = $config['base_path'] ?? '';
        
        // If absolute URL, redirect directly
        if (preg_match('#^https?://#i', $path)) {
            $target = $path;
        } else {
            $target = $basePath . '/' . ltrim($path, '/');
        }

        http_response_code($statusCode);
        header("Location: {$target}");
        exit;
    }

    public static function setStatus(int $code): void {
        http_response_code($code);
    }
}
