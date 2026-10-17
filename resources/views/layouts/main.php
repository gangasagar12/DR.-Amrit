<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $e($title ?? 'Dr. Amrit Jaishi | Oral & Dental Surgeon (BDS BPKIHS)') ?></title>
  <meta name="description" content="<?= $e($metaDescription ?? 'Personal professional portfolio of Dr. Amrit Jaishi, BDS (BPKIHS Dharan). Oral and Dental Surgeon at Dhangadhi Sanjeevani Hospital, Farwest Nepal.') ?>">
  <link rel="canonical" href="<?= $e($url($requestPath ?? '')) ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?= $e($title ?? 'Dr. Amrit Jaishi | Oral & Dental Surgeon') ?>">
  <meta property="og:description" content="<?= $e($metaDescription ?? 'Painless root canal treatment, dental crowns, restorative care, and emergency dental surgery.') ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= $e($url($requestPath ?? '')) ?>">
  <meta property="og:site_name" content="Dr. Amrit Jaishi Dental Care">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= $asset('img/logo.png') ?>">
  <link rel="apple-touch-icon" href="<?= $asset('img/logo.png') ?>">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="<?= $asset('assets/css/style.css') ?>?v=<?= @filemtime(ROOT_DIR . '/public/assets/css/style.css') ?: time() ?>">

  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Structured Data JSON-LD -->
  <?= \App\Helpers\Seo::getDoctorSchema() ?>
  <?= $articleSchema ?? '' ?>
  <?= $breadcrumbSchema ?? '' ?>
