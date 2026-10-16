<?php
/** @var array $appointment */
?>

<div style="max-width:850px;margin:0 auto;">
  <div style="margin-bottom:1.5rem;">
    <a href="<?= $url('/admin/appointments') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back to All Appointments
    </a>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <div>
        <div class="admin-card-title">Appointment Request #<?= $appointment['id'] ?></div>
        <div style="font-size:0.8rem;color:var(--admin-text-muted);">
          Submitted on: <?= date('F j, Y \a\t g:i A', strtotime($appointment['created_at'])) ?>
        </div>
      </div>
      <div>
        <?php 
          $badgeClass = match($appointment['status']) {
            'Pending' => 'badge-pending',
            'Confirmed' => 'badge-confirmed',
            'Completed' => 'badge-completed',
            'Cancelled' => 'badge-cancelled',
            default => 'badge-pending'
          };
        ?>
        <span class="status-badge <?= $badgeClass ?>" style="font-size:0.9rem;padding:0.4rem 1rem;">
          Current Status: <?= $e($appointment['status']) ?>
        </span>
      </div>
    </div>

    <div class="admin-card-body">
      <!-- Patient Information -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--admin-border);">
        <div>
          <h4 style="font-size:1rem;color:var(--admin-navy);margin-bottom:0.75rem;">Patient Details</h4>
          <div style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.925rem;">
            <div><strong>Name:</strong> <?= $e($appointment['patient_name']) ?></div>
            <div>
              <strong>Phone:</strong> 
              <a href="tel:<?= $e($appointment['phone']) ?>" style="color:var(--admin-teal);"><?= $e($appointment['phone']) ?></a>
            </div>
            <div>
              <strong>Email:</strong> 
              <?= $appointment['email'] ? '<a href="mailto:' . $e($appointment['email']) . '">' . $e($appointment['email']) . '</a>' : '<span style="color:var(--admin-text-muted);">Not provided</span>' ?>
            </div>
          </div>
        </div>

        <div>
          <h4 style="font-size:1rem;color:var(--admin-navy);margin-bottom:0.75rem;">Consultation Schedule</h4>
          <div style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.925rem;">
            <div><strong>Treatment:</strong> <?= $e($appointment['service_name'] ?? 'General Consultation') ?></div>
            <div><strong>Clinic Branch:</strong> <?= $e($appointment['clinic_name'] ?? 'Primary Clinic') ?></div>
            <div><strong>Preferred Date:</strong> <?= date('l, F j, Y', strtotime($appointment['preferred_date'])) ?></div>
            <div><strong>Time Slot:</strong> <?= $e($appointment['preferred_time']) ?></div>
          </div>
        </div>
      </div>

      <!-- Patient Message -->
      <?php if (!empty($appointment['message'])): ?>
        <div style="margin-bottom:2rem;padding:1.25rem;background:#f8fafc;border-radius:8px;border:1px solid var(--admin-border);">
          <h4 style="font-size:0.95rem;color:var(--admin-navy);margin-bottom:0.5rem;">Patient Clinical Message / Symptoms</h4>
          <p style="font-size:0.925rem;color:var(--admin-text);margin:0;line-height:1.6;">
            <?= nl2br($e($appointment['message'])) ?>
          </p>
        </div>
      <?php endif; ?>

      <!-- Status & Internal Notes Update Form -->
      <form action="<?= $url('/admin/appointments/status') ?>" method="POST" style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:10px;padding:1.75rem;">
        <?= $csrf() ?>
        <input type="hidden" name="id" value="<?= $appointment['id'] ?>">
        <input type="hidden" name="from_detail" value="1">

        <h4 style="font-size:1.1rem;color:var(--admin-navy);margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
          <i data-lucide="check-square" style="width:18px;height:18px;color:var(--admin-teal);"></i>
          Update Status & Clinical Administration Notes
        </h4>

        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Review Status <span style="color:#ef4444;">*</span></label>
            <select name="status" class="form-control" required>
              <option value="Pending" <?= $appointment['status'] === 'Pending' ? 'selected' : '' ?>>
                Pending (Awaiting Clinic Call/Verification)
              </option>
              <option value="Confirmed" <?= $appointment['status'] === 'Confirmed' ? 'selected' : '' ?>>
                Confirmed (Schedule Locked with Patient)
              </option>
              <option value="Completed" <?= $appointment['status'] === 'Completed' ? 'selected' : '' ?>>
                Completed (Treatment/Consultation Concluded)
              </option>
              <option value="Cancelled" <?= $appointment['status'] === 'Cancelled' ? 'selected' : '' ?>>
                Cancelled (Declined or Rescheduled)
              </option>
            </select>
            <div class="form-help">Do not set to Confirmed until reception has verified availability with the patient.</div>
          </div>

          <div class="form-group">
            <label class="form-label">Private Internal Doctor Notes</label>
            <textarea name="internal_notes" class="form-control" placeholder="Internal clinical instructions, X-ray requirements, or follow-up logs. (Never displayed publicly)"><?= $e($appointment['internal_notes'] ?? '') ?></textarea>
          </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:1rem;margin-top:1.5rem;">
          <a href="<?= $url('/admin/appointments') ?>" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary">
            <i data-lucide="save" style="width:16px;height:16px;"></i> Save Status & Notes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
