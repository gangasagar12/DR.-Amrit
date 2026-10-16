<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\User;

class AdminAccountController {
    public function edit(Request $request): void {
        Auth::requireAdmin();

        $user = Auth::user();
        if (!$user) {
            Session::flash('error', 'Administrator record not found.');
            Response::redirect('/admin/dashboard');
            return;
        }

        View::render('admin/account', [
            'title' => 'Admin Credentials & Security',
            'user' => $user,
            'activeAdmin' => 'account'
        ], 'layouts/admin');
    }

    public function updateEmail(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/admin/account');
            return;
        }

        $userId = (int)Auth::id();
        $name = Sanitizer::cleanString($request->post('name') ?? '');
        $email = strtolower(trim($request->post('email') ?? ''));
        $currentPassword = $request->post('current_password') ?? '';

        if (empty($name)) {
            Session::flash('error', 'Administrator name cannot be empty.');
            Response::redirect('/admin/account');
            return;
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            Response::redirect('/admin/account');
            return;
        }

        if (empty($currentPassword)) {
            Session::flash('error', 'Please enter your current password to authorize login email and account changes.');
            Response::redirect('/admin/account');
            return;
        }

        // Verify current password for security
        if (!User::verifyPassword($userId, $currentPassword)) {
            Session::flash('error', 'Current password was incorrect. Your credentials were not changed.');
            Response::redirect('/admin/account');
            return;
        }

        // Check if email already exists for another user
        if (User::isEmailTaken($email, $userId)) {
            Session::flash('error', 'The email address "' . htmlspecialchars($email) . '" is already registered to another account.');
            Response::redirect('/admin/account');
            return;
        }

        // Update database
        User::updateAccount($userId, $name, $email);

        // Update session
        Session::set('admin_user_name', $name);
        Session::set('admin_user_email', $email);

        Session::flash('success', 'Admin login email and account profile updated successfully!');
        Response::redirect('/admin/account');
    }

    public function updatePassword(Request $request): void {
        Auth::requireAdmin();

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/admin/account');
            return;
        }

        $userId = (int)Auth::id();
        $currentPassword = $request->post('current_password') ?? '';
        $newPassword = $request->post('new_password') ?? '';
        $confirmPassword = $request->post('confirm_password') ?? '';

        if (empty($currentPassword)) {
            Session::flash('error', 'Please provide your current password.');
            Response::redirect('/admin/account');
            return;
        }

        if (!User::verifyPassword($userId, $currentPassword)) {
            Session::flash('error', 'Current password was incorrect. Password was not changed.');
            Response::redirect('/admin/account');
            return;
        }

        if (strlen($newPassword) < 8) {
            Session::flash('error', 'New password must be at least 8 characters long.');
            Response::redirect('/admin/account');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'New password confirmation does not match.');
            Response::redirect('/admin/account');
            return;
        }

        // Update password
        User::updatePassword($userId, $newPassword);

        Session::flash('success', 'Admin login password updated successfully! Please keep your new password safe.');
        Response::redirect('/admin/account');
    }
}
