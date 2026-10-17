<?php
/** @var array $researches */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <div class="admin-card-title">Research Papers & Publications</div>
      <span style="font-size:0.85rem;color:var(--admin-text-muted);">Manage academic research, observational studies, and clinical surveys shown on your homepage</span>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <a href="<?= $url('/') ?>#research" target="_blank" class="btn btn-secondary btn-sm">
        <i data-lucide="external-link" style="width:14px;height:14px;"></i> View on Public Site
      </a>
      <a href="<?= $url('/admin/research/create') ?>" class="btn btn-primary btn-sm">
        <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Research Paper
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Paper Title & Journal</th>
          <th>Year</th>
          <th>Authors</th>
          <th>Status</th>
          <th>Cover / PDF</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($researches)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2.5rem;color:var(--admin-text-muted);">No research papers added yet. Click "Add Research Paper" above.</td></tr>
        <?php else: ?>
          <?php foreach ($researches as $r): ?>
            <tr>
              <td>
                <strong style="color:var(--admin-navy);"><?= $e($r['title']) ?></strong>
                <div style="font-size:0.8rem;color:var(--admin-teal);margin-top:2px;">
                  <i data-lucide="book-marked" style="width:12px;height:12px;display:inline-block;vertical-align:middle;"></i>
                  <?= $e($r['journal_name'] ?: 'Clinical Study') ?>
                  <?php if (!empty($r['doi'])): ?>
                    &bull; DOI: <?= $e($r['doi']) ?>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <span class="status-badge badge-pending" style="font-weight:600;"><?= $e($r['publication_year'] ?: 'N/A') ?></span>
              </td>
              <td style="font-size:0.85rem;color:var(--admin-text-muted);">
                <?= $e($r['authors'] ?: 'Dr. Amrit Jaishi') ?>
              </td>
              <td>
                <?php if ($r['status'] === 'published'): ?>
                  <span class="status-badge badge-confirmed">Published</span>
                <?php else: ?>
                  <span class="status-badge badge-cancelled">Draft</span>
                <?php endif; ?>
              </td>
              <td style="font-size:0.85rem;">
                <?php if (!empty($r['pdf_file'])): ?>
                  <a href="<?= $asset($r['pdf_file']) ?>" target="_blank" style="color:var(--admin-teal);display:inline-flex;align-items:center;gap:3px;text-decoration:none;">
                    <i data-lucide="file-text" style="width:13px;height:13px;"></i> PDF
                  </a>
                <?php else: ?>
                  <span style="color:#94a3b8;">None</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:0.4rem;">
                  <a href="<?= $url('/admin/research/edit?id=' . $r['id']) ?>" class="btn btn-secondary btn-sm" title="Edit">
                    <i data-lucide="edit" style="width:14px;height:14px;"></i>
                  </a>
                  <a href="<?= $url('/admin/research/delete?id=' . $r['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Are you sure you want to delete this research paper?" title="Delete">
                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
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
