<?php
/** @var array $videos */
/** @var array $categories */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <div class="admin-card-title">Manage Dental Video Gallery</div>
      <p style="font-size:0.875rem;color:#64748b;margin-top:0.25rem;">
        Publish and organize patient education videos, clinical walkthroughs, oral hygiene guides, and treatment demonstrations.
      </p>
    </div>
    <div style="display:flex;gap:0.75rem;">
      <a href="<?= $url('/admin/video-categories') ?>" class="btn btn-secondary btn-sm">
        <i data-lucide="tag" style="width:14px;height:14px;"></i> Categories
      </a>
      <a href="<?= $url('/admin/videos/create') ?>" class="btn btn-primary btn-sm">
        <i data-lucide="plus" style="width:14px;height:14px;"></i> Add New Video
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Thumbnail</th>
          <th>Title & Category</th>
          <th>Source</th>
          <th>Duration</th>
          <th>Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($videos)): ?>
          <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:#64748b;">No dental videos published yet. Click "Add New Video" to get started.</td></tr>
        <?php else: ?>
          <?php foreach ($videos as $v): ?>
            <tr>
              <td style="width:120px;">
                <div style="width:110px;aspect-ratio:16/9;border-radius:6px;overflow:hidden;background:#0f172a;position:relative;">
                  <?php if (!empty($v['thumbnail'])): ?>
                    <img src="<?= $asset($v['thumbnail']) ?>" alt="<?= $e($v['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
                  <?php else: ?>
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                      <i data-lucide="video" style="width:24px;height:24px;"></i>
                    </div>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <strong style="color:#0f172a;font-size:0.95rem;"><?= $e($v['title']) ?></strong>
                <div style="display:flex;align-items:center;gap:0.5rem;margin-top:0.25rem;">
                  <span class="status-badge badge-pending" style="font-size:0.75rem;">
                    <?= $e($v['category_name'] ?? 'Uncategorized') ?>
                  </span>
                  <?php if (!empty($v['published_at'])): ?>
                    <span style="font-size:0.75rem;color:#64748b;">
                      <?= date('M d, Y', strtotime($v['published_at'])) ?>
                    </span>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <span style="text-transform:uppercase;font-size:0.75rem;font-weight:700;color:#0284c7;background:#e0f2fe;padding:0.2rem 0.5rem;border-radius:4px;">
                  <?= $e($v['video_type']) ?>
                </span>
              </td>
              <td>
                <span style="font-size:0.8125rem;color:#475569;font-weight:600;">
                  <?= !empty($v['duration']) ? $e($v['duration']) : '--' ?>
                </span>
              </td>
              <td><?= (int)$v['display_order'] ?></td>
              <td>
                <?= $v['status'] === 'published' 
                  ? '<span class="status-badge badge-published">Published</span>' 
                  : '<span class="status-badge badge-draft">Draft</span>' ?>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= $url('/admin/videos/' . $v['id'] . '/edit') ?>" class="btn btn-secondary btn-sm">Edit</a>
                  <form action="<?= $url('/admin/videos/' . $v['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Remove this dental video?');">
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
