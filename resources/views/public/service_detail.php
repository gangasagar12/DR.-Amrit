<?php
/** @var array $service */
/** @var array $allServices */
/** @var array $clinics */
?>

<!-- Breadcrumbs Header -->
<section class="section section-subtle" style="padding: 2.5rem 0;">
  <div class="container">
    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--text-muted);margin-bottom:1rem;flex-wrap:wrap;">
      <a href="<?= $url('/') ?>">Home</a>
      <span>/</span>
      <a href="<?= $url('/services') ?>">Specialties & Services</a>
      <span>/</span>
      <span style="color:var(--deep-navy);font-weight:600;"><?= $e($service['name']) ?></span>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1.5rem;">
      <div>
        <span class="section-tag"><?= $e($service['category']) ?></span>
        <h1 style="font-size:clamp(1.85rem, 3.5vw, 2.75rem);margin-top:0.4rem;"><?= $e($service['name']) ?></h1>
        <p style="font-size:1.1rem;color:var(--text-muted);max-width:750px;"><?= $e($service['short_description']) ?></p>
      </div>

      <button type="button" class="btn btn-primary open-appointment-modal" data-service-id="<?= $service['id'] ?>">
        <i data-lucide="calendar" style="width:15px;height:15px;"></i> Book Procedure
      </button>
    </div>
  </div>
</section>

<!-- Main Detail Content -->
<section class="section">
  <div class="container">
    <div class="service-detail-grid">
      <!-- Content Area -->
      <div>
        <?php if (!empty($service['image'])): ?>
          <div style="border-radius:var(--radius-xl);overflow:hidden;margin-bottom:2.5rem;box-shadow:var(--shadow-md);">
            <img src="<?= $asset($service['image']) ?>" alt="<?= $e($service['name']) ?>" style="width:100%;height:380px;object-fit:cover;">
          </div>
        <?php endif; ?>

        <!-- Detailed Description -->
        <div class="article-body">
          <h2 style="font-size:1.5rem;margin-bottom:1rem;color:var(--deep-navy);">Clinical Overview</h2>
          <?= nl2br($service['full_description']) ?>
        </div>

        <!-- Clinical Benefits -->
        <?php if (!empty($service['benefits'])): ?>
          <div style="margin-top:3rem;background:var(--teal-light);border:1px solid var(--teal-border);border-radius:var(--radius-lg);padding:2rem;">
            <h3 style="color:var(--teal-dark);font-size:1.35rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
              <i data-lucide="shield-check" style="width:22px;height:22px;"></i> Key Clinical Benefits & Indications
            </h3>
            <ul style="color:var(--teal-dark);display:flex;flex-direction:column;gap:0.65rem;font-size:1rem;padding-left:1.5rem;">
              <?php foreach (array_filter(explode("\n", $service['benefits'])) as $bLine): ?>
                <li><strong><?= $e(trim($bLine)) ?></strong></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Procedure Steps -->
        <?php if (!empty($service['procedure_steps'])): ?>
          <div style="margin-top:3rem;">
            <h3 style="font-size:1.35rem;margin-bottom:1.25rem;color:var(--deep-navy);display:flex;align-items:center;gap:0.5rem;">
              <i data-lucide="list-ordered" style="width:22px;height:22px;color:var(--primary-teal);"></i> Step-by-Step Procedure Pathway
            </h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
              <?php 
                $stepCount = 1;
                foreach (array_filter(explode("\n", $service['procedure_steps'])) as $step): 
              ?>
                <div style="display:flex;gap:1.25rem;align-items:flex-start;background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-md);padding:1.25rem;">
                  <div style="width:32px;height:32px;border-radius:50%;background:var(--primary-teal);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0;">
                    <?= $stepCount++ ?>
                  </div>
                  <div style="font-size:0.975rem;color:var(--text-main);line-height:1.6;">
                    <?= $e(trim($step)) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <div style="margin-top:3rem;padding:2rem;background:#ffffff;border:2px dashed var(--primary-teal);border-radius:var(--radius-lg);text-align:center;">
          <h3 style="margin-bottom:0.5rem;">Ready to Discuss <?= $e($service['name']) ?>?</h3>
          <p style="color:var(--text-muted);margin-bottom:1.25rem;">Book a dental appointment with <?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> at Dhangadhi Sanjeevani Hospital.</p>
          <button type="button" class="btn btn-pill-cyan open-appointment-modal" data-service-id="<?= $service['id'] ?>">
            <span>Book Consultation</span>
            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
          </button>
        </div>
      </div>

      <!-- Sidebar -->
      <aside>
        <!-- Booking Card -->
        <div style="background:#ffffff;border:1px solid var(--border-color);border-radius:var(--radius-xl);padding:2rem;box-shadow:var(--shadow-md);margin-bottom:2rem;">
          <h3 style="font-size:1.2rem;margin-bottom:1rem;color:var(--deep-navy);">Book <?= $e($service['name']) ?></h3>
          <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1.5rem;">
            Consult with <?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> directly. Select your preferred date, time, and service.
          </p>
          <button type="button" class="btn btn-pill-cyan open-appointment-modal" data-service-id="<?= $service['id'] ?>" style="width:100%;">
            <span>Schedule Now</span>
            <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
          </button>
          <div style="margin-top:1.25rem;text-align:center;">
            <a href="tel:<?= $e($setting('contact_phone', '+15553827491')) ?>" style="font-size:0.875rem;color:var(--navy-slate);font-weight:600;display:inline-flex;align-items:center;gap:0.4rem;">
              <i data-lucide="phone" style="width:14px;height:14px;color:var(--primary-teal);"></i> Call for Immediate Questions
            </a>
          </div>
        </div>

        <!-- Other Dental Services -->
        <div style="background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-xl);padding:1.75rem;">
          <h4 style="font-size:1.1rem;margin-bottom:1.25rem;color:var(--deep-navy);">Other Specialized Services</h4>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.65rem;">
            <?php foreach ($allServices as $other): ?>
              <?php if ($other['id'] == $service['id']) continue; ?>
              <li>
                <a href="<?= $url('/services/' . $other['slug']) ?>" style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0.75rem;border-radius:6px;background:#fff;border:1px solid var(--border-color);font-size:0.9rem;font-weight:600;color:var(--deep-navy);text-decoration:none;">
                  <span><?= $e($other['name']) ?></span>
                  <i data-lucide="chevron-right" style="width:14px;height:14px;color:var(--primary-teal);"></i>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</section>
