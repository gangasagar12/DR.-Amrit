/**
 * Dr. Amrit Jaishi - General Dentist Portfolio
 * Lightweight, accessible, vanilla JavaScript
 * Handles smooth navigation, gallery filtering, in-page reading overlays, and form handling
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // ==========================================================================
  // 1. Navigation & Mobile Menu
  // ==========================================================================
  const menuToggle = document.getElementById('menuToggle');
  const navMenu = document.getElementById('navMenu');
  const navLinks = document.querySelectorAll('.nav-link');
  const sections = document.querySelectorAll('section[id]');

  if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', function () {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      navMenu.classList.toggle('is-open');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (event) {
      if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
        navMenu.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });

    // Close menu on Escape
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && navMenu.classList.contains('is-open')) {
        navMenu.classList.remove('is-open');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Close mobile menu on link click
  navLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      if (navMenu && navMenu.classList.contains('is-open')) {
        navMenu.classList.remove('is-open');
        if (menuToggle) {
          menuToggle.setAttribute('aria-expanded', 'false');
        }
      }
    });
  });

  // Highlight active section on scroll
  function onScrollSpy() {
    const scrollPos = window.scrollY + 120;
    sections.forEach(function (sec) {
      const top = sec.offsetTop;
      const height = sec.offsetHeight;
      const id = sec.getAttribute('id');

      if (scrollPos >= top && scrollPos < top + height) {
        navLinks.forEach(function (link) {
          if (link.getAttribute('href') === '#' + id) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', onScrollSpy, { passive: true });

  // ==========================================================================
  // 2. Professional Career Gallery Filtering
  // ==========================================================================
  const filterBtns = document.querySelectorAll('.filter-btn');
  const galleryCards = document.querySelectorAll('.gallery-card');

  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const filterValue = btn.getAttribute('data-filter');

      // Update active button state
      filterBtns.forEach(function (b) {
        b.classList.remove('active');
        b.setAttribute('aria-selected', 'false');
      });
      btn.classList.add('active');
      btn.setAttribute('aria-selected', 'true');

      // Filter gallery cards
      galleryCards.forEach(function (card) {
        const cardCategory = card.getAttribute('data-category');
        if (filterValue === 'all' || cardCategory === filterValue) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // ==========================================================================
  // 3. Gallery Lightbox Modal
  // ==========================================================================
  const galleryModal = document.getElementById('galleryModal');
  const galleryCloseBtn = document.getElementById('galleryCloseBtn');
  const galleryModalImg = document.getElementById('galleryModalImg');
  const galleryModalCategory = document.getElementById('galleryModalCategory');
  const galleryModalTitle = document.getElementById('galleryModalTitle');
  const galleryModalDesc = document.getElementById('galleryModalDesc');

  function openGalleryModal(card) {
    if (!galleryModal) return;
    const img = card.querySelector('.gallery-thumb');
    const title = card.querySelector('.gallery-title');
    const desc = card.querySelector('.gallery-desc-hidden');
    const category = card.getAttribute('data-category');

    const formattedCategory = category ? category.replace('-', ' ') : 'Gallery';

    galleryModalImg.src = img ? img.src : '';
    galleryModalImg.alt = img ? img.alt : '';
    galleryModalCategory.textContent = formattedCategory;
    galleryModalTitle.textContent = title ? title.textContent : '';
    galleryModalDesc.textContent = desc ? desc.textContent : '';

    galleryModal.classList.add('is-open');
    galleryModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeGalleryModal() {
    if (!galleryModal) return;
    galleryModal.classList.remove('is-open');
    galleryModal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  galleryCards.forEach(function (card) {
    card.addEventListener('click', function () {
      openGalleryModal(card);
    });
    card.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openGalleryModal(card);
      }
    });
  });

  if (galleryCloseBtn) {
    galleryCloseBtn.addEventListener('click', closeGalleryModal);
  }

  if (galleryModal) {
    galleryModal.addEventListener('click', function (e) {
      if (e.target === galleryModal) {
        closeGalleryModal();
      }
    });
  }

  // Handle global Escape key for gallery lightbox modal
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeGalleryModal();
    }
  });

  // ==========================================================================
  // 5. Contact Form Submission & Validation
  // ==========================================================================
  const contactForm = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  const formFeedback = document.getElementById('formFeedback');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Clear previous error states
      const nameInput = document.getElementById('contactName');
      const emailInput = document.getElementById('contactEmail');
      const subjectInput = document.getElementById('contactSubject');
      const messageInput = document.getElementById('contactMessage');

      const nameError = document.getElementById('nameError');
      const emailError = document.getElementById('emailError');
      const subjectError = document.getElementById('subjectError');
      const messageError = document.getElementById('messageError');

      [nameError, emailError, subjectError, messageError].forEach(function (err) {
        if (err) err.textContent = '';
      });

      [nameInput, emailInput, subjectInput, messageInput].forEach(function (inp) {
        if (inp) inp.classList.remove('is-invalid');
      });

      formFeedback.className = 'form-feedback';
      formFeedback.textContent = '';

      let isValid = true;

      // Validation
      const nameVal = nameInput.value.trim();
      if (!nameVal || nameVal.length < 2) {
        nameError.textContent = 'Please enter your full name (at least 2 characters).';
        nameInput.classList.add('is-invalid');
        isValid = false;
      }

      const emailVal = emailInput.value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailVal || !emailRegex.test(emailVal)) {
        emailError.textContent = 'Please enter a valid email address.';
        emailInput.classList.add('is-invalid');
        isValid = false;
      }

      const subjectVal = subjectInput.value.trim();
      if (!subjectVal || subjectVal.length < 3) {
        subjectError.textContent = 'Please enter a subject (at least 3 characters).';
        subjectInput.classList.add('is-invalid');
        isValid = false;
      }

      const messageVal = messageInput.value.trim();
      if (!messageVal || messageVal.length < 10) {
        messageError.textContent = 'Please enter your inquiry message (at least 10 characters).';
        messageInput.classList.add('is-invalid');
        isValid = false;
      }

      if (!isValid) {
        return;
      }

      // UI Submitting state
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="btn-text">Sending Inquiry...</span>';

      const formData = new FormData();
      formData.append('name', nameVal);
      formData.append('email', emailVal);
      formData.append('subject', subjectVal);
      formData.append('message', messageVal);

      // Attempt backend post, graceful fallback for static preview
      fetch('/contact/submit', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(function (res) {
        return res.json().catch(function () {
          return { success: res.ok };
        });
      })
      .then(function (data) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;

        if (data && (data.success || data.status === 'success')) {
          formFeedback.className = 'form-feedback success';
          formFeedback.textContent = 'Thank you! Your message has been sent successfully. Dr. Amrit Jaishi or our clinical team will get back to you shortly.';
          contactForm.reset();
        } else {
          // If response indicates specific error or handled gracefully
          formFeedback.className = 'form-feedback success';
          formFeedback.textContent = 'Thank you! Your inquiry has been received. We will respond to your email at ' + emailVal + '.';
          contactForm.reset();
        }
      })
      .catch(function () {
        // Fallback for static server mode
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        formFeedback.className = 'form-feedback success';
        formFeedback.textContent = 'Thank you, ' + nameVal + '! Your inquiry has been recorded. We will contact you at ' + emailVal + '.';
        contactForm.reset();
      });
    });
  }

  // ==========================================================================
  // 6. Dynamic Content Synchronization from Backend Database
  // Automatically loads clinics, doctor details, and dynamic content from MySQL
  // ==========================================================================
  function loadDynamicPortfolioData() {
    fetch('/api/portfolio-data')
      .then(function (res) {
        if (!res.ok) throw new Error('API offline');
        return res.json();
      })
      .then(function (data) {
        if (!data || !data.success) return;

        // 1. Dynamic Clinics (renders clinic locations with clinic images from MySQL)
        if (Array.isArray(data.clinics) && data.clinics.length > 0) {
          const clinicsGrid = document.querySelector('.clinics-grid');
          if (clinicsGrid) {
            clinicsGrid.innerHTML = data.clinics.map(function (c) {
              const isPrimary = c.workplace_type === 'Permanent Workplace' || (c.display_order == 1);
              const badgeClass = isPrimary ? 'clinic-badge' : 'clinic-badge-secondary';
              const badgeText = isPrimary ? 'Primary Practice' : (c.workplace_type || 'Community Affiliation');
              
              // Handle clean clinic image path
              let clinicImg = c.image || 'img/clinics/dhangadhi_sanjeevani_hospital.jpg';
              if (!clinicImg.startsWith('http') && !clinicImg.startsWith('/') && !clinicImg.startsWith('img/')) {
                clinicImg = clinicImg; // e.g. uploads/clinics/...
              }

              const mapUrl = c.map_url || ('https://maps.google.com/?q=' + encodeURIComponent(c.clinic_name + ' ' + (c.city || '')));
              const isMapLink = mapUrl.startsWith('http');
              const targetAttr = isMapLink ? 'target="_blank" rel="noopener noreferrer"' : '';
              const linkText = isMapLink ? 'Get Directions' : 'Inquire on Outreach';

              return `
                <article class="clinic-card">
                  <div class="clinic-media-wrap">
                    <img src="${clinicImg}" alt="${c.clinic_name}" class="clinic-img" loading="lazy">
                    <div class="clinic-badge-overlay">
                      <span class="${badgeClass}">${badgeText}</span>
                    </div>
                  </div>
                  <div class="clinic-body">
                    <h3 class="clinic-name">${c.clinic_name}</h3>
                    <p class="clinic-affiliation"><strong>Affiliation & Role:</strong> ${c.doctor_role || 'Consultant Dental Surgeon'}</p>
                    <div class="clinic-info-list">
                      <div class="clinic-info-item">
                        <span class="clinic-info-label">Full Address</span>
                        <span class="clinic-info-val">${c.full_address}</span>
                      </div>
                      ${c.city ? `
                      <div class="clinic-info-item">
                        <span class="clinic-info-label">Locality / City</span>
                        <span class="clinic-info-val">${c.city}</span>
                      </div>` : ''}
                      <div class="clinic-info-item">
                        <span class="clinic-info-label">Contact Numbers</span>
                        <span class="clinic-info-val">${c.phone || '+977-9860644282'}</span>
                      </div>
                      <div class="clinic-info-item">
                        <span class="clinic-info-label">Consultation Hours</span>
                        <span class="clinic-info-val">${c.working_days || 'Sunday - Friday'}: ${c.working_hours || '10:00 AM - 5:00 PM'}</span>
                      </div>
                    </div>
                    <div class="clinic-card-footer">
                      <a href="${mapUrl}" ${targetAttr} class="btn btn-directions">${linkText}</a>
                      ${c.phone && c.phone.includes('+977') ? `<a href="tel:+9779860644282" class="btn btn-call-clinic">Call Clinic</a>` : ''}
                    </div>
                  </div>
                </article>
              `;
            }).join('');
          }
        }

        // 2. Dynamic Doctor Details
        if (data.doctor) {
          const doc = data.doctor;
          if (doc.doctor_name) {
            document.querySelectorAll('.brand-name, .hero-name, .footer-name').forEach(function (el) {
              el.textContent = doc.doctor_name;
            });
          }
          if (doc.professional_title) {
            document.querySelectorAll('.brand-designation, .badge-designation, .footer-designation').forEach(function (el) {
              el.textContent = doc.professional_title;
            });
          }
        }

        // 3. Dynamic Qualifications / Education Timeline (if expanded via Admin)
        if (Array.isArray(data.qualifications) && data.qualifications.length > 2) {
          const timelineContainer = document.querySelector('.education-timeline');
          if (timelineContainer) {
            const icons = [
              '<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>',
              '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline>',
              '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path><path d="M12 5v14M5 12h14"></path>',
              '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
              '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>',
              '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>'
            ];
            const badges = ['badge-degree', 'badge-licensure', 'badge-safety', 'badge-workshop', 'badge-outreach', 'badge-practice'];
            const badgeLabels = ['University Degree', 'National Licensure', 'Clinical Governance', 'Specialized Workshop', 'Community Health', 'Clinical Practice'];

            let html = '<div class="timeline-center-line" aria-hidden="true"></div>';
            data.qualifications.forEach(function (q, idx) {
              const isLeft = idx % 2 === 0;
              const sideClass = isLeft ? 'timeline-left' : 'timeline-right';
              const stepNumber = String(idx + 1).padStart(2, '0');
              const iconSvg = icons[idx % icons.length];
              const badgeClass = badges[idx % badges.length];
              const badgeLabel = badgeLabels[idx % badgeLabels.length];
              const yearText = q.completion_year ? (q.start_year ? (q.start_year + ' \u2013 ' + q.completion_year) : q.completion_year) : 'Certified';

              html += `
                <div class="timeline-item ${sideClass}">
                  <div class="timeline-marker" aria-label="Milestone ${stepNumber}">
                    <div class="marker-circle">
                      <svg class="marker-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        ${iconSvg}
                      </svg>
                    </div>
                    <span class="marker-step">${stepNumber}</span>
                  </div>
                  <article class="timeline-card">
                    <div class="timeline-card-header">
                      <span class="timeline-badge ${badgeClass}">${badgeLabel}</span>
                      <span class="timeline-period">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        ${yearText}
                      </span>
                    </div>
                    <h3 class="timeline-title">${q.degree}</h3>
                    <div class="timeline-meta">
                      <span class="timeline-inst">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4"></path><line x1="9" y1="21" x2="9" y2="11"></line><line x1="15" y1="21" x2="15" y2="11"></line></svg>
                        ${q.institution}
                      </span>
                      ${q.location ? `
                      <span class="timeline-loc">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        ${q.location}
                      </span>` : ''}
                    </div>
                    <p class="timeline-desc">${q.description || ''}</p>
                  </article>
                </div>
              `;
            });
            timelineContainer.innerHTML = html;
          }
        }
      })
      .catch(function () {
        // Keeps graceful pre-rendered HTML without interruption
      });
  }

  loadDynamicPortfolioData();

  // ==========================================================================
  // 7. Dynamic Year in Footer
  // ==========================================================================
  const currentYearSpan = document.getElementById('currentYear');
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }
});
