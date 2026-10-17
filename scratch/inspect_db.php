<?php
require __DIR__ . '/../app/Core/Database.php';

$pdo = \App\Core\Database::getConnection();
$profile = $pdo->query('SELECT * FROM doctor_profile LIMIT 1')->fetch(PDO::FETCH_ASSOC);
echo "=== DOCTOR PROFILE ===\n";
print_r($profile);

$researches = $pdo->query('SELECT id, title, publication_year, journal_name, status FROM researches')->fetchAll(PDO::FETCH_ASSOC);
echo "\n=== RESEARCHES ===\n";
print_r($researches);
