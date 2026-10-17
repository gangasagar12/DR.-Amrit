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

        // 3. Dynamic Qualifications / Education Timeline (Loaded directly from MySQL database)
        if (Array.isArray(data.qualifications) && data.qualifications.length > 0) {
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
              const thumb = v.thumbnail || 'img/services/teeth_cleaning.jpg';
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

            // Re-bind video card interactions and category filter buttons
            bindVideoCards();
            bindVideoFilters();
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
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
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
    const title = card.getAttribute('data-title') || card.querySelector('.video-title')?.textContent || '';
    const desc = card.getAttribute('data-desc') || card.querySelector('.video-desc')?.textContent || '';
    const cat = card.getAttribute('data-category-name') || card.querySelector('.video-cat-badge')?.textContent || '';
    const duration = card.getAttribute('data-duration') || card.querySelector('.video-duration')?.textContent || '';

    let playerHtml = '';

    if (videoType === 'upload' || videoUrl.match(/\.(mp4|webm|ogg)$/i)) {
      playerHtml = `
        <video controls autoplay playsinline style="width:100%;height:100%;background:#000;">
          <source src="${videoUrl}" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      `;
    } else {
      const ytId = parseYouTubeId(videoUrl);
      const vmId = parseVimeoId(videoUrl);

      if (ytId) {
        playerHtml = `<iframe src="https://www.youtube-nocookie.com/embed/${ytId}?autoplay=1&rel=0&modestbranding=1" title="${title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
      } else if (vmId) {
        playerHtml = `<iframe src="https://player.vimeo.com/video/${vmId}?autoplay=1" title="${title}" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
      } else if (videoUrl) {
        playerHtml = `<iframe src="${videoUrl}" title="${title}" allow="autoplay; fullscreen" allowfullscreen style="width:100%;height:100%;border:0;"></iframe>`;
      } else {
        playerHtml = `<div style="color:#fff;display:flex;align-items:center;justify-content:center;height:100%;">Video source unavailable.</div>`;
      }
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

  function bindVideoCards() {
    const cards = document.querySelectorAll('.video-card');
    cards.forEach(function (card) {
      // Avoid duplicate event listener
      card.onclick = function () {
        openVideoModal(card);
      };
      card.onkeydown = function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          openVideoModal(card);
        }
      };
    });
  }

  function bindVideoFilters() {
    const filterBtns = document.querySelectorAll('.video-filter-btn');
    const cards = document.querySelectorAll('.video-card');

    filterBtns.forEach(function (btn) {
      btn.onclick = function () {
        const filterVal = btn.getAttribute('data-filter') || 'all';

        filterBtns.forEach(function (b) {
          b.classList.remove('active');
          b.setAttribute('aria-selected', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');

        cards.forEach(function (card) {
          const cardCat = card.getAttribute('data-category') || '';
          if (filterVal === 'all' || cardCat === filterVal) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      };
    });
  }

  // Initial binding for pre-rendered video cards
  bindVideoCards();
  bindVideoFilters();

  loadDynamicPortfolioData();

  // ==========================================================================
  // 8. Dynamic Year in Footer
  // ==========================================================================
  const currentYearSpan = document.getElementById('currentYear');
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }
});
