<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Research;
use App\Helpers\Uploader;

class AdminResearchController {
    public function index(Request $request): void {
        Auth::requireAdmin();
        $researches = Research::allAdmin();

        View::render('admin/research/index', [
            'title' => 'Research & Publications Management',
            'researches' => $researches,
            'activeAdmin' => 'research'
        ], 'layouts/admin');
    }

    public function create(Request $request): void {
        Auth::requireAdmin();

        View::render('admin/research/form', [
            'title' => 'Add Research Publication',
            'isEdit' => false,
            'paper' => [
                'id' => '',
                'title' => '',
                'authors' => 'Dr. Amrit Jaishi',
                'journal_name' => '',
                'publication_year' => date('Y'),
                'doi' => '',
                'publication_url' => '',
                'summary' => '',
                'abstract' => '',
                'full_text' => '',
                'cover_image' => '',
                'pdf_file' => '',
                'display_order' => 0,
                'status' => 'published'
            ],
            'activeAdmin' => 'research'
        ], 'layouts/admin');
    }

    public function store(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/research/create');
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $journal = Sanitizer::cleanString($request->post('journal_name'));
        $year = Sanitizer::cleanString($request->post('publication_year'));
        $authors = Sanitizer::cleanString($request->post('authors', 'Dr. Amrit Jaishi'));
        $summary = Sanitizer::cleanString($request->post('summary'));
        $abstract = Sanitizer::cleanString($request->post('abstract'));
        $fullText = Sanitizer::cleanHtml($request->post('full_text'));
        $doi = Sanitizer::cleanString($request->post('doi'));
        $url = Sanitizer::cleanString($request->post('publication_url'));
        $order = (int)$request->post('display_order', 0);
        $status = $request->post('status') === 'draft' ? 'draft' : 'published';

        if (empty($title)) {
            Session::flash('error', 'Research paper title is required.');
            Response::redirect('/admin/research/create');
            return;
        }

        $data = [
            'title' => $title,
            'journal_name' => $journal,
            'publication_year' => $year ?: date('Y'),
            'authors' => $authors,
            'summary' => $summary,
            'abstract' => $abstract,
            'full_text' => $fullText,
            'doi' => $doi,
            'publication_url' => $url,
            'display_order' => $order,
            'status' => $status,
            'cover_image' => '',
            'pdf_file' => ''
        ];

        // Handle cover image
        $imageFile = $request->file('cover_image');
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($imageFile, 'research');
            if ($up['success']) {
                $data['cover_image'] = $up['path'];
            }
        }

        // Handle PDF document
        $pdfFile = $request->file('pdf_file');
        if ($pdfFile && $pdfFile['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadPdf($pdfFile, 'research_docs');
            if ($up['success']) {
                $data['pdf_file'] = $up['path'];
            }
        }

        Research::create($data);
        Session::flash('success', 'Research publication added successfully.');
        Response::redirect('/admin/research');
    }

    public function edit(Request $request): void {
        Auth::requireAdmin();

        $id = (int)$request->get('id', $request->param('id'));
        $paper = Research::findById($id);

        if (!$paper) {
            Session::flash('error', 'Research publication not found.');
            Response::redirect('/admin/research');
            return;
        }

        View::render('admin/research/form', [
            'title' => 'Edit Research Publication: ' . $paper['title'],
            'isEdit' => true,
            'paper' => $paper,
            'activeAdmin' => 'research'
        ], 'layouts/admin');
    }

    public function update(Request $request): void {
        Auth::requireAdmin();

        $id = (int)$request->post('id');
        $paper = Research::findById($id);

        if (!$paper) {
            Session::flash('error', 'Research publication not found.');
            Response::redirect('/admin/research');
            return;
        }

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/research/edit?id=' . $id);
            return;
        }

        $title = Sanitizer::cleanString($request->post('title'));
        $journal = Sanitizer::cleanString($request->post('journal_name'));
        $year = Sanitizer::cleanString($request->post('publication_year'));
        $authors = Sanitizer::cleanString($request->post('authors', 'Dr. Amrit Jaishi'));
        $summary = Sanitizer::cleanString($request->post('summary'));
        $abstract = Sanitizer::cleanString($request->post('abstract'));
        $fullText = Sanitizer::cleanHtml($request->post('full_text'));
        $doi = Sanitizer::cleanString($request->post('doi'));
        $url = Sanitizer::cleanString($request->post('publication_url'));
        $order = (int)$request->post('display_order', 0);
        $status = $request->post('status') === 'draft' ? 'draft' : 'published';

        if (empty($title)) {
            Session::flash('error', 'Research paper title is required.');
            Response::redirect('/admin/research/edit?id=' . $id);
            return;
        }

        $data = [
            'title' => $title,
            'journal_name' => $journal,
            'publication_year' => $year,
            'authors' => $authors,
            'summary' => $summary,
            'abstract' => $abstract,
            'full_text' => $fullText,
            'doi' => $doi,
            'publication_url' => $url,
            'display_order' => $order,
            'status' => $status
        ];

        // Handle cover image
        $imageFile = $request->file('cover_image');
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadImage($imageFile, 'research');
            if ($up['success']) {
                $data['cover_image'] = $up['path'];
            }
        }

        // Handle PDF document
        $pdfFile = $request->file('pdf_file');
        if ($pdfFile && $pdfFile['error'] === UPLOAD_ERR_OK) {
            $up = Uploader::uploadPdf($pdfFile, 'research_docs');
            if ($up['success']) {
                $data['pdf_file'] = $up['path'];
            }
        }

        Research::update($id, $data);
        Session::flash('success', 'Research publication updated successfully.');
        Response::redirect('/admin/research');
    }

    public function delete(Request $request): void {
        Auth::requireAdmin();

        $id = (int)$request->post('id', $request->get('id', $request->param('id')));
        $token = $request->post('_csrf_token', $request->get('_csrf_token'));

        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/research');
            return;
        }

        Research::delete($id);
        Session::flash('success', 'Research publication removed.');
        Response::redirect('/admin/research');
    }
}
