<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Achievement;
use App\Helpers\Uploader;

class AdminAchievementController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $achievements = Achievement::allAdmin();

        View::render('admin/achievements/index', [
            'title' => 'Training, Certifications & Achievements Management',
            'achievements' => $achievements,
            'activeAdmin' => 'achievements'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        View::render('admin/achievements/create_edit', [
            'title' => 'Add New Certification / Achievement',
            'item' => null,
            'activeAdmin' => 'achievements'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/achievements/create');
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $org = Sanitizer::cleanString($request->post('organization'));
        $date = Sanitizer::cleanString($request->post('achievement_date'));

        if (empty($title) || empty($org) || empty($date)) {
            Session::flash('error', 'Title, Organization, and Date are required.');
            Response::redirect('/admin/achievements/create');
            return;
        }

        $certImage = null;
        $file = $request->file('certificate_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'certificates');
            if ($up['success']) {
                $certImage = $up['path'];
            }
        }

        Achievement::create([
            'title' => $title,
            'record_type' => Sanitizer::cleanString($request->post('record_type', 'Certification')),
            'organization' => $org,
            'achievement_date' => $date,
            'description' => Sanitizer::cleanString($request->post('description')),
            'certificate_image' => $certImage,
            'verification_url' => Sanitizer::cleanString($request->post('verification_url')),
            'display_order' => (int)$request->post('display_order', 0),
            'is_active' => $request->post('is_active') ? 1 : 0
        ]);

        Session::flash('success', 'Record added successfully.');
        Response::redirect('/admin/achievements');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $item = Achievement::findById($id);

        if (!$item) {
            Session::flash('error', 'Record not found.');
            Response::redirect('/admin/achievements');
            return;
        }

        View::render('admin/achievements/create_edit', [
            'title' => 'Edit Record: ' . $item['title'],
            'item' => $item,
            'activeAdmin' => 'achievements'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/achievements/edit?id=' . $id);
            return;
        }

        $data = [
            'title' => Sanitizer::cleanString($request->post('title')),
            'record_type' => Sanitizer::cleanString($request->post('record_type')),
            'organization' => Sanitizer::cleanString($request->post('organization')),
            'achievement_date' => Sanitizer::cleanString($request->post('achievement_date')),
            'description' => Sanitizer::cleanString($request->post('description')),
            'verification_url' => Sanitizer::cleanString($request->post('verification_url')),
            'display_order' => (int)$request->post('display_order', 0),
            'is_active' => $request->post('is_active') ? 1 : 0
        ];

        $file = $request->file('certificate_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'certificates');
            if ($up['success']) {
                $data['certificate_image'] = $up['path'];
            }
        }

        Achievement::update($id, $data);
        Session::flash('success', 'Record updated successfully.');
        Response::redirect('/admin/achievements');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/achievements');
            return;
        }

        Achievement::delete($id);
        Session::flash('success', 'Record deleted successfully.');
        Response::redirect('/admin/achievements');
    }
}
