// Frontend JavaScript for the example block
// This file is loaded on the frontend when the block is used

document.addEventListener('DOMContentLoaded', function() {
    const exampleBlocks = document.querySelectorAll('.c-example-block');
    
    exampleBlocks.forEach(function(block) {
        // Add any frontend interactivity here
        // For example, animations, event listeners, etc.
        
        // Example: Add a loaded class after block is initialized
        block.classList.add('c-example-block--loaded');
    });
});
