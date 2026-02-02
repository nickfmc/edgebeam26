// Frontend JavaScript for the accordion block with full accessibility support
// This file is loaded on the frontend when the block is used

document.addEventListener('DOMContentLoaded', function() {
    const accordionBlocks = document.querySelectorAll('.c-accordion-block');
    
    accordionBlocks.forEach(function(block) {
        initAccordionBlock(block);
    });
});

/**
 * Initialize a single accordion block
 * @param {HTMLElement} block - The accordion block container
 */
function initAccordionBlock(block) {
    const grid = block.querySelector('.c-accordion-block__grid');
    const loadMoreBtn = block.querySelector('.c-accordion-block__load-more-btn');
    const accordionItems = block.querySelectorAll('.c-accordion-block__item');
    const toggles = block.querySelectorAll('.c-accordion-block__toggle');
    
    if (!grid) return;
    
    // Get settings from data attributes
    const itemsToShow = parseInt(grid.dataset.itemsToShow) || 6;
    const showLoadMore = grid.dataset.showLoadMore === 'true';
    const loadMoreText = grid.dataset.loadMoreText || 'Load More';
    const totalItems = parseInt(grid.dataset.totalItems) || accordionItems.length;
    const columns = parseInt(grid.dataset.columns) || 2;
    
    // If itemsToShow is 0, show all items (no load more needed)
    const showAll = itemsToShow === 0;
    let currentlyShowing = showAll ? totalItems : itemsToShow;
    let originalItemsOrder = Array.from(accordionItems); // Store original order
    let isColumnLayout = false;
    let accordionInitialized = false;
    
    // Initialize layout based on screen size and column settings
    const isMobile = window.innerWidth < 768;
    const shouldUseColumns = columns > 1 && !isMobile;
    
    if (shouldUseColumns) {
        reorganizeItemsForColumns();
        isColumnLayout = true;
    } else {
        reorganizeItemsForSingleColumn();
        isColumnLayout = false;
    }
    
    // Initialize accordion functionality (get fresh toggles after layout)
    if (!accordionInitialized) {
        initAccordionToggles();
        accordionInitialized = true;
    }
    
    // Initialize load more functionality
    if (showLoadMore && loadMoreBtn) {
        initLoadMoreFunctionality();
    }
    
    // Add resize listener to handle layout changes
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleLayoutChange, 150);
    });
    
    // Add loaded class
    block.classList.add('c-accordion-block--loaded');
    
    /**
     * Handle layout changes on window resize
     */
    function handleLayoutChange() {
        const isMobile = window.innerWidth < 768;
        const shouldUseColumns = columns > 1 && !isMobile;
        
        if (shouldUseColumns && !isColumnLayout) {
            // Switch to column layout
            reorganizeItemsForColumns();
            isColumnLayout = true;
        } else if (!shouldUseColumns && isColumnLayout) {
            // Switch to single column layout
            reorganizeItemsForSingleColumn();
            isColumnLayout = false;
        }
    }
    
    /**
     * Reorganize items for column layout
     */
    function reorganizeItemsForColumns() {
        // Get all items from the grid (they might be in columns or flat)
        const allItems = getAllItemsInOrder();
        
        // Clear the grid
        grid.innerHTML = '';
        
        // Create column containers
        const columnContainers = [];
        for (let i = 0; i < columns; i++) {
            const column = document.createElement('div');
            column.className = 'c-accordion-block__column';
            columnContainers.push(column);
        }
        
        // Distribute items evenly across columns
        allItems.forEach((item, index) => {
            const columnIndex = index % columns;
            columnContainers[columnIndex].appendChild(item);
        });
        
        // Add column containers to grid
        columnContainers.forEach(column => {
            grid.appendChild(column);
        });
        
        // Update the grid class to use flexbox layout
        grid.classList.add('c-accordion-block__grid--flex-columns');
    }
    
    /**
     * Reorganize items for single column layout
     */
    function reorganizeItemsForSingleColumn() {
        // Get all items in their original order
        const allItems = getAllItemsInOrder();
        
        // Clear the grid and remove flex class
        grid.innerHTML = '';
        grid.classList.remove('c-accordion-block__grid--flex-columns');
        
        // Add items directly to grid in order
        allItems.forEach(item => {
            grid.appendChild(item);
        });
    }
    
    /**
     * Get all items in their original order regardless of current layout
     */
    function getAllItemsInOrder() {
        if (isColumnLayout) {
            // Items are in columns, need to collect them in original order
            const itemsInOrder = [];
            const columnElements = grid.querySelectorAll('.c-accordion-block__column');
            
            // Calculate how many items should be in each column
            const totalItems = originalItemsOrder.length;
            const itemsPerColumn = Math.ceil(totalItems / columns);
            
            // Collect items in round-robin order
            for (let itemIndex = 0; itemIndex < totalItems; itemIndex++) {
                const columnIndex = itemIndex % columns;
                const positionInColumn = Math.floor(itemIndex / columns);
                
                if (columnElements[columnIndex]) {
                    const itemInColumn = columnElements[columnIndex].children[positionInColumn];
                    if (itemInColumn) {
                        itemsInOrder.push(itemInColumn);
                    }
                }
            }
            
            return itemsInOrder;
        } else {
            // Items are in single column, return them as they are
            return Array.from(grid.querySelectorAll('.c-accordion-block__item'));
        }
    }
    
    /**
     * Distribute accordion items into separate columns
     */
    function distributeItemsIntoColumns() {
        if (columns <= 1) return;
        
        // Check if we're on mobile
        const isMobile = window.innerWidth < 768;
        if (isMobile) {
            isColumnLayout = false;
            return; // Don't create columns on mobile
        }
        
        // Create column containers
        const columnContainers = [];
        for (let i = 0; i < columns; i++) {
            const column = document.createElement('div');
            column.className = 'c-accordion-block__column';
            columnContainers.push(column);
        }
        
        // Distribute items evenly across columns
        const itemsArray = Array.from(accordionItems);
        itemsArray.forEach((item, index) => {
            const columnIndex = index % columns;
            columnContainers[columnIndex].appendChild(item);
        });
        
        // Clear the grid and add column containers
        grid.innerHTML = '';
        columnContainers.forEach(column => {
            grid.appendChild(column);
        });
        
        // Update the grid class to use flexbox layout
        grid.classList.add('c-accordion-block__grid--flex-columns');
    }
    
    /**
     * Initialize accordion toggle functionality with full accessibility
     */
    function initAccordionToggles() {
        // Use event delegation since toggles might be reorganized
        grid.addEventListener('click', function(e) {
            const toggle = e.target.closest('.c-accordion-block__toggle');
            if (!toggle) return;
            
            const content = toggle.parentNode.querySelector('.c-accordion-block__content');
            const icon = toggle.querySelector('.c-accordion-block__toggle-icon');
            
            if (!content) return;
            
            toggleAccordionItem(toggle, content, icon);
        });
        
        // Add keyboard event listener with delegation
        grid.addEventListener('keydown', function(e) {
            const toggle = e.target.closest('.c-accordion-block__toggle');
            if (!toggle) return;
            
            const allCurrentToggles = grid.querySelectorAll('.c-accordion-block__toggle');
            handleToggleKeydown(e, toggle, allCurrentToggles);
        });
        
        // Initialize states for all toggles
        const allToggles = grid.querySelectorAll('.c-accordion-block__toggle');
        allToggles.forEach(function(toggle) {
            const content = toggle.parentNode.querySelector('.c-accordion-block__content');
            const icon = toggle.querySelector('.c-accordion-block__toggle-icon');
            
            if (!content) return;
            
            // Ensure initial state is correct
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            updateAccordionItemState(toggle, content, icon, isExpanded);
        });
    }
    
    /**
     * Toggle an individual accordion item
     * @param {HTMLElement} toggle - The toggle button
     * @param {HTMLElement} content - The content container
     * @param {HTMLElement} icon - The toggle icon
     */
    function toggleAccordionItem(toggle, content, icon) {
        const isCurrentlyExpanded = toggle.getAttribute('aria-expanded') === 'true';
        const newState = !isCurrentlyExpanded;
        
        updateAccordionItemState(toggle, content, icon, newState);
        
        // Announce to screen readers
        announceToggleChange(toggle, newState);
        
        // Smooth scroll to item if opening and it's not fully visible
        if (newState) {
            setTimeout(() => {
                scrollToItemIfNeeded(toggle);
            }, 300); // Allow time for content to expand
        }
    }
    
    /**
     * Update the state of an accordion item
     * @param {HTMLElement} toggle - The toggle button
     * @param {HTMLElement} content - The content container
     * @param {HTMLElement} icon - The toggle icon
     * @param {boolean} isExpanded - Whether the item should be expanded
     */
    function updateAccordionItemState(toggle, content, icon, isExpanded) {
        const item = toggle.parentNode;
        
        // Update ARIA attributes
        toggle.setAttribute('aria-expanded', isExpanded.toString());
        content.hidden = !isExpanded;
        
        // Update classes
        item.classList.toggle('c-accordion-block__item--open', isExpanded);
        
        // Update icon - switch between + and -
        if (icon) {
            const svg = icon.querySelector('svg');
            if (svg) {
                if (isExpanded) {
                    // Show minus icon
                    svg.innerHTML = '  <path d="M8 12H16M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#8F1A95" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                } else {
                    // Show plus icon
                    svg.innerHTML = '  <path d="M12 8V16M8 12H16M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#0047BB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                }
            }
        }
        
        // Handle content height for smooth animation
        if (isExpanded) {
            content.style.maxHeight = content.scrollHeight + 'px';
        } else {
            content.style.maxHeight = '0px';
        }
    }
    
    /**
     * Handle keyboard navigation for accordion toggles
     * @param {KeyboardEvent} e - The keyboard event
     * @param {HTMLElement} currentToggle - The currently focused toggle
     * @param {NodeList} allToggles - All toggles in this accordion
     */
    function handleToggleKeydown(e, currentToggle, allToggles) {
        const visibleToggles = Array.from(allToggles).filter(toggle => 
            !toggle.parentNode.classList.contains('c-accordion-block__item--hidden')
        );
        const currentIndex = visibleToggles.indexOf(currentToggle);
        
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                focusNextToggle(visibleToggles, currentIndex, 1);
                break;
            case 'ArrowUp':
                e.preventDefault();
                focusNextToggle(visibleToggles, currentIndex, -1);
                break;
            case 'Home':
                e.preventDefault();
                visibleToggles[0]?.focus();
                break;
            case 'End':
                e.preventDefault();
                visibleToggles[visibleToggles.length - 1]?.focus();
                break;
        }
    }
    
    /**
     * Focus the next/previous toggle in the sequence
     * @param {Array} toggles - Visible toggle elements
     * @param {number} currentIndex - Current toggle index
     * @param {number} direction - Direction to move (1 for next, -1 for previous)
     */
    function focusNextToggle(toggles, currentIndex, direction) {
        const nextIndex = (currentIndex + direction + toggles.length) % toggles.length;
        toggles[nextIndex]?.focus();
    }
    
    /**
     * Initialize load more functionality
     */
    function initLoadMoreFunctionality() {
        loadMoreBtn.addEventListener('click', function() {
            showMoreItems();
        });
        
        // Keyboard support for load more button
        loadMoreBtn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                showMoreItems();
            }
        });
        
        // Update initial load more button state
        updateLoadMoreButton();
    }
    
    /**
     * Show more accordion items
     */
    function showMoreItems() {
        const allItemsVisible = currentlyShowing >= totalItems;
        
        if (allItemsVisible) {
            // Show less - hide items back to initial amount
            const allItems = getAllItemsInOrder();
            
            for (let i = itemsToShow; i < currentlyShowing; i++) {
                const item = allItems[i];
                if (item) {
                    item.classList.add('c-accordion-block__item--hidden');
                    
                    // Close any open accordion items that are being hidden
                    const toggle = item.querySelector('.c-accordion-block__toggle');
                    const content = item.querySelector('.c-accordion-block__content');
                    const icon = item.querySelector('.c-accordion-block__toggle-icon');
                    if (toggle && toggle.getAttribute('aria-expanded') === 'true') {
                        updateAccordionItemState(toggle, content, icon, false);
                    }
                }
            }
            currentlyShowing = itemsToShow;
            
            // Announce to screen readers
            announceItemsHidden(currentlyShowing, totalItems);
        } else {
            // Show more items
            const itemsToAdd = Math.min(itemsToShow, totalItems - currentlyShowing);
            const allItems = getAllItemsInOrder();
            
            for (let i = currentlyShowing; i < currentlyShowing + itemsToAdd; i++) {
                const item = allItems[i];
                if (item) {
                    item.classList.remove('c-accordion-block__item--hidden');
                    
                    // Add fade-in animation
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                }
            }
            
            currentlyShowing += itemsToAdd;
            
            // Announce to screen readers
            announceItemsLoaded(itemsToAdd, currentlyShowing, totalItems);
        }
        
        updateLoadMoreButton();
    }
    
    /**
     * Update the load more button state and text
     */
    function updateLoadMoreButton() {
        if (!loadMoreBtn) return;
        
        const allItemsVisible = currentlyShowing >= totalItems;
        const buttonText = loadMoreBtn.querySelector('span');
        const buttonIcon = loadMoreBtn.querySelector('svg path');
        
        if (allItemsVisible) {
            // Show "Show Less" button
            if (buttonText) {
                buttonText.textContent = 'Show Less';
            }
            
            // Update icon to point up (reverse the rotation)
            if (buttonIcon) {
                buttonIcon.setAttribute('d', 'M6 1L1 6L6 11'); // Point up
            }
            
            loadMoreBtn.setAttribute('aria-label', 
                `Show Less. Currently showing all ${totalItems} items. Click to show only ${itemsToShow} items.`
            );
        } else {
            // Show "Load More" button
            const remainingItems = totalItems - currentlyShowing;
            
            if (buttonText) {
                buttonText.textContent = loadMoreText;
            }
            
            // Update icon to point down (Figma design)
            if (buttonIcon) {
                buttonIcon.setAttribute('d', 'M1 11L6 6L1 1'); // Point down - exact from Figma
            }
            
            loadMoreBtn.setAttribute('aria-label', 
                `${loadMoreText}. Currently showing ${currentlyShowing} of ${totalItems} items. ${remainingItems} items remaining.`
            );
        }
        
        // Always show the button if there are more items than initially shown (unless showing all)
        loadMoreBtn.style.display = (showAll || totalItems <= itemsToShow) ? 'none' : 'flex';
    }
    
    /**
     * Scroll to accordion item if it's not fully visible
     * @param {HTMLElement} toggle - The toggle button that was activated
     */
    function scrollToItemIfNeeded(toggle) {
        const rect = toggle.getBoundingClientRect();
        const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;
        
        if (!isVisible) {
            toggle.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }
    
    /**
     * Announce toggle state change to screen readers
     * @param {HTMLElement} toggle - The toggle button
     * @param {boolean} isExpanded - New expanded state
     */
    function announceToggleChange(toggle, isExpanded) {
        const title = toggle.querySelector('.c-accordion-block__toggle-title')?.textContent || '';
        const message = isExpanded ? 
            `${title} section expanded` : 
            `${title} section collapsed`;
        
        announceToScreenReader(message);
    }
    
    /**
     * Announce loaded items to screen readers
     * @param {number} itemsAdded - Number of items just loaded
     * @param {number} totalShowing - Total items now showing
     * @param {number} totalItems - Total items available
     */
    function announceItemsLoaded(itemsAdded, totalShowing, totalItems) {
        const message = `Loaded ${itemsAdded} more items. Now showing ${totalShowing} of ${totalItems} items.`;
        announceToScreenReader(message);
    }
    
    /**
     * Announce hidden items to screen readers
     * @param {number} totalShowing - Total items now showing
     * @param {number} totalItems - Total items available
     */
    function announceItemsHidden(totalShowing, totalItems) {
        const itemsHidden = totalItems - totalShowing;
        const message = `Collapsed to show ${totalShowing} items. ${itemsHidden} items are now hidden.`;
        announceToScreenReader(message);
    }
    
    /**
     * Announce message to screen readers using live region
     * @param {string} message - Message to announce
     */
    function announceToScreenReader(message) {
        let liveRegion = document.getElementById('accordion-live-region');
        
        if (!liveRegion) {
            liveRegion = document.createElement('div');
            liveRegion.id = 'accordion-live-region';
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.style.position = 'absolute';
            liveRegion.style.left = '-10000px';
            liveRegion.style.width = '1px';
            liveRegion.style.height = '1px';
            liveRegion.style.overflow = 'hidden';
            document.body.appendChild(liveRegion);
        }
        
        liveRegion.textContent = message;
        
        // Clear the message after a short delay
        setTimeout(() => {
            liveRegion.textContent = '';
        }, 1000);
    }
}
