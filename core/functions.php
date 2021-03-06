<?php

/**
 * Get all amenities 
 * @return array() ['posts'] array of all amenities ['found_posts'] count of found posts
 */
function get_post_amenities(){

	$args = array(
		'post_type' => 'amenities',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	);

	$results = new WP_Query( $args );
	$return_array = array();
	$return_array['posts'] = array();
	$primary = array();
	$primary_details;

	if( $results->found_posts  > 0 ) {
		
		$return_array['found_posts'] = $results->found_posts;

		foreach( $results->posts as $post ) {
			$return_array['posts'][] = get_amenity( $post );
		}

	}
	else {
		$return_array['posts'] = array('no dice');
	}
	
	$return_array['posts'] = array_reverse($return_array['posts']); // primary location first item in array.
	
	return $return_array; 

}

/**
 * Get single amenity
 * @param  [type] $amenity Amenity - Post object or Post ID
 * @return object - Amentiy category - object or term ID
 */
function get_amenity( $amenity ) {

	/*if amenity is post ID*/
	if( ctype_digit($amenity) ) {
		$post = get_post($amenity);
	}
	
	/*else if is post object*/
	else {
		$post = $amenity;
	}

	$post->amenity_address = get_post_meta($post->ID, 'address', true);
	$post->amenity_latitude = get_post_meta($post->ID, 'latitude', true);
	$post->amenity_longitude = get_post_meta($post->ID, 'longitude', true);
	$post->place_id = get_post_meta($post->ID, 'place_id', true);
	$post->amenity_category = set_post_tax_array(wp_get_post_terms( $post->ID, 'amenity_category' ));

	return $post;

}

function get_amenities_by_categories() {
	
	$categories = get_amenity_categories();
	$return = array();

	foreach( $categories as $key => $category ) {
		$return[$key]['category'] = $category;
		$return[$key]['amenities'] = get_category_amenities($category);
	}

	return $return;

}


function get_sm_options() {
	
	$args = array(
		'post_type' => 'single_maps',
		'posts_per_page' => -1
	);

	$results = new WP_Query( $args );
	$array = array();

	foreach($results->posts as $post) {
		$single_map_options = array();
		$single_map_options["latitude"] = get_post_meta($post->ID, 'latitude', true);
		$single_map_options["longitude"] = get_post_meta($post->ID, 'longitude', true);
		$single_map_options["map_style"] = get_post_meta($post->ID, 'sm_map_styles', true);
		$single_map_options["icon"] = get_post_meta($post->ID, 'sm_map_icon', true);

		$array[$post->ID] = $single_map_options;
		$single_map_options = null;

	}

	return json_encode($array);

	wp_reset_postdata();

}

function get_amenity_categories() {

	$args = array(
		'hide_empty' => false,
		'orderby' => 'name',
		'order' => 'ASC',
		'posts_per_page' => -1
	);
	
	$categories = get_terms('amenity_category', $args);
	$return_array = array();

	foreach($categories as $category) {

		$category->term_icon = am_get_term_icon( $category->term_id );
		$category->term_color = am_get_term_icon( $category->term_id );
	
		$return_array[] = $category;

	}

	return $return_array;

}

function get_category_amenities( $amenity_category = null ) {
	
	/*else if is term object*/
	if( is_object($amenity_category) ) {
		$term_id = $amenity_category->term_id;
	}

	/*if amenity is term_id*/
	else {
		$term_id = $amenity_category;
	}

	$args = array(
		'post_type' => 'amenities',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'amenity_category',
				'field' => 'term_id',
				'terms' => $term_id
			)
		)
	);

	$posts = get_posts($args);

	foreach($posts as $post):
		$post->place_id = get_field('place_id', $post->ID);
	endforeach;


	return $posts;

}

function get_map_styles() {

	$return_array = array();
	$return_array['background_color'] = get_option('am_infobox_background');
	$return_array['color'] = get_option('am_infobox_color');
	$return_array['close_icon'] = get_option('am_infobox_close');
	$return_array['map_style'] = get_option('am_fancy_maps_settings');

	return json_encode($return_array);

}

function get_infobox_display_options() {

	$return_array = array();

	$return_array[] = get_option('am_infobox_address');
	$return_array[] = get_option('am_infobox_phone');
	$return_array[] = get_option('am_infobox_website');
	$return_array[] = get_option('am_infobox_card');

	return json_encode($return_array);

}

/*
*	Get the primary location
*/
function get_primary_location() {

	$primary_location_id = get_option('am_primary_location');
	$latitude = get_post_meta($primary_location_id)['latitude'][0];
	$longitude = get_post_meta($primary_location_id)['longitude'][0];
	$address = get_post_meta($primary_location_id)['address'][0];
	$place_id = get_post_meta($primary_location_id)['place_id'][0];

	$return_array = array();
	$return_array[] = $address;
	$return_array[] = $latitude;
	$return_array[] = $longitude;
	$return_array[] = get_post($primary_location_id)->post_name;
	$return_array[] = $place_id;
	
	return json_encode($return_array);

}

function set_post_tax_array($terms) {

	$array = array();
	if( $terms ) {
		foreach ($terms as $key => $term) {
			$array[] = $term->slug;
		}
	}
	else {
		$array = false;
	}
	return $array;

}

function display_amenity_category_menu() { ?>

	<?php $terms = get_terms('amenity_category', array('hide_empty' => false)) ?>
	<ul class="amenities-list clearfix">
		<li class="term-all active"><a href="#" data-term-slug="all"><span>All</span></a></li>
	<?php foreach ($terms as $key => $term): ?>
		<li class="side-nav-item term-<?php echo $term->term_id; ?> <?php echo $term->slug ?>"><a href="#" data-term-slug="<?php echo $term->slug; ?>"><span><?php echo $term->name; ?></span></a></li>
	<?php endforeach ?>
	</ul>

<?php }



function display_amentiy_accordian() { ?>

	<?php $terms = get_terms('amenity_category', array('hide_empty' => false)) ?>
	<ul class="amenities-list clearfix amenity-accordian">
	<?php foreach ($terms as $key => $term): ?>
		<li class="side-nav-item term-<?php echo $term->term_id; ?> <?php echo $term->slug ?>" id="amenity-category-<?php echo $term->term_id; ?>">
			<a href="#" data-term-slug="<?php echo $term->slug; ?>"><span><?php echo $term->name; ?></span><i></i></a>
			<ul class="sub-list"></ul>
		</li>
	<?php endforeach ?>
	</ul>

<?php }









