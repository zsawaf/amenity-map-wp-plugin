<?php
/**
 * Shortcode to display amenity map of all amenities
 */
function do_amentity_map() {

	enqueue_am_scripts();

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


/**
 * Shortcode to display a single map amenity
 */
function do_map($atts) {

	enqueue_am_scripts();

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


function do_amenity_accordian() {

	enqueue_am_scripts();

	ob_start(); ?>
	
	<?php display_amentiy_accordian(); ?>

	<script>
		jQuery(document).ready(function(){
			cat_accordian.init({
				accordianList: $(".amenity-accordian")
			});
		});
	</script>

	<?php
	return ob_get_clean();	
}
add_shortcode('amenity_accordian', 'do_amenity_accordian');









