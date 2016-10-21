<?php
	/**
	* Plugin Name: Amenities Map
	* Description: This plugin will add an amenity map to your webpage. 
	* Version: 1.0.0
	* Author: Zafer Sawaf
	*/
if ( ! defined( 'AMENITY_URL' ) )
define( 'AMENITY_URL', plugin_dir_path( __FILE__ ) );

require(AMENITY_URL.'core/init-data.php');
require(AMENITY_URL.'core/functions.php');
require(AMENITY_URL.'core/view.php');

function amenity_map_shortcode() {

}

function do_amentity_map() {
	?>

	<div class="map-outer">
		<div class="map-sidebar">
			<?php //do_categery_list() ?>
		</div>
		<div class="map-main">
			<div id="amenities-map" class="amenities-map google-maps-outer">
				<div class="map-inner">
					<div id="map_canvas">
						
					</div>
				</div>
				<div class="loading"></div>
			</div>
		</div>
	</div>

<?php }

add_shortcode('amentity_map', 'do_amentity_map');


do_shortcode('[amentity_map]');