</head>
<body>

  <!-- Top Contact & Social Bar (Theme Style) -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-socials">
        <a href="<?= $e($setting('facebook_url', 'https://facebook.com/amrit.jaishi')) ?>" target="_blank" rel="noopener" aria-label="Facebook">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <a href="<?= $e($setting('linkedin_url', 'https://linkedin.com/in/amrit-jaishi')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
        <a href="<?= $e($setting('youtube_url', 'https://youtube.com/@amritjaishi')) ?>" target="_blank" rel="noopener" aria-label="YouTube">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
        </a>
        <a href="<?= $e($setting('twitter_url', 'https://twitter.com/')) ?>" target="_blank" rel="noopener" aria-label="X (Twitter)">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
      </div>
      <div class="top-bar-contacts">
        <a href="tel:<?= $e($setting('hospital_phone', '091-590328')) ?>" class="top-bar-phone">
          <i data-lucide="phone" style="width:12px;height:12px;"></i>
          <span>(091) 590328</span><span class="phone-secondary"> / <?= $e($setting('contact_phone', '+977-9860644282')) ?></span>
        </a>
        <span class="top-bar-divider">|</span>
        <a href="mailto:<?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?>" class="top-bar-email"><i data-lucide="mail" style="width:12px;height:12px;"></i> <?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?></a>
      </div>
    </div>
  </div>

  <!-- Main Sticky Header -->
  <header class="site-header">
    <div class="container header-main">
      <a href="<?= $url('/') ?>" class="site-logo">
        <img src="<?= $asset('img/logo.png') ?>" alt="<?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> Logo" class="site-logo-img">
        <div class="logo-text">
          <span class="logo-name"><?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></span>
          <span class="logo-sub"><?= $e($doctor_profile()['professional_title'] ?? 'BDS (BPKIHS) - Oral & Dental Surgeon') ?></span>
        </div>
      </a>

      <!-- Navigation -->
      <nav>
        <ul class="main-nav">
          <li><a href="<?= $url('/') ?>" class="nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
          <li><a href="<?= $url('/about') ?>" class="nav-link <?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>">About Dr. Amrit</a></li>
          <li><a href="<?= $url('/services') ?>" class="nav-link <?= ($activeNav ?? '') === 'services' ? 'active' : '' ?>">Services</a></li>
          <li><a href="<?= $url('/clinics') ?>" class="nav-link <?= ($activeNav ?? '') === 'clinics' ? 'active' : '' ?>">Hospital & Clinic</a></li>
          <li><a href="<?= $url('/videos') ?>" class="nav-link <?= ($activeNav ?? '') === 'videos' ? 'active' : '' ?>">Videos</a></li>
          <li><a href="<?= $url('/blog') ?>" class="nav-link <?= ($activeNav ?? '') === 'blog' ? 'active' : '' ?>">Articles</a></li>
          <li><a href="<?= $url('/contact') ?>" class="nav-link <?= ($activeNav ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
        </ul>
      </nav>

      <!-- Mobile Hamburger Button -->
      <button type="button" class="mobile-toggle" aria-label="Toggle navigation">
        <i class="fa-thin fa-bars"></i>
      </button>
    </div>
  </header>

  <!-- Flash Alerts -->
  <div class="container" style="margin-top: 1.5rem;">
    <?php if ($has_flash('success')): ?>
      <div class="alert alert-success">
        <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
        <div><?= $e($flash('success')) ?></div>
      </div>
    <?php endif; ?>
    <?php if ($has_flash('error')): ?>
      <div class="alert alert-error">
        <i data-lucide="alert-octagon" style="width:20px;height:20px;"></i>
        <div><?= $e($flash('error')) ?></div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Main Body Content -->
  <main>
    <?= $content ?>
  </main>

  <!-- Appointment Booking Modal (Accessible from any page CTA) -->
  <div id="appointmentModal" class="modal-backdrop">
    <div class="modal-dialog">
      <div class="modal-header">
        <div style="display:flex;align-items:center;gap:0.75rem;">
          <div style="width:36px;height:36px;background:var(--teal-light);color:var(--primary-teal);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i data-lucide="calendar-check" style="width:20px;height:20px;"></i>
          </div>
          <div>
            <h3 class="modal-title">Request an Appointment</h3>
            <p style="font-size:0.85rem;color:var(--text-muted);margin:0;">Initial requests are marked Pending and confirmed by our clinic team.</p>
          </div>
        </div>
        <button type="button" class="modal-close">&times;</button>
      </div>

      <div class="modal-body">
        <form action="<?= $url('/appointment/book') ?>" method="POST" class="ajax-appointment-form">
          <?= $csrf() ?>
          <!-- Honeypot -->
          <input type="text" name="website_url_hp" style="display:none;" tabindex="-1" autocomplete="off">

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Full Name <span class="required">*</span></label>
              <input type="text" name="patient_name" class="form-control" placeholder="e.g. John Smith" required>
            </div>
            <div class="form-group">
              <label class="form-label">Contact Phone <span class="required">*</span></label>
              <input type="tel" name="phone" class="form-control" placeholder="e.g. +1 (555) 000-0000" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address (Optional)</label>
            <input type="email" name="email" class="form-control" placeholder="john.smith@example.com">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Selected Treatment / Service</label>
              <select name="service_id" id="modal_service_id" class="form-control">
                <option value="">General Consultation / Checkup</option>
                <?php 
                  $allServices = \App\Models\Service::allActive();
                  foreach ($allServices as $s): 
                ?>
                  <option value="<?= $s['id'] ?>"><?= $e($s['name']) ?> (<?= $e($s['category']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Preferred Clinic Location</label>
              <select name="clinic_id" id="modal_clinic_id" class="form-control">
                <?php 
                  $allClinics = \App\Models\Clinic::allActive();
                  foreach ($allClinics as $c): 
                ?>
                  <option value="<?= $c['id'] ?>">
                    <?= $e($c['clinic_name']) ?> [<?= $e($c['workplace_type']) ?>]
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Preferred Date <span class="required">*</span></label>
              <input type="date" name="preferred_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Preferred Time Slot <span class="required">*</span></label>
              <select name="preferred_time" class="form-control" required>
                <option value="Morning (09:00 AM - 12:00 PM)">Morning (09:00 AM - 12:00 PM)</option>
                <option value="Afternoon (12:00 PM - 03:00 PM)">Afternoon (12:00 PM - 03:00 PM)</option>
                <option value="Late Afternoon (03:00 PM - 06:00 PM)">Late Afternoon (03:00 PM - 06:00 PM)</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Dental Symptoms / Clinical Message (Optional)</label>
            <textarea name="message" class="form-control" placeholder="Describe any discomfort, specific tooth concerns, or history..."></textarea>
          </div>

          <div style="background:#f8fafc;padding:0.85rem;border-radius:8px;font-size:0.825rem;color:var(--text-muted);margin-bottom:1.25rem;">
            <i data-lucide="shield-check" style="width:16px;height:16px;display:inline;color:var(--primary-teal);"></i>
            <strong>Patient Privacy Notice:</strong> Your health details and contact information are confidential and strictly reviewed by <?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> and authorized hospital personnel only.
          </div>

          <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
            <button type="button" class="btn btn-secondary close-modal-btn">Cancel</button>
            <button type="submit" class="btn btn-pill-cyan">
              <span>Submit Request</span>
              <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Professional Footer -->
  <footer class="site-footer">
    <div class="container footer-grid">
      <!-- Col 1: Identity & Bio -->
      <div class="footer-col">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
          <img src="<?= $asset('img/logo.png') ?>" alt="Dr. Amrit Jaishi Logo" style="width:40px;height:40px;object-fit:contain;border-radius:8px;background:#ffffff;padding:2px;box-shadow:0 2px 8px rgba(0,0,0,0.2);">
          <div>
            <h3 style="margin:0;font-size:1.2rem;color:#ffffff;"><?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></h3>
            <span style="color:#93d5dc;font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;display:block;margin-top:2px;"><?= $e($doctor_profile()['professional_title'] ?? 'BDS (BPKIHS) - Oral & Dental Surgeon') ?></span>
          </div>
        </div>
        <p><?= $e($doctor_profile()['tagline'] ?? 'PERSONALISED SPECIALIST CARE FOR YOUR SMILE') ?></p>
        <div class="social-icons-row">
          <a href="<?= $e($setting('facebook_url', 'https://facebook.com/amrit.jaishi')) ?>" class="social-icon-btn" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="<?= $e($setting('linkedin_url', 'https://linkedin.com/in/amrit-jaishi')) ?>" class="social-icon-btn" target="_blank" rel="noopener" aria-label="LinkedIn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
          <a href="<?= $e($setting('youtube_url', 'https://youtube.com/@amritjaishi')) ?>" class="social-icon-btn" target="_blank" rel="noopener" aria-label="YouTube">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </a>
          <a href="<?= $e($setting('twitter_url', 'https://twitter.com/')) ?>" class="social-icon-btn" target="_blank" rel="noopener" aria-label="X (Twitter)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="footer-col">
        <h4>Professional Portfolio</h4>
        <ul class="footer-links">
          <li><a href="<?= $url('/about') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> About Dr. Amrit</a></li>
          <li><a href="<?= $url('/services') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Treatments & Specialties</a></li>
          <li><a href="<?= $url('/clinics') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Hospital & Clinic Location</a></li>
          <li><a href="<?= $url('/blog') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Dental Health Education</a></li>
          <li><a href="<?= $url('/contact') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Contact & Location</a></li>
        </ul>
      </div>

      <!-- Col 3: Key Procedures -->
      <div class="footer-col">
        <h4>Key Dental Treatments</h4>
        <ul class="footer-links">
          <li><a href="<?= $url('/services/emergency-dental-care') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Emergency Dental Care</a></li>
          <li><a href="<?= $url('/services/dental-crown-and-bridge') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Dental Crown & Bridge</a></li>
          <li><a href="<?= $url('/services/root-canal-treatment-rct') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Root Canal Treatment (RCT)</a></li>
          <li><a href="<?= $url('/services/periodontal-gum-care') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Periodontal & Gum Care</a></li>
          <li><a href="<?= $url('/services/wisdom-tooth-removal') ?>"><i data-lucide="chevron-right" style="width:14px;height:14px;display:inline;"></i> Wisdom Tooth Removal</a></li>
        </ul>
      </div>

      <!-- Col 4: Primary Clinic Contact -->
      <div class="footer-col">
        <h4>Hospital Consultation</h4>
        <p style="display:flex;gap:0.5rem;align-items:flex-start;margin-bottom:0.75rem;">
          <i data-lucide="map-pin" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;margin-top:3px;"></i>
          <span><?= $e($setting('clinic_address', 'Dhangadhi Sanjeevani Hospital, Hospital Road, Dhangadhi, Kailali, Nepal')) ?></span>
        </p>
        <p style="display:flex;gap:0.5rem;align-items:center;margin-bottom:0.75rem;">
          <i data-lucide="phone" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;"></i>
          <span>(091) 590328 / <?= $e($setting('contact_phone', '+977-9860644282')) ?></span>
        </p>
        <p style="display:flex;gap:0.5rem;align-items:center;margin-bottom:1.25rem;">
          <i data-lucide="mail" style="width:18px;height:18px;color:var(--primary-teal);flex-shrink:0;"></i>
          <span><?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?></span>
        </p>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="container footer-bottom">
      <div>
        &copy; <?= date('Y') ?> Dr. Amrit Jaishi. All rights reserved.
      </div>
      <div style="display:flex;gap:1.5rem;align-items:center;">
        <a href="https://dipeshbham.com.np/" style="color:#94a3b8;font-size:0.8rem;">Made with ❤️ Dipesh Bham</a>
        <!-- <a href="<?= $url('/admin/login') ?>" style="color:#94a3b8;font-size:0.8rem;"><i data-lucide="lock" style="width:12px;height:12px;display:inline;"></i> Doctor Portal</a> -->
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="<?= $asset('assets/js/main.js') ?>?v=<?= @filemtime(ROOT_DIR . '/public/assets/js/main.js') ?: time() ?>"></script>
</body>
</html>
