<?php
/** @var array $stats */
/** @var array $recentAppointments */
/** @var array $recentMessages */
?>

<!-- KPI Metric Cards -->
<div class="metrics-grid">
  <div class="metric-card">
    <div class="metric-icon-box icon-teal">
      <i data-lucide="calendar" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['pending_appointments'] ?></span>
      <span class="metric-label">Pending Appointments</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-navy">
      <i data-lucide="book-open" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['published_blogs'] ?></span>
      <span class="metric-label">Published Articles</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-amber">
      <i data-lucide="activity" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['services'] ?></span>
      <span class="metric-label">Active Treatments</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-rose">
      <i data-lucide="mail" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['unread_messages'] ?></span>
      <span class="metric-label">Unread Inquiries</span>
    </div>
  </div>
</div>

<!-- Secondary Stats Row -->
<div class="dashboard-quick-stats">
  <div class="quick-stat-item">
    <i data-lucide="building-2" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['clinics'] ?></strong> Clinic Locations</span>
  </div>
  <div class="quick-stat-item">
    <i data-lucide="image" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['gallery_images'] ?></strong> Gallery Photos</span>
  </div>
  <div class="quick-stat-item">
    <i data-lucide="file-edit" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['draft_blogs'] ?></strong> Article Drafts</span>
  </div>
  <div class="quick-stat-actions">
    <a href="<?= $url('/admin/blogs/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> New Article
    </a>
    <a href="<?= $url('/admin/services/create') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> New Service
    </a>
  </div>
</div>

<div class="dashboard-main-grid">
  <!-- Recent Appointments Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Recent Appointment Requests</div>
      <a href="<?= $url('/admin/appointments') ?>" class="btn btn-secondary btn-sm">View All (<?= $stats['total_appointments'] ?>)</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Patient</th>
            <th>Service / Clinic</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentAppointments)): ?>
            <tr>
              <td colspan="5" style="text-align:center;padding:2rem;color:var(--admin-text-muted);">
                No appointment requests submitted yet.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentAppointments as $app): ?>
              <tr>
                <td>
                  <strong><?= $e($app['patient_name']) ?></strong>
                  <div style="font-size:0.785rem;color:var(--admin-text-muted);"><?= $e($app['phone']) ?></div>
                </td>
                <td>
                  <div style="font-weight:600;"><?= $e($app['service_name'] ?? 'General Consultation') ?></div>
                  <div style="font-size:0.785rem;color:var(--admin-text-muted);"><?= $e($app['clinic_name'] ?? 'Primary Clinic') ?></div>
                </td>
                <td>
                  <div><?= date('M d, Y', strtotime($app['preferred_date'])) ?></div>
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
                <td>
                  <a href="<?= $url('/admin/appointments/detail?id=' . $app['id']) ?>" class="btn btn-secondary btn-sm" title="Review Request">
                    <i data-lucide="eye" style="width:14px;height:14px;"></i> Details
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Contact Inquiries -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Recent Inquiries</div>
      <a href="<?= $url('/admin/messages') ?>" class="btn btn-secondary btn-sm">View All</a>
    </div>

    <div class="admin-card-body" style="padding:0.75rem 1.25rem;">
      <?php if (empty($recentMessages)): ?>
        <p style="text-align:center;padding:2rem;color:var(--admin-text-muted);margin:0;">No messages received yet.</p>
      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
          <?php foreach ($recentMessages as $msg): ?>
            <div style="padding:0.85rem;border-radius:8px;background:<?= $msg['is_read'] ? '#fff' : '#f0fdfa' ?>;border:1px solid <?= $msg['is_read'] ? 'var(--admin-border)' : 'var(--admin-teal)' ?>;">
              <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.25rem;">
                <strong style="font-size:0.9rem;color:var(--admin-navy);"><?= $e($msg['name']) ?></strong>
                <span style="font-size:0.75rem;color:var(--admin-text-muted);"><?= date('M d', strtotime($msg['created_at'])) ?></span>
              </div>
              <div style="font-size:0.85rem;font-weight:600;color:var(--admin-teal);margin-bottom:0.25rem;"><?= $e($msg['subject']) ?></div>
              <p style="font-size:0.825rem;color:var(--admin-text-muted);margin:0;line-height:1.4;">
                <?= $e(mb_substr($msg['message'], 0, 90)) ?>...
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
