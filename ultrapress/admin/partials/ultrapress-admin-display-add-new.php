<?php

/**
 * Provide a admin area view for add circuits & components page
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>

<?php if (current_user_can( 'manage_options' )): ?>
	
	<div id="app">
	  <add-circuits-comps> </add-circuits-comps>
	</div>
	          	 
	<script type="text/x-template" id="add-circuits-comps">
	  <?php include_once( 'ultrapress-admin-display-add-circuits-comps.php' );?>
	</script>

<?php endif ?>
