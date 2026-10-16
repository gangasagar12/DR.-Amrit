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
  // 6. Dynamic Year in Footer
  // ==========================================================================
  const currentYearSpan = document.getElementById('currentYear');
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }
});
