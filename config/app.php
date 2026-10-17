<?php
/**
 * Application Configuration
 */

// Detect Base URL dynamically so it works seamlessly in:
// - http://localhost/doctor/
// - http://localhost:8000/
// - VirtualHost / Production domain
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = str_replace('\\', '/', $scriptDir);

// Trim /public if running through public/index.php or root rewrite
$basePath = preg_replace('#/public$#', '', rtrim($scriptDir, '/'));

// Load .env if exists
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim($val, " \t\n\r\0\x0B\"'");
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $val;
                putenv("$key=$val");
            }
        }
    }
}

$appEnv = getenv('APP_ENV') ?: ($_ENV['APP_ENV'] ?? 'development');
$appDebug = filter_var(getenv('APP_DEBUG') !== false ? getenv('APP_DEBUG') : ($_ENV['APP_DEBUG'] ?? ($appEnv === 'development')), FILTER_VALIDATE_BOOLEAN);

return [
    'app_name' => getenv('APP_NAME') ?: 'Dr. Dental Portfolio',
    'app_env' => $appEnv, // 'development' or 'production'
    'debug' => $appDebug,
    'base_url' => (getenv('APP_URL') ?: ($protocol . $host . $basePath)),
    'base_path' => $basePath,
    
    // Timezone
    'timezone' => 'UTC',
    
    // Session configuration (persistent login)
    'session_name' => 'dental_doctor_sess',
    'session_lifetime' => 31536000, // 1 year (remain logged in until explicit logout)
    
    // Upload settings
    'upload_max_size' => 5 * 1024 * 1024, // 5MB
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'allowed_image_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
    
    // Paths
    'root_dir' => dirname(__DIR__),
    'public_dir' => dirname(__DIR__) . '/public',
    'upload_dir' => dirname(__DIR__) . '/public/uploads',
    'views_dir' => dirname(__DIR__) . '/resources/views',
    'storage_dir' => dirname(__DIR__) . '/storage',
];
