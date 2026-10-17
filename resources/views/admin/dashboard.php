<?php
/** @var array $stats */
/** @var array $recentMessages */
?>

<!-- KPI Metric Cards -->
<div class="metrics-grid">
  <div class="metric-card">
    <div class="metric-icon-box icon-rose">
      <i data-lucide="mail" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['unread_messages'] ?></span>
      <span class="metric-label">Unread Patient Inquiries</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-teal">
      <i data-lucide="activity" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['services'] ?></span>
      <span class="metric-label">Dental Services</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-navy">
      <i data-lucide="microscope" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['researches'] ?></span>
      <span class="metric-label">Research Papers</span>
    </div>
  </div>

  <div class="metric-card">
    <div class="metric-icon-box icon-amber">
      <i data-lucide="book-open" style="width:24px;height:24px;"></i>
    </div>
    <div class="metric-info">
      <span class="metric-number"><?= $stats['published_blogs'] ?></span>
      <span class="metric-label">Published Articles</span>
    </div>
  </div>
</div>

<!-- Secondary Stats Row -->
<div class="dashboard-quick-stats">
  <div class="quick-stat-item">
    <i data-lucide="building-2" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['clinics'] ?></strong> Clinic Locations</span>
  </div>
  <div class="quick-stat-item">
    <i data-lucide="image" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['gallery_images'] ?></strong> Gallery Photos</span>
  </div>
  <div class="quick-stat-item">
    <i data-lucide="video" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['videos'] ?></strong> Educational Videos</span>
  </div>
  <div class="quick-stat-item">
    <i data-lucide="graduation-cap" style="width:16px;height:16px;color:var(--admin-teal);"></i>
    <span><strong><?= $stats['qualifications'] ?></strong> Academic Milestones</span>
  </div>
  <div class="quick-stat-actions">
    <a href="<?= $url('/admin/profile') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="sparkles" style="width:14px;height:14px;"></i> Edit Hero & About
    </a>
    <a href="<?= $url('/admin/research/create') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Research
    </a>
    <a href="<?= $url('/admin/blogs/create') ?>" class="btn btn-primary btn-sm">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Write Article
    </a>
  </div>
</div>

