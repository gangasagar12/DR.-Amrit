<?php
/**
 * Dental Doctor Portfolio - Front Controller
 */

// Error reporting settings
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Root directory
define('ROOT_DIR', dirname(__DIR__));

// PSR-4 Autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = ROOT_DIR . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Request;
use App\Core\Router;
use App\Core\Session;
use App\Core\Database;

try {
    // Start session
    Session::start();

    // Initialize Database
    $dbConfig = require ROOT_DIR . '/config/database.php';
    Database::init($dbConfig);

    // Create Request & Router
    $request = new Request();
    $router = new Router();

    // Load Routes
    require ROOT_DIR . '/routes/web.php';

    // Dispatch
    $router->dispatch($request);

} catch (\Throwable $e) {
    // Log to file
    $logDir = ROOT_DIR . '/storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n\n";
    @file_put_contents($logDir . '/error.log', $logMessage, FILE_APPEND);

    http_response_code(500);

    $appConfig = require ROOT_DIR . '/config/app.php';
    if (!empty($appConfig['debug'])) {
        echo '<!DOCTYPE html><html><head><title>Application Exception</title>';
        echo '<style>body{font-family:system-ui,-apple-system,sans-serif;background:#0f172a;color:#f8fafc;padding:2rem;line-height:1.6}';
        echo '.container{max-width:900px;margin:0 auto;background:#1e293b;border-radius:12px;padding:2rem;box-shadow:0 10px 25px rgba(0,0,0,0.5);border:1px solid #334155}';
        echo 'h1{color:#f43f5e;font-size:1.5rem;margin-top:0}pre{background:#090d16;padding:1rem;border-radius:8px;overflow-x:auto;color:#38bdf8;font-size:0.875rem}';
        echo '</style></head><body><div class="container">';
        echo '<h1>Application Error (Debug Mode Active)</h1>';
        echo '<p><strong>' . htmlspecialchars($e->getMessage()) . '</strong></p>';
        echo '<p><em>File:</em> ' . htmlspecialchars($e->getFile()) . ' on line ' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div></body></html>';
    } else {
        echo '<!DOCTYPE html><html><head><title>Service Temporarily Unavailable</title>';
        echo '<style>body{font-family:system-ui,sans-serif;background:#f8fafc;color:#1e293b;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}';
        echo '.box{text-align:center;max-width:500px;padding:2rem;background:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08)}';
        echo 'h1{color:#0d9488}a{color:#0d9488;text-decoration:none;font-weight:600}</style></head><body>';
        echo '<div class="box"><h1>Temporary Service Notice</h1>';
        echo '<p>We are currently experiencing a brief technical interruption. Our team has been notified. Please try again shortly.</p>';
        echo '<a href="/">Return to Homepage</a></div></body></html>';
    }
}
