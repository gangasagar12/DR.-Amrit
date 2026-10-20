<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\DoctorProfile;
use App\Models\Service;
use App\Models\Qualification;
use App\Models\Achievement;
use App\Models\Gallery;
use App\Models\Clinic;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Appointment;
use App\Models\Message;
use App\Models\Setting;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Helpers\Mailer;

class PublicController {
    public function home(Request $request): void {
        if (file_exists(ROOT_DIR . '/index.html')) {
            header("Content-Type: text/html; charset=UTF-8");
            readfile(ROOT_DIR . '/index.html');
            return;
        }
    }

    public function about(Request $request): void {
        $doctor = DoctorProfile::get();
        $qualifications = Qualification::all();
        $achievements = Achievement::allActive();

        View::render('public/about', [
            'title' => 'About ' . ($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') . ' | Qualifications & Philosophy',
            'metaDescription' => 'Learn about Dr. Amrit Jaishi\'s dental surgery education at BPKIHS Dharan, qualifications, and patient-first clinical philosophy in Farwest Nepal.',
            'doctor' => $doctor,
            'qualifications' => $qualifications,
            'achievements' => $achievements,
            'activeNav' => 'about'
        ]);
    }

    public function services(Request $request): void {
        $services = Service::allActive();

        View::render('public/services', [
            'title' => 'Dental Specializations & Services | Dr. Amrit Jaishi (BDS BPKIHS)',
            'metaDescription' => 'Explore our specialized dental treatments: painless root canal therapy, crown & bridge, gum care, scaling, and emergency oral surgery.',
            'services' => $services,
            'activeNav' => 'services'
        ]);
    }

    public function serviceDetail(Request $request, array $params): void {
        $slug = $params['slug'] ?? '';
        $service = Service::findBySlug($slug);

        if (!$service) {
            http_response_code(404);
            View::render('public/404', ['title' => 'Service Not Found'], 'layouts/main');
            return;
        }

        $allServices = Service::allActive();
        $clinics = Clinic::allActive();

        View::render('public/service_detail', [
            'title' => $service['meta_title'] ?: ($service['name'] . ' | Dr. Amrit Jaishi'),
            'metaDescription' => $service['meta_description'] ?: $service['short_description'],
            'service' => $service,
            'allServices' => $allServices,
            'clinics' => $clinics,
            'activeNav' => 'services'
        ]);
    }

    public function achievements(Request $request): void {
        $achievements = Achievement::allActive();

        View::render('public/achievements', [
            'title' => 'Certifications, Training & Achievements | Dr. Amrit Jaishi',
            'metaDescription' => 'Verified professional certifications, dental training programs, and clinical achievements.',
            'achievements' => $achievements,
            'activeNav' => 'achievements'
        ]);
    }

    public function gallery(Request $request): void {
        $cat = $request->get('cat');
        $gallery = Gallery::allActive($cat);

        View::render('public/gallery', [
            'title' => 'Clinic & Surgical Gallery | Dhangadhi Sanjeevani Hospital',
            'metaDescription' => 'Take a tour of our modern dental operatories, sterilization protocols, and clinical facilities.',
            'gallery' => $gallery,
            'currentCategory' => $cat ?: 'All',
            'activeNav' => 'gallery'
        ]);
    }

    public function videos(Request $request): void {
        $categories = VideoCategory::allActive();
        $videos = Video::allPublished();

        View::render('public/videos', [
            'title' => 'Dental Video Gallery | Dr. Amrit Jaishi (BDS BPKIHS)',
            'metaDescription' => 'Watch patient education videos, clinical procedure guides, oral hygiene tutorials, and dental awareness videos by Dr. Amrit Jaishi.',
            'categories' => $categories,
            'videos' => $videos,
            'activeNav' => 'videos'
        ]);
    }

    public function clinics(Request $request): void {
        $clinics = Clinic::allActive();
        $services = Service::allActive();

        View::render('public/clinics', [
            'title' => 'Clinic Locations & Consulting Hours | Dr. Amrit Jaishi',
            'metaDescription' => 'View verified clinic locations, consultation hours at Dhangadhi Sanjeevani Hospital, and book your visit.',
            'clinics' => $clinics,
            'services' => $services,
            'activeNav' => 'clinics'
        ]);
    }

    public function contact(Request $request): void {
        $clinics = Clinic::allActive();
        $services = Service::allActive();
        $doctor = DoctorProfile::get();

        View::render('public/contact', [
            'title' => 'Contact & Book an Appointment | Dr. Amrit Jaishi',
            'metaDescription' => 'Schedule your consultation or send an inquiry to Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital.',
            'clinics' => $clinics,
            'services' => $services,
            'doctor' => $doctor,
            'activeNav' => 'contact'
        ]);
    }

    public function bookAppointment(Request $request): void {
        // CSRF Check
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            if ($request->isAjax()) {
                Response::json(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page.'], 403);
            }
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/contact');
            return;
        }

        // Spam honeypot check
        if (!empty($request->post('website_url_hp'))) {
            // Silently drop bot submission
            Response::redirect('/contact');
            return;
        }

        // Validation
        $name = Sanitizer::cleanString($request->post('patient_name'));
        $phone = Sanitizer::cleanString($request->post('phone'));
        $email = filter_var($request->post('email'), FILTER_VALIDATE_EMAIL) ? trim($request->post('email')) : null;
        $serviceId = !empty($request->post('service_id')) ? (int)$request->post('service_id') : null;
        $clinicId = !empty($request->post('clinic_id')) ? (int)$request->post('clinic_id') : null;
        $date = Sanitizer::cleanString($request->post('preferred_date'));
        $time = Sanitizer::cleanString($request->post('preferred_time'));
        $message = Sanitizer::cleanString($request->post('message'));

        $errors = [];
        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Please provide your full name.';
        }
        if (empty($phone) || strlen($phone) < 7) {
            $errors[] = 'Please provide a valid contact phone number.';
        }
        if (empty($date)) {
            $errors[] = 'Please choose your preferred appointment date.';
        }
        if (empty($time)) {
            $errors[] = 'Please select a preferred consultation time.';
        }

        if (!empty($errors)) {
            $msg = implode(' ', $errors);
            if ($request->isAjax()) {
                Response::json(['success' => false, 'message' => $msg], 422);
            }
            Session::flash('error', $msg);
            Response::redirect('/contact');
            return;
        }

        // Store in database
        $appointmentId = Appointment::create([
            'patient_name' => $name,
            'phone' => $phone,
            'email' => $email,
            'service_id' => $serviceId,
            'clinic_id' => $clinicId,
            'preferred_date' => $date,
            'preferred_time' => $time,
            'message' => $message
        ]);

        // Email notification
        $adminNotifyEmail = Setting::get('notification_email', Setting::get('contact_email', ''));
        if (!empty($adminNotifyEmail)) {
            $serviceName = 'General Consultation';
            if ($serviceId) {
                $srv = Service::findById($serviceId);
                if ($srv) $serviceName = $srv['name'];
            }
            $clinicName = 'Apex Dental Excellence';
            if ($clinicId) {
                $cln = Clinic::findById($clinicId);
                if ($cln) $clinicName = $cln['clinic_name'];
            }

            $emailBody = "
                <h2>New Appointment Request Received</h2>
                <p><strong>Patient Name:</strong> {$name}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Email:</strong> " . ($email ?: 'Not provided') . "</p>
                <p><strong>Service:</strong> {$serviceName}</p>
                <p><strong>Clinic Location:</strong> {$clinicName}</p>
                <p><strong>Preferred Date:</strong> {$date}</p>
                <p><strong>Preferred Time:</strong> {$time}</p>
                <p><strong>Patient Note:</strong><br>" . nl2br($message ?: 'None') . "</p>
                <hr>
                <p><em>Initial Status: Pending. Please log into the Admin Panel to review and confirm with the patient.</em></p>
            ";

            Mailer::send($adminNotifyEmail, "New Appointment Request: {$name} ({$date})", $emailBody);
        }

        $successMsg = 'Thank you! Your appointment request has been submitted with Pending status. Our dental clinic team will contact you shortly to confirm your schedule.';

        if ($request->isAjax()) {
            Response::json(['success' => true, 'message' => $successMsg]);
        }

        Session::flash('success', $successMsg);
        Response::redirect('/contact');
    }

