<?php
/** @var array $qualifications */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Academic Qualifications & Education Timeline</div>
    <a href="<?= $url('/admin/qualifications/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Qualification
    </a>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Degree / Qualification</th>
          <th>Institution & Location</th>
          <th>Years / Period</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($qualifications)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2rem;">No educational qualifications added yet.</td></tr>
        <?php else: ?>
          <?php foreach ($qualifications as $q): ?>
            <tr>
              <td>
                <span style="display:inline-block;width:24px;height:24px;line-height:24px;text-align:center;background:#e0f2fe;color:#0284c7;font-weight:700;border-radius:50%;font-size:0.75rem;">
                  <?= (int)$q['display_order'] ?>
                </span>
              </td>
              <td>
                <strong><?= $e($q['degree']) ?></strong>
                <p style="font-size:0.8rem;color:#64748b;margin:0.25rem 0 0;max-width:320px;"><?= $e(substr($q['description'] ?? '', 0, 90)) ?>...</p>
              </td>
              <td>
                <div><?= $e($q['institution']) ?></div>
                <?php if (!empty($q['location'])): ?>
                  <span style="font-size:0.75rem;color:#64748b;"><?= $e($q['location']) ?></span>
                <?php endif; ?>
              </td>
              <td>
                <span class="status-badge badge-pending">
                  <?= !empty($q['start_year']) ? $e($q['start_year']) . ' &ndash; ' : '' ?><?= $e($q['completion_year']) ?>
                </span>
              </td>
              <td>
                <?= !empty($q['is_published']) ? '<span class="status-badge badge-published">Published</span>' : '<span class="status-badge badge-draft">Draft</span>' ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/admin/qualifications/' . $q['id'] . '/edit') ?>" class="btn btn-secondary btn-sm">Edit</a>
                  <form action="<?= $url('/admin/qualifications/' . $q['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Delete this qualification milestone?');">
                    <?= $csrf() ?>
                    <button type="submit" class="btn btn-danger btn-sm" style="padding:0.4rem 0.6rem;">
                      <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
