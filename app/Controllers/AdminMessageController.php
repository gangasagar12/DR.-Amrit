<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Message;

class AdminMessageController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $messages = Message::allAdmin();

        View::render('admin/messages/index', [
            'title' => 'Contact Inquiries & Patient Messages',
            'messages' => $messages,
            'activeAdmin' => 'messages'
        ], 'layouts/admin');
    }

    public function markRead(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/messages');
            return;
        }

        Message::markAsRead($id);
        Session::flash('success', 'Message marked as read.');
        Response::redirect('/admin/messages');
    }

    public function markUnread(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/messages');
            return;
        }

        Message::markAsUnread($id);
        Session::flash('success', 'Message marked as unread.');
        Response::redirect('/admin/messages');
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/messages');
            return;
        }

        Message::delete($id);
        Session::flash('success', 'Message deleted.');
        Response::redirect('/admin/messages');
    }
}
