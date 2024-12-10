<?php

/**
 * Provide a admin area view for circuits page
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>

<?php if (current_user_can( 'manage_options' )): ?>
	
	<div id="app">
	  <list-circuits> </list-circuits>
	</div>
	          	 
	<script type="text/x-template" id="list-circuits">
	  <?php include_once( 'ultrapress-admin-display-circuits-template.php' );?>
	</script>

<?php endif ?>
