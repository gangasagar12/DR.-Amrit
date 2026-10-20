<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    private static array $config = [];
    private static string $activeDriver = 'mysql';

    public static function init(array $config): void {
        self::$config = $config;
    }

    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        if (empty(self::$config)) {
            self::$config = require dirname(__DIR__, 2) . '/config/database.php';
        }

        $driver = self::$config['driver'] ?? 'mysql';

        if ($driver === 'mysql') {
            try {
                $host = self::$config['host'] ?? 'localhost';
                $port = self::$config['port'] ?? '3306';
                $dbName = self::$config['database'] ?? 'dental_doctor_db';
                $user = self::$config['username'] ?? 'root';
                $pass = self::$config['password'] ?? '';
                $charset = self::$config['charset'] ?? 'utf8mb4';

                // Try connecting to MySQL directly
                try {
                    $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ];
                    if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                        $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$charset}";
                    }
                    $pdo = new PDO($dsn, $user, $pass, $options);
                    self::$instance = $pdo;
                    self::$activeDriver = 'mysql';
                    return self::$instance;
                } catch (PDOException $e) {
                    // Check if error is "Unknown database" (1049)
                    if ($e->getCode() == 1049 || strpos($e->getMessage(), 'Unknown database') !== false) {
                        // Connect without dbname and create it
                        $dsnNoDb = "mysql:host={$host};port={$port};charset={$charset}";
                        $rootPdo = new PDO($dsnNoDb, $user, $pass, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ]);
                        $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE " . (self::$config['collation'] ?? 'utf8mb4_unicode_ci'));
                        
                        // Now connect to the newly created database
                        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
                        $pdo = new PDO($dsn, $user, $pass, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => false
                        ]);
                        
                        // Import database schema and seeds automatically
                        self::autoImportSchema($pdo, 'mysql');
                        self::$instance = $pdo;
                        self::$activeDriver = 'mysql';
                        return self::$instance;
                    }
                    throw $e;
                }
            } catch (PDOException $e) {
                // If MySQL is not running or failed, check for SQLite fallback for resilient operation
                if (!empty(self::$config['sqlite_path'])) {
                    $sqliteDir = dirname(self::$config['sqlite_path']);
                    if (!is_dir($sqliteDir)) {
                        @mkdir($sqliteDir, 0755, true);
                    }
                    $isNew = !file_exists(self::$config['sqlite_path']);
                    $pdo = new PDO('sqlite:' . self::$config['sqlite_path'], null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                    $pdo->exec('PRAGMA foreign_keys = ON;');
                    if (method_exists($pdo, 'sqliteCreateFunction')) {
                        @$pdo->sqliteCreateFunction('NOW', function() { return date('Y-m-d H:i:s'); });
                    }
                    self::ensureSqliteTables($pdo);
                    self::$instance = $pdo;
                    self::$activeDriver = 'sqlite';
                    return self::$instance;
                }
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        } elseif ($driver === 'sqlite') {
            $sqlitePath = self::$config['sqlite_path'] ?? (dirname(__DIR__, 2) . '/storage/database.sqlite');
            $sqliteDir = dirname($sqlitePath);
            if (!is_dir($sqliteDir)) {
                @mkdir($sqliteDir, 0755, true);
            }
            $pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON;');
            if (method_exists($pdo, 'sqliteCreateFunction')) {
                @$pdo->sqliteCreateFunction('NOW', function() { return date('Y-m-d H:i:s'); });
            }
            self::ensureSqliteTables($pdo);
            self::$instance = $pdo;
            self::$activeDriver = 'sqlite';
            return self::$instance;
        }

        throw new \Exception("Unsupported database driver: {$driver}");
    }

    public static function getDriver(): string {
        return self::$activeDriver;
    }

    /**
     * Automatically import schema if new database is initialized
     */
    public static function autoImportSchema(PDO $pdo, string $driver = 'mysql'): void {
        $dbDir = dirname(__DIR__, 2) . '/database';
        $schemaFile = $dbDir . '/database.sql';
        $seedFile = $dbDir . '/seed.sql';

        if ($driver === 'mysql' && file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $pdo->exec($sql);
            if (file_exists($seedFile)) {
                $seedSql = file_get_contents($seedFile);
                $pdo->exec($seedSql);
            }
        } elseif ($driver === 'sqlite') {
            // Create and verify all tables in SQLite
            self::ensureSqliteTables($pdo);
        }
    }

    public static function ensureSqliteTables(PDO $pdo): void {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admin_users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                remember_token TEXT NULL,
                is_active INTEGER NOT NULL DEFAULT 1,
                last_login_at TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS doctor_profile (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                doctor_name TEXT NOT NULL DEFAULT 'Dr. Amrit Jaishi',
                professional_title TEXT NOT NULL DEFAULT 'BDS (BPKIHS) - Oral & Dental Surgeon',
                nmc_number TEXT NULL,
                tagline TEXT NOT NULL DEFAULT 'Dedicated to patient-centered dental care',
                biography TEXT NOT NULL,
                philosophy TEXT NULL,
                core_principles TEXT NULL,
                experience_summary TEXT NULL,
                clinical_focus TEXT NULL,
                experience_years INTEGER NOT NULL DEFAULT 4,
                consultation_fees TEXT NULL,
                patient_care_approach TEXT NULL,
                languages TEXT NOT NULL DEFAULT 'Nepali, English, Hindi',
                profile_image TEXT NULL,
                hero_banner_image TEXT NULL,
                clinic_cover_image TEXT NULL,
                contact_email TEXT NOT NULL,
                phone TEXT NOT NULL,
                whatsapp TEXT NOT NULL,
                emergency_contact TEXT NULL,
                cta_primary_label TEXT NULL,
                cta_primary_link TEXT NULL,
                cta_secondary_label TEXT NULL,
                cta_secondary_link TEXT NULL,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS qualifications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                degree TEXT NOT NULL,
                institution TEXT NOT NULL,
                completion_year TEXT NOT NULL,
                description TEXT NULL,
                display_order INTEGER NOT NULL DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS services (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                category TEXT NOT NULL DEFAULT 'General Dentistry',
                short_description TEXT NOT NULL,
                full_description TEXT NOT NULL,
                benefits TEXT NULL,
                procedure_steps TEXT NULL,
                image TEXT NULL,
                icon TEXT NOT NULL DEFAULT 'activity',
                is_active INTEGER NOT NULL DEFAULT 1,
                is_featured INTEGER NOT NULL DEFAULT 0,
                display_order INTEGER NOT NULL DEFAULT 0,
                meta_title TEXT NULL,
                meta_description TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS achievements (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                record_type TEXT NOT NULL DEFAULT 'Certification',
                organization TEXT NOT NULL,
                achievement_date TEXT NOT NULL,
                description TEXT NULL,
                certificate_image TEXT NULL,
                verification_url TEXT NULL,
                display_order INTEGER NOT NULL DEFAULT 0,
                is_active INTEGER NOT NULL DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS gallery_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT NULL,
                display_order INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS gallery_images (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NULL,
                image_path TEXT NOT NULL,
                image_title TEXT NULL,
                caption TEXT NOT NULL,
                alt_text TEXT NOT NULL,
                category TEXT NOT NULL DEFAULT 'Clinic Facility',
                display_order INTEGER NOT NULL DEFAULT 0,
                is_active INTEGER NOT NULL DEFAULT 1,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS blog_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS blogs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NULL,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                content_type TEXT NOT NULL DEFAULT 'Educational Article',
                excerpt TEXT NOT NULL,
                content_html TEXT NOT NULL,
                featured_image TEXT NULL,
                image_alt TEXT NULL,
                author_name TEXT NOT NULL DEFAULT 'Dr. Amrit Jaishi',
                journal_name TEXT NULL,
                publication_year TEXT NULL,
                doi TEXT NULL,
                publication_url TEXT NULL,
                references_text TEXT NULL,
                meta_title TEXT NULL,
                meta_description TEXT NULL,
                status TEXT NOT NULL DEFAULT 'Published',
                published_at TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
                deleted_at TEXT NULL
            );
            CREATE TABLE IF NOT EXISTS clinics (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                clinic_name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                doctor_role TEXT NOT NULL DEFAULT 'Dental Surgeon',
                workplace_type TEXT NOT NULL DEFAULT 'Permanent Workplace',
                full_address TEXT NOT NULL,
                phone TEXT NOT NULL,
                website_url TEXT NULL,
                map_url TEXT NULL,
                image TEXT NULL,
                description TEXT NULL,
                is_active INTEGER NOT NULL DEFAULT 1,
                display_order INTEGER NOT NULL DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS clinic_schedules (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                clinic_id INTEGER NOT NULL,
                visiting_day TEXT NOT NULL,
                start_time TEXT NOT NULL,
                end_time TEXT NOT NULL,
                notes TEXT NULL
            );
            CREATE TABLE IF NOT EXISTS testimonials (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                patient_name TEXT NULL,
                patient_display_name TEXT NULL,
                treatment_type TEXT NOT NULL DEFAULT 'Dental Care',
                quote TEXT NULL,
                testimonial_text TEXT NULL,
                rating INTEGER NOT NULL DEFAULT 5,
                is_verified INTEGER NOT NULL DEFAULT 1,
                consent_status INTEGER NOT NULL DEFAULT 1,
                is_approved INTEGER NOT NULL DEFAULT 1,
                display_order INTEGER NOT NULL DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS appointments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                patient_name TEXT NOT NULL,
                phone TEXT NOT NULL,
                email TEXT NULL,
                service_id INTEGER NULL,
                clinic_id INTEGER NULL,
                preferred_date TEXT NOT NULL,
                preferred_time TEXT NOT NULL,
                message TEXT NULL,
                status TEXT NOT NULL DEFAULT 'Pending',
                internal_notes TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS contact_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT NULL,
                subject TEXT NOT NULL,
                message TEXT NOT NULL,
                is_read INTEGER NOT NULL DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS site_settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_key TEXT NOT NULL UNIQUE,
                setting_value TEXT NULL,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS video_categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                display_order INTEGER DEFAULT 0,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS videos (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                category_id INTEGER NULL,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                description TEXT NULL,
                video_type TEXT DEFAULT 'youtube',
                video_url TEXT NULL,
                video_file TEXT NULL,
                thumbnail TEXT NULL,
                duration TEXT NULL,
                display_order INTEGER DEFAULT 0,
                status TEXT DEFAULT 'published',
                published_at TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS researches (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                summary TEXT NULL,
                abstract TEXT NULL,
                full_text TEXT NULL,
                authors TEXT NOT NULL DEFAULT 'Dr. Amrit Jaishi',
                journal_name TEXT NULL,
                publication_year TEXT NULL,
                doi TEXT NULL,
                publication_url TEXT NULL,
                cover_image TEXT NULL,
                pdf_file TEXT NULL,
                display_order INTEGER NOT NULL DEFAULT 0,
                status TEXT NOT NULL DEFAULT 'published',
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS password_resets (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL,
                token TEXT NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                expires_at TEXT NOT NULL
            );
        ");

        // Seed basic admin user if empty
        $adminCount = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
        if ($adminCount === 0) {
            $passHash = password_hash('Admin@12345', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admin_users (id, name, email, password_hash, is_active) VALUES (1, 'Dr. Amrit Jaishi', 'admin@dentaldoctor.com', ?, 1)");
            $stmt->execute([$passHash]);
        }

        // Seed video categories if empty
        $vCatCount = (int)$pdo->query("SELECT COUNT(*) FROM video_categories")->fetchColumn();
        if ($vCatCount === 0) {
            $pdo->exec("
                INSERT INTO video_categories (id, name, slug, display_order) VALUES
                (1, 'Oral Health Education', 'oral-health-education', 1),
                (2, 'Dental Treatment Awareness', 'dental-treatment-awareness', 2),
                (3, 'Preventive Dentistry', 'preventive-dentistry', 3),
                (4, 'Clinical Practice & Surgery', 'clinical-practice', 4),
                (5, 'Patient Care & FAQs', 'patient-faqs', 5);
            ");
        }

        // Seed sample educational videos if empty
        $videoCount = (int)$pdo->query("SELECT COUNT(*) FROM videos")->fetchColumn();
        if ($videoCount === 0) {
            $pdo->exec("
                INSERT INTO videos (id, category_id, title, slug, description, video_type, video_url, thumbnail, duration, display_order, status, published_at) VALUES
                (1, 1, 'Proper Tooth Brushing & Flossing Technique for Healthy Gums', 'proper-tooth-brushing-and-flossing-technique', 'Dr. Amrit Jaishi demonstrates the modified Bass brushing method and correct flossing habits to prevent plaque accumulation and gingivitis.', 'youtube', 'https://www.youtube.com/watch?v=xm9c5HAUBpY', 'img/services/teeth_cleaning.jpg', '03:45', 1, 'published', datetime('now')),
                (2, 2, 'What to Expect During a Single-Visit Root Canal Treatment', 'what-to-expect-during-root-canal-treatment', 'A step-by-step patient walkthrough explaining modern painless rotary endodontic therapy, rubber dam isolation, and crown restoration.', 'youtube', 'https://www.youtube.com/watch?v=PDFHQKVIJpo', 'img/services/rct.jpg', '05:20', 2, 'published', datetime('now')),
                (3, 3, 'Understanding Ultrasonic Scaling & Tartar Removal', 'understanding-ultrasonic-scaling-tartar-removal', 'Learn why routine professional scaling does not weaken your enamel, and how it protects supportive bone from periodontitis.', 'youtube', 'https://www.youtube.com/watch?v=sMoi6laLS8A', 'img/services/gum_care.jpg', '04:12', 3, 'published', datetime('now')),
                (4, 4, 'Atraumatic Wisdom Tooth Management & Surgical Care', 'atraumatic-wisdom-tooth-management', 'Clinical insights into diagnostic evaluation of impacted third molars, gentle surgical sectioning, and post-operative healing instructions.', 'youtube', 'https://www.youtube.com/watch?v=wRpHeXVn_Nw', 'img/services/wisdom_tooth.jpg', '06:05', 4, 'published', datetime('now'));
            ");
        }

        // Seed sample research papers if empty
        $resCount = (int)$pdo->query("SELECT COUNT(*) FROM researches")->fetchColumn();
        if ($resCount === 0) {
            $pdo->exec("
                INSERT INTO researches (id, title, slug, summary, abstract, authors, journal_name, publication_year, doi, display_order, status) VALUES
                (1, 'Clinical Evaluation of Single-Visit Rotary Endodontics in Symptomatic Irreversible Pulpitis', 'single-visit-rotary-endodontics-evaluation', 'A comparative observational study examining post-operative pain and clinical success in single-visit versus multiple-visit endodontic therapy using NiTi rotary files.', 'Introduction: Rotary endodontic instrumentation has revolutionized modern endodontics. This observational study evaluates postoperative pain incidence and 12-month radiographic healing rates in 85 patients presenting with irreversible pulpitis in Farwest Nepal.', 'Dr. Amrit Jaishi, BDS (BPKIHS)', 'Journal of Nepalese Dental Association (JNDA)', '2023', '10.3126/jnda.v23i1.48201', 1, 'published'),
                (2, 'Prevalence of Periodontal Disease and Oral Health Awareness in Rural Communities of Kailali District', 'periodontal-disease-prevalence-kailali', 'Community dental epidemiological survey assessing CPITN scores, oral hygiene index, and dietary habits across rural municipalities.', 'Abstract: A cross-sectional field study of 420 adults screened during regional mobile health camps. Results revealed high calculus accumulation and low fluoride toothpaste adoption.', 'Dr. Amrit Jaishi, BDS (BPKIHS)', 'Farwest Medical & Health Sciences Review', '2022', NULL, 2, 'published');
            ");
        }

        // Auto-migrate missing columns for existing SQLite tables
        self::ensureColumnsExist($pdo, 'doctor_profile', [
            'hero_heading' => 'TEXT NULL',
            'hero_subheading' => 'TEXT NULL',
            'hero_lead' => 'TEXT NULL',
            'hero_approach' => 'TEXT NULL',
            'about_kicker' => 'TEXT NULL',
            'about_subheading' => 'TEXT NULL',
            'about_badge_title' => 'TEXT NULL',
            'about_badge_subtitle' => 'TEXT NULL',
            'hero_image' => 'TEXT NULL',
            'about_image' => 'TEXT NULL',
            'hero_btn1_text' => 'TEXT NULL DEFAULT "View Dental Services"',
            'hero_btn1_link' => 'TEXT NULL DEFAULT "#services"',
            'hero_btn2_text' => 'TEXT NULL DEFAULT "Contact Doctor"',
            'hero_btn2_link' => 'TEXT NULL DEFAULT "#contact"',
            'nmc_number' => 'TEXT NULL',
            'consultation_fees' => 'TEXT NULL',
            'patient_care_approach' => 'TEXT NULL',
            'hero_banner_image' => 'TEXT NULL',
            'clinic_cover_image' => 'TEXT NULL',
            'cta_primary_label' => 'TEXT NULL',
            'cta_primary_link' => 'TEXT NULL',
            'cta_secondary_label' => 'TEXT NULL',
            'cta_secondary_link' => 'TEXT NULL'
        ]);

        self::ensureColumnsExist($pdo, 'gallery_images', [
            'category_id' => 'INTEGER NULL',
            'image_title' => 'TEXT NULL'
        ]);

        self::ensureColumnsExist($pdo, 'testimonials', [
            'patient_name' => 'TEXT NULL',
            'quote' => 'TEXT NULL',
            'is_verified' => 'INTEGER NOT NULL DEFAULT 1'
        ]);

        self::ensureColumnsExist($pdo, 'admin_users', [
            'remember_token' => 'TEXT NULL'
        ]);
    }

    private static function ensureColumnsExist(PDO $pdo, string $table, array $columns): void {
        try {
            $existing = $pdo->query("PRAGMA table_info({$table})")->fetchAll(PDO::FETCH_COLUMN, 1);
            foreach ($columns as $col => $definition) {
                if (!in_array($col, $existing, true)) {
                    @$pdo->exec("ALTER TABLE {$table} ADD COLUMN {$col} {$definition}");
                }
            }
        } catch (\Throwable $e) {}
    }
}
