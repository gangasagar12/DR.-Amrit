<?php
use App\Core\Router;
use App\Core\Response;

/** @var Router $router */

// Public Website Routes
$router->get('/', 'PublicController@home');
$router->get('/about', 'PublicController@about');
$router->get('/services', 'PublicController@services');
$router->get('/services/{slug}', 'PublicController@serviceDetail');
$router->get('/achievements', 'PublicController@achievements');
$router->get('/gallery', 'PublicController@gallery');
$router->get('/clinics', 'PublicController@clinics');
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@show');
$router->get('/contact', 'PublicController@contact');

// Public Form Submissions
$router->post('/appointment/book', 'PublicController@bookAppointment');
$router->post('/contact/submit', 'PublicController@submitContact');
$router->get('/api/portfolio-data', 'PublicController@apiPortfolioData');

// SEO Feeds
$router->get('/sitemap.xml', 'PublicController@sitemap');
$router->get('/robots.txt', 'PublicController@robots');

// Admin Panel Authentication
$router->get('/admin', function() {
    Response::redirect('/admin/dashboard');
});
$router->get('/admin/login', 'AdminAuthController@showLogin');
$router->post('/admin/login', 'AdminAuthController@login');
$router->any('/admin/logout', 'AdminAuthController@logout');
$router->get('/admin/forgot-password', 'AdminAuthController@showForgotPassword');
$router->post('/admin/forgot-password', 'AdminAuthController@sendResetLink');
$router->get('/admin/reset-password', 'AdminAuthController@showResetPassword');
$router->post('/admin/reset-password', 'AdminAuthController@processResetPassword');
$router->get('/admin/setup', 'AdminAuthController@showSetup');
$router->post('/admin/setup', 'AdminAuthController@processSetup');

// Admin Dashboard
$router->get('/admin/dashboard', 'AdminDashboardController@index');

// Doctor Profile & Qualifications
$router->get('/admin/profile', 'AdminProfileController@edit');
$router->post('/admin/profile', 'AdminProfileController@update');
$router->post('/admin/profile/qualification/add', 'AdminProfileController@addQualification');
$router->post('/admin/profile/qualification/update', 'AdminProfileController@updateQualification');
$router->any('/admin/profile/qualification/delete', 'AdminProfileController@deleteQualification');

// Dental Services
$router->get('/admin/services', 'AdminServiceController@index');
$router->get('/admin/services/create', 'AdminServiceController@create');
$router->post('/admin/services/create', 'AdminServiceController@store');
$router->get('/admin/services/edit', 'AdminServiceController@edit');
$router->post('/admin/services/edit', 'AdminServiceController@update');
$router->any('/admin/services/delete', 'AdminServiceController@delete');

// Achievements & Certifications
$router->get('/admin/achievements', 'AdminAchievementController@index');
$router->get('/admin/achievements/create', 'AdminAchievementController@create');
$router->post('/admin/achievements/create', 'AdminAchievementController@store');
$router->get('/admin/achievements/edit', 'AdminAchievementController@edit');
$router->post('/admin/achievements/edit', 'AdminAchievementController@update');
$router->any('/admin/achievements/delete', 'AdminAchievementController@delete');

// Gallery
$router->get('/admin/gallery', 'AdminGalleryController@index');
$router->post('/admin/gallery/upload', 'AdminGalleryController@store');
$router->any('/admin/gallery/delete', 'AdminGalleryController@delete');

// Clinics & Affiliations
$router->get('/admin/clinics', 'AdminClinicController@index');
$router->get('/admin/clinics/create', 'AdminClinicController@create');
$router->post('/admin/clinics/create', 'AdminClinicController@store');
$router->get('/admin/clinics/edit', 'AdminClinicController@edit');
$router->post('/admin/clinics/edit', 'AdminClinicController@update');
$router->any('/admin/clinics/delete', 'AdminClinicController@delete');

// Blogs & Research Publications
$router->get('/admin/blogs', 'AdminBlogController@index');
$router->get('/admin/blogs/create', 'AdminBlogController@create');
$router->post('/admin/blogs/create', 'AdminBlogController@store');
$router->get('/admin/blogs/edit', 'AdminBlogController@edit');
$router->post('/admin/blogs/edit', 'AdminBlogController@update');
$router->any('/admin/blogs/delete', 'AdminBlogController@delete');
$router->post('/admin/blogs/upload-image', 'AdminBlogController@uploadEditorImage');

// Blog Categories
$router->get('/admin/categories', 'AdminCategoryController@index');
$router->post('/admin/categories/create', 'AdminCategoryController@store');
$router->post('/admin/categories/update', 'AdminCategoryController@update');
$router->any('/admin/categories/delete', 'AdminCategoryController@delete');

// Appointments Management
$router->get('/admin/appointments', 'AdminAppointmentController@index');
$router->get('/admin/appointments/detail', 'AdminAppointmentController@detail');
$router->post('/admin/appointments/status', 'AdminAppointmentController@updateStatus');
$router->any('/admin/appointments/delete', 'AdminAppointmentController@delete');

// Contact Messages
$router->get('/admin/messages', 'AdminMessageController@index');
$router->any('/admin/messages/read', 'AdminMessageController@markRead');
$router->any('/admin/messages/unread', 'AdminMessageController@markUnread');
$router->any('/admin/messages/delete', 'AdminMessageController@delete');

// Site Settings & Testimonials
$router->get('/admin/settings', 'AdminSettingsController@index');
$router->post('/admin/settings', 'AdminSettingsController@update');
$router->post('/admin/settings/test-smtp', 'AdminSettingsController@testSmtp');
$router->get('/admin/testimonials', 'AdminSettingsController@testimonials');
$router->post('/admin/testimonials/create', 'AdminSettingsController@addTestimonial');
$router->post('/admin/testimonials/update', 'AdminSettingsController@updateTestimonial');
$router->any('/admin/testimonials/delete', 'AdminSettingsController@deleteTestimonial');

// Admin Account Credentials & Security
$router->get('/admin/account', 'AdminAccountController@edit');
$router->post('/admin/account/update', 'AdminAccountController@updateEmail');
$router->post('/admin/account/password', 'AdminAccountController@updatePassword');

