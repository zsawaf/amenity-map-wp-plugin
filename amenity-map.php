<?php
	/**
	* Plugin Name: Amenities Map
	* Description: This plugin will add an amenity map to your webpage. 
	* Version: 1.0.0
	* Author: Zafer Sawaf
	*/

define('AMENITY_PATH', plugin_dir_path(__FILE__));
define('AMENITY_URL', plugins_url('', __FILE__));

require(AMENITY_PATH.'core/init-data.php');
require(AMENITY_PATH.'core/functions.php');
require(AMENITY_PATH.'core/init-meta-fields.php');
require(AMENITY_PATH.'core/init-settings.php');
require(AMENITY_PATH.'core/init-styles.php');

function do_amentity_map() {
	ob_start(); ?>
	<div class="am <?php echo get_option('am_display_setting')?>">
		<div class="am_navigation">
			<?php display_amenity_category_menu() ?>
		</div>
		<div id="am_map" class="am_map google-maps-outer">
			<div class="map-inner">
				<div id="map_canvas">
					
				</div>
			</div>
			<div class="loading"></div>
		</div>
	</div>


	<script>
	jQuery(document).ready(function($){
		doAreaAmenities.init({
			termsContainer : $(".am_navigation"),
			loading: $(".loading")
		});
	});
	</script>
	
	<?php
	return ob_get_clean();
}

add_shortcode('amenity_map', 'do_amentity_map');