// Frontend JavaScript for the video modal block
// This file handles modal opening/closing, YouTube video loading, accordion functionality, and focus management

document.addEventListener('DOMContentLoaded', function() {
    const videoModalCovers = document.querySelectorAll('.c-video-modal__cover');
    
    videoModalCovers.forEach(function(cover) {
        // Add click event to open modal
        cover.addEventListener('click', function() {
            const videoId = this.getAttribute('data-video-id');
            const modalId = this.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            
            if (modal && videoId) {
                openVideoModal(modal, videoId, modalId);
            }
        });
    });
    
    // Handle close buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('c-popup-close') || e.target.closest('.c-popup-close')) {
            const modal = e.target.closest('.c-popup-overlay');
            if (modal) {
                closeVideoModal(modal);
            }
        }
    });
    
    // Handle clicking outside modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('c-popup-overlay')) {
            closeVideoModal(e.target);
        }
    });
    
    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.c-popup-overlay.is-active');
            if (openModal) {
                closeVideoModal(openModal);
            }
        }
    });
    
    // Handle transcript accordion toggles
    document.addEventListener('click', function(e) {
        if (e.target.matches('.c-video-modal__transcript-toggle') || e.target.closest('.c-video-modal__transcript-toggle')) {
            const button = e.target.matches('.c-video-modal__transcript-toggle') ? e.target : e.target.closest('.c-video-modal__transcript-toggle');
            toggleTranscriptAccordion(button);
        }
    });
    
    // Store focus before modal opens
    let lastFocusedElement = null;
    
    function openVideoModal(modal, videoId, modalId) {
        // Store the currently focused element
        lastFocusedElement = document.activeElement;
        
        // Store original parent for restoration later
        modal.originalParent = modal.parentElement;
        modal.originalNextSibling = modal.nextElementSibling;
        
        // Move modal to body to ensure full viewport coverage
        document.body.appendChild(modal);
        
        // Ensure proper overlay class is applied
        modal.classList.add('c-video-modal-overlay');
        
        // Update ARIA attributes
        modal.setAttribute('aria-hidden', 'false');
        
        // Prevent body scroll
        document.body.classList.add('popup-form-open');
        
        // Show modal
        modal.classList.add('is-active');
        
        // Load YouTube video
        loadYouTubeVideo(videoId, modalId + '-video');
        
        // Set up focus trap
        setupFocusTrap(modal);
        
        // Focus the close button initially
        setTimeout(() => {
            const closeButton = modal.querySelector('.c-popup-close');
            if (closeButton) {
                closeButton.focus();
            }
        }, 100);
    }
    
    function closeVideoModal(modal) {
        // Hide modal
        modal.classList.remove('is-active');
        
        // Update ARIA attributes
        modal.setAttribute('aria-hidden', 'true');
        
        // Allow body scroll
        document.body.classList.remove('popup-form-open');
        
        // Stop YouTube video
        const videoContainer = modal.querySelector('.c-video-modal__video-wrapper');
        if (videoContainer) {
            videoContainer.innerHTML = '';
        }
        
        // Remove focus trap
        removeFocusTrap(modal);
        
        // Restore focus to the element that opened the modal
        if (lastFocusedElement) {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
        
        // Move modal back to its original location
        if (modal.originalParent) {
            if (modal.originalNextSibling) {
                modal.originalParent.insertBefore(modal, modal.originalNextSibling);
            } else {
                modal.originalParent.appendChild(modal);
            }
            // Clean up references
            delete modal.originalParent;
            delete modal.originalNextSibling;
        }
    }
    
    function toggleTranscriptAccordion(button) {
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        const targetId = button.getAttribute('aria-controls');
        const content = document.getElementById(targetId);
        
        if (!content) return;
        
        // Toggle expanded state
        button.setAttribute('aria-expanded', !isExpanded);
        
        if (isExpanded) {
            // Close accordion
            content.setAttribute('hidden', '');
            content.classList.remove('is-open');
        } else {
            // Open accordion
            content.removeAttribute('hidden');
            content.classList.add('is-open');
            
            // Adjust max-height for smooth animation
            const scrollHeight = content.scrollHeight;
            content.style.maxHeight = scrollHeight + 'px';
        }
    }
    
    function setupFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        function handleTabKey(e) {
            if (e.key !== 'Tab') return;
            
            if (e.shiftKey) {
                // Shift + Tab
                if (document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                }
            } else {
                // Tab
                if (document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
        
        modal.addEventListener('keydown', handleTabKey);
        modal._focusTrapHandler = handleTabKey; // Store reference for cleanup
    }
    
    function removeFocusTrap(modal) {
        if (modal._focusTrapHandler) {
            modal.removeEventListener('keydown', modal._focusTrapHandler);
            delete modal._focusTrapHandler;
        }
    }
    
    function loadYouTubeVideo(videoId, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        // Create responsive iframe wrapper
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.width = '100%';
        wrapper.style.height = '0';
        wrapper.style.paddingBottom = '56.25%'; // 16:9 aspect ratio
        
        // Create iframe
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
        iframe.style.position = 'absolute';
        iframe.style.top = '0';
        iframe.style.left = '0';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.frameBorder = '0';
        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
        iframe.allowFullscreen = true;
        
        wrapper.appendChild(iframe);
        container.appendChild(wrapper);
    }
});
