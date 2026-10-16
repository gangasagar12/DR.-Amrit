<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PasswordReset {
    /**
     * Create a new password reset token record.
     * Removes existing reset tokens for the user/email first to prevent duplicates.
     */
    public static function create(int $userId, string $email, string $tokenHash, int $expiryMinutes = 60): bool {
        $pdo = Database::getConnection();
        
        // Remove existing tokens for this user/email
        self::deleteForUser($userId);
        
        $stmt = $pdo->prepare("
            INSERT INTO password_resets (user_id, email, token_hash, expires_at, created_at)
            VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), NOW())
        ");
        
        return $stmt->execute([
            $userId,
            strtolower(trim($email)),
            $tokenHash,
            $expiryMinutes
        ]);
    }

    /**
     * Locate a valid, non-expired password reset record by token hash.
     */
    public static function findValidToken(string $tokenHash): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT * FROM password_resets 
            WHERE token_hash = ? AND expires_at > NOW() 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$tokenHash]);
        $record = $stmt->fetch();
        return $record ?: null;
    }

    /**
     * Delete reset records for a given user ID.
     */
    public static function deleteForUser(int $userId): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

    /**
     * Delete reset records for a given email.
     */
    public static function deleteByEmail(string $email): void {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([strtolower(trim($email))]);
    }

    /**
     * Clean up all expired tokens.
     */
    public static function cleanExpired(): void {
        $pdo = Database::getConnection();
        $pdo->query("DELETE FROM password_resets WHERE expires_at <= NOW()");
    }
}
