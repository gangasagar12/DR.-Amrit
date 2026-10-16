<?php
/** @var ?array $item */
$isEdit = !empty($item);
$actionUrl = $isEdit ? $url('/admin/qualifications/' . $item['id'] . '/update') : $url('/admin/qualifications');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><?= $isEdit ? 'Edit Qualification: ' . $e($item['degree']) : 'Add Educational Milestone' ?></div>
    <a href="<?= $url('/admin/qualifications') ?>" class="btn btn-secondary btn-sm">Back</a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST">
      <?= $csrf() ?>

      <div class="form-grid">
        <div class="form-group form-full">
          <label class="form-label">Degree / Qualification Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="degree" class="form-control" value="<?= $e($item['degree'] ?? '') ?>" placeholder="e.g. Bachelor of Dental Surgery (BDS) or Registered Dental Surgeon" required>
        </div>

        <div class="form-group">
          <label class="form-label">Institution / University / Council <span style="color:#ef4444;">*</span></label>
          <input type="text" name="institution" class="form-control" value="<?= $e($item['institution'] ?? '') ?>" placeholder="e.g. B.P. Koirala Institute of Health Sciences (BPKIHS)" required>
        </div>

        <div class="form-group">
          <label class="form-label">Location / City</label>
          <input type="text" name="location" class="form-control" value="<?= $e($item['location'] ?? '') ?>" placeholder="e.g. Dharan, Sunsari, Nepal">
        </div>

        <div class="form-group">
          <label class="form-label">Start Year</label>
          <input type="text" name="start_year" class="form-control" value="<?= $e($item['start_year'] ?? '') ?>" placeholder="e.g. 2014">
        </div>

        <div class="form-group">
          <label class="form-label">Completion Year / Period <span style="color:#ef4444;">*</span></label>
          <input type="text" name="completion_year" class="form-control" value="<?= $e($item['completion_year'] ?? '') ?>" placeholder="e.g. 2019 or Present" required>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinical Scope & Description</label>
          <textarea name="description" class="form-control" rows="4" placeholder="Detailed clinical rotations, curriculum focus, licensure scope, or workshop achievements..."><?= $e($item['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order (1 = Top / First)</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($item['display_order'] ?? 0) ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Publication Status</label>
          <select name="is_published" class="form-control">
            <option value="1" <?= (!isset($item['is_published']) || $item['is_published'] == 1) ? 'selected' : '' ?>>Published on Website</option>
            <option value="0" <?= (isset($item['is_published']) && $item['is_published'] == 0) ? 'selected' : '' ?>>Draft / Hidden</option>
          </select>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;gap:1rem;">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="check" style="width:16px;height:16px;"></i>
          <?= $isEdit ? 'Save Changes' : 'Create Qualification' ?>
        </button>
        <a href="<?= $url('/admin/qualifications') ?>" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
