<?php
/** @var array $testimonials */
?>

<div style="display:grid;grid-template-columns: 0.75fr 1.25fr;gap:2rem;align-items:start;">
  <!-- Add Testimonial Form -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Add Patient Review</div>
    </div>
    <div class="admin-card-body">
      <form action="<?= $url('/admin/testimonials/create') ?>" method="POST">
        <?= $csrf() ?>
        
        <div class="form-group">
          <label class="form-label">Patient Display Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="patient_display_name" class="form-control" placeholder="e.g. Eleanor V." required>
        </div>

        <div class="form-group">
          <label class="form-label">Treatment Received</label>
          <input type="text" name="treatment_type" class="form-control" placeholder="e.g. Dental Implants / Porcelain Veneers" value="Dental Care">
        </div>

        <div class="form-group">
          <label class="form-label">Star Rating (1 to 5)</label>
          <select name="rating" class="form-control">
            <option value="5" selected>5 Stars (Outstanding)</option>
            <option value="4">4 Stars (Very Good)</option>
            <option value="3">3 Stars (Good)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Testimonial Review Text <span style="color:#ef4444;">*</span></label>
          <textarea name="testimonial_text" class="form-control" placeholder="Patient's feedback on their dental treatment experience..." required></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="0">
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="consent_status" value="1" checked required>
            <span>Patient documented consent verified</span>
          </label>
          <div class="form-help">Mandatory: Only publish testimonials with documented patient consent.</div>
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;">
            <input type="checkbox" name="is_approved" value="1" checked>
            <span>Approved for Public Website Display</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">
          <i data-lucide="plus" style="width:16px;height:16px;"></i> Add Testimonial
        </button>
      </form>
    </div>
  </div>

  <!-- Testimonials Listing -->
  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">Patient Testimonials & Reviews</div>
      <a href="<?= $url('/admin/settings') ?>" class="btn btn-secondary btn-sm">Settings</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Patient</th>
            <th>Review</th>
            <th>Rating</th>
            <th>Approved</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($testimonials)): ?>
            <tr><td colspan="5" style="text-align:center;padding:2rem;">No testimonials added yet.</td></tr>
          <?php else: ?>
            <?php foreach ($testimonials as $t): ?>
              <tr>
                <td>
                  <strong><?= $e($t['patient_display_name']) ?></strong>
                  <div style="font-size:0.75rem;color:var(--admin-teal);"><?= $e($t['treatment_type']) ?></div>
                </td>
                <td style="max-width:280px;font-size:0.85rem;color:var(--admin-text-muted);">
                  "<?= $e($t['testimonial_text']) ?>"
                </td>
                <td>
                  <span style="color:#d97706;font-weight:700;"><?= (int)$t['rating'] ?> &#9733;</span>
                </td>
                <td>
                  <?php if ($t['is_approved'] && $t['consent_status']): ?>
                    <span class="status-badge badge-published">Live</span>
                  <?php else: ?>
                    <span class="status-badge badge-draft">Hidden</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= $url('/admin/testimonials/delete?id=' . $t['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this testimonial?">
                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