<div class="dashboard-main-grid" style="grid-template-columns: 1.6fr 1fr;">
  <!-- Recent Contact Inquiries -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <div class="admin-card-title">Recent Patient Contact Inquiries</div>
        <span style="font-size:0.8rem;color:var(--admin-text-muted);">Direct messages submitted from the public contact form</span>
      </div>
      <a href="<?= $url('/admin/messages') ?>" class="btn btn-secondary btn-sm">
        View All (<?= $stats['total_messages'] ?>)
      </a>
    </div>

    <div class="admin-card-body" style="padding:0.75rem 1.25rem;">
      <?php if (empty($recentMessages)): ?>
        <p style="text-align:center;padding:2.5rem;color:var(--admin-text-muted);margin:0;">No inquiries received yet.</p>
      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
          <?php foreach ($recentMessages as $msg): ?>
            <div style="padding:0.85rem 1rem;border-radius:8px;background:<?= $msg['is_read'] ? '#fff' : '#f0fdfa' ?>;border:1px solid <?= $msg['is_read'] ? 'var(--admin-border)' : 'var(--admin-teal)' ?>;display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;">
              <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.25rem;">
                  <strong style="font-size:0.92rem;color:var(--admin-navy);"><?= $e($msg['name']) ?></strong>
                  <span style="font-size:0.75rem;color:var(--admin-text-muted);"><?= date('M d, Y', strtotime($msg['created_at'])) ?></span>
                </div>
                <div style="font-size:0.825rem;color:var(--admin-text-muted);margin-bottom:0.35rem;">
                  <a href="mailto:<?= $e($msg['email']) ?>" style="color:var(--admin-teal);text-decoration:none;"><?= $e($msg['email']) ?></a>
                  <?php if (!empty($msg['phone'])): ?> &bull; <?= $e($msg['phone']) ?><?php endif; ?>
                </div>
                <div style="font-size:0.875rem;font-weight:600;color:var(--admin-navy);margin-bottom:0.25rem;"><?= $e($msg['subject']) ?></div>
                <p style="font-size:0.825rem;color:var(--admin-text-muted);margin:0;line-height:1.45;">
                  <?= $e(mb_substr($msg['message'], 0, 140)) ?>...
                </p>
              </div>
              <div style="display:flex;flex-direction:column;gap:0.35rem;margin-top:0.25rem;">
                <?php if (!$msg['is_read']): ?>
                  <span class="status-badge badge-pending" style="font-size:0.7rem;text-align:center;">New</span>
                  <a href="<?= $url('/admin/messages/read?id=' . $msg['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-secondary btn-sm" style="font-size:0.75rem;padding:0.25rem 0.5rem;" title="Mark Read">
                    Read
                  </a>
                <?php else: ?>
                  <span class="status-badge badge-confirmed" style="font-size:0.7rem;text-align:center;">Seen</span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Homepage Management Quick Actions -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Homepage Section Controls</div>
    </div>
    <div class="admin-card-body" style="padding:1.25rem;">
      <p style="font-size:0.85rem;color:var(--admin-text-muted);margin-top:0;margin-bottom:1.25rem;">
        Easily update any section of Dr. Amrit Jaishi's portfolio directly from here:
      </p>

      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        <a href="<?= $url('/admin/profile') ?>" class="sidebar-link" style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;text-decoration:none;">
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <i data-lucide="sparkles" style="width:18px;height:18px;color:var(--admin-teal);"></i>
            <div>
              <div style="font-weight:600;font-size:0.88rem;color:var(--admin-navy);">Hero & About Sections</div>
              <div style="font-size:0.75rem;color:var(--admin-text-muted);">Name, badge, leads, bio & clinical photo</div>
            </div>
          </div>
          <i data-lucide="chevron-right" style="width:16px;height:16px;color:#94a3b8;"></i>
        </a>

        <a href="<?= $url('/admin/qualifications') ?>" class="sidebar-link" style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;text-decoration:none;">
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <i data-lucide="graduation-cap" style="width:18px;height:18px;color:var(--admin-teal);"></i>
            <div>
              <div style="font-weight:600;font-size:0.88rem;color:var(--admin-navy);">Education & Licensure</div>
              <div style="font-size:0.75rem;color:var(--admin-text-muted);">BPKIHS, NMC registration & timeline</div>
            </div>
          </div>
          <i data-lucide="chevron-right" style="width:16px;height:16px;color:#94a3b8;"></i>
        </a>

        <a href="<?= $url('/admin/services') ?>" class="sidebar-link" style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;text-decoration:none;">
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <i data-lucide="activity" style="width:18px;height:18px;color:var(--admin-teal);"></i>
            <div>
              <div style="font-weight:600;font-size:0.88rem;color:var(--admin-navy);">Dental Treatments</div>
              <div style="font-size:0.75rem;color:var(--admin-text-muted);">Root canal, cleaning, crowns & care</div>
            </div>
          </div>
          <i data-lucide="chevron-right" style="width:16px;height:16px;color:#94a3b8;"></i>
        </a>

        <a href="<?= $url('/admin/research') ?>" class="sidebar-link" style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;text-decoration:none;">
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <i data-lucide="microscope" style="width:18px;height:18px;color:var(--admin-teal);"></i>
            <div>
              <div style="font-weight:600;font-size:0.88rem;color:var(--admin-navy);">Research & Publications</div>
              <div style="font-size:0.75rem;color:var(--admin-text-muted);">Papers, peer reviews, PDFs & journals</div>
            </div>
          </div>
          <i data-lucide="chevron-right" style="width:16px;height:16px;color:#94a3b8;"></i>
        </a>

        <a href="<?= $url('/admin/gallery') ?>" class="sidebar-link" style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;text-decoration:none;">
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <i data-lucide="image" style="width:18px;height:18px;color:var(--admin-teal);"></i>
            <div>
              <div style="font-weight:600;font-size:0.88rem;color:var(--admin-navy);">Photo & Video Galleries</div>
              <div style="font-size:0.75rem;color:var(--admin-text-muted);">Clinical practice, outreach & workshops</div>
            </div>
          </div>
          <i data-lucide="chevron-right" style="width:16px;height:16px;color:#94a3b8;"></i>
        </a>
      </div>
    </div>
  </div>
</div>
