<?php
/** @var array $clinics */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Clinic Affiliations & Practice Locations</div>
    <a href="<?= $url('/admin/clinics/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add New Clinic Affiliation
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Clinic Name</th>
          <th>Workplace Type</th>
          <th>Doctor Role</th>
          <th>Consulting Days</th>
          <th>Phone / Address</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($clinics)): ?>
          <tr><td colspan="7" style="text-align:center;padding:2rem;">No clinic affiliations configured yet.</td></tr>
        <?php else: ?>
          <?php foreach ($clinics as $c): ?>
            <tr>
              <td>
                <strong><?= $e($c['clinic_name']) ?></strong>
                <div style="font-size:0.75rem;color:var(--admin-text-muted);">/clinics/<?= $e($c['slug']) ?></div>
              </td>
              <td>
                <span class="status-badge <?= $c['workplace_type'] === 'Permanent Workplace' ? 'badge-confirmed' : 'badge-pending' ?>">
                  <?= $e($c['workplace_type']) ?>
                </span>
              </td>
              <td><?= $e($c['doctor_role']) ?></td>
              <td>
                <?php if (!empty($c['schedules'])): ?>
                  <div style="font-size:0.8rem;line-height:1.4;">
                    <?php foreach ($c['schedules'] as $sch): ?>
                      <div><strong><?= $e($sch['visiting_day']) ?>:</strong> <?= $e($sch['start_time']) ?>-<?= $e($sch['end_time']) ?></div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <span style="color:var(--admin-text-muted);font-size:0.8rem;">By prior appointment</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="font-size:0.85rem;color:var(--admin-navy);"><?= $e($c['phone']) ?></div>
                <div style="font-size:0.75rem;color:var(--admin-text-muted);max-width:200px;"><?= $e($c['full_address']) ?></div>
              </td>
              <td>
                <?= $c['is_active'] ? '<span class="status-badge badge-published">Active</span>' : '<span class="status-badge badge-draft">Inactive</span>' ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/admin/clinics/edit?id=' . $c['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                  <a href="<?= $url('/admin/clinics/delete?id=' . $c['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this clinic affiliation?">
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
