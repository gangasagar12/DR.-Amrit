<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $e($title ?? 'Clinical Administration Portal') ?> - Doctor Management</title>
  
  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= $asset('img/logo.png') ?>">

  <!-- Admin Stylesheet -->
  <link rel="stylesheet" href="<?= $asset('assets/css/admin.css') ?>?v=3.0">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- TinyMCE Rich Text Editor (Community / License-Compliant Integration) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>

<div class="admin-layout">
  <!-- Mobile & Tablet Backdrop -->
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <!-- Sidebar -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <div class="sidebar-brand-left">
        <div class="brand-icon" style="background:#ffffff;padding:2px;display:flex;align-items:center;justify-content:center;border-radius:8px;">
          <img src="<?= $asset('img/logo.png') ?>" alt="Logo" style="width:28px;height:28px;object-fit:contain;">
        </div>
        <div>
          <div class="brand-title">Doctor Portal</div>
          <div class="brand-subtitle">Clinical Admin</div>
        </div>
      </div>
      <button type="button" class="sidebar-collapse-btn" id="sidebarCollapseBtn" title="Slide / Collapse Sidebar" aria-label="Collapse Sidebar">
        <i data-lucide="chevron-left" style="width:18px;height:18px;"></i>
      </button>
    </div>

    <ul class="sidebar-menu">
      <li class="menu-category-title">Overview</li>
      <li>
        <a href="<?= $url('/admin/dashboard') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'dashboard' ? 'active' : '' ?>">
          <i data-lucide="layout-dashboard" style="width:18px;height:18px;"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="menu-category-title">Professional Identity</li>
      <li>
        <a href="<?= $url('/admin/profile') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'profile' ? 'active' : '' ?>">
          <i data-lucide="user-check" style="width:18px;height:18px;"></i>
          <span>Doctor Profile & Bio</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/services') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'services' ? 'active' : '' ?>">
          <i data-lucide="activity" style="width:18px;height:18px;"></i>
          <span>Dental Services</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/achievements') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'achievements' ? 'active' : '' ?>">
          <i data-lucide="award" style="width:18px;height:18px;"></i>
          <span>Certifications & Honors</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/clinics') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'clinics' ? 'active' : '' ?>">
          <i data-lucide="building-2" style="width:18px;height:18px;"></i>
          <span>Clinic Affiliations</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/gallery') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'gallery' ? 'active' : '' ?>">
          <i data-lucide="image" style="width:18px;height:18px;"></i>
          <span>Clinic & Work Gallery</span>
        </a>
      </li>

      <li class="menu-category-title">Education & Publications</li>
      <li>
        <a href="<?= $url('/admin/blogs') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'blogs' ? 'active' : '' ?>">
          <i data-lucide="book-open" style="width:18px;height:18px;"></i>
          <span>Blog & Research</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/categories') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'categories' ? 'active' : '' ?>">
          <i data-lucide="folder-tree" style="width:18px;height:18px;"></i>
          <span>Article Categories</span>
        </a>
      </li>

      <li class="menu-category-title">Patient Interactions</li>
      <li>
        <a href="<?= $url('/admin/appointments') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'appointments' ? 'active' : '' ?>">
          <i data-lucide="calendar" style="width:18px;height:18px;"></i>
          <span>Appointments</span>
          <?php 
            $pendingCount = \App\Models\Appointment::countPending();
            if ($pendingCount > 0): 
          ?>
            <span class="sidebar-badge"><?= $pendingCount ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/messages') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'messages' ? 'active' : '' ?>">
          <i data-lucide="mail" style="width:18px;height:18px;"></i>
          <span>Inquiries</span>
          <?php 
            $unreadCount = \App\Models\Message::countUnread();
            if ($unreadCount > 0): 
          ?>
            <span class="sidebar-badge"><?= $unreadCount ?></span>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/testimonials') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'testimonials' ? 'active' : '' ?>">
          <i data-lucide="message-square" style="width:18px;height:18px;"></i>
          <span>Testimonials</span>
        </a>
      </li>

      <li class="menu-category-title">System & Settings</li>
      <li>
        <a href="<?= $url('/admin/settings') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'settings' ? 'active' : '' ?>">
          <i data-lucide="settings" style="width:18px;height:18px;"></i>
          <span>Site Settings & SEO</span>
        </a>
      </li>
      <li>
        <a href="<?= $url('/admin/account') ?>" class="sidebar-link <?= ($activeAdmin ?? '') === 'account' ? 'active' : '' ?>">
          <i data-lucide="shield-check" style="width:18px;height:18px;"></i>
          <span>Login & Password</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <a href="<?= $url('/') ?>" target="_blank" class="sidebar-link" style="color:#94a3b8;">
        <i data-lucide="external-link" style="width:16px;height:16px;"></i>
        <span>View Public Website</span>
      </a>
      <a href="<?= $url('/admin/logout') ?>" class="sidebar-link" style="color:#f87171;margin-top:0.4rem;" data-confirm="Are you sure you want to log out?">
        <i data-lucide="log-out" style="width:16px;height:16px;"></i>
        <span>Sign Out</span>
      </a>
    </div>
  </aside>

  <!-- Main Content Area -->
  <div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
      <div class="topbar-left">
        <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Toggle Side Menu" title="Slide / Toggle Side Menu">
          <i data-lucide="menu" style="width:20px;height:20px;"></i>
        </button>
        <div class="topbar-title"><?= $e($title ?? 'Dashboard') ?></div>
      </div>

      <div class="topbar-right">
        <a href="<?= $url('/') ?>" target="_blank" class="btn btn-secondary btn-sm">
          <i data-lucide="globe" style="width:14px;height:14px;"></i> Live Site
        </a>
        <a href="<?= $url('/admin/account') ?>" class="user-badge" style="text-decoration:none;cursor:pointer;" title="Manage Admin Login & Password">
          <div class="user-avatar-initials">AJ</div>
          <span style="color:var(--admin-navy);"><?= $e(\App\Core\Session::get('admin_user_name', 'Dr. Amrit Jaishi')) ?></span>
        </a>
      </div>
    </header>

    <!-- Page Content -->
    <main class="admin-content">
      <!-- Flash Messages -->
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

      <?= $content ?>
    </main>
  </div>
</div>

<!-- Admin Scripts -->
<script src="<?= $asset('assets/js/admin.js') ?>?v=3.0"></script>
</body>
</html>
