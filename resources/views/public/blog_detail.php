<?php
/** @var array $blog */
/** @var int $readingTime */
/** @var array $related */
/** @var array $categories */
?>

<!-- Breadcrumbs Header -->
<section class="section section-subtle" style="padding: 2.5rem 0;">
  <div class="container container-narrow">
    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--text-muted);margin-bottom:1.25rem;flex-wrap:wrap;">
      <a href="<?= $url('/') ?>">Home</a>
      <span>/</span>
      <a href="<?= $url('/blog') ?>">Dental Knowledge</a>
      <span>/</span>
      <?php if (!empty($blog['category_name'])): ?>
        <a href="<?= $url('/blog?category=' . $blog['category_slug']) ?>"><?= $e($blog['category_name']) ?></a>
        <span>/</span>
      <?php endif; ?>
      <span style="color:var(--deep-navy);font-weight:600;"><?= $e($blog['title']) ?></span>
    </div>

    <div style="margin-bottom:1rem;">
      <span class="section-tag"><?= $e($blog['content_type']) ?></span>
      <?php if (!empty($blog['category_name'])): ?>
        <span class="section-tag" style="background:#fff;border-color:var(--border-color);color:var(--navy-slate);">
          <?= $e($blog['category_name']) ?>
        </span>
      <?php endif; ?>
    </div>

    <h1 style="font-size:clamp(2rem, 4vw, 2.85rem);line-height:1.25;margin-bottom:1.25rem;color:var(--deep-navy);">
      <?= $e($blog['title']) ?>
    </h1>

    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;color:var(--text-muted);font-size:0.925rem;border-top:1px solid var(--border-color);border-bottom:1px solid var(--border-color);padding:0.85rem 0;">
      <div style="display:flex;align-items:center;gap:0.4rem;">
        <i data-lucide="user" style="width:16px;height:16px;color:var(--primary-teal);"></i>
        <strong style="color:var(--deep-navy);"><?= $e($blog['author_name']) ?></strong>
      </div>
      <div style="display:flex;align-items:center;gap:0.4rem;">
        <i data-lucide="calendar" style="width:16px;height:16px;"></i>
        <span><?= date('F j, Y', strtotime($blog['published_at'] ?: $blog['created_at'])) ?></span>
      </div>
      <div style="display:flex;align-items:center;gap:0.4rem;">
        <i data-lucide="clock" style="width:16px;height:16px;"></i>
        <span><?= $readingTime ?> min read</span>
      </div>
    </div>
  </div>
</section>

