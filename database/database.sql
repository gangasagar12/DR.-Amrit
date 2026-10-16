

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `clinic_schedules`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `blogs`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `gallery_images`;
DROP TABLE IF EXISTS `achievements`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `qualifications`;
DROP TABLE IF EXISTS `clinics`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `doctor_profile`;
DROP TABLE IF EXISTS `admin_users`;
DROP TABLE IF EXISTS `password_resets`;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------
-- 1. Admin Users Table
-- -------------------------------------------------------
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 1b. Password Resets Table
-- -------------------------------------------------------
CREATE TABLE `password_resets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_reset_token` (`token_hash`),
  INDEX `idx_reset_user` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `admin_users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 2. Doctor Profile Table
-- -------------------------------------------------------
CREATE TABLE `doctor_profile` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `doctor_name` VARCHAR(150) NOT NULL,
  `professional_title` VARCHAR(200) NOT NULL,
  `tagline` VARCHAR(255) NOT NULL,
  `biography` TEXT NOT NULL,
  `experience_years` INT UNSIGNED NOT NULL DEFAULT 4,
  `experience_description` TEXT NULL,
  `philosophy` TEXT NULL,
  `languages` VARCHAR(255) NOT NULL DEFAULT 'Nepali, English, Hindi',
  `profile_image` VARCHAR(255) NULL,
  `contact_email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `whatsapp` VARCHAR(50) NOT NULL,
  `emergency_contact` VARCHAR(100) NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 3. Doctor Qualifications Table
-- -------------------------------------------------------
CREATE TABLE `qualifications` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `degree` VARCHAR(150) NOT NULL,
  `institution` VARCHAR(200) NOT NULL,
  `completion_year` VARCHAR(20) NOT NULL,
  `description` TEXT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 4. Dental Services Table
