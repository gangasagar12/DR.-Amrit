<?php
namespace App\Core;

class Session {
    private static bool $started = false;

    public static function start(): void {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $lifetime = $config['session_lifetime'] ?? 7200;
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

        session_name($config['session_name'] ?? 'dental_doctor_sess');

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        session_start();
        self::$started = true;

        // Check for idle timeout
        if (isset($_SESSION['__last_activity'])) {
            if (time() - $_SESSION['__last_activity'] > $lifetime) {
                self::destroy();
                session_start();
            }
        }
        $_SESSION['__last_activity'] = time();
    }

    public static function set(string $key, mixed $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function regenerate(): void {
        self::start();
        session_regenerate_id(true);
    }

    public static function destroy(): void {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        self::$started = false;
    }

    /**
     * Flash messaging (messages available for exactly the next request)
     */
    public static function flash(string $key, mixed $message): void {
        self::start();
        $_SESSION['__flash'][$key] = $message;
    }

    public static function getFlash(string $key, mixed $default = null): mixed {
        self::start();
        if (isset($_SESSION['__flash'][$key])) {
            $val = $_SESSION['__flash'][$key];
            unset($_SESSION['__flash'][$key]);
            return $val;
        }
        return $default;
    }

    public static function hasFlash(string $key): bool {
        self::start();
        return isset($_SESSION['__flash'][$key]);
    }
}
