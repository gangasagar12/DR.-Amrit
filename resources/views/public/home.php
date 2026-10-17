<?php
/** @var array $doctor */
/** @var array $featuredServices */
/** @var array $latestBlogs */
/** @var array $clinics */
/** @var array $achievements */
/** @var array $gallery */
/** @var array $testimonials */
?>

<!-- 1. HERO SECTION (CANVA TEMPLATE DESIGN) -->
<section class="hero-canva-section" id="heroSection" style="padding: 0; margin: 0; background-color: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: center; align-items: center; width: 100%;">
  <div style="width: 100%; max-width: 900px; margin: 0 auto;">
    <img src="<?= $asset(!empty($doctor['hero_image']) ? $doctor['hero_image'] : 'img/doctor-image.png') ?>" alt="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi - General Dentist') ?>" style="width: 100%; height: auto; display: block;">
  </div>
</section>

<!-- 2. ABOUT DOCTOR SECTION (EXACT THEME DESIGN) -->
<section class="section" id="about">
  <div class="container">
    <div class="about-theme-grid">
      <!-- Left Photo with Circular Experience Badge -->
      <div class="about-theme-img-wrap">
        <div class="about-exp-badge">
          <span class="exp-num"><?= (int)($doctor['experience_years'] ?? 4) ?>+</span>
          <span class="exp-label">Years of<br>Experience</span>
        </div>
        <div class="about-img-card">
          <img src="<?= $asset(!empty($doctor['about_image']) ? $doctor['about_image'] : (!empty($doctor['profile_image']) ? $doctor['profile_image'] : 'img/doctor-clinic.jpg')) ?>" alt="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> BDS (BPKIHS) Dental Surgeon">
        </div>
      </div>

      <!-- Right Bio & Mini Cards -->
      <div class="about-theme-content">
        <div class="section-tag">
          <i data-lucide="award" style="width:14px;height:14px;"></i> <?= $e($doctor['about_kicker'] ?? 'About Dr. Amrit Jaishi') ?>
        </div>
        <h2 style="font-size:clamp(1.85rem, 3.2vw, 2.5rem);margin-bottom:1.25rem;line-height:1.2;">
          <?= $e($doctor['about_subheading'] ?? 'SPECIALIST DENTAL CARE & RESTORATIVE DENTISTRY') ?>
        </h2>
        <?php if (!empty($doctor['biography'])): ?>
          <div style="font-size:1.025rem;line-height:1.75;margin-bottom:1.5rem;color:var(--text-muted);display:flex;flex-direction:column;gap:1rem;">
            <?= nl2br($e($doctor['biography'])) ?>
          </div>
        <?php else: ?>
          <p style="font-size:1.025rem;line-height:1.75;margin-bottom:1.15rem;color:var(--text-muted);">
            Dr. Amrit Raj Jaishi has been serving as an Oral and Dental Surgeon in Farwest Province, Nepal, for over four years. Currently based at Dhangadhi Sanjeevani Hospital, Dr. Jaishi graduated with a Bachelor of Dental Surgery (BDS) from <strong>B.P. Koirala Institute of Health Sciences (BPKIHS, Dharan)</strong> — one of Nepal's leading autonomous medical and dental universities.
          </p>
        <?php endif; ?>

        <!-- 3 Feature Mini Cards from theme.webp -->
        <div class="about-mini-cards">
          <div class="about-mini-card">
            <div class="mini-card-number">1</div>
            <div class="mini-card-title">Restorative Dentistry</div>
          </div>
          <div class="about-mini-card">
            <div class="mini-card-number cyan-bg">2</div>
            <div class="mini-card-title">Preventive Maintenance</div>
          </div>
          <div class="about-mini-card">
            <div class="mini-card-number teal-bg">3</div>
            <div class="mini-card-title">Oral Minor Surgery</div>
          </div>
        </div>

        <a href="<?= $url('/about') ?>" class="btn btn-pill-cyan">
          <span>Learn More About</span>
          <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- 3. OUR SPECIALIZED SERVICES (EXACT THEME 3-COLUMN LAYOUT) -->
