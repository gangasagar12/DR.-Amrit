<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Service;
use App\Helpers\Uploader;

class AdminServiceController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $services = Service::allAdmin();

        View::render('admin/services/index', [
            'title' => 'Dental Services & Specialties Management',
            'services' => $services,
            'activeAdmin' => 'services'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        View::render('admin/services/create_edit', [
            'title' => 'Add New Dental Service',
            'service' => null,
            'activeAdmin' => 'services'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/services/create');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        if (empty($name)) {
            Session::flash('error', 'Service name is required.');
            Response::redirect('/admin/services/create');
            return;
        }

        $imagePath = null;
        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'services');
            if ($up['success']) {
                $imagePath = $up['path'];
            } else {
                Session::flash('error', $up['message']);
                Response::redirect('/admin/services/create');
                return;
            }
        }

        $id = Service::create([
            'name' => $name,
            'category' => Sanitizer::cleanString($request->post('category')),
            'short_description' => Sanitizer::cleanString($request->post('short_description')),
            'full_description' => Sanitizer::cleanHtml($request->post('full_description')),
            'benefits' => Sanitizer::cleanString($request->post('benefits')),
            'procedure_steps' => Sanitizer::cleanString($request->post('procedure_steps')),
            'image' => $imagePath,
            'icon' => Sanitizer::cleanString($request->post('icon', 'activity')),
            'is_active' => $request->post('is_active') ? 1 : 0,
            'is_featured' => $request->post('is_featured') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0),
            'meta_title' => Sanitizer::cleanString($request->post('meta_title')),
            'meta_description' => Sanitizer::cleanString($request->post('meta_description'))
        ]);

        Session::flash('success', 'Dental service created successfully.');
        Response::redirect('/admin/services');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $service = Service::findById($id);

        if (!$service) {
            Session::flash('error', 'Service not found.');
            Response::redirect('/admin/services');
            return;
        }

        View::render('admin/services/create_edit', [
            'title' => 'Edit Dental Service: ' . $service['name'],
            'service' => $service,
            'activeAdmin' => 'services'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/services/edit?id=' . $id);
            return;
        }

        $data = [
            'name' => Sanitizer::cleanString($request->post('name')),
            'slug' => Sanitizer::cleanString($request->post('slug')),
            'category' => Sanitizer::cleanString($request->post('category')),
            'short_description' => Sanitizer::cleanString($request->post('short_description')),
            'full_description' => Sanitizer::cleanHtml($request->post('full_description')),
            'benefits' => Sanitizer::cleanString($request->post('benefits')),
            'procedure_steps' => Sanitizer::cleanString($request->post('procedure_steps')),
            'icon' => Sanitizer::cleanString($request->post('icon', 'activity')),
            'is_active' => $request->post('is_active') ? 1 : 0,
            'is_featured' => $request->post('is_featured') ? 1 : 0,
            'display_order' => (int)$request->post('display_order', 0),
            'meta_title' => Sanitizer::cleanString($request->post('meta_title')),
            'meta_description' => Sanitizer::cleanString($request->post('meta_description'))
        ];

        $file = $request->file('image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'services');
            if ($up['success']) {
                $data['image'] = $up['path'];
            }
        }

        Service::update($id, $data);
        Session::flash('success', 'Dental service updated successfully.');
        Response::redirect('/admin/services');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/services');
            return;
        }

        Service::delete($id);
        Session::flash('success', 'Service deleted successfully.');
        Response::redirect('/admin/services');
    }
}
