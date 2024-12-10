<?php

/**
 * Provide a admin area view for components page
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */
?>
<?php if (current_user_can( 'manage_options' )): ?>
	
	<div id="app">
	  <list-components > </list-components>
	</div>
	 
	<script type="text/x-template" id="list-components">
	  <?php include_once( 'ultrapress-admin-display-components-template.php' );?>
	</script>


	</div>
	
<?php endif ?>
