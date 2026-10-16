<?php
namespace App\Core;

use App\Models\Setting;
use App\Models\DoctorProfile;

class View {
    private static array $shared = [];

    public static function share(string $key, mixed $value): void {
        self::$shared[$key] = $value;
    }

    public static function render(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void {
        $viewsDir = dirname(__DIR__, 2) . '/resources/views/';
        $viewFile = $viewsDir . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }

        // Merge shared data with view data
        $mergedData = array_merge(self::$shared, $data);

        // Helper closures for views
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $basePath = $config['base_path'] ?? '';

        $url = function(string $path = '') use ($basePath): string {
            if (empty($path) || $path === '/') {
                return $basePath === '' ? '/' : $basePath;
            }
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }
            return $basePath . '/' . ltrim($path, '/');
        };

        $asset = function(string $path) use ($basePath): string {
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }
            return $basePath . '/' . ltrim($path, '/');
        };

        $e = function(?string $str): string {
            return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
        };

        $csrf = function(): string {
            return Csrf::field();
        };

        $csrf_token = function(): string {
            return Csrf::getToken();
        };

        $flash = function(string $key, $default = null) {
            return Session::getFlash($key, $default);
        };

        $has_flash = function(string $key): bool {
            return Session::hasFlash($key);
        };

        $setting = function(string $key, $default = '') {
            return Setting::get($key, $default);
        };

        $doctor_profile = function() {
            return DoctorProfile::get();
        };

        // Extract variables into view scope so view data takes priority
        extract($mergedData, EXTR_OVERWRITE);

        // Capture view content
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If layout specified, render inside layout
        if ($layout !== null) {
            $layoutFile = $viewsDir . str_replace('.', '/', $layout) . '.php';
            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: {$layoutFile}");
            }
            include $layoutFile;
        } else {
            echo $content;
        }
    }
}
