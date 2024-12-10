<?php

/**
 * circuits page
 *
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */

$array_of_circuits  = get_option('ultrapress', array());

?>

<div class="container">
	<div class="d-flex justify-content-between my-5">
	    <h2><?php _e('Circuits', 'ultrapress'); ?> </h2> 
	    
	    <a href="https://ultra-press.com/features/" class="btn btn-outline-secondary btn-lg">
	    	<?php _e('load circuits & components', 'ultrapress'); ?>
	    </a> 
    </div>
  
  <table class="table">
    <thead>
      <tr>
        <th><?php _e('Circuit', 'ultrapress'); ?></th>
        <th><?php _e('Description ', 'ultrapress'); ?></th> 
      </tr>
    </thead>

    <tbody>
    	<?php
			foreach ($array_of_circuits as $key_of_circ => $circuit) {
		?>
		<tr>
			<td style='width: 30%;'>
				<?php echo esc_html( $key_of_circ); ?>
				
				<br>

					<?php $nonce_deactivate_circuit = wp_create_nonce( 'ultrapress_deactivate_circuit' ); ?>
					<button type="button" id="deactivate-circuit" data-nonce="<?php echo $nonce_deactivate_circuit;?>" class="btn btn-sm btn-danger small p-0 px-1" v-on:click="deactivate_circuit('<?php echo $key_of_circ; ?>')"> 
					   <span class="small"> <?php _e('Deactivate ', 'ultrapress'); ?></span>
					</button>
									
			</td>
			<td>
			    <?php echo esc_html( $circuit["disc"]); ?> 
			    <hr>	
			    <?php
			    	if ( array_key_exists('package', $circuit) ) {
						echo "<strong>package:</strong> " . esc_html( $circuit[ 'package' ]);
					} else {
						echo "<strong class='text-danger'>no package</strong> ";
					}
			     ?>
			    	
			</td>;
		</tr>
		<?php 
	    }
	    ?>
    </tbody>
  </table>
</div>


