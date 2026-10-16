<?php
require_once __DIR__ . '/../app/Core/Database.php';

$pdo = \App\Core\Database::getConnection();

// 1. Create video_categories table
$pdo->exec("CREATE TABLE IF NOT EXISTS video_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    display_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 2. Create videos table
$pdo->exec("CREATE TABLE IF NOT EXISTS videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    video_type ENUM('upload', 'youtube', 'vimeo', 'external') DEFAULT 'youtube',
    video_url VARCHAR(500) NULL,
    video_file VARCHAR(255) NULL,
    thumbnail VARCHAR(255) NULL,
    duration VARCHAR(20) NULL,
    display_order INT DEFAULT 0,
    status ENUM('draft', 'published') DEFAULT 'published',
    published_at DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (category_id),
    INDEX (status),
    INDEX (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 3. Seed Initial Realistic Categories
$categories = [
    ['name' => 'Oral Health Education', 'slug' => 'oral-health-education', 'display_order' => 1],
    ['name' => 'Dental Treatment Awareness', 'slug' => 'dental-treatment-awareness', 'display_order' => 2],
    ['name' => 'Preventive Dentistry', 'slug' => 'preventive-dentistry', 'display_order' => 3],
    ['name' => 'Clinical Practice & Surgery', 'slug' => 'clinical-practice', 'display_order' => 4],
    ['name' => 'Patient Care & FAQs', 'slug' => 'patient-faqs', 'display_order' => 5],
];

$catStmt = $pdo->prepare("INSERT IGNORE INTO video_categories (name, slug, display_order) VALUES (?, ?, ?)");
foreach ($categories as $cat) {
    $catStmt->execute([$cat['name'], $cat['slug'], $cat['display_order']]);
}

// 4. Seed Initial High-Quality Dental Videos
$vids = [
    [
        'category_slug' => 'oral-health-education',
        'title' => 'Proper Tooth Brushing & Flossing Technique for Healthy Gums',
        'slug' => 'proper-tooth-brushing-and-flossing-technique',
        'description' => 'Dr. Amrit Jaishi demonstrates the modified Bass brushing method and correct flossing habits to prevent plaque accumulation and gingivitis.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=xm9c5HAUBpY',
        'thumbnail' => 'img/services/teeth_cleaning.jpg',
        'duration' => '03:45',
        'display_order' => 1,
        'status' => 'published',
        'published_at' => '2026-08-10 10:00:00'
    ],
    [
        'category_slug' => 'dental-treatment-awareness',
        'title' => 'What to Expect During a Single-Visit Root Canal Treatment',
        'slug' => 'what-to-expect-during-root-canal-treatment',
        'description' => 'A step-by-step patient walkthrough explaining modern painless rotary endodontic therapy, rubber dam isolation, and crown restoration.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=jww7r7H5rF0',
        'thumbnail' => 'img/services/rct.jpg',
        'duration' => '05:20',
        'display_order' => 2,
        'status' => 'published',
        'published_at' => '2026-08-25 11:30:00'
    ],
    [
        'category_slug' => 'preventive-dentistry',
        'title' => 'Understanding Ultrasonic Scaling & Tartar Removal',
        'slug' => 'understanding-ultrasonic-scaling-tartar-removal',
        'description' => 'Learn why routine professional scaling does not weaken your enamel, and how it protects supportive bone from periodontitis.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=l_aQ5yXh4lU',
        'thumbnail' => 'img/services/gum_care.jpg',
        'duration' => '04:12',
        'display_order' => 3,
        'status' => 'published',
        'published_at' => '2026-09-05 14:15:00'
    ],
    [
        'category_slug' => 'clinical-practice',
        'title' => 'Atraumatic Wisdom Tooth Management & Surgical Care',
        'slug' => 'atraumatic-wisdom-tooth-management',
        'description' => 'Clinical insights into diagnostic evaluation of impacted third molars, gentle surgical sectioning, and post-operative healing instructions.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=kYJv_QkF4Jg',
        'thumbnail' => 'img/services/wisdom_tooth.jpg',
        'duration' => '06:05',
        'display_order' => 4,
        'status' => 'published',
        'published_at' => '2026-09-18 09:45:00'
    ],
    [
        'category_slug' => 'dental-treatment-awareness',
        'title' => 'Crown & Bridge Restorations: Ceramic vs. Zirconia',
        'slug' => 'crown-and-bridge-restorations-guide',
        'description' => 'Dr. Jaishi explains the differences in durability, aesthetics, and biocompatibility when choosing dental crowns after root canal treatment.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=4d3L8jK1aU8',
        'thumbnail' => 'img/services/dental_crown.jpg',
        'duration' => '04:30',
        'display_order' => 5,
        'status' => 'published',
        'published_at' => '2026-10-02 16:20:00'
    ],
    [
        'category_slug' => 'patient-faqs',
        'title' => 'Emergency Dental Care: What to Do in Acute Toothache or Trauma',
        'slug' => 'emergency-dental-care-acute-toothache-guide',
        'description' => 'Practical emergency guidelines for knocked-out teeth, cracked enamel, severe swelling, and immediate first-aid before reaching the clinic.',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=2f3qY_0eU9I',
        'thumbnail' => 'img/services/emergency.jpg',
        'duration' => '03:55',
        'display_order' => 6,
        'status' => 'published',
        'published_at' => '2026-10-12 12:00:00'
    ]
];

$vidStmt = $pdo->prepare("INSERT IGNORE INTO videos (category_id, title, slug, description, video_type, video_url, thumbnail, duration, display_order, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($vids as $v) {
    // lookup category id
    $cStmt = $pdo->prepare("SELECT id FROM video_categories WHERE slug = ? LIMIT 1");
    $cStmt->execute([$v['category_slug']]);
    $catId = $cStmt->fetchColumn() ?: null;

    $vidStmt->execute([
        $catId,
        $v['title'],
        $v['slug'],
        $v['description'],
        $v['video_type'],
        $v['video_url'],
        $v['thumbnail'],
        $v['duration'],
        $v['display_order'],
        $v['status'],
        $v['published_at']
    ]);
}

echo "MIGRATION_AND_SEEDING_COMPLETE\n";
