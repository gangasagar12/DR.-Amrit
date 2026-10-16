<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Initial Administrator Setup</title>
  <link rel="stylesheet" href="<?= $asset('assets/css/admin.css') ?>">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="admin-login-body">
<div class="login-card">
  <div class="login-brand">
    <div class="login-icon"><i data-lucide="shield-alert" style="width:28px;height:28px;"></i></div>
    <h2 style="font-size:1.6rem;color:var(--admin-navy);">Create Admin Account</h2>
    <p style="font-size:0.875rem;color:var(--admin-text-muted);">Initialize your primary practice administrator account.</p>
  </div>

  <?php if ($has_flash('error')): ?>
    <div style="background:#fef2f2;color:#991b1b;padding:0.75rem 1rem;border-radius:6px;font-size:0.85rem;margin-bottom:1.25rem;">
      <?= $e($flash('error')) ?>
    </div>
  <?php endif; ?>

  <form action="<?= $url('/admin/setup') ?>" method="POST">
    <?= $csrf() ?>
    <div class="form-group">
      <label class="form-label">Doctor / Administrator Full Name</label>
      <input type="text" name="name" class="form-control" placeholder="Dr. Sarah Jenkins" required>
    </div>
    <div class="form-group">
      <label class="form-label">Administrator Email</label>
      <input type="email" name="email" class="form-control" placeholder="admin@drsarahjenkins.com" required>
    </div>
    <div class="form-group">
      <label class="form-label">Password (Min 8 characters)</label>
      <input type="password" name="password" class="form-control" required minlength="8">
    </div>
    <div class="form-group">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" required minlength="8">
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:1rem;">Complete Setup & Initialize</button>
  </form>
</div>
<script>if(typeof lucide!=='undefined')lucide.createIcons();</script>
</body>
</html>
