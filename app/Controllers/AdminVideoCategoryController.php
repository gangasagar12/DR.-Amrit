<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\VideoCategory;

class AdminVideoCategoryController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $categories = VideoCategory::all();

        View::render('admin/videos/categories', [
            'title' => 'Video Categories Management',
            'categories' => $categories,
            'activeAdmin' => 'videos'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/video-categories');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        $displayOrder = (int)$request->post('display_order', 0);

        if (empty($name)) {
            Session::flash('error', 'Category name is required.');
            Response::redirect('/admin/video-categories');
            return;
        }

        $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $slug = $slugBase;
        $counter = 1;
        while (VideoCategory::findBySlug($slug)) {
            $slug = $slugBase . '-' . $counter++;
        }

        VideoCategory::create([
            'name' => $name,
            'slug' => $slug,
            'display_order' => $displayOrder
        ]);

        Session::flash('success', 'Video category added successfully.');
        Response::redirect('/admin/video-categories');
    }

    public function update(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/video-categories');
            return;
        }

        $id = (int)$request->post('id');
        $name = Sanitizer::cleanString($request->post('name'));
        $displayOrder = (int)$request->post('display_order', 0);

        if (empty($name) || !$id) {
            Session::flash('error', 'Invalid category data.');
            Response::redirect('/admin/video-categories');
            return;
        }

        $existing = VideoCategory::findById($id);
        if (!$existing) {
            Session::flash('error', 'Category not found.');
            Response::redirect('/admin/video-categories');
            return;
        }

        $slug = $existing['slug'];
        if ($name !== $existing['name']) {
            $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
            $slug = $slugBase;
            $counter = 1;
            while ($c = VideoCategory::findBySlug($slug)) {
                if ($c['id'] == $id) break;
                $slug = $slugBase . '-' . $counter++;
            }
        }

        VideoCategory::update($id, [
            'name' => $name,
            'slug' => $slug,
            'display_order' => $displayOrder
        ]);

        Session::flash('success', 'Video category updated successfully.');
        Response::redirect('/admin/video-categories');
    }

    public function delete(Request $request): void {
        Auth::requireAdmin();

        $id = (int)$request->query('id', $request->post('id', 0));
        $token = $request->query('_csrf_token', $request->post('_csrf_token'));

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/video-categories');
            return;
        }

        VideoCategory::delete($id);
        Session::flash('success', 'Video category deleted.');
        Response::redirect('/admin/video-categories');
    }
}
