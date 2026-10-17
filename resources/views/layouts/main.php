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
  <link rel="icon" type="image/png" href="<?= $asset('img/doctor-favicon.png') ?>">
  <link rel="apple-touch-icon" href="<?= $asset('img/doctor-favicon.png') ?>">

  <!-- Google Fonts: Inter & Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Sub-page specific styles (article body, comments, etc.) -->
  <link rel="stylesheet" href="/assets/css/style.css?v=<?= @filemtime(ROOT_DIR . '/public/assets/css/style.css') ?: time() ?>">

  <!-- Master Portfolio Stylesheet (Guarantees 100% identical navbar & footer) -->
  <link rel="stylesheet" href="/style.css?v=<?= @filemtime(ROOT_DIR . '/style.css') ?: time() ?>">

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

  <!-- ======================================================================
       1. Header and Navigation (Identical to Homepage)
       ====================================================================== -->
  <header class="site-header" id="siteHeader">
    <div class="container nav-container">
      <a href="/#home" class="brand-logo" aria-label="<?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?> - <?= $e($doctor_profile()['professional_title'] ?? 'General Dentist') ?>">
        <img src="/img/doctor-favicon.png" alt="<?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?>" class="brand-avatar-img" width="44" height="44">
        <div class="brand-text">
          <span class="brand-name"><?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></span>
          <span class="brand-designation"><?= $e($doctor_profile()['professional_title'] ?? 'General Dentist') ?></span>
        </div>
      </a>

      <!-- Mobile Menu Toggle Button -->
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="navMenu">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

      <!-- Navigation Links -->
      <nav class="nav-menu" id="navMenu" aria-label="Main navigation">
        <a href="/#home" class="nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">Home</a>
        <a href="/#about" class="nav-link <?= ($activeNav ?? '') === 'about' ? 'active' : '' ?>">About</a>
        <a href="/#services" class="nav-link <?= ($activeNav ?? '') === 'services' ? 'active' : '' ?>">Services</a>
        <a href="/#education" class="nav-link">Education</a>
        <a href="/#gallery" class="nav-link">Gallery</a>
        <a href="/#videos" class="nav-link">Videos</a>
        <a href="/#blog" class="nav-link <?= ($activeNav ?? '') === 'blog' ? 'active' : '' ?>">Blog</a>
        <a href="/#research" class="nav-link">Research</a>
        <a href="/#clinics" class="nav-link">Clinics</a>
      </nav>

      <!-- Small Contact Header CTA -->
      <div class="header-action">
        <a href="/#contact" class="btn btn-outline-nav">Contact</a>
      </div>
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

  <!-- ======================================================================
       10. Footer (Identical to Homepage)
       ====================================================================== -->
  <footer class="site-footer">
    <div class="container footer-container">
      <div class="footer-top">
        <div class="footer-brand">
          <div class="footer-name"><?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></div>
          <div class="footer-designation"><?= $e($doctor_profile()['designation'] ?? $doctor_profile()['professional_title'] ?? 'General Dentist') ?></div>
          <p class="footer-tagline">
            <?= $e($doctor_profile()['tagline'] ?? 'Dedicated to providing patient-centered dental care with a focus on oral health, preventive dentistry, and comfortable treatment experiences.') ?>
          </p>
          <div class="footer-social" id="footerSocial" aria-label="Social Media Profiles">
            <?php
            $fb = $setting('social_facebook', $setting('facebook_url', ''));
            $ig = $setting('social_instagram', $setting('instagram_url', ''));
            $li = $setting('social_linkedin', $setting('linkedin_url', ''));
            $yt = $setting('social_youtube', $setting('youtube_url', ''));
            $tw = $setting('social_twitter', $setting('twitter_url', ''));
            $wa = $setting('whatsapp_number', $doctor_profile()['whatsapp'] ?? '');
            if (!empty($fb)): ?>
              <a href="<?= $e($fb) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
            <?php endif; ?>
            <?php if (!empty($ig)): ?>
              <a href="<?= $e($ig) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Instagram"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></a>
            <?php endif; ?>
            <?php if (!empty($li)): ?>
              <a href="<?= $e($li) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="LinkedIn"><svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
            <?php endif; ?>
            <?php if (!empty($yt)): ?>
              <a href="<?= $e($yt) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
            <?php endif; ?>
            <?php if (!empty($tw)): ?>
              <a href="<?= $e($tw) ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="X (Twitter)"><svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
            <?php endif; ?>
            <?php if (!empty($wa)): 
              $cleanWa = preg_replace('/[^0-9]/', '', $wa);
            ?>
              <a href="https://wa.me/<?= $cleanWa ?>" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="WhatsApp"><svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.301-.15-1.782-.879-2.058-.979-.276-.1-.477-.15-.678.15-.201.3-.777.979-.953 1.18-.176.201-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.896-.799-1.501-1.786-1.677-2.087-.176-.301-.019-.464.132-.614.136-.135.301-.351.452-.527.15-.176.201-.301.301-.502.101-.201.05-.377-.025-.527-.075-.15-.678-1.634-.929-2.238-.244-.589-.493-.509-.678-.518-.176-.009-.377-.009-.578-.009s-.527.075-.803.377c-.276.301-1.054 1.03-1.054 2.511 0 1.481 1.079 2.911 1.23 3.112.15.201 2.123 3.242 5.143 4.547.718.311 1.279.497 1.716.636.721.23 1.377.197 1.896.12.578-.087 1.782-.728 2.033-1.431.251-.703.251-1.305.176-1.431-.076-.126-.277-.201-.578-.351zM12 21.82a9.77 9.77 0 0 1-4.99-1.365l-.358-.213-3.708.973.99-3.615-.233-.371A9.8 9.8 0 1 1 21.8 12c0 5.414-4.404 9.82-9.8 9.82z"/></svg></a>
            <?php endif; ?>
          </div>
        </div>

        <div class="footer-nav">
          <span class="footer-heading">Navigation</span>
          <div class="footer-links-grid">
            <a href="/#home">Home</a>
            <a href="/#about">About</a>
            <a href="/#services">Services</a>
            <a href="/#education">Education</a>
            <a href="/#gallery">Gallery</a>
            <a href="/#blog">Blog</a>
            <a href="/#research">Research</a>
            <a href="/#clinics">Clinics</a>
            <a href="/#contact">Contact</a>
          </div>
        </div>

        <div class="footer-contact">
          <span class="footer-heading">Contact Details</span>
          <div class="footer-contact-list">
            <div>Email: <a href="mailto:<?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?>" class="footer-email-link"><?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?></a></div>
            <div>Phone: <a href="tel:<?= $e(preg_replace('/[^0-9+]/', '', $setting('contact_phone', '+9779860644282'))) ?>" class="footer-phone-link"><?= $e($setting('contact_phone', '+977-9860644282')) ?></a></div>
            <div>Location: <span id="footerLocationVal"><?= $e($setting('clinic_address', 'Dhangadhi Sanjeevani Hospital, Kailali, Nepal')) ?></span></div>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p class="copyright-text">
          &copy; <span id="currentYear"><?= date('Y') ?></span> <span class="footer-copy-name"><?= $e($doctor_profile()['doctor_name'] ?? 'Dr. Amrit Jaishi') ?></span>. All rights reserved.
        </p>
        <p class="footer-subtext">
          Developed by <a href="https://stra.com" target="_blank" rel="noopener">Stratraflow Technologies</a>.
        </p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="<?= $asset('assets/js/main.js') ?>?v=<?= @filemtime(ROOT_DIR . '/public/assets/js/main.js') ?: time() ?>"></script>
  <script>
    // Header & Mobile Nav handler for subpages matching homepage
    document.addEventListener('DOMContentLoaded', function () {
      const menuToggle = document.getElementById('menuToggle');
      const navMenu = document.getElementById('navMenu');
      if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', function () {
          const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
          menuToggle.setAttribute('aria-expanded', !isExpanded);
          navMenu.classList.toggle('is-open');
        });
        document.addEventListener('click', function (event) {
          if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
            navMenu.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
          }
        });
      }
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    });
  </script>
</body>
</html>
