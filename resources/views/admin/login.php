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

  <form action="<?= $url('/admin/login') ?>" method="POST">
    <?= $csrf() ?>

    <div class="form-group">
      <label class="form-label">Username or Doctor Email</label>
      <input type="text" name="email" class="form-control" placeholder="Enter username or email" value="" required autofocus autocomplete="off">
    </div>

    <div class="form-group">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem;">
        <label class="form-label" style="margin-bottom:0;">Password</label>
        <a href="<?= $url('/admin/forgot-password') ?>" style="font-size:0.8rem;color:var(--admin-teal);font-weight:600;text-decoration:none;">
          Forgot Password?
        </a>
      </div>
      <div style="position:relative;">
        <input type="password" name="password" id="loginPassword" class="form-control" placeholder="••••••••••••" value="" required style="padding-right:40px;" autocomplete="new-password">
        <button type="button" id="togglePasswordBtn" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;display:flex;align-items:center;" title="Show/Hide Password">
          <i data-lucide="eye" style="width:18px;height:18px;"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.75rem;padding:0.8rem;">
      <i data-lucide="log-in" style="width:18px;height:18px;"></i> Authenticate & Enter Dashboard
    </button>
  </form>

  <script>
    const pwdInput = document.getElementById('loginPassword');
    const toggleBtn = document.getElementById('togglePasswordBtn');
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