-- -------------------------------------------------------
CREATE TABLE `services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `category` VARCHAR(100) NOT NULL DEFAULT 'General Dentistry',
  `short_description` VARCHAR(300) NOT NULL,
  `full_description` MEDIUMTEXT NOT NULL,
  `benefits` TEXT NULL,
  `procedure_steps` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'activity',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `display_order` INT NOT NULL DEFAULT 0,
  `meta_title` VARCHAR(200) NULL,
  `meta_description` VARCHAR(300) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_service_slug` (`slug`),
  INDEX `idx_service_active` (`is_active`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 5. Achievements, Certifications & Training Table
-- -------------------------------------------------------
CREATE TABLE `achievements` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `record_type` ENUM('Training', 'Certification', 'Conference', 'Award', 'Membership') NOT NULL DEFAULT 'Certification',
  `organization` VARCHAR(200) NOT NULL,
  `achievement_date` VARCHAR(50) NOT NULL,
  `description` TEXT NULL,
  `certificate_image` VARCHAR(255) NULL,
  `verification_url` VARCHAR(255) NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 6. Clinic Gallery Table
-- -------------------------------------------------------
CREATE TABLE `gallery_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'Clinic Facility',
  `display_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 7. Blog Categories Table
-- -------------------------------------------------------
CREATE TABLE `blog_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 8. Blog Articles & Research Publications Table
-- -------------------------------------------------------
CREATE TABLE `blogs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` VARCHAR(500) NOT NULL,
  `content_html` MEDIUMTEXT NOT NULL,
  `featured_image` VARCHAR(255) NULL,
  `image_alt` VARCHAR(255) NULL,
  `author_name` VARCHAR(100) NOT NULL DEFAULT 'Dr. Amrit Jaishi',
  `content_type` ENUM('educational_blog', 'research_publication') NOT NULL DEFAULT 'educational_blog',
  `references_text` TEXT NULL,
  `doi` VARCHAR(150) NULL,
  `publication_url` VARCHAR(255) NULL,
  `journal_name` VARCHAR(200) NULL,
  `publication_year` VARCHAR(20) NULL,
  `co_authors` VARCHAR(255) NULL,
  `meta_title` VARCHAR(200) NULL,
  `meta_description` VARCHAR(300) NULL,
  `status` ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
  `views_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `published_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  INDEX `idx_blog_slug` (`slug`),
  INDEX `idx_blog_status` (`status`, `published_at`),
  CONSTRAINT `fk_blog_category` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 9. Clinics & Practice Locations Table
-- -------------------------------------------------------
CREATE TABLE `clinics` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `clinic_name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(160) NOT NULL UNIQUE,
  `full_address` VARCHAR(255) NOT NULL,
  `doctor_role` VARCHAR(150) NOT NULL DEFAULT 'Consultant Dental Surgeon',
  `workplace_type` ENUM('Permanent Workplace', 'Visiting Consultant') NOT NULL DEFAULT 'Permanent Workplace',
  `phone` VARCHAR(100) NOT NULL,
  `website_url` VARCHAR(255) NULL,
  `map_url` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `appointment_type` ENUM('online_form', 'call_only', 'external_link') NOT NULL DEFAULT 'online_form',
  `appointment_url` VARCHAR(255) NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_clinic_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 10. Clinic Schedules Table
-- -------------------------------------------------------
CREATE TABLE `clinic_schedules` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `clinic_id` INT UNSIGNED NOT NULL,
  `visiting_day` VARCHAR(50) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `notes` VARCHAR(255) NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_schedule_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 11. Appointments Table
-- -------------------------------------------------------
CREATE TABLE `appointments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `patient_name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(40) NOT NULL,
  `email` VARCHAR(150) NULL,
  `service_id` INT UNSIGNED NULL,
  `clinic_id` INT UNSIGNED NULL,
  `preferred_date` DATE NOT NULL,
  `preferred_time` VARCHAR(30) NOT NULL,
  `message` TEXT NULL,
  `status` ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
  `internal_notes` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_appt_status` (`status`),
  INDEX `idx_appt_date` (`preferred_date`),
  CONSTRAINT `fk_appt_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_appt_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 12. Contact Messages Table
-- -------------------------------------------------------
CREATE TABLE `contact_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(40) NULL,
  `subject` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 13. Testimonials Table
-- -------------------------------------------------------
CREATE TABLE `testimonials` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `patient_name` VARCHAR(120) NOT NULL,
  `patient_display_name` VARCHAR(120) NULL,
  `treatment_type` VARCHAR(150) NOT NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `quote` TEXT NOT NULL,
  `testimonial_text` TEXT NULL,
  `is_verified` TINYINT(1) NOT NULL DEFAULT 1,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 1,
  `consent_status` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 14. Site Settings Table
-- -------------------------------------------------------
CREATE TABLE `site_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =======================================================
-- SEED DATA: DR. AMRIT JAISHI (AUTHENTIC CLINICAL PORTFOLIO)
-- =======================================================

-- 1. Default Admin User (Password: Admin@12345)
INSERT INTO `admin_users` (`id`, `name`, `email`, `password_hash`, `is_active`, `created_at`) VALUES
(1, 'Dr. Amrit Jaishi', 'admin@dentaldoctor.com', '$2y$10$SbOobg4ioXXkefedw6qhWehRgoMPPGXPyf/R9AOuXEHmWaDGq7lt6', 1, NOW())
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`), `password_hash` = VALUES(`password_hash`);

-- 2. Doctor Profile
INSERT INTO `doctor_profile` (
  `id`, `doctor_name`, `professional_title`, `tagline`, `biography`, 
  `experience_years`, `experience_description`, `philosophy`, `languages`, 
  `profile_image`, `contact_email`, `phone`, `whatsapp`, `emergency_contact`
) VALUES (
  1,
  'Dr. Amrit Jaishi',
  'BDS (BPKIHS) - Oral and Dental Surgeon',
  'PERSONALISED SPECIALIST CARE FOR YOUR SMILE',
  'Dr. Amrit Raj Jaishi has been serving as an Oral and Dental Surgeon in Farwest Province, Nepal, for over four years. Currently based at Dhangadhi Sanjeevani Hospital, Dr. Jaishi was drawn to dentistry by its transformative ability to restore smiles, relieve acute pain, and dramatically enhance patient quality of life.\n\nDr. Jaishi completed his Bachelor of Dental Surgery (BDS) from B.P. Koirala Institute of Health Sciences (BPKIHS Dharan) — one of the leading autonomous health science universities and medical institutions in Nepal. Dedicated to continuous professional growth, patient education, and compassionate clinical care, Dr. Jaishi specializes in pain-free root canal treatment, dental crowns, restorative dentistry, emergency trauma management, and pediatric dental solutions.',
  4,
  'Over 4 years of dedicated clinical practice in Farwest Province, Nepal. Skilled in comprehensive oral diagnosis, endodontic therapy, minor surgical procedures, emergency trauma management, and community oral health education.',
  'Every patient deserves gentle, ethical, and transparent dental care. We prioritize preserving natural teeth, minimizing discomfort with modern clinical techniques, and empowering patients with practical oral health education.',
  'Nepali, English, Hindi',
  'uploads/profile/amrit_jaishi.jpg',
  'amritjaishi0@gmail.com',
  '+977-9860644282',
  '+9779860644282',
  '091-590328 / 091-590348 (Hospital Line)'
)
ON DUPLICATE KEY UPDATE `doctor_name` = VALUES(`doctor_name`), `professional_title` = VALUES(`professional_title`);

-- 3. Doctor Qualifications
INSERT INTO `qualifications` (`id`, `degree`, `institution`, `completion_year`, `description`, `display_order`) VALUES
(1, 'Bachelor of Dental Surgery (BDS)', 'B.P. Koirala Institute of Health Sciences (BPKIHS), Dharan', '2019', 'Graduated from Nepal\'s leading medical and dental institute with comprehensive clinical training in Conservative Dentistry, Endodontics, Periodontics, and Oral Minor Surgery.', 1),
(2, 'Registered Oral and Dental Surgeon', 'Nepal Medical Council (NMC)', '2020', 'Permanent clinical license for practicing dental surgery, restorative dentistry, and oral healthcare procedures.', 2);

-- 4. Dental Services
INSERT INTO `services` (`id`, `name`, `slug`, `category`, `short_description`, `full_description`, `benefits`, `procedure_steps`, `image`, `icon`, `is_active`, `is_featured`, `display_order`, `meta_title`, `meta_description`) VALUES
(1, 'Emergency Dental Care', 'emergency-dental-care', 'Emergency Dentistry',
 'Immediate relief for severe toothache, dental trauma, knocked-out teeth, and acute oral infections.',
 'Dental emergencies require rapid, skilled intervention to alleviate intense pain and salvage compromised teeth. Dr. Amrit Jaishi provides comprehensive emergency oral care at Dhangadhi Sanjeevani Hospital for fractures, toothache, acute pulpitis, and oral lacerations.\n\nOur team rapidly evaluates trauma using digital diagnostics and provides swift, pain-relieving stabilization.',
 'Immediate pain relief and emergency care\nPrevents permanent tooth loss and systemic infection\nGentle local anesthesia for instant comfort\nFollow-up care and comprehensive restorative plan',
 'Step 1: Emergency triage & digital diagnostic evaluation\nStep 2: Instant pain management & targeted local anesthesia\nStep 3: Immediate tooth stabilization, pulpectomy or medication\nStep 4: Prescriptions and tailored long-term recovery plan',
 'uploads/services/emergency.jpg', 'shield-alert', 1, 1, 1,
 'Emergency Dental Care in Dhangadhi | Dr. Amrit Jaishi',
 'Immediate relief for severe toothache and dental trauma by Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital.'),

(2, 'Dental Crown & Bridge', 'dental-crown-and-bridge', 'Restorative Dentistry',
 'Durable, natural-looking porcelain and zirconia crowns to restore broken, weakened, or missing teeth.',
 'Dental crowns protect extensively damaged or root-canal-treated teeth, restoring their structural integrity, chewing strength, and aesthetic appearance. Bridges seamlessly span gaps caused by missing teeth.\n\nDr. Jaishi uses precision impression techniques and high-grade biocompatible materials that match your natural tooth contour and enamel shade.',
 'Restores 100% natural chewing function\nProtects root canal treated teeth from fracture\nCustom-matched color and natural light translucency\nLong-lasting durability and gum-line compatibility',
 'Step 1: Clinical assessment and tooth preparation\nStep 2: High-accuracy impression & shade matching\nStep 3: Temporary protective crown placement\nStep 4: Permanent precision crown bonding & bite alignment',
 'uploads/services/dental_crown.jpg', 'crown', 1, 1, 2,
 'Dental Crown & Bridge Treatment | Dr. Amrit Jaishi',
 'Protect and restore damaged teeth with custom dental crowns and bridges crafted with precision by Dr. Amrit Jaishi.'),

(3, 'Root Canal Treatment (RCT)', 'root-canal-treatment-rct', 'Endodontics',
 'Pain-free root canal treatment to save infected, deeply decayed teeth and eliminate toothache.',
 'Root canal treatment saves natural teeth that would otherwise need extraction due to deep decay, pulp infection, or trauma. Using modern rotary endodontic equipment, Dr. Amrit Jaishi removes inflamed pulp, cleans and shapes root canals, and hermetically seals them.\n\nWith gentle local anesthesia, RCT is comfortable and effectively ends toothache in 1-2 smooth visits.',
 'Saves natural tooth and maintains jawbone integrity\nInstant relief from severe throbbing tooth pain\nPainless modern rotary endodontic technology\nPrevents abscess spread and facial swelling',
 'Step 1: Diagnostic digital X-ray and painless local numbing\nStep 2: Infected pulp removal & rotary canal disinfection\nStep 3: Hermetic biocompatible gutta-percha obturation\nStep 4: Post-endodontic restorative crown build-up',
 'uploads/services/rct.jpg', 'activity', 1, 1, 3,
 'Painless Root Canal Treatment (RCT) | Dr. Amrit Jaishi',
 'Gentle and effective root canal treatment by Dr. Amrit Jaishi to save natural teeth and eliminate toothache permanently.'),

(4, 'Periodontal & Gum Care', 'periodontal-gum-care', 'Periodontics',
 'Comprehensive treatment for bleeding gums, gingivitis, periodontitis, and loose teeth.',
 'Healthy gums are the foundation of a healthy smile. Gum disease often develops silently with symptoms like bleeding during brushing, persistent bad breath, and receding gums. Dr. Jaishi provides specialized deep scaling, root planing, and antiseptic irrigation to eliminate bacterial plaque under the gumline and restore tissue firmness.',
 'Eliminates bleeding, redness, and gum soreness\nPrevents tooth loosening and premature tooth loss\nRemoves deep subgingival tartar and bacterial toxins\nRestores fresh breath and healthy pink gums',
 'Step 1: Periodontal pocket depth measurement\nStep 2: Ultrasonic subgingival scaling\nStep 3: Root surface smoothing (planing)\nStep 4: Antimicrobial irrigation & homecare instructions',
 'uploads/services/gum_care.jpg', 'heart-pulse', 1, 1, 4,
 'Gum Disease Treatment & Periodontal Care | Dr. Amrit Jaishi',
 'Stop gum bleeding and protect your teeth with specialized periodontal care and deep cleaning by Dr. Amrit Jaishi.'),

(5, 'Teeth Cleaning & Scaling', 'teeth-cleaning-and-scaling', 'Preventive Dentistry',
 'Professional ultrasonic cleaning, stain removal, and polishing for healthy teeth and radiant smile.',
 'Routine brushing cannot remove hardened tartar (calculus). Ultrasonic scaling effortlessly removes calculus deposits, coffee/tea stains, and bacterial biofilm without damaging natural enamel. Routine scaling every 6 months prevents cavities, gum inflammation, and bad breath.',
 'Removes tough tartar and yellowing surface stains\nProtects against cavities and gingival inflammation\nQuick, comfortable, and non-invasive procedure\nLeaves teeth clean, smooth, and naturally bright',
 'Step 1: Comprehensive oral examination\nStep 2: Ultrasonic cavitation scaling\nStep 3: Gentle fine hand instrumentation\nStep 4: Prophy-paste polishing & enamel fluoridation',
 'uploads/services/teeth_cleaning.jpg', 'sparkles', 1, 1, 5,
 'Professional Teeth Cleaning & Scaling | Dr. Amrit Jaishi',
 'Maintain optimal oral hygiene with gentle ultrasonic scaling and polishing by Dr. Amrit Jaishi.'),

(6, 'Tooth Extraction & Minor Surgery', 'tooth-extraction-minor-surgery', 'Oral Surgery',
 'Safe, painless extractions for severely decayed teeth, retained roots, and dental trauma.',
 'When a tooth cannot be saved by restorative treatment, gentle extraction prevents infection from spreading. Dr. Amrit Jaishi performs careful, atraumatic extractions with gentle technique, minimizing post-operative swelling and ensuring rapid healing.',
 'Painless extraction with effective local anesthesia\nAtraumatic socket preservation technique\nRapid recovery with clear post-op care guidance\nPrevents severe spread of facial dental infections',
 'Step 1: Radiographic bone and root evaluation\nStep 2: Gentle, profound local anesthesia\nStep 3: Atraumatic tooth elevation and removal\nStep 4: Socket hemorrage control & healing instructions',
 'uploads/services/extraction.jpg', 'wrench', 1, 1, 6,
 'Gentle Tooth Extraction & Minor Oral Surgery | Dr. Amrit Jaishi',
 'Comfortable and safe tooth extractions performed with gentle surgical technique by Dr. Amrit Jaishi.'),

(7, 'Wisdom Tooth Removal', 'wisdom-tooth-removal', 'Oral Surgery',
 'Surgical management of painful, impacted, or misaligned third molars (बुद्धी बंगारा).',
 'Impacted wisdom teeth frequently cause severe pain, pericoronitis (gum swelling), damage to adjacent molars, and jaw stiffness. Dr. Jaishi performs precise minor surgical removal of impacted wisdom teeth with thorough pain control and gentle surgical protocol.',
 'Relieves recurrent jaw pain, swelling, and headaches\nPrevents cavities and bone loss on adjacent second molars\nEliminates painful gum infections around partially erupted teeth\nGentle procedure with quick clinical recovery',
 'Step 1: Panoramic/digital X-ray evaluation of nerve and root\nStep 2: Profound local anesthesia\nStep 3: Delicate surgical access & sectional removal\nStep 4: Sterile suturing & detailed post-operative medications',
 'uploads/services/wisdom_tooth.jpg', 'smile', 1, 0, 7,
 'Wisdom Tooth Removal in Dhangadhi | Dr. Amrit Jaishi',
 'Relieve impacted wisdom tooth pain with safe minor oral surgery by Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital.'),

(8, 'Dental Braces & Orthodontics', 'dental-braces-orthodontics', 'Orthodontics',
 'Correction of crooked, crowded, spaced teeth and irregular bites for children and adults.',
 'Properly aligned teeth not only enhance your smile aesthetics but also improve chewing efficiency and make brushing far easier. Dr. Jaishi provides orthodontic assessment, space management, and braces to align teeth into harmony.',
 'Straightens crooked, overlapping, and crowded teeth\nCloses gaps and enhances facial profile aesthetics\nImproves chewing function and speech clarity\nLong-term retention plan for lasting smile stability',
 'Step 1: Diagnostic photography, study models & bite analysis\nStep 2: Customized orthodontic treatment planning\nStep 3: Bracket bonding and initial gentle archwire placement\nStep 4: Monthly adjustments and final retainer delivery',
 'uploads/services/braces.jpg', 'smile', 1, 0, 8,
 'Dental Braces & Orthodontic Care | Dr. Amrit Jaishi',
 'Straighten your smile and correct irregular bites with modern orthodontic treatment guided by Dr. Amrit Jaishi.');

-- 5. Training, Certifications and Achievements
INSERT INTO `achievements` (`id`, `title`, `record_type`, `organization`, `achievement_date`, `description`, `certificate_image`, `verification_url`, `display_order`, `is_active`) VALUES
(1, 'Bachelor of Dental Surgery (BDS)', 'Certification', 'B.P. Koirala Institute of Health Sciences (BPKIHS)', '2019', 'Graduation from Nepal\'s premier autonomous health science university with comprehensive rotations across clinical dental specialties.', NULL, 'https://bpkihs.edu', 1, 1),
(2, 'Oral & Dental Surgeon Registration', 'Membership', 'Nepal Medical Council (NMC)', '2020', 'Permanent registration as licensed dental surgeon authorized to deliver dental surgery and oral health treatments in Nepal.', NULL, 'https://nmc.org.np', 2, 1),
(3, 'Rotary Endodontics & Esthetic Dentistry Workshop', 'Training', 'Dental Science Forum', '2022', 'Advanced clinical hands-on training on single-visit rotary canal preparation, warm vertical compaction, and direct composite layering.', NULL, NULL, 3, 1);

-- 6. Clinic Affiliations & Locations
INSERT INTO `clinics` (`id`, `clinic_name`, `slug`, `full_address`, `doctor_role`, `workplace_type`, `phone`, `website_url`, `map_url`, `image`, `description`, `appointment_type`, `appointment_url`, `is_active`, `display_order`) VALUES
(1, 'Dhangadhi Sanjeevani Hospital', 'dhangadhi-sanjeevani-hospital', 'Hospital Road, Dhangadhi, Kailali, Sudurpashchim Province, Nepal', 
 'Consultant Dental & Oral Surgeon', 'Permanent Workplace', '091-590328, 091-590348', 
 'https://www.jaishiamrit.com.np', 'https://maps.google.com/?q=Dhangadhi+Sanjeevani+Hospital+Kailali', 
 'uploads/profile/amrit_portrait.jpg', 
 'Dhangadhi Sanjeevani Hospital is a leading multidisciplinary healthcare center in Farwest Nepal. Dr. Amrit Jaishi operates a full-fledged Dental Department equipped with modern dental chair units, digital X-rays, rotary endodontics, and sterile surgical facilities.',
 'online_form', NULL, 1, 1);

-- 7. Clinic Schedules
INSERT INTO `clinic_schedules` (`id`, `clinic_id`, `visiting_day`, `start_time`, `end_time`, `notes`) VALUES
(1, 1, 'Sunday - Friday', '10:00:00', '17:00:00', 'General Dental OPD & Scheduled Procedures. Emergency on-call 24/7.');

-- 8. Blog Categories
INSERT INTO `blog_categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Oral Health Education', 'oral-health-education', 'Practical tips and guidance for maintaining optimal dental hygiene.'),
(2, 'Emergency Dental Care', 'emergency-dental-care', 'Managing toothache, oral trauma, and dental emergencies.'),
(3, 'Restorative Dentistry', 'restorative-dentistry', 'Understanding modern root canals, crowns, and tooth restorations.');

-- 9. Blogs & Articles
INSERT INTO `blogs` (`id`, `category_id`, `title`, `slug`, `excerpt`, `content_html`, `featured_image`, `image_alt`, `author_name`, `content_type`, `references_text`, `status`, `published_at`) VALUES
(1, 1, 'दाँतको नियमित हेरचाह र ब्रस गर्ने सही तरिका: स्वस्थ दाँतका लागि उपयोगी सुझावहरू', 'proper-tooth-brushing-and-oral-hygiene-guide', 
 'दिनमा दुई पटक सहि तरिकाले ब्रस गर्दा दाँत किराले खाने र गीजाको समस्याबाट ९०% बच्न सकिन्छ। जान्नुहोस् दन्त चिकित्सक डा. अमृत जैसीका सुझावहरू।',
 '<p>हाम्रो समग्र स्वास्थ्यको लागि मुखको स्वास्थ्य अत्यन्त महत्वपूर्ण हुन्छ। तर धेरैजसो मानिसहरू दाँत दुखेपछि मात्र अस्पताल जाने बानी हुन्छ।</p><h3>ब्रस गर्ने सहि तरिका:</h3><ul><li>दिनमा दुई पटक, बिहान खाना खाएपछि र राति सुत्नु अघि कम्तीमा २ मिनेट नरम (Soft) ब्रसले माझ्नुहोस्।</li><li>दाँतलाई बेस्सरी घोटेर होइन, हल्का गोलाकार चालमा माझ्नुपर्छ।</li><li>फ्लोराइडयुक्त मन्जन प्रयोग गर्नुहोस्।</li><li>दाँतको बीचको फोहोर निकाल्न डेन्टल फ्लस (Dental Floss) को प्रयोग गर्नुहोस्।</li></ul><p>हरेक ६ महिनामा दन्त चिकित्सकसँग दाँतको परीक्षण गराउनुहोस्।</p>',
 'uploads/services/teeth_cleaning.jpg', 'Tooth brushing oral health guide', 'Dr. Amrit Jaishi', 'educational_blog', 'Nepal Dental Association Guidelines for Preventive Oral Care.', 'published', NOW()),

(2, 2, 'दाँत एक्कासी दुख्दा के गर्ने? आकस्मिक प्राथमिक उपचार र सावधानीहरू', 'emergency-toothache-home-care-and-precautions',
 'राती वा यात्रामा दाँतमा तीव्र पीडा हुँदा अपनाउन सकिने प्राथमिक घरेलु उपायहरू र तुरुन्त डाक्टरलाई देखाउनुपर्ने लक्षणहरू।',
 '<p>दाँतको दुखाइ अचानक राति वा असुविधाजनक समयमा हुनसक्छ। यस्तो अवस्थामा आत्तिनुको सट्टा केही तत्काल सावधानी अपनाउन सकिन्छ:</p><h3>तत्काल गर्न सकिने प्राथमिक उपचार:</h3><ul><li>मनतातो पानीमा थोरै नुन मिसाएर कुल्ला गर्नुहोस्।</li><li>दाँतको कापमा अड्किएको खाना डेन्टल फ्लसले बिस्तारै निकाल्नुहोस्।</li><li>दुखेको ठाउँमा सिधै पेनकिलर चक्की नराख्नुहोस्, यसले गीजा पोल्न सक्छ।</li><li>दाह्री वा गाला बाहिरबाट बरफको चिसो सेकाइ गर्नुहोस्।</li></ul><p>जतिसक्दो चाँडो नजिकको दन्त अस्पतालमा पुगेर डा. अमृत जैसी वा विशेषज्ञसँग जाँच गराउनुहोस्।</p>',
 'uploads/services/emergency.jpg', 'Dental emergency toothache care', 'Dr. Amrit Jaishi', 'educational_blog', 'Farwest Dental Health Clinical Protocol.', 'published', NOW());

-- 10. Patient Testimonials
INSERT INTO `testimonials` (`id`, `patient_name`, `treatment_type`, `rating`, `quote`, `is_verified`, `display_order`) VALUES
(1, 'Ramesh K.', 'Root Canal & Crown', 5, 'Dr. Amrit Jaishi made my root canal completely painless. I was terrified of dental visits, but his gentle explanation and skilled hands made the entire treatment comfortable. Truly the best dental surgeon in Dhangadhi!', 1, 1),
(2, 'Sunita B.', 'Emergency Care & Gum Treatment', 5, 'Visited Dhangadhi Sanjeevani Hospital with severe tooth swelling at night. Dr. Jaishi provided immediate emergency relief and subsequent treatment. Extremely polite, highly qualified, and transparent.', 1, 2),
(3, 'Dipendra S.', 'Wisdom Tooth Removal & Cleaning', 5, 'My impacted wisdom tooth extraction was finished in under 20 minutes with zero discomfort. Clean clinic, modern equipment, and reasonable cost. Highly recommend Dr. Amrit Jaishi to everyone in Sudurpashchim.', 1, 3),
(4, 'Maya T.', 'Pediatric Dental Care & Fillings', 5, 'Dr. Amrit Jaishi was wonderfully patient and gentle with my 7-year-old son during his tooth filling. The hospital clinic is clean, hygienic, and child-friendly. No pain and no fear!', 1, 4),
(5, 'Prakash J.', 'Dental Bridge & Restoration', 5, 'Got my dental bridge done at Dhangadhi Sanjeevani Hospital. The shade match is so natural that nobody can tell it is artificial. Honest guidance and transparent charges.', 1, 5),
(6, 'Aarav S.', 'Scaling & Stain Removal', 5, 'Ultrasonic teeth cleaning was very gentle and thorough. Dr. Jaishi explained daily preventive oral care and flossing habits with great care. Truly top-notch service in Farwest Nepal.', 1, 6);

-- 11. Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('site_title', 'Dr. Amrit Jaishi | Oral & Dental Surgeon (BDS BPKIHS)'),
('doctor_name', 'Dr. Amrit Jaishi'),
('professional_title', 'BDS (BPKIHS) - Oral & Dental Surgeon'),
('contact_email', 'amritjaishi0@gmail.com'),
('contact_phone', '+977-9860644282'),
('hospital_phone', '091-590328'),
('whatsapp_number', '+9779860644282'),
('clinic_address', 'Dhangadhi Sanjeevani Hospital, Hospital Road, Dhangadhi, Kailali, Nepal'),
('working_hours', 'Sun - Fri: 10:00 AM - 5:00 PM (Emergency 24/7)'),
('emergency_guidance', 'For severe tooth trauma, bleeding, or acute facial swelling, report to Emergency OPD at Dhangadhi Sanjeevani Hospital (Call: 091-590328).'),
('facebook_url', 'https://facebook.com/amrit.jaishi'),
('twitter_url', 'https://twitter.com/'),
('linkedin_url', 'https://linkedin.com/in/amrit-jaishi'),
('youtube_url', 'https://youtube.com/@amritjaishi'),
('meta_default_description', 'Official portfolio of Dr. Amrit Jaishi, BDS (BPKIHS). Oral & Dental Surgeon at Dhangadhi Sanjeevani Hospital, Farwest Nepal. Painless root canal, dental crowns, restorative care, and emergency dental surgery.'),
('meta_title', 'Dr. Amrit Jaishi | Oral & Dental Surgeon (BDS BPKIHS)'),
('site_name', 'Dr. Amrit Jaishi Dental Care')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);


