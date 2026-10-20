<?php
define('ROOT_DIR', dirname(__DIR__));
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = ROOT_DIR . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $file = $baseDir . str_replace('\\', '/', substr($class, $len)) . '.php';
    if (file_exists($file)) require $file;
});

use App\Controllers\PublicController;
use App\Core\Request;

try {
    ob_start();
    $controller = new PublicController();
    $controller->home(new Request());
    $html = ob_get_clean();
    echo "SUCCESS: Rendered " . strlen($html) . " bytes\n";
    echo "Contains Dr. Amrit: " . (strpos($html, 'Dr. Amrit') !== false ? 'YES' : 'NO') . "\n";
    echo "Contains Services: " . (strpos($html, 'services') !== false ? 'YES' : 'NO') . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
