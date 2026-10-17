<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Helpers\Mailer;

class AdminSettingsController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $settings = Setting::all();

        View::render('admin/settings/index', [
            'title' => 'Website Settings & Configuration',
            'settings' => $settings,
            'activeAdmin' => 'settings'
        ], 'layouts/admin');
    }

    public function update(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/settings');
            return;
        }

        $fields = [
            'site_name', 'site_tagline', 'meta_title', 'meta_description',
            'contact_email', 'contact_phone', 'whatsapp_number', 'clinic_address',
            'working_hours', 'emergency_guidance', 'social_facebook', 'social_instagram',
            'social_linkedin', 'social_youtube', 'social_twitter', 'footer_copyright_text',
            'smtp_enabled', 'smtp_host', 'smtp_port', 'smtp_username',
            'smtp_encryption', 'smtp_from_email', 'smtp_from_name', 'notification_email'
        ];

        $updates = [];
        foreach ($fields as $field) {
            $val = $request->post($field);
            if ($field === 'smtp_enabled') {
                $updates[$field] = $val ? '1' : '0';
            } else {
                $updates[$field] = Sanitizer::cleanString($val);
            }
        }

        // Mirror social keys for backwards and template compatibility
        if (isset($updates['social_facebook'])) $updates['facebook_url'] = $updates['social_facebook'];
        if (isset($updates['social_instagram'])) $updates['instagram_url'] = $updates['social_instagram'];
        if (isset($updates['social_linkedin'])) $updates['linkedin_url'] = $updates['social_linkedin'];
        if (isset($updates['social_youtube'])) $updates['youtube_url'] = $updates['social_youtube'];
        if (isset($updates['social_twitter'])) $updates['twitter_url'] = $updates['social_twitter'];

        // Only update SMTP password if a new value is submitted
        $smtpPass = $request->post('smtp_password');
        if (!empty($smtpPass)) {
            $updates['smtp_password'] = trim($smtpPass);
        }

        Setting::setMany($updates);
        Session::flash('success', 'Website settings updated successfully.');
        Response::redirect('/admin/settings');
    }

    public function testimonials(Request $request): void {
        Auth::requireAdmin();

        $testimonials = Testimonial::allAdmin();

        View::render('admin/settings/testimonials', [
            'title' => 'Patient Testimonials & Consent Moderation',
            'testimonials' => $testimonials,
            'activeAdmin' => 'testimonials'
        ], 'layouts/admin');
    }

    public function addTestimonial(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/testimonials');
            return;
        }

        $name = Sanitizer::cleanString($request->post('patient_display_name'));
        $text = Sanitizer::cleanString($request->post('testimonial_text'));

        if (empty($name) || empty($text)) {
            Session::flash('error', 'Patient display name and review text are required.');
            Response::redirect('/admin/testimonials');
            return;
        }

        Testimonial::create([
            'patient_display_name' => $name,
            'treatment_type' => Sanitizer::cleanString($request->post('treatment_type', 'Dental Care')),
            'testimonial_text' => $text,
            'rating' => (int)$request->post('rating', 5),
            'consent_status' => $request->post('consent_status') ? 1 : 0,
            'is_approved' => $request->post('is_approved') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0)
        ]);

        Session::flash('success', 'Testimonial added successfully.');
        Response::redirect('/admin/testimonials');
    }

    public function updateTestimonial(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/testimonials');
            return;
        }

        Testimonial::update($id, [
            'patient_display_name' => Sanitizer::cleanString($request->post('patient_display_name')),
            'treatment_type' => Sanitizer::cleanString($request->post('treatment_type')),
            'testimonial_text' => Sanitizer::cleanString($request->post('testimonial_text')),
            'rating' => (int)$request->post('rating', 5),
            'consent_status' => $request->post('consent_status') ? 1 : 0,
            'is_approved' => $request->post('is_approved') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0)
        ]);

        Session::flash('success', 'Testimonial updated successfully.');
        Response::redirect('/admin/testimonials');
    }

    public function deleteTestimonial(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/testimonials');
            return;
        }

        Testimonial::delete($id);
        Session::flash('success', 'Testimonial removed successfully.');
        Response::redirect('/admin/testimonials');
    }

    public function testSmtp(Request $request): void {
        Auth::requireAdmin();

        $target = Sanitizer::cleanString($request->post('test_email'));
        if (!filter_var($target, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please provide a valid test recipient email address.');
            Response::redirect('/admin/settings');
            return;
        }

        $body = "
            <h3>SMTP Configuration Test</h3>
            <p>This is a test notification from your Dental Doctor Portfolio administration panel.</p>
            <p>If you are reading this email, your SMTP email delivery is operating correctly!</p>
            <p>Timestamp: " . date('Y-m-d H:i:s') . "</p>
        ";

        $res = Mailer::send($target, 'SMTP Test Email - Dental Portfolio', $body);
        if ($res) {
            Session::flash('success', "Test email sent successfully to {$target}.");
        } else {
            Session::flash('error', "Failed to send test email. Please check your SMTP host, port, credentials, and encryption.");
        }

        Response::redirect('/admin/settings');
    }
}
