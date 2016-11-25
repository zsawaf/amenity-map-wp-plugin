var $ = jQuery;

console.log(AMENITIES);
var doAreaAmenities = {

	cat : 'all',
	searchType : null,
	termsContainer : null,
	loading : null,
	initBounds: null,
	mapMarkers : [],
	infoWindows : [],
	style: [],
	activeBox: null,
	activeCategory: null,

	init : function( args ) {
		
		var self = this;
		// replace map style with desired styles
		this.amenities = AMENITIES;

		this.termsContainer = args.termsContainer; // container of tax terms
		this.loading = args.loading; // selector for loading div
		this.primary_location = JSON.parse(this.amenities['primary_location']);
		this.categories = JSON.parse(this.amenities['categories']);
		this.infobox_options = JSON.parse(this.amenities['infobox_display']);
		this.map = null;
		this.position = null;
		this.static_card_appended = false;
		this.isDraggable = $(document).width() > 768 ? true : false;
		this.style = JSON.parse(JSON.parse(AMENITIES['map_styles']).map_style);
		this.infobox_style = JSON.parse(AMENITIES['map_styles']);
		this.clickEvents();

		setTimeout(function(){
			self.doResults();
		}, 3000);


		/* OVERRIDE FOR STATIC INFOBOX */
	},

	/**
	 * Instantiate all click events for the interface
	 */
	clickEvents : function() {
		var self = this;
		this.termsContainer.find('a').on('click', function(e){
			e.preventDefault();
			var same_link = false;

			self.searchType = 'tag';
			if (self.cat == $(this).data('term-slug')) {
				same_link = true;
			}
			self.cat = ( $(this).parent().hasClass('active') ) ? self.cat : $(this).data('term-slug');

			if (!same_link) {
				self.termsContainer.find('li').removeClass('active');
			}
			
			if( self.cat ) {
				$(this).parent().addClass('active');
			}	
			
			if (!same_link) {
				self.doResults();
			}
		});

	},

	doResults : function() {
		var self = this;
		this.mapMarkers = [];
		this.setFilteredResults();
		this.loopPostHtml();
		this.initialize( this.mapMarkers );
	},

	setFilteredResults : function() {
		var self = this;
		var amenities = this.amenities['data']['posts'];
		var amenities_category = $(".am_navigation ul .active").attr("data-term-slug");

		if('all' == this.cat) {
			filteredList = amenities;
		}
		else {
			var filteredList = [];
			// loop through all amenities
			for( i = 0; i <= amenities.length - 1; i ++ ) {

				if( amenities[i] ) {
					
					var amentyCategories = amenities[i]['amenity_category'][0];

					if (amenities[i].post_name == this.primary_location[3]) {
						filteredList.push(amenities[i]);
					}
					// if the selected category is part of the amenty, then add the amenity to the filtered list
					if( amentyCategories == this.cat ) {
						filteredList.push(amenities[i]);
					}

				}
			}
		}

		this.filteredList = filteredList;

	},

	loopPostHtml : function() {
		
		var self = this;
		self.infoWindows = [];

		var obj = this.filteredList;

		if( obj.length > 0 ) {

			for( i = 0; i < obj.length; i++ ) {
				var html = '';
				var content = '';

				if( obj[i] && typeof obj[i].amenity_latitude !== 'undefined' && typeof obj[i].amenity_longitude !== 'undefined' ) {

					if( obj[i].amenity_address.address != '' ) {
						self.mapMarkers.push( [obj[i].post_name, obj[i].amenity_latitude, obj[i].amenity_longitude, obj[i]['amenity_category'][0], obj[i].place_id ] );
					}	
					
					if( obj[i].amenity_address && obj[i].amenity_address != '' ) {

						content += '<div class = "info-content"><h3 style="line-height:1; margin: 0;font-size:1.2em;">' + obj[i].post_title + '</h3>';
						self.infoWindows.push( [ content ] );

					}

				}
			}
		}	

	},

	getNoResults : function() {

	},

	infobox: function(marker, markers, markers_array) {
		var self = this;
		var infobox = new InfoBox({
			content: document.getElementById("infobox"),
			disableAutoPan: false,
			maxWidth: 150,
			pixelOffset: new google.maps.Size(0, -45),
			zIndex: null,
			boxStyle: {
				opacity: 1,
				width: "auto",
				backgroundColor: self.infobox_style.background_color,
				color: self.infobox_style.color,
				padding: "7px 15px 7px 11px",
				borderRadius : "0",
                fontSize: '12px',
				border: '1px solid #f9f2d8'
			},
			closeBoxMargin: "-5px -12px -5px 5px",
			closeBoxURL: self.infobox_style.close_icon,
			infoBoxClearance: new google.maps.Size(1, 1),
		});

		// Allow each marker to have an info window    
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				if( self.infoWindows[i] ) {
					// if marker is not primary location
					if (markers[i][0] != self.primary_location[3]) {
						// get place id
		                self.activeCategory = markers[i][3];
						self.activeBox = i;
						for (var j=0; j<markers_array.length; j++) {
							self.set_icons(markers[j], marker, self.categories);
						}
						// hover state icon
						markers_array[i].setIcon(AMENITIES['active_icon']);
						self.get_marker_html(markers[i], self.map, infobox, self.position, marker);
					}
				}

			}
		})(marker, i));

		google.maps.event.addListener(infobox, 'closeclick', function(){
		    self.set_icons([0, 0, 0, self.activeCategory], markers_array[self.activeBox], self.categories);
		});
	},

	static_card: function(marker, markers, markers_array) {
		// create a static card
		var self = this;
		if (!this.static_card_appended) {
			jQuery("#am_map").addClass('am_relative');
			var card = '<div id="am_card"></div>';
			jQuery("#am_map").append(card);

			this.static_card_appended = true;

			self.get_marker_html(markers[0], self.map, null, self.position, marker, true);
		}

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				if( self.infoWindows[i] ) {
	                self.activeCategory = markers[i][3];
					self.activeBox = i;
					for (var j=0; j<markers_array.length; j++) {
						self.set_icons(markers[j], marker, self.categories);
					}
					// hover state icon
					markers_array[i].setIcon(AMENITIES['active_icon']);
					self.get_marker_html(markers[i], self.map, null, self.position, marker, true);
				}
			}
		})(marker, i));
	},
	initialize : function( markers ) {
		var self = this;
		var bounds = new google.maps.LatLngBounds();
		console.log(this.isDraggable);
		var mapOptions = {
			draggable: this.isDraggable,
			styles: this.style,
			mapTypeId: 'roadmap',
			scrollwheel: false,
			center:new google.maps.LatLng(43.8156,-79.3108)
		};

		this.map = new google.maps.Map(document.getElementById('am_map'), mapOptions);

		var success = 0;
		var markers_array = [];

		if(  markers.length ) {
			for( i = 0; i < markers.length; i++ ) {
				if( typeof markers[i] === 'undefined' || typeof markers[i][1] === 'undefined' || typeof markers[i][2] === 'undefined' )
					return false;
				else 
					success++;
				
				// for now use primary marker
				var markerIcon = AMENITIES['primary_location_icon'];
				this.position = new google.maps.LatLng(markers[i][1], markers[i][2]);
				bounds.extend(this.position);

				marker = new google.maps.Marker({
					position: this.position,
					map: this.map,
					zoom : 5,
					title: markers[i][0]
				});

                self.set_icons(markers[i], marker, this.categories);

				markers_array.push(marker);

				// we can set info box styles from back end here
				if (this.infobox_options[3] == "1") {
					this.static_card(marker, markers, markers_array);
				}
				else {
					this.infobox(marker, markers, markers_array);
				}
				// Automatically center the map fitting all markers on the screen
			}

			if( success > 0 ) {
				this.map.fitBounds(bounds);
				google.maps.event.addDomListener(window, 'load', self.initialize);
			}
		}
	},

	/* HELPER METHODS */
	primary_location_index: function(markers) {
		jQuery.each(markers, function(k, v){
			console.log(v);
		});
	},
	get_marker_html: function(o_place, map, infobox, position, marker, flag) {
		var self = this;
		if (typeof flag === 'undefined') { flag = false; }
		var place_id = o_place[4];
		var service = new google.maps.places.PlacesService(self.map);
		var html;
		service.getDetails({
		  placeId: place_id
		}, function(place, status) {
			if (status === google.maps.places.PlacesServiceStatus.OK) {
				html = self.write_marker_html(place);
				if (!flag) {
					infobox.setContent(html);
					infobox.open(self.map, marker);
					map.panTo(position);
				}
				else {
					// populate card
					jQuery("#am_card").html(html);
				}
			}
		});
	},
	write_marker_html: function(details) {
		var html = '';
		html += '<h3 class="am_title">'+details.name+'</h3>';
		html+= (this.infobox_options[0] == 1) ? '<p class="address">'+details.formatted_address+'</p>' : '';
		html += '<div class="am_contact_info">';
		html+= (this.infobox_options[1] == 1) ? '<p>'+details.formatted_phone_number+'</p>' : '';
		html+= (this.infobox_options[2] == 1) ? '<p>'+details.website+'</p>' : '';
		html += '</div>';
		return html;
	},
	set_icons: function(markers, marker, categories) {
		if (marker.title == this.primary_location[3]) {
			marker.setIcon(AMENITIES['primary_location_icon']);
			return false;
		}
		for ( var i = 0; i < categories.length ; i ++ ) {
			var category = categories[i];
			if (category[1] == markers[3]) {
				marker.setIcon(category[2]);
				return false;
			}
		}
	}
}

	




