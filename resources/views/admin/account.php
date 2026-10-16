<?php
/** @var array $user */
$doctorProfile = \App\Models\DoctorProfile::get();
?>

<div class="account-grid">
  
  <!-- Account Summary Info Card -->
  <div class="admin-card" style="grid-column:1/-1;">
    <div class="admin-card-header" style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);color:#ffffff;border-radius:12px 12px 0 0;padding:1.25rem 1.5rem;">
      <div style="display:flex;align-items:center;gap:0.85rem;">
        <div style="width:44px;height:44px;border-radius:10px;background:rgba(13,148,136,0.25);border:1px solid rgba(13,148,136,0.5);display:flex;align-items:center;justify-content:center;color:#2dd4bf;">
          <i data-lucide="shield-check" style="width:24px;height:24px;"></i>
        </div>
        <div>
          <div style="font-size:1.15rem;font-weight:700;color:#ffffff;"><?= $e($user['name']) ?> &mdash; Administrator Credentials</div>
          <div style="font-size:0.85rem;color:#94a3b8;">Manage your Doctor Portal authentication credentials, primary login email, and secure access password.</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <span style="display:inline-flex;align-items:center;gap:0.35rem;background:#ecfdf5;color:#065f46;padding:0.3rem 0.75rem;border-radius:20px;font-size:0.8rem;font-weight:600;border:1px solid #a7f3d0;">
          <span style="width:7px;height:7px;background:#10b981;border-radius:50%;display:inline-block;"></span> Active Admin
        </span>
      </div>
    </div>
    <div class="admin-card-body" style="background:#f8fafc;padding:1rem 1.5rem;border-bottom:1px solid var(--admin-border);">
      <div style="display:flex;flex-wrap:wrap;gap:2rem;font-size:0.85rem;color:var(--admin-text-muted);">
        <div>
          <strong style="color:var(--admin-navy);">Primary Login Email:</strong> 
          <span style="color:var(--admin-teal);font-weight:600;"><?= $e($user['email']) ?></span>
        </div>
        <?php if (!empty($doctorProfile['contact_email']) && strtolower($doctorProfile['contact_email']) !== strtolower($user['email'])): ?>
          <div>
            <strong style="color:var(--admin-navy);">Doctor Public Contact Email:</strong> 
            <span style="color:#64748b;"><?= $e($doctorProfile['contact_email']) ?></span>
          </div>
        <?php endif; ?>
        <div>
          <strong style="color:var(--admin-navy);">Last Logged In:</strong> 
          <span><?= !empty($user['last_login_at']) ? date('M j, Y, g:i A', strtotime($user['last_login_at'])) : 'Active Now' ?></span>
        </div>
        <div>
          <strong style="color:var(--admin-navy);">Account Role:</strong> 
          <span>Lead Doctor & Practice Administrator</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Form 1: Change Login Email & Name -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title" style="display:flex;align-items:center;gap:0.5rem;">
        <i data-lucide="mail" style="width:18px;height:18px;color:var(--admin-teal);"></i> Change Login Email & Admin Name
      </div>
    </div>
    <div class="admin-card-body">
      <p style="font-size:0.875rem;color:var(--admin-text-muted);margin-bottom:1.25rem;line-height:1.5;">
        Updating your login email changes the email address required to sign into this portal and receive password reset requests.
      </p>

      <form action="<?= $url('/admin/account/update') ?>" method="POST">
        <?= $csrf() ?>

        <div class="form-group">
          <label class="form-label">Administrator Display Name <span style="color:#ef4444;">*</span></label>
          <input 
            type="text" 
            name="name" 
            class="form-control" 
            value="<?= $e($user['name']) ?>" 
            placeholder="Dr. Amrit Jaishi" 
            required
          >
          <div class="form-help">The name displayed across the administrative dashboard.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Admin Login Email <span style="color:#ef4444;">*</span></label>
          <input 
            type="email" 
            name="email" 
            class="form-control" 
            value="<?= $e($user['email']) ?>" 
            placeholder="amritjaishi0@gmail.com" 
            required
          >
          <div class="form-help">This is your primary email for logging into the Doctor Portal.</div>
        </div>

        <div class="form-group" style="background:#f8fafc;padding:0.9rem;border-radius:8px;border:1px dashed #cbd5e1;">
          <label class="form-label" style="color:#0f172a;font-weight:600;">
            Current Password <span style="color:#ef4444;">*</span>
          </label>
          <input 
            type="password" 
            name="current_password" 
            class="form-control" 
            placeholder="Enter current password to authorize changes" 
            required
          >
          <div class="form-help" style="color:#64748b;">
            <i data-lucide="lock" style="width:12px;height:12px;display:inline-block;vertical-align:middle;"></i> 
            Required for security verification before saving a new login email.
          </div>
        </div>

        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
          <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
            <i data-lucide="save" style="width:16px;height:16px;"></i> Update Login Email
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Form 2: Change Password -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title" style="display:flex;align-items:center;gap:0.5rem;">
        <i data-lucide="key" style="width:18px;height:18px;color:var(--admin-teal);"></i> Change Login Password
      </div>
    </div>
    <div class="admin-card-body">
      <p style="font-size:0.875rem;color:var(--admin-text-muted);margin-bottom:1.25rem;line-height:1.5;">
        Change your Doctor Portal password. Please choose a strong password of at least 8 characters.
      </p>

      <form action="<?= $url('/admin/account/password') ?>" method="POST" id="changePasswordForm">
        <?= $csrf() ?>

        <div class="form-group">
          <label class="form-label">Current Password <span style="color:#ef4444;">*</span></label>
          <input 
            type="password" 
            name="current_password" 
            class="form-control" 
            placeholder="••••••••••••" 
            required
          >
        </div>

        <div class="form-group">
          <label class="form-label">New Password <span style="color:#ef4444;">*</span></label>
          <input 
            type="password" 
            name="new_password" 
            id="accountNewPassword"
            class="form-control" 
            placeholder="At least 8 characters" 
            minlength="8" 
            required
          >
          <div class="form-help">Minimum 8 characters. Combination of letters and numbers recommended.</div>
        </div>

        <div class="form-group">
          <label class="form-label">Confirm New Password <span style="color:#ef4444;">*</span></label>
          <input 
            type="password" 
            name="confirm_password" 
            id="accountConfirmPassword"
            class="form-control" 
            placeholder="Re-type new password" 
            minlength="8" 
            required
          >
          <div id="accountPasswordMismatch" style="display:none;color:#dc2626;font-size:0.775rem;margin-top:0.25rem;">
            New passwords do not match.
          </div>
        </div>

        <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
          <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:0.4rem;">
            <i data-lucide="shield-check" style="width:16px;height:16px;"></i> Update Login Password
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<script>
  // Validation for password confirmation
  const pwdForm = document.getElementById('changePasswordForm');
  const newPwd = document.getElementById('accountNewPassword');
  const confirmPwd = document.getElementById('accountConfirmPassword');
  const mismatchAlert = document.getElementById('accountPasswordMismatch');

  if (pwdForm && newPwd && confirmPwd) {
    pwdForm.addEventListener('submit', function(e) {
      if (newPwd.value !== confirmPwd.value) {
        e.preventDefault();
        mismatchAlert.style.display = 'block';
        confirmPwd.focus();
      } else {
        mismatchAlert.style.display = 'none';
      }
    });

    confirmPwd.addEventListener('input', function() {
      if (confirmPwd.value && newPwd.value !== confirmPwd.value) {
        mismatchAlert.style.display = 'block';
      } else {
        mismatchAlert.style.display = 'none';
      }
    });
  }
</script>
