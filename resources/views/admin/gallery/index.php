<?php
/** @var array $gallery */
?>

<!-- Upload New Photo Form -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Upload New Photograph to Gallery</div>
  </div>

  <div class="admin-card-body">
    <form action="<?= $url('/admin/gallery/upload') ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Select Photograph File <span style="color:#ef4444;">*</span></label>
          <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" required data-preview="newGalleryPreview">
          <div class="form-help">Formats: JPG, PNG, WebP. Max size: 5MB.</div>

          <div class="img-preview-box" style="width:140px;height:100px;display:none;margin-top:0.5rem;">
            <img id="newGalleryPreview" src="" alt="Preview">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Category <span style="color:#ef4444;">*</span></label>
          <select name="category" class="form-control" required>
            <option value="Clinic & Facilities">Clinic & Facilities</option>
            <option value="Doctor at Work">Doctor at Work</option>
            <option value="Equipment">Equipment & Technology</option>
            <option value="Conferences & Events">Conferences & Events</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Caption / Description</label>
          <input type="text" name="caption" class="form-control" placeholder="e.g. Modern treatment operatory with ambient lighting" required>
        </div>

        <div class="form-group">
          <label class="form-label">Image Alt Text (For Accessibility & SEO)</label>
          <input type="text" name="alt_text" class="form-control" placeholder="e.g. Dental surgical microscope operatory setup">
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="0">
        </div>
      </div>

      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="upload" style="width:16px;height:16px;"></i> Upload & Publish to Gallery
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Existing Gallery Table / Grid -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Existing Gallery Photographs (<?= count($gallery) ?>)</div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Preview</th>
          <th>Caption</th>
          <th>Category</th>
          <th>Alt Text</th>
          <th>Order</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($gallery)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2rem;">No photographs uploaded yet.</td></tr>
        <?php else: ?>
          <?php foreach ($gallery as $item): ?>
            <tr>
              <td>
                <img src="<?= $asset($item['image_path']) ?>" alt="" style="width:65px;height:50px;border-radius:6px;object-fit:cover;">
              </td>
              <td><strong><?= $e($item['caption']) ?></strong></td>
              <td><span class="status-badge badge-pending"><?= $e($item['category']) ?></span></td>
              <td style="font-size:0.8rem;color:var(--admin-text-muted);"><?= $e($item['alt_text']) ?></td>
              <td><?= (int)$item['display_order'] ?></td>
              <td>
                <a href="<?= $url('/admin/gallery/delete?id=' . $item['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this image from gallery?">
                  <i data-lucide="trash-2" style="width:13px;height:13px;"></i> Delete
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
