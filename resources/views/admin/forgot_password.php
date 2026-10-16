<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password | Dr. Amrit Jaishi</title>
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
    <h2 style="font-size:1.5rem;color:var(--admin-navy);margin-bottom:0.25rem;">Reset Password</h2>
    <p style="font-size:0.875rem;color:var(--admin-text-muted);line-height:1.5;">
      Enter your registered doctor email address and we'll send you a secure link to reset your account password.
    </p>
  </div>

  <?php if ($has_flash('success')): ?>
    <div style="background:#ecfdf5;color:#065f46;padding:0.85rem 1rem;border-radius:8px;font-size:0.85rem;margin-bottom:1.25rem;border:1px solid #a7f3d0;display:flex;align-items:flex-start;gap:0.6rem;">
      <i data-lucide="check-circle-2" style="width:18px;height:18px;flex-shrink:0;margin-top:2px;color:#059669;"></i>
      <div style="flex:1;line-height:1.45;">
        <?= $e($flash('success')) ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($has_flash('reset_preview_link')): ?>
    <?php $debugLink = $flash('reset_preview_link'); ?>
    <div style="background:#f0f9ff;color:#0369a1;padding:0.85rem 1rem;border-radius:8px;font-size:0.825rem;margin-bottom:1.25rem;border:1px solid #bae6fd;">
      <div style="font-weight:600;display:flex;align-items:center;gap:0.4rem;margin-bottom:0.35rem;">
        <i data-lucide="info" style="width:15px;height:15px;"></i> Quick Reset Link:
      </div>
      <a href="<?= $e($debugLink) ?>" style="color:#0284c7;word-break:break-all;font-weight:600;text-decoration:underline;">
        Proceed to Password Reset &rarr;
      </a>
      <div style="color:#64748b;font-size:0.75rem;margin-top:0.35rem;">
        (Also logged directly to <code>storage/logs/email.log</code>)
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

  <form action="<?= $url('/admin/forgot-password') ?>" method="POST">
    <?= $csrf() ?>

    <div class="form-group">
      <label class="form-label">Doctor / Administrator Email</label>
      <input 
        type="email" 
        name="email" 
        class="form-control" 
        placeholder="amritjaishi0@gmail.com" 
        value="<?= $e($_POST['email'] ?? 'amritjaishi0@gmail.com') ?>" 
        required 
        autofocus
      >
      <div class="form-help">Enter the email associated with your doctor profile or admin portal.</div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.75rem;padding:0.8rem;">
      <i data-lucide="send" style="width:17px;height:17px;"></i> Send Password Reset Link
    </button>
  </form>

  <div style="margin-top:2rem;text-align:center;border-top:1px solid var(--admin-border);padding-top:1rem;display:flex;justify-content:space-between;align-items:center;">
    <a href="<?= $url('/admin/login') ?>" style="font-size:0.85rem;color:var(--admin-teal);font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to Login
    </a>
    <a href="<?= $url('/') ?>" style="font-size:0.85rem;color:var(--admin-text-muted);display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;">
      Practice Website <i data-lucide="external-link" style="width:13px;height:13px;"></i>
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
