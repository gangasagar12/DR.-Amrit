<?php
require_once __DIR__ . '/../config/database.php';
$db = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};dbname={$db['database']};charset={$db['charset']}",
        $db['username'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Helper to add column safely
    $addColumn = function($table, $column, $definition) use ($pdo) {
        $stmt = $pdo->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
        if (!$stmt->fetch()) {
            $pdo->exec("ALTER TABLE `{$table}` ADD `{$column}` {$definition}");
            echo "Added {$column} to {$table}\n";
        }
    };

    // 1. Doctor Profile columns
    $addColumn('doctor_profile', 'hero_image', 'VARCHAR(255) NULL AFTER profile_image');
    $addColumn('doctor_profile', 'about_image', 'VARCHAR(255) NULL AFTER hero_image');
    $addColumn('doctor_profile', 'hero_heading', 'VARCHAR(255) NULL AFTER professional_title');
    $addColumn('doctor_profile', 'hero_subheading', 'TEXT NULL AFTER hero_heading');
    $addColumn('doctor_profile', 'hero_btn1_text', "VARCHAR(100) NULL DEFAULT 'View Dental Services'");
    $addColumn('doctor_profile', 'hero_btn1_link', "VARCHAR(255) NULL DEFAULT '#services'");
    $addColumn('doctor_profile', 'hero_btn2_text', "VARCHAR(100) NULL DEFAULT 'Contact Doctor'");
    $addColumn('doctor_profile', 'hero_btn2_link', "VARCHAR(255) NULL DEFAULT '#contact'");

    // Update hero defaults for Dr. Amrit
    $pdo->exec("UPDATE doctor_profile SET 
        hero_image = COALESCE(hero_image, 'img/doctor-image.png'),
        about_image = COALESCE(about_image, 'img/doctor-clinic.jpg'),
        hero_heading = COALESCE(hero_heading, 'PERSONALISED SPECIALIST CARE FOR YOUR SMILE'),
        hero_subheading = COALESCE(hero_subheading, 'Dr. Amrit Jaishi provides dedicated, gentle, and modern dental treatments at Dhangadhi Sanjeevani Hospital.')
        WHERE id = 1");

    // 2. Qualifications columns
    $addColumn('qualifications', 'start_year', 'VARCHAR(20) NULL AFTER institution');
    $addColumn('qualifications', 'location', 'VARCHAR(150) NULL AFTER start_year');
    $addColumn('qualifications', 'certificate_image', 'VARCHAR(255) NULL AFTER description');
    $addColumn('qualifications', 'is_published', 'TINYINT(1) NOT NULL DEFAULT 1 AFTER display_order');

    // 3. Gallery Categories Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `gallery_categories` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(120) NOT NULL,
      `slug` VARCHAR(130) NOT NULL UNIQUE,
      `description` VARCHAR(255) NULL,
      `display_order` INT NOT NULL DEFAULT 0,
      `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Seed default gallery categories if empty
    $catCount = $pdo->query("SELECT COUNT(*) FROM `gallery_categories`")->fetchColumn();
    if ($catCount == 0) {
        $pdo->exec("INSERT INTO `gallery_categories` (`id`, `name`, `slug`, `description`, `display_order`) VALUES
            (1, 'Clinic Facilities', 'clinic-facilities', 'Modern dental operatories, sterilization, and patient lounges', 1),
            (2, 'Dental Procedures', 'dental-procedures', 'Sterile clinical operations and restorative dental care', 2),
            (3, 'Patient Smiles', 'patient-smiles', 'Restored smile transformations and happy patient smiles', 3),
            (4, 'Community Outreach', 'community-outreach', 'Oral health awareness drives and screening camps', 4);");
    }

    $addColumn('gallery_images', 'category_id', 'INT UNSIGNED NULL AFTER id');
    $addColumn('gallery_images', 'image_title', 'VARCHAR(200) NULL AFTER image_path');

    // 4. Researches Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `researches` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(255) NOT NULL,
      `slug` VARCHAR(255) NOT NULL UNIQUE,
      `summary` TEXT NULL,
      `abstract` TEXT NULL,
      `full_text` MEDIUMTEXT NULL,
      `authors` VARCHAR(255) NOT NULL DEFAULT 'Dr. Amrit Jaishi',
      `journal_name` VARCHAR(200) NULL,
      `publication_year` VARCHAR(30) NULL,
      `doi` VARCHAR(150) NULL,
      `publication_url` VARCHAR(255) NULL,
      `cover_image` VARCHAR(255) NULL,
      `pdf_file` VARCHAR(255) NULL,
      `display_order` INT NOT NULL DEFAULT 0,
      `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
      `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Seed sample research if empty
    $rCount = $pdo->query("SELECT COUNT(*) FROM `researches`")->fetchColumn();
    if ($rCount == 0) {
        $pdo->exec("INSERT INTO `researches` (`title`, `slug`, `summary`, `abstract`, `full_text`, `authors`, `journal_name`, `publication_year`, `doi`, `publication_url`, `status`) VALUES
            ('Clinical Evaluation of Single-Visit Rotary Endodontics in Symptomatic Irreversible Pulpitis',
             'single-visit-rotary-endodontics-evaluation',
             'A comparative observational study examining post-operative pain and clinical success in single-visit versus multiple-visit endodontic therapy using NiTi rotary files.',
             'Introduction: Rotary endodontic instrumentation has revolutionized modern endodontics. This observational study evaluates postoperative pain incidence and 12-month radiographic healing rates in 85 patients presenting with irreversible pulpitis in Farwest Nepal. Conclusion: Single-visit rotary treatment demonstrated equal healing rates and significantly reduced total patient appointments.',
             'Full clinical methodology, patient criteria, rotary file sequences, and statistical tables are documented in this prospective evaluation conducted across regional clinical centers.',
             'Dr. Amrit Jaishi, BDS (BPKIHS)',
             'Journal of Nepalese Dental Association (JNDA)',
             '2023',
             '10.3126/jnda.v23i1.48201',
             'https://example.org/dental-research/single-visit-endodontics',
             'published'),
            ('Prevalence of Periodontal Disease and Oral Health Awareness in Rural Communities of Kailali District',
             'periodontal-disease-prevalence-kailali',
             'Community dental epidemiological survey assessing CPITN scores, oral hygiene index, and dietary habits across rural municipalities.',
             'Abstract: A cross-sectional field study of 420 adults screened during regional mobile health camps. Results revealed high calculus accumulation and low fluoride toothpaste adoption, underscoring the urgent need for structured public health preventive scaling initiatives.',
             'Complete survey demographics, CPITN indices, community education workshops, and policy recommendations for Farwest healthcare initiatives.',
             'Dr. Amrit Jaishi, BDS (BPKIHS)',
             'Farwest Medical & Health Sciences Review',
             '2022',
             NULL,
             'https://example.org/health-studies/kailali-oral-survey',
             'published');");
    }

    // 5. Services alt_text
    $addColumn('services', 'alt_text', 'VARCHAR(255) NULL AFTER image');

    // 6. Clinics extra fields
    $addColumn('clinics', 'city', "VARCHAR(100) NULL DEFAULT 'Dhangadhi' AFTER full_address");
    $addColumn('clinics', 'working_days', "VARCHAR(100) NULL DEFAULT 'Sunday - Friday' AFTER phone");
    $addColumn('clinics', 'working_hours', "VARCHAR(100) NULL DEFAULT '10:00 AM - 5:00 PM' AFTER working_days");

    echo "MIGRATION COMPLETED SUCCESSFULLY!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
