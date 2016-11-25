<?php 
/**
 * Create Amenities CPT
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

/**
 * Create Amnities Category Taxonomy
 * @return [type] [description]
 */
function amenity_category() {

	$labels = array(
		'name'                       => _x( 'Amenity Categories', 'Taxonomy General Name', 'Amenity' ),
		'singular_name'              => _x( 'Amenity Category', 'Taxonomy Singular Name', 'Amenity' ),
		'menu_name'                  => __( 'Amenity Categories', 'Amenity' ),
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

function single_map() {
	$labels = array(
		'name'                  => _x( 'Single Map', 'Post Type General Name', 'Amenity' ),
		'singular_name'         => _x( 'Single Maps', 'Post Type Singular Name', 'Amenity' ),
		'menu_name'             => __( 'Single Maps', 'Amenity' ),
		'name_admin_bar'        => __( 'Single Map', 'Amenity' ),
		'archives'              => __( 'Single Maps', 'Amenity' ),
		'parent_item_colon'     => __( 'Parent Amentity', 'Amenity' ),
		'all_items'             => __( 'All Single Maps', 'Amenity' ),
		'add_new_item'          => __( 'Add New Single Map', 'Amenity' ),
		'add_new'               => __( 'Add New', 'Amenity' ),
		'new_item'              => __( 'New Single Map', 'Amenity' ),
		'edit_item'             => __( 'Edit Item', 'Amenity' ),
		'update_item'           => __( 'Update Single Map', 'Amenity' ),
		'view_item'             => __( 'View Single Map', 'Amenity' ),
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
		'label'                 => __( 'Single Map', 'Amenity' ),
		'description'           => __( 'Single Maps', 'Amenity' ),
		'labels'                => $labels,
		'supports'              => array( 'title','featured_image', 'custom_fields' ),
		'taxonomies'            => array( 'area_title' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,   
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		);
	register_post_type( 'single_maps', $args );
}
add_action( 'init', 'single_map', 0 );

add_action( 'admin_menu', 'single_map_submenu' );
function single_map_submenu() {
	add_submenu_page(
		'edit.php?post_type=amenities',
		'Single Maps',
		'Single Maps',
		'edit_posts',
		'edit.php?post_type=single_maps'
	);
}




