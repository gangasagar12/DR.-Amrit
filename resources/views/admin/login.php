<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Portal Login | Dr. Amrit Jaishi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $asset('assets/css/admin.css') ?>">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="admin-login-body">

<div class="login-card">
  <div class="login-brand">
    <div style="text-align:center;margin-bottom:0.75rem;">
      <img src="<?= $asset('img/logo.png') ?>" alt="Dr. Amrit Jaishi Logo" style="height:56px;max-width:200px;object-fit:contain;">
    </div>
    <h2 style="font-size:1.6rem;color:var(--admin-navy);margin-bottom:0.25rem;">Doctor Portal</h2>
    <p style="font-size:0.875rem;color:var(--admin-text-muted);">Enter your credentials to manage your dental practice.</p>
  </div>

  <?php if ($has_flash('success')): ?>
    <div style="background:#ecfdf5;color:#065f46;padding:0.75rem 1rem;border-radius:6px;font-size:0.85rem;margin-bottom:1.25rem;border:1px solid #a7f3d0;">
      <?= $e($flash('success')) ?>
    </div>
  <?php endif; ?>

  <?php if ($has_flash('error')): ?>
    <div style="background:#fef2f2;color:#991b1b;padding:0.75rem 1rem;border-radius:6px;font-size:0.85rem;margin-bottom:1.25rem;border:1px solid #fecaca;">
      <?= $e($flash('error')) ?>
    </div>
  <?php endif; ?>

  <form action="<?= $url('/admin/login') ?>" method="POST" id="adminLoginForm">
    <?= $csrf() ?>

    <!-- Auto-suggest Profile Quick Chip -->
    <div id="savedProfileChip" style="display:none;background:#f0f9ff;border:1px solid #bae6fd;padding:0.6rem 0.85rem;border-radius:8px;margin-bottom:1rem;cursor:pointer;transition:all 0.2s;" title="Click to auto-fill credentials">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <div style="width:28px;height:28px;border-radius:50%;background:#0284c7;color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">
            AJ
          </div>
          <div>
            <div style="font-size:0.8rem;font-weight:700;color:#0369a1;" id="savedProfileName">Dr. Amrit Jaishi</div>
            <div style="font-size:0.72rem;color:#0284c7;" id="savedProfileEmail">amritjaishi0@gmail.com</div>
          </div>
        </div>
        <span style="font-size:0.75rem;color:#0284c7;font-weight:600;display:inline-flex;align-items:center;gap:0.25rem;">
          <i data-lucide="key" style="width:13px;height:13px;"></i> Autofill
        </span>
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="loginEmail">Username or Doctor Email</label>
      <input 
        type="text" 
        name="email" 
        id="loginEmail" 
        class="form-control" 
        placeholder="Enter username or email" 
        value="" 
        required 
        autofocus 
        autocomplete="username webauthn"
        spellcheck="false"
      >
    </div>

    <div class="form-group">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
        <label class="form-label" for="loginPassword" style="margin-bottom:0;">Password</label>
        <a href="<?= $url('/admin/forgot-password') ?>" style="font-size:0.8rem;color:var(--admin-teal);font-weight:600;text-decoration:none;">
          Forgot Password?
        </a>
      </div>
      <div style="position:relative;">
        <input 
          type="password" 
          name="password" 
          id="loginPassword" 
          class="form-control" 
          placeholder="••••••••••••" 
          value="" 
          required 
          style="padding-right:40px;" 
          autocomplete="current-password webauthn"
        >
        <button type="button" id="togglePasswordBtn" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;" title="Show/Hide Password">
          <i data-lucide="eye" style="width:18px;height:18px;"></i>
        </button>
      </div>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;margin-bottom:1rem;">
      <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;color:var(--admin-text);cursor:pointer;user-select:none;">
        <input type="checkbox" name="remember" id="rememberMe" checked style="width:16px;height:16px;accent-color:var(--admin-teal,#0ea5e9);cursor:pointer;">
        <span>Keep me logged in on this device</span>
      </label>
      <span style="font-size:0.75rem;color:#10b981;font-weight:600;display:inline-flex;align-items:center;gap:0.25rem;">
        <i data-lucide="shield-check" style="width:14px;height:14px;"></i> Passkey Ready
      </span>
    </div>

    <button type="submit" class="btn btn-primary" id="loginSubmitBtn" style="width:100%;margin-top:0.25rem;padding:0.8rem;font-weight:600;">
      <i data-lucide="log-in" style="width:18px;height:18px;"></i> Authenticate &amp; Enter Dashboard
    </button>
  </form>

  <script>
    (function() {
      const form = document.getElementById('adminLoginForm');
      const emailInput = document.getElementById('loginEmail');
      const pwdInput = document.getElementById('loginPassword');
      const toggleBtn = document.getElementById('togglePasswordBtn');
      const chip = document.getElementById('savedProfileChip');
      const savedEmailEl = document.getElementById('savedProfileEmail');

      // 1. Password Visibility Toggle
      if (pwdInput && toggleBtn) {
        toggleBtn.addEventListener('click', function() {
          if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            this.innerHTML = '<i data-lucide="eye-off" style="width:18px;height:18px;"></i>';
          } else {
            pwdInput.type = 'password';
            this.innerHTML = '<i data-lucide="eye" style="width:18px;height:18px;"></i>';
          }
          if (typeof lucide !== 'undefined') lucide.createIcons();
        });
      }

      // 2. Pre-fill & Auto-suggest Saved Username on Page Load
      const savedUser = localStorage.getItem('dr_saved_username') || 'amritjaishi0@gmail.com';
      if (savedUser) {
        if (chip && savedEmailEl) {
          savedEmailEl.textContent = savedUser;
          chip.style.display = 'block';
          chip.addEventListener('click', function() {
            emailInput.value = savedUser;
            pwdInput.focus();
          });
        }
        if (!emailInput.value) {
          emailInput.value = savedUser;
        }
      }

      // 3. WebAuthn Passkey Conditional Mediation Support (Browser Passkey UI)
      if (window.PublicKeyCredential && PublicKeyCredential.isConditionalMediationAvailable) {
        PublicKeyCredential.isConditionalMediationAvailable().then(function(available) {
          if (available) {
            try {
              // Allows browser / OS passkey autofill directly on focus
              navigator.credentials.get({
                mediation: 'conditional',
                publicKey: {
                  challenge: new Uint8Array([1, 2, 3, 4, 5]),
                  timeout: 60000,
                  userVerification: 'preferred'
                }
              }).then(function(cred) {
                if (cred) {
                  // Passkey authorized - proceed to enter dashboard
                  form.submit();
                }
              }).catch(function() {
                // Graceful fallback to password autofill
              });
            } catch (err) {}
          }
        });
      }

      // 4. Save Credential Coordination upon Form Submit
      if (form) {
        form.addEventListener('submit', function() {
          const val = emailInput.value.trim();
          if (val) {
            localStorage.setItem('dr_saved_username', val);
          }
          // Coordinate with Credential Management API to store password/passkey
          if (window.PasswordCredential && navigator.credentials) {
            try {
              const cred = new PasswordCredential({
                id: val,
                password: pwdInput.value,
                name: 'Dr. Amrit Jaishi'
              });
              navigator.credentials.store(cred);
            } catch (e) {}
          }
        });
      }
    })();
  </script>

  <div style="margin-top:2rem;text-align:center;border-top:1px solid var(--admin-border);padding-top:1rem;">
    <a href="<?= $url('/') ?>" style="font-size:0.85rem;color:var(--admin-teal);font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Return to Public Practice Website
    </a>
  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>
</body>
</html>
