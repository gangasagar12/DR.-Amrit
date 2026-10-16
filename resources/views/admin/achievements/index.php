<?php
/** @var array $achievements */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Training, Certifications, Awards & Memberships</div>
    <a href="<?= $url('/admin/achievements/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Record
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Type</th>
          <th>Organization</th>
          <th>Date</th>
          <th>Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($achievements)): ?>
          <tr><td colspan="7" style="text-align:center;padding:2rem;">No achievement records added yet.</td></tr>
        <?php else: ?>
          <?php foreach ($achievements as $ach): ?>
            <tr>
              <td>
                <strong><?= $e($ach['title']) ?></strong>
                <?php if (!empty($ach['verification_url'])): ?>
                  <div style="font-size:0.75rem;"><a href="<?= $e($ach['verification_url']) ?>" target="_blank" rel="noopener">Verified Link</a></div>
                <?php endif; ?>
              </td>
              <td><span class="status-badge badge-pending"><?= $e($ach['record_type']) ?></span></td>
              <td><?= $e($ach['organization']) ?></td>
              <td><?= $e($ach['achievement_date']) ?></td>
              <td><?= (int)$ach['display_order'] ?></td>
              <td>
                <?= $ach['is_active'] ? '<span class="status-badge badge-published">Active</span>' : '<span class="status-badge badge-draft">Hidden</span>' ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/admin/achievements/edit?id=' . $ach['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                  <a href="<?= $url('/admin/achievements/delete?id=' . $ach['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this certification record?">
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
