<?php
/** @var array $appointments */
/** @var array $clinics */
/** @var array $services */
/** @var ?string $currentStatus */
/** @var ?int $currentClinic */
/** @var ?string $currentDate */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Patient Appointment Requests (<?= count($appointments) ?>)</div>
    <div style="font-size:0.85rem;color:var(--admin-text-muted);">
      Initial requests are strictly Pending until reviewed & confirmed by clinic team.
    </div>
  </div>

  <!-- Filter Controls -->
  <div style="padding:1rem 1.5rem;background:#f8fafc;border-bottom:1px solid var(--admin-border);">
    <form action="<?= $url('/admin/appointments') ?>" method="GET" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:center;">
      <div>
        <select name="status" class="form-control" style="width:160px;">
          <option value="">All Statuses</option>
          <?php foreach (['Pending', 'Confirmed', 'Completed', 'Cancelled'] as $st): ?>
            <option value="<?= $st ?>" <?= ($currentStatus ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <select name="clinic_id" class="form-control" style="width:200px;">
          <option value="">All Clinics</option>
          <?php foreach ($clinics as $cl): ?>
            <option value="<?= $cl['id'] ?>" <?= ($currentClinic ?? null) == $cl['id'] ? 'selected' : '' ?>>
              <?= $e($cl['clinic_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <input type="date" name="date" class="form-control" value="<?= $e($currentDate ?? '') ?>" style="width:160px;">
      </div>

      <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
      <?php if ($currentStatus || $currentClinic || $currentDate): ?>
        <a href="<?= $url('/admin/appointments') ?>" class="btn btn-secondary btn-sm" style="color:var(--danger);">Clear</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Patient Details</th>
          <th>Requested Service</th>
          <th>Clinic Location</th>
          <th>Preferred Date & Time</th>
          <th>Status</th>
          <th>Internal Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($appointments)): ?>
          <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:var(--admin-text-muted);">No appointment records match the selected criteria.</td></tr>
        <?php else: ?>
          <?php foreach ($appointments as $app): ?>
            <tr>
              <td>
                <strong style="color:var(--admin-navy);"><?= $e($app['patient_name']) ?></strong>
                <div style="font-size:0.8rem;color:var(--admin-teal);"><?= $e($app['phone']) ?></div>
                <?php if (!empty($app['email'])): ?>
                  <div style="font-size:0.75rem;color:var(--admin-text-muted);"><?= $e($app['email']) ?></div>
                <?php endif; ?>
              </td>
              <td><?= $e($app['service_name'] ?? 'General Consultation') ?></td>
              <td><span style="font-size:0.85rem;font-weight:600;"><?= $e($app['clinic_name'] ?? 'Primary Clinic') ?></span></td>
              <td>
                <div style="font-weight:600;"><?= date('M d, Y', strtotime($app['preferred_date'])) ?></div>
                <div style="font-size:0.785rem;color:var(--admin-text-muted);"><?= $e($app['preferred_time']) ?></div>
              </td>
              <td>
                <?php 
                  $badgeClass = match($app['status']) {
                    'Pending' => 'badge-pending',
                    'Confirmed' => 'badge-confirmed',
                    'Completed' => 'badge-completed',
                    'Cancelled' => 'badge-cancelled',
                    default => 'badge-pending'
                  };
                ?>
                <span class="status-badge <?= $badgeClass ?>"><?= $e($app['status']) ?></span>
              </td>
              <td style="max-width:180px;font-size:0.8rem;color:var(--admin-text-muted);">
                <?= $e($app['internal_notes'] ?: 'No notes') ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/admin/appointments/detail?id=' . $app['id']) ?>" class="btn btn-secondary btn-sm" title="View details and manage status">
                    <i data-lucide="edit" style="width:13px;height:13px;"></i> Review
                  </a>
                  <a href="<?= $url('/admin/appointments/delete?id=' . $app['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this appointment record?">
                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
