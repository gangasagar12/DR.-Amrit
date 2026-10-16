<?php
/** @var array $doctor */
/** @var array $qualifications */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div>
      <div class="admin-card-title">Doctor Bio & Professional Credentials</div>
      <span style="font-size:0.85rem;color:var(--admin-text-muted);">Content directly synchronizes to public website</span>
    </div>
    <a href="<?= $url('/admin/account') ?>" class="btn btn-secondary btn-sm">
      <i data-lucide="shield-check" style="width:14px;height:14px;"></i> Login & Password
    </a>
  </div>

  <div class="admin-card-body">
    <form action="<?= $url('/admin/profile') ?>" method="POST" enctype="multipart/form-data">
      <?= $csrf() ?>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Doctor Full Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="doctor_name" class="form-control" value="<?= $e($doctor['doctor_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Professional Title / Credentials <span style="color:#ef4444;">*</span></label>
          <input type="text" name="professional_title" class="form-control" value="<?= $e($doctor['professional_title'] ?? '') ?>" placeholder="e.g. BDS, MDS - Dental Surgeon & Implantologist" required>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Hero Introduction Tagline</label>
          <input type="text" name="tagline" class="form-control" value="<?= $e($doctor['tagline'] ?? '') ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Detailed Professional Biography</label>
          <textarea name="biography" class="form-control" style="min-height:160px;"><?= $e($doctor['biography'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Clinical Experience (Years)</label>
          <input type="number" name="experience_years" class="form-control" value="<?= (int)($doctor['experience_years'] ?? 10) ?>" min="0">
        </div>

        <div class="form-group">
          <label class="form-label">Languages Spoken</label>
          <input type="text" name="languages" class="form-control" value="<?= $e($doctor['languages'] ?? 'English') ?>">
        </div>

        <div class="form-group form-full">
          <label class="form-label">Philosophy of Patient Care</label>
          <textarea name="philosophy" class="form-control"><?= $e($doctor['philosophy'] ?? '') ?></textarea>
        </div>

        <div class="form-group form-full">
          <label class="form-label">Clinical Focus & Procedures Summary</label>
          <textarea name="experience_description" class="form-control"><?= $e($doctor['experience_description'] ?? '') ?></textarea>
        </div>

        <!-- Contact details -->
        <div class="form-group">
          <label class="form-label">Primary Contact Email</label>
          <input type="email" name="contact_email" class="form-control" value="<?= $e($doctor['contact_email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">Primary Telephone Number</label>
          <input type="text" name="phone" class="form-control" value="<?= $e($doctor['phone'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label class="form-label">WhatsApp Contact Number (With Country Code)</label>
          <input type="text" name="whatsapp" class="form-control" value="<?= $e($doctor['whatsapp'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Emergency Dental Hotline Guidance</label>
          <input type="text" name="emergency_contact" class="form-control" value="<?= $e($doctor['emergency_contact'] ?? '') ?>">
        </div>

        <!-- Portrait upload -->
        <div class="form-group form-full">
          <label class="form-label">Doctor Portrait Photograph</label>
          <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/png,image/webp" data-preview="portraitPreview">
          <div class="form-help">Accepted formats: JPG, PNG, WebP. Recommended dimensions: 800x1000px.</div>

          <div class="img-preview-box" style="width:140px;height:160px;margin-top:0.75rem;">
            <?php 
              $currentPic = !empty($doctor['profile_image']) ? $asset($doctor['profile_image']) : 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=300&q=80';
            ?>
            <img id="portraitPreview" src="<?= $e($currentPic) ?>" alt="Doctor Portrait Preview">
          </div>
        </div>
      </div>

      <div style="margin-top:2rem;display:flex;justify-content:flex-end;">
        <button type="submit" class="btn btn-primary">
          <i data-lucide="save" style="width:16px;height:16px;"></i> Save Doctor Profile
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Academic & Clinical Qualifications Management -->
<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Educational Background & Dental Qualifications</div>
    <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('addQualModal').style.display='flex'">
      <i data-lucide="plus" style="width:14px;height:14px;"></i> Add Qualification
    </button>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Degree / Credential</th>
          <th>Institution</th>
          <th>Year</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($qualifications)): ?>
          <tr><td colspan="5" style="text-align:center;padding:2rem;">No qualifications added yet.</td></tr>
        <?php else: ?>
          <?php foreach ($qualifications as $q): ?>
            <tr>
              <td><strong><?= $e($q['degree']) ?></strong></td>
              <td><?= $e($q['institution']) ?></td>
              <td><span class="status-badge badge-pending"><?= $e($q['completion_year']) ?></span></td>
              <td style="max-width:300px;font-size:0.85rem;color:var(--admin-text-muted);"><?= $e($q['description']) ?></td>
              <td>
                <a href="<?= $url('/admin/profile/qualification/delete?id=' . $q['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Remove this qualification?">
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

<!-- Add Qualification Modal -->
<div id="addQualModal" class="modal-backdrop" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-header">
      <h3 class="modal-title">Add Dental Degree or Residency</h3>
      <button type="button" class="modal-close" onclick="document.getElementById('addQualModal').style.display='none'">&times;</button>
    </div>
    <div class="modal-body">
      <form action="<?= $url('/admin/profile/qualification/add') ?>" method="POST">
        <?= $csrf() ?>
        <div class="form-group">
          <label class="form-label">Degree / Fellowship Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="degree" class="form-control" placeholder="e.g. Master of Dental Surgery (MDS)" required>
        </div>
        <div class="form-group">
          <label class="form-label">Institution / University <span style="color:#ef4444;">*</span></label>
          <input type="text" name="institution" class="form-control" placeholder="e.g. King's College London Dental Institute" required>
        </div>
        <div class="form-group">
          <label class="form-label">Completion Year <span style="color:#ef4444;">*</span></label>
          <input type="text" name="completion_year" class="form-control" placeholder="e.g. 2015" required>
        </div>
        <div class="form-group">
          <label class="form-label">Description / Residency Specialization</label>
          <textarea name="description" class="form-control" placeholder="Key focus areas, honors, or thesis topics..."></textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.5rem;">
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('addQualModal').style.display='none'">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Qualification</button>
        </div>
      </form>
    </div>
  </div>
</div>
