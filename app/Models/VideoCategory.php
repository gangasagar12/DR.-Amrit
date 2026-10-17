<?php
namespace App\Models;

use App\Core\Database;

class VideoCategory {
    public static function all(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM video_categories ORDER BY display_order ASC, name ASC");
        return $stmt->fetchAll();
    }

    public static function allActive(): array {
        return self::all();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM video_categories WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM video_categories WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO video_categories (name, slug, display_order) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['slug'],
            (int)($data['display_order'] ?? 0)
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE video_categories SET name = ?, slug = ?, display_order = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            (int)($data['display_order'] ?? 0),
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        // Remove foreign category references
        $pdo->prepare("UPDATE videos SET category_id = NULL WHERE category_id = ?")->execute([$id]);
        $stmt = $pdo->prepare("DELETE FROM video_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
