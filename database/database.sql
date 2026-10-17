-- MySQL dump 10.13  Distrib 9.5.0, for Win64 (x86_64)
--
-- Host: localhost    Database: dental_doctor_db
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '7f75aa2d-eea2-11f0-ba4f-0a002700000a:1-10398';

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `achievements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_type` enum('Training','Certification','Conference','Award','Membership') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Certification',
  `organization` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `achievement_date` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `certificate_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achievements`
--

LOCK TABLES `achievements` WRITE;
/*!40000 ALTER TABLE `achievements` DISABLE KEYS */;
INSERT INTO `achievements` VALUES (1,'Bachelor of Dental Surgery (BDS)','Certification','B.P. Koirala Institute of Health Sciences (BPKIHS)','2019','Graduation from Nepal\'s premier autonomous health science university with comprehensive rotations across clinical dental specialties.',NULL,'https://bpkihs.edu',1,1,'2026-10-16 10:47:32'),(2,'Oral & Dental Surgeon Registration','Membership','Nepal Medical Council (NMC)','2020','Permanent registration as licensed dental surgeon authorized to deliver dental surgery and oral health treatments in Nepal.',NULL,'https://nmc.org.np',2,1,'2026-10-16 10:47:32'),(3,'Rotary Endodontics & Esthetic Dentistry Workshop','Training','Dental Science Forum','2022','Advanced clinical hands-on training on single-visit rotary canal preparation, warm vertical compaction, and direct composite layering.',NULL,NULL,3,1,'2026-10-16 10:47:32');
/*!40000 ALTER TABLE `achievements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_admin_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'Dr. Amrit Jaishi','admin@dentaldoctor.com','$2y$10$SbOobg4ioXXkefedw6qhWehRgoMPPGXPyf/R9AOuXEHmWaDGq7lt6','3780769e592c17698de73bbf2f3be03fe8e43aeaf76cfccc23a473a12a3add05',1,'2026-10-17 11:41:49','2026-10-16 10:47:32','2026-10-17 11:41:49');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` int unsigned DEFAULT NULL,
  `clinic_id` int unsigned DEFAULT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` enum('Pending','Confirmed','Completed','Cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_appt_status` (`status`),
  KEY `idx_appt_date` (`preferred_date`),
  KEY `fk_appt_service` (`service_id`),
  KEY `fk_appt_clinic` (`clinic_id`),
  CONSTRAINT `fk_appt_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_appt_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_categories`
--

DROP TABLE IF EXISTS `blog_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_categories`
--

LOCK TABLES `blog_categories` WRITE;
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
INSERT INTO `blog_categories` VALUES (1,'Preventive Dentistry','preventive-dentistry','Routine cleaning, brushing methods, and caries prevention.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(2,'Restorative Care','restorative-care','Composite fillings, tooth sensitivity, and tooth repair.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(3,'Endodontics','endodontics','Root canal therapy, rotary instrumentation, and pulp preservation.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(4,'Gum Health & Periodontics','gum-health','Gingivitis prevention, calculus scaling, and periodontal health.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(5,'Prosthodontics','prosthodontics','Zirconia crowns, dental bridges, and aesthetic restorations.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(6,'Oral Surgery','oral-surgery','Wisdom teeth management, atraumatic extractions, and minor surgery.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(7,'Pediatric Dental Care','pediatric-care','Children oral health, fluoride varnish, and diet guidance.','2026-10-17 11:57:15','2026-10-17 11:57:15'),(8,'Emergency Dental Care','emergency-care','Immediate first-aid for toothache, trauma, and dental swelling.','2026-10-17 11:57:15','2026-10-17 11:57:15');
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_html` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_alt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dr. Amrit Jaishi',
  `content_type` enum('educational_blog','research_publication') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'educational_blog',
  `references_text` text COLLATE utf8mb4_unicode_ci,
  `doi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `co_authors` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `views_count` int unsigned NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_blog_slug` (`slug`),
  KEY `idx_blog_status` (`status`,`published_at`),
  KEY `fk_blog_category` (`category_id`),
  CONSTRAINT `fk_blog_category` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,1,'The Importance of Regular Dental Checkups and Professional Cleaning','importance-of-regular-dental-checkups','Why routine biannual checkups and ultrasonic cleanings are essential for preventing hidden caries and gum disease.','<h3>Why Preventive Dentistry is Your Best Defense</h3>\n<p>Many individuals seek dental consultation only when they begin experiencing acute toothache, tender gums, or visible dental fractures. In dentistry, however, prevention is profoundly more effective and economical than late-stage intervention. Dental caries (tooth decay) and gingivitis (gum inflammation) often progress silently beneath the surface without causing noticeable pain until extensive structural damage has occurred.</p>\n<div class=\"article-callout-box\"><strong>Key Fact:</strong> Over 80% of tooth loss in adults can be prevented through routine biannual ultrasonic cleaning and early detection of microscopic cavities.</div>\n<h3>The Science Behind Professional Ultrasonic Scaling</h3>\n<p>Every day, a thin, sticky film of bacteria called plaque forms across tooth surfaces. While daily brushing and flossing disrupt soft plaque, minerals in saliva eventually harden residual plaque into dental calculus (tartar) within 24 to 72 hours.</p>\n<p>Once calculus hardens, standard toothbrushes, floss, or home remedies cannot remove it. Ultrasonic scaling utilizes high-frequency sound vibrations along with a gentle micro-mist of water to safely dislodge hardened tartar from both supra-gingival and sub-gingival surfaces without scratching tooth enamel.</p>\n<h3>Doctor\'s Recommendation</h3>\n<p>Commit to brushing twice a day for two full minutes with a soft-bristled toothbrush and fluoride toothpaste. Avoid aggressive scrubbing, incorporate daily flossing, and schedule a professional clinical visit every six months.</p>','img/services/teeth_cleaning.jpg','The Importance of Regular Dental Checkups and Professional Cleaning','Dr. Amrit Jaishi','educational_blog','Nepal Dental Association Guidelines for Preventive Oral Care.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(2,2,'Common Causes of Tooth Sensitivity and How to Treat It','common-causes-of-tooth-sensitivity-treatment','Understanding enamel erosion, exposed root surfaces, and modern clinical desensitizing options for daily comfort.','<h3>Understanding Dentin Hypersensitivity</h3>\n<p>Dentin hypersensitivity is characterized by a sharp, transient pain arising from exposed dentin in response to thermal (hot or cold drinks), chemical (sweets, acidic foods), tactile, or evaporative stimuli. Millions of individuals experience discomfort when enjoying a warm cup of tea or a cold beverage.</p>\n<h3>How Does Sensitivity Develop?</h3>\n<p>The crown of a healthy tooth is protected by an impervious outer layer of enamel, while the root is shielded by cementum and firm gum tissue. Beneath enamel lies dentin, a porous structure containing thousands of microscopic channels called dentinal tubules. When enamel thins or gums recede, external temperatures trigger fluid movement within the tubules, exciting nerve fibers and producing acute pain.</p>\n<h3>Modern Clinical Solutions</h3>\n<ul>\n<li><strong>In-Office Desensitizing Varnishes:</strong> Professional application of concentrated fluoride or calcium phosphate agents that occlude open dentinal tubules.</li>\n<li><strong>Tooth-Colored Composite Bonding:</strong> For deep cervical abrasion notches, a micro-layer of aesthetic composite resin is bonded to shield exposed nerve endings permanently.</li>\n<li><strong>Custom Nightguards:</strong> Fabricated for patients suffering from bruxism to protect enamel from nocturnal grinding forces.</li>\n</ul>','img/services/gum_care.jpg','Common Causes of Tooth Sensitivity and How to Treat It','Dr. Amrit Jaishi','educational_blog','Journal of Clinical Periodontology: Dentin Hypersensitivity Etiology & Management.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(3,3,'Understanding Root Canal Treatment: What Patients Should Know','understanding-root-canal-treatment','Demystifying rotary endodontic treatment, pain management, and why preserving your natural tooth is always the best choice.','<h3>Preserving Your Natural Tooth Structure</h3>\n<p>Root canal treatment (endodontic therapy) is one of the most misunderstood dental procedures. Contrary to popular anxiety, modern endodontics does not cause pain — it relieves excruciating toothache caused by deep pulp inflammation or bacterial infection.</p>\n<h3>When is a Root Canal Necessary?</h3>\n<p>Inside every natural tooth is a soft inner chamber known as the dental pulp, containing blood vessels, connective tissues, and sensory nerves. When deep bacterial caries, repeated fillings, or traumatic cracks penetrate into the pulp, bacteria cause irreversible pulpitis or necrosis.</p>\n<h3>Modern Rotary Endodontics at Our Clinic</h3>\n<p>Dr. Amrit Jaishi utilizes modern nickel-titanium (NiTi) rotary files, electronic apex locators, and sterile rubber dam isolation. This enables faster, quieter, and exceptionally precise single-visit or two-visit treatments with minimal post-operative sensitivity.</p>','img/services/rct.jpg','Understanding Root Canal Treatment: What Patients Should Know','Dr. Amrit Jaishi','educational_blog','American Association of Endodontists Clinical Guidance for Pulp Therapy.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(4,4,'Bleeding Gums When Brushing: Warning Signs You Shouldn\'t Ignore','bleeding-gums-warning-signs','How early gingivitis progresses to irreversible periodontal bone loss, and why gentle flossing and professional scaling reverse bleeding.','<h3>Is Gum Bleeding Normal?</h3>\n<p>A common misconception is that gums bleed simply because one brushed \"too hard\". In reality, healthy gum tissue does not bleed upon routine brushing or flossing. Bleeding is the hallmark clinical indicator of active gingival inflammation caused by bacterial plaque.</p>\n<h3>Gingivitis vs. Periodontitis</h3>\n<p><strong>Gingivitis:</strong> The early, completely reversible stage of gum disease confined to soft tissue. With gentle ultrasonic scaling and daily flossing, gingivitis heals completely in 1-2 weeks.</p>\n<p><strong>Periodontitis:</strong> If plaque and calculus remain unaddressed, inflammation migrates deeper into the periodontal ligament and alveolar jawbone, causing irreversible bone resorption and loose teeth.</p>','img/services/teeth_cleaning.jpg','Bleeding Gums When Brushing: Warning Signs You Shouldn\'t Ignore','Dr. Amrit Jaishi','educational_blog','European Federation of Periodontology Consensus Guidelines.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(5,5,'Choosing the Right Crown: Zirconia, E-Max Ceramic, or PFM?','choosing-right-dental-crown-materials','Comparing fracture toughness, translucent lifelike aesthetics, and biocompatibility when restoring treated teeth with permanent caps.','<h3>Why Do Treated Teeth Require Crowns?</h3>\n<p>Following extensive tooth decay or root canal treatment, a significant portion of natural tooth structure is hollowed out. A custom-fabricated dental crown acts as a protective 360-degree helmet, preventing the tooth from catastrophic fracture under daily chewing forces.</p>\n<h3>Comparing Crown Materials</h3>\n<ul>\n<li><strong>Monolithic Zirconia:</strong> Exceptional flexural strength (>1000 MPa), virtually chip-proof, ideal for posterior molars subjected to heavy chewing.</li>\n<li><strong>Lithium Disilicate (E-Max):</strong> Outstanding lifelike translucency and natural aesthetic shade matching, ideal for anterior front teeth.</li>\n<li><strong>Porcelain-Fused-to-Metal (PFM):</strong> Traditional durable option with a metallic coping beneath ceramic coating.</li>\n</ul>','img/services/dental_crown.jpg','Choosing the Right Crown: Zirconia, E-Max Ceramic, or PFM?','Dr. Amrit Jaishi','educational_blog','Journal of Prosthodontic Research: Ceramic Biomaterials Comparison.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(6,6,'Wisdom Tooth Pain & Pericoronitis: When is Surgical Removal Necessary?','wisdom-tooth-pain-surgical-removal-timing','Clinical indicators for third molar extractions, preventative surgical timings, and essential 48-hour recovery guidelines.','<h3>Why Do Wisdom Teeth Cause Trouble?</h3>\n<p>Wisdom teeth (third molars) typically erupt between ages 17 and 25. In modern jaws, there is often inadequate arch space, resulting in partial eruption or impaction (teeth trapped at odd angles against neighboring molars or jawbone).</p>\n<h3>What is Pericoronitis?</h3>\n<p>When a lower wisdom tooth only partially emerges, an operculum (flap of gum) covers the biting surface. Food debris and bacteria accumulate under this flap where toothbrushes cannot reach, causing acute localized swelling, foul odor, and difficulty opening the jaw (trismus).</p>\n<h3>Atraumatic Sectioning Technique</h3>\n<p>Dr. Amrit Jaishi performs gentle crown-root sectioning under local anesthesia with socket preservation, preventing bone trauma and dramatically reducing post-surgical downtime.</p>','img/services/wisdom_tooth.jpg','Wisdom Tooth Pain & Pericoronitis: When is Surgical Removal Necessary?','Dr. Amrit Jaishi','educational_blog','British Association of Oral and Maxillofacial Surgeons Third Molar Guidelines.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(7,7,'Protecting Children\'s Enamel: Fluoride Varnish & Diet Guidelines','pediatric-fluoride-and-enamel-protection','How early childhood caries start from sticky snacks, and practical parenting steps for establishing lifelong dental brushing habits.','<h3>The Vulnerability of Primary Teeth</h3>\n<p>Primary (baby) teeth have significantly thinner enamel and larger nerve chambers than adult permanent teeth. A small microscopic cavity can penetrate into the pulp within months, leading to painful abscesses that can harm developing permanent tooth buds underneath.</p>\n<h3>Parenting Guidelines for Cavity-Free Childhood</h3>\n<ul>\n<li>Never let toddlers sleep with a feeding bottle containing milk, formula, or sweetened drinks (Baby Bottle Tooth Decay).</li>\n<li>Introduce a rice-grain smear of fluoride toothpaste as soon as the first tooth erupts.</li>\n<li>Professional in-office fluoride varnish applications twice a year protect developing enamel from acid demineralization.</li>\n</ul>','img/services/emergency.jpg','Protecting Children\'s Enamel: Fluoride Varnish & Diet Guidelines','Dr. Amrit Jaishi','educational_blog','American Academy of Pediatric Dentistry Caries Prevention Policy.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(8,1,'दाँतको नियमित हेरचाह र ब्रस गर्ने सही तरिका: स्वस्थ दाँतका लागि उपयोगी सुझावहरू','proper-tooth-brushing-and-oral-hygiene-guide','दिनमा दुई पटक सहि तरिकाले ब्रस गर्दा दाँत किराले खाने र गीजाको समस्याबाट ९०% बच्न सकिन्छ। जान्नुहोस् दन्त चिकित्सक डा. अमृत जैसीका सुझावहरू।','<h3>मुख तथा दाँतको स्वास्थ्य किन महत्वपूर्ण छ?</h3>\n<p>हाम्रो समग्र स्वास्थ्यको लागि मुखको स्वास्थ्य अत्यन्त महत्वपूर्ण हुन्छ। तर धेरैजसो मानिसहरू दाँतमा कडा दुखाइ सुरु भएपछि मात्र अस्पताल जाने बानी हुन्छ। समयमै हेरचाह गरेमा धेरै समस्याबाट सहजै बच्न सकिन्छ।</p>\n<h3>ब्रस गर्ने सहि तरिका:</h3>\n<ul>\n<li>दिनमा दुई पटक, बिहान खाना खाएपछि र राति सुत्नु अघि कम्तीमा २ मिनेट नरम (Soft) ब्रसले माझ्नुहोस्।</li>\n<li>दाँतलाई बेस्सरी घोटेर होइन, हल्का गोलाकार (Circular) चालमा माझ्नुपर्छ ताकि इनामेल नघसियोस्।</li>\n<li>फ्लोराइडयुक्त मन्जन प्रयोग गर्नुहोस् जसले इनामेललाई मजबुत बनाउँछ।</li>\n<li>दाँतको बीचको फोहोर निकाल्न डेन्टल फ्लस (Dental Floss) को प्रयोग गर्नुहोस्।</li>\n</ul>\n<p>हरेक ६ महिनामा दन्त चिकित्सकसँग दाँतको नियमित परीक्षण गराउनुहोस्।</p>','img/services/teeth_cleaning.jpg','दाँतको नियमित हेरचाह र ब्रस गर्ने सही तरिका: स्वस्थ दाँतका लागि उपयोगी सुझावहरू','Dr. Amrit Jaishi','educational_blog','Nepal Dental Association Guidelines for Preventive Oral Care.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL),(9,8,'दाँत एक्कासी दुख्दा के गर्ने? आकस्मिक प्राथमिक उपचार र सावधानीहरू','emergency-toothache-home-care-and-precautions','राती वा यात्रामा दाँतमा तीव्र पीडा हुँदा अपनाउन सकिने प्राथमिक घरेलु उपायहरू र तुरुन्त डाक्टरलाई देखाउनुपर्ने लक्षणहरू।','<h3>दाँतको आकस्मिक दुखाइमा के गर्ने?</h3>\n<p>दाँतको दुखाइ अचानक राति वा असुविधाजनक समयमा हुनसक्छ। यस्तो अवस्थामा आत्तिनुको सट्टा केही तत्काल सावधानी अपनाउन सकिन्छ:</p>\n<h3>तत्काल गर्न सकिने प्राथमिक उपचार:</h3>\n<ul>\n<li>मनतातो पानीमा थोरै नुन मिसाएर बिस्तारै कुल्ला गर्नुहोस्। यसले सुन्निएको गीजालाई राहत दिन्छ।</li>\n<li>दाँतको कापमा अड्किएको खाना डेन्टल फ्लसले बिस्तारै निकाल्नुहोस्।</li>\n<li>दुखेको ठाउँमा सिधै पेनकिलर चक्की नराख्नुहोस्, यसले गीजालाई रासायनिक रूपमा पोल्न सक्छ।</li>\n<li>दाह्री वा गाला बाहिरबाट बरफको चिसो सेकाइ गर्नुहोस्।</li>\n</ul>\n<p>जतिसक्दो चाँडो नजिकको दन्त अस्पतालमा पुगेर डा. अमृत जैसी वा विशेषज्ञसँग जाँच गराउनुहोस्।</p>','img/services/emergency.jpg','दाँत एक्कासी दुख्दा के गर्ने? आकस्मिक प्राथमिक उपचार र सावधानीहरू','Dr. Amrit Jaishi','educational_blog','Farwest Dental Health Clinical Protocol & Hospital Emergency OPD.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'published',0,'2026-10-17 11:57:15','2026-10-17 11:57:15','2026-10-17 11:57:15',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinic_schedules`
--

DROP TABLE IF EXISTS `clinic_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinic_schedules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `clinic_id` int unsigned NOT NULL,
  `visiting_day` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_schedule_clinic` (`clinic_id`),
  CONSTRAINT `fk_schedule_clinic` FOREIGN KEY (`clinic_id`) REFERENCES `clinics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinic_schedules`
--

LOCK TABLES `clinic_schedules` WRITE;
/*!40000 ALTER TABLE `clinic_schedules` DISABLE KEYS */;
INSERT INTO `clinic_schedules` VALUES (1,1,'Sunday - Friday','10:00:00','17:00:00','General Dental OPD & Scheduled Procedures. Emergency on-call 24/7.','2026-10-16 10:47:32');
/*!40000 ALTER TABLE `clinic_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clinics`
--

DROP TABLE IF EXISTS `clinics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clinics` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `clinic_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Dhangadhi',
  `doctor_role` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Consultant Dental Surgeon',
  `workplace_type` enum('Permanent Workplace','Visiting Consultant') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Permanent Workplace',
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `working_days` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Sunday - Friday',
  `working_hours` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '10:00 AM - 5:00 PM',
  `website_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map_url` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `appointment_type` enum('online_form','call_only','external_link') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'online_form',
  `appointment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_clinic_slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clinics`
--

LOCK TABLES `clinics` WRITE;
/*!40000 ALTER TABLE `clinics` DISABLE KEYS */;
INSERT INTO `clinics` VALUES (1,'Dhangadhi Sanjeevani Hospital','dhangadhi-sanjeevani-hospital','Hospital Road, Dhangadhi, Kailali, Sudurpashchim Province, Nepal','Dhangadhi, Kailali','Consultant Dental & Oral Surgeon (Outpatient & Emergency Dental Department)','Permanent Workplace','Direct: +977-9860644282 • Hospital: 091-590328, 091-590348','Sunday - Friday','10:00 AM - 5:00 PM (Emergency On-Call: 24/7)','https://www.jaishiamrit.com.np','https://maps.google.com/?q=Dhangadhi+Sanjeevani+Hospital+Kailali','uploads/clinics/dhangadhi_sanjeevani_hospital.jpg','Dhangadhi Sanjeevani Hospital is a leading multidisciplinary healthcare center in Farwest Nepal. Dr. Amrit Jaishi operates a full-fledged Dental Department equipped with modern dental chair units, digital X-rays, rotary endodontics, and sterile surgical facilities.','online_form',NULL,1,1,'2026-10-16 10:47:32','2026-10-16 17:15:39'),(2,'Rural Oral Health Screening Outreach','rural-oral-health-screening-outreach','Community Schools & Health Posts across Kailali & Sudurpashchim Province','Farwest Nepal','Volunteer Visiting Dental Surgeon','Visiting Consultant','Coordinated through Sanjeevani Hospital Dental Department','Scheduled Weekend Outreach Camps','School Health Days & Community Drives',NULL,'#contact','uploads/clinics/rural_outreach_clinic.jpg','Community dental outreach providing oral health awareness and screening for rural schools.','online_form',NULL,1,2,'2026-10-16 17:15:40','2026-10-16 17:15:40');
/*!40000 ALTER TABLE `clinics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctor_profile`
--

DROP TABLE IF EXISTS `doctor_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctor_profile` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `doctor_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `professional_title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hero_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_subheading` text COLLATE utf8mb4_unicode_ci,
  `hero_lead` text COLLATE utf8mb4_unicode_ci,
  `hero_approach` text COLLATE utf8mb4_unicode_ci,
  `about_kicker` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_subheading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_badge_title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_badge_subtitle` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `experience_years` int unsigned NOT NULL DEFAULT '4',
  `experience_description` text COLLATE utf8mb4_unicode_ci,
  `philosophy` text COLLATE utf8mb4_unicode_ci,
  `languages` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Nepali, English, Hindi',
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hero_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emergency_contact` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hero_btn1_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'View Dental Services',
  `hero_btn1_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#services',
  `hero_btn2_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Contact Doctor',
  `hero_btn2_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '#contact',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctor_profile`
--

LOCK TABLES `doctor_profile` WRITE;
/*!40000 ALTER TABLE `doctor_profile` DISABLE KEYS */;
INSERT INTO `doctor_profile` VALUES (1,'Dr. Amrit Jaishi','BDS (BPKIHS)','','Dr. Amrit Jaishi provides dedicated, gentle, and modern dental treatments at Dhangadhi Sanjeevani Hospital.','Dedicated to providing patient-centered dental care with a focus on oral health, preventive dentistry, and comfortable treatment experiences. Committed to helping individuals maintain healthy smiles through professional care, clear communication, and evidence-based dental practices.','Every clinical procedure is guided by conservative tooth preservation, gentle pain-free techniques, and transparent guidance tailored to each patient\'s individual needs.','Professional Background','Committed to Patient Comfort, Ethics & Clinical Precision','4+ Years Clinical Care','Oral & Dental Surgery','','Dr. Amrit Raj Jaishi has been serving as an Oral and Dental Surgeon in Farwest Province, Nepal, for over four years. Currently based at Dhangadhi Sanjeevani Hospital, Dr. Jaishi was drawn to dentistry by its transformative ability to restore smiles, relieve acute pain, and dramatically enhance patient quality of life.\r\n\r\nDr. Jaishi completed his Bachelor of Dental Surgery (BDS) from B.P. Koirala Institute of Health Sciences (BPKIHS Dharan) — one of the leading autonomous health science universities and medical institutions in Nepal. Dedicated to continuous professional growth, patient education, and compassionate clinical care, Dr. Jaishi specializes in pain-free root canal treatment, dental crowns, restorative dentistry, emergency trauma management, and pediatric dental solutions.',4,'','Every patient deserves gentle, ethical, and transparent dental care. We prioritize preserving natural teeth, minimizing discomfort with modern clinical techniques, and empowering patients with practical oral health education.','Nepali, English, Hindi','uploads/profile/amrit_jaishi.jpg','uploads/profile/b1a3e2d6397163b115f45618272af140.png','img/doctor-clinic.jpg','amritjaishi0@gmail.com','+977-9860644282','+9779860644282','091-590328 / 091-590348 (Hospital Line)','2026-10-17 11:58:08','View Dental Services','#services','Contact Doctor','#contact');
/*!40000 ALTER TABLE `doctor_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_categories`
--

DROP TABLE IF EXISTS `gallery_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(130) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_categories`
--

LOCK TABLES `gallery_categories` WRITE;
/*!40000 ALTER TABLE `gallery_categories` DISABLE KEYS */;
INSERT INTO `gallery_categories` VALUES (1,'Clinical Practice','clinical-practice',NULL,1,'2026-10-16 15:37:13'),(2,'Training','training',NULL,2,'2026-10-16 15:37:13'),(3,'Conferences','conferences',NULL,3,'2026-10-16 15:37:13'),(4,'Achievements','achievements',NULL,4,'2026-10-16 15:37:13');
/*!40000 ALTER TABLE `gallery_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_images`
--

DROP TABLE IF EXISTS `gallery_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_images` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Clinic Facility',
  `display_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_images`
--

LOCK TABLES `gallery_images` WRITE;
/*!40000 ALTER TABLE `gallery_images` DISABLE KEYS */;
INSERT INTO `gallery_images` VALUES (1,1,'img/gallery/clinical_practice_1.jpg','Operatory Clinical Practice','Dr. Amrit Jaishi conducting restorative dental consultation at Dhangadhi Sanjeevani Hospital','Dr. Amrit Jaishi conducting restorative dental consultation','Clinic Facility',1,1,'2026-10-16 15:37:13'),(2,4,'img/gallery/hospital_honor_award.jpg','Hospital Service Recognition','Honored for dedicated oral healthcare and emergency clinical service in Farwest Nepal','Service award and recognition','Clinic Facility',2,1,'2026-10-16 15:37:13'),(3,2,'img/gallery/clinical_training_seminar.jpg','Advanced Endodontic Training','Continuous dental education hands-on workshop on rotary instrumentation','Hands-on dental training seminar','Clinic Facility',3,1,'2026-10-16 15:37:13'),(4,1,'img/gallery/dental_camp_team.jpg','Community Dental Outreach','Voluntary oral screening camp providing dental hygiene awareness to regional schools','School dental screening camp','Clinic Facility',4,1,'2026-10-16 15:37:13'),(5,2,'img/gallery/endodontic_rotary_prep.jpg','Rotary Endodontics Workshop','Hands-on clinical study of single-visit rotary canal shaping and irrigation protocols','Rotary endodontics demonstration','Clinic Facility',5,1,'2026-10-16 15:37:13'),(6,3,'img/gallery/community_screening.jpg','Regional Dental Conference','Participating in clinical exchange sessions with regional dental surgeons','Dental surgeon conference session','Clinic Facility',6,1,'2026-10-16 15:37:13');
/*!40000 ALTER TABLE `gallery_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reset_token` (`token_hash`),
  KEY `idx_reset_user` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `degree` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institution` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_year` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completion_year` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `certificate_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualifications`
--

LOCK TABLES `qualifications` WRITE;
/*!40000 ALTER TABLE `qualifications` DISABLE KEYS */;
INSERT INTO `qualifications` VALUES (1,'Bachelor of Dental Surgery (BDS)','B.P. Koirala Institute of Health Sciences (BPKIHS)','2014','Dharan, Sunsari, Nepal','2019','Five-year rigorous university medical curriculum and full-time clinical rotations across Conservative Dentistry, Endodontics, Oral & Maxillofacial Surgery, Prosthodontics, Periodontics, and Community Dental Health.',NULL,1,1,'2026-10-16 17:45:22'),(2,'Registered Dental Surgeon','Nepal Medical Council (NMC)','2020','Kathmandu, Nepal','Present','Official clinical licensure and permanent registration with the Nepal Medical Council, granting full statutory authority for independent practice in dental surgery, diagnostic screening, and restorative procedures across Nepal.',NULL,2,1,'2026-10-16 17:45:22'),(3,'Hospital Sterilization & Biosafety Protocols','Hospital Clinical Governance Training','2021','Farwest Health Directorate','2021','In-depth clinical protocols covering multi-stage autoclave validation, surgical armamentarium chemical decontamination, surface barrier management, and zero-cross-infection protocols adhering to international healthcare standards.',NULL,3,1,'2026-10-16 17:45:22'),(4,'Advanced Rotary Endodontics & Esthetics','Dental Science Forum & Clinical Workshop','2022','Kathmandu, Nepal','2022','Hands-on advanced clinical training focusing on single-visit rotary canal instrumentation, modern electronic apex locators, warm vertical 3D obturation, and biomimetic anterior composite shade stratification.',NULL,4,1,'2026-10-16 17:45:22'),(5,'Community Oral Health Outreach & Screening','Farwest Rural Dental Screening Initiative','2021','Kailali District & Farwest Region','Present','Active field leadership across regional mobile dental camps, conducting free diagnostic screenings, early caries interventions, topical fluoride therapy, and oral hygiene workshops across rural public schools.',NULL,5,1,'2026-10-16 17:45:22'),(6,'Consultant Dental Surgeon','Dhangadhi Sanjeevani Hospital','2023','Dhangadhi-3, Kailali, Nepal','Present','Providing full-spectrum restorative, endodontic, prosthetic crown & bridge, periodontal gum therapy, and atraumatic minor oral surgical extractions with modern hospital facilities and patient-centered protocols.',NULL,6,1,'2026-10-16 17:45:22');
/*!40000 ALTER TABLE `qualifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `researches`
--

DROP TABLE IF EXISTS `researches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `researches` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `abstract` text COLLATE utf8mb4_unicode_ci,
  `full_text` mediumtext COLLATE utf8mb4_unicode_ci,
  `authors` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Dr. Amrit Jaishi',
  `journal_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_year` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doi` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publication_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `researches`
--

LOCK TABLES `researches` WRITE;
/*!40000 ALTER TABLE `researches` DISABLE KEYS */;
INSERT INTO `researches` VALUES (1,'Clinical Evaluation of Single-Visit Rotary Endodontics in Symptomatic Irreversible Pulpitis','single-visit-rotary-endodontics-evaluation','A comparative observational study examining post-operative pain and clinical success in single-visit versus multiple-visit endodontic therapy using NiTi rotary files.','Introduction: Rotary endodontic instrumentation has revolutionized modern endodontics. This observational study evaluates postoperative pain incidence and 12-month radiographic healing rates in 85 patients presenting with irreversible pulpitis in Farwest Nepal. Conclusion: Single-visit rotary treatment demonstrated equal healing rates and significantly reduced total patient appointments.','Full clinical methodology, patient criteria, rotary file sequences, and statistical tables are documented in this prospective evaluation conducted across regional clinical centers.','Dr. Amrit Jaishi, BDS (BPKIHS)','Journal of Nepalese Dental Association (JNDA)','2023','10.3126/jnda.v23i1.48201','https://example.org/dental-research/single-visit-endodontics',NULL,NULL,0,'published','2026-10-16 15:33:23','2026-10-16 15:33:23'),(2,'Prevalence of Periodontal Disease and Oral Health Awareness in Rural Communities of Kailali District','periodontal-disease-prevalence-kailali','Community dental epidemiological survey assessing CPITN scores, oral hygiene index, and dietary habits across rural municipalities.','Abstract: A cross-sectional field study of 420 adults screened during regional mobile health camps. Results revealed high calculus accumulation and low fluoride toothpaste adoption, underscoring the urgent need for structured public health preventive scaling initiatives.','Complete survey demographics, CPITN indices, community education workshops, and policy recommendations for Farwest healthcare initiatives.','Dr. Amrit Jaishi, BDS (BPKIHS)','Farwest Medical & Health Sciences Review','2022',NULL,'https://example.org/health-studies/kailali-oral-survey',NULL,NULL,0,'published','2026-10-16 15:33:23','2026-10-16 15:33:23');
/*!40000 ALTER TABLE `researches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'General Dentistry',
  `short_description` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `benefits` text COLLATE utf8mb4_unicode_ci,
  `procedure_steps` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activity',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `display_order` int NOT NULL DEFAULT '0',
  `meta_title` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_service_slug` (`slug`),
  KEY `idx_service_active` (`is_active`,`display_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Emergency Dental Care','emergency-dental-care','Emergency Dentistry','Immediate relief for severe toothache, dental trauma, knocked-out teeth, and acute oral infections.','Dental emergencies require rapid, skilled intervention to alleviate intense pain and salvage compromised teeth. Dr. Amrit Jaishi provides comprehensive emergency oral care at Dhangadhi Sanjeevani Hospital for fractures, toothache, acute pulpitis, and oral lacerations.\n\nOur team rapidly evaluates trauma using digital diagnostics and provides swift, pain-relieving stabilization.','Immediate pain relief and emergency care\nPrevents permanent tooth loss and systemic infection\nGentle local anesthesia for instant comfort\nFollow-up care and comprehensive restorative plan','Step 1: Emergency triage & digital diagnostic evaluation\nStep 2: Instant pain management & targeted local anesthesia\nStep 3: Immediate tooth stabilization, pulpectomy or medication\nStep 4: Prescriptions and tailored long-term recovery plan','uploads/services/emergency.jpg',NULL,'shield-alert',1,1,1,'Emergency Dental Care in Dhangadhi | Dr. Amrit Jaishi','Immediate relief for severe toothache and dental trauma by Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(2,'Dental Crown & Bridge','dental-crown-and-bridge','Restorative Dentistry','Durable, natural-looking porcelain and zirconia crowns to restore broken, weakened, or missing teeth.','Dental crowns protect extensively damaged or root-canal-treated teeth, restoring their structural integrity, chewing strength, and aesthetic appearance. Bridges seamlessly span gaps caused by missing teeth.\n\nDr. Jaishi uses precision impression techniques and high-grade biocompatible materials that match your natural tooth contour and enamel shade.','Restores 100% natural chewing function\nProtects root canal treated teeth from fracture\nCustom-matched color and natural light translucency\nLong-lasting durability and gum-line compatibility','Step 1: Clinical assessment and tooth preparation\nStep 2: High-accuracy impression & shade matching\nStep 3: Temporary protective crown placement\nStep 4: Permanent precision crown bonding & bite alignment','uploads/services/dental_crown.jpg',NULL,'crown',1,1,2,'Dental Crown & Bridge Treatment | Dr. Amrit Jaishi','Protect and restore damaged teeth with custom dental crowns and bridges crafted with precision by Dr. Amrit Jaishi.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(3,'Root Canal Treatment (RCT)','root-canal-treatment-rct','Endodontics','Pain-free root canal treatment to save infected, deeply decayed teeth and eliminate toothache.','Root canal treatment saves natural teeth that would otherwise need extraction due to deep decay, pulp infection, or trauma. Using modern rotary endodontic equipment, Dr. Amrit Jaishi removes inflamed pulp, cleans and shapes root canals, and hermetically seals them.\n\nWith gentle local anesthesia, RCT is comfortable and effectively ends toothache in 1-2 smooth visits.','Saves natural tooth and maintains jawbone integrity\nInstant relief from severe throbbing tooth pain\nPainless modern rotary endodontic technology\nPrevents abscess spread and facial swelling','Step 1: Diagnostic digital X-ray and painless local numbing\nStep 2: Infected pulp removal & rotary canal disinfection\nStep 3: Hermetic biocompatible gutta-percha obturation\nStep 4: Post-endodontic restorative crown build-up','uploads/services/rct.jpg',NULL,'activity',1,1,3,'Painless Root Canal Treatment (RCT) | Dr. Amrit Jaishi','Gentle and effective root canal treatment by Dr. Amrit Jaishi to save natural teeth and eliminate toothache permanently.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(4,'Periodontal & Gum Care','periodontal-gum-care','Periodontics','Comprehensive treatment for bleeding gums, gingivitis, periodontitis, and loose teeth.','Healthy gums are the foundation of a healthy smile. Gum disease often develops silently with symptoms like bleeding during brushing, persistent bad breath, and receding gums. Dr. Jaishi provides specialized deep scaling, root planing, and antiseptic irrigation to eliminate bacterial plaque under the gumline and restore tissue firmness.','Eliminates bleeding, redness, and gum soreness\nPrevents tooth loosening and premature tooth loss\nRemoves deep subgingival tartar and bacterial toxins\nRestores fresh breath and healthy pink gums','Step 1: Periodontal pocket depth measurement\nStep 2: Ultrasonic subgingival scaling\nStep 3: Root surface smoothing (planing)\nStep 4: Antimicrobial irrigation & homecare instructions','uploads/services/gum_care.jpg',NULL,'heart-pulse',1,1,4,'Gum Disease Treatment & Periodontal Care | Dr. Amrit Jaishi','Stop gum bleeding and protect your teeth with specialized periodontal care and deep cleaning by Dr. Amrit Jaishi.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(5,'Teeth Cleaning & Scaling','teeth-cleaning-and-scaling','Preventive Dentistry','Professional ultrasonic cleaning, stain removal, and polishing for healthy teeth and radiant smile.','Routine brushing cannot remove hardened tartar (calculus). Ultrasonic scaling effortlessly removes calculus deposits, coffee/tea stains, and bacterial biofilm without damaging natural enamel. Routine scaling every 6 months prevents cavities, gum inflammation, and bad breath.','Removes tough tartar and yellowing surface stains\nProtects against cavities and gingival inflammation\nQuick, comfortable, and non-invasive procedure\nLeaves teeth clean, smooth, and naturally bright','Step 1: Comprehensive oral examination\nStep 2: Ultrasonic cavitation scaling\nStep 3: Gentle fine hand instrumentation\nStep 4: Prophy-paste polishing & enamel fluoridation','uploads/services/teeth_cleaning.jpg',NULL,'sparkles',1,1,5,'Professional Teeth Cleaning & Scaling | Dr. Amrit Jaishi','Maintain optimal oral hygiene with gentle ultrasonic scaling and polishing by Dr. Amrit Jaishi.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(6,'Tooth Extraction & Minor Surgery','tooth-extraction-minor-surgery','Oral Surgery','Safe, painless extractions for severely decayed teeth, retained roots, and dental trauma.','When a tooth cannot be saved by restorative treatment, gentle extraction prevents infection from spreading. Dr. Amrit Jaishi performs careful, atraumatic extractions with gentle technique, minimizing post-operative swelling and ensuring rapid healing.','Painless extraction with effective local anesthesia\nAtraumatic socket preservation technique\nRapid recovery with clear post-op care guidance\nPrevents severe spread of facial dental infections','Step 1: Radiographic bone and root evaluation\nStep 2: Gentle, profound local anesthesia\nStep 3: Atraumatic tooth elevation and removal\nStep 4: Socket hemorrage control & healing instructions','uploads/services/extraction.jpg',NULL,'wrench',1,1,6,'Gentle Tooth Extraction & Minor Oral Surgery | Dr. Amrit Jaishi','Comfortable and safe tooth extractions performed with gentle surgical technique by Dr. Amrit Jaishi.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(7,'Wisdom Tooth Removal','wisdom-tooth-removal','Oral Surgery','Surgical management of painful, impacted, or misaligned third molars (Óñ¼ÓÑüÓñªÓÑìÓñºÓÑÇ Óñ¼ÓñéÓñùÓñ¥Óñ░Óñ¥).','Impacted wisdom teeth frequently cause severe pain, pericoronitis (gum swelling), damage to adjacent molars, and jaw stiffness. Dr. Jaishi performs precise minor surgical removal of impacted wisdom teeth with thorough pain control and gentle surgical protocol.','Relieves recurrent jaw pain, swelling, and headaches\nPrevents cavities and bone loss on adjacent second molars\nEliminates painful gum infections around partially erupted teeth\nGentle procedure with quick clinical recovery','Step 1: Panoramic/digital X-ray evaluation of nerve and root\nStep 2: Profound local anesthesia\nStep 3: Delicate surgical access & sectional removal\nStep 4: Sterile suturing & detailed post-operative medications','uploads/services/wisdom_tooth.jpg',NULL,'smile',1,0,7,'Wisdom Tooth Removal in Dhangadhi | Dr. Amrit Jaishi','Relieve impacted wisdom tooth pain with safe minor oral surgery by Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital.','2026-10-16 10:47:32','2026-10-16 10:47:32'),(8,'Dental Braces & Orthodontics','dental-braces-orthodontics','Orthodontics','Correction of crooked, crowded, spaced teeth and irregular bites for children and adults.','Properly aligned teeth not only enhance your smile aesthetics but also improve chewing efficiency and make brushing far easier. Dr. Jaishi provides orthodontic assessment, space management, and braces to align teeth into harmony.','Straightens crooked, overlapping, and crowded teeth\nCloses gaps and enhances facial profile aesthetics\nImproves chewing function and speech clarity\nLong-term retention plan for lasting smile stability','Step 1: Diagnostic photography, study models & bite analysis\nStep 2: Customized orthodontic treatment planning\nStep 3: Bracket bonding and initial gentle archwire placement\nStep 4: Monthly adjustments and final retainer delivery','uploads/services/braces.jpg',NULL,'smile',1,0,8,'Dental Braces & Orthodontic Care | Dr. Amrit Jaishi','Straighten your smile and correct irregular bites with modern orthodontic treatment guided by Dr. Amrit Jaishi.','2026-10-16 10:47:32','2026-10-16 10:47:32');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_title','Dr. Amrit Jaishi | Oral & Dental Surgeon (BDS BPKIHS)','2026-10-16 10:47:32'),(2,'doctor_name','Dr. Amrit Jaishi','2026-10-16 10:47:32'),(3,'professional_title','BDS (BPKIHS) - Oral & Dental Surgeon','2026-10-16 10:47:32'),(4,'contact_email','amritjaishi0@gmail.com','2026-10-16 10:47:32'),(5,'contact_phone','+977-9860644282','2026-10-16 10:47:32'),(6,'hospital_phone','091-590328','2026-10-16 10:47:32'),(7,'whatsapp_number','+9779860644282','2026-10-16 10:47:32'),(8,'clinic_address','Dhangadhi Sanjeevani Hospital, Hospital Road, Dhangadhi, Kailali, Nepal','2026-10-16 10:47:32'),(9,'working_hours','Sun - Fri: 10:00 AM - 5:00 PM (Emergency 24/7)','2026-10-16 10:47:32'),(10,'emergency_guidance','For severe tooth trauma, bleeding, or acute facial swelling, report to Emergency OPD at Dhangadhi Sanjeevani Hospital (Call: 091-590328).','2026-10-16 10:47:32'),(11,'facebook_url','https://facebook.com/amrit.jaishi','2026-10-16 10:47:32'),(12,'twitter_url','https://twitter.com/','2026-10-16 10:47:32'),(13,'linkedin_url','https://linkedin.com/in/amrit-jaishi','2026-10-16 10:47:32'),(14,'youtube_url','https://youtube.com/@amritjaishi','2026-10-16 10:47:32'),(15,'meta_default_description','Official portfolio of Dr. Amrit Jaishi, BDS (BPKIHS). Oral & Dental Surgeon at Dhangadhi Sanjeevani Hospital, Farwest Nepal. Painless root canal, dental crowns, restorative care, and emergency dental surgery.','2026-10-16 10:47:32'),(16,'meta_title','Dr. Amrit Jaishi | Oral & Dental Surgeon (BDS BPKIHS)','2026-10-16 10:47:32'),(17,'site_name','Dr. Amrit Jaishi Dental Care','2026-10-16 10:47:32');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_display_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_type` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `quote` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `testimonial_text` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '1',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1',
  `consent_status` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Ramesh K.',NULL,'Root Canal & Crown',5,'Dr. Amrit Jaishi made my root canal completely painless. I was terrified of dental visits, but his gentle explanation and skilled hands made the entire treatment comfortable. Truly the best dental surgeon in Dhangadhi!',NULL,1,1,1,1,'2026-10-16 10:47:32'),(2,'Sunita B.',NULL,'Emergency Care & Gum Treatment',5,'Visited Dhangadhi Sanjeevani Hospital with severe tooth swelling at night. Dr. Jaishi provided immediate emergency relief and subsequent treatment. Extremely polite, highly qualified, and transparent.',NULL,1,1,1,2,'2026-10-16 10:47:32'),(3,'Dipendra S.',NULL,'Wisdom Tooth Removal & Cleaning',5,'My impacted wisdom tooth extraction was finished in under 20 minutes with zero discomfort. Clean clinic, modern equipment, and reasonable cost. Highly recommend Dr. Amrit Jaishi to everyone in Sudurpashchim.',NULL,1,1,1,3,'2026-10-16 10:47:32'),(4,'Maya T.',NULL,'Pediatric Dental Care & Fillings',5,'Dr. Amrit Jaishi was wonderfully patient and gentle with my 7-year-old son during his tooth filling. The hospital clinic is clean, hygienic, and child-friendly. No pain and no fear!',NULL,1,1,1,4,'2026-10-16 10:47:32'),(5,'Prakash J.',NULL,'Dental Bridge & Restoration',5,'Got my dental bridge done at Dhangadhi Sanjeevani Hospital. The shade match is so natural that nobody can tell it is artificial. Honest guidance and transparent charges.',NULL,1,1,1,5,'2026-10-16 10:47:32'),(6,'Aarav S.',NULL,'Scaling & Stain Removal',5,'Ultrasonic teeth cleaning was very gentle and thorough. Dr. Jaishi explained daily preventive oral care and flossing habits with great care. Truly top-notch service in Farwest Nepal.',NULL,1,1,1,6,'2026-10-16 10:47:32');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_categories`
--

DROP TABLE IF EXISTS `video_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `video_categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_categories`
--

LOCK TABLES `video_categories` WRITE;
/*!40000 ALTER TABLE `video_categories` DISABLE KEYS */;
INSERT INTO `video_categories` VALUES (1,'Oral Health Education','oral-health-education',1,'2026-10-16 17:58:49'),(2,'Dental Treatment Awareness','dental-treatment-awareness',2,'2026-10-16 17:58:49'),(3,'Preventive Dentistry','preventive-dentistry',3,'2026-10-16 17:58:49'),(4,'Clinical Practice & Surgery','clinical-practice',4,'2026-10-16 17:58:49'),(5,'Patient Care & FAQs','patient-faqs',5,'2026-10-16 17:58:49');
/*!40000 ALTER TABLE `video_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_type` enum('upload','youtube','vimeo','external') COLLATE utf8mb4_unicode_ci DEFAULT 'youtube',
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_order` int DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `display_order` (`display_order`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,1,'Proper Tooth Brushing & Flossing Technique for Healthy Gums','proper-tooth-brushing-and-flossing-technique','Dr. Amrit Jaishi demonstrates the modified Bass brushing method and correct flossing habits to prevent plaque accumulation and gingivitis.','youtube','https://www.youtube.com/watch?v=xm9c5HAUBpY',NULL,'img/services/teeth_cleaning.jpg','03:45',1,'published','2026-08-10 10:00:00','2026-10-16 17:58:49','2026-10-16 17:58:49'),(2,2,'What to Expect During a Single-Visit Root Canal Treatment','what-to-expect-during-root-canal-treatment','A step-by-step patient walkthrough explaining modern painless rotary endodontic therapy, rubber dam isolation, and crown restoration.','youtube','https://www.youtube.com/watch?v=PDFHQKVIJpo',NULL,'img/services/rct.jpg','05:20',2,'published','2026-08-25 11:30:00','2026-10-16 17:58:49','2026-10-17 08:43:20'),(3,3,'Understanding Ultrasonic Scaling & Tartar Removal','understanding-ultrasonic-scaling-tartar-removal','Learn why routine professional scaling does not weaken your enamel, and how it protects supportive bone from periodontitis.','youtube','https://www.youtube.com/watch?v=sMoi6laLS8A',NULL,'img/services/gum_care.jpg','04:12',3,'published','2026-09-05 14:15:00','2026-10-16 17:58:49','2026-10-17 08:43:20'),(4,4,'Atraumatic Wisdom Tooth Management & Surgical Care','atraumatic-wisdom-tooth-management','Clinical insights into diagnostic evaluation of impacted third molars, gentle surgical sectioning, and post-operative healing instructions.','youtube','https://www.youtube.com/watch?v=wRpHeXVn_Nw',NULL,'img/services/wisdom_tooth.jpg','06:05',4,'published','2026-09-18 09:45:00','2026-10-16 17:58:49','2026-10-17 08:43:20'),(5,2,'Crown & Bridge Restorations: Ceramic vs. Zirconia','crown-and-bridge-restorations-guide','Dr. Jaishi explains the differences in durability, aesthetics, and biocompatibility when choosing dental crowns after root canal treatment.','youtube','https://www.youtube.com/watch?v=_9_gafu2YtI',NULL,'img/services/dental_crown.jpg','04:30',5,'published','2026-10-02 16:20:00','2026-10-16 17:58:49','2026-10-17 08:43:20'),(6,5,'Emergency Dental Care: What to Do in Acute Toothache or Trauma','emergency-dental-care-acute-toothache-guide','Practical emergency guidelines for knocked-out teeth, cracked enamel, severe swelling, and immediate first-aid before reaching the clinic.','youtube','https://www.youtube.com/watch?v=TeqHgWezHbo',NULL,'img/services/emergency.jpg','03:55',6,'published','2026-10-12 12:00:00','2026-10-16 17:58:49','2026-10-17 08:43:20'),(13,1,'Cavity Prevention: How Sugar & Bacteria Cause Tooth Decay','cavity-prevention-sugar-bacteria-decay','An educational guide on early enamel demineralization, acid attacks from carbohydrates, and how fluoride toothpaste remineralizes teeth.','youtube','https://www.youtube.com/watch?v=xm9c5HAUBpY',NULL,'img/services/teeth_cleaning.jpg','04:15',7,'published','2026-10-13 14:00:00','2026-10-17 06:31:56','2026-10-17 08:43:20'),(14,2,'Dental Implants vs. Bridges: Which Option is Right for You?','dental-implants-vs-bridges-guide','Understanding titanium fixtures, osseointegration, aesthetic crown matching, and long-term bone preservation after tooth loss.','youtube','https://www.youtube.com/watch?v=PDFHQKVIJpo',NULL,'img/services/dental_crown.jpg','05:40',8,'published','2026-10-14 11:30:00','2026-10-17 06:31:56','2026-10-17 08:43:20'),(15,4,'Infection Control & Vacuum Autoclave Sterilization Standards','infection-control-autoclave-sterilization-standards','A transparent look inside our Class-B vacuum autoclave protocols, pouch packaging, and sterile surgical operatory preparation.','youtube','https://www.youtube.com/watch?v=sMoi6laLS8A',NULL,'img/services/gum_care.jpg','03:50',9,'published','2026-10-15 09:15:00','2026-10-17 06:31:56','2026-10-17 08:43:20'),(16,3,'Fluoride Varnish & Pit Fissure Sealants in Pediatric Dental Care','fluoride-varnish-pit-fissure-sealants','Why dental fissure sealants are the most effective preventive shield for permanent molars in growing children and teens.','youtube','https://www.youtube.com/watch?v=wRpHeXVn_Nw',NULL,'img/services/rct.jpg','04:05',10,'published','2026-10-15 16:20:00','2026-10-17 06:31:56','2026-10-17 08:43:20');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-17 12:48:07
