<?php

// enqueue scripts and styles
add_action('wp_enqueue_scripts', 'am_add_scripts');
function am_add_scripts() {

	$maps_api_key = get_option('am_gm_api_key');

	wp_register_script('am-google-maps', 'https://maps.googleapis.com/maps/api/js?&libraries=places&key='.$maps_api_key );
	

	wp_register_style( 'am-amenities-map-style', AMENITY_URL.'/assets/css/styles.css' );

	wp_register_script('am-amenities-map-script', AMENITY_URL.'/assets/js/map.js', array('jquery', 'am-infobox'));
	wp_register_script('amenity-accordian', AMENITY_URL.'/assets/js/amenity-accordian.js', array('jquery'));

	wp_register_script('am-sm-script', AMENITY_URL.'/assets/js/single_map.js', array('jquery'));

	wp_register_script('am-infobox', AMENITY_URL.'/assets/js/infobox.js');

}


function load_admin_scripts($hook) {
		global $post;

		lt($post);

		if( 'post-new.php' != $hook && 'post.php' != $hook ) {
			return;
		}
		if ( isset($_GET['post_type']) && ( 'amenities' != $_GET['post_type']  &&  'single_maps' != $_GET['post_type']) ) {
			return;
		}
		if( !isset($_GET['post_type']) && ( 'amenities' != get_post_type($post->ID) &&  'single_maps' != get_post_type($post->ID)  ) ) {
			return;
		}

		$maps_api_key = get_option('am_gm_api_key');
		$maps_api_url = 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places&callback=initAutocomplete';

		wp_register_script('am_admin_scripts', AMENITY_URL.'/assets/js/am-admin-scripts.js', array( 'jquery'), null, true);
			wp_localize_script('am_admin_scripts', 
				'AMENITIES', 
				array( 
					'data' => get_primary_location(), 
					'theme_url' => AMENITY_URL
				)
			);
		wp_enqueue_script('am_admin_scripts');

		wp_enqueue_script('am-admin-google-maps', $maps_api_url, array('am_admin_scripts'), null, true );
		wp_enqueue_style('am_admin_styles', AMENITY_URL. '/assets/css/am-admin-styles.css');
		
}
add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );


function enqueue_am_scripts() {

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

	wp_localize_script('amenity-accordian', 'CATEGORY_AMENITIES', array(
			'categories' => get_amenities_by_categories(),
		)
	);

	wp_enqueue_script('am-google-maps');
	wp_enqueue_script('amenity-accordian');
	wp_enqueue_script('am-amenities-map-script');
	wp_enqueue_script('am-infobox');
	wp_enqueue_script('am-sm-script');
	wp_enqueue_style('am-amenities-map-style');
	
}