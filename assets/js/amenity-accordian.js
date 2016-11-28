var $ = jQuery;
var cat_accordian = {

	amentityCategories: [],
	returnHtml: '',

	init: function(args) {
		
		var amentityCategories = CATEGORY_AMENITIES.categories;
		this.amentityCategories = amentityCategories;
		this.accordianList = args.accordianList;
		this.accordionClick();
		this.amenityClick();
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
		var html = '';
		for( var i in categoryAmenityList.amenities ) {	
			// console.log(categoryAmenityList.amenities);
			var amenity = categoryAmenityList.amenities[i];
			self.getPlaceId(amenity, $catParent);
		}
	},

	getPlaceId: function(amenity, $catParent) {
		var place_id = amenity.place_id;
		var self = this;
		var return_place = null;
		var service = new google.maps.places.PlacesService(document.createElement('div'));
		var html;
		service.getDetails({
		  placeId: place_id
		}, function(place, status) {
			if (status === google.maps.places.PlacesServiceStatus.OK) {
				$catParent.find('.sub-list').append('<li class="amenity-list-item amenity-' + amenity.ID  + '">' + self.getPlaceHtml(place) + '</li>');
			}
			else {
				console.log(status);
			}
		});
	},

	getPlaceHtml: function(place) {
		var html = '';
		var address = (typeof place.formatted_address !== "undefined" ? place.formatted_address : '');
		var phone = (typeof place.formatted_phone_number !== "undefined" ? place.formatted_phone_number : '');
		var website = (typeof place.website !== "undefined" ? place.website : '');
		var opening_hours = (typeof place.opening_hours !== "undefined" ? place.opening_hours.weekday_text : '');
		var hours_string = '';
		for (var i=0; i < opening_hours.length; i++) hours_string += '<li>'+opening_hours[i]+'</li>';

		html = '<a class="amenity" href="#">'+place.name+'</a><ul class="place-details"><li>'+address+'</li>'+'<li>'+phone+'</li>'+'<li><a target="_blank" href="'+website+'">'+website+'</a></li>'+'<li class="opening-hours"><ul>'+hours_string+'</ul></ul>';

		return html;
	},

	accordionClick: function() {
		$(document).on('click', '.amenities-list .side-nav-item a', function(e){
			e.preventDefault();
			var $parent = $(this).parent();
			var $sub_list = $parent.find('.sub-list');

			if ($sub_list.hasClass('expanded')) {
				$sub_list.slideUp(300, function(){
					$(this).removeClass('expanded');
				});
			}
			else {
				$(".amenities-list .sub-list").removeClass('expanded');
				$(".amenities-list .sub-list").slideUp(300, function(){
					$sub_list.slideDown(300, function(){
						$(this).addClass('expanded');
					});
				});
				
			}
		});
	},

	amenityClick: function() {
		$(document).on('click', '.amenity-list-item', function(e){
			e.preventDefault();
			var $elem = $(this);
			var $parent = $(this).parent();
			var $place_details = $(this).find('.place-details');
			if ($place_details.hasClass('expanded')) {
				$place_details.slideUp(300, function(){
					$(this).removeClass('expanded');
					$elem.removeClass('expanded');
				});
			}
			else {
				$place_details.slideDown(300, function(){
					$(this).addClass('expanded');
					$elem.addClass('expanded');
				});
			}
		});
	}

}
