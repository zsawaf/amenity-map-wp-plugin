<div class="amenities">
	<div class="amenities-navigation">
		<?php display_amenity_category_menu() ?>
	</div>
	<div id="amenities-map" class="amenities-map google-maps-outer">
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
		termsContainer : $(".amenities-list"),
		loading: $(".loading")
	});
});
</script>