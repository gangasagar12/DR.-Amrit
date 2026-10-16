<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Auth;
use App\Models\Blog;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\Gallery;
use App\Models\Appointment;
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
            'pending_appointments' => Appointment::countPending(),
            'total_appointments' => Appointment::countTotal(),
            'unread_messages' => Message::countUnread()
        ];

        $recentAppointments = Appointment::recent(5);
        $recentMessages = Message::recent(5);

        View::render('admin/dashboard', [
            'title' => 'Clinical Administration Dashboard',
            'stats' => $stats,
            'recentAppointments' => $recentAppointments,
            'recentMessages' => $recentMessages,
            'activeAdmin' => 'dashboard'
        ], 'layouts/admin');
    }
}
