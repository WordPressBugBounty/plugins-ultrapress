// Ultrapress Admin Scripts
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ultrapress Admin JS loaded successfully - Version:', ultrapressData.version);
    
    // Initialize any existing tutorial scripts
    if (typeof TipsCarousel !== 'undefined') {
        new TipsCarousel();
    }
    
    // Debug logging for carousel
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Page URL:', window.location.href);
        console.log('Current admin page:', document.body.className);
        
        // Check if we're on the correct page
        if (document.querySelector('.ultrapress-tutorial-toolbar')) {
            console.log('Found tutorial toolbar - this is the correct page');
        } else {
            console.log('Tutorial toolbar not found - wrong page');
        }
        
        console.log('Searching for carousel container...');
        const carouselContainer = document.getElementById('tips-carousel-container');
        console.log('Carousel container found:', carouselContainer);
        
        if (carouselContainer) {
            const computedStyle = window.getComputedStyle(carouselContainer);
            console.log('Carousel container styles:', {
                display: computedStyle.display,
                visibility: computedStyle.visibility,
                position: computedStyle.position,
                zIndex: computedStyle.zIndex,
                width: computedStyle.width,
                height: computedStyle.height,
                bottom: computedStyle.bottom,
                left: computedStyle.left,
                right: computedStyle.right
            });

            // Check parent elements
            let parent = carouselContainer.parentElement;
            let parentPath = [];
            while (parent) {
                parentPath.push({
                    tag: parent.tagName,
                    id: parent.id,
                    classes: parent.className,
                    display: window.getComputedStyle(parent).display
                });
                parent = parent.parentElement;
            }
            console.log('Parent element hierarchy:', parentPath);
        }
    });
    
    // Add your other admin scripts here
});
