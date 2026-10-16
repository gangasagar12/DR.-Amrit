<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Gallery;
use App\Helpers\Uploader;

class AdminGalleryController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $gallery = Gallery::allAdmin();

        View::render('admin/gallery/index', [
            'title' => 'Clinic & Work Gallery Management',
            'gallery' => $gallery,
            'activeAdmin' => 'gallery'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/gallery');
            return;
        }

        $caption = Sanitizer::cleanString($request->post('caption'));
        $altText = Sanitizer::cleanString($request->post('alt_text'));
        $category = Sanitizer::cleanString($request->post('category', 'Clinic & Facilities'));
        $order = (int)$request->post('display_order', 0);

        $file = $request->file('image');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Please select an image file to upload.');
            Response::redirect('/admin/gallery');
            return;
        }

        $up = Uploader::uploadImage($file, 'gallery');
        if (!$up['success']) {
            Session::flash('error', $up['message']);
            Response::redirect('/admin/gallery');
            return;
        }

        Gallery::create([
            'image_path' => $up['path'],
            'caption' => $caption,
            'alt_text' => $altText ?: $caption,
            'category' => $category,
            'display_order' => $order,
            'is_active' => 1
        ]);

        Session::flash('success', 'Photograph uploaded and published to gallery.');
        Response::redirect('/admin/gallery');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/gallery');
            return;
        }

        Gallery::delete($id);
        Session::flash('success', 'Gallery photograph removed successfully.');
        Response::redirect('/admin/gallery');
    }
}
