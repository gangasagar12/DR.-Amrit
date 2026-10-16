/**
 * Dental Doctor Admin Panel JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Sidebar Slide & Collapse (Desktop & Mobile)
    const adminLayout = document.querySelector('.admin-layout');
    const adminSidebar = document.querySelector('.admin-sidebar');
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    // Restore desktop collapsed preference from localStorage
    const adminMain = document.querySelector('.admin-main');
    if (adminLayout && window.innerWidth > 992 && localStorage.getItem('admin_sidebar_collapsed') === 'true') {
        adminLayout.classList.add('sidebar-collapsed');
        if (adminSidebar) adminSidebar.classList.add('collapsed');
        if (adminMain) adminMain.classList.add('expanded');
    }

    function toggleSidebar() {
        if (!adminLayout || !adminSidebar) return;

        if (window.innerWidth <= 992) {
            // Mobile: toggle open drawer & backdrop
            const isOpen = adminSidebar.classList.toggle('open');
            if (sidebarBackdrop) {
                if (isOpen) {
                    sidebarBackdrop.classList.add('active');
                } else {
                    sidebarBackdrop.classList.remove('active');
                }
            }
        } else {
            // Desktop: slide sidebar off-screen for 100% full dashboard view
            const isCollapsed = adminLayout.classList.toggle('sidebar-collapsed');
            if (isCollapsed) {
                adminSidebar.classList.add('collapsed');
                if (adminMain) adminMain.classList.add('expanded');
            } else {
                adminSidebar.classList.remove('collapsed');
                if (adminMain) adminMain.classList.remove('expanded');
            }
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        }
    }

    if (sidebarToggleBtn) {
        sidebarToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    function closeMobileSidebar() {
        if (adminSidebar) adminSidebar.classList.remove('open');
        if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (sidebarCollapseBtn) {
        sidebarCollapseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.innerWidth <= 992) {
                closeMobileSidebar();
            } else {
                adminLayout.classList.add('sidebar-collapsed');
                adminSidebar.classList.add('collapsed');
                if (adminMain) adminMain.classList.add('expanded');
                localStorage.setItem('admin_sidebar_collapsed', 'true');
            }
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
    }

    // Auto-close drawer on mobile when navigating
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                closeMobileSidebar();
            }
        });
    });

    // Reset mobile state on desktop resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            closeMobileSidebar();
        }
    });

    // 2. Auto Slug Generator (for blogs, services, clinics)
    const titleInputs = document.querySelectorAll('[data-slug-source]');
    titleInputs.forEach(sourceInput => {
        const targetSlugId = sourceInput.getAttribute('data-slug-source');
        const targetInput = document.getElementById(targetSlugId);

        if (targetInput && (!targetInput.value || targetInput.value.trim() === '')) {
            sourceInput.addEventListener('input', function() {
                const slug = sourceInput.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                targetInput.value = slug;
            });
        }
    });

    // 3. Dynamic Clinic Schedule Rows
    const scheduleContainer = document.getElementById('schedulesContainer');
    const addScheduleBtn = document.getElementById('addScheduleRowBtn');

    if (scheduleContainer && addScheduleBtn) {
        addScheduleBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'form-grid schedule-row-item';
            newRow.style.marginTop = '0.75rem';
            newRow.style.padding = '0.75rem';
            newRow.style.background = '#f8fafc';
            newRow.style.borderRadius = '8px';
            newRow.style.border = '1px solid #e2e8f0';

            newRow.innerHTML = `
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Visiting Day(s)</label>
                    <input type="text" name="visiting_day[]" class="form-control" placeholder="e.g. Mon, Wed, Fri" required>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Consultation Hours</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <input type="text" name="start_time[]" class="form-control" placeholder="09:00 AM">
                        <span>to</span>
                        <input type="text" name="end_time[]" class="form-control" placeholder="05:00 PM">
                        <button type="button" class="btn btn-danger btn-sm remove-schedule-btn" title="Remove">&times;</button>
                    </div>
                </div>
            `;
            scheduleContainer.appendChild(newRow);

            // Bind remove button
            newRow.querySelector('.remove-schedule-btn').addEventListener('click', function() {
                newRow.remove();
            });
        });

        // Bind existing remove buttons
        scheduleContainer.querySelectorAll('.remove-schedule-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                btn.closest('.schedule-row-item').remove();
            });
        });
    }

    // 4. Destructive Action Confirmation
    const deleteLinks = document.querySelectorAll('[data-confirm]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = link.getAttribute('data-confirm') || 'Are you sure you want to perform this action?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // 5. Image File Preview
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        const previewTargetId = input.getAttribute('data-preview');
        const previewTarget = document.getElementById(previewTargetId);

        if (previewTarget) {
            input.addEventListener('change', function() {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewTarget.src = e.target.result;
                        previewTarget.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
    });

    // 6. Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
