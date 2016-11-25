class Map {
	constructor(id) {
		this.id = id;
		// filter all options and get the selected one.
		var all_options = JSON.parse(OPTIONS['data']);
		var options = this.filterMaps(all_options);
		 console.log(all_options);
		 console.log(options);
		// init all variables
		this.coords = {
			lat: parseFloat(options.latitude), 
			lng: parseFloat(options.longitude)
		};
		this.map_styles = JSON.parse(options['map_style']);
		this.icon = options.icon;
		this.marker = null;
		this.map = null;
		this.isDraggable = $(document).width() > 768 ? true : false;
		this.createMap();
		this.createPin();
	}

	// create the map
	createMap() {
		this.map = new google.maps.Map(document.getElementById('am_single_map'), {
			zoom: 15,
			center: this.coords,
			styles: this.map_styles,
			draggable: this.isDraggable,
			scrollwheel: false,
		});
	}

	createPin() {
		// create marker
		this.marker = new google.maps.Marker({
			position: this.coords,
			map: this.map
		})

		// set icon
		this.marker.setIcon(this.icon);
	}

	filterMaps(all_options) {
		var ref_id = this.id;
		var selected_option = null;
		jQuery.each(all_options, function(k, v){
			if (k == ref_id) {
				selected_option = v;
				return false;
			}
		});
		return selected_option;
	}
}