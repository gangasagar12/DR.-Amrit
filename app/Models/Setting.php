<?php
namespace App\Models;

use App\Core\Database;

class Setting {
    private static ?array $cache = null;

    public static function all(): array {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
        $rows = $stmt->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        self::$cache = $settings;
        return self::$cache;
    }

    public static function get(string $key, mixed $default = ''): mixed {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void {
        $pdo = Database::getConnection();
        $driver = Database::getDriver();

        if ($driver === 'sqlite') {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value");
        } else {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        }

        $stmt->execute([$key, $value]);
        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }
    }

    public static function setMany(array $settings): void {
        foreach ($settings as $key => $val) {
            self::set($key, is_array($val) ? json_encode($val) : (string)$val);
        }
        self::$cache = null;
    }
}
