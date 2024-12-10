<?php

/**
 * packages page
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>
<div class="container">
	<div class="d-flex justify-content-between my-5">
	    <h2><?php _e('packages', 'ultrapress'); ?> </h2> 
	    
	    <a href="https://ultra-press.com/packages/" class="btn btn-outline-secondary btn-lg">
	    	<?php _e('load packages', 'ultrapress'); ?>
	    </a> 
    </div>
<?php
$array_of_activated_packages  = get_option('ultrapress_packages', array());
$non_activated_packages  = array();
$dirs = glob( ULTRAPRESS_PATH . 'packages/*', GLOB_ONLYDIR);

foreach($dirs as $dir_of_package)
{	
	if (! file_exists( $dir_of_package . DIRECTORY_SEPARATOR . 'data.json') ) {
		continue;
	}
	
	$json_path = file_get_contents( $dir_of_package . DIRECTORY_SEPARATOR . 'data.json');
	$json = json_decode($json_path, true);
	$desc  = $json['desc'];
	$name  = $json['name'];
	if (! array_key_exists($name, $array_of_activated_packages)) {
		$non_activated_packages[$name]  = array();
		$non_activated_packages[$name]['name']  = $name;
		$non_activated_packages[$name]['desc']  = $desc;
		// relative path from PLUGINS directory
		$non_activated_packages[$name]['path']  = substr($dir_of_package, strlen(WP_PLUGIN_DIR));
	}
}
?>
  <table class="table">
    <thead>
      <tr>

        <th><?php _e('Packages', 'ultrapress'); ?></th>
        <th><?php _e('Description ', 'ultrapress'); ?></th> 
      </tr>
    </thead>

    <tbody>
    	<?php
			foreach ($array_of_activated_packages as $name_of_package => $pack) {
				// relative path from PLUGINS directory
				$path_of_package = $pack["path"];
		?>
		<tr>
			<td style='width: 30%;'>
				<?php echo esc_html( $name_of_package); ?>
				<br>

				<?php $nonce_deactivate_package = wp_create_nonce( 'ultrapress_deactivate_package' ); ?>
				<button type="button" id="deactivate-package" data-nonce="<?php echo $nonce_deactivate_package;?>" class="btn btn-sm btn-danger small p-0 px-1" v-on:click="deactivate_package('<?php echo $name_of_package; ?>')"> 
				   <span class="small"> <?php _e('Deactivate ', 'ultrapress'); ?></span>
				</button>
				
			</td>
			<td>
			    <?php echo esc_html( $pack["desc"]); ?> 
			    <?php if ($json_path): ?>
			    <hr>
			    <strong><?php _e('path of package', 'ultrapress'); ?>:</strong>
			     <?php echo esc_html( $path_of_package); ?> 
			    <?php endif ?>
			</td>;
		</tr>
		<?php 
	    }


	    // non activated packages
		foreach ($non_activated_packages as $name_of_package => $pack) {
			$path_of_package = $pack["path"];
		?>
		<tr>
			<td style='width: 30%;'>
				<?php echo esc_html( $name_of_package); ?>
				<br>				
				<?php $nonce_activate_package = wp_create_nonce( 'ultrapress_activate_package' ); ?>
				<button type="button" id="<?php echo esc_html( $name_of_package);?>" data-nonce="<?php echo $nonce_activate_package;?>" data-name_of_package="<?php echo esc_html( $name_of_package);?>" data-path_of_package="<?php echo esc_html( $path_of_package);?>" class="btn btn-sm btn-success small p-0 px-1" v-on:click="activate_package($event)"> 
					<span class="small"> <?php _e('Activate', 'ultrapress'); ?></span>	   
				</button>
				
				<?php $nonce_delete_package = wp_create_nonce( 'ultrapress_delete_package' ); ?>
				<button type="button" id="delete-<?php echo esc_html( $name_of_package);?>" data-nonce="<?php echo $nonce_delete_package;?>"  data-name_of_package="<?php echo esc_html( $name_of_package);?>" data-path_of_package="<?php echo esc_html( $path_of_package);?>" class="btn btn-sm btn-secondary small p-0 px-1" v-on:click="delete_package($event)"> 
					<span class="small"> <?php _e('Delete', 'ultrapress'); ?></span>   
				</button>				
			</td>
			<td>
			    <?php echo esc_html( $pack["desc"]); ?> 
			    <hr>
			    <strong><?php _e('path of package', 'ultrapress'); ?>:</strong>
			     <?php echo esc_html( $path_of_package); ?> 
			</td>;
		</tr>
		<?php 
	    }
	    ?>
    </tbody>
  </table>
</div>