    public function submitContact(Request $request): void {
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid. Please try again.');
            Response::redirect('/contact');
            return;
        }

        // Honeypot check
        if (!empty($request->post('middle_name_hp'))) {
            Response::redirect('/contact');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        $email = filter_var($request->post('email'), FILTER_VALIDATE_EMAIL) ? trim($request->post('email')) : null;
        $phone = Sanitizer::cleanString($request->post('phone'));
        $subject = Sanitizer::cleanString($request->post('subject'));
        $message = Sanitizer::cleanString($request->post('message'));

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            Session::flash('error', 'Please fill in all required fields with a valid email address.');
            Response::redirect('/contact');
            return;
        }

        Message::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        ]);

        // Email notification
        $adminNotifyEmail = Setting::get('notification_email', Setting::get('contact_email', ''));
        if (!empty($adminNotifyEmail)) {
            $body = "
                <h2>New Website Inquiry</h2>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
            ";
            Mailer::send($adminNotifyEmail, "New Contact Inquiry: {$subject}", $body);
        }

        Session::flash('success', 'Your message has been sent successfully. Dr. Jenkins and the clinic team will respond promptly.');
        Response::redirect('/contact');
    }

    public function sitemap(Request $request): void {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $baseUrl = $config['base_url'];

        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static routes
        $static = [
            '/' => '1.0',
            '/about' => '0.8',
            '/services' => '0.9',
            '/achievements' => '0.7',
            '/gallery' => '0.7',
            '/clinics' => '0.8',
            '/blog' => '0.9',
            '/contact' => '0.8'
        ];

        foreach ($static as $path => $priority) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . $path) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>{$priority}</priority>\n";
            echo "  </url>\n";
        }

        // Active services
        $services = Service::allActive();
        foreach ($services as $srv) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . '/services/' . $srv['slug']) . "</loc>\n";
            echo "    <lastmod>" . date('Y-m-d', strtotime($srv['updated_at'])) . "</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        // Published blogs
        $blogs = Blog::allPublished(1, 100);
        foreach ($blogs['items'] as $b) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($baseUrl . '/blog/' . $b['slug']) . "</loc>\n";
            echo "    <lastmod>" . date('Y-m-d', strtotime($b['updated_at'] ?: $b['published_at'])) . "</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }

    public function apiPortfolioData(Request $request): void {
        $doctor = [];
        $clinics = [];
        $services = [];
        $qualifications = [];
        $gallery = [];
        $blogs = [];
        $researches = [];
        $galleryCategories = [];
        $videos = [];
        $videoCategories = [];
        $settings = [];

        try { $doctor = DoctorProfile::get(); } catch (\Throwable $e) {}
        try { $clinics = Clinic::allActive(); } catch (\Throwable $e) {}
        try { $services = Service::allActive(); } catch (\Throwable $e) {}
        try {
            $qualifications = Qualification::allActive();
            if (empty($qualifications)) {
                $qualifications = Qualification::all();
            }
        } catch (\Throwable $e) {}
        try { $gallery = Gallery::allActive(); } catch (\Throwable $e) {}
        try { $blogs = Blog::latest(24); } catch (\Throwable $e) {}
        try { $researches = \App\Models\Research::allPublished(); } catch (\Throwable $e) {}
        try {
            $pdo = \App\Core\Database::getConnection();
            $galleryCategories = $pdo->query("SELECT * FROM gallery_categories ORDER BY display_order ASC, id ASC")->fetchAll();
        } catch (\Throwable $e) {}
        try { $videos = \App\Models\Video::allPublished(); } catch (\Throwable $e) {}
        try { $videoCategories = \App\Models\VideoCategory::all(); } catch (\Throwable $e) {}
        try { $settings = Setting::all(); } catch (\Throwable $e) {}

        Response::json([
            'success' => true,
            'doctor' => $doctor,
            'clinics' => $clinics,
            'services' => $services,
            'qualifications' => $qualifications,
            'gallery' => $gallery,
            'gallery_categories' => $galleryCategories,
            'blogs' => $blogs,
            'researches' => $researches,
            'videos' => $videos,
            'video_categories' => $videoCategories,
            'settings' => $settings,
            'contact' => [
                'email' => $doctor['contact_email'] ?? ($settings['contact_email'] ?? 'amritjaishi0@gmail.com'),
                'phone' => $doctor['phone'] ?? ($settings['contact_phone'] ?? '+977-9860644282'),
                'whatsapp' => $doctor['whatsapp'] ?? ($settings['whatsapp_number'] ?? '+9779860644282'),
                'emergency_contact' => $doctor['emergency_contact'] ?? ($settings['emergency_guidance'] ?? ($settings['hospital_phone'] ?? '091-590328 / 091-590348 (Hospital Lines)')),
                'address' => $settings['clinic_address'] ?? ($clinics[0]['full_address'] ?? 'Dhangadhi Sanjeevani Hospital, Hospital Road, Dhangadhi, Kailali, Nepal'),
                'working_hours' => $settings['working_hours'] ?? ($clinics[0]['working_days'] ? ($clinics[0]['working_days'] . ': ' . $clinics[0]['working_hours']) : 'Sun - Fri: 10:00 AM - 5:00 PM')
            ]
        ]);
    }
}
