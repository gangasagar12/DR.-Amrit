<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Helpers\Uploader;

class AdminVideoController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $videos = Video::allAdmin();
        $categories = VideoCategory::all();

        View::render('admin/videos/index', [
            'title' => 'Video Gallery Management',
            'videos' => $videos,
            'categories' => $categories,
            'activeAdmin' => 'videos'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        $categories = VideoCategory::all();

        View::render('admin/videos/create_edit', [
            'title' => 'Add New Dental Video',
            'item' => null,
            'categories' => $categories,
            'activeAdmin' => 'videos'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/videos/create');
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $categoryId = (int)$request->post('category_id') ?: null;
        $videoType = Sanitizer::cleanString($request->post('video_type', 'youtube'));
        $videoUrl = Sanitizer::cleanString($request->post('video_url'));
        $description = Sanitizer::cleanString($request->post('description'));
        $duration = Sanitizer::cleanString($request->post('duration'));
        $displayOrder = (int)$request->post('display_order', 0);
        $status = $request->post('status') === 'draft' ? 'draft' : 'published';
        $publishedAt = Sanitizer::cleanString($request->post('published_at')) ?: date('Y-m-d H:i:s');

        if (empty($title)) {
            Session::flash('error', 'Video title is required.');
            Response::redirect('/admin/videos/create');
            return;
        }

        // Generate unique slug
        $slugBase = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        $slug = $slugBase;
        $counter = 1;
        while (Video::findBySlug($slug)) {
            $slug = $slugBase . '-' . $counter++;
        }

        // Handle Thumbnail Upload
        $thumbnailPath = null;
        $thumbFile = $request->file('thumbnail');
        if ($thumbFile && $thumbFile['error'] === UPLOAD_ERR_OK) {
            $upResult = Uploader::uploadImage($thumbFile, 'videos/thumbnails');
            if ($upResult['success']) {
                $thumbnailPath = $upResult['path'];
            }
        }

        // Handle Video Upload (MP4)
        $videoFilePath = null;
        if ($videoType === 'upload') {
            $videoFile = $request->file('video_file');
            if ($videoFile && $videoFile['error'] === UPLOAD_ERR_OK) {
                // Validate MP4 / WebM
                $allowedMimes = ['video/mp4', 'video/webm', 'video/ogg'];
                $ext = strtolower(pathinfo($videoFile['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['mp4', 'webm', 'ogg'])) {
                    Session::flash('error', 'Only MP4, WebM, and OGG video files are supported.');
                    Response::redirect('/admin/videos/create');
                    return;
                }

                $targetDir = ROOT_DIR . '/public/uploads/videos';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                $filename = 'vid_' . uniqid() . '.' . $ext;
                $dest = $targetDir . '/' . $filename;
                if (move_uploaded_file($videoFile['tmp_name'], $dest)) {
                    $videoFilePath = 'uploads/videos/' . $filename;
                }
            } else {
                Session::flash('error', 'Please upload a valid MP4 video file or provide an external video URL.');
                Response::redirect('/admin/videos/create');
                return;
            }
        } elseif (empty($videoUrl)) {
            Session::flash('error', 'Please provide a valid video URL.');
            Response::redirect('/admin/videos/create');
            return;
        }

        Video::create([
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'video_type' => $videoType,
            'video_url' => $videoUrl,
            'video_file' => $videoFilePath,
            'thumbnail' => $thumbnailPath,
            'duration' => $duration,
            'display_order' => $displayOrder,
            'status' => $status,
            'published_at' => $publishedAt
        ]);

        Session::flash('success', 'Dental video published successfully.');
        Response::redirect('/admin/videos');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->query('id', 0));
        $item = Video::findById($id);

        if (!$item) {
            Session::flash('error', 'Video not found.');
            Response::redirect('/admin/videos');
            return;
        }

        $categories = VideoCategory::all();

        View::render('admin/videos/create_edit', [
            'title' => 'Edit Video: ' . $item['title'],
            'item' => $item,
            'categories' => $categories,
            'activeAdmin' => 'videos'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id', 0));
        $item = Video::findById($id);

        if (!$item) {
            Session::flash('error', 'Video not found.');
            Response::redirect('/admin/videos');
            return;
        }

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/videos/' . $id . '/edit');
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $categoryId = (int)$request->post('category_id') ?: null;
        $videoType = Sanitizer::cleanString($request->post('video_type', 'youtube'));
        $videoUrl = Sanitizer::cleanString($request->post('video_url'));
        $description = Sanitizer::cleanString($request->post('description'));
        $duration = Sanitizer::cleanString($request->post('duration'));
        $displayOrder = (int)$request->post('display_order', 0);
        $status = $request->post('status') === 'draft' ? 'draft' : 'published';
        $publishedAt = Sanitizer::cleanString($request->post('published_at')) ?: date('Y-m-d H:i:s');

        if (empty($title)) {
            Session::flash('error', 'Video title is required.');
            Response::redirect('/admin/videos/' . $id . '/edit');
            return;
        }

        // Handle Thumbnail Update
        $thumbnailPath = $item['thumbnail'];
        $thumbFile = $request->file('thumbnail');
        if ($thumbFile && $thumbFile['error'] === UPLOAD_ERR_OK) {
            $upResult = Uploader::uploadImage($thumbFile, 'videos/thumbnails');
            if ($upResult['success']) {
                $thumbnailPath = $upResult['path'];
            }
        }

        // Handle Video File Update
        $videoFilePath = $item['video_file'];
        if ($videoType === 'upload') {
            $videoFile = $request->file('video_file');
            if ($videoFile && $videoFile['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($videoFile['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['mp4', 'webm', 'ogg'])) {
                    $targetDir = ROOT_DIR . '/public/uploads/videos';
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $filename = 'vid_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($videoFile['tmp_name'], $targetDir . '/' . $filename)) {
                        $videoFilePath = 'uploads/videos/' . $filename;
                    }
                }
            }
        }

        Video::update($id, [
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $item['slug'],
            'description' => $description,
            'video_type' => $videoType,
            'video_url' => $videoUrl,
            'video_file' => $videoFilePath,
            'thumbnail' => $thumbnailPath,
            'duration' => $duration,
            'display_order' => $displayOrder,
            'status' => $status,
            'published_at' => $publishedAt
        ]);

        Session::flash('success', 'Video details updated successfully.');
        Response::redirect('/admin/videos');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id', $request->query('id', 0)));
        $token = $request->post('_csrf_token') ?: $request->query('_csrf_token');

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/videos');
            return;
        }

        Video::delete($id);
        Session::flash('success', 'Video removed successfully.');
        Response::redirect('/admin/videos');
    }
}
