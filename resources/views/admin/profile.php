<?php
/** @var array $doctor */
/** @var array $qualifications */
?>

<form action="<?= $url('/admin/profile') ?>" method="POST" enctype="multipart/form-data">
  <?= $csrf() ?>

  <!-- 1. HERO SECTION CUSTOMIZER -->
  <div class="admin-card" style="margin-bottom:1.5rem;">
    <div class="admin-card-header">
      <div>
        <div class="admin-card-title">
          <i data-lucide="sparkles" style="width:18px;height:18px;color:var(--admin-teal);display:inline-block;vertical-align:middle;margin-right:6px;"></i>
          Homepage Hero Section
        </div>
        <span style="font-size:0.85rem;color:var(--admin-text-muted);">Manage the top banner of your public homepage</span>
      </div>
      <a href="<?= $url('/') ?>#home" target="_blank" class="btn btn-secondary btn-sm">
        <i data-lucide="external-link" style="width:14px;height:14px;"></i> View Hero on Live Site
      </a>
    </div>

    <div class="admin-card-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Doctor Display Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="doctor_name" class="form-control" value="<?= $e($doctor['doctor_name'] ?? 'Dr. Amrit Jaishi') ?>" required>
          <div class="form-help">Displayed in the main title, navigation bar, and footer.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Badge Designation / Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="professional_title" class="form-control" value="<?= $e($doctor['professional_title'] ?? 'General Dentist') ?>" placeholder="e.g. General Dentist" required>
          <div class="form-help">Hero badgepill and header designation (e.g. General Dentist).</div>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Hero Lead Paragraph <span style="color:#ef4444;">*</span></label>
          <textarea name="hero_lead" class="form-control" style="min-height:90px;" required><?= $e($doctor['hero_lead'] ?? 'Dedicated to providing patient-centered dental care with a focus on oral health, preventive dentistry, and comfortable treatment experiences. Committed to helping individuals maintain healthy smiles through professional care, clear communication, and evidence-based dental practices.') ?></textarea>
          <div class="form-help">Primary introductory paragraph directly under the doctor name.</div>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Hero Clinical Approach Statement</label>
          <textarea name="hero_approach" class="form-control" style="min-height:75px;"><?= $e($doctor['hero_approach'] ?? 'Every clinical procedure is guided by conservative tooth preservation, gentle pain-free techniques, and transparent guidance tailored to each patient\'s individual needs.') ?></textarea>
          <div class="form-help">Secondary statement highlighting tooth preservation and gentle care.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Primary Button Text</label>
          <input type="text" name="hero_btn1_text" class="form-control" value="<?= $e($doctor['hero_btn1_text'] ?? 'Explore My Profile') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Primary Button Link</label>
          <input type="text" name="hero_btn1_link" class="form-control" value="<?= $e($doctor['hero_btn1_link'] ?? '#about') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Secondary Button Text</label>
          <input type="text" name="hero_btn2_text" class="form-control" value="<?= $e($doctor['hero_btn2_text'] ?? 'Contact Me') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Secondary Button Link</label>
          <input type="text" name="hero_btn2_link" class="form-control" value="<?= $e($doctor['hero_btn2_link'] ?? '#contact') ?>">
        </div>

        <!-- Hero Cutout Image Upload -->
        <div class="form-group form-full" style="background:#f8fafc;padding:1.25rem;border-radius:10px;border:1px solid #e2e8f0;">
          <label class="form-label" style="font-weight:700;">Hero Transparent Cutout Photograph (Doctor Portrait)</label>
          <input type="file" name="hero_image" class="form-control" accept="image/png,image/webp,image/jpeg" data-preview="heroPreview">
          <div class="form-help">Recommended: High-resolution transparent PNG or WebP with cutout background (e.g. 560x680px).</div>

          <div style="display:flex;align-items:center;gap:1.5rem;margin-top:1rem;">
            <div class="img-preview-box" style="width:130px;height:160px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;border-radius:8px;border:1px solid #cbd5e1;overflow:hidden;">
              <?php 
                $currentHeroPic = !empty($doctor['hero_image']) ? $asset($doctor['hero_image']) : $asset('img/doctor-cutout.png');
              ?>
              <img id="heroPreview" src="<?= $e($currentHeroPic) ?>" alt="Hero Cutout Preview" style="width:100%;height:100%;object-fit:contain;">
            </div>
            <div style="font-size:0.85rem;color:var(--admin-text-muted);">
              Current hero cutout path: <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;"><?= $e($doctor['hero_image'] ?? 'img/doctor-cutout.png') ?></code><br>
              Uploading a new file will automatically replace this image on the live website.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. ABOUT SECTION CUSTOMIZER -->
  <div class="admin-card" style="margin-bottom:1.5rem;">
    <div class="admin-card-header">
      <div>
        <div class="admin-card-title">
          <i data-lucide="user" style="width:18px;height:18px;color:var(--admin-teal);display:inline-block;vertical-align:middle;margin-right:6px;"></i>
          Homepage About & Bio Section
        </div>
        <span style="font-size:0.85rem;color:var(--admin-text-muted);">Manage your clinical background, philosophy, and clinical photo</span>
      </div>
      <a href="<?= $url('/') ?>#about" target="_blank" class="btn btn-secondary btn-sm">
        <i data-lucide="external-link" style="width:14px;height:14px;"></i> View About on Live Site
      </a>
    </div>

    <div class="admin-card-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Section Kicker</label>
          <input type="text" name="about_kicker" class="form-control" value="<?= $e($doctor['about_kicker'] ?? 'Professional Background') ?>" placeholder="e.g. Professional Background">
        </div>

        <div class="form-group">
          <label class="form-label">About Subheading</label>
          <input type="text" name="about_subheading" class="form-control" value="<?= $e($doctor['about_subheading'] ?? 'Committed to Patient Comfort, Ethics & Clinical Precision') ?>" placeholder="e.g. Committed to Patient Comfort, Ethics & Clinical Precision">
        </div>

        <div class="form-group form-full">
          <label class="form-label">About Bio Paragraphs (Detailed Background) <span style="color:#ef4444;">*</span></label>
          <textarea name="biography" class="form-control" style="min-height:160px;" required><?= $e($doctor['biography'] ?? '') ?></textarea>
          <div class="form-help">Separate paragraphs with a blank line. Each paragraph displays clearly in the About section.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Clinical Experience (Years)</label>
          <input type="number" name="experience_years" class="form-control" value="<?= (int)($doctor['experience_years'] ?? 4) ?>" min="0">
        </div>

        <div class="form-group">
          <label class="form-label">Languages Spoken</label>
          <input type="text" name="languages" class="form-control" value="<?= $e($doctor['languages'] ?? 'Nepali, English, Hindi') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Floating Badge Title</label>
          <input type="text" name="about_badge_title" class="form-control" value="<?= $e($doctor['about_badge_title'] ?? '4+ Years Clinical Care') ?>" placeholder="e.g. 4+ Years Clinical Care">
        </div>

        <div class="form-group">
          <label class="form-label">Floating Badge Subtitle</label>
          <input type="text" name="about_badge_subtitle" class="form-control" value="<?= $e($doctor['about_badge_subtitle'] ?? 'Oral & Dental Surgery') ?>" placeholder="e.g. Oral & Dental Surgery">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinical Care Philosophy & Focus</label>
          <textarea name="philosophy" class="form-control" style="min-height:80px;"><?= $e($doctor['philosophy'] ?? '') ?></textarea>
        </div>

        <!-- About Clinical Photo Upload -->
        <div class="form-group form-full" style="background:#f8fafc;padding:1.25rem;border-radius:10px;border:1px solid #e2e8f0;">
          <label class="form-label" style="font-weight:700;">About Clinical Practice Photo</label>
          <input type="file" name="about_image" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="aboutPreview">
          <div class="form-help">Recommended: High quality 4:5 vertical portrait in clinical or hospital attire (e.g. 480x600px).</div>

          <div style="display:flex;align-items:center;gap:1.5rem;margin-top:1rem;">
            <div class="img-preview-box" style="width:130px;height:160px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;border-radius:8px;border:1px solid #cbd5e1;overflow:hidden;">
              <?php 
                $currentAboutPic = !empty($doctor['about_image']) ? $asset($doctor['about_image']) : $asset('img/doctor-clinic.jpg');
              ?>
              <img id="aboutPreview" src="<?= $e($currentAboutPic) ?>" alt="About Photo Preview" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="font-size:0.85rem;color:var(--admin-text-muted);">
              Current about photo path: <code style="background:#e2e8f0;padding:2px 6px;border-radius:4px;"><?= $e($doctor['about_image'] ?? 'img/doctor-clinic.jpg') ?></code><br>
              This is different from the hero cutout to give your site a rich, professional design.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. CONTACT SECTION & CLINICAL HOTLINES -->
  <div class="admin-card" style="margin-bottom:1.5rem;">
    <div class="admin-card-header">
      <div>
        <div class="admin-card-title">
          <i data-lucide="phone-call" style="width:18px;height:18px;color:var(--admin-teal);display:inline-block;vertical-align:middle;margin-right:6px;"></i>
          Contact Details & Patient Hotlines
        </div>
        <span style="font-size:0.85rem;color:var(--admin-text-muted);">Synchronized to the Contact section, direct action buttons, and footer</span>
      </div>
      <a href="<?= $url('/') ?>#contact" target="_blank" class="btn btn-secondary btn-sm">
        <i data-lucide="external-link" style="width:14px;height:14px;"></i> View Contact on Live Site
      </a>
    </div>

    <div class="admin-card-body">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Professional Email <span style="color:#ef4444;">*</span></label>
          <input type="email" name="contact_email" class="form-control" value="<?= $e($doctor['contact_email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Primary Telephone / Mobile <span style="color:#ef4444;">*</span></label>
          <input type="text" name="phone" class="form-control" value="<?= $e($doctor['phone'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">WhatsApp Contact Number (with country code)</label>
          <input type="text" name="whatsapp" class="form-control" value="<?= $e($doctor['whatsapp'] ?? '') ?>" placeholder="e.g. +9779860644282">
          <div class="form-help">Powers the "Message on WhatsApp" button in the contact section.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Hospital Lines / Emergency Guidance</label>
          <input type="text" name="emergency_contact" class="form-control" value="<?= $e($doctor['emergency_contact'] ?? '') ?>" placeholder="e.g. 091-590328 / 091-590348 (Hospital Lines)">
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Save Bar -->
  <div style="position:sticky;bottom:1.5rem;z-index:100;background:rgba(255,255,255,0.95);backdrop-filter:blur(8px);padding:1rem 1.5rem;border-radius:12px;box-shadow:0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
    <div style="font-size:0.9rem;color:var(--admin-text-muted);">
      <i data-lucide="info" style="width:16px;height:16px;vertical-align:middle;margin-right:4px;"></i>
      All modifications will immediately synchronize across your public homepage.
    </div>
    <button type="submit" class="btn btn-primary" style="padding:0.65rem 1.75rem;font-size:0.95rem;font-weight:700;">
      <i data-lucide="save" style="width:16px;height:16px;"></i> Save Homepage Content & Bio
    </button>
  </div>
</form>
