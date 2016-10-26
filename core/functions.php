<?php  
	function get_post_amenities(){

		global $post;

		$args = array(
			'post_type' => 'amenities',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1
		);

		$results = new WP_Query( $args );
		$array = array();

		if( $results->found_posts  > 0 ) {
			
			$array['found_posts'] = $results->found_posts;

			foreach( $results->posts as $post ) {

				$post->amenity_address = get_field('address', $post->ID);
				$post->amenity_latitude = get_field('latitude', $post->ID);
				$post->amenity_longitude = get_field('longitude', $post->ID);
				$post->place_id = get_field('place_id', $post->ID);
				$post->amenity_category = set_post_tax_array(wp_get_post_terms( $post->ID, 'amenity_category' ));
				$array['posts'][] = $post;

			}

		}
		else {
			$array['posts'] = array('no dice');
		}
		return $array;

	}

	function get_amenity_categories() {
		global $post;
		$args = array(
			'post_type' => 'amenities',
			'orderby' => 'name',
  			'order' => 'ASC',
  			'posts_per_page' => -1
		);
		$categories = get_terms('amenity_category', $args);
		$return_array = array();
		foreach($categories as $category) {
			$cat_array = array();
			$cat_array[]= $category->term_id;
			$cat_array[]= $category->slug;

			$cat_array[]= am_get_term_icon( $category->term_id );
			$cat_array[]= am_get_term_color( $category->term_id );
			$return_array[] = $cat_array;
		}
		return json_encode($return_array);
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
		$return_array[]= get_option('am_infobox_address');
		$return_array[]= get_option('am_infobox_phone');
		$return_array[]= get_option('am_infobox_website');

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
		$return_array[]= $address;
		$return_array[]= $latitude;
		$return_array[]= $longitude;
		$return_array[]= get_post($primary_location_id)->post_name;
		$return_array[]= $place_id;
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

	/**
	* Simple helper to debug to the console
	*
	* @param $data object, array, string $data
	* @param $context string  Optional a description.
	*
	* @return string
	*/
	function debug_to_console( $data, $context = 'Debug in Console' ) {

		// Buffering to solve problems frameworks, like header() in this and not a solid return.
		ob_start();

		$output  = 'console.info( \'' . $context . ':\' );';
		$output .= 'console.log(' . json_encode( $data ) . ');';
		$output  = sprintf( '<script>%s</script>', $output );

		echo $output;
	}
?>