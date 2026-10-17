<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\DoctorProfile;
use App\Models\Qualification;
use App\Helpers\Uploader;

class AdminProfileController {
    public function edit(Request $request): void {
        Auth::requireAdmin();

        $doctor = DoctorProfile::get();
        $qualifications = Qualification::all();

        View::render('admin/profile', [
            'title' => 'Doctor Profile & Credentials Management',
            'doctor' => $doctor,
            'qualifications' => $qualifications,
            'activeAdmin' => 'profile'
        ], 'layouts/admin');
    }

    public function update(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/profile');
            return;
        }

        $data = [
            'doctor_name' => Sanitizer::cleanString($request->post('doctor_name')),
            'professional_title' => Sanitizer::cleanString($request->post('professional_title')),
            'hero_heading' => Sanitizer::cleanString($request->post('hero_heading')),
            'hero_lead' => Sanitizer::cleanString($request->post('hero_lead')),
            'hero_approach' => Sanitizer::cleanString($request->post('hero_approach')),
            'hero_btn1_text' => Sanitizer::cleanString($request->post('hero_btn1_text')),
            'hero_btn1_link' => Sanitizer::cleanString($request->post('hero_btn1_link')),
            'hero_btn2_text' => Sanitizer::cleanString($request->post('hero_btn2_text')),
            'hero_btn2_link' => Sanitizer::cleanString($request->post('hero_btn2_link')),
            'about_kicker' => Sanitizer::cleanString($request->post('about_kicker')),
            'about_subheading' => Sanitizer::cleanString($request->post('about_subheading')),
            'about_badge_title' => Sanitizer::cleanString($request->post('about_badge_title')),
            'about_badge_subtitle' => Sanitizer::cleanString($request->post('about_badge_subtitle')),
            'tagline' => Sanitizer::cleanString($request->post('tagline')),
            'biography' => Sanitizer::cleanString($request->post('biography')),
            'experience_years' => (int)$request->post('experience_years'),
            'experience_description' => Sanitizer::cleanString($request->post('experience_description')),
            'philosophy' => Sanitizer::cleanString($request->post('philosophy')),
            'languages' => Sanitizer::cleanString($request->post('languages')),
            'contact_email' => Sanitizer::cleanString($request->post('contact_email')),
            'phone' => Sanitizer::cleanString($request->post('phone')),
            'whatsapp' => Sanitizer::cleanString($request->post('whatsapp')),
            'emergency_contact' => Sanitizer::cleanString($request->post('emergency_contact'))
        ];

        // Handle profile image upload
        $file = $request->file('profile_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $uploadRes = Uploader::uploadImage($file, 'profile');
            if ($uploadRes['success']) {
                $data['profile_image'] = $uploadRes['path'];
            } else {
                Session::flash('error', $uploadRes['message']);
                Response::redirect('/admin/profile');
                return;
            }
        }

        // Handle hero cutout image upload
        $heroFile = $request->file('hero_image');
        if ($heroFile && $heroFile['error'] === UPLOAD_ERR_OK) {
            $uploadHero = Uploader::uploadImage($heroFile, 'profile');
            if ($uploadHero['success']) {
                $data['hero_image'] = $uploadHero['path'];
            } else {
                Session::flash('error', $uploadHero['message']);
                Response::redirect('/admin/profile');
                return;
            }
        }

        // Handle about clinic image upload
        $aboutFile = $request->file('about_image');
        if ($aboutFile && $aboutFile['error'] === UPLOAD_ERR_OK) {
            $uploadAbout = Uploader::uploadImage($aboutFile, 'profile');
            if ($uploadAbout['success']) {
                $data['about_image'] = $uploadAbout['path'];
            } else {
                Session::flash('error', $uploadAbout['message']);
                Response::redirect('/admin/profile');
                return;
            }
        }

        DoctorProfile::update($data);
        Session::flash('success', 'Doctor profile, Hero section, and About section updated successfully.');
        Response::redirect('/admin/profile');
    }

    public function addQualification(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/profile');
            return;
        }

        $degree = Sanitizer::cleanString($request->post('degree'));
        $institution = Sanitizer::cleanString($request->post('institution'));
        $year = Sanitizer::cleanString($request->post('completion_year'));
        $desc = Sanitizer::cleanString($request->post('description'));
        $order = (int)$request->post('display_order', 0);

        if (empty($degree) || empty($institution) || empty($year)) {
            Session::flash('error', 'Degree, Institution, and Year are required.');
            Response::redirect('/admin/profile');
            return;
        }

        Qualification::create([
            'degree' => $degree,
            'institution' => $institution,
            'completion_year' => $year,
            'description' => $desc,
            'display_order' => $order
        ]);

        Session::flash('success', 'Qualification added successfully.');
        Response::redirect('/admin/profile');
    }

    public function updateQualification(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/profile');
            return;
        }

        $degree = Sanitizer::cleanString($request->post('degree'));
        $institution = Sanitizer::cleanString($request->post('institution'));
        $year = Sanitizer::cleanString($request->post('completion_year'));
        $desc = Sanitizer::cleanString($request->post('description'));
        $order = (int)$request->post('display_order', 0);

        Qualification::update($id, [
            'degree' => $degree,
            'institution' => $institution,
            'completion_year' => $year,
            'description' => $desc,
            'display_order' => $order
        ]);

        Session::flash('success', 'Qualification updated successfully.');
        Response::redirect('/admin/profile');
    }

    public function deleteQualification(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/profile');
            return;
        }

        Qualification::delete($id);
        Session::flash('success', 'Qualification removed successfully.');
        Response::redirect('/admin/profile');
    }
}
