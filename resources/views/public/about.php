<?php
/** @var array $doctor */
/** @var array $qualifications */
/** @var array $achievements */
?>

<!-- Header Banner -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="user-check" style="width:14px;height:14px;"></i> Professional Profile</div>
    <h1>About <?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;"><?= $e($doctor['professional_title'] ?? 'BDS (BPKIHS) - Oral & Dental Surgeon') ?></p>
  </div>
</section>

<!-- Main Profile Section -->
<section class="section">
  <div class="container">
    <div class="about-page-grid">
      <!-- Portrait & Quick Facts Card -->
      <div>
        <div style="border-radius:var(--radius-xl);overflow:hidden;box-shadow:var(--shadow-lg);border:4px solid #fff;margin-bottom:2rem;">
          <?php 
            $portraitSrc = !empty($doctor['profile_image']) ? $asset($doctor['profile_image']) : 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=800&q=80';
          ?>
          <img src="<?= $e($portraitSrc) ?>" alt="<?= $e($doctor['doctor_name']) ?>" style="width:100%;height:460px;object-fit:cover;">
        </div>

        <div style="background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:1.75rem;">
          <h4 style="font-size:1.1rem;margin-bottom:1rem;color:var(--deep-navy);">At a Glance</h4>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.85rem;font-size:0.925rem;">
            <li style="display:flex;justify-content:space-between;border-bottom:1px solid var(--border-color);padding-bottom:0.5rem;">
              <span style="color:var(--text-muted);">Experience:</span>
              <span style="font-weight:700;color:var(--deep-navy);"><?= (int)($doctor['experience_years'] ?? 12) ?>+ Years</span>
            </li>
            <li style="display:flex;justify-content:space-between;border-bottom:1px solid var(--border-color);padding-bottom:0.5rem;">
              <span style="color:var(--text-muted);">Languages:</span>
              <span style="font-weight:700;color:var(--deep-navy);"><?= $e($doctor['languages'] ?? 'English') ?></span>
            </li>
            <li style="display:flex;justify-content:space-between;border-bottom:1px solid var(--border-color);padding-bottom:0.5rem;">
              <span style="color:var(--text-muted);">Primary Specialty:</span>
              <span style="font-weight:700;color:var(--primary-teal);">Implant & Restorative</span>
            </li>
            <li style="display:flex;justify-content:space-between;padding-bottom:0.25rem;">
              <span style="color:var(--text-muted);">Consultations:</span>
              <span style="font-weight:700;color:var(--deep-navy);">By Appointment</span>
            </li>
          </ul>

          <button type="button" class="btn btn-primary open-appointment-modal" style="width:100%;margin-top:1.5rem;">
            <i data-lucide="calendar" style="width:16px;height:16px;"></i> Request Appointment
          </button>
        </div>
      </div>

      <!-- Comprehensive Bio & Philosophy -->
      <div>
        <div class="section-tag"><i data-lucide="book-open" style="width:14px;height:14px;"></i> Clinical Journey</div>
        <h2 style="margin-bottom:1.25rem;">Dedicated to Clinical Excellence and Restoring Patient Confidence</h2>

        <div style="font-size:1.05rem;line-height:1.8;color:var(--navy-slate);display:flex;flex-direction:column;gap:1.25rem;">
          <?= nl2br($e($doctor['biography'] ?? '')) ?>
        </div>

        <?php if (!empty($doctor['philosophy'])): ?>
          <div style="background:var(--teal-light);border-left:4px solid var(--primary-teal);padding:1.5rem 1.75rem;border-radius:0 12px 12px 0;margin:2rem 0;">
            <h4 style="color:var(--teal-dark);margin-bottom:0.5rem;font-size:1.05rem;">Philosophy of Patient Care</h4>
            <p style="font-style:italic;color:var(--teal-dark);margin:0;font-size:1rem;line-height:1.65;">
              "<?= $e($doctor['philosophy']) ?>"
            </p>
          </div>
        <?php endif; ?>

        <?php if (!empty($doctor['experience_description'])): ?>
          <div style="margin-top:2rem;">
            <h3 style="font-size:1.35rem;margin-bottom:0.85rem;">Clinical Focus & Experience</h3>
            <p style="font-size:1.02rem;line-height:1.75;"><?= nl2br($e($doctor['experience_description'])) ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Educational Qualifications Timeline -->
