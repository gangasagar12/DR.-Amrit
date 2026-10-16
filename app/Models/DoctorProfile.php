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

        if (empty($existing['id'])) {
            $stmt = $pdo->prepare("INSERT INTO doctor_profile (
                doctor_name, professional_title, tagline, biography, experience_years,
                experience_description, philosophy, languages, profile_image,
                contact_email, phone, whatsapp, emergency_contact
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $res = $stmt->execute([
                $data['doctor_name'] ?? 'Dr. Amrit Jaishi',
                $data['professional_title'] ?? '',
                $data['tagline'] ?? '',
                $data['biography'] ?? '',
                (int)($data['experience_years'] ?? 10),
                $data['experience_description'] ?? '',
                $data['philosophy'] ?? '',
                $data['languages'] ?? 'English',
                $data['profile_image'] ?? '',
                $data['contact_email'] ?? '',
                $data['phone'] ?? '',
                $data['whatsapp'] ?? '',
                $data['emergency_contact'] ?? ''
            ]);
        } else {
            $sql = "UPDATE doctor_profile SET 
                doctor_name = ?,
                professional_title = ?,
                tagline = ?,
                biography = ?,
                experience_years = ?,
                experience_description = ?,
                philosophy = ?,
                languages = ?,
                contact_email = ?,
                phone = ?,
                whatsapp = ?,
                emergency_contact = ?";
            
            $params = [
                $data['doctor_name'] ?? '',
                $data['professional_title'] ?? '',
                $data['tagline'] ?? '',
                $data['biography'] ?? '',
                (int)($data['experience_years'] ?? 10),
                $data['experience_description'] ?? '',
                $data['philosophy'] ?? '',
                $data['languages'] ?? '',
                $data['contact_email'] ?? '',
                $data['phone'] ?? '',
                $data['whatsapp'] ?? '',
                $data['emergency_contact'] ?? ''
            ];

            if (!empty($data['profile_image'])) {
                $sql .= ", profile_image = ?";
                $params[] = $data['profile_image'];
            }

            $sql .= " WHERE id = ?";
            $params[] = $existing['id'];

            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute($params);
        }

        self::$cached = null;
        return $res;
    }
}
