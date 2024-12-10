<?php
/**
 * admin page
 *
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */
?> 

<div class="ultrapress-container container-fluid p-0">   

  <div class="ultrapress-header p-0"> <!-- head of ultrapress-comp  -->
    <?php include_once( 'ultrapress-admin-header.php' );?>
  </div> <!--  end of head of ultrapress-comp -->
  <div class="row ultrapress-mvc h-100 "> <!--  mvc of head of ultrapress-comp -->
    <div class="ultrapress-view col-md-9 p-0"> <!-- view of ultrapress-comp  -->
      <?php include_once( 'ultrapress-admin-svg.php' );?>
    </div> <!-- end of view of ultra -->
    <div class="ultrapress-data col-md-3 overflow-auto p-0 pl-1 pt-2 small" id="sidebar-mp"> <!-- disc of ultra -->
      <?php include_once( 'ultrapress-admin-sidebar.php' );?>
    </div> <!-- end of disc of ultra  -->
    <!-- Flexbox container for aligning the toasts -->
    

  </div> <!-- end of mvc of head of ultra  -->
  <div> <!-- all dialogs -->
    <?php include_once( 'ultrapress-admin-dialogs.php' );?>
  </div> <!--  end  -->
  
  <?php include_once( 'ultrapress-admin-tips-carousel.php' ); ?>
  
</div> 
