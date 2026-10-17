<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->execute([strtolower(trim($email))]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findDoctorOrAdminByEmail(string $identifier): ?array {
        $identifier = strtolower(trim($identifier));
        if (empty($identifier)) {
            return null;
        }

        // 1. Match directly by email
        $user = self::findByEmail($identifier);
        if ($user) {
            return $user;
        }

        // 2. Match by username 'admin', 'administrator', or user display name
        $pdo = Database::getConnection();
        if ($identifier === 'admin' || $identifier === 'administrator' || $identifier === 'root') {
            $stmt = $pdo->query("SELECT * FROM admin_users WHERE is_active = 1 ORDER BY id ASC LIMIT 1");
            $admin = $stmt->fetch();
            if ($admin) return $admin;
        }

        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE LOWER(name) = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$identifier]);
        $userByName = $stmt->fetch();
        if ($userByName) {
            return $userByName;
        }

        // 3. Match against doctor profile contact email
        $profile = DoctorProfile::get();
        if (!empty($profile['contact_email']) && strtolower(trim($profile['contact_email'])) === $identifier) {
            $stmt = $pdo->query("SELECT * FROM admin_users WHERE is_active = 1 ORDER BY id ASC LIMIT 1");
            $admin = $stmt->fetch();
            return $admin ?: null;
        }

        return null;
    }

    public static function updateLastLogin(int $id): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE admin_users SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function findByRememberToken(string $token): ?array {
        if (empty($token) || strlen($token) < 20) {
            return null;
        }
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE remember_token = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function updateRememberToken(int $id, ?string $token): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE admin_users SET remember_token = ? WHERE id = ?");
        return $stmt->execute([$token, $id]);
    }

    public static function updatePassword(int $id, string $newPassword): bool {
        $pdo = Database::getConnection();
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public static function updateAccount(int $id, string $name, string $email): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE admin_users SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([trim($name), strtolower(trim($email)), $id]);
    }

    public static function isEmailTaken(string $email, int $excludeUserId): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE email = ? AND id != ?");
        $stmt->execute([strtolower(trim($email)), $excludeUserId]);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    public static function verifyPassword(int $id, string $plainPassword): bool {
        $user = self::findById($id);
        if (!$user || empty($user['password_hash'])) {
            return false;
        }
        return password_verify($plainPassword, $user['password_hash'])
            || password_verify(trim($plainPassword), $user['password_hash']);
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
    }

    public static function create(string $name, string $email, string $password): int {
        $pdo = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (name, email, password_hash, is_active, created_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->execute([$name, strtolower(trim($email)), $hash]);
        return (int)$pdo->lastInsertId();
    }
}
