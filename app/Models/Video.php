<?php
namespace App\Models;

use App\Core\Database;

class Video {
    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $sql = "SELECT v.*, c.name AS category_name, c.slug AS category_slug 
                FROM videos v 
                LEFT JOIN video_categories c ON v.category_id = c.id 
                ORDER BY v.display_order ASC, v.created_at DESC";
        return $pdo->query($sql)->fetchAll();
    }

    public static function allPublished(?string $categorySlug = null): array {
        $pdo = Database::getConnection();
        $sql = "SELECT v.*, c.name AS category_name, c.slug AS category_slug 
                FROM videos v 
                LEFT JOIN video_categories c ON v.category_id = c.id 
                WHERE v.status = 'published'";
        
        $params = [];
        if (!empty($categorySlug) && $categorySlug !== 'all') {
            $sql .= " AND c.slug = ?";
            $params[] = $categorySlug;
        }

        $sql .= " ORDER BY v.display_order ASC, v.published_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT v.*, c.name AS category_name, c.slug AS category_slug 
                               FROM videos v 
                               LEFT JOIN video_categories c ON v.category_id = c.id 
                               WHERE v.id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT v.*, c.name AS category_name, c.slug AS category_slug 
                               FROM videos v 
                               LEFT JOIN video_categories c ON v.category_id = c.id 
                               WHERE v.slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO videos 
            (category_id, title, slug, description, video_type, video_url, video_file, thumbnail, duration, display_order, status, published_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['category_id'] ?: null,
            $data['title'],
            $data['slug'],
            $data['description'] ?? '',
            $data['video_type'] ?? 'youtube',
            $data['video_url'] ?? null,
            $data['video_file'] ?? null,
            $data['thumbnail'] ?? null,
            $data['duration'] ?? null,
            (int)($data['display_order'] ?? 0),
            $data['status'] ?? 'published',
            !empty($data['published_at']) ? $data['published_at'] : date('Y-m-d H:i:s')
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE videos SET 
            category_id = ?, title = ?, slug = ?, description = ?, video_type = ?, 
            video_url = ?, video_file = ?, thumbnail = ?, duration = ?, display_order = ?, 
            status = ?, published_at = ? 
            WHERE id = ?");
        return $stmt->execute([
            $data['category_id'] ?: null,
            $data['title'],
            $data['slug'],
            $data['description'] ?? '',
            $data['video_type'] ?? 'youtube',
            $data['video_url'] ?? null,
            $data['video_file'] ?? null,
            $data['thumbnail'] ?? null,
            $data['duration'] ?? null,
            (int)($data['display_order'] ?? 0),
            $data['status'] ?? 'published',
            !empty($data['published_at']) ? $data['published_at'] : date('Y-m-d H:i:s'),
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $item = self::findById($id);
        if ($item) {
            // Delete local file if present
            if (!empty($item['video_file']) && file_exists(ROOT_DIR . '/public/' . $item['video_file'])) {
                @unlink(ROOT_DIR . '/public/' . $item['video_file']);
            }
            if (!empty($item['thumbnail']) && file_exists(ROOT_DIR . '/public/' . $item['thumbnail'])) {
                @unlink(ROOT_DIR . '/public/' . $item['thumbnail']);
            }
        }
        $stmt = $pdo->prepare("DELETE FROM videos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Parse YouTube or Vimeo URL to clean embed player URL
     */
    public static function getEmbedUrl(string $url, string $type): string {
        if ($type === 'youtube') {
            // Match youtube.com/watch?v=ID or youtu.be/ID or youtube.com/embed/ID
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0';
            }
        } elseif ($type === 'vimeo') {
            if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/i', $url, $m)) {
                return 'https://player.vimeo.com/video/' . $m[3] . '?autoplay=1';
            }
        }
        return $url;
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM videos")->fetchColumn();
    }
}
