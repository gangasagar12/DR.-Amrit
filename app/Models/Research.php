<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Research {
    public static function allPublished(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM researches WHERE status = 'published' ORDER BY display_order ASC, publication_year DESC, id DESC");
        return $stmt->fetchAll();
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM researches ORDER BY display_order ASC, publication_year DESC, id DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM researches WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM researches WHERE slug = ? LIMIT 1");
        $stmt->execute([$slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function generateSlug(string $title, ?int $excludeId = null): string {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $title), '-'));
        if (empty($base)) {
            $base = 'research-paper';
        }

        $pdo = Database::getConnection();
        $slug = $base;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM researches WHERE slug = ?";
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
        $slug = self::generateSlug($data['title'] ?? 'research');

        $stmt = $pdo->prepare("INSERT INTO researches (
            title, slug, summary, abstract, full_text, authors, journal_name,
            publication_year, doi, publication_url, cover_image, pdf_file,
            display_order, status, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        $stmt->execute([
            $data['title'] ?? '',
            $slug,
            $data['summary'] ?? '',
            $data['abstract'] ?? '',
            $data['full_text'] ?? '',
            $data['authors'] ?? 'Dr. Amrit Jaishi',
            $data['journal_name'] ?? '',
            $data['publication_year'] ?? date('Y'),
            $data['doi'] ?? '',
            $data['publication_url'] ?? '',
            $data['cover_image'] ?? '',
            $data['pdf_file'] ?? '',
            (int)($data['display_order'] ?? 0),
            $data['status'] ?? 'published'
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($data['title'] ?? 'research', $id);

        $sql = "UPDATE researches SET 
            title = ?,
            slug = ?,
            summary = ?,
            abstract = ?,
            full_text = ?,
            authors = ?,
            journal_name = ?,
            publication_year = ?,
            doi = ?,
            publication_url = ?,
            display_order = ?,
            status = ?,
            updated_at = NOW()";

        $params = [
            $data['title'] ?? '',
            $slug,
            $data['summary'] ?? '',
            $data['abstract'] ?? '',
            $data['full_text'] ?? '',
            $data['authors'] ?? 'Dr. Amrit Jaishi',
            $data['journal_name'] ?? '',
            $data['publication_year'] ?? date('Y'),
            $data['doi'] ?? '',
            $data['publication_url'] ?? '',
            (int)($data['display_order'] ?? 0),
            $data['status'] ?? 'published'
        ];

        if (!empty($data['cover_image'])) {
            $sql .= ", cover_image = ?";
            $params[] = $data['cover_image'];
        }

        if (!empty($data['pdf_file'])) {
            $sql .= ", pdf_file = ?";
            $params[] = $data['pdf_file'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM researches WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countPublished(): int {
        try {
            $pdo = Database::getConnection();
            return (int)$pdo->query("SELECT COUNT(*) FROM researches WHERE status = 'published'")->fetchColumn();
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
