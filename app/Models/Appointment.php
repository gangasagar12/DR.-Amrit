<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Appointment {
    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO appointments (
            patient_name, phone, email, service_id, clinic_id,
            preferred_date, preferred_time, message, status, internal_notes, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NULL, NOW())");

        $stmt->execute([
            trim($data['patient_name']),
            trim($data['phone']),
            !empty($data['email']) ? trim($data['email']) : null,
            !empty($data['service_id']) ? (int)$data['service_id'] : null,
            !empty($data['clinic_id']) ? (int)$data['clinic_id'] : null,
            $data['preferred_date'],
            trim($data['preferred_time']),
            !empty($data['message']) ? trim($data['message']) : null
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT a.*, s.name as service_name, c.clinic_name, c.full_address as clinic_address
            FROM appointments a
            LEFT JOIN services s ON s.id = a.service_id
            LEFT JOIN clinics c ON c.id = a.clinic_id
            WHERE a.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function allAdmin(array $filters = []): array {
        $pdo = Database::getConnection();
        $where = [];
        $params = [];

        if (!empty($filters['status']) && in_array($filters['status'], ['Pending', 'Confirmed', 'Completed', 'Cancelled'])) {
            $where[] = "a.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['clinic_id'])) {
            $where[] = "a.clinic_id = ?";
            $params[] = (int)$filters['clinic_id'];
        }

        if (!empty($filters['service_id'])) {
            $where[] = "a.service_id = ?";
            $params[] = (int)$filters['service_id'];
        }

        if (!empty($filters['date'])) {
            $where[] = "a.preferred_date = ?";
            $params[] = $filters['date'];
        }

        $whereSql = !empty($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "
            SELECT a.*, s.name as service_name, c.clinic_name
            FROM appointments a
            LEFT JOIN services s ON s.id = a.service_id
            LEFT JOIN clinics c ON c.id = a.clinic_id
            {$whereSql}
            ORDER BY a.preferred_date DESC, a.id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $status, ?string $internalNotes = null): bool {
        $valid = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
        if (!in_array($status, $valid, true)) {
            return false;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE appointments SET status = ?, internal_notes = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $internalNotes, $id]);
    }

    public static function countPending(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM appointments WHERE status = 'Pending'")->fetchColumn();
    }

    public static function countTotal(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    }

    public static function recent(int $limit = 5): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT a.*, s.name as service_name, c.clinic_name
            FROM appointments a
            LEFT JOIN services s ON s.id = a.service_id
            LEFT JOIN clinics c ON c.id = a.clinic_id
            ORDER BY a.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
