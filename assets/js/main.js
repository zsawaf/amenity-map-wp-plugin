var map_style = [
];

/*
    {lat: 43.544370, lng: -80.241390},
    {lat: 43.544518, lng: -80.241884},
    {lat: 43.544837, lng: -80.241814},
    {lat: 43.547522, lng: -80.243405}

*/

var doAreaAmenities = {

	cat : 'all',
	searchType : null,
	style: [],
	termsContainer : null,
	loading : null,
	initBounds: null,
	mapMarkers : [],
	infoWindows : [],
	activeBox: null,
    activeCategory: null,
	init : function( args ) {

		// replace map style with desired styles
		this.style = map_style;
		this.amenities = AMENITIES;
		this.termsContainer = args.termsContainer; // container of tax terms
		this.loading = args.loading; // selector for loading div

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
			self.cat = ( $(this).hasClass('active') ) ? self.cat : $(this).data('term-slug');

			if (!same_link) {
				self.termsContainer.find('a').removeClass('active');
			}
			
			if( self.cat ) {
				$(this).addClass('active');
			}	
			
			if (!same_link) {
				self.doResults();
			}
		});

	},

	doResults : function() {
		this.mapMarkers = [];
		this.setFIlteredResults();
		this.loopPostHtml();
		this.initialize( this.mapMarkers );
	},

	setFIlteredResults : function() {
		var amenities = this.amenities['data']['posts'];
		var amenities_category = $(".amenities-header ul .active").attr("data-term-slug");

		if('all' == this.cat) {
			filteredList = amenities;
		}
		else {
			var filteredList = [];
			// loop through all amenities
			for( i = 0; i <= amenities.length - 1; i ++ ) {
				var amentyCategories = amenities[i]['amenity_category'];
                console.log(amenities[i].post_name);
				if (amenities[i].post_name == "the-metalworks") {
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
				if( typeof obj[i].amenity_address.lat !== 'undefined' && typeof obj[i].amenity_address.lng !== 'undefined' ) {

					if( obj[i].amenity_address.address != '' ) {
						self.mapMarkers.push( [obj[i].post_name, obj[i].amenity_address.lat, obj[i].amenity_address.lng, obj[i]['amenity_category'][0] ] );
					}	
						// infoWindowContent.push( [ <?=json_encode( $content )?> ] );
					
					if( obj[i].amenity_address.address && obj[i].amenity_address.address != '' ) {

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
		// var defaultIcon = AMENITIES['theme_url'] + '/assets/img/ellipse.png';
		// var activeIcon = AMENITIES['theme_url'] + '/assets/img/active-marker.png';
		var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

		var success = 0;
		var markers_array = [];

		if(  markers.length ) {
			for( i = 0; i < markers.length; i++ ) {
				if( typeof markers[i] === 'undefined' || typeof markers[i][1] === 'undefined' || typeof markers[i][2] === 'undefined' )
					return false;
				else 
					success++;
				
				var markerIcon = AMENITIES['theme_url'] + '/assets/img/brown-map-pin.png';
				var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
				
				bounds.extend(position);
				marker = new google.maps.Marker({
					position: position,
					map: map,
					zoom : 5,
					title: markers[i][0]
				});

                set_icons(markers[i], marker);

				markers_array.push(marker);

				var infobox = new InfoBox({
					content: document.getElementById("infobox"),
					disableAutoPan: false,
					maxWidth: 150,
					pixelOffset: new google.maps.Size(0, -45),
					zIndex: null,
					boxStyle: {
						opacity: 1,
						width: "auto",
						backgroundColor: '#4e3629',
						color: '#f9f2d8',
						padding: "7px 15px 7px 11px",
						borderRadius : "0",
                        fontSize: '12px',
						border: '1px solid #f9f2d8'
					},
					closeBoxMargin: "-5px -12px -5px 5px",
					closeBoxURL: AMENITIES['theme_url'] + '/assets/img/close.png',
					infoBoxClearance: new google.maps.Size(1, 1),
				});

				// Allow each marker to have an info window    
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						if( self.infoWindows[i] ) {
							if (markers[i][0] != "the-metalworks") {
                                self.activeCategory = markers[i][3];
								self.activeBox = i;
								for (var j=0; j<markers_array.length; j++) {
									set_icons(markers[j], marker);
								}
								markers_array[i].setIcon(AMENITIES['theme_url'] + '/assets/img/black-map-pin.png');
								infobox.setContent(self.infoWindows[i][0]);
								infobox.open(map, marker);
								map.panTo(position);
							}
						}

					}
				})(marker, i));

				google.maps.event.addListener(infobox, 'closeclick', function(){
                    set_icons([0, 0, 0, self.activeCategory], markers_array[self.activeBox]);
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

function set_icons(markers, marker) {
    if (markers[3] == 'recreation') {
        marker.setIcon(AMENITIES['theme_url'] + '/assets/img/green-map-pin.png');
    }

    else if (markers[3] == 'services') {
        marker.setIcon(AMENITIES['theme_url'] + '/assets/img/gold-map-pin.png');
    }

    else if (markers[3] == 'dining-nightlife') {
        marker.setIcon(AMENITIES['theme_url'] + '/assets/img/teal-map-pin.png');
    }
    else {
         marker.setIcon(AMENITIES['theme_url'] + '/assets/img/brown-map-pin.png');
    }
}

	




