<?php

/**
 * add circuits & comps page
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>
<div class="container pt-3">
  <div class="ultrapress-tutorial-toolbar">
    <div class="tutorial-step" data-step="1">
        <div class="step-number">1</div>
        <div class="step-content">
            <h4>Double Click to Add</h4>
            <p>Double click anywhere in the workspace to add a new component</p>
        </div>
    </div>
    <div class="tutorial-step" data-step="2">
        <div class="step-number">2</div>
        <div class="step-content">
            <h4>Connect Components</h4>
            <p>Draw lines between red (output) and green (input) nodes</p>
        </div>
    </div>
    <div class="tutorial-step" data-step="3">
        <div class="step-number">3</div>
        <div class="step-content">
            <h4>Configure Settings</h4>
            <p>Click components to adjust their settings</p>
        </div>
    </div>
    <div class="tutorial-close">×</div>
  </div>

  <style>
  .ultrapress-tutorial-toolbar {
      position: fixed;
      top: 32px;
      left: 0;
      right: 0;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 10px 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      z-index: 1000;
  }

  .tutorial-step {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
      border-radius: 4px;
      background: #f8f9fa;
  }

  .step-number {
      width: 24px;
      height: 24px;
      background: #2271b1;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
  }

  .step-content {
      max-width: 200px;
  }

  .step-content h4 {
      margin: 0 0 5px;
      color: #1d2327;
  }

  .step-content p {
      margin: 0;
      font-size: 12px;
      color: #50575e;
  }

  .tutorial-close {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 20px;
      color: #666;
      padding: 5px;
  }

  .tutorial-close:hover {
      color: #1d2327;
  }
  </style>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const toolbar = document.querySelector('.ultrapress-tutorial-toolbar');
      const closeBtn = document.querySelector('.tutorial-close');
      
      // Check if user has dismissed the tutorial
      if (localStorage.getItem('ultrapressTutorialClosed')) {
          toolbar.style.display = 'none';
      }

      // Handle close button
      closeBtn.addEventListener('click', function() {
          toolbar.style.display = 'none';
          localStorage.setItem('ultrapressTutorialClosed', 'true');
      });
  });
  </script>

  <h4>
    <?php _e( 'add packages' ); ?>
    <button type="button" id="upload-package-button"  class="btn btn-outline-primary" data-toggle="collapse" data-target="#Upload-package-form"> 
          <?php _e( 'Upload package' ); ?>
    </button>
  </h4>
  <div class="Upload-package border p-5 collapse bg-light" id="Upload-package-form">
    <p class="d-flex justify-content-center mark"><?php _e( 'If you have a package in a .zip format, you may install it by uploading it here.' ); ?>
    </p>

    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
      </div>
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="file"
          aria-describedby="inputGroupFileAddon02" accept=".zip">
        <label class="custom-file-label" for="file">Choose zip file</label>
      </div>
    </div>

    <div class="d-flex justify-content-center pt-3">
        <?php $nonce = wp_create_nonce( 'ultrapress_upload_package' ); ?>
        <button type="button" id="meed-send-package" data-nonce="<?php echo $nonce; ?>" data-user_id="<?php echo get_current_user_id(); ?>" class="btn btn-secondary m-2 px-3"> 
          <?php _e( 'install' ); ?>
        </button>
        </button>
    </div>
  </div>

  <form class="m-5 mb-2">
    <div class="input-group mb-3 input-group-sm">
       <div class="input-group-prepend">
         <span class="input-group-text"><?php _e('keywords', 'ultrapress'); ?>:</span>
      </div>
      <input type="text" v-model="keys" placeholder="<?php _e('Search circuits & components', 'ultrapress'); ?>" class="form-control" @change="load_circuits_comps_ajax($event)">
    </div>
  </form>

  <div class="alert alert-info" role="alert">
    <h4 class="alert-heading"><?php _e('Package Repository Under Development', 'ultrapress'); ?></h4>
    <p><?php _e('Our package repository service is currently under development. Soon you\'ll be able to browse and install packages directly from here.', 'ultrapress'); ?></p>
  </div>

  <div class="d-flex justify-content-between my-5">


        <div id="primary-2" class="content-area-2">
          <main id="main-2" class="site-main">

            <div class="container bg-white pb-4">
              <div class="container-fluid">
                <div class="row" id="inject-html">
                <br>
                loading ...
                 </div>
              </div>
            </div>
          </main><!-- #main -->
        </div><!-- #primary -->
  
</div>

<?php
// Debug output
echo "<!-- Debug: Adding carousel container -->\n";
?>
<div class="tips-carousel-container" id="tips-carousel-container" style="display: block !important;">
    <?php echo "<!-- Debug: Inside carousel container -->\n"; ?>
    <div class="tips-carousel">
        <div class="tip-slide active">
            <span class="tip-icon">💡</span>
            <span class="tip-text">Double-click anywhere to add a new component</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">🔗</span>
            <span class="tip-text">Connect components by dragging from red to green nodes</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">⚙️</span>
            <span class="tip-text">Click on a component to configure its settings</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">🎯</span>
            <span class="tip-text">Use the success/failure paths to handle different outcomes</span>
        </div>
        <div class="tip-slide">
            <span class="tip-icon">📦</span>
            <span class="tip-text">Export your circuit as a package to reuse it later</span>
        </div>
    </div>
    <button class="tip-nav prev">‹</button>
    <button class="tip-nav next">›</button>
</div>
<?php echo "<!-- Debug: After carousel container -->\n"; ?>

<style>
.tips-carousel-container {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    padding: 15px 50px;
    z-index: 9999;
    display: block !important;
}

.tips-carousel {
    overflow: hidden;
    position: relative;
    height: 40px;
}

.tip-slide {
    position: absolute;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.tip-slide.active {
    opacity: 1;
    transform: translateY(0);
}

.tip-icon {
    font-size: 20px;
}

.tip-text {
    font-size: 14px;
    color: #1d2327;
}

.tip-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 24px;
    color: #2271b1;
    cursor: pointer;
    padding: 0 10px;
    z-index: 2;
}

.tip-nav:hover {
    color: #135e96;
}

.tip-nav.prev {
    left: 10px;
}

.tip-nav.next {
    right: 10px;
}
</style>

<script>
console.log('Tips carousel script starting...');

class TipsCarousel {
    constructor() {
        console.log('TipsCarousel: Constructor called');
        
        // Get DOM elements
        this.container = document.getElementById('tips-carousel-container');
        console.log('Container element:', this.container);
        
        this.slides = document.querySelectorAll('.tip-slide');
        console.log('Found slides:', this.slides.length);
        
        this.prevButton = document.querySelector('.tip-nav.prev');
        this.nextButton = document.querySelector('.tip-nav.next');
        console.log('Navigation buttons:', { prev: this.prevButton, next: this.nextButton });

        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoPlayInterval = null;

        // Force container visibility
        if (this.container) {
            this.container.style.display = 'block';
            console.log('Forced container visibility');
        }

        // Initialize
        this.init();
        
        // Bind events
        this.bindEvents();
        
        // Start autoplay
        this.startAutoPlay();
    }

    init() {
        console.log('TipsCarousel: Initializing...');
        this.showSlide(0);
        console.log('Initial slide shown');
    }

    bindEvents() {
        console.log('TipsCarousel: Binding events');
        if (this.prevButton && this.nextButton) {
            this.prevButton.addEventListener('click', () => {
                console.log('Previous button clicked');
                this.prevSlide();
            });
            
            this.nextButton.addEventListener('click', () => {
                console.log('Next button clicked');
                this.nextSlide();
            });
            console.log('Events bound successfully');
        } else {
            console.error('Navigation buttons not found');
        }
    }

    showSlide(index) {
        console.log('Showing slide:', index);
        
        // Remove active class from all slides
        this.slides.forEach((slide, i) => {
            slide.classList.remove('active');
            slide.style.transform = 'translateY(20px)';
            slide.style.opacity = '0';
            console.log(`Slide ${i} reset`);
        });

        // Show active slide
        const slide = this.slides[index];
        if (slide) {
            slide.classList.add('active');
            slide.style.transform = 'translateY(0)';
            slide.style.opacity = '1';
            console.log(`Slide ${index} activated`);
        } else {
            console.error(`Slide ${index} not found`);
        }
    }

    nextSlide() {
        console.log('Moving to next slide');
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        this.showSlide(this.currentSlide);
        this.resetAutoPlay();
    }

    prevSlide() {
        console.log('Moving to previous slide');
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.showSlide(this.currentSlide);
        this.resetAutoPlay();
    }

    startAutoPlay() {
        console.log('Starting autoplay');
        this.autoPlayInterval = setInterval(() => {
            console.log('Auto-advancing to next slide');
            this.nextSlide();
        }, 5000);
    }

    resetAutoPlay() {
        console.log('Resetting autoplay');
        clearInterval(this.autoPlayInterval);
        this.startAutoPlay();
    }
}

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing carousel...');
    try {
        window.tipsCarousel = new TipsCarousel();
        console.log('Carousel initialized successfully');
    } catch (error) {
        console.error('Error initializing carousel:', error);
    }
});

// Additional check after a short delay
setTimeout(() => {
    const container = document.getElementById('tips-carousel-container');
    console.log('Container visibility check:', {
        element: container,
        display: container ? getComputedStyle(container).display : 'not found',
        visibility: container ? getComputedStyle(container).visibility : 'not found',
        zIndex: container ? getComputedStyle(container).zIndex : 'not found'
    });
}, 1000);
</script>
