<?php
namespace App\Core;

use App\Models\User;

class Auth {
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutes

    public static function check(): bool {
        Session::start();
        if (Session::has('admin_user_id') && Session::get('admin_user_id') > 0) {
            return true;
        }

        // Automatic restoration from 1-year remember token cookie
        $cookieToken = $_COOKIE['dental_remember_token'] ?? null;
        if (!empty($cookieToken) && is_string($cookieToken) && strlen($cookieToken) >= 32) {
            $user = User::findByRememberToken($cookieToken);
            if ($user && !empty($user['is_active'])) {
                Session::set('admin_user_id', (int)$user['id']);
                Session::set('admin_user_name', $user['name']);
                Session::set('admin_user_email', $user['email']);
                User::updateLastLogin((int)$user['id']);
                return true;
            }
        }

        return false;
    }

    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }
        $userId = Session::get('admin_user_id');
        return User::findById($userId);
    }

    public static function id(): ?int {
        return Session::get('admin_user_id');
    }

    public static function attempt(string $email, string $password): array {
        Session::start();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $throttleKey = 'login_throttle_' . md5($ip . '_' . strtolower($email));
        $throttleData = Session::get($throttleKey, ['count' => 0, 'last_time' => 0]);

        $isLocal = in_array($ip, ['127.0.0.1', '::1', 'localhost']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;

        // Check if locked out (only on remote production)
        if (!$isLocal && $throttleData['count'] >= self::MAX_LOGIN_ATTEMPTS) {
            $timePassed = time() - $throttleData['last_time'];
            if ($timePassed < self::LOCKOUT_TIME) {
                $waitMinutes = ceil((self::LOCKOUT_TIME - $timePassed) / 60);
                return [
                    'success' => false,
                    'message' => "Too many failed attempts. Please wait {$waitMinutes} minute(s) before trying again."
                ];
            } else {
                // Reset throttle after lockout period
                $throttleData = ['count' => 0, 'last_time' => 0];
            }
        }

        $user = User::findDoctorOrAdminByEmail($email);

        $passwordValid = false;
        if ($user && isset($user['password_hash'])) {
            $passwordValid = password_verify($password, $user['password_hash'])
                || password_verify(trim($password), $user['password_hash']);
        }

        if (!$user || !$passwordValid) {
            $throttleData['count']++;
            $throttleData['last_time'] = time();
            Session::set($throttleKey, $throttleData);

            return [
                'success' => false,
                'message' => 'Invalid username/email or password.'
            ];
        }

        if (empty($user['is_active'])) {
            return [
                'success' => false,
                'message' => 'This account has been deactivated. Contact the system administrator.'
            ];
        }

        // Reset throttle on successful authentication
        Session::remove($throttleKey);

        // Session fixation protection
        Session::regenerate();

        Session::set('admin_user_id', (int)$user['id']);
        Session::set('admin_user_name', $user['name']);
        Session::set('admin_user_email', $user['email']);

        // Update last login timestamp
        User::updateLastLogin((int)$user['id']);

        // Generate and set persistent 1-year remember token
        $token = bin2hex(random_bytes(32));
        User::updateRememberToken((int)$user['id'], $token);

        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        setcookie('dental_remember_token', $token, [
            'expires' => time() + 31536000, // 1 year
            'path' => '/',
            'domain' => '',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public static function logout(): void {
        Session::start();
        $userId = Session::get('admin_user_id');
        if ($userId) {
            User::updateRememberToken((int)$userId, null);
        }

        if (isset($_COOKIE['dental_remember_token'])) {
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie('dental_remember_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'domain' => '',
                'secure' => $isSecure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            unset($_COOKIE['dental_remember_token']);
        }

        Session::remove('admin_user_id');
        Session::remove('admin_user_name');
        Session::remove('admin_user_email');
        Session::destroy();
    }

    public static function requireAdmin(): void {
        if (!self::check()) {
            Session::flash('error', 'Please log in to access the administration panel.');
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $loginUrl = ($config['base_path'] ?? '') . '/admin/login';
            header("Location: {$loginUrl}");
            exit;
        }
    }
}
