<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>

<?php if (current_user_can( 'manage_options' )): ?>
	<div id="app" class="">
	  <ultrapress-comp> </ultrapress-comp>
	</div> 
	 
	<script type="text/x-template" id="ultrapress-template">
	  <?php include_once( 'ultrapress-admin-template.php' );?>
	</script>
	     	
<?php endif ?>

