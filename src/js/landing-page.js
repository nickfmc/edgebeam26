/**
 * Landing Page JavaScript
 * Smooth scroll, accordion functionality, mobile navigation, and scroll spy
 */

(function() {
  'use strict';

  // ========================================
  // Smooth Scroll for Anchor Links
  // ========================================
  function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    const headerHeight = document.querySelector('.c-landing-header')?.offsetHeight || 80;

    anchorLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
          e.preventDefault();
          
          const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset;
          const offsetPosition = targetPosition - headerHeight - 20;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          // Update URL without triggering scroll
          history.pushState(null, null, targetId);
          
          // Close mobile nav if open
          closeMobileNav();
        }
      });
    });
  }

  // ========================================
  // FAQ Accordion
  // ========================================
  function initAccordion() {
    const accordions = document.querySelectorAll('[data-accordion]');

    accordions.forEach(accordion => {
      const triggers = accordion.querySelectorAll('[data-accordion-trigger]');

      triggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
          const item = this.closest('[data-accordion-item]');
          const content = item.querySelector('[data-accordion-content]');
          const isExpanded = this.getAttribute('aria-expanded') === 'true';

          // Close all other items (single-open accordion)
          const allItems = accordion.querySelectorAll('[data-accordion-item]');
          allItems.forEach(otherItem => {
            if (otherItem !== item) {
              const otherTrigger = otherItem.querySelector('[data-accordion-trigger]');
              const otherContent = otherItem.querySelector('[data-accordion-content]');
              
              otherTrigger.setAttribute('aria-expanded', 'false');
              otherContent.hidden = true;
              otherItem.classList.remove('is-open');
            }
          });

          // Toggle current item
          this.setAttribute('aria-expanded', !isExpanded);
          content.hidden = isExpanded;
          item.classList.toggle('is-open', !isExpanded);

          // Animate height
          if (!isExpanded) {
            content.style.height = '0';
            content.hidden = false;
            const height = content.scrollHeight;
            content.style.height = height + 'px';
            
            // Remove inline height after animation
            setTimeout(() => {
              content.style.height = '';
            }, 300);
          }
        });

        // Keyboard navigation
        trigger.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
          }
        });
      });
    });
  }

  // ========================================
  // Mobile Navigation
  // ========================================
  function initMobileNav() {
    const toggle = document.getElementById('landing-mobile-toggle');
    const nav = document.getElementById('landing-mobile-nav');

    if (!toggle || !nav) return;

    toggle.addEventListener('click', function() {
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      this.setAttribute('aria-expanded', !isExpanded);
      nav.setAttribute('aria-hidden', isExpanded);
      
      // Prevent body scroll when nav is open
      document.body.classList.toggle('nav-open', !isExpanded);
    });

    // Close on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeMobileNav();
      }
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        closeMobileNav();
      }
    });
  }

  function closeMobileNav() {
    const toggle = document.getElementById('landing-mobile-toggle');
    const nav = document.getElementById('landing-mobile-nav');

    if (toggle && nav) {
      toggle.setAttribute('aria-expanded', 'false');
      nav.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('nav-open');
    }
  }

  // ========================================
  // Scroll Spy for Navigation
  // ========================================
  function initScrollSpy() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.c-landing-nav__link, .c-landing-mobile-nav__list a');
    const headerHeight = document.querySelector('.c-landing-header')?.offsetHeight || 80;

    function updateActiveLink() {
      let currentSection = '';

      sections.forEach(section => {
        const sectionTop = section.offsetTop - headerHeight - 100;
        const sectionBottom = sectionTop + section.offsetHeight;

        if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionBottom) {
          currentSection = section.getAttribute('id');
        }
      });

      navLinks.forEach(link => {
        link.classList.remove('is-active');
        if (link.getAttribute('href') === `#${currentSection}`) {
          link.classList.add('is-active');
        }
      });
    }

    // Throttle scroll events
    let ticking = false;
    window.addEventListener('scroll', function() {
      if (!ticking) {
        window.requestAnimationFrame(function() {
          updateActiveLink();
          ticking = false;
        });
        ticking = true;
      }
    });

    // Initial check
    updateActiveLink();
  }

  // ========================================
  // Header Scroll Effect
  // ========================================
  function initHeaderScroll() {
    const header = document.querySelector('.c-landing-header');
    if (!header) return;

    let lastScroll = 0;
    const scrollThreshold = 100;

    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset;

      // Add/remove scrolled class
      if (currentScroll > 50) {
        header.classList.add('is-scrolled');
      } else {
        header.classList.remove('is-scrolled');
      }

      // Hide/show header on scroll direction (optional)
      if (currentScroll > lastScroll && currentScroll > scrollThreshold) {
        header.classList.add('is-hidden');
      } else {
        header.classList.remove('is-hidden');
      }

      lastScroll = currentScroll;
    });
  }

  // ========================================
  // Intersection Observer for Animations
  // ========================================
  function initScrollAnimations() {
    // Check for reduced motion preference
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      return;
    }

    const animatedElements = document.querySelectorAll('[data-animate]');
    
    if (!animatedElements.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(el => {
      observer.observe(el);
    });
  }

  // ========================================
  // Form Enhancements
  // ========================================
  function initFormEnhancements() {
    // Floating labels (if inputs have values on load)
    const inputs = document.querySelectorAll('.c-form__field input, .c-form__field textarea');
    
    inputs.forEach(input => {
      // Check if input has value
      const checkValue = () => {
        if (input.value.trim()) {
          input.classList.add('has-value');
        } else {
          input.classList.remove('has-value');
        }
      };

      input.addEventListener('input', checkValue);
      input.addEventListener('blur', checkValue);
      checkValue();
    });

    // Form validation visual feedback
    const forms = document.querySelectorAll('.c-form--landing');
    
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
          } else {
            field.classList.remove('is-invalid');
          }
        });

        if (!isValid) {
          e.preventDefault();
        }
      });
    });
  }

  // ========================================
  // Initialize on DOM Ready
  // ========================================
  function init() {
    initSmoothScroll();
    initAccordion();
    initMobileNav();
    initScrollSpy();
    initHeaderScroll();
    initScrollAnimations();
    initFormEnhancements();
  }

  // Run when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
