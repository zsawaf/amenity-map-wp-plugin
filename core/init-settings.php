<?php  
	function amenities_settings() {

		add_submenu_page(
			'edit.php?post_type=amenities', 'Settings', 'Settings', 'read_private_posts', 'amenities_settings', 'amenities_settings_page'
		);

	}
	add_action( 'admin_menu', 'amenities_settings' );

	function amenities_settings_page() {
		?>
			<div class="wrap">
			<h1>Settings Page</h1>
			<form method="post" action="options.php">
			    <?php
			        settings_fields("amenities_settings");
			        do_settings_sections("theme-options"); 
			        do_settings_sections("map-options");   
			        do_settings_sections("infobox-options");  
			        submit_button(); 
			    ?>          
			</form>
			</div>
		<?php
	}

	function display_primary_location() {

		$post_type = 'amenities';

		// Get all the taxonomies for this post type
		$query = query_posts(array('post_type' => $post_type) );
		?>
    	<select name="am_primary_location" id="am_primary_location">
    		<?php
    			if ( have_posts() ) : while ( have_posts() ) : the_post();
    				$selected = (get_the_id() == get_option('am_primary_location') ? 'selected' : '');
    				?>
					<option <?php echo ($selected != '' ? 'selected="selected"' : '') ?> 
					value="<?php echo get_the_id() ?>"><?php echo the_title() ?></option>
    				<?php
				endwhile;
    			endif;
    		?>
    	</select>
    <?php
	}

	function display_am_setting() {
		?>
		<select name="am_display_setting" id="am_display_setting" class="am_display_setting">
			<option 
				<?php echo (get_option('am_display_setting') == 'am_sidebar' ? 'selected="selected"' : '') ?>
				value="am_sidebar">Sidebar Amenity Menu</option>
			<option 
				<?php echo (get_option('am_display_setting') == 'am_topbar' ? 'selected="selected"' : '') ?>
				value="am_topbar">Topbar Amenity Menu</option>
		</select>
		<?php
	}
	function display_primary_location_icon() {
		?>
    	<input type="text" name="am_primary_location_icon" id="am_primary_location_icon" value="<?php echo get_option('am_primary_location_icon'); ?>" />
    <?php
	}

	function display_active_icon() {
		?>
    	<input type="text" name="am_active_icon" id="am_active_icon" value="<?php echo get_option('am_active_icon'); ?>" />
    <?php
	}

	function display_google_maps_api_key() {
		?>
    	<input type="text" name="am_gm_api_key" id="am_gm_api_key" value="<?php echo get_option('am_gm_api_key'); ?>" />
    <?php
	}

	function display_fancy_maps() {
		?>
		<textarea name="am_fancy_maps_settings" id="am_fancy_maps_settings"><?php echo get_option('am_fancy_maps_settings'); ?></textarea>
		<?php
	}

	function display_infobox_bg() {
		?>
		<input type="text" name="am_infobox_background" id="am_infobox_background" value="<?php echo get_option('am_infobox_background'); ?>" />
		<?php
	}

	function display_infobox_color() {
		?>
		<input type="text" name="am_infobox_color" id="am_infobox_color" value="<?php echo get_option('am_infobox_color'); ?>" />
		<?php
	}

	function display_infobox_close() {
		?>
		<input type="text" name="am_infobox_close" id="am_infobox_close" value="<?php echo get_option('am_infobox_close'); ?>" />
		<?php
	}

	function display_info_address() {
		?>
		<input type="checkbox" name="am_infobox_address" value="1" <?php echo (get_option('am_infobox_address') == '1' ? 'checked' : '')?>>
		<?php
	}

	function display_info_phone() {
		?>
		<input type="checkbox" name="am_infobox_phone" value="1" <?php echo (get_option('am_infobox_phone') == '1' ? 'checked' : '')?>>
		<?php
	}

	function display_info_website() {
		?>
		<input type="checkbox" name="am_infobox_website" value="1" <?php echo (get_option('am_infobox_website') == '1' ? 'checked' : '')?>>
		<?php
	}

	function display_settings() {
		add_settings_section("amenities_settings", "Amenities Settings", null, "theme-options");

		add_settings_field("am_display_setting", "AM Display Orientation", "display_am_setting", "theme-options", "amenities_settings");

		add_settings_field("am_primary_location", "Primary Location", "display_primary_location", "theme-options", "amenities_settings");

		add_settings_field("am_primary_location_icon", "Primary Location Icon", "display_primary_location_icon", "theme-options", "amenities_settings");

		add_settings_field("am_active_icon", "Active State Icon", "display_active_icon", "theme-options", "amenities_settings");

		add_settings_field("am_gm_api_key", "Google API Key", "display_google_maps_api_key", "theme-options", "amenities_settings");


		register_setting("amenities_settings", "am_primary_location");
		register_setting("amenities_settings", "am_primary_location_icon");
		register_setting("amenities_settings", "am_active_icon");
		register_setting("amenities_settings", "am_gm_api_key");
		register_setting("amenities_settings", "am_display_setting");
	}

	function map_settings() {
		add_settings_section("amenities_settings", "Map Settings", null, "map-options");

		add_settings_field("am_fancy_maps_settings", "Map Styles", "display_fancy_maps", "map-options", "amenities_settings");

		register_setting("amenities_settings", "am_fancy_maps_settings");
	}

	function infobox_settings() {
		add_settings_section("amenities_settings", "Infobox Settings", null, "infobox-options");

		// infobox styles
		add_settings_field("am_infobox_background", "Infobox Background", "display_infobox_bg", "infobox-options", "amenities_settings");
		add_settings_field("am_infobox_color", "Infobox Font Color", "display_infobox_color", "infobox-options", "amenities_settings");
		add_settings_field("am_infobox_close", "Infobox Close Icon", "display_infobox_close", "infobox-options", "amenities_settings");

		// infobox content
		add_settings_field("am_infobox_address", "Display Address", "display_info_address", "infobox-options", "amenities_settings");
		add_settings_field("am_infobox_phone", "Display Phone", "display_info_phone", "infobox-options", "amenities_settings");
		add_settings_field("am_infobox_website", "Display Website", "display_info_website", "infobox-options", "amenities_settings");

		register_setting("amenities_settings", "am_infobox_address");
		register_setting("amenities_settings", "am_infobox_phone");
		register_setting("amenities_settings", "am_infobox_website");
		register_setting("amenities_settings", "am_infobox_background");
		register_setting("amenities_settings", "am_infobox_color");
		register_setting("amenities_settings", "am_infobox_close");
	}

	add_action("admin_init", "display_settings");
	add_action("admin_init", "map_settings");
	add_action("admin_init", "infobox_settings");
