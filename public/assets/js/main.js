/**
 * Dental Doctor Portfolio - Main Public JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Mobile Navigation Toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mainNav = document.querySelector('.main-nav');

    if (mobileToggle && mainNav) {
        mobileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            mainNav.classList.toggle('open');
        });

        // Close on link click
        mainNav.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mainNav.classList.remove('open');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!mainNav.contains(e.target) && !mobileToggle.contains(e.target)) {
                mainNav.classList.remove('open');
            }
        });
    }

    // 2. Appointment Booking Modal Handlers
    const modalBackdrop = document.getElementById('appointmentModal');
    const openModalBtns = document.querySelectorAll('.open-appointment-modal');
    const closeModalBtns = document.querySelectorAll('.modal-close, .close-modal-btn');

    if (modalBackdrop) {
        openModalBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Pre-fill service or clinic if present on data attributes
                const serviceId = btn.getAttribute('data-service-id');
                const clinicId = btn.getAttribute('data-clinic-id');

                if (serviceId) {
                    const serviceSelect = modalBackdrop.querySelector('#modal_service_id');
                    if (serviceSelect) serviceSelect.value = serviceId;
                }
                if (clinicId) {
                    const clinicSelect = modalBackdrop.querySelector('#modal_clinic_id');
                    if (clinicSelect) clinicSelect.value = clinicId;
                }

                modalBackdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modalBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close when clicking backdrop
        modalBackdrop.addEventListener('click', function(e) {
            if (e.target === modalBackdrop) {
                modalBackdrop.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // 3. AJAX Appointment Form Submissions (Smooth UX without reload)
    const appointmentForms = document.querySelectorAll('.ajax-appointment-form');
    appointmentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Submitting Request...';
            }

            const formData = new FormData(form);
            const actionUrl = form.getAttribute('action') || '/appointment/book';

            fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }

                if (data.success) {
                    // Show success banner
                    showToast(data.message, 'success');
                    form.reset();
                    if (modalBackdrop) {
                        modalBackdrop.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                } else {
                    showToast(data.message || 'Error submitting request.', 'error');
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                // Fallback normal submission if fetch fails
                form.submit();
            });
        });
    });

    // 4. Simple Gallery Lightbox
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length > 0) {
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const img = item.querySelector('img');
                const caption = item.getAttribute('data-caption') || (img ? img.alt : '');
                const imgSrc = img ? img.src : '';

                if (imgSrc) {
                    showImageLightbox(imgSrc, caption);
                }
            });
        });
    }

    function showImageLightbox(src, caption) {
        let lightbox = document.getElementById('galleryLightbox');
        if (!lightbox) {
            lightbox = document.createElement('div');
            lightbox.id = 'galleryLightbox';
            lightbox.className = 'modal-backdrop active';
            lightbox.innerHTML = `
                <div style="max-width: 900px; width: 100%; text-align: center; position: relative;">
                    <button class="modal-close" style="position: absolute; right: 10px; top: -40px; color: #fff; font-size: 2rem;">&times;</button>
                    <img src="${src}" style="max-height: 80vh; max-width: 100%; border-radius: 12px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <p style="color: #fff; margin-top: 1rem; font-size: 1rem; font-weight: 500;">${caption}</p>
                </div>
            `;
            document.body.appendChild(lightbox);
            document.body.style.overflow = 'hidden';

            lightbox.querySelector('.modal-close').addEventListener('click', () => {
                lightbox.remove();
                document.body.style.overflow = '';
            });
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.remove();
                    document.body.style.overflow = '';
                }
            });
        }
    }

    // 5. Toast Notification System
    function showToast(message, type = 'info') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.style.position = 'fixed';
            container.style.bottom = '24px';
            container.style.right = '24px';
            container.style.zIndex = '9999';
            container.style.display = 'flex';
            container.style.flexDirection = 'column';
            container.style.gap = '10px';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `alert alert-${type}`;
        toast.style.margin = '0';
        toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        toast.style.minWidth = '300px';
        toast.style.maxWidth = '450px';
        toast.style.animation = 'fadeIn 0.3s ease';
        toast.innerHTML = `<div>${message}</div>`;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 5000);
    }

    // 6. Testimonials Marquee Interaction (Pause on Touch / Tap)
    const marqueeWrap = document.getElementById('testimonialsMarquee');
    if (marqueeWrap) {
        let resumeTimer = null;
        marqueeWrap.addEventListener('touchstart', function() {
            clearTimeout(resumeTimer);
            marqueeWrap.classList.add('is-paused');
        }, { passive: true });

        marqueeWrap.addEventListener('touchend', function() {
            clearTimeout(resumeTimer);
            resumeTimer = setTimeout(function() {
                marqueeWrap.classList.remove('is-paused');
            }, 1500);
        }, { passive: true });
    }

    // 7. Hero Section Smooth Photo Crossfade (Fade In & Fade Out Transitions)
    const heroCrossfader = document.getElementById('heroCrossfader');
    if (heroCrossfader) {
        const items = heroCrossfader.querySelectorAll('.hero-fade-item');
        let currentIndex = 0;
        let fadeTimer = null;

        function nextPhoto() {
            if (!items.length) return;
            const nextIndex = (currentIndex + 1) % items.length;
            items[currentIndex].classList.remove('active');
            items[nextIndex].classList.add('active');
            currentIndex = nextIndex;
        }

        if (items.length > 1) {
            fadeTimer = setInterval(nextPhoto, 4200);
        }
    }

    // 8. Sticky Header Scroll Shadow
    const siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        function checkScroll() {
            if (window.scrollY > 15) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }
        }
        window.addEventListener('scroll', checkScroll, { passive: true });
        checkScroll();
    }

    // 9. Smooth Fade-In Animation While Scrolling Across All Pages
    const animElements = document.querySelectorAll(`
        .section-header,
        .about-theme-img-wrap,
        .about-theme-info,
        .about-mini-card,
        .service-card,
        .service-theme-item,
        .clinic-card,
        .clinic-full-card,
        .blog-card,
        .path-step-card,
        .contact-form-card,
        .appointment-banner,
        .service-detail-body,
        .award-banner-card,
        .fade-in-scroll
    `);

    if ('IntersectionObserver' in window && animElements.length) {
        const appearOptions = {
            threshold: 0.08,
            rootMargin: '0px 0px -40px 0px'
        };

        const appearOnScroll = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, appearOptions);

        animElements.forEach(el => {
            el.classList.add('fade-in-scroll');
            appearOnScroll.observe(el);
        });
    }

    // 10. Initialize Lucide icons if loaded
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
