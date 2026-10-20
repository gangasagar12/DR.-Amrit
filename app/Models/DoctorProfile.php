<?php
namespace App\Models;

use App\Core\Database;

class DoctorProfile {
    private static ?array $cached = null;

    public static function get(): array {
        if (self::$cached !== null) {
            return self::$cached;
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM doctor_profile ORDER BY id ASC LIMIT 1");
        $profile = $stmt->fetch();

        if (!$profile) {
            // Default placeholder profile
            $profile = [
                'id' => 1,
                'doctor_name' => 'Dr. Amrit Jaishi',
                'professional_title' => 'BDS (BPKIHS) - Oral and Dental Surgeon',
                'tagline' => 'PERSONALISED SPECIALIST CARE FOR YOUR SMILE',
                'biography' => 'Dr. Amrit Raj Jaishi has been serving as an Oral and Dental Surgeon in Farwest Province, Nepal, for over four years. Based at Dhangadhi Sanjeevani Hospital, Dr. Jaishi graduated with a BDS from B.P. Koirala Institute of Health Sciences (BPKIHS, Dharan).',
                'experience_years' => 4,
                'experience_description' => 'Over 4 years of clinical experience in Farwest Nepal specializing in restorative dentistry, root canals, and emergency dental trauma care.',
                'philosophy' => 'Every patient deserves ethical, gentle, and transparent dental care with emphasis on natural tooth preservation and proactive education.',
                'languages' => 'Nepali, English, Hindi',
                'profile_image' => 'uploads/profile/amrit_jaishi.jpg',
                'contact_email' => 'amritjaishi0@gmail.com',
                'phone' => '+977-9860644282',
                'whatsapp' => '+9779860644282',
                'emergency_contact' => '091-590328 / 091-590348 (Hospital Line)'
            ];
        }

        self::$cached = $profile;
        return self::$cached;
    }

    public static function update(array $data): bool {
        $pdo = Database::getConnection();
        $existing = self::get();

        $allowedFields = [
            'doctor_name', 'professional_title', 'tagline', 'hero_heading', 'hero_subheading',
            'hero_lead', 'hero_approach', 'hero_btn1_text', 'hero_btn1_link', 'hero_btn2_text', 'hero_btn2_link',
            'about_kicker', 'about_subheading', 'about_badge_title', 'about_badge_subtitle',
            'biography', 'experience_years', 'experience_description', 'philosophy', 'languages',
            'contact_email', 'phone', 'whatsapp', 'emergency_contact',
            'profile_image', 'hero_image', 'about_image'
        ];

        // Auto-ensure columns exist in SQLite if needed
        if (Database::getDriver() === 'sqlite') {
            try {
                $existingCols = $pdo->query("PRAGMA table_info(doctor_profile)")->fetchAll(\PDO::FETCH_COLUMN, 1);
                foreach ($allowedFields as $field) {
                    if (!in_array($field, $existingCols, true)) {
                        @$pdo->exec("ALTER TABLE doctor_profile ADD COLUMN {$field} TEXT NULL");
                    }
                }
            } catch (\Throwable $e) {}
        }

        $setParts = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $setParts[] = "{$field} = ?";
                $params[] = $data[$field];
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $params[] = $existing['id'] ?? 1;
        $sql = "UPDATE doctor_profile SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute($params);

        self::$cached = null;
        return $res;
    }
}
