<?php
/** @var array $categories */
?>

<div style="display:grid;grid-template-columns: 0.7fr 1.3fr;gap:2rem;align-items:start;">
  <!-- Add Category Form -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Add Article Category</div>
    </div>
    <div class="admin-card-body">
      <form action="<?= $url('/admin/categories/create') ?>" method="POST">
        <?= $csrf() ?>
        <div class="form-group">
          <label class="form-label">Category Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Pediatric Dentistry" required>
        </div>
        <div class="form-group">
          <label class="form-label">Category Description</label>
          <textarea name="description" class="form-control" placeholder="Brief scope of articles in this category..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">
          <i data-lucide="plus" style="width:16px;height:16px;"></i> Create Category
        </button>
      </form>
    </div>
  </div>

  <!-- Categories Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">All Categories</div>
      <a href="<?= $url('/admin/blogs') ?>" class="btn btn-secondary btn-sm">View Articles</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Articles</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($categories)): ?>
            <tr><td colspan="4" style="text-align:center;padding:2rem;">No categories created yet.</td></tr>
          <?php else: ?>
            <?php foreach ($categories as $cat): ?>
              <tr>
                <td><strong><?= $e($cat['name']) ?></strong></td>
                <td><code style="font-size:0.8rem;background:var(--admin-bg);padding:0.2rem 0.4rem;border-radius:4px;"><?= $e($cat['slug']) ?></code></td>
                <td>
                  <span class="status-badge badge-pending"><?= (int)$cat['blog_count'] ?> articles</span>
                </td>
                <td>
                  <a href="<?= $url('/admin/categories/delete?id=' . $cat['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Delete category '<?= $e($cat['name']) ?>'?">
                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
