<?php
/** @var array $services */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Dental Services & Specialties</div>
    <a href="<?= $url('/admin/services/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add New Treatment
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Service Name</th>
          <th>Category</th>
          <th>Status</th>
          <th>Featured on Home</th>
          <th>Order</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($services)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2rem;">No services created yet.</td></tr>
        <?php else: ?>
          <?php foreach ($services as $srv): ?>
            <tr>
              <td>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                  <?php if (!empty($srv['image'])): ?>
                    <img src="<?= $asset($srv['image']) ?>" alt="" style="width:40px;height:40px;border-radius:6px;object-fit:cover;">
                  <?php else: ?>
                    <div style="width:40px;height:40px;border-radius:6px;background:var(--admin-teal-light);color:var(--admin-teal);display:flex;align-items:center;justify-content:center;">
                      <i data-lucide="activity" style="width:20px;height:20px;"></i>
                    </div>
                  <?php endif; ?>
                  <div>
                    <strong style="color:var(--admin-navy);"><?= $e($srv['name']) ?></strong>
                    <div style="font-size:0.75rem;color:var(--admin-text-muted);">/services/<?= $e($srv['slug']) ?></div>
                  </div>
                </div>
              </td>
              <td><span style="font-weight:600;color:var(--admin-teal);"><?= $e($srv['category']) ?></span></td>
              <td>
                <?php if ($srv['is_active']): ?>
                  <span class="status-badge badge-published">Active</span>
                <?php else: ?>
                  <span class="status-badge badge-draft">Inactive</span>
                <?php endif; ?>
              </td>
              <td>
                <?= $srv['is_featured'] ? '<span style="color:#047857;font-weight:700;">Yes</span>' : '<span style="color:#94a3b8;">No</span>' ?>
              </td>
              <td><?= (int)$srv['display_order'] ?></td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/services/' . $srv['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="Preview Public Page">
                    <i data-lucide="external-link" style="width:13px;height:13px;"></i>
                  </a>
                  <a href="<?= $url('/admin/services/edit?id=' . $srv['id']) ?>" class="btn btn-secondary btn-sm" title="Edit Service">
                    <i data-lucide="edit-2" style="width:13px;height:13px;"></i> Edit
                  </a>
                  <a href="<?= $url('/admin/services/delete?id=' . $srv['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Are you sure you want to delete this service?" title="Delete">
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
