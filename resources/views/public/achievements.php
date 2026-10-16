<?php
/** @var array $achievements */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="award" style="width:14px;height:14px;"></i> Verified Credentials</div>
    <h1>Training, Certifications & Achievements</h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;">
      Demonstrating clinical excellence through continuing international postgraduate training, surgical masterclasses, and peer-recognized honors.
    </p>
  </div>
</section>

<!-- Achievements Listing -->
<section class="section">
  <div class="container">
    <div class="awards-responsive-grid">
      <?php foreach ($achievements as $ach): ?>
        <div style="background:#fff;border:1px solid var(--border-color);border-radius:var(--radius-xl);overflow:hidden;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;transition:var(--transition);" onmouseover="this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.boxShadow='var(--shadow-sm)'">
          
          <?php if (!empty($ach['certificate_image'])): ?>
            <div style="height:200px;overflow:hidden;background:var(--bg-subtle);">
              <img src="<?= $asset($ach['certificate_image']) ?>" alt="<?= $e($ach['title']) ?>" style="width:100%;height:100%;object-fit:cover;">
            </div>
          <?php endif; ?>

          <div style="padding:1.75rem;display:flex;flex-direction:column;flex-grow:1;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
              <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--primary-teal);background:var(--teal-light);border:1px solid var(--teal-border);padding:0.25rem 0.65rem;border-radius:var(--radius-full);">
                <?= $e($ach['record_type']) ?>
              </span>
              <span style="font-size:0.85rem;color:var(--text-muted);font-weight:600;"><?= $e($ach['achievement_date']) ?></span>
            </div>

            <h3 style="font-size:1.25rem;color:var(--deep-navy);margin-bottom:0.5rem;line-height:1.3;"><?= $e($ach['title']) ?></h3>
            <div style="font-size:0.925rem;font-weight:600;color:var(--primary-teal);margin-bottom:1rem;"><?= $e($ach['organization']) ?></div>

            <?php if (!empty($ach['description'])): ?>
              <p style="font-size:0.925rem;color:var(--text-muted);line-height:1.6;margin-bottom:1.5rem;flex-grow:1;">
                <?= $e($ach['description']) ?>
              </p>
            <?php endif; ?>

            <?php if (!empty($ach['verification_url'])): ?>
              <div style="margin-top:auto;border-top:1px solid var(--border-light);padding-top:1rem;">
                <a href="<?= $e($ach['verification_url']) ?>" target="_blank" rel="noopener" style="font-size:0.875rem;font-weight:700;display:inline-flex;align-items:center;gap:0.4rem;">
                  Official Credential Verification <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
