<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Service {
    public static function allActive(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    }

    public static function featured(int $limit = 4): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = 1 AND is_featured = 1 ORDER BY display_order ASC, id ASC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        
        // If not enough featured, fill with top active
        if (count($results) < $limit) {
            $stmt = $pdo->prepare("SELECT * FROM services WHERE is_active = 1 ORDER BY display_order ASC, id ASC LIMIT ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        return $results;
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM services ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function generateSlug(string $name, ?int $excludeId = null): string {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
        if (empty($base)) {
            $base = 'service';
        }

        $pdo = Database::getConnection();
        $slug = $base;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM services WHERE slug = ?";
            $params = [$slug];
            if ($excludeId !== null) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            if (!$stmt->fetch()) {
                return $slug;
            }
            $slug = $base . '-' . $counter;
            $counter++;
        }
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($data['name']);
        
        $stmt = $pdo->prepare("INSERT INTO services (
            name, slug, category, short_description, full_description,
            benefits, procedure_steps, image, icon, is_active, is_featured,
            display_order, meta_title, meta_description, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $data['name'],
            $slug,
            $data['category'] ?? 'General Dentistry',
            $data['short_description'] ?? '',
            $data['full_description'] ?? '',
            $data['benefits'] ?? '',
            $data['procedure_steps'] ?? '',
            $data['image'] ?? null,
            $data['icon'] ?? 'activity',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
            (int)($data['display_order'] ?? 0),
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        
        $sql = "UPDATE services SET 
            name = ?,
            category = ?,
            short_description = ?,
            full_description = ?,
            benefits = ?,
            procedure_steps = ?,
            icon = ?,
            is_active = ?,
            is_featured = ?,
            display_order = ?,
            meta_title = ?,
            meta_description = ?";
        
        $params = [
            $data['name'],
            $data['category'] ?? 'General Dentistry',
            $data['short_description'] ?? '',
            $data['full_description'] ?? '',
            $data['benefits'] ?? '',
            $data['procedure_steps'] ?? '',
            $data['icon'] ?? 'activity',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            isset($data['is_featured']) ? (int)$data['is_featured'] : 0,
            (int)($data['display_order'] ?? 0),
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null
        ];

        if (!empty($data['slug'])) {
            $sql .= ", slug = ?";
            $params[] = self::generateSlug($data['slug'], $id);
        }

        if (!empty($data['image'])) {
            $sql .= ", image = ?";
            $params[] = $data['image'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
    }
}
