<?php
namespace App\Models;

use App\Core\Database;

class Qualification {
    public static function all(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM qualifications ORDER BY display_order ASC, completion_year DESC");
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
        $stmt = $pdo->prepare("INSERT INTO qualifications (degree, institution, completion_year, description, display_order) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['degree'],
            $data['institution'],
            $data['completion_year'],
            $data['description'] ?? '',
            (int)($data['display_order'] ?? 0)
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE qualifications SET degree = ?, institution = ?, completion_year = ?, description = ?, display_order = ? WHERE id = ?");
        return $stmt->execute([
            $data['degree'],
            $data['institution'],
            $data['completion_year'],
            $data['description'] ?? '',
            (int)($data['display_order'] ?? 0),
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM qualifications WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
