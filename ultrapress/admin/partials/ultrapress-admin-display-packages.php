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
	<div id="app1">
	  <list-packages> </list-packages>
	</div>
	<script type="text/x-template" id="list-packages">
	  <?php include_once( 'ultrapress-admin-display-packages-template.php' );?>
	</script>

<?php endif ?>
