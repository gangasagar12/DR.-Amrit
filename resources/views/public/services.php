<?php
/** @var array $services */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="activity" style="width:14px;height:14px;"></i> Dental Specialties</div>
    <h1>Specialized Dental Treatments</h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;">
      Advanced clinical care designed for optimal oral health, aesthetics, and long-term tooth preservation.
    </p>
  </div>
</section>

<!-- Services Grid -->
<section class="section">
  <div class="container">
    <div class="services-grid">
      <?php foreach ($services as $service): ?>
        <div class="service-card">
          <div class="service-image-box">
            <?php 
              $imgSrc = !empty($service['image']) ? $asset($service['image']) : 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=600&q=80';
            ?>
            <img src="<?= $e($imgSrc) ?>" alt="<?= $e($service['name']) ?>" loading="lazy">
            <span class="service-badge"><?= $e($service['category']) ?></span>
          </div>

          <div class="service-content">
            <h3 class="service-title">
              <a href="<?= $url('/services/' . $service['slug']) ?>"><?= $e($service['name']) ?></a>
            </h3>
            
            <p class="service-excerpt"><?= $e($service['short_description']) ?></p>

            <?php if (!empty($service['benefits'])): ?>
              <div style="background:var(--bg-subtle);padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.25rem;">
                <div style="font-size:0.775rem;font-weight:700;color:var(--deep-navy);text-transform:uppercase;margin-bottom:0.35rem;">
                  Key Clinical Benefits:
                </div>
                <div style="font-size:0.85rem;color:var(--text-muted);line-height:1.4;">
                  <?php 
                    $lines = array_filter(explode("\n", $service['benefits']));
                    echo $e(implode(' &bull; ', array_slice($lines, 0, 2)));
                  ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="service-footer">
              <a href="<?= $url('/services/' . $service['slug']) ?>" style="font-weight:700;font-size:0.875rem;display:inline-flex;align-items:center;gap:0.35rem;">
                Procedure Details <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
              </a>

              <button type="button" class="btn btn-primary btn-sm open-appointment-modal" data-service-id="<?= $service['id'] ?>">
                <i data-lucide="calendar" style="width:14px;height:14px;"></i> Book Service
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Bottom CTA -->
<section class="section section-subtle">
  <div class="container text-center">
    <h2 style="margin-bottom:1rem;">Not Sure Which Treatment You Need?</h2>
    <p style="max-width:600px;margin:0 auto 2rem auto;font-size:1.05rem;">
      Schedule an initial comprehensive checkup with <?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> at Dhangadhi Sanjeevani Hospital to evaluate your dental health and discuss tailored care.
    </p>
    <button type="button" class="btn btn-pill-cyan open-appointment-modal">
      <span>Book Consultation</span>
      <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
    </button>
  </div>
</section>
