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
use App\Models\PasswordReset;
use App\Models\DoctorProfile;
use App\Helpers\Mailer;

class AdminAuthController {
    public function showLogin(Request $request): void {
        if (Auth::check()) {
            Response::redirect('/admin/dashboard');
            return;
        }

        View::render('admin/login', [
            'title' => 'Doctor Portal Login | Dr. Amrit Jaishi'
        ], null); // Render without admin layout
    }

    public function login(Request $request): void {
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/admin/login');
            return;
        }

        $email = Sanitizer::cleanString($request->post('email'));
        $password = $request->post('password') ?? '';

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please enter your email and password.');
            Response::redirect('/admin/login');
            return;
        }

        $result = Auth::attempt($email, $password);

        if ($result['success']) {
            Session::flash('success', 'Welcome back, ' . htmlspecialchars($result['user']['name']) . '!');
            Response::redirect('/admin/dashboard');
        } else {
            Session::flash('error', $result['message']);
            Response::redirect('/admin/login');
        }
    }

    public function logout(Request $request): void {
        Auth::logout();
        Session::flash('success', 'You have been safely logged out.');
        Response::redirect('/admin/login');
    }

    public function showSetup(Request $request): void {
        // If an admin already exists, lock this route!
        if (User::count() > 0) {
            Session::flash('error', 'Initial setup has already been completed.');
            Response::redirect('/admin/login');
            return;
        }

        View::render('admin/setup', [
            'title' => 'Initial Administrator Setup'
        ], null);
    }

    public function processSetup(Request $request): void {
        if (User::count() > 0) {
            Session::flash('error', 'Initial setup has already been completed.');
            Response::redirect('/admin/login');
            return;
        }

        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/setup');
            return;
        }

        $name = Sanitizer::cleanString($request->post('name'));
        $email = filter_var($request->post('email'), FILTER_VALIDATE_EMAIL) ? trim($request->post('email')) : null;
        $password = $request->post('password');
        $confirmPassword = $request->post('confirm_password');

        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'All fields are required.');
            Response::redirect('/admin/setup');
            return;
        }

        if (strlen($password) < 8) {
            Session::flash('error', 'Password must be at least 8 characters long.');
            Response::redirect('/admin/setup');
            return;
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'Passwords do not match.');
            Response::redirect('/admin/setup');
            return;
        }

        User::create($name, $email, $password);

        Session::flash('success', 'Administrator account created successfully! Please log in.');
        Response::redirect('/admin/login');
    }

    public function showForgotPassword(Request $request): void {
        if (Auth::check()) {
            Response::redirect('/admin/dashboard');
            return;
        }

        View::render('admin/forgot_password', [
            'title' => 'Forgot Password | Dr. Amrit Jaishi'
        ], null);
    }

    public function sendResetLink(Request $request): void {
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        $email = Sanitizer::cleanString($request->post('email'));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please provide a valid doctor email address.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        $user = User::findDoctorOrAdminByEmail($email);
        if (!$user) {
            Session::flash('error', "No doctor administrator account was found associated with '{$email}'.");
            Response::redirect('/admin/forgot-password');
            return;
        }

        // Generate 64-char crypto-secure hex token
        $plainToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $plainToken);

        // Store in database (valid for 60 minutes)
        PasswordReset::create((int)$user['id'], $email, $tokenHash, 60);

        // Generate full reset URL
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $baseUrl = $config['base_url'] ?? 'http://localhost/doctor';
        $resetUrl = rtrim($baseUrl, '/') . '/admin/reset-password?token=' . urlencode($plainToken);

        // Prepare email body
        $doctorName = htmlspecialchars($user['name'] ?? 'Doctor');
        $htmlBody = "
        <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff;'>
            <div style='text-align: center; margin-bottom: 24px;'>
                <h2 style='color: #0f172a; margin-bottom: 4px; font-size: 22px;'>Dr. Amrit Jaishi Dental Practice</h2>
                <p style='color: #64748b; font-size: 14px; margin: 0;'>Doctor Administrative Portal</p>
            </div>
            <div style='padding: 24px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;'>
                <p style='color: #334155; font-size: 16px; line-height: 1.6; margin-top: 0;'>Hello <strong>{$doctorName}</strong>,</p>
                <p style='color: #475569; font-size: 15px; line-height: 1.6;'>
                    A request has been made to reset the password for your Doctor Portal account. You can securely set a new password by clicking the button below:
                </p>
                <div style='text-align: center; margin: 32px 0;'>
                    <a href='{$resetUrl}' style='background-color: #0d9488; color: #ffffff; padding: 13px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);'>
                        Reset Doctor Password
                    </a>
                </div>
                <p style='color: #64748b; font-size: 13px; line-height: 1.5;'>
                    If the button above does not work, copy and paste this link into your web browser:<br>
                    <a href='{$resetUrl}' style='color: #0d9488; word-break: break-all; font-weight: 500;'>{$resetUrl}</a>
                </p>
                <p style='color: #94a3b8; font-size: 12px; margin-top: 24px; margin-bottom: 0;'>
                    * This reset link is single-use and will automatically expire in 60 minutes. If you did not initiate this request, your existing login credentials remain completely secure.
                </p>
            </div>
            <div style='text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8;'>
                &copy; " . date('Y') . " Dr. Amrit Jaishi Dental Practice. All rights reserved.
            </div>
        </div>
        ";

        // Dispatch email
        Mailer::send($email, 'Password Reset Link | Doctor Portal', $htmlBody);

        Session::flash('success', "A password reset link has been dispatched to {$email}. Please check your email inbox.");
        // Provide link preview for immediate testing
        Session::flash('reset_preview_link', $resetUrl);

        Response::redirect('/admin/forgot-password');
    }

    public function showResetPassword(Request $request): void {
        if (Auth::check()) {
            Response::redirect('/admin/dashboard');
            return;
        }

        $plainToken = $request->get('token') ?? '';
        if (empty($plainToken)) {
            Session::flash('error', 'Missing password reset token.');
            Response::redirect('/admin/login');
            return;
        }

        $tokenHash = hash('sha256', $plainToken);
        $reset = PasswordReset::findValidToken($tokenHash);

        if (!$reset) {
            Session::flash('error', 'This password reset link is invalid, expired, or has already been used. Please request a new one.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        View::render('admin/reset_password', [
            'title' => 'Set New Password | Doctor Portal',
            'token' => $plainToken,
            'email' => $reset['email']
        ], null);
    }

    public function processResetPassword(Request $request): void {
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid or expired. Please try again.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        $plainToken = $request->post('token') ?? '';
        $password = $request->post('password') ?? '';
        $confirmPassword = $request->post('confirm_password') ?? '';

        if (empty($plainToken)) {
            Session::flash('error', 'Reset token is required.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        $tokenHash = hash('sha256', $plainToken);
        $reset = PasswordReset::findValidToken($tokenHash);

        if (!$reset) {
            Session::flash('error', 'This password reset link has expired or is invalid. Please request a new reset link.');
            Response::redirect('/admin/forgot-password');
            return;
        }

        if (strlen($password) < 8) {
            Session::flash('error', 'New password must be at least 8 characters long.');
            Response::redirect('/admin/reset-password?token=' . urlencode($plainToken));
            return;
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'Password confirmation does not match.');
            Response::redirect('/admin/reset-password?token=' . urlencode($plainToken));
            return;
        }

        // Update the admin user password
        User::updatePassword((int)$reset['user_id'], $password);

        // Clear all reset tokens for this user
        PasswordReset::deleteForUser((int)$reset['user_id']);

        Session::flash('success', 'Your password has been successfully reset! You can now log in with your new password.');
        Response::redirect('/admin/login');
    }
}
