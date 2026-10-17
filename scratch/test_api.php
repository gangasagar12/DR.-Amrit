<?php
$json = file_get_contents('http://localhost:8000/api/portfolio-data');
$data = json_decode($json, true);

echo "SUCCESS: " . ($data['success'] ? 'YES' : 'NO') . PHP_EOL;
echo "Doctor: " . $data['doctor']['doctor_name'] . ' | ' . $data['doctor']['professional_title'] . PHP_EOL;
echo "Hero Lead: " . substr($data['doctor']['hero_lead'], 0, 60) . '...' . PHP_EOL;
echo "Hero Img: " . $data['doctor']['hero_image'] . PHP_EOL;
echo "About Img: " . $data['doctor']['about_image'] . PHP_EOL;
echo "Researches count: " . count($data['researches']) . PHP_EOL;
echo "Services count: " . count($data['services']) . PHP_EOL;
echo "Clinics count: " . count($data['clinics']) . PHP_EOL;
echo "Blogs count: " . count($data['blogs']) . PHP_EOL;
echo "Videos count: " . count($data['videos']) . PHP_EOL;
echo "Contact Email: " . $data['contact']['email'] . PHP_EOL;
