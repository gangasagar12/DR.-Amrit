<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Blog {
    public static function allPublished(int $page = 1, int $perPage = 6, ?int $categoryId = null, ?string $contentType = null, ?string $search = null): array {
        $pdo = Database::getConnection();
        $offset = ($page - 1) * $perPage;

        $where = ["b.status = 'Published'", "b.deleted_at IS NULL"];
        $params = [];

        if ($categoryId) {
            $where[] = "b.category_id = ?";
            $params[] = $categoryId;
        }

        if ($contentType) {
            $where[] = "b.content_type = ?";
            $params[] = $contentType;
        }

        if ($search) {
            $where[] = "(b.title LIKE ? OR b.excerpt LIKE ? OR b.content_html LIKE ?)";
            $term = '%' . trim($search) . '%';
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $whereSql = implode(' AND ', $where);

        // Count total
        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs b WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Fetch records
        $sql = "
            SELECT b.*, c.name as category_name, c.slug as category_slug
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE {$whereSql}
            ORDER BY b.published_at DESC, b.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($sql);
        $pIndex = 1;
        foreach ($params as $p) {
            $stmt->bindValue($pIndex++, $p);
        }
        $stmt->bindValue($pIndex++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($pIndex++, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    public static function latest(int $limit = 3): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name, c.slug as category_slug
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE b.status = 'Published' AND b.deleted_at IS NULL
            ORDER BY b.published_at DESC, b.id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name, c.slug as category_slug
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE b.slug = ? AND b.status = 'Published' AND b.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([$slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name, c.slug as category_slug
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE b.id = ? AND b.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function related(int $currentId, ?int $categoryId, int $limit = 3): array {
        $pdo = Database::getConnection();
        if ($categoryId) {
            $stmt = $pdo->prepare("
                SELECT b.*, c.name as category_name, c.slug as category_slug
                FROM blogs b
                LEFT JOIN blog_categories c ON c.id = b.category_id
                WHERE b.id != ? AND b.category_id = ? AND b.status = 'Published' AND b.deleted_at IS NULL
                ORDER BY b.published_at DESC
                LIMIT ?
            ");
            $stmt->bindValue(1, $currentId, PDO::PARAM_INT);
            $stmt->bindValue(2, $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(3, $limit, PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetchAll();
            if (!empty($res)) return $res;
        }

        // Fallback to latest
        $stmt = $pdo->prepare("
            SELECT b.*, c.name as category_name, c.slug as category_slug
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE b.id != ? AND b.status = 'Published' AND b.deleted_at IS NULL
            ORDER BY b.published_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $currentId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function generateSlug(string $title, ?int $excludeId = null): string {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
        if (empty($base)) $base = 'article';

        $pdo = Database::getConnection();
        $slug = $base;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM blogs WHERE slug = ? AND deleted_at IS NULL";
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

    public static function allAdmin(int $page = 1, int $perPage = 10, ?string $status = null, ?string $search = null): array {
        $pdo = Database::getConnection();
        $offset = ($page - 1) * $perPage;

        $where = ["b.deleted_at IS NULL"];
        $params = [];

        if ($status && in_array($status, ['Published', 'Draft'])) {
            $where[] = "b.status = ?";
            $params[] = $status;
        }

        if ($search) {
            $where[] = "(b.title LIKE ? OR b.author_name LIKE ?)";
            $term = '%' . trim($search) . '%';
            $params[] = $term;
            $params[] = $term;
        }

        $whereSql = implode(' AND ', $where);

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM blogs b WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT b.*, c.name as category_name
            FROM blogs b
            LEFT JOIN blog_categories c ON c.id = b.category_id
            WHERE {$whereSql}
            ORDER BY b.id DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $pdo->prepare($sql);
        $pIndex = 1;
        foreach ($params as $p) {
            $stmt->bindValue($pIndex++, $p);
        }
        $stmt->bindValue($pIndex++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($pIndex++, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($data['title']);
        $status = ($data['status'] === 'Draft') ? 'Draft' : 'Published';
        $publishedAt = ($status === 'Published') ? date('Y-m-d H:i:s') : null;

        $stmt = $pdo->prepare("INSERT INTO blogs (
            category_id, title, slug, content_type, excerpt, content_html,
            featured_image, image_alt, author_name, journal_name, publication_year,
            doi, publication_url, references_text, meta_title, meta_description,
            status, published_at, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            !empty($data['category_id']) ? (int)$data['category_id'] : null,
            trim($data['title']),
            $slug,
            $data['content_type'] ?? 'Educational Article',
            trim($data['excerpt']),
            $data['content_html'],
            $data['featured_image'] ?? null,
            $data['image_alt'] ?? trim($data['title']),
            $data['author_name'] ?? 'Dr. Amrit Jaishi',
            $data['journal_name'] ?? null,
            $data['publication_year'] ?? null,
            $data['doi'] ?? null,
            $data['publication_url'] ?? null,
            $data['references_text'] ?? null,
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null,
            $status,
            $publishedAt
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $current = self::findById($id);
        if (!$current) return false;

        $status = ($data['status'] === 'Draft') ? 'Draft' : 'Published';
        $publishedAt = $current['published_at'];
        if ($status === 'Published' && empty($publishedAt)) {
            $publishedAt = date('Y-m-d H:i:s');
        }

        $sql = "UPDATE blogs SET 
            category_id = ?,
            title = ?,
            content_type = ?,
            excerpt = ?,
            content_html = ?,
            image_alt = ?,
            author_name = ?,
            journal_name = ?,
            publication_year = ?,
            doi = ?,
            publication_url = ?,
            references_text = ?,
            meta_title = ?,
            meta_description = ?,
            status = ?,
            published_at = ?";

        $params = [
            !empty($data['category_id']) ? (int)$data['category_id'] : null,
            trim($data['title']),
            $data['content_type'] ?? 'Educational Article',
            trim($data['excerpt']),
            $data['content_html'],
            $data['image_alt'] ?? trim($data['title']),
            $data['author_name'] ?? 'Dr. Amrit Jaishi',
            $data['journal_name'] ?? null,
            $data['publication_year'] ?? null,
            $data['doi'] ?? null,
            $data['publication_url'] ?? null,
            $data['references_text'] ?? null,
            $data['meta_title'] ?? null,
            $data['meta_description'] ?? null,
            $status,
            $publishedAt
        ];

        if (!empty($data['slug'])) {
            $sql .= ", slug = ?";
            $params[] = self::generateSlug($data['slug'], $id);
        }

        if (!empty($data['featured_image'])) {
            $sql .= ", featured_image = ?";
            $params[] = $data['featured_image'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        // Safe soft delete
        $stmt = $pdo->prepare("UPDATE blogs SET deleted_at = NOW(), status = 'Draft' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countPublished(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM blogs WHERE status = 'Published' AND deleted_at IS NULL")->fetchColumn();
    }

    public static function countDrafts(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM blogs WHERE status = 'Draft' AND deleted_at IS NULL")->fetchColumn();
    }
}
