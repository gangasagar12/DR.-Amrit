<?php
require __DIR__ . '/../app/Core/Database.php';

define('ROOT_DIR', dirname(__DIR__));
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = ROOT_DIR . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require $file;
});

// Test database connection
$pdo = \App\Core\Database::getConnection();
echo "1. Database connection: OK\n";

// Check doctor profile columns
$profile = \App\Models\DoctorProfile::get();
echo "2. Doctor Profile:\n";
echo "   - Name: {$profile['doctor_name']}\n";
echo "   - Title: {$profile['professional_title']}\n";
echo "   - Hero lead: " . substr($profile['hero_lead'] ?? '', 0, 40) . "...\n";
echo "   - Hero cutout: {$profile['hero_image']}\n";
echo "   - About photo: {$profile['about_image']}\n";
echo "   - About kicker: {$profile['about_kicker']}\n";

// Check research model
$papers = \App\Models\Research::allAdmin();
echo "3. Research Publications: " . count($papers) . " papers found\n";
foreach ($papers as $p) {
    echo "   - [{$p['id']}] {$p['title']} ({$p['publication_year']}, {$p['journal_name']}) Status: {$p['status']}\n";
}

// Test HTTP endpoints with cookie session
$loginUrl = 'http://localhost:8000/admin/login';
$cookieJar = tempnam(sys_get_temp_dir(), 'admin_cookie');

// Fetch login page to get CSRF
$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
$loginHtml = curl_exec($ch);
curl_close($ch);

preg_match('/name="_csrf_token"\s+value="([^"]+)"/', $loginHtml, $matches);
$csrfToken = $matches[1] ?? '';

// Post login credentials
$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'email' => 'admin@dentaldoctor.com',
    'password' => 'Admin@12345',
    'remember_me' => '1',
    '_csrf_token' => $csrfToken
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$dashHtml = curl_exec($ch);
$dashCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "4. Admin Login Status: HTTP $dashCode\n";

// Check Admin Endpoints
$endpoints = [
    '/admin/dashboard',
    '/admin/profile',
    '/admin/research',
    '/admin/research/create',
    '/admin/qualifications',
    '/admin/services',
    '/admin/gallery',
    '/admin/videos',
    '/admin/blogs',
    '/admin/messages',
    '/admin/settings',
    '/admin/account'
];

echo "5. Testing Admin Endpoints:\n";
foreach ($endpoints as $ep) {
    $ch = curl_init('http://localhost:8000' . $ep);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $hasError = (stripos($res, 'Fatal error') !== false || stripos($res, 'Parse error') !== false);
    $statusText = $hasError ? "FAILED (PHP Error)" : ($code === 200 ? "OK" : "HTTP $code");
    echo "   - $ep : $statusText\n";
}

@unlink($cookieJar);
echo "\nAll verification steps completed!\n";
