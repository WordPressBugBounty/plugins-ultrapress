<?php

/**
 * header of admin page
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?> 

<div id="masthead-mp" class="site-header" >
	<nav id="site-navigation-mp" class="main-navigation navbar-light navbar p-0 navbar-expand-md bg-primary text-white">
		<ul class="navbar-nav">
			<div class="btn-group btn-group-lg mr-auto float-left">
				<div class="btn-group">
				    <button type="button" class="btn btn-primary" v-on:click="modalPlugin">
				    	<i class="fa fa-plug text-white fa-lg" title="<?php _e('create plugin', 'ultrapress'); ?>"></i>
				    </button> 			    
				</div>

				<div class="btn-group">
				    <button type="button" class="btn btn-primary" v-on:click="modalPackage">
				    	<i class="fa fa-share-square text-white fa-lg" title="<?php _e('create package', 'ultrapress'); ?>"></i>
				    </button> 			    
				</div>	

				<div class="btn-group">
				    <button type="button" class="btn btn-primary" v-on:click="addComposedCompInfo">
				       	<i class="fa fa-cubes text-white fa-lg" title="<?php _e('create composed component', 'ultrapress'); ?>"></i>
					</button>
				</div>

				<div class="btn-group">
				    <button type="button" class="btn btn-primary" v-on:click="addNewCircInfo">
				       	<i class="fa fa-plus text-white fa-lg" title="<?php _e('create new circuit', 'ultrapress'); ?>"></i>
				    </button> 
				</div>
				<div class="vl"></div>

				<div class="my-auto ml-1" title="choose circuit">
					 <?php _e('choose Circuit', 'ultrapress'); ?>
					 <select v-model="currentCircuitKey">
					    <option v-for="(circ, key, x) in circuits" v-bind:value="key">
					    	{{ key }}
					    </option>
					</select>
				</div>				  
			</div>
		</ul>

		<ul class="nav navbar-nav mx-auto"> 
	        <li class="nav-item ultrapress-logo">
	             <?php _e('Ultrapress', 'ultrapress'); ?>
			</li>
  		</ul>

		<ul class="nav navbar-nav">
			<div class="btn-group btn-group-lg mr-auto float-left">
			    <div class="btn-group">
			    	
				  	<button type="button"  class="btn btn-primary"  v-on:click="modalRun()">

				  		<i class="fa fa-play-circle text-white fa-lg" title="<?php _e('run circuit', 'ultrapress'); ?>"></i>
				    </button>

				    <?php $nonce = wp_create_nonce( 'ultrapress_save' ); ?>
				  	<button type="button" id="save-circuit" class="btn btn-primary" data-nonce="<?php echo $nonce;?>"  v-on:click="save()">
				  		<i class="fa fa-save text-white fa-lg" title="<?php _e('save circuit', 'ultrapress'); ?>"></i>
				    </button> 

				    <div class="vl"></div>

				    <button type="button" class="btn btn-primary ml-1" v-on:click="zoomPlus()">
				  		<i class="fa fa-search-plus  text-white fa-lg" title="<?php _e('zoom out', 'ultrapress'); ?>"></i>
				    </button> 

				    <button type="button" class="btn btn-primary" v-on:click="zoomMinus()">
				  		<i class="fa fa-search-minus  text-white fa-lg" title="<?php _e('zoom in', 'ultrapress'); ?>"></i>
				    </button> 


			  </div>
			</div>
		</ul>
	</nav><!-- #site-navigation-mp -->

	</div><!-- #masthead-mp -->