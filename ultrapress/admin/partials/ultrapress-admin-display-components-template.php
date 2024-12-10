<?php

/**
 * components page
 *
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */

$components = Ultrapress_Class::$components;
$composed_components = Ultrapress_Class::$composed_components;
?>

<div class="container">
	<div class="d-flex justify-content-between my-5">
	    <h2><?php _e('components', 'ultrapress'); ?> </h2> 
	    
	    <a href="https://ultra-press.com/features/" class="btn btn-outline-secondary btn-lg">
	    	<?php _e('load circuits & components', 'ultrapress'); ?>
	    </a> 
    </div>
  <table class="table">
    <thead>
      <tr>
        <th><?php _e('components', 'ultrapress'); ?></th>
        <th><?php _e('Description', 'ultrapress'); ?></th> 
      </tr>
    </thead>
    <tbody>
    	<?php
			foreach ($components as $key_of_comp => $comp) {
				$is_composed      = $comp['composed'];
				if ($is_composed) {
					continue;
				}
				?>
				<tr>
					<td style='width: 30%;'>
						<?php echo esc_html( $key_of_comp); ?>	
						<br>

						<?php $nonce_delete_component = wp_create_nonce( 'ultrapress_delete_component' ); ?>
						<button type="button" id="delete-component" data-nonce="<?php echo $nonce_delete_component;?>" class="btn btn-sm btn-danger small p-0 px-1" v-on:click="delete_component('<?php echo $key_of_comp; ?>')"> 
						   <span class="small"><?php _e('delete', 'ultrapress'); ?> </span> 
						</button>				
				</td>
					<td>
					    <?php
					    	echo esc_html( $comp["disc"] );
					    ?>
					    <hr>
					    <strong><?php _e('path of component', 'ultrapress'); ?>:</strong>
					    <?php echo esc_html( $comp["path_of_component_dir"]); ?> 
					</td>;
				</tr>
				<?php	
			}
		?>
		<tr>
		    <th><?php _e('composed components', 'ultrapress'); ?></th>
		    <th><?php _e('Description', 'ultrapress'); ?></th>
    	</tr>
    	<?php
			foreach ($components as $key_of_comp => $comp) {
				$is_composed      = $comp['composed'];
				if (! $is_composed) {
					continue;
				}
				?>
				<tr>
					<td style='width: 30%;'>
						<?php echo esc_html( $key_of_comp); ?>	
						<br>

						<?php $nonce_delete_composed_component = wp_create_nonce( 'ultrapress_delete_composed_component' ); ?>
						<button type="button" id="delete-composed-component" data-nonce="<?php echo $nonce_delete_composed_component;?>" class="btn btn-sm btn-danger small p-0 px-1" v-on:click="delete_composed_component('<?php echo $key_of_comp; ?>')"> 
						   <span class="small"><?php _e('delete', 'ultrapress'); ?> </span> 
						</button>							
					</td>

					<td>
					    <?php
					    echo esc_html( $comp["disc"] );
					     ?>
					</td>;
				</tr>
				<?php	
			}
		?>
    </tbody>
  </table>
</div>