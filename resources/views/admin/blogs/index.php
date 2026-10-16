<?php
/** @var array $blogs */
/** @var int $currentPage */
/** @var int $totalPages */
/** @var ?string $currentStatus */
/** @var ?string $search */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Dental Articles & Research Publications</div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="<?= $url('/admin/categories') ?>" class="btn btn-secondary btn-sm">
        <i data-lucide="folder-tree" style="width:14px;height:14px;"></i> Manage Categories
      </a>
      <a href="<?= $url('/admin/blogs/create') ?>" class="btn btn-primary btn-sm">
        <i data-lucide="plus" style="width:14px;height:14px;"></i> Write New Article
      </a>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div style="padding:1rem 1.5rem;background:#f8fafc;border-bottom:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
    <div style="display:flex;gap:0.5rem;">
      <a href="<?= $url('/admin/blogs') ?>" class="btn <?= empty($currentStatus) ? 'btn-primary' : 'btn-secondary' ?> btn-sm">All</a>
      <a href="<?= $url('/admin/blogs?status=Published') ?>" class="btn <?= $currentStatus === 'Published' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Published</a>
      <a href="<?= $url('/admin/blogs?status=Draft') ?>" class="btn <?= $currentStatus === 'Draft' ? 'btn-primary' : 'btn-secondary' ?> btn-sm">Drafts</a>
    </div>

    <form action="<?= $url('/admin/blogs') ?>" method="GET" style="display:flex;gap:0.5rem;">
      <?php if ($currentStatus): ?>
        <input type="hidden" name="status" value="<?= $e($currentStatus) ?>">
      <?php endif; ?>
      <input type="text" name="q" class="form-control" style="width:220px;" placeholder="Search title or author..." value="<?= $e($search ?? '') ?>">
      <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Type</th>
          <th>Status</th>
          <th>Published Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($blogs)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2.5rem;">No blog articles found.</td></tr>
        <?php else: ?>
          <?php foreach ($blogs as $b): ?>
            <tr>
              <td>
                <strong><?= $e($b['title']) ?></strong>
                <div style="font-size:0.75rem;color:var(--admin-text-muted);">/blog/<?= $e($b['slug']) ?></div>
              </td>
              <td><?= $e($b['category_name'] ?? 'Uncategorized') ?></td>
              <td>
                <span class="status-badge <?= $b['content_type'] === 'Research Publication' ? 'badge-confirmed' : 'badge-pending' ?>" style="font-size:0.725rem;">
                  <?= $e($b['content_type']) ?>
                </span>
              </td>
              <td>
                <span class="status-badge <?= $b['status'] === 'Published' ? 'badge-published' : 'badge-draft' ?>">
                  <?= $e($b['status']) ?>
                </span>
              </td>
              <td style="font-size:0.85rem;color:var(--admin-text-muted);">
                <?= $b['published_at'] ? date('M d, Y', strtotime($b['published_at'])) : 'Draft' ?>
              </td>
              <td>
                <div class="action-buttons">
                  <?php if ($b['status'] === 'Published'): ?>
                    <a href="<?= $url('/blog/' . $b['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm" title="View Public Article">
                      <i data-lucide="external-link" style="width:13px;height:13px;"></i>
                    </a>
                  <?php endif; ?>
                  <a href="<?= $url('/admin/blogs/edit?id=' . $b['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>
                  <a href="<?= $url('/admin/blogs/delete?id=' . $b['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Delete this article?">
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

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div style="display:flex;justify-content:center;gap:0.4rem;padding:1.5rem;border-top:1px solid var(--admin-border);">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= $url('/admin/blogs?page=' . $p . ($currentStatus ? '&status=' . $currentStatus : '') . ($search ? '&q=' . urlencode($search) : '')) ?>" 
           class="btn <?= $p === $currentPage ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>
