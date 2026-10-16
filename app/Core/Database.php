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
                    if ($isNew) {
                        self::autoImportSchema($pdo, 'sqlite');
                    }
                    self::$instance = $pdo;
                    self::$activeDriver = 'sqlite';
                    return self::$instance;
                }
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        } elseif ($driver === 'sqlite') {
            $sqlitePath = self::$config['sqlite_path'] ?? (dirname(__DIR__, 2) . '/storage/database.sqlite');
            $isNew = !file_exists($sqlitePath);
            $pdo = new PDO('sqlite:' . $sqlitePath, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON;');
            if ($isNew) {
                self::autoImportSchema($pdo, 'sqlite');
            }
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
            // Create essential tables in SQLite if running fallback
            self::initSqliteTables($pdo);
        }
    }

    private static function initSqliteTables(PDO $pdo): void {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admin_users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                is_active INTEGER NOT NULL DEFAULT 1,
                last_login_at TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS doctor_profile (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                doctor_name TEXT NOT NULL DEFAULT 'Dr. Jane Doe',
                professional_title TEXT NOT NULL DEFAULT 'BDS, MDS - Dental Surgeon',
                tagline TEXT NOT NULL DEFAULT 'Crafting Confident, Healthy Smiles',
                biography TEXT NOT NULL,
                experience_years INTEGER NOT NULL DEFAULT 12,
                experience_description TEXT NULL,
                philosophy TEXT NULL,
                languages TEXT NOT NULL DEFAULT 'English',
                profile_image TEXT NULL,
                contact_email TEXT NOT NULL,
                phone TEXT NOT NULL,
                whatsapp TEXT NOT NULL,
                emergency_contact TEXT NULL,
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
            CREATE TABLE IF NOT EXISTS gallery_images (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                image_path TEXT NOT NULL,
                caption TEXT NOT NULL,
                alt_text TEXT NOT NULL,
                category TEXT NOT NULL DEFAULT 'Clinic & Facilities',
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
                author_name TEXT NOT NULL DEFAULT 'Dr. Sarah Jenkins',
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
                patient_display_name TEXT NOT NULL,
                treatment_type TEXT NOT NULL DEFAULT 'Dental Care',
                testimonial_text TEXT NOT NULL,
                rating INTEGER NOT NULL DEFAULT 5,
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
        ");

        // Seed basic admin into sqlite
        $passHash = password_hash('Admin@12345', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO admin_users (id, name, email, password_hash, is_active) VALUES (1, 'Dr. Sarah Jenkins', 'admin@dentaldoctor.com', ?, 1)");
        $stmt->execute([$passHash]);
    }
}
