<?php
namespace App\Core;

class Request {
    private array $get;
    private array $post;
    private array $files;
    private array $server;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
    }

    public function method(): string {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool {
        return $this->method() === 'POST';
    }

    public function isGet(): bool {
        return $this->method() === 'GET';
    }

    public function isAjax(): bool {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) && 
            strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
            (strpos($this->server['HTTP_ACCEPT'] ?? '', 'application/json') !== false);
    }

    public function path(): string {
        // First check explicit query fallback route parameter (e.g. ?route=/admin/blogs)
        if (isset($this->get['route'])) {
            $r = '/' . ltrim($this->get['route'], '/');
            return $r === '' ? '/' : $r;
        }

        $uri = $this->server['REQUEST_URI'] ?? '/';
        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Get script directory to strip base path
        $scriptDir = dirname($this->server['SCRIPT_NAME'] ?? '');
        $scriptDir = str_replace('\\', '/', $scriptDir);
        $scriptDir = preg_replace('#/public$#', '', $scriptDir);

        if ($scriptDir !== '/' && $scriptDir !== '' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }

        // Also if /public is in uri, strip it
        if (strpos($uri, '/public') === 0) {
            $uri = substr($uri, 7);
        }

        $uri = '/' . ltrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    public function get(string $key, mixed $default = null): mixed {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed {
        return $this->post[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed {
        return $this->post[$key] ?? ($this->get[$key] ?? $default);
    }

    public function param(string $key, mixed $default = null): mixed {
        return $this->get[$key] ?? ($this->post[$key] ?? $default);
    }

    public function all(): array {
        return array_merge($this->get, $this->post);
    }

    public function file(string $key): ?array {
        if (isset($this->files[$key]) && !empty($this->files[$key]['name'])) {
            return $this->files[$key];
        }
        return null;
    }

    public function ip(): string {
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function userAgent(): string {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
}
