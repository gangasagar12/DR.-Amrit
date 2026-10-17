<?php
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

require ROOT_DIR . '/app/Core/Database.php';

try {
    $stats = [
        'published_blogs' => \App\Models\Blog::countPublished(),
        'draft_blogs' => \App\Models\Blog::countDrafts(),
        'services' => \App\Models\Service::count(),
        'clinics' => \App\Models\Clinic::count(),
        'gallery_images' => \App\Models\Gallery::count(),
        'videos' => \App\Models\Video::count(),
        'researches' => \App\Models\Research::countPublished(),
        'qualifications' => \App\Models\Qualification::count(),
        'unread_messages' => \App\Models\Message::countUnread(),
        'total_messages' => \App\Models\Message::countTotal()
    ];
    echo "STATS OK:\n";
    print_r($stats);

    $recentMessages = \App\Models\Message::recent(8);
    echo "RECENT MESSAGES OK: " . count($recentMessages) . "\n";
} catch (\Throwable $t) {
    echo "ERROR: " . $t->getMessage() . " at " . $t->getFile() . ":" . $t->getLine() . "\n";
}
