<?php
namespace App\Models;

use App\Core\Database;

class Category {
    public static function all(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT c.*, COUNT(b.id) as blog_count 
            FROM blog_categories c 
            LEFT JOIN blogs b ON b.category_id = c.id AND b.status = 'Published' AND b.deleted_at IS NULL
            GROUP BY c.id 
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM blog_categories WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM blog_categories WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function generateSlug(string $name, ?int $excludeId = null): string {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
        if (empty($base)) $base = 'category';

        $pdo = Database::getConnection();
        $slug = $base;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM blog_categories WHERE slug = ?";
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

    public static function create(string $name, ?string $description = null): int {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($name);
        $stmt = $pdo->prepare("INSERT INTO blog_categories (name, slug, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([trim($name), $slug, $description ? trim($description) : null]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, string $name, ?string $description = null): bool {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($name, $id);
        $stmt = $pdo->prepare("UPDATE blog_categories SET name = ?, slug = ?, description = ? WHERE id = ?");
        return $stmt->execute([trim($name), $slug, $description ? trim($description) : null, $id]);
    }

    public static function delete(int $id): array {
        $pdo = Database::getConnection();
        // Check if articles are assigned
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE category_id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        $count = (int)$stmt->fetchColumn();

        if ($count > 0) {
            return [
                'success' => false,
                'message' => "Cannot delete category: {$count} article(s) are currently assigned to it. Please reassign them first."
            ];
        }

        $del = $pdo->prepare("DELETE FROM blog_categories WHERE id = ?");
        $del->execute([$id]);
        return ['success' => true, 'message' => 'Category deleted successfully.'];
    }
}
