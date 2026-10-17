<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Message {
    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
        $stmt->execute([
            trim($data['name']),
            trim($data['email']),
            !empty($data['phone']) ? trim($data['phone']) : null,
            trim($data['subject']),
            trim($data['message'])
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY is_read ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function markAsRead(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function markAsUnread(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countUnread(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
    }

    public static function recent(int $limit = 5): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countTotal(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
    }
}
