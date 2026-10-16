<?php
/** @var ?array $blog */
/** @var array $categories */
$isEdit = !empty($blog);
$actionUrl = $isEdit ? $url('/admin/blogs/edit') : $url('/admin/blogs/create');
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><?= $isEdit ? 'Edit Article: ' . $e($blog['title']) : 'Write New Dental Article or Research Paper' ?></div>
    <a href="<?= $url('/admin/blogs') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Articles
    </a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" id="blogForm">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $blog['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <div class="form-group form-full">
          <label class="form-label">Article Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control" data-slug-source="blog_slug" value="<?= $e($blog['title'] ?? '') ?>" placeholder="e.g. The Science Behind Flossing: Why Brushing Cleans Only 60% of Tooth Surfaces" required style="font-size:1.15rem;font-weight:700;">
        </div>

        <div class="form-group">
          <label class="form-label">URL Slug</label>
          <input type="text" name="slug" id="blog_slug" class="form-control" value="<?= $e($blog['slug'] ?? '') ?>" placeholder="science-behind-flossing">
          <div class="form-help">Unique address for SEO. Auto-generated from title.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Article Category</label>
          <select name="category_id" class="form-control">
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($blog['category_id'] ?? null) == $cat['id'] ? 'selected' : '' ?>>
                <?= $e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Content Classification Type</label>
          <select name="content_type" id="contentTypeSelect" class="form-control">
            <option value="Educational Article" <?= ($blog['content_type'] ?? '') === 'Educational Article' ? 'selected' : '' ?>>
              Educational Article (Patient Hygiene, Procedure Guides, Tips)
            </option>
            <option value="Research Publication" <?= ($blog['content_type'] ?? '') === 'Research Publication' ? 'selected' : '' ?>>
              Research Publication (Peer-reviewed Papers, Laboratory Studies with DOI)
            </option>
            <option value="Case Study" <?= ($blog['content_type'] ?? '') === 'Case Study' ? 'selected' : '' ?>>
              Clinical Case Study (Surgical Observations & Clinical Follow-ups)
            </option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Author Name</label>
          <input type="text" name="author_name" class="form-control" value="<?= $e($blog['author_name'] ?? 'Dr. Amrit Jaishi') ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Short Summary / Excerpt</label>
          <textarea name="excerpt" class="form-control" style="min-height:80px;" placeholder="Engaging summary for search engines and article cards..."><?= $e($blog['excerpt'] ?? '') ?></textarea>
        </div>

        <!-- Rich Text Editor -->
        <div class="form-group form-full">
          <label class="form-label">Article Content (Rich Text Editor) <span style="color:#ef4444;">*</span></label>
          <textarea name="content_html" id="richEditor" class="form-control" style="min-height:400px;"><?= $blog['content_html'] ?? '' ?></textarea>
        </div>

        <!-- Featured Image -->
        <div class="form-group">
          <label class="form-label">Featured Header Image</label>
          <input type="file" name="featured_image" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="blogImgPreview">
          <div class="form-help">High-resolution image (1200x630px recommended).</div>
          <?php if (!empty($blog['featured_image'])): ?>
            <div class="img-preview-box" style="width:160px;height:100px;margin-top:0.5rem;">
              <img id="blogImgPreview" src="<?= $asset($blog['featured_image']) ?>" alt="">
            </div>
          <?php else: ?>
            <div class="img-preview-box" style="width:160px;height:100px;margin-top:0.5rem;display:none;">
              <img id="blogImgPreview" src="" alt="Preview">
            </div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Image Alternative Text (Accessibility)</label>
          <input type="text" name="image_alt" class="form-control" value="<?= $e($blog['image_alt'] ?? '') ?>" placeholder="Descriptive label for screen readers">
        </div>

        <!-- Specific Fields for Research Publication -->
        <div class="form-group form-full" id="researchFieldsGroup" style="border:1px solid #bae6fd;background:#f0f9ff;border-radius:8px;padding:1.5rem;margin-top:1rem;">
          <h4 style="color:#0369a1;font-size:1.05rem;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
            <i data-lucide="microscope" style="width:18px;height:18px;"></i> Peer-Reviewed Research Publication Metadata
          </h4>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Scientific Journal Name</label>
              <input type="text" name="journal_name" class="form-control" value="<?= $e($blog['journal_name'] ?? '') ?>" placeholder="e.g. International Journal of Prosthodontics">
            </div>
            <div class="form-group">
              <label class="form-label">Publication Year</label>
              <input type="text" name="publication_year" class="form-control" value="<?= $e($blog['publication_year'] ?? '') ?>" placeholder="e.g. 2023">
            </div>
            <div class="form-group">
              <label class="form-label">DOI (Digital Object Identifier)</label>
              <input type="text" name="doi" class="form-control" value="<?= $e($blog['doi'] ?? '') ?>" placeholder="e.g. 10.1016/j.dental.2023.08.012">
            </div>
            <div class="form-group">
              <label class="form-label">Publisher Link / External URL</label>
              <input type="url" name="publication_url" class="form-control" value="<?= $e($blog['publication_url'] ?? '') ?>" placeholder="https://doi.org/10.1016/...">
            </div>
          </div>
        </div>

        <!-- References -->
        <div class="form-group form-full">
          <label class="form-label">Scientific References & Citations (Optional)</label>
          <textarea name="references_text" class="form-control" style="min-height:90px;" placeholder="1. Brecx M, et al. J Clin Periodontol. 2019.&#10;2. Lang NP, Bartold PM. J Periodontol. 2018."><?= $e($blog['references_text'] ?? '') ?></textarea>
        </div>

        <!-- SEO Metadata -->
        <div class="form-group form-full" style="border-top:1px solid var(--admin-border);padding-top:1.5rem;margin-top:1rem;">
          <h4 style="font-size:1rem;color:var(--admin-navy);margin-bottom:0.75rem;">Search Engine Optimization (SEO)</h4>
        </div>

        <div class="form-group">
          <label class="form-label">Custom Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= $e($blog['meta_title'] ?? '') ?>" placeholder="Leave blank to use article title">
        </div>

        <div class="form-group">
          <label class="form-label">Custom Meta Description</label>
          <input type="text" name="meta_description" class="form-control" value="<?= $e($blog['meta_description'] ?? '') ?>" placeholder="Leave blank to use excerpt">
        </div>
      </div>

      <!-- Action Buttons: Direct Publishing or Draft -->
      <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--admin-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <div>
          <span style="font-size:0.875rem;color:var(--admin-text-muted);">
            Current Status: <strong><?= $e($blog['status'] ?? 'New') ?></strong>
          </span>
        </div>

        <div style="display:flex;gap:0.75rem;">
          <button type="submit" name="action" value="draft" class="btn btn-secondary">
            <i data-lucide="file-text" style="width:16px;height:16px;"></i> Save as Draft
          </button>
          
          <button type="submit" name="action" value="publish" class="btn btn-primary" style="background:#0d9488;">
            <i data-lucide="send" style="width:16px;height:16px;"></i> <?= ($isEdit && ($blog['status'] ?? '') === 'Published') ? 'Update & Keep Published' : 'Publish Immediately Live' ?>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- TinyMCE Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (typeof tinymce !== 'undefined') {
    tinymce.init({
      selector: '#richEditor',
      height: 480,
      menubar: true,
      plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
      ],
      toolbar: 'undo redo | blocks fontfamily fontsize | ' +
        'bold italic underline strikethrough | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'table link image blockquote | removeformat code help',
      content_style: 'body { font-family: Plus Jakarta Sans, system-ui, sans-serif; font-size:16px; line-height: 1.7; color: #1e293b; }',
      // Safe image upload endpoint
      images_upload_url: '<?= $url('/admin/blogs/upload-image') ?>',
      automatic_uploads: true,
      images_reuse_filename: true,
      branding: false
    });
  }

  // Toggle research fields
  const typeSelect = document.getElementById('contentTypeSelect');
  const researchBox = document.getElementById('researchFieldsGroup');
  function checkType() {
    if (typeSelect && researchBox) {
      if (typeSelect.value === 'Research Publication') {
        researchBox.style.display = 'block';
      } else {
        researchBox.style.display = 'none';
      }
    }
  }
  if (typeSelect) {
    typeSelect.addEventListener('change', checkType);
    checkType();
  }
});
</script>
