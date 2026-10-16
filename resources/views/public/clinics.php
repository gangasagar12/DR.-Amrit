<?php
/** @var array $clinics */
/** @var array $services */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="building-2" style="width:14px;height:14px;"></i> Consultation Locations</div>
    <h1>Clinic Affiliations & Practice Hours</h1>
    <p class="section-description" style="max-width:720px;margin:0 auto;">
      <?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> provides oral surgery, restorative treatments, and emergency care at Dhangadhi Sanjeevani Hospital.
    </p>
  </div>
</section>

<!-- Clinics List -->
<section class="section">
  <div class="container">
    <div style="display:flex;flex-direction:column;gap:3rem;">
      <?php foreach ($clinics as $clinic): ?>
        <div class="clinic-full-card">
          <!-- Clinic Image / Visual Column -->
          <div style="position:relative;min-height:300px;background:var(--bg-subtle);">
            <?php 
              $cImg = !empty($clinic['image']) ? $asset($clinic['image']) : 'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=800&q=80';
            ?>
            <img src="<?= $e($cImg) ?>" alt="<?= $e($clinic['clinic_name']) ?>" style="width:100%;height:100%;object-fit:cover;">
            <div style="position:absolute;top:16px;left:16px;">
              <span class="clinic-type-badge <?= $clinic['workplace_type'] === 'Permanent Workplace' ? 'clinic-type-permanent' : 'clinic-type-visiting' ?>" style="box-shadow:var(--shadow-md);">
                <i data-lucide="<?= $clinic['workplace_type'] === 'Permanent Workplace' ? 'check-circle' : 'calendar-days' ?>" style="width:13px;height:13px;"></i>
                <?= $e($clinic['workplace_type']) ?>
              </span>
            </div>
          </div>

          <!-- Clinic Details Column -->
          <div class="clinic-full-body">
            <div>
              <div style="font-size:0.85rem;font-weight:700;color:var(--primary-teal);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:0.25rem;">
                <?= $e($clinic['doctor_role']) ?>
              </div>
              <h2 class="clinic-full-name"><?= $e($clinic['clinic_name']) ?></h2>

              <?php if (!empty($clinic['description'])): ?>
                <p class="clinic-full-desc">
                  <?= $e($clinic['description']) ?>
                </p>
              <?php endif; ?>

              <!-- Address & Contacts -->
              <div class="clinic-contacts-list">
                <div class="clinic-contact-item">
                  <i data-lucide="map-pin" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;margin-top:2px;"></i>
                  <span><?= $e($clinic['full_address']) ?></span>
                </div>
                <div class="clinic-contact-item">
                  <i data-lucide="phone" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;"></i>
                  <span><?= $e($clinic['phone']) ?></span>
                </div>
                <?php if (!empty($clinic['website_url'])): ?>
                  <div class="clinic-contact-item">
                    <i data-lucide="globe" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;"></i>
                    <a href="<?= $e($clinic['website_url']) ?>" target="_blank" rel="noopener" style="font-weight:600;">Visit Facility Website</a>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Schedules -->
              <?php if (!empty($clinic['schedules'])): ?>
                <div class="clinic-schedules-box">
                  <div class="clinic-schedules-title">
                    <i data-lucide="clock" style="width:14px;height:14px;color:var(--primary-teal);"></i> Consulting Days & Timings
                  </div>
                  <div class="clinic-schedules-rows">
                    <?php foreach ($clinic['schedules'] as $sRow): ?>
                      <div class="clinic-schedule-row">
                        <span class="schedule-day"><?= $e($sRow['visiting_day']) ?></span>
                        <span class="schedule-time"><?= $e($sRow['start_time']) ?> &ndash; <?= $e($sRow['end_time']) ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Action buttons: Sleek, compact and responsive -->
            <div class="clinic-card-actions">
              <button type="button" class="btn btn-primary open-appointment-modal" data-clinic-id="<?= $clinic['id'] ?>">
                <i data-lucide="calendar" style="width:15px;height:15px;"></i>
                <span>Book Consultation</span>
              </button>
              <?php if (!empty($clinic['map_url'])): ?>
                <a href="<?= $e($clinic['map_url']) ?>" target="_blank" rel="noopener" class="btn btn-outline-navy">
                  <i data-lucide="map-pin" style="width:15px;height:15px;"></i>
                  <span>Directions</span>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
