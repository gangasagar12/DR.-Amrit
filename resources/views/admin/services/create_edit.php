<?php
/** @var ?array $service */
$isEdit = !empty($service);
$actionUrl = $isEdit ? $url('/admin/services/edit') : $url('/admin/services/create');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><?= $isEdit ? 'Edit Dental Service: ' . $e($service['name']) : 'Add New Dental Service' ?></div>
    <a href="<?= $url('/admin/services') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Services
    </a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $service['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Service / Treatment Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="name" class="form-control" data-slug-source="service_slug" value="<?= $e($service['name'] ?? '') ?>" placeholder="e.g. Advanced Dental Implants" required>
        </div>

        <div class="form-group">
          <label class="form-label">URL Slug</label>
          <input type="text" name="slug" id="service_slug" class="form-control" value="<?= $e($service['slug'] ?? '') ?>" placeholder="advanced-dental-implants">
          <div class="form-help">Auto-generated from service name. Leave as is or customize for SEO.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Specialty Category</label>
          <input type="text" name="category" class="form-control" value="<?= $e($service['category'] ?? 'General Dentistry') ?>" placeholder="e.g. Implantology, Cosmetic Dentistry, Endodontics">
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($service['display_order'] ?? 0) ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Short Summary (For Cards & Previews) <span style="color:#ef4444;">*</span></label>
          <input type="text" name="short_description" class="form-control" value="<?= $e($service['short_description'] ?? '') ?>" placeholder="1-2 sentences summarizing the procedure..." required>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Detailed Clinical Description & Overview</label>
          <textarea name="full_description" class="form-control" style="min-height:160px;" placeholder="Comprehensive explanation of the treatment, clinical materials, and indications..."><?= $e($service['full_description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Clinical Benefits (One benefit per line)</label>
          <textarea name="benefits" class="form-control" style="min-height:120px;" placeholder="Prevents bone loss and facial sagging&#10;Permanent 98% clinical success rate&#10;Restores full chewing power"><?= $e($service['benefits'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Step-by-Step Procedure Steps (One step per line)</label>
          <textarea name="procedure_steps" class="form-control" style="min-height:120px;" placeholder="Step 1: 3D CBCT Digital Diagnostics & Consultation&#10;Step 2: Painless Guided Surgical Fixture Placement&#10;Step 3: Osseointegration healing period&#10;Step 4: Custom Precision Ceramic Crown Attachment"><?= $e($service['procedure_steps'] ?? '') ?></textarea>
        </div>

        <!-- Featured Image -->
        <div class="form-group form-full">
          <label class="form-label">Featured Service Photograph</label>
          <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="serviceImgPreview">
          <div class="form-help">Accepted formats: JPG, PNG, WebP. High-quality treatment or clinical photo.</div>

          <div class="img-preview-box" style="width:200px;height:120px;margin-top:0.75rem;">
            <?php 
              $sImg = !empty($service['image']) ? $asset($service['image']) : 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=400&q=80';
            ?>
            <img id="serviceImgPreview" src="<?= $e($sImg) ?>" alt="Service Image Preview">
          </div>
        </div>

        <!-- Status Toggles -->
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($service['is_active']) || $service['is_active']) ? 'checked' : '' ?>>
            <span>Active & Visible on Public Website</span>
          </label>
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="is_featured" value="1" <?= (!empty($service['is_featured'])) ? 'checked' : '' ?>>
            <span>Feature on Homepage Specialties Grid</span>
          </label>
        </div>

        <!-- SEO Metadata -->
        <div class="form-group form-full" style="border-top:1px solid var(--admin-border);padding-top:1.5rem;margin-top:0.5rem;">
          <h4 style="font-size:1rem;color:var(--admin-navy);margin-bottom:0.85rem;">SEO Metadata (Optional)</h4>
        </div>

        <div class="form-group">
          <label class="form-label">SEO Page Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= $e($service['meta_title'] ?? '') ?>" placeholder="e.g. Dental Implants | Dr. Sarah Jenkins">
        </div>

        <div class="form-group">
          <label class="form-label">SEO Meta Description</label>
          <input type="text" name="meta_description" class="form-control" value="<?= $e($service['meta_description'] ?? '') ?>" placeholder="Brief description for search engines...">
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= $url('/admin/services') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i data-lucide="save" style="width:16px;height:16px;"></i> <?= $isEdit ? 'Update Dental Service' : 'Save & Publish Service' ?>
        </button>
      </div>
    </form>
  </div>
</div>
