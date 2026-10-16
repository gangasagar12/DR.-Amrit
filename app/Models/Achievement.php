<?php
namespace App\Models;

use App\Core\Database;

class Achievement {
    public static function allActive(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM achievements WHERE is_active = 1 ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM achievements ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM achievements WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO achievements (
            title, record_type, organization, achievement_date,
            description, certificate_image, verification_url, display_order, is_active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['title'],
            $data['record_type'] ?? 'Certification',
            $data['organization'],
            $data['achievement_date'],
            $data['description'] ?? '',
            $data['certificate_image'] ?? null,
            $data['verification_url'] ?? null,
            (int)($data['display_order'] ?? 0),
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $sql = "UPDATE achievements SET 
            title = ?,
            record_type = ?,
            organization = ?,
            achievement_date = ?,
            description = ?,
            verification_url = ?,
            display_order = ?,
            is_active = ?";
        
        $params = [
            $data['title'],
            $data['record_type'] ?? 'Certification',
            $data['organization'],
            $data['achievement_date'],
            $data['description'] ?? '',
            $data['verification_url'] ?? null,
            (int)($data['display_order'] ?? 0),
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ];

        if (!empty($data['certificate_image'])) {
            $sql .= ", certificate_image = ?";
            $params[] = $data['certificate_image'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM achievements WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
