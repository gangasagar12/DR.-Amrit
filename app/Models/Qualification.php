<?php
namespace App\Models;

use App\Core\Database;

class Qualification {
    public static function all(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM qualifications ORDER BY display_order ASC, completion_year DESC");
        return $stmt->fetchAll();
    }

    public static function allActive(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM qualifications WHERE is_published = 1 ORDER BY display_order ASC, completion_year DESC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM qualifications WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO qualifications (degree, institution, location, start_year, completion_year, description, display_order, is_published) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['degree'],
            $data['institution'],
            $data['location'] ?? '',
            $data['start_year'] ?? '',
            $data['completion_year'],
            $data['description'] ?? '',
            (int)($data['display_order'] ?? 0),
            isset($data['is_published']) ? (int)$data['is_published'] : 1
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE qualifications SET degree = ?, institution = ?, location = ?, start_year = ?, completion_year = ?, description = ?, display_order = ?, is_published = ? WHERE id = ?");
        return $stmt->execute([
            $data['degree'],
            $data['institution'],
            $data['location'] ?? '',
            $data['start_year'] ?? '',
            $data['completion_year'],
            $data['description'] ?? '',
            (int)($data['display_order'] ?? 0),
            isset($data['is_published']) ? (int)$data['is_published'] : 1,
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM qualifications WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM qualifications")->fetchColumn();
    }
}

