<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Clinic {
    public static function allActive(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM clinics WHERE is_active = 1 ORDER BY workplace_type ASC, display_order ASC, id ASC");
        $clinics = $stmt->fetchAll();

        foreach ($clinics as &$clinic) {
            $clinic['schedules'] = self::getSchedules((int)$clinic['id']);
        }

        return $clinics;
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM clinics ORDER BY display_order ASC, id ASC");
        $clinics = $stmt->fetchAll();

        foreach ($clinics as &$clinic) {
            $clinic['schedules'] = self::getSchedules((int)$clinic['id']);
        }

        return $clinics;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM clinics WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $clinic = $stmt->fetch();
        if ($clinic) {
            $clinic['schedules'] = self::getSchedules($id);
            return $clinic;
        }
        return null;
    }

    public static function findBySlug(string $slug): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM clinics WHERE slug = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$slug]);
        $clinic = $stmt->fetch();
        if ($clinic) {
            $clinic['schedules'] = self::getSchedules((int)$clinic['id']);
            return $clinic;
        }
        return null;
    }

    public static function getSchedules(int $clinicId): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM clinic_schedules WHERE clinic_id = ? ORDER BY id ASC");
        $stmt->execute([$clinicId]);
        return $stmt->fetchAll();
    }

    public static function generateSlug(string $name, ?int $excludeId = null): string {
        $base = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
        if (empty($base)) $base = 'clinic';

        $pdo = Database::getConnection();
        $slug = $base;
        $counter = 1;

        while (true) {
            $sql = "SELECT id FROM clinics WHERE slug = ?";
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

    public static function create(array $data, array $schedules = []): int {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($data['clinic_name']);

        $stmt = $pdo->prepare("INSERT INTO clinics (
            clinic_name, slug, doctor_role, workplace_type, full_address,
            phone, website_url, map_url, image, description, is_active, display_order, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $data['clinic_name'],
            $slug,
            $data['doctor_role'] ?? 'Senior Dental Surgeon',
            $data['workplace_type'] ?? 'Permanent Workplace',
            $data['full_address'],
            $data['phone'] ?? '',
            $data['website_url'] ?? null,
            $data['map_url'] ?? null,
            $data['image'] ?? null,
            $data['description'] ?? '',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            (int)($data['display_order'] ?? 0)
        ]);

        $clinicId = (int)$pdo->lastInsertId();

        self::saveSchedules($clinicId, $schedules);

        return $clinicId;
    }

    public static function update(int $id, array $data, array $schedules = []): bool {
        $pdo = Database::getConnection();

        $sql = "UPDATE clinics SET 
            clinic_name = ?,
            doctor_role = ?,
            workplace_type = ?,
            full_address = ?,
            phone = ?,
            website_url = ?,
            map_url = ?,
            description = ?,
            is_active = ?,
            display_order = ?";

        $params = [
            $data['clinic_name'],
            $data['doctor_role'] ?? 'Senior Dental Surgeon',
            $data['workplace_type'] ?? 'Permanent Workplace',
            $data['full_address'],
            $data['phone'] ?? '',
            $data['website_url'] ?? null,
            $data['map_url'] ?? null,
            $data['description'] ?? '',
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            (int)($data['display_order'] ?? 0)
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
        $res = $stmt->execute($params);

        if (!empty($schedules)) {
            self::saveSchedules($id, $schedules);
        }

        return $res;
    }

    public static function saveSchedules(int $clinicId, array $schedules): void {
        $pdo = Database::getConnection();
        // Clear existing
        $del = $pdo->prepare("DELETE FROM clinic_schedules WHERE clinic_id = ?");
        $del->execute([$clinicId]);

        $insert = $pdo->prepare("INSERT INTO clinic_schedules (clinic_id, visiting_day, start_time, end_time, notes) VALUES (?, ?, ?, ?, ?)");
        foreach ($schedules as $sched) {
            if (!empty($sched['visiting_day'])) {
                $insert->execute([
                    $clinicId,
                    trim($sched['visiting_day']),
                    trim($sched['start_time'] ?? ''),
                    trim($sched['end_time'] ?? ''),
                    trim($sched['notes'] ?? '')
                ]);
            }
        }
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM clinics WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function count(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM clinics WHERE is_active = 1")->fetchColumn();
    }
}
