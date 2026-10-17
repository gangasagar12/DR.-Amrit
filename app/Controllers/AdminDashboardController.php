<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Auth;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\Gallery;
use App\Models\Video;
use App\Models\Research;
use App\Models\Qualification;
use App\Models\Message;

class AdminDashboardController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $stats = [
            'published_blogs' => Blog::countPublished(),
            'draft_blogs' => Blog::countDrafts(),
            'services' => Service::count(),
            'clinics' => Clinic::count(),
            'gallery_images' => Gallery::count(),
            'videos' => Video::count(),
            'researches' => Research::countPublished(),
            'qualifications' => Qualification::count(),
            'unread_messages' => Message::countUnread(),
            'total_messages' => Message::countTotal()
        ];

        $recentMessages = Message::recent(8);

        View::render('admin/dashboard', [
            'title' => 'Doctor Management Portal Dashboard',
            'stats' => $stats,
            'recentMessages' => $recentMessages,
            'activeAdmin' => 'dashboard'
        ], 'layouts/admin');
    }
}
