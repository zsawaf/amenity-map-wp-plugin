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
require(AMENITY_PATH.'core/scripts-styles.php');
require(AMENITY_PATH.'core/functions.php');
require(AMENITY_PATH.'core/init-meta-fields.php');
require(AMENITY_PATH.'core/init-settings.php');
require(AMENITY_PATH.'core/init-styles.php');

function get_shortcode_scripts() {

	wp_localize_script('am-amenities-map-script', 'AMENITIES', 
		array(
			'data' => get_post_amenities(), 
			'categories' => get_amenity_categories(), 
			'theme_url' => AMENITY_URL, 
			'infobox_display' => get_infobox_display_options(), 
			'primary_location' => get_primary_location(), 
			'primary_location_icon' => get_option('am_primary_location_icon'), 
			'active_icon' => get_option('am_active_icon'), 
			'maps_api_key' => $maps_api_key, 
			'map_styles' => get_map_styles()
		) 
	);

	wp_localize_script('am-sm-script', 'OPTIONS', array(
			'data' => get_sm_options(),
		)
	);

	wp_enqueue_script('am-google-maps');
	wp_enqueue_script('am-amenities-map-script');
	wp_enqueue_script('am-infobox');
	wp_enqueue_script('am-sm-script');
	wp_enqueue_style('am-amenities-map-style');
	
}

function do_amentity_map() {

	get_shortcode_scripts();

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

function do_map($atts) {

	get_shortcode_scripts();

	$a = shortcode_atts( array(
		'id' => '1',
	), $atts, 'single_map' );

	$map_post = get_post(array("ID" => $a['id']));

	ob_start(); ?>
	<div id="am_single_map"></div>
	<script>
		jQuery(document).ready(function($){
			var map = new Map(<?php echo $a['id'] ?>);
		});
	</script>
	<?php
	return ob_get_clean();
}

add_shortcode('single_map', 'do_map');
