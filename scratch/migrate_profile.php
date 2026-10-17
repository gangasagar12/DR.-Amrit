<?php
require __DIR__ . '/../app/Core/Database.php';

$pdo = \App\Core\Database::getConnection();

$cols = $pdo->query("DESCRIBE doctor_profile")->fetchAll(PDO::FETCH_COLUMN);

$queries = [];
if (!in_array('hero_lead', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN hero_lead TEXT NULL AFTER hero_subheading";
}
if (!in_array('hero_approach', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN hero_approach TEXT NULL AFTER hero_lead";
}
if (!in_array('about_kicker', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN about_kicker VARCHAR(150) NULL AFTER hero_approach";
}
if (!in_array('about_subheading', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN about_subheading VARCHAR(255) NULL AFTER about_kicker";
}
if (!in_array('about_badge_title', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN about_badge_title VARCHAR(100) NULL AFTER about_subheading";
}
if (!in_array('about_badge_subtitle', $cols)) {
    $queries[] = "ALTER TABLE doctor_profile ADD COLUMN about_badge_subtitle VARCHAR(100) NULL AFTER about_badge_title";
}

foreach ($queries as $q) {
    echo "Running: $q\n";
    $pdo->exec($q);
}

// Populate default values from index.html into doctor_profile
$stmt = $pdo->prepare("UPDATE doctor_profile SET 
    hero_image = 'img/doctor-cutout.png',
    about_image = 'img/doctor-clinic.jpg'
    WHERE id = 1");
$stmt->execute();

echo "Doctor profile migration completed successfully!\n";
