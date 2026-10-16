<?php
/** @var array $categories */
?>

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:2rem;align-items:flex-start;">
  <!-- Add Category Form -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Add Video Category</div>
    </div>
    <div class="admin-card-body">
      <form action="<?= $url('/admin/video-categories/create') ?>" method="POST">
        <?= $csrf() ?>
        
        <div class="form-group" style="margin-bottom:1.25rem;">
          <label class="form-label">Category Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Oral Health Education" required>
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="0">
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">
          <i data-lucide="plus" style="width:16px;height:16px;"></i> Create Category
        </button>
      </form>
    </div>
  </div>

  <!-- Existing Categories List -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Existing Video Categories</div>
      <a href="<?= $url('/admin/videos') ?>" class="btn btn-secondary btn-sm">Back to Videos</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order</th>
            <th>Category Name</th>
            <th>Slug</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($categories)): ?>
            <tr><td colspan="4" style="text-align:center;padding:2rem;">No video categories configured.</td></tr>
          <?php else: ?>
            <?php foreach ($categories as $cat): ?>
              <tr>
                <td style="width:70px;"><?= (int)$cat['display_order'] ?></td>
                <td>
                  <strong><?= $e($cat['name']) ?></strong>
                </td>
                <td style="color:#64748b;font-size:0.8125rem;"><?= $e($cat['slug']) ?></td>
                <td style="width:140px;">
                  <div class="action-buttons">
                    <form action="<?= $url('/admin/video-categories/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Remove category <?= $e($cat['name']) ?>?');">
                      <?= $csrf() ?>
                      <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                      <button type="submit" class="btn btn-danger btn-sm" style="padding:0.35rem 0.6rem;">
                        <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
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
</div>
