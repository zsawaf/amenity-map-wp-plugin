<?php  
	/**
	 * Register meta boxes.
	 */

	// address picker
	function register_address_meta_box() {
	    add_meta_box( 'am_meta_address', __( 'Amenity Address', 'textdomain' ), 'address_picker_callback', 'amenities' );
	}
	add_action( 'add_meta_boxes', 'register_address_meta_box' );
	 
	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	function address_picker_callback( $post ) {

	    wp_nonce_field(basename(__FILE__), "meta-box-nonce"); 

	    ?>
	    
		<input id="pac-input" class="controls" type="text" placeholder="Search for an amenity address">
		<input type="hidden" id="am_lat" name="am_lat" value="<?php echo ( get_post_meta($post->ID, 'latitude' ) ) ? get_post_meta($post->ID, 'latitude', true )  : '' ?>">
		<input type="hidden" id="am_lon" name="am_lon" value="<?php echo ( get_post_meta($post->ID, 'longitude' ) ) ? get_post_meta($post->ID, 'longitude', true )  : '' ?>">
		<input type="hidden" id="am_place_id" name="am_place_id" value="<?php echo ( get_post_meta($post->ID, 'place_id' ) ) ? get_post_meta($post->ID, 'place_id',  true )  : '' ?>">
		<input type="hidden" id="am_address" name="am_address" value="<?php echo ( get_post_meta($post->ID, 'address' ) ) ? get_post_meta($post->ID, 'address',  true )  : '' ?>">

		<div id="am_admin_map"></div>
		<?php
	}
	 
	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	function amenities_save_post( $post_id ) {
	    // Save logic goes here. Don't forget to include nonce checks!
		if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        	return $post_id;

        $address = "";
        $lat = "";
        $lon = "";
        $place_id = "";

        if (isset($_POST['am_address'])) {
        	$address = $_POST['am_address'];
        }
        update_post_meta($post_id, "address", $address);

        if (isset($_POST['am_lon'])) {
        	$lon = $_POST['am_lon'];
        }

        if (isset($_POST['am_place_id'])) {
        	$place_id = $_POST['am_place_id'];
        }
        update_post_meta($post_id, 'place_id', $place_id);

        update_post_meta($post_id, "longitude", $lon);

        if (isset($_POST['am_lat'])) {
        	$lat = $_POST['am_lat'];
        }
        update_post_meta($post_id, "latitude", $lat);

	}
	add_action( 'save_post', 'amenities_save_post' );


	/* CATEGORY SECTION */
	
	// register meta
	add_action('init', 'am_register_meta');
	function am_register_meta() {
		register_meta('term', 'color', 'am_sanitize_hex');
		register_meta('term', 'icon', 'am_sanitize_url');
	}

	// sanitize hex colors 
	function am_sanitize_hex($color) {
		// $color = ltrim( $color, '#' );
  		// return preg_match( '/([A-Fa-f0-9]{3}){1,2}$/', $color ) ? $color : '';
		return $color;
	}

	function am_sanitize_url($url) {
		return $url;
	}

	function am_get_term_color($term_id) {
		$color = get_term_meta($term_id, 'color', true);
		return $color;
	}

	function am_get_term_icon($term_id) {
		$icon = get_term_meta($term_id, 'icon', true);
		return $icon;
	}

	add_action('amenity_category_add_form_fields', 'am_new_term_color_field', 10, 2);
	function am_new_term_color_field() {
		wp_nonce_field( basename( __FILE__ ), 'am_term_color_nonce' );
		?>
		<div class="form-field">
			<label for="am_term_color">Color</label>
			<input type="text" name="am_term_color" id="am_term_color">
		</div>
		<?php
	}

	add_action('amenity_category_add_form_fields', 'am_new_term_icon_field', 10, 2);
	function am_new_term_icon_field() {
		wp_nonce_field( basename( __FILE__ ), 'am_term_icon_nonce' );
		?>
		<div class="form-field">
			<label for="am_term_icon">Icon</label>
			<input type="text" name="am_term_icon" id="am_term_icon">
		</div>
		<?php
	}

	add_action('amenity_category_edit_form_fields', 'am_edit_term_color_field');
	function am_edit_term_color_field($term) {
		$default = '#ffffff';
		$color   = am_get_term_color( $term->term_id );

		if ( ! $color )
			$color = $default; 
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="am_term_color">Color</label>
				<td>
					<?php wp_nonce_field( basename( __FILE__ ), 'am_term_color_nonce' ); ?>
					<input type="text" name="am_term_color" id="am_term_color" value="<?php echo esc_attr($color); ?>">
				</td>
			</th>
		</tr>
	<?php	
	}

	add_action('amenity_category_edit_form_fields', 'am_edit_term_icon_field', 10, 2);
	function am_edit_term_icon_field($term) {
		$default = '';
		$icon   = am_get_term_icon( $term->term_id );

		if ( ! $icon )
			$icon = $default; 
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="am_term_icon">Icon</label>
				<td>
					<?php wp_nonce_field( basename( __FILE__ ), 'am_term_icon_nonce' ); ?>
					<input type="text" name="am_term_icon" id="am_term_icon" value="<?php echo esc_attr($icon); ?>">
				</td>
			</th>
		</tr>
	<?php	
	}

	add_action( 'edit_amenity_category',   'am_save_terms', 10, 2 );
	add_action( 'create_amenity_category', 'am_save_terms', 10, 2 );

	function am_save_terms( $term_id ) {

		// save color
		if ( ! isset( $_POST['am_term_color_nonce'] ) || ! wp_verify_nonce( $_POST['am_term_color_nonce'], basename( __FILE__ ) ) )
        return;

    	if ( ! isset( $_POST['am_term_icon_nonce'] ) || ! wp_verify_nonce( $_POST['am_term_icon_nonce'], basename( __FILE__ ) ) )
        return;

	    $old_color = am_get_term_color( $term_id );
	    $new_color = isset( $_POST['am_term_color'] ) ? am_sanitize_hex( $_POST['am_term_color'] ) : '';

	    if ( $old_color && '' === $new_color )
	        delete_term_meta( $term_id, 'color' );

	    else if ( $old_color !== $new_color )
	        update_term_meta( $term_id, 'color', $new_color );

	    // save icon
	    $old_icon = am_get_term_icon( $term_id );
	    $new_icon = isset( $_POST['am_term_icon'] ) ? am_sanitize_url( $_POST['am_term_icon'] ) : '';

	    if ( $old_icon && '' === $new_icon )
	        delete_term_meta( $term_id, 'icon' );

	    else if ( $old_icon !== $new_icon )
	        update_term_meta( $term_id, 'icon', $new_icon );


	}
