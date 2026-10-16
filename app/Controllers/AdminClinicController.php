<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Clinic;
use App\Helpers\Uploader;

class AdminClinicController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $clinics = Clinic::allAdmin();

        View::render('admin/clinics/index', [
            'title' => 'Clinic Affiliations & Locations Management',
            'clinics' => $clinics,
            'activeAdmin' => 'clinics'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        View::render('admin/clinics/create_edit', [
            'title' => 'Add New Clinic Affiliation',
            'clinic' => null,
            'activeAdmin' => 'clinics'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/clinics/create');
            return;
        }

        $name = Sanitizer::cleanString($request->post('clinic_name'));
        $address = Sanitizer::cleanString($request->post('full_address'));

        if (empty($name) || empty($address)) {
            Session::flash('error', 'Clinic name and full address are required.');
            Response::redirect('/admin/clinics/create');
            return;
        }

        $imagePath = null;
        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'clinics');
            if ($up['success']) {
                $imagePath = $up['path'];
            }
        }

        // Process schedule inputs
        $schedules = [];
        $days = $request->post('visiting_day') ?? [];
        $starts = $request->post('start_time') ?? [];
        $ends = $request->post('end_time') ?? [];
        $notes = $request->post('notes') ?? [];

        if (is_array($days)) {
            for ($i = 0; $i < count($days); $i++) {
                if (!empty($days[$i])) {
                    $schedules[] = [
                        'visiting_day' => Sanitizer::cleanString($days[$i]),
                        'start_time' => Sanitizer::cleanString($starts[$i] ?? ''),
                        'end_time' => Sanitizer::cleanString($ends[$i] ?? ''),
                        'notes' => Sanitizer::cleanString($notes[$i] ?? '')
                    ];
                }
            }
        }

        $id = Clinic::create([
            'clinic_name' => $name,
            'doctor_role' => Sanitizer::cleanString($request->post('doctor_role', 'Senior Dental Surgeon')),
            'workplace_type' => Sanitizer::cleanString($request->post('workplace_type', 'Permanent Workplace')),
            'full_address' => $address,
            'phone' => Sanitizer::cleanString($request->post('phone')),
            'website_url' => Sanitizer::cleanString($request->post('website_url')),
            'map_url' => Sanitizer::cleanString($request->post('map_url')),
            'image' => $imagePath,
            'description' => Sanitizer::cleanString($request->post('description')),
            'is_active' => $request->post('is_active') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0)
        ], $schedules);

        Session::flash('success', 'Clinic affiliation and visiting schedules saved.');
        Response::redirect('/admin/clinics');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $clinic = Clinic::findById($id);

        if (!$clinic) {
            Session::flash('error', 'Clinic not found.');
            Response::redirect('/admin/clinics');
            return;
        }

        View::render('admin/clinics/create_edit', [
            'title' => 'Edit Clinic Affiliation: ' . $clinic['clinic_name'],
            'clinic' => $clinic,
            'activeAdmin' => 'clinics'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/clinics/edit?id=' . $id);
            return;
        }

        $data = [
            'clinic_name' => Sanitizer::cleanString($request->post('clinic_name')),
            'slug' => Sanitizer::cleanString($request->post('slug')),
            'doctor_role' => Sanitizer::cleanString($request->post('doctor_role')),
            'workplace_type' => Sanitizer::cleanString($request->post('workplace_type')),
            'full_address' => Sanitizer::cleanString($request->post('full_address')),
            'phone' => Sanitizer::cleanString($request->post('phone')),
            'website_url' => Sanitizer::cleanString($request->post('website_url')),
            'map_url' => Sanitizer::cleanString($request->post('map_url')),
            'description' => Sanitizer::cleanString($request->post('description')),
            'is_active' => $request->post('is_active') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0)
        ];

        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'clinics');
            if ($up['success']) {
                $data['image'] = $up['path'];
            }
        }

        $schedules = [];
        $days = $request->post('visiting_day') ?? [];
        $starts = $request->post('start_time') ?? [];
        $ends = $request->post('end_time') ?? [];
        $notes = $request->post('notes') ?? [];

        if (is_array($days)) {
            for ($i = 0; $i < count($days); $i++) {
                if (!empty($days[$i])) {
                    $schedules[] = [
                        'visiting_day' => Sanitizer::cleanString($days[$i]),
                        'start_time' => Sanitizer::cleanString($starts[$i] ?? ''),
                        'end_time' => Sanitizer::cleanString($ends[$i] ?? ''),
                        'notes' => Sanitizer::cleanString($notes[$i] ?? '')
                    ];
                }
            }
        }

        Clinic::update($id, $data, $schedules);
        Session::flash('success', 'Clinic affiliation and consulting hours updated.');
        Response::redirect('/admin/clinics');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/clinics');
            return;
        }

        Clinic::delete($id);
        Session::flash('success', 'Clinic affiliation removed successfully.');
        Response::redirect('/admin/clinics');
    }
}
