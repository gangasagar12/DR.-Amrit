<?php
/** @var ?array $clinic */
$isEdit = !empty($clinic);
$actionUrl = $isEdit ? $url('/admin/clinics/edit') : $url('/admin/clinics/create');
$schedules = $clinic['schedules'] ?? [];
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title"><?= $isEdit ? 'Edit Clinic: ' . $e($clinic['clinic_name']) : 'Add Clinic Affiliation' ?></div>
    <a href="<?= $url('/admin/clinics') ?>" class="btn btn-secondary btn-sm">Back</a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= $clinic['id'] ?>">
      <?php endif; ?>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Clinic Facility Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="clinic_name" class="form-control" data-slug-source="clinic_slug" value="<?= $e($clinic['clinic_name'] ?? '') ?>" placeholder="e.g. Apex Dental Excellence Pavilion" required>
        </div>

        <div class="form-group">
          <label class="form-label">URL Slug</label>
          <input type="text" name="slug" id="clinic_slug" class="form-control" value="<?= $e($clinic['slug'] ?? '') ?>" placeholder="apex-dental-excellence">
        </div>

        <div class="form-group">
          <label class="form-label">Workplace Affiliation Type <span style="color:#ef4444;">*</span></label>
          <select name="workplace_type" class="form-control">
            <option value="Permanent Workplace" <?= ($clinic['workplace_type'] ?? '') === 'Permanent Workplace' ? 'selected' : '' ?>>
              Permanent Workplace (Primary Practice / Daily Operatory)
            </option>
            <option value="Visiting Consultant" <?= ($clinic['workplace_type'] ?? '') === 'Visiting Consultant' ? 'selected' : '' ?>>
              Visiting Consultant (Designated Consulting Days / Hours)
            </option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Doctor's Role at this Location</label>
          <input type="text" name="doctor_role" class="form-control" value="<?= $e($clinic['doctor_role'] ?? 'Senior Dental Surgeon') ?>" placeholder="e.g. Director & Principal Implantologist">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Full Street Address & Suite <span style="color:#ef4444;">*</span></label>
          <input type="text" name="full_address" class="form-control" value="<?= $e($clinic['full_address'] ?? '') ?>" placeholder="e.g. Suite 400, 742 Evergreen Healthcare Pavilion, Metro City, NY 10001" required>
        </div>

        <div class="form-group">
          <label class="form-label">Clinic Telephone Number</label>
          <input type="text" name="phone" class="form-control" value="<?= $e($clinic['phone'] ?? '') ?>" placeholder="+1 (555) 382-7491" required>
        </div>

        <div class="form-group">
          <label class="form-label">Facility Website URL (Optional)</label>
          <input type="url" name="website_url" class="form-control" value="<?= $e($clinic['website_url'] ?? '') ?>" placeholder="https://apexdentalexcellence.example.com">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Google Maps Directions / Embed Link (Optional)</label>
          <input type="text" name="map_url" class="form-control" value="<?= $e($clinic['map_url'] ?? '') ?>" placeholder="https://maps.google.com/?q=New+York+Dental+Center">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinic Facility Description & Scope</label>
          <textarea name="description" class="form-control" placeholder="Describe the facility operatories, available 3D scanners, surgery suites..."><?= $e($clinic['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinic Facility Photograph</label>
          <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
          <?php if (!empty($clinic['image'])): ?>
            <div class="img-preview-box" style="width:160px;height:100px;margin-top:0.5rem;">
              <img src="<?= $asset($clinic['image']) ?>" alt="">
            </div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Display Order</label>
          <input type="number" name="display_order" class="form-control" value="<?= (int)($clinic['display_order'] ?? 0) ?>">
        </div>

        <div class="form-group">
          <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-weight:600;margin-top:2rem;">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($clinic['is_active']) || $clinic['is_active']) ? 'checked' : '' ?>>
            <span>Active & Open for Consultation Inquiries</span>
          </label>
        </div>
      </div>

      <!-- Consulting Schedules Section -->
      <div style="border-top:1px solid var(--admin-border);padding-top:1.5rem;margin-top:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
          <div>
            <h4 style="font-size:1.1rem;color:var(--admin-navy);">Visiting Days & Consulting Timings</h4>
            <p style="font-size:0.85rem;color:var(--admin-text-muted);margin:0;">Configure the days and hours the doctor is present at this facility.</p>
          </div>
          <button type="button" class="btn btn-secondary btn-sm" id="addScheduleRowBtn">
            <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Schedule Row
          </button>
        </div>

        <div id="schedulesContainer">
          <?php if (empty($schedules)): ?>
            <!-- Default row -->
            <div class="form-grid schedule-row-item" style="padding:0.75rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
              <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Visiting Day(s)</label>
                <input type="text" name="visiting_day[]" class="form-control" placeholder="e.g. Monday to Thursday" value="Monday to Thursday">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Consultation Hours</label>
                <div style="display:flex;gap:0.5rem;align-items:center;">
                  <input type="text" name="start_time[]" class="form-control" placeholder="08:30 AM" value="08:30 AM">
                  <span>to</span>
                  <input type="text" name="end_time[]" class="form-control" placeholder="05:30 PM" value="05:30 PM">
                </div>
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($schedules as $s): ?>
              <div class="form-grid schedule-row-item" style="padding:0.75rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-bottom:0.75rem;">
                <div class="form-group" style="margin-bottom:0">
                  <label class="form-label">Visiting Day(s)</label>
                  <input type="text" name="visiting_day[]" class="form-control" value="<?= $e($s['visiting_day']) ?>">
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label class="form-label">Consultation Hours</label>
                  <div style="display:flex;gap:0.5rem;align-items:center;">
                    <input type="text" name="start_time[]" class="form-control" value="<?= $e($s['start_time']) ?>">
                    <span>to</span>
                    <input type="text" name="end_time[]" class="form-control" value="<?= $e($s['end_time']) ?>">
                    <button type="button" class="btn btn-danger btn-sm remove-schedule-btn">&times;</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div style="margin-top:2.5rem;display:flex;justify-content:flex-end;gap:1rem;">
        <a href="<?= $url('/admin/clinics') ?>" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update Clinic Affiliation' : 'Save Clinic Affiliation' ?></button>
      </div>
    </form>
  </div>
</div>
