<?php
/** @var array $settings */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Website Settings & Practice Configuration</div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
      <a href="<?= $url('/admin/account') ?>" class="btn btn-secondary btn-sm">
        <i data-lucide="shield-check" style="width:14px;height:14px;"></i> Login & Password
      </a>
      <a href="<?= $url('/admin/testimonials') ?>" class="btn btn-secondary btn-sm">
        <i data-lucide="message-square" style="width:14px;height:14px;"></i> Patient Testimonials & Consent
      </a>
    </div>
  </div>

  <div class="admin-card-body">
    <form action="<?= $url('/admin/settings') ?>" method="POST">
      <?= $csrf() ?>

      <!-- 1. Identity & Branding -->
      <h3 style="font-size:1.15rem;color:var(--admin-navy);margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;">
        <i data-lucide="globe" style="width:18px;height:18px;color:var(--admin-teal);"></i> General Practice Identity
      </h3>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Website Name / Brand Title</label>
          <input type="text" name="site_name" class="form-control" value="<?= $e($settings['site_name'] ?? 'Dr. Amrit Jaishi | Oral & Dental Surgeon') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Header Subtitle / Tagline</label>
          <input type="text" name="site_tagline" class="form-control" value="<?= $e($settings['site_tagline'] ?? '') ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Primary Clinic Address (Displayed in Footer)</label>
          <input type="text" name="clinic_address" class="form-control" value="<?= $e($settings['clinic_address'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Reception Phone Number</label>
          <input type="text" name="contact_phone" class="form-control" value="<?= $e($settings['contact_phone'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">General Contact Email</label>
          <input type="email" name="contact_email" class="form-control" value="<?= $e($settings['contact_email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">WhatsApp Number (e.g. +15553827491)</label>
          <input type="text" name="whatsapp_number" class="form-control" value="<?= $e($settings['whatsapp_number'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">General Working Hours Notice</label>
          <input type="text" name="working_hours" class="form-control" value="<?= $e($settings['working_hours'] ?? 'Mon - Thu: 8:30 AM - 5:30 PM | Fri: 8:30 AM - 2:00 PM') ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Emergency Dental Advice Guidance</label>
          <input type="text" name="emergency_guidance" class="form-control" value="<?= $e($settings['emergency_guidance'] ?? '') ?>">
        </div>
      </div>

      <!-- 2. Social Media URLs -->
      <h3 style="font-size:1.15rem;color:var(--admin-navy);margin:2rem 0 1rem 0;display:flex;align-items:center;gap:0.4rem;border-top:1px solid var(--admin-border);padding-top:1.5rem;">
        <i data-lucide="share-2" style="width:18px;height:18px;color:var(--admin-teal);"></i> Professional Social Media Accounts
      </h3>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Facebook Profile / Page</label>
          <input type="url" name="social_facebook" class="form-control" value="<?= $e($settings['social_facebook'] ?? ($settings['facebook_url'] ?? '')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Instagram Profile</label>
          <input type="url" name="social_instagram" class="form-control" value="<?= $e($settings['social_instagram'] ?? ($settings['instagram_url'] ?? '')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">LinkedIn Profile</label>
          <input type="url" name="social_linkedin" class="form-control" value="<?= $e($settings['social_linkedin'] ?? ($settings['linkedin_url'] ?? '')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">YouTube Educational Channel</label>
          <input type="url" name="social_youtube" class="form-control" value="<?= $e($settings['social_youtube'] ?? ($settings['youtube_url'] ?? '')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">X / Twitter Profile</label>
          <input type="url" name="social_twitter" class="form-control" value="<?= $e($settings['social_twitter'] ?? ($settings['twitter_url'] ?? '')) ?>">
        </div>
      </div>

      <!-- 3. Default SEO Settings -->
      <h3 style="font-size:1.15rem;color:var(--admin-navy);margin:2rem 0 1rem 0;display:flex;align-items:center;gap:0.4rem;border-top:1px solid var(--admin-border);padding-top:1.5rem;">
        <i data-lucide="search" style="width:18px;height:18px;color:var(--admin-teal);"></i> Default SEO & Meta Tags
      </h3>

      <div class="form-grid">
        <div class="form-group form-full">
          <label class="form-label">Default SEO Meta Title</label>
          <input type="text" name="meta_title" class="form-control" value="<?= $e($settings['meta_title'] ?? '') ?>">
        </div>
        <div class="form-group form-full">
          <label class="form-label">Default SEO Meta Description</label>
          <textarea name="meta_description" class="form-control"><?= $e($settings['meta_description'] ?? '') ?></textarea>
        </div>
        <div class="form-group form-full">
          <label class="form-label">Footer Copyright Text (HTML allowed)</label>
          <input type="text" name="footer_copyright_text" class="form-control" value="<?= $e($settings['footer_copyright_text'] ?? '') ?>">
        </div>
      </div>

      <!-- 4. Email & SMTP Configuration -->
      <h3 style="font-size:1.15rem;color:var(--admin-navy);margin:2rem 0 1rem 0;display:flex;align-items:center;gap:0.4rem;border-top:1px solid var(--admin-border);padding-top:1.5rem;">
        <i data-lucide="mail" style="width:18px;height:18px;color:var(--admin-teal);"></i> Email & SMTP Notification Settings
      </h3>

      <div class="form-grid">
        <div class="form-group form-full">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="smtp_enabled" value="1" <?= !empty($settings['smtp_enabled']) ? 'checked' : '' ?>>
            <span>Enable SMTP Socket Delivery (Sends notifications for appointments and inquiries)</span>
          </label>
        </div>

        <div class="form-group">
          <label class="form-label">SMTP Host</label>
          <input type="text" name="smtp_host" class="form-control" value="<?= $e($settings['smtp_host'] ?? 'smtp.mailtrap.io') ?>" placeholder="e.g. smtp.gmail.com or smtp.mailtrap.io">
        </div>

        <div class="form-group">
          <label class="form-label">SMTP Port</label>
          <input type="text" name="smtp_port" class="form-control" value="<?= $e($settings['smtp_port'] ?? '587') ?>" placeholder="587 or 465">
        </div>

        <div class="form-group">
          <label class="form-label">SMTP Username</label>
          <input type="text" name="smtp_username" class="form-control" value="<?= $e($settings['smtp_username'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">SMTP Password</label>
          <input type="password" name="smtp_password" class="form-control" placeholder="Leave blank to keep current password">
        </div>

        <div class="form-group">
          <label class="form-label">SMTP Encryption</label>
          <select name="smtp_encryption" class="form-control">
            <option value="tls" <?= ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (Port 587)</option>
            <option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
            <option value="none" <?= ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">From Email Address</label>
          <input type="email" name="smtp_from_email" class="form-control" value="<?= $e($settings['smtp_from_email'] ?? 'no-reply@jaishiamrit.com.np') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">From Display Name</label>
          <input type="text" name="smtp_from_name" class="form-control" value="<?= $e($settings['smtp_from_name'] ?? 'Dr. Amrit Jaishi Practice') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Doctor / Admin Notification Email (Receives New Bookings)</label>
          <input type="email" name="notification_email" class="form-control" value="<?= $e($settings['notification_email'] ?? 'amritjaishi0@gmail.com') ?>">
        </div>
      </div>

      <div style="margin-top:2.5rem;display:flex;justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="save" style="width:16px;height:16px;"></i> Save All Site Settings
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Send Test Email Utility -->
<div class="admin-card" style="margin-top:2rem;">
  <div class="admin-card-header">
    <div class="admin-card-title">Test SMTP Email Delivery</div>
  </div>
  <div class="admin-card-body">
    <p style="font-size:0.9rem;color:var(--admin-text-muted);margin-bottom:1rem;">
      Send a diagnostic email to ensure your SMTP host, credentials, port, and TLS encryption are communicating successfully.
    </p>
    <form action="<?= $url('/admin/settings/test-smtp') ?>" method="POST" style="display:flex;gap:1rem;max-width:500px;">
      <?= $csrf() ?>
      <input type="email" name="test_email" class="form-control" placeholder="your-email@example.com" required>
      <button type="submit" class="btn btn-secondary" style="white-space:nowrap;">
        <i data-lucide="send" style="width:15px;height:15px;"></i> Send Test Email
      </button>
    </form>
  </div>
</div>
