<?php
namespace App\Models;

use App\Core\Database;

class Testimonial {
    public static function allApproved(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT id, 
                   COALESCE(patient_name, patient_display_name, 'Patient') AS patient_name,
                   COALESCE(patient_display_name, patient_name, 'Patient') AS patient_display_name,
                   treatment_type,
                   rating,
                   COALESCE(quote, testimonial_text, '') AS quote,
                   COALESCE(testimonial_text, quote, '') AS testimonial_text,
                   COALESCE(is_verified, 1) AS is_verified,
                   display_order
            FROM testimonials 
            WHERE (is_approved = 1 OR is_approved IS NULL)
            ORDER BY display_order ASC, id DESC
        ");
        return $stmt->fetchAll();
    }

    public static function allAdmin(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT id, 
                   COALESCE(patient_name, patient_display_name, 'Patient') AS patient_name,
                   COALESCE(patient_display_name, patient_name, 'Patient') AS patient_display_name,
                   treatment_type,
                   rating,
                   COALESCE(quote, testimonial_text, '') AS quote,
                   COALESCE(testimonial_text, quote, '') AS testimonial_text,
                   COALESCE(is_verified, 1) AS is_verified,
                   COALESCE(is_approved, 1) AS is_approved,
                   COALESCE(consent_status, 1) AS consent_status,
                   display_order
            FROM testimonials 
            ORDER BY display_order ASC, id DESC
        ");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT id, 
                   COALESCE(patient_name, patient_display_name, 'Patient') AS patient_name,
                   COALESCE(patient_display_name, patient_name, 'Patient') AS patient_display_name,
                   treatment_type,
                   rating,
                   COALESCE(quote, testimonial_text, '') AS quote,
                   COALESCE(testimonial_text, quote, '') AS testimonial_text,
                   COALESCE(is_verified, 1) AS is_verified,
                   COALESCE(is_approved, 1) AS is_approved,
                   COALESCE(consent_status, 1) AS consent_status,
                   display_order
            FROM testimonials 
            WHERE id = ? 
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $name = trim($data['patient_name'] ?? ($data['patient_display_name'] ?? 'Patient'));
        $treatment = trim($data['treatment_type'] ?? 'Dental Care');
        $quote = trim($data['quote'] ?? ($data['testimonial_text'] ?? ''));
        $rating = (int)($data['rating'] ?? 5);
        $order = (int)($data['display_order'] ?? 0);
        $isVerified = isset($data['is_verified']) ? (int)$data['is_verified'] : 1;
        $isApproved = isset($data['is_approved']) ? (int)$data['is_approved'] : 1;
        $consent = isset($data['consent_status']) ? (int)$data['consent_status'] : 1;

        $stmt = $pdo->prepare("INSERT INTO testimonials (
            patient_name, patient_display_name, treatment_type, rating,
            quote, testimonial_text, is_verified, is_approved, consent_status, display_order, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $name, $name, $treatment, $rating,
            $quote, $quote, $isVerified, $isApproved, $consent, $order
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $name = trim($data['patient_name'] ?? ($data['patient_display_name'] ?? 'Patient'));
        $treatment = trim($data['treatment_type'] ?? 'Dental Care');
        $quote = trim($data['quote'] ?? ($data['testimonial_text'] ?? ''));
        $rating = (int)($data['rating'] ?? 5);
        $order = (int)($data['display_order'] ?? 0);
        $isVerified = isset($data['is_verified']) ? (int)$data['is_verified'] : 1;
        $isApproved = isset($data['is_approved']) ? (int)$data['is_approved'] : 1;
        $consent = isset($data['consent_status']) ? (int)$data['consent_status'] : 1;

        $stmt = $pdo->prepare("UPDATE testimonials SET 
            patient_name = ?,
            patient_display_name = ?,
            treatment_type = ?,
            rating = ?,
            quote = ?,
            testimonial_text = ?,
            is_verified = ?,
            is_approved = ?,
            consent_status = ?,
            display_order = ?
            WHERE id = ?");

        return $stmt->execute([
            $name, $name, $treatment, $rating,
            $quote, $quote, $isVerified, $isApproved, $consent, $order, $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
