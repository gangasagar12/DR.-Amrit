<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Qualification;

class AdminQualificationController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $qualifications = Qualification::all();

        View::render('admin/qualifications/index', [
            'title' => 'Education & Qualifications Management',
            'qualifications' => $qualifications,
            'activeAdmin' => 'qualifications'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        View::render('admin/qualifications/create_edit', [
            'title' => 'Add New Academic Qualification',
            'item' => null,
            'activeAdmin' => 'qualifications'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/qualifications/create');
            return;
        }

        $degree = Sanitizer::cleanString($request->post('degree'));
        $institution = Sanitizer::cleanString($request->post('institution'));
        $completionYear = Sanitizer::cleanString($request->post('completion_year'));
        $startYear = Sanitizer::cleanString($request->post('start_year'));
        $location = Sanitizer::cleanString($request->post('location'));
        $description = Sanitizer::cleanString($request->post('description'));
        $displayOrder = (int)$request->post('display_order', 0);
        $isPublished = (int)$request->post('is_published', 1);

        if (empty($degree) || empty($institution) || empty($completionYear)) {
            Session::flash('error', 'Degree, Institution, and Completion Year are required.');
            Response::redirect('/admin/qualifications/create');
            return;
        }

        Qualification::create([
            'degree' => $degree,
            'institution' => $institution,
            'location' => $location,
            'start_year' => $startYear,
            'completion_year' => $completionYear,
            'description' => $description,
            'display_order' => $displayOrder,
            'is_published' => $isPublished
        ]);

        Session::flash('success', 'Educational qualification added successfully.');
        Response::redirect('/admin/qualifications');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        $item = Qualification::findById($id);

        if (!$item) {
            Session::flash('error', 'Qualification record not found.');
            Response::redirect('/admin/qualifications');
            return;
        }

        View::render('admin/qualifications/create_edit', [
            'title' => 'Edit Qualification: ' . $item['degree'],
            'item' => $item,
            'activeAdmin' => 'qualifications'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        $item = Qualification::findById($id);

        if (!$item) {
            Session::flash('error', 'Qualification record not found.');
            Response::redirect('/admin/qualifications');
            return;
        }

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/qualifications/' . $id . '/edit');
            return;
        }

        $degree = Sanitizer::cleanString($request->post('degree'));
        $institution = Sanitizer::cleanString($request->post('institution'));
        $completionYear = Sanitizer::cleanString($request->post('completion_year'));
        $startYear = Sanitizer::cleanString($request->post('start_year'));
        $location = Sanitizer::cleanString($request->post('location'));
        $description = Sanitizer::cleanString($request->post('description'));
        $displayOrder = (int)$request->post('display_order', 0);
        $isPublished = (int)$request->post('is_published', 1);

        if (empty($degree) || empty($institution) || empty($completionYear)) {
            Session::flash('error', 'Degree, Institution, and Completion Year are required.');
            Response::redirect('/admin/qualifications/' . $id . '/edit');
            return;
        }

        Qualification::update($id, [
            'degree' => $degree,
            'institution' => $institution,
            'location' => $location,
            'start_year' => $startYear,
            'completion_year' => $completionYear,
            'description' => $description,
            'display_order' => $displayOrder,
            'is_published' => $isPublished
        ]);

        Session::flash('success', 'Qualification updated successfully.');
        Response::redirect('/admin/qualifications');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? 0);
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/qualifications');
            return;
        }

        Qualification::delete($id);
        Session::flash('success', 'Qualification record deleted successfully.');
        Response::redirect('/admin/qualifications');
    }
}
