<?php
/** @var array $messages */
?>

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">Patient Inquiries & Website Messages</div>
    <span style="font-size:0.85rem;color:var(--admin-text-muted);">Total received: <?= count($messages) ?></span>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Sender</th>
          <th>Subject</th>
          <th>Message Preview</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($messages)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2.5rem;">No contact messages received yet.</td></tr>
        <?php else: ?>
          <?php foreach ($messages as $msg): ?>
            <tr style="<?= $msg['is_read'] ? '' : 'background-color:#f0fdfa;' ?>">
              <td>
                <strong style="color:var(--admin-navy);"><?= $e($msg['name']) ?></strong>
                <div style="font-size:0.785rem;color:var(--admin-text-muted);"><a href="mailto:<?= $e($msg['email']) ?>"><?= $e($msg['email']) ?></a></div>
                <?php if (!empty($msg['phone'])): ?>
                  <div style="font-size:0.75rem;color:var(--admin-teal);"><?= $e($msg['phone']) ?></div>
                <?php endif; ?>
              </td>
              <td><strong><?= $e($msg['subject']) ?></strong></td>
              <td style="max-width:320px;font-size:0.875rem;line-height:1.4;">
                <?= $e(mb_substr($msg['message'], 0, 110)) ?>...
              </td>
              <td style="font-size:0.85rem;color:var(--admin-text-muted);white-space:nowrap;">
                <?= date('M d, Y', strtotime($msg['created_at'])) ?>
              </td>
              <td>
                <?php if ($msg['is_read']): ?>
                  <span class="status-badge badge-draft">Read</span>
                <?php else: ?>
                  <span class="status-badge badge-published" style="background:#ccfbf1;color:#0f766e;">New Unread</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-buttons">
                  <?php if (!$msg['is_read']): ?>
                    <a href="<?= $url('/admin/messages/read?id=' . $msg['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-secondary btn-sm" title="Mark as Read">
                      <i data-lucide="check" style="width:13px;height:13px;"></i> Read
                    </a>
                  <?php else: ?>
                    <a href="<?= $url('/admin/messages/unread?id=' . $msg['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-secondary btn-sm" title="Mark as Unread">
                      Unread
                    </a>
                  <?php endif; ?>
                  
                  <button type="button" class="btn btn-secondary btn-sm" onclick="viewMessageModal('<?= $e(addslashes($msg['name'])) ?>', '<?= $e(addslashes($msg['email'])) ?>', '<?= $e(addslashes($msg['phone'] ?? '')) ?>', '<?= $e(addslashes($msg['subject'])) ?>', `<?= $e(addslashes($msg['message'])) ?>`)">
                    View
                  </button>

                  <a href="<?= $url('/admin/messages/delete?id=' . $msg['id'] . '&_csrf_token=' . $csrf_token()) ?>" class="btn btn-danger btn-sm" data-confirm="Delete message?">
                    <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Message Detail Modal -->
<div id="msgModal" class="modal-backdrop" style="display:none;">
  <div class="modal-dialog">
    <div class="modal-header">
      <h3 class="modal-title" id="msgModalSubject">Message Subject</h3>
      <button type="button" class="modal-close" onclick="document.getElementById('msgModal').style.display='none'">&times;</button>
    </div>
    <div class="modal-body">
      <div style="background:#f8fafc;padding:1rem;border-radius:8px;margin-bottom:1.25rem;font-size:0.9rem;">
        <div><strong>Sender:</strong> <span id="msgModalSender"></span></div>
        <div><strong>Email:</strong> <span id="msgModalEmail"></span></div>
        <div><strong>Phone:</strong> <span id="msgModalPhone"></span></div>
      </div>
      <div style="line-height:1.7;color:var(--admin-navy);white-space:pre-wrap;" id="msgModalBody"></div>
      <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('msgModal').style.display='none'">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function viewMessageModal(name, email, phone, subject, message) {
  document.getElementById('msgModalSubject').innerText = subject;
  document.getElementById('msgModalSender').innerText = name;
  document.getElementById('msgModalEmail').innerText = email;
  document.getElementById('msgModalPhone').innerText = phone || 'Not provided';
  document.getElementById('msgModalBody').innerText = message;
  document.getElementById('msgModal').style.display = 'flex';
}
</script>
