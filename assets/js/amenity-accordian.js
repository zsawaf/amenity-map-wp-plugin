var $ = jQuery;
var cat_accordian = {

	amentityCategories: [],
	returnHtml: '',

	init: function(args) {
		
		var amentityCategories = CATEGORY_AMENITIES.categories;
		this.amentityCategories = amentityCategories;
		this.accordianList = args.accordianList;
		this.loopHtml();
		
	},

	loopHtml: function() {

		var amentityCategories = this.amentityCategories;

		for( var i in amentityCategories ) {
			 this.setAmenityList( amentityCategories[i] );
		}

	},

	setAmenityList: function( categoryAmenityList ) {

		var self = this;
		var $catParent = this.accordianList.find('#amenity-category-' + categoryAmenityList.category.term_id );
		for( var i in categoryAmenityList.amenities ) {	
			// console.log(categoryAmenityList.amenities);
			var amenity = categoryAmenityList.amenities[i];
			var html = '';
			html += '<li class="amenity-list-item amenity-' + amenity.ID  + '">' + amenity.post_title + '<li>';
			var place = self.getPlaceId(amenity);
		}
		$catParent.find('ul').html(html);
	},

	getPlaceId: function(amenity) {
		var place_id = amenity.place_id;
		var self = this;

		var service = new google.maps.places.PlacesService(document.createElement('div'));
		console.log($(".amenity-list-item-"+amenity.ID)[0]);
		var html;
		service.getDetails({
		  placeId: place_id
		}, function(place, status) {
			if (status === google.maps.places.PlacesServiceStatus.OK) {
				console.log(place);
			}
		});
	}

}
