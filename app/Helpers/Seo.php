<?php
namespace App\Helpers;

use App\Models\Setting;
use App\Models\DoctorProfile;

class Seo {
    public static function getDoctorSchema(): string {
        $doctor = DoctorProfile::get();
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $baseUrl = $config['base_url'];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Dentist',
            'name' => $doctor['doctor_name'] ?? 'Dr. Amrit Jaishi',
            'description' => $doctor['tagline'] ?? 'PERSONALISED SPECIALIST CARE FOR YOUR SMILE',
            'url' => $baseUrl,
            'telephone' => $doctor['phone'] ?? '+977-9860644282',
            'email' => $doctor['contact_email'] ?? 'amritjaishi0@gmail.com',
            'priceRange' => '$$',
            'openingHours' => Setting::get('working_hours', 'Sun-Fri 10:00-17:00'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Setting::get('clinic_address', 'Dhangadhi Sanjeevani Hospital, Hospital Road'),
                'addressLocality' => 'Dhangadhi',
                'addressRegion' => 'Kailali',
                'postalCode' => '10900',
                'addressCountry' => 'NP'
            ],
            'founder' => [
                '@type' => 'Person',
                'name' => $doctor['doctor_name'] ?? 'Dr. Amrit Jaishi',
                'jobTitle' => $doctor['professional_title'] ?? 'Oral & Dental Surgeon (BDS BPKIHS)',
                'worksFor' => [
                    '@type' => 'Hospital',
                    'name' => 'Dhangadhi Sanjeevani Hospital'
                ]
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    public static function getArticleSchema(array $blog): string {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $baseUrl = $config['base_url'];
        $url = $baseUrl . '/blog/' . ($blog['slug'] ?? '');
        $imageUrl = !empty($blog['featured_image']) ? ($baseUrl . '/' . $blog['featured_image']) : ($baseUrl . '/assets/images/placeholder.jpg');

        $isResearch = ($blog['content_type'] ?? '') === 'Research Publication';
        $type = $isResearch ? 'ScholarlyArticle' : 'MedicalWebPage';

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $type,
            'headline' => $blog['title'] ?? '',
            'description' => $blog['excerpt'] ?? '',
            'image' => $imageUrl,
            'url' => $url,
            'datePublished' => $blog['published_at'] ?? $blog['created_at'],
            'dateModified' => $blog['updated_at'] ?? $blog['published_at'],
            'author' => [
                '@type' => 'Person',
                'name' => $blog['author_name'] ?? 'Dr. Amrit Jaishi'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => Setting::get('site_name', 'Dr. Amrit Jaishi - Oral & Dental Surgeon')
            ]
        ];

        if ($isResearch && !empty($blog['doi'])) {
            $schema['identifier'] = $blog['doi'];
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    public static function getBreadcrumbSchema(array $items): string {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $baseUrl = $config['base_url'];

        $elements = [];
        $pos = 1;
        foreach ($items as $name => $path) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $pos++,
                'name' => $name,
                'item' => str_starts_with($path, 'http') ? $path : ($baseUrl . '/' . ltrim($path, '/'))
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}
