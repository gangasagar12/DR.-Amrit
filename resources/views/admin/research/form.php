<?php
/** @var bool $isEdit */
/** @var array $paper */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <div class="admin-card-title"><?= $isEdit ? 'Edit Research Publication' : 'Add New Research Paper' ?></div>
      <span style="font-size:0.85rem;color:var(--admin-text-muted);">Papers appear in the "Research & Publications" section of the doctor portfolio</span>
    </div>
    <a href="<?= $url('/admin/research') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Research List
    </a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $url($isEdit ? '/admin/research/edit' : '/admin/research/create') ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $paper['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <div class="form-group form-full">
          <label class="form-label">Research Paper Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control" value="<?= $e($paper['title'] ?? '') ?>" placeholder="e.g. Clinical Evaluation of Single-Visit Rotary Endodontics in Symptomatic Irreversible Pulpitis" required>
        </div>

        <div class="form-group">
          <label class="form-label">Journal / Publication Forum <span style="color:#ef4444;">*</span></label>
          <input type="text" name="journal_name" class="form-control" value="<?= $e($paper['journal_name'] ?? '') ?>" placeholder="e.g. Journal of Nepalese Dental Association (JNDA)" required>
        </div>

        <div class="form-group">
          <label class="form-label">Publication Year <span style="color:#ef4444;">*</span></label>
          <input type="text" name="publication_year" class="form-control" value="<?= $e($paper['publication_year'] ?? date('Y')) ?>" placeholder="e.g. 2023" required>
        </div>

        <div class="form-group">
          <label class="form-label">Authors List</label>
          <input type="text" name="authors" class="form-control" value="<?= $e($paper['authors'] ?? 'Dr. Amrit Jaishi') ?>" placeholder="e.g. Dr. Amrit Jaishi, Dr. S. Koirala">
        </div>

        <div class="form-group">
          <label class="form-label">DOI / Indexing Reference</label>
          <input type="text" name="doi" class="form-control" value="<?= $e($paper['doi'] ?? '') ?>" placeholder="e.g. 10.3126/jnda.v23i1.45892">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Direct Article Link / URL</label>
          <input type="url" name="publication_url" class="form-control" value="<?= $e($paper['publication_url'] ?? '') ?>" placeholder="https://journal-url.com/article/view/123">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Short Summary / Excerpt (Displayed on Homepage Card) <span style="color:#ef4444;">*</span></label>
          <textarea name="summary" class="form-control" style="min-height:90px;" placeholder="Brief 2-3 sentence overview of clinical methodology, patient sample, and primary findings..." required><?= $e($paper['summary'] ?? '') ?></textarea>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Complete Abstract</label>
          <textarea name="abstract" class="form-control" style="min-height:140px;" placeholder="Introduction, Aims, Methods, Results, Conclusion..."><?= $e($paper['abstract'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($paper['display_order'] ?? 0) ?>" min="0">
          <div class="form-help">Lower number displays first (e.g. 1, 2, 3).</div>
        </div>

        <div class="form-group">
          <label class="form-label">Publication Status</label>
          <select name="status" class="form-control">
            <option value="published" <?= ($paper['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published (Live on Website)</option>
            <option value="draft" <?= ($paper['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
          </select>
        </div>

        <!-- Cover Image Upload -->
        <div class="form-group" style="background:#f8fafc;padding:1rem;border-radius:8px;border:1px solid #e2e8f0;">
          <label class="form-label">Research Card Cover Image</label>
          <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
          <div class="form-help">Format: JPG, PNG, WebP (400x300px recommended).</div>
          <?php if (!empty($paper['cover_image'])): ?>
            <div style="margin-top:0.5rem;display:flex;align-items:center;gap:0.75rem;">
              <img src="<?= $asset($paper['cover_image']) ?>" alt="Cover" style="width:70px;height:50px;object-fit:cover;border-radius:4px;">
              <span style="font-size:0.8rem;color:var(--admin-text-muted);">Current cover</span>
            </div>
          <?php endif; ?>
        </div>

        <!-- PDF Document Upload -->
        <div class="form-group" style="background:#f8fafc;padding:1rem;border-radius:8px;border:1px solid #e2e8f0;">
          <label class="form-label">Upload Research PDF Document</label>
          <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
          <div class="form-help">Upload full research paper PDF (Max 10MB).</div>
          <?php if (!empty($paper['pdf_file'])): ?>
            <div style="margin-top:0.5rem;">
              <a href="<?= $asset($paper['pdf_file']) ?>" target="_blank" style="color:var(--admin-teal);font-size:0.85rem;display:inline-flex;align-items:center;gap:4px;">
                <i data-lucide="file-check" style="width:14px;height:14px;"></i> View Attached PDF
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= $url('/admin/research') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i data-lucide="save" style="width:16px;height:16px;"></i>
          <?= $isEdit ? 'Save Changes' : 'Publish Research Paper' ?>
        </button>
      </div>
    </form>
  </div>
</div>
