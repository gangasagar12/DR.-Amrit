<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Set New Password | Dr. Amrit Jaishi</title>
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
    <h2 style="font-size:1.5rem;color:var(--admin-navy);margin-bottom:0.25rem;">Create New Password</h2>
    <p style="font-size:0.875rem;color:var(--admin-text-muted);line-height:1.5;">
      Choose a secure password for your Doctor Portal account.
    </p>
    <?php if (!empty($email)): ?>
      <div style="display:inline-block;background:#f1f5f9;color:#475569;font-size:0.8rem;padding:0.25rem 0.65rem;border-radius:20px;margin-top:0.5rem;font-weight:500;">
        Resetting account for: <strong><?= $e($email) ?></strong>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($has_flash('success')): ?>
    <div style="background:#ecfdf5;color:#065f46;padding:0.85rem 1rem;border-radius:8px;font-size:0.85rem;margin-bottom:1.25rem;border:1px solid #a7f3d0;display:flex;align-items:flex-start;gap:0.6rem;">
      <i data-lucide="check-circle-2" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;color:#059669;"></i>
      <div style="flex:1;line-height:1.45;">
        <?= $e($flash('success')) ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($has_flash('error')): ?>
    <div style="background:#fef2f2;color:#991b1b;padding:0.85rem 1rem;border-radius:8px;font-size:0.85rem;margin-bottom:1.25rem;border:1px solid #fecaca;display:flex;align-items:flex-start;gap:0.6rem;">
      <i data-lucide="alert-circle" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;color:#dc2626;"></i>
      <div style="flex:1;line-height:1.45;">
        <?= $e($flash('error')) ?>
      </div>
    </div>
  <?php endif; ?>

  <form action="<?= $url('/admin/reset-password') ?>" method="POST" id="resetPasswordForm">
    <?= $csrf() ?>
    <input type="hidden" name="token" value="<?= $e($token) ?>">

    <div class="form-group">
      <label class="form-label">New Password</label>
      <input 
        type="password" 
        name="password" 
        id="password"
        class="form-control" 
        placeholder="At least 8 characters" 
        minlength="8" 
        required 
        autofocus
      >
      <div class="form-help">Must be at least 8 characters long.</div>
    </div>

    <div class="form-group">
      <label class="form-label">Confirm New Password</label>
      <input 
        type="password" 
        name="confirm_password" 
        id="confirm_password"
        class="form-control" 
        placeholder="Re-type your new password" 
        minlength="8" 
        required
      >
      <div id="passwordMismatch" style="display:none;color:#dc2626;font-size:0.775rem;margin-top:0.25rem;">
        Passwords do not match.
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.75rem;padding:0.8rem;">
      <i data-lucide="shield-check" style="width:17px;height:17px;"></i> Update & Save Password
    </button>
  </form>

  <div style="margin-top:2rem;text-align:center;border-top:1px solid var(--admin-border);padding-top:1rem;">
    <a href="<?= $url('/admin/login') ?>" style="font-size:0.85rem;color:var(--admin-teal);font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Return to Login
    </a>
  </div>
</div>

<script>
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  // Client-side quick validation feedback
  const form = document.getElementById('resetPasswordForm');
  const pwd = document.getElementById('password');
  const confirmPwd = document.getElementById('confirm_password');
  const mismatchNotice = document.getElementById('passwordMismatch');

  form.addEventListener('submit', function(e) {
    if (pwd.value !== confirmPwd.value) {
      e.preventDefault();
      mismatchNotice.style.display = 'block';
      confirmPwd.focus();
    } else {
      mismatchNotice.style.display = 'none';
    }
  });

  confirmPwd.addEventListener('input', function() {
    if (confirmPwd.value && pwd.value !== confirmPwd.value) {
      mismatchNotice.style.display = 'block';
    } else {
      mismatchNotice.style.display = 'none';
    }
  });
</script>
</body>
</html>
