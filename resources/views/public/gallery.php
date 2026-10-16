<?php
/** @var array $gallery */
/** @var string $currentCategory */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="camera" style="width:14px;height:14px;"></i> Visual Tour</div>
    <h1>Clinic & Work Gallery</h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;">
      Explore our surgical suites, sterilization center, high-resolution diagnostic equipment, and clinical symposiums.
    </p>
  </div>
</section>

<!-- Filter Tabs & Gallery Grid -->
<section class="section">
  <div class="container">
    <div class="gallery-filter-tabs">
      <a href="<?= $url('/gallery') ?>" class="filter-tab <?= ($currentCategory ?? 'All') === 'All' ? 'active' : '' ?>">All Photographs</a>
      <a href="<?= $url('/gallery?cat=Clinic+%26+Facilities') ?>" class="filter-tab <?= ($currentCategory ?? '') === 'Clinic & Facilities' ? 'active' : '' ?>">Clinic & Facilities</a>
      <a href="<?= $url('/gallery?cat=Doctor+at+Work') ?>" class="filter-tab <?= ($currentCategory ?? '') === 'Doctor at Work' ? 'active' : '' ?>">Doctor at Work</a>
      <a href="<?= $url('/gallery?cat=Equipment') ?>" class="filter-tab <?= ($currentCategory ?? '') === 'Equipment' ? 'active' : '' ?>">Advanced Equipment</a>
      <a href="<?= $url('/gallery?cat=Conferences+%26+Events') ?>" class="filter-tab <?= ($currentCategory ?? '') === 'Conferences & Events' ? 'active' : '' ?>">Conferences & Events</a>
    </div>

    <?php if (empty($gallery)): ?>
      <div style="text-align:center;padding:4rem;background:var(--bg-subtle);border-radius:var(--radius-xl);border:1px dashed var(--border-color);">
        <i data-lucide="image" style="width:48px;height:48px;color:var(--text-light);margin-bottom:1rem;"></i>
        <h3 style="color:var(--deep-navy);">No photographs in this category yet.</h3>
        <p style="color:var(--text-muted);">Please check back soon or browse other categories.</p>
      </div>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($gallery as $item): ?>
          <div class="gallery-item" data-caption="<?= $e($item['caption']) ?>">
            <?php 
              $imgSrc = !empty($item['image_path']) ? $asset($item['image_path']) : 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=800&q=80';
            ?>
            <img src="<?= $e($imgSrc) ?>" alt="<?= $e($item['alt_text']) ?>" loading="lazy">
            <div class="gallery-item-overlay">
              <span class="gallery-item-category"><?= $e($item['category']) ?></span>
              <span class="gallery-item-caption"><?= $e($item['caption']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