<!-- Main Article Section -->
<section class="section" style="padding-top: 2rem;">
  <div class="container container-narrow">
    <!-- Featured Image -->
    <?php if (!empty($blog['featured_image'])): ?>
      <div style="border-radius:var(--radius-xl);overflow:hidden;margin-bottom:2.5rem;box-shadow:var(--shadow-md);">
        <img src="<?= $asset($blog['featured_image']) ?>" alt="<?= $e($blog['image_alt'] ?: $blog['title']) ?>" style="width:100%;max-height:480px;object-fit:cover;">
      </div>
    <?php endif; ?>

    <!-- Research Citations Box if Research Paper -->
    <?php if ($blog['content_type'] === 'Research Publication' && (!empty($blog['doi']) || !empty($blog['journal_name']))): ?>
      <div style="background:#f0f9ff;border:1px solid #bae6fd;border-left:4px solid var(--info);border-radius:var(--radius-md);padding:1.5rem;margin-bottom:2.5rem;">
        <h4 style="color:#0369a1;font-size:1.1rem;margin-bottom:0.65rem;display:flex;align-items:center;gap:0.5rem;">
          <i data-lucide="file-check" style="width:18px;height:18px;"></i> Peer-Reviewed Publication Metadata
        </h4>
        <div style="font-size:0.925rem;color:#0c4a6e;display:flex;flex-direction:column;gap:0.35rem;">
          <?php if (!empty($blog['journal_name'])): ?>
            <div><strong>Journal:</strong> <?= $e($blog['journal_name']) ?> (<?= $e($blog['publication_year'] ?? '') ?>)</div>
          <?php endif; ?>
          <?php if (!empty($blog['doi'])): ?>
            <div>
              <strong>Digital Object Identifier (DOI):</strong> 
              <a href="https://doi.org/<?= $e($blog['doi']) ?>" target="_blank" rel="noopener" style="color:var(--primary-teal);font-weight:700;">
                https://doi.org/<?= $e($blog['doi']) ?>
              </a>
            </div>
          <?php endif; ?>
          <?php if (!empty($blog['publication_url'])): ?>
            <div>
              <strong>Original Article:</strong> 
              <a href="<?= $e($blog['publication_url']) ?>" target="_blank" rel="noopener" style="color:var(--primary-teal);">
                View on Publisher Website <i data-lucide="external-link" style="width:12px;height:12px;display:inline;"></i>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Article Content -->
    <article class="article-body">
      <?= $blog['content_html'] ?>
    </article>

    <!-- References Section -->
    <?php if (!empty($blog['references_text'])): ?>
      <div style="margin-top:3rem;background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:1.75rem;">
        <h4 style="font-size:1.1rem;color:var(--deep-navy);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
          <i data-lucide="bookmark" style="width:18px;height:18px;color:var(--primary-teal);"></i> Scientific References & Sources
        </h4>
        <div style="font-size:0.875rem;color:var(--text-muted);line-height:1.7;">
          <?= nl2br($e($blog['references_text'])) ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Medical Education Disclaimer -->
    <div class="medical-disclaimer">
      <div style="display:flex;gap:0.75rem;align-items:flex-start;">
        <i data-lucide="alert-triangle" style="width:20px;height:20px;color:#d97706;flex-shrink:0;margin-top:2px;"></i>
        <div>
          <strong>Medical Education Disclaimer:</strong> This publication is written for patient education and informational purposes only. It is not intended as a substitute for professional clinical diagnosis or personalized medical advice. Always consult a qualified dental surgeon regarding any specific oral conditions or treatment plans.
        </div>
      </div>
    </div>

    <!-- Social Share Buttons -->
    <?php 
      $currentFullUrl = rawurlencode($url('/blog/' . $blog['slug']));
      $shareTitle = rawurlencode($blog['title']);
    ?>
    <div style="margin-top:2.5rem;padding:1.5rem 0;border-top:1px solid var(--border-color);border-bottom:1px solid var(--border-color);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
      <span style="font-weight:700;color:var(--deep-navy);font-size:0.95rem;">Share this publication:</span>
      <div style="display:flex;gap:0.65rem;">
        <a href="https://api.whatsapp.com/send?text=<?= $shareTitle ?>%20<?= $currentFullUrl ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="background:#25d366;color:#fff !important;border:none;">
          <i data-lucide="message-circle" style="width:14px;height:14px;"></i> WhatsApp
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $currentFullUrl ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="background:#0a66c2;color:#fff !important;border:none;">
          <i data-lucide="linkedin" style="width:14px;height:14px;"></i> LinkedIn
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?= $shareTitle ?>&url=<?= $currentFullUrl ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="background:#000;color:#fff !important;border:none;">
          <i data-lucide="share-2" style="width:14px;height:14px;"></i> X / Twitter
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $currentFullUrl ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="background:#1877f2;color:#fff !important;border:none;">
          <i data-lucide="facebook" style="width:14px;height:14px;"></i> Facebook
        </a>
      </div>
    </div>

    <!-- Author Card -->
    <div style="margin-top:2.5rem;background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-xl);padding:2rem;display:flex;gap:1.75rem;align-items:center;flex-wrap:wrap;">
      <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;border:3px solid #fff;box-shadow:var(--shadow-md);flex-shrink:0;">
        <img src="<?= $asset($doctor_profile()['profile_image'] ?? 'uploads/profile/amrit_jaishi.jpg') ?>" alt="<?= $e($blog['author_name'] ?? 'Dr. Amrit Jaishi') ?>" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div style="flex-grow:1;">
        <h4 style="font-size:1.15rem;margin-bottom:0.25rem;color:var(--deep-navy);"><?= $e($blog['author_name'] ?? 'Dr. Amrit Jaishi') ?></h4>
        <div style="font-size:0.85rem;color:var(--primary-teal);font-weight:600;margin-bottom:0.5rem;"><?= $e($doctor_profile()['professional_title'] ?? 'BDS (BPKIHS) - Oral & Dental Surgeon') ?></div>
        <p style="font-size:0.9rem;color:var(--text-muted);margin:0;">
          Serving as Oral and Dental Surgeon at Dhangadhi Sanjeevani Hospital with focus on restorative dentistry, painless root canals, and emergency dental trauma care.
        </p>
      </div>
    </div>

    <!-- Related Articles -->
    <?php if (!empty($related)): ?>
      <div style="margin-top:4rem;">
        <h3 style="font-size:1.5rem;color:var(--deep-navy);margin-bottom:1.5rem;">Related Publications & Articles</h3>
        <div class="blog-grid" style="grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));">
          <?php foreach ($related as $rBlog): ?>
            <div class="blog-card">
              <div class="blog-card-img-wrap" style="height:160px;">
                <?php 
                  $rImg = !empty($rBlog['featured_image']) ? $asset($rBlog['featured_image']) : 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=600&q=80';
                ?>
                <img src="<?= $e($rImg) ?>" alt="<?= $e($rBlog['title']) ?>" loading="lazy">
              </div>
              <div class="blog-card-body" style="padding:1rem;">
                <h4 style="font-size:1.05rem;line-height:1.3;margin-bottom:0.5rem;">
                  <a href="<?= $url('/blog/' . $rBlog['slug']) ?>"><?= $e($rBlog['title']) ?></a>
                </h4>
                <div style="font-size:0.8rem;color:var(--text-muted);"><?= date('M d, Y', strtotime($rBlog['published_at'] ?: $rBlog['created_at'])) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div style="margin-top:3.5rem;text-align:center;">
      <a href="<?= $url('/blog') ?>" class="btn btn-secondary">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Return to All Publications
      </a>
    </div>
  </div>
</section>
