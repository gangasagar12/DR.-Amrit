<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Blog;
use App\Models\Category;
use App\Helpers\Uploader;

class AdminBlogController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $page = max(1, (int)$request->get('page', 1));
        $status = $request->get('status');
        $search = $request->get('q');

        $result = Blog::allAdmin($page, 10, $status, $search);

        View::render('admin/blogs/index', [
            'title' => 'Blog & Research Publication Management',
            'blogs' => $result['items'],
            'total' => $result['total'],
            'currentPage' => $result['page'],
            'totalPages' => $result['total_pages'],
            'currentStatus' => $status,
            'search' => $search,
            'activeAdmin' => 'blogs'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        $categories = Category::all();

        View::render('admin/blogs/create_edit', [
            'title' => 'Write New Dental Article or Research Paper',
            'blog' => null,
            'categories' => $categories,
            'activeAdmin' => 'blogs'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/blogs/create');
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $contentHtml = Sanitizer::cleanHtml($request->post('content_html'));
        $excerpt = Sanitizer::cleanString($request->post('excerpt'));

        if (empty($title)) {
            Session::flash('error', 'Article title is required.');
            Response::redirect('/admin/blogs/create');
            return;
        }

        // If excerpt is empty, auto-generate from content
        if (empty($excerpt)) {
            $excerpt = mb_substr(strip_tags($contentHtml), 0, 200) . '...';
        }

        $featuredImage = null;
        $file = $request->file('featured_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'blogs');
            if ($up['success']) {
                $featuredImage = $up['path'];
            } else {
                Session::flash('error', $up['message']);
                Response::redirect('/admin/blogs/create');
                return;
            }
        }

        // Direct publishing or draft
        $action = $request->post('action'); // 'publish' or 'draft'
        $status = ($action === 'draft') ? 'Draft' : 'Published';

        $id = Blog::create([
            'category_id' => !empty($request->post('category_id')) ? (int)$request->post('category_id') : null,
            'title' => $title,
            'content_type' => Sanitizer::cleanString($request->post('content_type', 'Educational Article')),
            'excerpt' => $excerpt,
            'content_html' => $contentHtml,
            'featured_image' => $featuredImage,
            'image_alt' => Sanitizer::cleanString($request->post('image_alt')),
            'author_name' => Sanitizer::cleanString($request->post('author_name', 'Dr. Sarah Jenkins')),
            'journal_name' => Sanitizer::cleanString($request->post('journal_name')),
            'publication_year' => Sanitizer::cleanString($request->post('publication_year')),
            'doi' => Sanitizer::cleanString($request->post('doi')),
            'publication_url' => Sanitizer::cleanString($request->post('publication_url')),
            'references_text' => Sanitizer::cleanString($request->post('references_text')),
            'meta_title' => Sanitizer::cleanString($request->post('meta_title')),
            'meta_description' => Sanitizer::cleanString($request->post('meta_description')),
            'status' => $status
        ]);

        if ($status === 'Published') {
            Session::flash('success', 'Article published successfully and is now immediately live on your website!');
        } else {
            Session::flash('success', 'Article saved as draft.');
        }

        Response::redirect('/admin/blogs');
    }

    public function edit(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $blog = Blog::findById($id);

        if (!$blog) {
            Session::flash('error', 'Article not found.');
            Response::redirect('/admin/blogs');
            return;
        }

        $categories = Category::all();

        View::render('admin/blogs/create_edit', [
            'title' => 'Edit Article: ' . $blog['title'],
            'blog' => $blog,
            'categories' => $categories,
            'activeAdmin' => 'blogs'
        ], 'layouts/admin');
    }

    public function update(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/blogs/edit?id=' . $id);
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $contentHtml = Sanitizer::cleanHtml($request->post('content_html'));
        $excerpt = Sanitizer::cleanString($request->post('excerpt'));

        if (empty($title)) {
            Session::flash('error', 'Article title is required.');
            Response::redirect('/admin/blogs/edit?id=' . $id);
            return;
        }

        if (empty($excerpt)) {
            $excerpt = mb_substr(strip_tags($contentHtml), 0, 200) . '...';
        }

        $action = $request->post('action');
        $status = ($action === 'draft') ? 'Draft' : 'Published';

        $data = [
            'category_id' => !empty($request->post('category_id')) ? (int)$request->post('category_id') : null,
            'title' => $title,
            'slug' => Sanitizer::cleanString($request->post('slug')),
            'content_type' => Sanitizer::cleanString($request->post('content_type', 'Educational Article')),
            'excerpt' => $excerpt,
            'content_html' => $contentHtml,
            'image_alt' => Sanitizer::cleanString($request->post('image_alt')),
            'author_name' => Sanitizer::cleanString($request->post('author_name', 'Dr. Sarah Jenkins')),
            'journal_name' => Sanitizer::cleanString($request->post('journal_name')),
            'publication_year' => Sanitizer::cleanString($request->post('publication_year')),
            'doi' => Sanitizer::cleanString($request->post('doi')),
            'publication_url' => Sanitizer::cleanString($request->post('publication_url')),
            'references_text' => Sanitizer::cleanString($request->post('references_text')),
            'meta_title' => Sanitizer::cleanString($request->post('meta_title')),
            'meta_description' => Sanitizer::cleanString($request->post('meta_description')),
            'status' => $status
        ];

        $file = $request->file('featured_image');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($file, 'blogs');
            if ($up['success']) {
                $data['featured_image'] = $up['path'];
            }
        }

        Blog::update($id, $data);

        Session::flash('success', 'Article updated successfully' . ($status === 'Published' ? ' and published live.' : ' as draft.'));
        Response::redirect('/admin/blogs');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/blogs');
            return;
        }

        Blog::delete($id);
        Session::flash('success', 'Article has been removed.');
        Response::redirect('/admin/blogs');
    }

    /**
     * AJAX endpoint for TinyMCE editor image uploads
     */
    public function uploadEditorImage(Request $request): void {
        Auth::requireAdmin();

        $file = $request->file('file');
        if (!$file) {
            Response::json(['error' => 'No image file uploaded.'], 400);
        }

        $up = Uploader::uploadImage($file, 'blogs');
        if ($up['success']) {
            $config = require dirname(__DIR__, 2) . '/config/app.php';
            $url = $config['base_url'] . '/' . $up['path'];
            Response::json(['location' => $url]);
        } else {
            Response::json(['error' => $up['message']], 400);
        }
    }
}
