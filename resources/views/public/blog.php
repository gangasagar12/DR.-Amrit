<?php
/** @var array $blogs */
/** @var array $categories */
/** @var ?array $currentCategory */
/** @var ?string $currentType */
/** @var ?string $search */
/** @var int $currentPage */
/** @var int $totalPages */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="book-open" style="width:14px;height:14px;"></i> Dental Science & Patient Education</div>
    <h1>Dental Knowledge & Clinical Research</h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;">
      Evidence-based articles on oral hygiene, cosmetic treatments, implantology protocols, and scientific journal papers.
    </p>

    <!-- Search Form -->
    <div style="max-width:550px;margin:2rem auto 0 auto;">
      <form action="<?= $url('/blog') ?>" method="GET" style="display:flex;gap:0.5rem;">
        <input type="text" name="q" class="form-control" placeholder="Search by topic, condition, or keyword..." value="<?= $e($search ?? '') ?>" style="border-radius:var(--radius-full);padding-left:1.5rem;">
        <?php if ($currentCategory): ?>
          <input type="hidden" name="category" value="<?= $e($currentCategory['slug']) ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-primary" style="border-radius:var(--radius-full);padding:0 1.5rem;">
          <i data-lucide="search" style="width:18px;height:18px;"></i> Search
        </button>
      </form>
    </div>
  </div>
</section>

<!-- Content Filtering & Articles Grid -->
<section class="section">
  <div class="container">
    <!-- Category & Content Type Filter Pills -->
    <div style="display:flex;justify-content:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:3rem;">
      <a href="<?= $url('/blog') ?>" class="filter-tab <?= (!$currentCategory && !$currentType) ? 'active' : '' ?>">
        All Publications
      </a>
      <a href="<?= $url('/blog?type=Educational+Article') ?>" class="filter-tab <?= ($currentType === 'Educational Article') ? 'active' : '' ?>">
        <i data-lucide="book" style="width:14px;height:14px;display:inline;"></i> Educational Guides
      </a>
      <a href="<?= $url('/blog?type=Research+Publication') ?>" class="filter-tab <?= ($currentType === 'Research Publication') ? 'active' : '' ?>">
        <i data-lucide="microscope" style="width:14px;height:14px;display:inline;"></i> Research Papers (DOI)
      </a>

      <?php foreach ($categories as $cat): ?>
        <a href="<?= $url('/blog?category=' . $cat['slug']) ?>" class="filter-tab <?= ($currentCategory && $currentCategory['id'] == $cat['id']) ? 'active' : '' ?>">
          <?= $e($cat['name']) ?> (<?= $cat['blog_count'] ?>)
        </a>
      <?php endforeach; ?>
    </div>

    <!-- Active Search Filter Badge -->
    <?php if ($search): ?>
      <div style="display:flex;justify-content:space-between;align-items:center;background:var(--bg-subtle);padding:1rem 1.5rem;border-radius:var(--radius-md);margin-bottom:2rem;border:1px solid var(--border-color);">
        <span>Showing results for: <strong>"<?= $e($search) ?>"</strong> (<?= count($blogs) ?> found)</span>
        <a href="<?= $url('/blog') ?>" style="font-size:0.875rem;font-weight:600;color:var(--danger);"><i data-lucide="x" style="width:14px;height:14px;display:inline;"></i> Clear Filter</a>
      </div>
    <?php endif; ?>

    <!-- Articles Grid -->
    <?php if (empty($blogs)): ?>
      <div style="text-align:center;padding:5rem 2rem;background:var(--bg-subtle);border-radius:var(--radius-xl);border:1px dashed var(--border-color);max-width:700px;margin:0 auto;">
        <i data-lucide="book-x" style="width:54px;height:54px;color:var(--text-light);margin-bottom:1rem;"></i>
        <h3 style="color:var(--deep-navy);margin-bottom:0.5rem;">No Published Articles Found</h3>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">No dental articles match the selected category or search filter.</p>
        <a href="<?= $url('/blog') ?>" class="btn btn-outline-teal">View All Articles</a>
      </div>
    <?php else: ?>
      <div class="blog-grid">
        <?php foreach ($blogs as $blog): ?>
          <article class="blog-card">
            <div class="blog-card-img-wrap">
              <?php 
                $imgSrc = !empty($blog['featured_image']) ? $asset($blog['featured_image']) : 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=600&q=80';
              ?>
              <img src="<?= $e($imgSrc) ?>" alt="<?= $e($blog['image_alt'] ?: $blog['title']) ?>" loading="lazy">
              <span class="blog-type-tag <?= $blog['content_type'] === 'Research Publication' ? 'blog-type-research' : '' ?>">
                <?= $e($blog['content_type']) ?>
              </span>
            </div>

            <div class="blog-card-body">
              <div class="blog-meta-header">
                <span class="blog-meta-category"><?= $e($blog['category_name'] ?? 'Oral Health') ?></span>
                <span>&bull;</span>
                <span><?= date('M d, Y', strtotime($blog['published_at'] ?: $blog['created_at'])) ?></span>
              </div>

              <h3 class="blog-card-title">
                <a href="<?= $url('/blog/' . $blog['slug']) ?>"><?= $e($blog['title']) ?></a>
              </h3>

              <p class="blog-card-excerpt"><?= $e($blog['excerpt']) ?></p>

              <?php if ($blog['content_type'] === 'Research Publication' && !empty($blog['journal_name'])): ?>
                <div style="font-size:0.8rem;background:var(--bg-subtle);padding:0.4rem 0.65rem;border-radius:4px;margin-bottom:1rem;color:var(--navy-slate);">
                  <i data-lucide="file-text" style="width:13px;height:13px;display:inline;color:var(--primary-teal);"></i>
                  <strong>Journal:</strong> <?= $e($blog['journal_name']) ?> (<?= $e($blog['publication_year'] ?? '') ?>)
                </div>
              <?php endif; ?>

              <div class="blog-card-footer">
                <span style="color:var(--text-muted);font-size:0.85rem;"><?= $e($blog['author_name'] ?? 'Dr. Amrit Jaishi') ?></span>
                <a href="<?= $url('/blog/' . $blog['slug']) ?>" style="font-weight:700;display:inline-flex;align-items:center;gap:0.3rem;">
                  Read Article <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
        <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:3.5rem;">
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="<?= $url('/blog?page=' . $p . ($currentCategory ? '&category=' . $currentCategory['slug'] : '') . ($search ? '&q=' . urlencode($search) : '')) ?>" 
               class="btn <?= $p === $currentPage ? 'btn-primary' : 'btn-secondary' ?>" 
               style="min-width:42px;height:42px;padding:0;border-radius:var(--radius-sm);">
              <?= $p ?>
            </a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
