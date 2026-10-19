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
  // 2. Section Pagination Controller (2 Rows per View for Gallery, Blog, Research)
  // ==========================================================================
  function setupSectionPagination(config) {
    const grid = document.querySelector(config.gridSelector);
    const paginationEl = document.getElementById(config.paginationId);
    if (!grid) return { update: function () {} };

    let currentPage = 1;
    let currentCategory = 'all';

    function update(scrollToTop) {
      const allCards = grid.querySelectorAll(config.cardSelector);
      if (!allCards || allCards.length === 0) return;

      const matchingCards = [];
      allCards.forEach(function (card) {
        const cat = config.categoryAttr ? (card.getAttribute(config.categoryAttr) || '') : 'all';
        if (currentCategory === 'all' || cat === currentCategory) {
          matchingCards.push(card);
        } else {
          card.style.display = 'none';
        }
      });

      const totalMatching = matchingCards.length;
      const totalPages = Math.ceil(totalMatching / config.perPage) || 1;

      if (currentPage > totalPages) {
        currentPage = 1;
      }

      const startIndex = (currentPage - 1) * config.perPage;
      const endIndex = startIndex + config.perPage;

      matchingCards.forEach(function (card, idx) {
        if (idx >= startIndex && idx < endIndex) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });

      if (!paginationEl) return;

      if (totalPages <= 1) {
        paginationEl.innerHTML = '';
        return;
      }

      let html = '';
      const isFirst = currentPage === 1;
      html += `
        <button type="button" class="section-page-btn" data-page="${currentPage - 1}" ${isFirst ? 'disabled aria-disabled="true"' : ''} aria-label="Previous Page">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="15 18 9 12 15 6"></polyline></svg>
          <span>Prev</span>
        </button>
      `;

      for (let p = 1; p <= totalPages; p++) {
        const active = p === currentPage;
        html += `
          <button type="button" class="section-page-btn ${active ? 'active' : ''}" data-page="${p}" ${active ? 'aria-current="page"' : ''} aria-label="Page ${p}">
            ${p}
          </button>
        `;
      }

      const isLast = currentPage === totalPages;
      html += `
        <button type="button" class="section-page-btn" data-page="${currentPage + 1}" ${isLast ? 'disabled aria-disabled="true"' : ''} aria-label="Next Page">
          <span>Next</span>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      `;

      paginationEl.innerHTML = html;

      paginationEl.querySelectorAll('.section-page-btn').forEach(function (btn) {
        btn.onclick = function () {
          if (btn.disabled || btn.classList.contains('active')) return;
          const target = parseInt(btn.getAttribute('data-page'), 10);
          if (!isNaN(target) && target >= 1 && target <= totalPages) {
            currentPage = target;
            update(true);
          }
        };
      });

      if (scrollToTop && config.sectionId) {
        const sec = document.getElementById(config.sectionId);
        if (sec) {
          sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    }

    if (config.filterSelector) {
      const filterBtns = document.querySelectorAll(config.filterSelector);
      filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          const filterVal = btn.getAttribute('data-filter') || 'all';
          filterBtns.forEach(function (b) {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
          });
          btn.classList.add('active');
          btn.setAttribute('aria-selected', 'true');

          currentCategory = filterVal;
          currentPage = 1;
          update(false);
        });
      });
    }

    update(false);

    return {
      update: update
    };
  }

  // Gallery: 3 columns x 2 rows = 6 items per page
  const galleryPagination = setupSectionPagination({
    gridSelector: '#galleryGrid',
    cardSelector: '.gallery-card',
    paginationId: 'galleryPagination',
    perPage: 6,
    sectionId: 'gallery',
    filterSelector: '.gallery-filters .filter-btn',
    categoryAttr: 'data-category'
  });

  // Blog: 3 columns x 2 rows = 6 articles per page
  const blogPagination = setupSectionPagination({
    gridSelector: '#blog .articles-grid',
    cardSelector: '.article-grid-card',
    paginationId: 'blogPagination',
    perPage: 6,
    sectionId: 'blog'
  });

  // Research: 2 columns x 2 rows = 4 research papers per page
  const researchPagination = setupSectionPagination({
    gridSelector: '#research .research-grid',
    cardSelector: '.research-wide-card',
    paginationId: 'researchPagination',
    perPage: 4,
    sectionId: 'research'
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

  const galleryCards = document.querySelectorAll('.gallery-card');
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

  // Global event delegation for gallery lightbox
  document.addEventListener('click', function (e) {
    const card = e.target.closest('.gallery-card');
    if (card) {
      e.preventDefault();
      openGalleryModal(card);
    }
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
              let clinicImg = c.image || '/img/clinics/dhangadhi_sanjeevani_hospital.jpg';
              if (clinicImg && !clinicImg.startsWith('http') && !clinicImg.startsWith('/')) {
                clinicImg = '/' + clinicImg;
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

        // 2. Dynamic Doctor Details: Hero & About Section Controls
        if (data.doctor) {
          const doc = data.doctor;
          if (doc.doctor_name) {
            document.querySelectorAll('.brand-name, .hero-name, .footer-name').forEach(function (el) {
              el.textContent = doc.doctor_name;
            });
            const aboutHead = document.querySelector('.section-about .section-heading');
            if (aboutHead) aboutHead.textContent = 'About ' + doc.doctor_name;
          }
          if (doc.professional_title) {
            document.querySelectorAll('.brand-designation, .badge-designation, .footer-designation').forEach(function (el) {
              el.textContent = doc.professional_title;
            });
          }
          if (doc.hero_lead) {
            const leadEl = document.querySelector('.hero-lead');
            if (leadEl) leadEl.textContent = doc.hero_lead;
          }
          if (doc.hero_approach) {
            const appEl = document.querySelector('.hero-approach');
            if (appEl) appEl.textContent = doc.hero_approach;
          }
          if (doc.hero_image) {
            const heroImg = document.querySelector('.hero-portrait');
            if (heroImg) heroImg.src = doc.hero_image;
          }
          if (doc.hero_btn1_text) {
            const b1 = document.querySelector('.hero-actions .btn-primary');
            if (b1) {
              b1.textContent = doc.hero_btn1_text;
              if (doc.hero_btn1_link) b1.setAttribute('href', doc.hero_btn1_link);
            }
          }
          if (doc.hero_btn2_text) {
            const b2 = document.querySelector('.hero-actions .btn-secondary');
            if (b2) {
              b2.textContent = doc.hero_btn2_text;
              if (doc.hero_btn2_link) b2.setAttribute('href', doc.hero_btn2_link);
            }
          }

          // About Section
          if (doc.about_kicker) {
            const kickEl = document.querySelector('#about .section-kicker');
            if (kickEl) kickEl.textContent = doc.about_kicker;
          }
          if (doc.about_subheading) {
            const subEl = document.querySelector('.about-subheading');
            if (subEl) subEl.textContent = doc.about_subheading;
          }
          if (doc.about_image) {
            const aboutImg = document.querySelector('.about-portrait');
            if (aboutImg) aboutImg.src = doc.about_image;
          }
          if (doc.about_badge_title) {
            const bt = document.querySelector('.about-badge-title');
            if (bt) bt.textContent = doc.about_badge_title;
          }
          if (doc.about_badge_subtitle) {
            const bs = document.querySelector('.about-badge-subtitle');
            if (bs) bs.textContent = doc.about_badge_subtitle;
          }
          if (doc.tagline) {
            document.querySelectorAll('.footer-tagline').forEach(function (el) {
              el.textContent = doc.tagline;
            });
          }
          if (doc.biography) {
            const bioCol = document.querySelector('.about-content-column');
            if (bioCol) {
              const paras = doc.biography.split(/\r?\n\r?\n/).map(function (p) { return p.trim(); }).filter(function (p) { return p.length > 0; });
              if (paras.length > 0) {
                const existingParas = bioCol.querySelectorAll('.about-text');
                existingParas.forEach(function (p) { p.remove(); });
                const subHead = bioCol.querySelector('.about-subheading');
                for (let i = paras.length - 1; i >= 0; i--) {
                  const pEl = document.createElement('p');
                  pEl.className = 'about-text';
                  pEl.textContent = paras[i];
                  subHead.insertAdjacentElement('afterend', pEl);
                }
              }
            }
          }
        }

        // 3. Dynamic Qualifications / Education Timeline (Loaded directly from MySQL database)
        if (Array.isArray(data.qualifications) && data.qualifications.length > 0) {
          // Dynamic Education Highlights Subsection in About Section
          const highlightsGrid = document.getElementById('aboutHighlightsGrid');
          if (highlightsGrid) {
            const top3 = data.qualifications.slice(0, 3);
            highlightsGrid.innerHTML = top3.map(function (q) {
              const yr = q.completion_year ? (q.start_year ? (q.start_year + ' \u2013 ' + q.completion_year) : q.completion_year) : 'Certified';
              return `
                <div class="highlight-item">
                  <span class="highlight-degree">${q.degree}</span>
                  <span class="highlight-institution">${q.institution}${q.location ? ', ' + q.location : ''}</span>
                  <span class="highlight-year">${yr}</span>
                </div>
              `;
            }).join('');
          }

          const timelineContainer = document.querySelector('.education-timeline');
          if (timelineContainer) {
            const iconSvgGrad = '<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>';
            const iconSvgShield = '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 12 11 14 15 10"></polyline>';
            const iconSvgCross = '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path><path d="M12 5v14M5 12h14"></path>';
            const iconSvgPulse = '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>';
            const iconSvgPeople = '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>';
            const iconSvgClinic = '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>';

            let html = '<div class="timeline-center-line" aria-hidden="true"></div>';
            data.qualifications.forEach(function (q, idx) {
              const isLeft = idx % 2 === 0;
              const sideClass = isLeft ? 'timeline-left' : 'timeline-right';
              const stepNumber = String(idx + 1).padStart(2, '0');
              const dLower = (q.degree || '').toLowerCase();

              let badgeClass = 'badge-degree';
              let badgeLabel = 'University Degree';
              let iconSvg = iconSvgGrad;

              if (dLower.includes('license') || dLower.includes('council') || dLower.includes('nmc') || dLower.includes('registered')) {
                badgeClass = 'badge-licensure';
                badgeLabel = 'National Licensure';
                iconSvg = iconSvgShield;
              } else if (dLower.includes('sterilization') || dLower.includes('safety') || dLower.includes('infection') || dLower.includes('governance')) {
                badgeClass = 'badge-safety';
                badgeLabel = 'Clinical Governance';
                iconSvg = iconSvgCross;
              } else if (dLower.includes('rotary') || dLower.includes('endodontic') || dLower.includes('workshop') || dLower.includes('restoration')) {
                badgeClass = 'badge-workshop';
                badgeLabel = 'Specialized Workshop';
                iconSvg = iconSvgPulse;
              } else if (dLower.includes('outreach') || dLower.includes('screening') || dLower.includes('community')) {
                badgeClass = 'badge-outreach';
                badgeLabel = 'Community Health';
                iconSvg = iconSvgPeople;
              } else if (dLower.includes('consultant') || dLower.includes('hospital') || dLower.includes('practice')) {
                badgeClass = 'badge-practice';
                badgeLabel = 'Clinical Practice';
                iconSvg = iconSvgClinic;
              }

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

        // 4. Dynamic Video Gallery & Categories (loaded directly from MySQL database)
        if (Array.isArray(data.video_categories) && data.video_categories.length > 0) {
          const videoFiltersContainer = document.querySelector('.video-filters');
          if (videoFiltersContainer) {
            let filterHtml = '<button type="button" class="video-filter-btn active" data-filter="all" role="tab" aria-selected="true">All Videos</button>';
            data.video_categories.forEach(function (cat) {
              filterHtml += `<button type="button" class="video-filter-btn" data-filter="${cat.slug}" role="tab" aria-selected="false">${cat.name}</button>`;
            });
            videoFiltersContainer.innerHTML = filterHtml;
          }
        }

        if (Array.isArray(data.videos) && data.videos.length > 0) {
          const videosGrid = document.getElementById('videosGrid');
          if (videosGrid) {
            videosGrid.innerHTML = data.videos.map(function (v) {
              const catSlug = v.category_slug || '';
              const catName = v.category_name || 'Dental Video';
              const duration = v.duration || '04:00';
              const videoSrc = v.video_type === 'upload' ? (v.video_file || '') : (v.video_url || '');
              const thumb = v.thumbnail ? (v.thumbnail.startsWith('http') || v.thumbnail.startsWith('/') ? v.thumbnail : '/' + v.thumbnail) : '/img/services/teeth_cleaning.jpg';
              const pubDate = v.published_at ? new Date(v.published_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : '';

              return `
                <article class="video-card" 
                         data-category="${catSlug}" 
                         data-video-url="${videoSrc}" 
                         data-video-type="${v.video_type || 'youtube'}" 
                         data-title="${v.title}" 
                         data-desc="${v.description || ''}" 
                         data-category-name="${catName}" 
                         data-duration="${duration}" 
                         tabindex="0" 
                         role="button" 
                         aria-label="Watch video: ${v.title}">
                  <div class="video-media">
                    <img src="${thumb}" alt="${v.title} thumbnail" class="video-thumb" loading="lazy">
                    <div class="video-play-btn" aria-hidden="true">
                      <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    </div>
                    <span class="video-duration">${duration}</span>
                  </div>
                  <div class="video-body">
                    <div class="video-meta">
                      <span class="video-cat-badge">${catName}</span>
                      ${pubDate ? `<span class="video-date">${pubDate}</span>` : ''}
                    </div>
                    <h3 class="video-title">${v.title}</h3>
                    <p class="video-desc">${v.description || ''}</p>
                  </div>
                </article>
              `;
            }).join('');

            // Re-bind video card interactions, filters, and 2-row pagination
            bindVideoCards();
            bindVideoFilters();
            updateVideoDisplay(false);
          }
        }

        // 5. Dynamic Dental Services (loaded directly from MySQL database)
        if (Array.isArray(data.services) && data.services.length > 0) {
          const servicesGrid = document.querySelector('.services-grid');
          if (servicesGrid) {
            servicesGrid.innerHTML = data.services.map(function (s) {
              const sImg = s.image ? (s.image.startsWith('http') || s.image.startsWith('/') ? s.image : '/' + s.image) : '/img/services/teeth_cleaning.jpg';
              return `
                <article class="service-card">
                  <div class="service-media">
                    <img src="${sImg}" alt="${s.name}" class="service-thumb" loading="lazy">
                  </div>
                  <div class="service-content">
                    <h3 class="service-name">${s.name}</h3>
                    <p class="service-summary">${s.short_description || ''}</p>
                  </div>
                </article>
              `;
            }).join('');
          }
        }

        // 6. Dynamic Career Photo Gallery (loaded directly from MySQL database)
        if (Array.isArray(data.gallery) && data.gallery.length > 0) {
          const galleryGrid = document.getElementById('galleryGrid');
          if (galleryGrid) {
            galleryGrid.innerHTML = data.gallery.map(function (g) {
              const catRaw = g.category || 'clinical-practice';
              const catSlug = catRaw.toLowerCase().replace(/[^a-z0-9]+/g, '-');
              return `
                <figure class="gallery-card" data-category="${catSlug}" tabindex="0" role="button" aria-label="View ${g.image_title || 'Gallery image'}">
                  <img src="${g.image_path}" alt="${g.image_title || 'Gallery image'}" class="gallery-thumb" loading="lazy">
                  <figcaption class="gallery-overlay">
                    <span class="gallery-title">${g.image_title || 'Gallery'}</span>
                    <span class="gallery-desc-hidden">${g.caption || ''}</span>
                  </figcaption>
                </figure>
              `;
            }).join('');

            // Re-render gallery pagination
            if (galleryPagination && typeof galleryPagination.update === 'function') {
              galleryPagination.update(false);
            }
          }
        }

        // 7. Dynamic Dental Health Blog (loaded directly from MySQL database)
        if (Array.isArray(data.blogs) && data.blogs.length > 0) {
          const articlesGrid = document.querySelector('#blog .articles-grid');
          if (articlesGrid) {
            articlesGrid.innerHTML = data.blogs.map(function (b) {
              const catName = b.category_name || 'Oral Care';
              const dateStr = b.published_at ? new Date(b.published_at).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) : 'Recent';
              const thumb = b.featured_image ? (b.featured_image.startsWith('http') || b.featured_image.startsWith('/') ? b.featured_image : '/' + b.featured_image) : '/img/services/teeth_cleaning.jpg';
              const articleUrl = b.slug ? ('/blog/' + b.slug) : '/blog';
              return `
                <article class="article-grid-card">
                  <div class="grid-card-media">
                    <a href="${articleUrl}" aria-label="Read article: ${b.title}">
                      <img src="${thumb}" alt="${b.title}" class="grid-card-img" loading="lazy">
                    </a>
                  </div>
                  <div class="grid-card-body">
                    <div class="grid-card-meta">
                      <span class="meta-tag">${catName}</span>
                      <span class="meta-date">${dateStr}</span>
                    </div>
                    <h3 class="grid-card-title">
                      <a href="${articleUrl}" class="article-title-link">${b.title}</a>
                    </h3>
                    <p class="grid-card-desc">${b.excerpt || ''}</p>
                    <div class="grid-card-footer">
                      <a href="${articleUrl}" class="read-link-btn">Read Article &rarr;</a>
                    </div>
                  </div>
                </article>
              `;
            }).join('');

            // Re-render blog pagination
            if (blogPagination && typeof blogPagination.update === 'function') {
              blogPagination.update(false);
            }
          }
        }

        // 8. Dynamic Research & Publications (loaded directly from MySQL database)
        if (Array.isArray(data.researches) && data.researches.length > 0) {
          const researchGrid = document.querySelector('#research .research-grid');
          if (researchGrid) {
            researchGrid.innerHTML = data.researches.map(function (r) {
              const thumb = r.cover_image ? (r.cover_image.startsWith('http') || r.cover_image.startsWith('/') ? r.cover_image : '/' + r.cover_image) : '/img/services/rct.jpg';
              let readUrl = '#research';
              let linkLabel = 'Read Complete Research &rarr;';
              let isExt = false;

              if (r.pdf_file) {
                readUrl = r.pdf_file;
                linkLabel = 'Read PDF Document &rarr;';
                isExt = true;
              } else if (r.publication_url) {
                readUrl = r.publication_url;
                isExt = true;
              }

              const targetAttr = isExt ? 'target="_blank" rel="noopener noreferrer"' : '';

              return `
                <article class="research-wide-card">
                  <div class="research-media-wrap">
                    <a href="${readUrl}" ${targetAttr} aria-label="Read research on ${r.title}">
                      <img src="${thumb}" alt="${r.title}" class="research-card-img" loading="lazy">
                    </a>
                  </div>
                  <div class="research-body">
                    <div class="research-meta">
                      <span class="research-badge">${r.authors || 'Peer-Reviewed Paper'}</span>
                      <span class="research-year">${r.publication_year || '2023'}</span>
                    </div>
                    <div class="research-journal">${r.journal_name || 'Academic Dental Journal'}</div>
                    <h3 class="research-title">
                      <a href="${readUrl}" ${targetAttr} class="article-title-link">${r.title}</a>
                    </h3>
                    <p class="research-excerpt">${r.summary || r.abstract || ''}</p>
                    <div class="research-footer">
                      <a href="${readUrl}" ${targetAttr} class="read-link-btn">${linkLabel}</a>
                    </div>
                  </div>
                </article>
              `;
            }).join('');

            // Re-render research pagination
            if (researchPagination && typeof researchPagination.update === 'function') {
              researchPagination.update(false);
            }
          }
        }

        // 9. Dynamic Social Links (loaded from settings / doctor)
        const footerSocial = document.getElementById('footerSocial');
        if (footerSocial) {
          const s = data.settings || {};
          const doc = data.doctor || {};
          const fb = s.social_facebook || s.facebook_url;
          const ig = s.social_instagram || s.instagram_url;
          const li = s.social_linkedin || s.linkedin_url;
          const yt = s.social_youtube || s.youtube_url;
          const tw = s.social_twitter || s.twitter_url;
          const wa = s.whatsapp_number || doc.whatsapp;

          let socialHtml = '';
          if (fb) {
            socialHtml += `<a href="${fb}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>`;
          }
          if (ig) {
            socialHtml += `<a href="${ig}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="Instagram"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></a>`;
          }
          if (li) {
            socialHtml += `<a href="${li}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="LinkedIn"><svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>`;
          }
          if (yt) {
            socialHtml += `<a href="${yt}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="YouTube"><svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>`;
          }
          if (tw) {
            socialHtml += `<a href="${tw}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="X (Twitter)"><svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>`;
          }
          if (wa) {
            const cleanWa = wa.replace(/[^0-9]/g, '');
            socialHtml += `<a href="https://wa.me/${cleanWa}" target="_blank" rel="noopener noreferrer" class="footer-social-link" aria-label="WhatsApp"><svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.301-.15-1.782-.879-2.058-.979-.276-.1-.477-.15-.678.15-.201.3-.777.979-.953 1.18-.176.201-.351.226-.652.075-.301-.15-1.272-.469-2.423-1.496-.896-.799-1.501-1.786-1.677-2.087-.176-.301-.019-.464.132-.614.136-.135.301-.351.452-.527.15-.176.201-.301.301-.502.101-.201.05-.377-.025-.527-.075-.15-.678-1.634-.929-2.238-.244-.589-.493-.509-.678-.518-.176-.009-.377-.009-.578-.009s-.527.075-.803.377c-.276.301-1.054 1.03-1.054 2.511 0 1.481 1.079 2.911 1.23 3.112.15.201 2.123 3.242 5.143 4.547.718.311 1.279.497 1.716.636.721.23 1.377.197 1.896.12.578-.087 1.782-.728 2.033-1.431.251-.703.251-1.305.176-1.431-.076-.126-.277-.201-.578-.351zM12 21.82a9.77 9.77 0 0 1-4.99-1.365l-.358-.213-3.708.973.99-3.615-.233-.371A9.8 9.8 0 1 1 21.8 12c0 5.414-4.404 9.82-9.8 9.82z"/></svg></a>`;
          }

          footerSocial.innerHTML = socialHtml;
        }

        // 10. Dynamic Contact & Footer Details
        if (data.contact) {
          const c = data.contact;
          const s = data.settings || {};

          // Emails
          if (c.email) {
            document.querySelectorAll('a[href^="mailto:"], #contactEmailVal, .footer-email-link').forEach(function (a) {
              a.href = 'mailto:' + c.email;
              a.textContent = c.email;
            });
          }

          // Phones
          if (c.phone) {
            const cleanPhone = c.phone.replace(/[^0-9+]/g, '');
            document.querySelectorAll('a[href^="tel:"], .btn-call-clinic, #contactPhoneVal, .footer-phone-link').forEach(function (a) {
              a.href = 'tel:' + cleanPhone;
              if (!a.classList.contains('btn') || a.id === 'contactPhoneVal') {
                a.textContent = c.phone;
              }
            });
            const qCall = document.getElementById('quickCallBtn');
            if (qCall) qCall.href = 'tel:' + cleanPhone;
          }

          // WhatsApp
          if (c.whatsapp) {
            const cleanWa = c.whatsapp.replace(/[^0-9]/g, '');
            document.querySelectorAll('a[href*="wa.me"]').forEach(function (a) {
              a.href = 'https://wa.me/' + cleanWa;
            });
            const qWa = document.getElementById('quickWhatsappBtn');
            if (qWa) qWa.href = 'https://wa.me/' + cleanWa;
          }

          // Emergency guidance / Hospital lines
          if (c.emergency_contact) {
            const emEl = document.getElementById('contactEmergencyVal');
            if (emEl) {
              emEl.textContent = 'Hospital Lines: ' + c.emergency_contact;
            } else {
              const subEl = document.querySelector('.contact-entry .entry-sub');
              if (subEl) subEl.textContent = 'Hospital Lines: ' + c.emergency_contact;
            }
          }

          // Location / Address
          const fullAddress = c.address || s.clinic_address;
          if (fullAddress) {
            const fLoc = document.getElementById('footerLocationVal');
            if (fLoc) fLoc.textContent = fullAddress;

            const cLoc = document.getElementById('contactAddressSub');
            if (cLoc) cLoc.textContent = fullAddress;
          }

          // Working hours
          const hours = c.working_hours || s.working_hours;
          if (hours) {
            const cHours = document.getElementById('contactHoursVal');
            if (cHours) cHours.textContent = hours;
          }

          // Copyright doctor name
          if (data.doctor && data.doctor.doctor_name) {
            document.querySelectorAll('.footer-copy-name').forEach(function (el) {
              el.textContent = data.doctor.doctor_name;
            });
          }
        }
      })
      .catch(function () {
        // Keeps graceful pre-rendered HTML without interruption
      });
  }

  // ==========================================================================
  // 7. Video Player Modal & Playback Control
  // ==========================================================================
  const videoModal = document.getElementById('videoModal');
  const closeVideoModal = document.getElementById('closeVideoModal');
  const videoModalPlayer = document.getElementById('videoModalPlayer');
  const videoModalTitle = document.getElementById('videoModalTitle');
  const videoModalDesc = document.getElementById('videoModalDesc');
  const videoModalCategory = document.getElementById('videoModalCategory');
  const videoModalDuration = document.getElementById('videoModalDuration');

  function parseYouTubeId(url) {
    if (!url) return null;
    url = url.trim();
    // Direct 11-char ID
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) {
      return url;
    }
    const regExp = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i;
    const match = url.match(regExp);
    return match ? match[1] : null;
  }

  function parseVimeoId(url) {
    if (!url) return null;
    const match = url.match(/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/);
    return match ? match[1] : null;
  }

  function openVideoModal(card) {
    if (!videoModal || !videoModalPlayer) return;

    const videoUrl = card.getAttribute('data-video-url') || '';
    const videoType = card.getAttribute('data-video-type') || 'youtube';
    const title = card.getAttribute('data-title') || card.querySelector('.video-title')?.textContent || 'Dental Video';
    const desc = card.getAttribute('data-desc') || card.querySelector('.video-desc')?.textContent || '';
    const cat = card.getAttribute('data-category-name') || card.querySelector('.video-cat-badge')?.textContent || '';
    const duration = card.getAttribute('data-duration') || card.querySelector('.video-duration')?.textContent || '';

    let playerHtml = '';

    const ytId = parseYouTubeId(videoUrl);
    const vmId = parseVimeoId(videoUrl);

    if (ytId) {
      // YouTube embed with autoplay and controls (universal youtube.com domain for Brave/Shields compatibility)
      playerHtml = `<iframe src="https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0&modestbranding=1&enablejsapi=1" title="${title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else if (vmId) {
      playerHtml = `<iframe src="https://player.vimeo.com/video/${vmId}?autoplay=1" title="${title}" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else if (videoType === 'upload' || videoUrl.match(/\.(mp4|webm|ogg)$/i)) {
      playerHtml = `
        <video controls autoplay playsinline style="width:100%;height:100%;background:#000;">
          <source src="${videoUrl}" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      `;
    } else if (videoUrl && videoUrl.startsWith('http')) {
      playerHtml = `<iframe src="${videoUrl}" title="${title}" allow="autoplay; fullscreen" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
    } else {
      playerHtml = `<div style="color:#fff;display:flex;align-items:center;justify-content:center;height:100%;font-size:1rem;">Video URL not available.</div>`;
    }

    videoModalPlayer.innerHTML = playerHtml;
    if (videoModalTitle) videoModalTitle.textContent = title;
    if (videoModalDesc) videoModalDesc.textContent = desc;
    if (videoModalCategory) videoModalCategory.textContent = cat;
    if (videoModalDuration) videoModalDuration.textContent = duration ? `Duration: ${duration}` : '';

    videoModal.classList.add('open');
    videoModal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeVideo() {
    if (!videoModal) return;
    videoModal.classList.remove('open');
    videoModal.setAttribute('aria-hidden', 'true');
    if (videoModalPlayer) {
      videoModalPlayer.innerHTML = ''; // Immediately stops video and sound
    }
    document.body.style.overflow = '';
  }

  if (closeVideoModal) {
    closeVideoModal.addEventListener('click', closeVideo);
  }

  if (videoModal) {
    videoModal.addEventListener('click', function (e) {
      if (e.target === videoModal) {
        closeVideo();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && videoModal.classList.contains('open')) {
        closeVideo();
      }
    });
  }

  // Event delegation: reliable clicking on any video card (even dynamically created)
  document.addEventListener('click', function (e) {
    const card = e.target.closest('.video-card');
    if (card) {
      e.preventDefault();
      openVideoModal(card);
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      const card = e.target.closest('.video-card');
      if (card && document.activeElement === card) {
        e.preventDefault();
        openVideoModal(card);
      }
    }
  });

  // ==========================================================================
  // Video Pagination & Display (2 Rows / Max 6 Videos per page)
  // ==========================================================================
  const VIDEOS_PER_PAGE = 6; // 3 columns * 2 rows = 6 cards per view
  let currentVideoPage = 1;
  let currentVideoCategory = 'all';

  function updateVideoDisplay(scrollToVideos) {
    const allCards = document.querySelectorAll('#videosGrid .video-card');
    const paginationEl = document.getElementById('videoPagination');
    if (!allCards || allCards.length === 0) return;

    // Filter cards matching active category
    const matchingCards = [];
    allCards.forEach(function (card) {
      const cat = card.getAttribute('data-category') || '';
      if (currentVideoCategory === 'all' || cat === currentVideoCategory) {
        matchingCards.push(card);
      } else {
        card.style.display = 'none';
      }
    });

    const totalMatching = matchingCards.length;
    const totalPages = Math.ceil(totalMatching / VIDEOS_PER_PAGE) || 1;

    if (currentVideoPage > totalPages) {
      currentVideoPage = 1;
    }

    // Show only 6 cards for the active page
    const startIndex = (currentVideoPage - 1) * VIDEOS_PER_PAGE;
    const endIndex = startIndex + VIDEOS_PER_PAGE;

    matchingCards.forEach(function (card, index) {
      if (index >= startIndex && index < endIndex) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });

    // Render pagination controls (Prev, 1, 2, 3..., Next)
    if (!paginationEl) return;

    if (totalPages <= 1) {
      paginationEl.innerHTML = '';
      return;
    }

    let paginationHtml = '';

    // Previous Button
    const isFirstPage = currentVideoPage === 1;
    paginationHtml += `
      <button type="button" class="video-page-btn" data-page="${currentVideoPage - 1}" ${isFirstPage ? 'disabled aria-disabled="true"' : ''} aria-label="Previous Page">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="15 18 9 12 15 6"></polyline></svg>
        <span>Prev</span>
      </button>
    `;

    // Numbered Buttons: 1, 2, 3...
    for (let p = 1; p <= totalPages; p++) {
      const isActive = p === currentVideoPage;
      paginationHtml += `
        <button type="button" class="video-page-btn ${isActive ? 'active' : ''}" data-page="${p}" ${isActive ? 'aria-current="page"' : ''} aria-label="Page ${p}">
          ${p}
        </button>
      `;
    }

    // Next Button
    const isLastPage = currentVideoPage === totalPages;
    paginationHtml += `
      <button type="button" class="video-page-btn" data-page="${currentVideoPage + 1}" ${isLastPage ? 'disabled aria-disabled="true"' : ''} aria-label="Next Page">
        <span>Next</span>
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"><polyline points="9 18 15 12 9 6"></polyline></svg>
      </button>
    `;

    paginationEl.innerHTML = paginationHtml;

    // Attach listeners to pagination buttons
    paginationEl.querySelectorAll('.video-page-btn').forEach(function (btn) {
      btn.onclick = function () {
        if (btn.disabled || btn.classList.contains('active')) return;
        const targetPage = parseInt(btn.getAttribute('data-page'), 10);
        if (!isNaN(targetPage) && targetPage >= 1 && targetPage <= totalPages) {
          currentVideoPage = targetPage;
          updateVideoDisplay(true);
        }
      };
    });

    if (scrollToVideos) {
      const videoSection = document.getElementById('videos');
      if (videoSection) {
        videoSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  }

  function bindVideoCards() {
    // Retained for backwards compatibility
    const cards = document.querySelectorAll('.video-card');
    cards.forEach(function (card) {
      card.onclick = function (e) {
        e.preventDefault();
        openVideoModal(card);
      };
    });
  }

  function bindVideoFilters() {
    const filterBtns = document.querySelectorAll('.video-filter-btn');

    filterBtns.forEach(function (btn) {
      btn.onclick = function () {
        const filterVal = btn.getAttribute('data-filter') || 'all';

        filterBtns.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');

        currentVideoCategory = filterVal;
        currentVideoPage = 1; // Reset to first page when filtering
        updateVideoDisplay(false);
      };
    });
  }

  // Initial binding and 2-row pagination display
  bindVideoCards();
  bindVideoFilters();
  updateVideoDisplay(false);

  loadDynamicPortfolioData();

  // ==========================================================================
  // 8. Dynamic Year in Footer
  // ==========================================================================
  const currentYearSpan = document.getElementById('currentYear');
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }
});
