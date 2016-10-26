<?php 
	// enqueue scripts and styles
	add_action('wp_enqueue_scripts', 'am_add_scripts');
	function am_add_scripts() {
		$maps_api_key = get_option('am_gm_api_key');

 		wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?&libraries=places&key='.$maps_api_key );
        wp_enqueue_script('google-maps');

		wp_register_style( 'amenities-map-style', AMENITY_URL.'/assets/css/styles.css' );

		wp_register_script('amenities-map-script', AMENITY_URL.'/assets/js/map.js', array('jquery'));
		wp_register_script('infobox', AMENITY_URL.'/assets/js/infobox.js');

		wp_localize_script('amenities-map-script', 'AMENITIES', array( 'data' => get_post_amenities(), 'categories' => get_amenity_categories(), 'theme_url' => AMENITY_URL, 'infobox_display' => get_infobox_display_options(), 'primary_location' => get_primary_location(), 'primary_location_icon' => get_option('am_primary_location_icon'), 'active_icon' => get_option('am_active_icon'), 'maps_api_key' => $maps_api_key, 'map_styles' => get_map_styles() ) );

		wp_enqueue_script('amenities-map-script');
		wp_enqueue_script('infobox');
		wp_enqueue_style('amenities-map-style');
	}

	// enqueue and add admin scripts
	function load_admin_scripts() {
		$maps_api_key = get_option('am_gm_api_key');

		wp_enqueue_script('admin-google-maps', 'https://maps.googleapis.com/maps/api/js?key='.$maps_api_key.'&libraries=places&callback=initAutocomplete' );

		wp_enqueue_style('admin_css', AMENITY_URL. '/assets/css/admin.css');

		wp_register_script('admin_js', AMENITY_URL.'/assets/js/am.js');
		wp_localize_script('admin_js', 'AMENITIES', array( 'data' => get_primary_location(), 'theme_url' => AMENITY_URL) );

		wp_enqueue_script('admin_js');
    }
    add_action( 'admin_enqueue_scripts', 'load_admin_scripts' );

    /**
	* Add async attributes to enqueued scripts where needed.
	* The ability to filter script tags was added in WordPress 4.1 for this purpose.
	*/
	function my_async_scripts( $tag, $handle, $src ) {
		// the handles of the enqueued scripts we want to async
		$async_scripts = array('admin-google-maps');

		if ( in_array( $handle, $async_scripts ) ) {
		    return '<script type="text/javascript" src="' . $src . '" async defer></script>' . "\n";
		}

		return $tag;
	}
	add_filter( 'script_loader_tag', 'my_async_scripts', 10, 3 );

	/*
	*	ADD TAXONOMIES
	*/
	function amenities() {

	$labels = array(
		'name'                  => _x( 'Amenities', 'Post Type General Name', 'Amenity' ),
		'singular_name'         => _x( 'Amenity', 'Post Type Singular Name', 'Amenity' ),
		'menu_name'             => __( 'Amenities', 'Amenity' ),
		'name_admin_bar'        => __( 'Amentity', 'Amenity' ),
		'archives'              => __( 'Amenities', 'Amenity' ),
		'parent_item_colon'     => __( 'Parent Amentity', 'Amenity' ),
		'all_items'             => __( 'All Amenities', 'Amenity' ),
		'add_new_item'          => __( 'Add New Amentity', 'Amenity' ),
		'add_new'               => __( 'Add New', 'Amenity' ),
		'new_item'              => __( 'New Amentity', 'Amenity' ),
		'edit_item'             => __( 'Edit Item', 'Amenity' ),
		'update_item'           => __( 'Update Amentity', 'Amenity' ),
		'view_item'             => __( 'View Amentity', 'Amenity' ),
		'search_items'          => __( 'Search Item', 'Amenity' ),
		'not_found'             => __( 'Not found', 'Amenity' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'Amenity' ),
		'featured_image'        => __( 'Featured Image', 'Amenity' ),
		'set_featured_image'    => __( 'Set featured image', 'Amenity' ),
		'remove_featured_image' => __( 'Remove featured image', 'Amenity' ),
		'use_featured_image'    => __( 'Use as featured image', 'Amenity' ),
		'insert_into_item'      => __( 'Insert into item', 'Amenity' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'Amenity' ),
		'items_list'            => __( 'Items list', 'Amenity' ),
		'items_list_navigation' => __( 'Items list navigation', 'Amenity' ),
		'filter_items_list'     => __( 'Filter items list', 'Amenity' ),
		);
	$args = array(
		'label'                 => __( 'Amenity', 'Amenity' ),
		'description'           => __( 'Area Amenities', 'Amenity' ),
		'labels'                => $labels,
		'supports'              => array( 'title','featured_image', 'custom_fields' ),
		'taxonomies'            => array( 'area_title' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,   
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		);
	register_post_type( 'amenities', $args );

	}
	add_action( 'init', 'amenities', 0 );

	// Register Custom Taxonomy
	function amenity_category() {

	$labels = array(
		'name'                       => _x( 'Amenity Categories', 'Taxonomy General Name', 'Amenity' ),
		'singular_name'              => _x( 'Amenity Category', 'Taxonomy Singular Name', 'Amenity' ),
		'menu_name'                  => __( 'Amenity Category', 'Amenity' ),
		'all_items'                  => __( 'All Amenity Categories', 'Amenity' ),
		'parent_item'                => __( 'Parent Amenity Category', 'Amenity' ),
		'parent_item_colon'          => __( 'Parent Amenity Category:', 'Amenity' ),
		'new_item_name'              => __( 'New Amenity Category Name', 'Amenity' ),
		'add_new_item'               => __( 'Add New Amenity Category', 'Amenity' ),
		'edit_item'                  => __( 'Edit Amenity Category', 'Amenity' ),
		'update_item'                => __( 'Update Amenity Category', 'Amenity' ),
		'view_item'                  => __( 'View Amenity Category', 'Amenity' ),
		'separate_items_with_commas' => __( 'Separate menity Categories with commas', 'Amenity' ),
		'add_or_remove_items'        => __( 'Add or remove menity Categories', 'Amenity' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'Amenity' ),
		'popular_items'              => __( 'Popular Items', 'Amenity' ),
		'search_items'               => __( 'Search Items', 'Amenity' ),
		'not_found'                  => __( 'Not Found', 'Amenity' ),
		'no_terms'                   => __( 'No items', 'Amenity' ),
		'items_list'                 => __( 'Items list', 'Amenity' ),
		'items_list_navigation'      => __( 'Items list navigation', 'Amenity' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'amenity_category', array( 'amenities' ), $args );

	}
	add_action( 'init', 'amenity_category', 0 );
	

?>