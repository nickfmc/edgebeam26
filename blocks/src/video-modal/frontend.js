// Frontend JavaScript for the video modal block
// This file handles modal opening/closing, YouTube video loading, accordion functionality, and focus management

document.addEventListener('DOMContentLoaded', function() {
    const videoModalCovers = document.querySelectorAll('.c-video-modal__cover');
    
    videoModalCovers.forEach(function(cover) {
        // Add click event to open modal
        cover.addEventListener('click', function() {
            const videoType = this.getAttribute('data-video-type') || 'youtube';
            const videoId = this.getAttribute('data-video-id');
            const localVideoData = this.getAttribute('data-local-video');
            const modalId = this.getAttribute('data-modal-id');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                if (videoType === 'local' && localVideoData) {
                    try {
                        const localVideo = JSON.parse(localVideoData);
                        openVideoModal(modal, null, modalId, 'local', localVideo);
                    } catch(e) {
                        console.error('Error parsing local video data:', e);
                    }
                } else if (videoType === 'youtube' && videoId) {
                    openVideoModal(modal, videoId, modalId, 'youtube', null);
                }
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
    
    function openVideoModal(modal, videoId, modalId, videoType, localVideo) {
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
        
        // Load appropriate video type
        if (videoType === 'local' && localVideo) {
            loadLocalVideo(localVideo, modalId + '-video');
        } else if (videoType === 'youtube' && videoId) {
            loadYouTubeVideo(videoId, modalId + '-video');
        }
        
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
        
        // Stop video (works for both YouTube iframe and HTML5 video)
        const videoContainer = modal.querySelector('.c-video-modal__video-wrapper');
        if (videoContainer) {
            // Check if there's an HTML5 video element and pause it
            const videoElement = videoContainer.querySelector('video');
            if (videoElement) {
                videoElement.pause();
                videoElement.currentTime = 0;
            }
            // Clear container
            videoContainer.innerHTML = '';
        }
        
        // Allow body scroll
        document.body.classList.remove('popup-form-open');
        
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
    
    function loadLocalVideo(videoData, containerId) {
        const container = document.getElementById(containerId);
        if (!container || !videoData || !videoData.url) return;
        
        // Create responsive video wrapper
        const wrapper = document.createElement('div');
        wrapper.style.position = 'relative';
        wrapper.style.width = '100%';
        wrapper.style.height = '0';
        wrapper.style.paddingBottom = '56.25%'; // 16:9 aspect ratio
        wrapper.style.backgroundColor = '#000';
        
        // Create HTML5 video element
        const video = document.createElement('video');
        video.src = videoData.url;
        video.style.position = 'absolute';
        video.style.top = '0';
        video.style.left = '0';
        video.style.width = '100%';
        video.style.height = '100%';
        video.controls = true;
        video.autoplay = true;
        video.preload = 'metadata';
        
        // Add common video attributes
        video.setAttribute('playsinline', '');
        video.setAttribute('controlsList', 'nodownload');
        
        wrapper.appendChild(video);
        container.appendChild(wrapper);
    }
});
