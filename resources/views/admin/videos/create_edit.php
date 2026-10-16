<?php
/** @var ?array $item */
/** @var array $categories */
$isEdit = !empty($item);
$actionUrl = $isEdit ? $url('/admin/videos/' . $item['id'] . '/update') : $url('/admin/videos/create');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <div class="admin-card-title"><?= $isEdit ? 'Edit Dental Video: ' . $e($item['title']) : 'Add New Dental Video' ?></div>
      <p style="font-size:0.875rem;color:#64748b;margin-top:0.25rem;">
        Configure video source (YouTube embed or MP4 upload), custom thumbnail, educational summary, and categories.
      </p>
    </div>
    <a href="<?= $url('/admin/videos') ?>" class="btn btn-secondary btn-sm">Back to Videos</a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $item['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <!-- Title -->
        <div class="form-group form-full">
          <label class="form-label">Video Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control" value="<?= $e($item['title'] ?? '') ?>" placeholder="e.g. Proper Tooth Brushing Technique for Gum Protection" required>
        </div>

        <!-- Category -->
        <div class="form-group">
          <label class="form-label">Video Category</label>
          <select name="category_id" class="form-control">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($item['category_id'] ?? null) == $cat['id'] ? 'selected' : '' ?>>
                <?= $e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Video Source Type -->
        <div class="form-group">
          <label class="form-label">Video Source Type <span style="color:#ef4444;">*</span></label>
          <select name="video_type" id="videoTypeSelect" class="form-control">
            <option value="youtube" <?= ($item['video_type'] ?? 'youtube') === 'youtube' ? 'selected' : '' ?>>YouTube URL (Recommended)</option>
            <option value="vimeo" <?= ($item['video_type'] ?? '') === 'vimeo' ? 'selected' : '' ?>>Vimeo URL</option>
            <option value="external" <?= ($item['video_type'] ?? '') === 'external' ? 'selected' : '' ?>>External Video / Stream Link</option>
            <option value="upload" <?= ($item['video_type'] ?? '') === 'upload' ? 'selected' : '' ?>>Upload MP4 File</option>
          </select>
        </div>

        <!-- Video URL (for YouTube / Vimeo / External) -->
        <div class="form-group form-full" id="videoUrlGroup">
          <label class="form-label">Video URL <span style="color:#ef4444;">*</span></label>
          <input type="url" name="video_url" class="form-control" value="<?= $e($item['video_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=... or https://vimeo.com/...">
          <small style="color:#64748b;margin-top:0.25rem;display:block;">Paste standard YouTube video link (e.g. https://www.youtube.com/watch?v=XXXX or https://youtu.be/XXXX).</small>
        </div>

        <!-- Local Video Upload (for MP4 upload) -->
        <div class="form-group form-full" id="videoUploadGroup" style="display:none;">
          <label class="form-label">Upload Video File (.mp4, .webm)</label>
          <input type="file" name="video_file" class="form-control" accept="video/mp4,video/webm">
          <?php if (!empty($item['video_file'])): ?>
            <div style="margin-top:0.5rem;font-size:0.875rem;color:#0284c7;">
              Current file: <?= $e($item['video_file']) ?>
            </div>
          <?php endif; ?>
          <small style="color:#64748b;margin-top:0.25rem;display:block;">Maximum upload size defined by PHP configuration. Fast streaming is optimized with MP4 H.264.</small>
        </div>

        <!-- Custom Thumbnail Upload -->
        <div class="form-group">
          <label class="form-label">Preview Thumbnail Image</label>
          <input type="file" name="thumbnail" class="form-control" accept="image/*">
          <?php if (!empty($item['thumbnail'])): ?>
            <div style="margin-top:0.5rem;">
              <img src="<?= $asset($item['thumbnail']) ?>" alt="Thumbnail preview" style="width:160px;aspect-ratio:16/9;object-fit:cover;border-radius:6px;border:1px solid #cbd5e1;">
            </div>
          <?php endif; ?>
          <small style="color:#64748b;margin-top:0.25rem;display:block;">Recommended 16:9 ratio (e.g. 1280x720px JPG or WebP).</small>
        </div>

        <!-- Duration -->
        <div class="form-group">
          <label class="form-label">Duration (mm:ss)</label>
          <input type="text" name="duration" class="form-control" value="<?= $e($item['duration'] ?? '') ?>" placeholder="e.g. 04:30">
        </div>

        <!-- Display Order -->
        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($item['display_order'] ?? 0) ?>">
        </div>

        <!-- Publication Status -->
        <div class="form-group">
          <label class="form-label">Publication Status</label>
          <select name="status" class="form-control">
            <option value="published" <?= ($item['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published in Video Gallery</option>
            <option value="draft" <?= ($item['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft / Hidden</option>
          </select>
        </div>

        <!-- Publication Date -->
        <div class="form-group">
          <label class="form-label">Publication Date</label>
          <input type="datetime-local" name="published_at" class="form-control" value="<?= !empty($item['published_at']) ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : date('Y-m-d\TH:i') ?>">
        </div>

        <!-- Description -->
        <div class="form-group form-full">
          <label class="form-label">Educational Summary / Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="A short 1-2 sentence overview of the dental procedure or oral hygiene advice..."><?= $e($item['description'] ?? '') ?></textarea>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;gap:1rem;">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="check" style="width:16px;height:16px;"></i>
          <?= $isEdit ? 'Save Video Changes' : 'Publish Video' ?>
        </button>
        <a href="<?= $url('/admin/videos') ?>" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('videoTypeSelect');
  const urlGroup = document.getElementById('videoUrlGroup');
  const uploadGroup = document.getElementById('videoUploadGroup');

  function updateFields() {
    if (select.value === 'upload') {
      urlGroup.style.display = 'none';
      uploadGroup.style.display = 'block';
    } else {
      urlGroup.style.display = 'block';
      uploadGroup.style.display = 'none';
    }
  }

  select.addEventListener('change', updateFields);
  updateFields();
});
</script>
