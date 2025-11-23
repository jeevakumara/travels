/**
 * NMD Travels - Site JavaScript
 * Minimal vanilla JS for interactions
 */

(function() {
  'use strict';

  // Mobile menu toggle
  function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    
    if (!menuToggle || !mobileNav) return;

    menuToggle.addEventListener('click', function() {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      mobileNav.classList.toggle('active');
      
      // Prevent body scroll when menu is open
      document.body.style.overflow = !isExpanded ? 'hidden' : '';
      
      // Focus trap
      if (!isExpanded) {
        const firstLink = mobileNav.querySelector('a');
        if (firstLink) firstLink.focus();
      }
    });

    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('active');
        document.body.style.overflow = '';
        menuToggle.focus();
      }
    });

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
      if (!menuToggle.contains(e.target) && !mobileNav.contains(e.target)) {
        if (mobileNav.classList.contains('active')) {
          menuToggle.setAttribute('aria-expanded', 'false');
          mobileNav.classList.remove('active');
          document.body.style.overflow = '';
        }
      }
    });

    // Close menu on window resize to desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 1024 && mobileNav.classList.contains('active')) {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileNav.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  }

  // Set active nav link based on current page
  function setActiveNavLink() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
    
    navLinks.forEach(function(link) {
      const linkPath = new URL(link.href).pathname;
      if (currentPath === linkPath || (currentPath === '/' && linkPath.includes('index.php'))) {
        link.classList.add('active');
        link.setAttribute('aria-current', 'page');
      }
    });
  }

  // Smooth scroll for anchor links
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#') return;
        
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
          
          // Update URL without jumping
          history.pushState(null, null, href);
          
          // Focus target for accessibility
          target.setAttribute('tabindex', '-1');
          target.focus();
        }
      });
    });
  }

  // Form validation enhancement
  function enhanceFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(function(form) {
      form.addEventListener('submit', function(e) {
        const inputs = form.querySelectorAll('[required]');
        let isValid = true;
        
        inputs.forEach(function(input) {
          if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            // Show error message
            let errorMsg = input.nextElementSibling;
            if (!errorMsg || !errorMsg.classList.contains('form-error')) {
              errorMsg = document.createElement('span');
              errorMsg.className = 'form-error';
              errorMsg.textContent = 'This field is required';
              input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }
          } else {
            input.classList.remove('error');
            const errorMsg = input.nextElementSibling;
            if (errorMsg && errorMsg.classList.contains('form-error')) {
              errorMsg.remove();
            }
          }
        });
        
        if (!isValid) {
          e.preventDefault();
        }
      });
      
      // Remove error on input
      form.querySelectorAll('[required]').forEach(function(input) {
        input.addEventListener('input', function() {
          this.classList.remove('error');
          const errorMsg = this.nextElementSibling;
          if (errorMsg && errorMsg.classList.contains('form-error')) {
            errorMsg.remove();
          }
        });
      });
    });
  }

  // Lazy load images
  function initLazyLoading() {
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
            }
            imageObserver.unobserve(img);
          }
        });
      });

      document.querySelectorAll('img[data-src]').forEach(function(img) {
        imageObserver.observe(img);
      });
    }
  }

  // Admin table enhancements
  function initAdminTables() {
    const tables = document.querySelectorAll('.table-responsive');
    
    tables.forEach(function(wrapper) {
      const table = wrapper.querySelector('table');
      if (!table) return;
      
      // Add scroll indicator
      function updateScrollIndicator() {
        const isScrollable = wrapper.scrollWidth > wrapper.clientWidth;
        const isAtEnd = wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - 1;
        
        if (isScrollable && !isAtEnd) {
          wrapper.classList.add('has-scroll');
        } else {
          wrapper.classList.remove('has-scroll');
        }
      }
      
      updateScrollIndicator();
      wrapper.addEventListener('scroll', updateScrollIndicator);
      window.addEventListener('resize', updateScrollIndicator);
    });
  }

  // Header scroll effect
  function initHeaderScroll() {
    const header = document.querySelector('.site-header');
    if (!header) return;
    
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
      
      lastScroll = currentScroll;
    }, { passive: true });
  }

  // Intersection Observer for animations
  function initScrollAnimations() {
    if (!('IntersectionObserver' in window)) return;
    
    const animateOnScroll = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-in');
          animateOnScroll.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });
    
    // Animate cards
    document.querySelectorAll('.card, .review, .hero-content, .hero-media').forEach(function(el) {
      animateOnScroll.observe(el);
    });
  }

  // Add ripple effect to buttons
  function initRippleEffect() {
    document.querySelectorAll('.btn').forEach(function(button) {
      button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(function() {
          ripple.remove();
        }, 600);
      });
    });
  }

  // Preload critical images
  function preloadImages() {
    const criticalImages = document.querySelectorAll('img[data-priority="high"]');
    criticalImages.forEach(function(img) {
      if (img.dataset.src) {
        img.src = img.dataset.src;
        img.removeAttribute('data-src');
      }
    });
  }

  // Add page transition class
  function initPageTransition() {
    document.body.classList.add('page-transition');
  }

  // Flash message handling
  function initFlashMessages() {
    const flashMessage = document.querySelector('.flash-message');
    if (!flashMessage) return;
    
    // Auto-dismiss after 5 seconds
    const autoDismiss = setTimeout(function() {
      dismissFlash(flashMessage);
    }, 5000);
    
    // Close button
    const closeBtn = flashMessage.querySelector('.flash-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function() {
        clearTimeout(autoDismiss);
        dismissFlash(flashMessage);
      });
    }
    
    function dismissFlash(element) {
      element.style.animation = 'slideUp 0.3s ease-out';
      setTimeout(function() {
        element.remove();
      }, 300);
    }
  }

  // Initialize all features on DOM ready
  function init() {
    initPageTransition();
    initFlashMessages();
    initMobileMenu();
    setActiveNavLink();
    initSmoothScroll();
    enhanceFormValidation();
    initLazyLoading();
    initAdminTables();
    initHeaderScroll();
    initScrollAnimations();
    initRippleEffect();
    preloadImages();
  }

  // Run on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
