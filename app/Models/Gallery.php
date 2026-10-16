<?php
namespace App\Models;

use App\Core\Database;

class Gallery {
    public static function allActive(?string $category = null): array {
        $pdo = Database::getConnection();
        if ($category && $category !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE is_active = 1 AND category = ? ORDER BY display_order ASC, id DESC");
            $stmt->execute([$category]);
        } else {
            $stmt = $pdo->query("SELECT * FROM gallery_images WHERE is_active = 1 ORDER BY display_order ASC, id DESC");
        }
        return $stmt->fetchAll();
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM gallery_images ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO gallery_images (
            image_path, caption, alt_text, category, display_order, is_active, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $data['image_path'],
            $data['caption'] ?? '',
            $data['alt_text'] ?? $data['caption'] ?? 'Dental gallery image',
            $data['category'] ?? 'Clinic & Facilities',
            (int)($data['display_order'] ?? 0),
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM gallery_images WHERE is_active = 1")->fetchColumn();
    }
}
