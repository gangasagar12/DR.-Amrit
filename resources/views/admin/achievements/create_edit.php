<?php
/** @var ?array $item */
$isEdit = !empty($item);
$actionUrl = $isEdit ? $url('/admin/achievements/edit') : $url('/admin/achievements/create');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><?= $isEdit ? 'Edit Record: ' . $e($item['title']) : 'Add Certification / Achievement' ?></div>
    <a href="<?= $url('/admin/achievements') ?>" class="btn btn-secondary btn-sm">Back</a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Record Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control" value="<?= $e($item['title'] ?? '') ?>" placeholder="e.g. Fellow of International Congress of Oral Implantologists" required>
        </div>

        <div class="form-group">
          <label class="form-label">Record Type <span style="color:#ef4444;">*</span></label>
          <select name="record_type" class="form-control">
            <?php foreach (['Certification', 'Training', 'Award', 'Conference', 'Membership'] as $type): ?>
              <option value="<?= $type ?>" <?= ($item['record_type'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Issuing Organization / Academy <span style="color:#ef4444;">*</span></label>
          <input type="text" name="organization" class="form-control" value="<?= $e($item['organization'] ?? '') ?>" placeholder="e.g. ICOI Worldwide / British Dental Association" required>
        </div>

        <div class="form-group">
          <label class="form-label">Date / Year Awarded <span style="color:#ef4444;">*</span></label>
          <input type="text" name="achievement_date" class="form-control" value="<?= $e($item['achievement_date'] ?? '') ?>" placeholder="e.g. October 2018 or 2017 - Present" required>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinical Scope & Description</label>
          <textarea name="description" class="form-control" placeholder="Clinical criteria met, skills demonstrated, or masterclass subjects..."><?= $e($item['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Verification URL / Registry Link (Optional)</label>
          <input type="url" name="verification_url" class="form-control" value="<?= $e($item['verification_url'] ?? '') ?>" placeholder="https://www.icoi.org/verify">
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($item['display_order'] ?? 0) ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Certificate Photograph or Document Scan (Optional)</label>
          <input type="file" name="certificate_image" class="form-control" accept="image/jpeg,image/png,image/webp">
          <?php if (!empty($item['certificate_image'])): ?>
            <div class="img-preview-box" style="width:140px;height:100px;margin-top:0.5rem;">
              <img src="<?= $asset($item['certificate_image']) ?>" alt="Certificate Preview">
            </div>
          <?php endif; ?>
        </div>

        <div class="form-group form-full">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($item['is_active']) || $item['is_active']) ? 'checked' : '' ?>>
            <span>Active & Visible on Public Website</span>
          </label>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= $url('/admin/achievements') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Record' : 'Save Record' ?></button>
      </div>
    </form>
  </div>
</div>
