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

return [
    'app_name' => 'Dr. Dental Portfolio',
    'app_env' => 'development', // 'development' or 'production'
    'debug' => true,
    'base_url' => $protocol . $host . $basePath,
    'base_path' => $basePath,
    
    // Timezone
    'timezone' => 'UTC',
    
    // Session configuration
    'session_name' => 'dental_doctor_sess',
    'session_lifetime' => 7200, // 2 hours
    
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
