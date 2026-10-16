<?php
/** @var array $clinics */
/** @var array $services */
/** @var array $doctor */
?>

<!-- Header -->
<section class="section section-subtle" style="padding: 3.5rem 0;">
  <div class="container text-center">
    <div class="section-tag"><i data-lucide="map-pin" style="width:14px;height:14px;"></i> Connect With Us</div>
    <h1>Contact & Appointment Inquiries</h1>
    <p class="section-description" style="max-width:700px;margin:0 auto;">
      Schedule your consultation or send a clinical question. We respond promptly to all patient communications.
    </p>
  </div>
</section>

<!-- Forms & Contact Grid -->
<section class="section">
  <div class="container">
    <div class="contact-page-grid">
      
      <!-- Appointment Booking Form Card -->
      <div class="contact-form-card">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
          <div style="width:42px;height:42px;background:var(--teal-light);color:var(--primary-teal);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;">
            <i data-lucide="calendar" style="width:22px;height:22px;"></i>
          </div>
          <div>
            <h2 style="font-size:1.5rem;color:var(--deep-navy);">Request an Appointment</h2>
            <p style="font-size:0.875rem;color:var(--text-muted);margin:0;">Submitted requests are set to Pending and confirmed by clinic reception.</p>
          </div>
        </div>

        <form action="<?= $url('/appointment/book') ?>" method="POST" class="ajax-appointment-form">
          <?= $csrf() ?>
          <!-- Honeypot -->
          <input type="text" name="website_url_hp" style="display:none;" tabindex="-1" autocomplete="off">

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Patient Full Name <span class="required">*</span></label>
              <input type="text" name="patient_name" class="form-control" placeholder="e.g. Eleanor Vance" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone Number <span class="required">*</span></label>
              <input type="tel" name="phone" class="form-control" placeholder="e.g. +1 (555) 382-7491" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address (Optional)</label>
            <input type="email" name="email" class="form-control" placeholder="eleanor.v@example.com">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Requested Treatment / Service</label>
              <select name="service_id" class="form-control">
                <option value="">General Consultation / Comprehensive Checkup</option>
                <?php foreach ($services as $srv): ?>
                  <option value="<?= $srv['id'] ?>"><?= $e($srv['name']) ?> (<?= $e($srv['category']) ?>)</option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Preferred Clinic Location</label>
              <select name="clinic_id" class="form-control">
                <?php foreach ($clinics as $cln): ?>
                  <option value="<?= $cln['id'] ?>">
                    <?= $e($cln['clinic_name']) ?> [<?= $e($cln['workplace_type']) ?>]
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
              <label class="form-label">Preferred Consultation Time <span class="required">*</span></label>
              <select name="preferred_time" class="form-control" required>
                <option value="Morning (09:00 AM - 12:00 PM)">Morning (09:00 AM - 12:00 PM)</option>
                <option value="Afternoon (12:00 PM - 03:00 PM)">Afternoon (12:00 PM - 03:00 PM)</option>
                <option value="Late Afternoon (03:00 PM - 06:00 PM)">Late Afternoon (03:00 PM - 06:00 PM)</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Clinical Symptoms or Questions (Optional)</label>
            <textarea name="message" class="form-control" placeholder="Describe any current dental discomfort, emergency concerns, or procedure questions..."></textarea>
          </div>

          <div style="background:#f8fafc;padding:0.85rem;border-radius:8px;font-size:0.825rem;color:var(--text-muted);margin-bottom:1.5rem;">
            <i data-lucide="shield-check" style="width:15px;height:15px;display:inline;color:var(--primary-teal);"></i>
            <strong>Privacy Guarantee:</strong> All patient data is confidential and securely handled in strict compliance with healthcare privacy protocols.
          </div>

          <button type="submit" class="btn btn-primary" style="width:100%;max-width:260px;">
            <i data-lucide="send" style="width:15px;height:15px;"></i> Send Message
          </button>
        </form>
      </div>

      <!-- Practice Information & Contact Details -->
      <div>
        <!-- Quick Contacts Card -->
        <div style="background:var(--bg-subtle);border:1px solid var(--border-color);border-radius:var(--radius-xl);padding:2rem;margin-bottom:2rem;">
          <h3 style="font-size:1.35rem;color:var(--deep-navy);margin-bottom:1.25rem;">Practice Details</h3>
          
          <div style="display:flex;flex-direction:column;gap:1.25rem;">
            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
              <div style="width:36px;height:36px;background:#fff;border:1px solid var(--border-color);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary-teal);flex-shrink:0;">
                <i data-lucide="map-pin" style="width:18px;height:18px;"></i>
              </div>
              <div>
                <strong style="color:var(--deep-navy);font-size:0.95rem;display:block;">Primary Clinic Location</strong>
                <span style="font-size:0.9rem;color:var(--text-muted);"><?= $e($setting('clinic_address', 'Suite 400, 742 Evergreen Healthcare Pavilion, Metro City, NY 10001')) ?></span>
              </div>
            </div>

            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
              <div style="width:36px;height:36px;background:#fff;border:1px solid var(--border-color);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary-teal);flex-shrink:0;">
                <i data-lucide="phone" style="width:18px;height:18px;"></i>
              </div>
              <div>
                <strong style="color:var(--deep-navy);font-size:0.95rem;display:block;">Telephone</strong>
                <a href="tel:<?= $e($setting('contact_phone', '+15553827491')) ?>" style="font-size:0.9rem;color:var(--text-muted);"><?= $e($setting('contact_phone', '+1 (555) 382-7491')) ?></a>
              </div>
            </div>

            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
              <div style="width:36px;height:36px;background:#fff;border:1px solid var(--border-color);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary-teal);flex-shrink:0;">
                <i data-lucide="mail" style="width:18px;height:18px;"></i>
              </div>
              <div>
                <strong style="color:var(--deep-navy);font-size:0.95rem;display:block;">Email Address</strong>
                <a href="mailto:<?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?>" style="font-size:0.9rem;color:var(--text-muted);"><?= $e($setting('contact_email', 'amritjaishi0@gmail.com')) ?></a>
              </div>
            </div>

            <div style="display:flex;gap:0.75rem;align-items:flex-start;">
              <div style="width:36px;height:36px;background:#fff;border:1px solid var(--border-color);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--primary-teal);flex-shrink:0;">
                <i data-lucide="clock" style="width:18px;height:18px;"></i>
              </div>
              <div>
                <strong style="color:var(--deep-navy);font-size:0.95rem;display:block;">Consultation Timings</strong>
                <span style="font-size:0.9rem;color:var(--text-muted);"><?= $e($setting('working_hours', 'Sun - Fri: 10:00 AM - 5:00 PM')) ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- General Inquiry Form -->
        <div style="background:#fff;border:1px solid var(--border-color);border-radius:var(--radius-xl);padding:2rem;">
          <h3 style="font-size:1.25rem;color:var(--deep-navy);margin-bottom:0.75rem;">General Inquiry or Message</h3>
          <p style="font-size:0.875rem;color:var(--text-muted);margin-bottom:1.25rem;">For administrative, academic, or non-appointment inquiries.</p>

          <form action="<?= $url('/contact/submit') ?>" method="POST">
            <?= $csrf() ?>
            <input type="text" name="middle_name_hp" style="display:none;" tabindex="-1" autocomplete="off">

            <div class="form-group">
              <label class="form-label">Your Name <span class="required">*</span></label>
              <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
              <label class="form-label">Your Email <span class="required">*</span></label>
              <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
              <label class="form-label">Phone Number</label>
              <input type="tel" name="phone" class="form-control">
            </div>

            <div class="form-group">
              <label class="form-label">Subject <span class="required">*</span></label>
              <input type="text" name="subject" class="form-control" required>
            </div>

            <div class="form-group">
              <label class="form-label">Message <span class="required">*</span></label>
              <textarea name="message" class="form-control" style="min-height:90px;" required></textarea>
            </div>

            <button type="submit" class="btn btn-navy" style="width:100%;">
              <i data-lucide="send" style="width:16px;height:16px;"></i> Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
