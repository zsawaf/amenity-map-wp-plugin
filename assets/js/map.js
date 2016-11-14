var $ = jQuery;
var primary_location;

//var map_style = [{"featureType":"all","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"all","elementType":"labels","stylers":[{"visibility":"off"},{"saturation":"-100"}]},{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40},{"visibility":"off"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"off"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#4d6059"}]},{"featureType":"landscape","elementType":"geometry.stroke","stylers":[{"color":"#4d6059"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#4d6059"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"lightness":21}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#4d6059"}]},{"featureType":"poi","elementType":"geometry.stroke","stylers":[{"color":"#4d6059"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#7f8d89"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#7f8d89"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#7f8d89"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#7f8d89"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#7f8d89"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#7f8d89"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#7f8d89"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#7f8d89"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#2b3638"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#2b3638"},{"lightness":17}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#24282b"}]},{"featureType":"water","elementType":"geometry.stroke","stylers":[{"color":"#24282b"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.icon","stylers":[{"visibility":"off"}]}];
var map_style = JSON.parse(JSON.parse(AMENITIES['map_styles']).map_style);
var infobox_options;
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
		// replace map style with desired styles
		this.amenities = AMENITIES;
		this.map_styles = JSON.parse(this.amenities['map_styles']);
		this.termsContainer = args.termsContainer; // container of tax terms
		this.loading = args.loading; // selector for loading div
		primary_location = JSON.parse(this.amenities['primary_location']);
		this.categories = JSON.parse(this.amenities['categories']);
		infobox_options = JSON.parse(this.amenities['infobox_display']);

		console.log(this.map_styles);
		this.style = map_style;
		var self = this;
		this.clickEvents();

		setTimeout(function(){
			self.doResults();
		}, 3000);

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
		this.mapMarkers = [];
		this.setFilteredResults();
		this.loopPostHtml();
		this.initialize( this.mapMarkers );
	},

	setFilteredResults : function() {
		var amenities = this.amenities['data']['posts'];
		var amenities_category = $(".am_navigation ul .active").attr("data-term-slug");

		if('all' == this.cat) {
			filteredList = amenities;
		}
		else {
			var filteredList = [];
			// loop through all amenities
			for( i = 0; i <= amenities.length - 1; i ++ ) {
				var amentyCategories = amenities[i]['amenity_category'][0];
				if (amenities[i].post_name == primary_location[3]) {
					filteredList.push(amenities[i]);
				}
				// if the selected category is part of the amenty, then add the amenity to the filtered list
				if( amentyCategories == this.cat ) {
					filteredList.push(amenities[i]);
				}
			}
		}

		this.filteredList = filteredList;
	},

	loopPostHtml : function( obj ) {

		var self = this;
		self.infoWindows = [];

		var obj = this.filteredList;
		if( obj.length < 1 ) {
		}
		else {

			for( i = 0; i < obj.length; i++ ) {
				var html = '';
				var content = '';
				// if(0){
				if( typeof obj[i].amenity_latitude !== 'undefined' && typeof obj[i].amenity_longitude !== 'undefined' ) {

					if( obj[i].amenity_address.address != '' ) {
						self.mapMarkers.push( [obj[i].post_name, obj[i].amenity_latitude, obj[i].amenity_longitude, obj[i]['amenity_category'][0], obj[i].place_id ] );
					}	
						// infoWindowContent.push( [ <?=json_encode( $content )?> ] );
					
					if( obj[i].amenity_address && obj[i].amenity_address != '' ) {

						content += '<div class = "info-content"><h3 style="line-height:1; margin: 0;font-size:1.2em;">' + obj[i].post_title + '</h3>';
						//content += '<p>' + obj[i].amenity_address.address + '</p></div>';

						self.infoWindows.push( [ content ] );

					}

				}
			}
		}	

	},

	getNoResults : function() {

	},

	initialize : function( markers ) {
		var self = this;
		var bounds = new google.maps.LatLngBounds();
		var mapOptions = {
			styles: this.style,
			mapTypeId: 'roadmap',
			scrollwheel: false,
			center:new google.maps.LatLng(43.8156,-79.3108)
		};

		var map = new google.maps.Map(document.getElementById('am_map'), mapOptions);

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
				var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
				var map_styles = this.map_styles;
				bounds.extend(position);
				marker = new google.maps.Marker({
					position: position,
					map: map,
					zoom : 5,
					title: markers[i][0]
				});

                set_icons(markers[i], marker, this.categories);

				markers_array.push(marker);

				// we can set info box styles from back end here
				var infobox = new InfoBox({
					content: document.getElementById("infobox"),
					disableAutoPan: false,
					maxWidth: 150,
					pixelOffset: new google.maps.Size(0, -45),
					zIndex: null,
					boxStyle: {
						opacity: 1,
						width: "auto",
						backgroundColor: map_styles.background_color,
						color: map_styles.color,
						padding: "7px 15px 7px 11px",
						borderRadius : "0",
                        fontSize: '12px',
						border: '1px solid #f9f2d8'
					},
					closeBoxMargin: "-5px -12px -5px 5px",
					closeBoxURL: map_styles.close_icon,
					infoBoxClearance: new google.maps.Size(1, 1),
				});

				// Allow each marker to have an info window    
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						if( self.infoWindows[i] ) {
							// if marker is not primary location
							if (markers[i][0] != primary_location[3]) {
								// get place id
                                self.activeCategory = markers[i][3];
								self.activeBox = i;
								for (var j=0; j<markers_array.length; j++) {
									set_icons(markers[j], marker, self.categories);
								}
								// hover state icon
								markers_array[i].setIcon(AMENITIES['active_icon']);
								get_marker_html(markers[i], map, infobox, position, marker);
							}
						}

					}
				})(marker, i));

				google.maps.event.addListener(infobox, 'closeclick', function(){
                    set_icons([0, 0, 0, self.activeCategory], markers_array[self.activeBox], self.categories);
				});

				
				// Automatically center the map fitting all markers on the screen
				
			}

			if( success > 0 ) {
				map.fitBounds(bounds);
				google.maps.event.addDomListener(window, 'load', self.initialize);
			}
		}

	}	
}

// get place id when infobox is clicked from lat long
function get_marker_html(o_place, map, infobox, position, marker) {
	var place_id = o_place[4];
	var service = new google.maps.places.PlacesService(map);
	var html;
	service.getDetails({
      placeId: place_id
    }, function(place, status) {
    	if (status === google.maps.places.PlacesServiceStatus.OK) {
    		html = write_marker_html(place);
    		infobox.setContent(html);
			infobox.open(map, marker);
			map.panTo(position);
    	}
    });
    return html;
}

function write_marker_html(details) {
	var html = '';

	html += '<h3>'+details.name+'</h3>';
	html+= (infobox_options[0] == 1) ? '<p>'+details.formatted_address+'</p>' : '';
	html+= (infobox_options[1] == 1) ? '<p>'+details.formatted_phone_number+'</p>' : '';
	html+= (infobox_options[2] == 1) ? '<p>'+details.website+'</p>' : '';
	return html;
}

// Function to set icons to different categories
function set_icons(markers, marker, categories) {
	if (marker.title == primary_location[3]) {
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

jQuery(document).ready(function($){
	doAreaAmenities.init({
		termsContainer : $(".am_navigation"),
		loading: $(".loading")
	});
});


	