<section class="section section-subtle" id="services">
  <div class="container">
    <div class="section-header">
      <div style="color:var(--primary-teal);font-weight:700;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;">
        &bull; What We Offer
      </div>
      <h2 class="section-title">OUR SPECIALIZED SERVICES</h2>
      <p class="section-description">
        Comprehensive diagnosis, restorative procedures, and surgical care delivered with modern technology and gentle patient empathy.
      </p>
    </div>

    <?php
      $srvList = !empty($featuredServices) ? $featuredServices : [];
      $srvLeft = array_slice($srvList, 0, 3);
      $srvRight = array_slice($srvList, 3, 3);
      $centerDoctorImg = !empty($doctor['about_image']) ? $doctor['about_image'] : (!empty($doctor['profile_image']) ? $doctor['profile_image'] : 'img/doctor-clinic.jpg');
    ?>
    <div class="services-theme-wrapper">
      <!-- Left 3 Services -->
      <div class="services-theme-col">
        <?php foreach ($srvLeft as $srv): ?>
          <a href="<?= $url('/services/' . $srv['slug']) ?>" class="service-theme-item">
            <div class="service-theme-icon">
              <i data-lucide="shield-check" style="width:24px;height:24px;"></i>
            </div>
            <div class="service-theme-info">
              <h3><?= $e($srv['name']) ?></h3>
              <p><?= $e($srv['short_description']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Center Doctor Showcase -->
      <div class="services-center-img-box">
        <img src="<?= $asset($centerDoctorImg) ?>" alt="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> in Consultation">
      </div>

      <!-- Right 3 Services -->
      <div class="services-theme-col">
        <?php foreach ($srvRight as $srv): ?>
          <a href="<?= $url('/services/' . $srv['slug']) ?>" class="service-theme-item">
            <div class="service-theme-icon">
              <i data-lucide="sparkles" style="width:24px;height:24px;"></i>
            </div>
            <div class="service-theme-info">
              <h3><?= $e($srv['name']) ?></h3>
              <p><?= $e($srv['short_description']) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div style="text-align:center;margin-top:3rem;">
      <a href="<?= $url('/services') ?>" class="btn btn-pill-cyan">
        <span>View All Treatments & Guidance</span>
        <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
      </a>
    </div>
  </div>
</section>

<!-- 4. HOW WE WORK / PATH TO HEALTHIER GUMS & STRONGER SMILE (EXACT THEME DESIGN) -->
<section class="section" id="how-it-works">
  <div class="container">
    <div class="path-theme-grid">
      <!-- Left Content -->
      <div class="path-left">
        <div class="section-tag">
          <i data-lucide="check-circle" style="width:14px;height:14px;"></i> How We Work
        </div>
        <h2 style="font-size:clamp(1.85rem, 3.2vw, 2.5rem);margin-bottom:1.25rem;line-height:1.2;">
          YOUR PATH TO HEALTHIER GUMS & A STRONGER SMILE
        </h2>
        <p style="font-size:1.025rem;line-height:1.75;color:var(--text-muted);margin-bottom:2rem;">
          At Dhangadhi Sanjeevani Hospital, Dr. Amrit Jaishi makes dental visits straightforward, comfortable, and personalized. From your initial consultation, our team guides you through every step with transparent care and clear communication.
        </p>

        <button type="button" class="btn btn-pill-cyan open-appointment-modal">
          <span>Schedule Your Consultation</span>
          <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
        </button>
      </div>

      <!-- Right 3 Step Cards -->
      <div class="path-steps-list">
        <!-- Step 01 -->
        <div class="path-step-card">
          <div class="step-number-badge">01</div>
          <img src="<?= $asset('uploads/services/teeth_cleaning.jpg') ?>" alt="Consultation" class="step-thumb-img">
          <div class="step-content">
            <h3>Comprehensive Consultation</h3>
            <p>Receive a thorough clinical assessment, digital examination, and a detailed discussion of your oral health needs.</p>
          </div>
        </div>

        <!-- Step 02 -->
        <div class="path-step-card">
          <div class="step-number-badge">02</div>
          <img src="<?= $asset('uploads/services/rct.jpg') ?>" alt="Treatment Plan" class="step-thumb-img">
          <div class="step-content">
            <h3>Personalized Treatment Plan</h3>
            <p>Based on your individual requirements, Dr. Jaishi prepares a tailored, comfortable, and cost-effective treatment strategy.</p>
          </div>
        </div>

        <!-- Step 03 -->
        <div class="path-step-card">
          <div class="step-number-badge">03</div>
          <img src="<?= $asset('uploads/services/emergency.jpg') ?>" alt="Treatment and Care" class="step-thumb-img">
          <div class="step-content">
            <h3>Treatment & Ongoing Care</h3>
            <p>Experience gentle, pain-managed procedures with long-term oral hygiene guidance to keep your smile healthy forever.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- 5. FLOATING CONTINUOUS PATIENT TESTIMONIALS (STREAM ONLY) -->
<section class="testimonials-floating-section" id="testimonials">
  <?php
    // Ensure base set has at least 6 items before doubling for infinite loop
    $baseList = !empty($testimonials) ? $testimonials : [];
    if (!empty($baseList)) {
      while (count($baseList) < 6) {
        $baseList = array_merge($baseList, $testimonials);
      }
    }
    // Duplicate so track translates from 0% to -50% seamlessly
    $marqueeItems = array_merge($baseList, $baseList);
  ?>
  <div class="testimonials-marquee-wrapper" id="testimonialsMarquee">
    <div class="testimonials-marquee-track">
      <?php foreach ($marqueeItems as $t): ?>
        <div class="testimonial-dark-card marquee-card">
          <div>
            <div class="testimonial-dark-rating">
              <span class="score"><?= number_format((float)$t['rating'], 1) ?></span>
              <span class="stars">★★★★★</span>
              <span class="verified-pill"><i data-lucide="shield-check" style="width:11px;height:11px;"></i> Verified Patient</span>
            </div>
            <p class="testimonial-dark-quote">
              "<?= $e($t['quote']) ?>"
            </p>
          </div>

          <div class="testimonial-dark-author">
            <div class="dark-avatar"><?= substr($e($t['patient_name']), 0, 1) ?></div>
            <div class="dark-author-info">
              <h4><?= $e($t['patient_name']) ?></h4>
              <span><?= $e($t['treatment_type']) ?> Patient</span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 6. CLINIC AFFILIATION & SCHEDULE (MAIN WORKPLACE) -->
<section class="section" id="clinics">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i data-lucide="building-2" style="width:14px;height:14px;"></i> Practice Location</div>
      <h2 class="section-title">Hospital Affiliation & Consultation Hours</h2>
      <p class="section-description">
        Dr. Amrit Jaishi consults and operates primarily at Dhangadhi Sanjeevani Hospital, equipped with modern dental operatories.
      </p>
    </div>

    <div style="max-width:850px;margin:0 auto;">
      <?php foreach ($clinics as $clinic): ?>
        <div class="clinic-card" style="box-shadow:var(--shadow-lg);border:2px solid #e0f2fe;overflow:hidden;border-radius:12px;margin-bottom:2rem;">
          <?php if (!empty($clinic['image'])): ?>
            <div style="width:100%;height:220px;overflow:hidden;background-color:#f1f5f9;">
              <img src="<?= $asset($clinic['image']) ?>" alt="<?= $e($clinic['clinic_name']) ?>" style="width:100%;height:100%;object-fit:cover;">
            </div>
          <?php endif; ?>
          <div class="clinic-card-header">
            <div>
              <span class="clinic-type-badge clinic-type-permanent">
                <i data-lucide="check-circle" style="width:13px;height:13px;"></i>
                <?= $e($clinic['workplace_type']) ?>
              </span>
              <h3 class="clinic-title" style="margin-top:0.75rem;font-size:1.4rem;"><?= $e($clinic['clinic_name']) ?></h3>
              <div class="clinic-role" style="font-weight:600;color:var(--primary-teal);"><?= $e($clinic['doctor_role']) ?></div>
            </div>
          </div>

          <div class="clinic-body">
            <p style="color:var(--text-muted);margin-bottom:1.25rem;">
              <?= $e($clinic['description']) ?>
            </p>

            <div class="clinic-meta-row">
              <i data-lucide="map-pin" class="clinic-meta-icon" style="width:20px;height:20px;color:var(--primary-teal);"></i>
              <span style="font-weight:600;color:var(--deep-navy);"><?= $e($clinic['full_address']) ?></span>
            </div>

            <div class="clinic-meta-row">
              <i data-lucide="phone" class="clinic-meta-icon" style="width:20px;height:20px;color:var(--primary-teal);"></i>
              <span><strong>Hospital Reception:</strong> <?= $e($clinic['phone']) ?></span>
            </div>

            <?php if (!empty($clinic['schedules'])): ?>
              <div class="clinic-schedule-box" style="margin-top:1.25rem;">
                <div class="clinic-schedule-title">
                  <i data-lucide="clock" style="width:15px;height:15px;"></i> Department Consulting Hours
                </div>
                <ul class="schedule-list">
                  <?php foreach ($clinic['schedules'] as $sch): ?>
                    <li class="schedule-row">
                      <span class="schedule-day"><strong><?= $e($sch['visiting_day']) ?></strong></span>
                      <span class="schedule-time"><?= date('g:i A', strtotime($sch['start_time'])) ?> &ndash; <?= date('g:i A', strtotime($sch['end_time'])) ?></span>
                    </li>
                    <?php if (!empty($sch['notes'])): ?>
                      <li style="list-style:none;padding-top:0.35rem;font-size:0.85rem;color:var(--text-muted);">
                        <em><?= $e($sch['notes']) ?></em>
                      </li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>

          <div class="clinic-card-footer" style="gap:0.75rem;">
            <button type="button" class="btn btn-pill-cyan open-appointment-modal" data-clinic-id="<?= $clinic['id'] ?>">
              <span>Book Appointment</span>
              <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
            </button>
            <?php if (!empty($clinic['map_url'])): ?>
              <a href="<?= $e($clinic['map_url']) ?>" target="_blank" rel="noopener" class="btn btn-outline-navy" title="Open Google Maps">
                <i data-lucide="map-pin" style="width:15px;height:15px;"></i> Directions
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 7. LATEST ARTICLES -->
<section class="section section-subtle" id="articles">
  <div class="container">
    <div class="section-header">
      <div class="section-tag"><i data-lucide="book-open" style="width:14px;height:14px;"></i> Dental Education</div>
      <h2 class="section-title">Oral Health Guides & Educational Articles</h2>
      <p class="section-description">Practical guidance, preventive tips, and emergency advice written by Dr. Amrit Jaishi.</p>
    </div>

    <div class="blog-grid">
      <?php foreach ($latestBlogs as $blog): ?>
        <article class="blog-card">
          <div class="blog-card-img-wrap">
            <img src="<?= $asset($blog['featured_image'] ?: 'uploads/services/teeth_cleaning.jpg') ?>" alt="<?= $e($blog['image_alt'] ?: $blog['title']) ?>" loading="lazy">
            <span class="blog-type-tag">Educational Article</span>
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

            <div class="blog-card-footer">
              <span style="color:var(--text-muted);font-size:0.85rem;">Dr. Amrit Jaishi</span>
              <a href="<?= $url('/blog/' . $blog['slug']) ?>" style="font-weight:700;display:inline-flex;align-items:center;gap:0.3rem;">
                Read Article <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
              </a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- 8. APPOINTMENT BOOKING CTA BANNER -->
<section class="section" style="padding-top:2rem;">
  <div class="container">
    <div class="appointment-banner" style="background:linear-gradient(135deg, var(--theme-teal-hero-start) 0%, var(--theme-teal-hero-end) 100%);">
      <div class="banner-grid">
        <div class="banner-content">
          <h2 style="color:#ffffff;">Ready for a Healthier, Pain-Free Smile?</h2>
          <p style="color:#d1f4f6;">Schedule your personalized dental consultation with Dr. Amrit Jaishi at Dhangadhi Sanjeevani Hospital. We provide gentle care for all dental concerns.</p>
        </div>
        <div class="banner-actions">
          <button type="button" class="btn btn-pill-cyan open-appointment-modal">
            <span>Request Appointment</span>
            <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
          </button>
          <a href="tel:091590328" class="btn btn-pill-outline">
            <i data-lucide="phone" style="width:15px;height:15px;"></i> Call (091) 590328
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
