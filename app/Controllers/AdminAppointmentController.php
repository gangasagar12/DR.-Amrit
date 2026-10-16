<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Service;

class AdminAppointmentController {
    public function index(Request $request): void {
        Auth::requireAdmin();

        $status = $request->get('status');
        $clinicId = $request->get('clinic_id');
        $date = $request->get('date');

        $filters = [];
        if ($status) $filters['status'] = $status;
        if ($clinicId) $filters['clinic_id'] = $clinicId;
        if ($date) $filters['date'] = $date;

        $appointments = Appointment::allAdmin($filters);
        $clinics = Clinic::allAdmin();
        $services = Service::allAdmin();

        View::render('admin/appointments/index', [
            'title' => 'Patient Appointment Requests Management',
            'appointments' => $appointments,
            'clinics' => $clinics,
            'services' => $services,
            'currentStatus' => $status,
            'currentClinic' => $clinicId,
            'currentDate' => $date,
            'activeAdmin' => 'appointments'
        ], 'layouts/admin');
    }

    public function detail(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->get('id'));
        $appointment = Appointment::findById($id);

        if (!$appointment) {
            Session::flash('error', 'Appointment request not found.');
            Response::redirect('/admin/appointments');
            return;
        }

        View::render('admin/appointments/detail', [
            'title' => 'Appointment Request: ' . $appointment['patient_name'],
            'appointment' => $appointment,
            'activeAdmin' => 'appointments'
        ], 'layouts/admin');
    }

    public function updateStatus(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->post('id'));
        $token = $request->post('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/appointments');
            return;
        }

        $status = Sanitizer::cleanString($request->post('status'));
        $notes = Sanitizer::cleanString($request->post('internal_notes'));

        Appointment::updateStatus($id, $status, $notes);
        Session::flash('success', 'Appointment status updated to ' . htmlspecialchars($status) . '.');

        if ($request->post('from_detail')) {
            Response::redirect('/admin/appointments/detail?id=' . $id);
        } else {
            Response::redirect('/admin/appointments');
        }
    }

    public function delete(Request $request, array $params): void {
        Auth::requireAdmin();

        $id = (int)($params['id'] ?? $request->input('id'));
        $token = $request->input('_csrf_token');
        if (!Csrf::validate($token)) {
            Session::flash('error', 'Security token invalid.');
            Response::redirect('/admin/appointments');
            return;
        }

        Appointment::delete($id);
        Session::flash('success', 'Appointment record removed.');
        Response::redirect('/admin/appointments');
    }
}