<section class="section section-subtle">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i data-lucide="graduation-cap" style="width:14px;height:14px;"></i> Formal Education</div>
      <h2 class="section-title">Academic Qualifications & Clinical Residencies</h2>
      <p class="section-description">Rigorous university dental education and surgical postgraduate training.</p>
    </div>

    <div style="max-width:850px;margin:0 auto;display:flex;flex-direction:column;gap:1.5rem;">
      <?php foreach ($qualifications as $q): ?>
        <div style="background:#fff;border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:1.75rem 2rem;box-shadow:var(--shadow-sm);display:flex;gap:1.5rem;align-items:flex-start;">
          <div style="width:48px;height:48px;background:var(--teal-light);color:var(--primary-teal);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="award" style="width:24px;height:24px;"></i>
          </div>
          <div style="flex-grow:1;">
            <div style="display:flex;justify-content:space-between;align-items:baseline;flex-wrap:wrap;gap:0.5rem;margin-bottom:0.35rem;">
              <h3 style="font-size:1.2rem;color:var(--deep-navy);"><?= $e($q['degree']) ?></h3>
              <span style="background:var(--bg-subtle);border:1px solid var(--border-color);padding:0.25rem 0.65rem;border-radius:var(--radius-full);font-size:0.8rem;font-weight:700;color:var(--primary-teal);">
                <?= $e($q['completion_year']) ?>
              </span>
            </div>
            <div style="font-weight:600;color:var(--navy-slate);font-size:0.95rem;margin-bottom:0.5rem;"><?= $e($q['institution']) ?></div>
            <?php if (!empty($q['description'])): ?>
              <p style="font-size:0.925rem;color:var(--text-muted);margin:0;"><?= $e($q['description']) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Certifications, Memberships & Awards Highlights -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i data-lucide="shield-check" style="width:14px;height:14px;"></i> Continuing Competence</div>
      <h2 class="section-title">Certifications & Professional Memberships</h2>
      <p class="section-description">Affiliations with premier global dental bodies and evidence-based academies.</p>
    </div>

    <div class="awards-responsive-grid">
      <?php foreach ($achievements as $ach): ?>
        <div style="background:#fff;border:1px solid var(--border-color);border-radius:var(--radius-lg);padding:1.5rem;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
            <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;color:var(--primary-teal);background:var(--teal-light);padding:0.2rem 0.6rem;border-radius:var(--radius-full);">
              <?= $e($ach['record_type']) ?>
            </span>
            <span style="font-size:0.8rem;color:var(--text-muted);"><?= $e($ach['achievement_date']) ?></span>
          </div>

          <h4 style="font-size:1.15rem;margin-bottom:0.5rem;color:var(--deep-navy);"><?= $e($ach['title']) ?></h4>
          <div style="font-size:0.875rem;font-weight:600;color:var(--navy-slate);margin-bottom:0.75rem;"><?= $e($ach['organization']) ?></div>
          
          <?php if (!empty($ach['description'])): ?>
            <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:1rem;flex-grow:1;"><?= $e($ach['description']) ?></p>
          <?php endif; ?>

          <?php if (!empty($ach['verification_url'])): ?>
            <div style="margin-top:auto;border-top:1px solid var(--border-light);padding-top:0.75rem;">
              <a href="<?= $e($ach['verification_url']) ?>" target="_blank" rel="noopener" style="font-size:0.825rem;font-weight:700;display:inline-flex;align-items:center;gap:0.3rem;">
                Verify Credential <i data-lucide="external-link" style="width:13px;height:13px;"></i>
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
