<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Category;

class AdminCategoryController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $categories = Category::all();

        View::render('admin/categories/index', [
            'title' => 'Blog Categories Management',
            'categories' => $categories,
            'activeAdmin' => 'categories'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/categories');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        $desc = Sanitizer::cleanString($request->post('description'));

        if (empty($name)) {
            Session::flash('error', 'Category name is required.');
            Response::redirect('/admin/categories');
            return;
        }

        Category::create($name, $desc);
        Session::flash('success', 'Category created successfully.');
        Response::redirect('/admin/categories');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/categories');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        $desc = Sanitizer::cleanString($request->post('description'));

        Category::update($id, $name, $desc);
        Session::flash('success', 'Category updated successfully.');
        Response::redirect('/admin/categories');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/categories');
            return;
        }

        $res = Category::delete($id);
        if ($res['success']) {
            Session::flash('success', $res['message']);
        } else {
            Session::flash('error', $res['message']);
        }

        Response::redirect('/admin/categories');
    }
}
