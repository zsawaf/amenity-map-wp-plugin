var $ = jQuery;
var cat_accordian = {

	amentityCategories: [],
	returnHtml: '',

	init: function(args) {
		
		var self = this;
		var amentityCategories = CATEGORY_AMENITIES.categories;
		var vw = $(window).width();

		this.initialized = false;
		this.active_category = null;

		this.amentityCategories = amentityCategories;
		this.accordianList = args.accordianList;

		$(window).resize(function(){
			vw = $(window).width();
			self.callInit(vw);
		});

		this.callInit(vw);
		
	},

	callInit: function(vw) {
		if (vw <= 768 && !this.initialized) {
			this.accordionClick();
			this.amenityClick();
			this.loopHtml();
			this.initialized = true;
		}
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
				// I DON'T LIKE THIS
				setTimeout(function(){
					self.getPlaceId(amenity, $catParent);
				}, 200);
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

		html = '<a class="amenity" href="#"><span>'+place.name+'</span><i class="inner-icon"></i></a><ul class="place-details"><li>'+address+'</li>'+'<li>'+phone+'</li>'+'<li><a target="_blank" href="'+website+'">'+website+'</a></li>'+'<li class="opening-hours"><ul>'+hours_string+'</ul></ul>';

		return html;
	},

	accordionClick: function() {
		$(document).on('click', '.amenities-list .side-nav-item > a', function(e){
			e.preventDefault();
			var $parent = $(this).parent();
			var $sub_list = $parent.find('.sub-list');

			if ($sub_list.hasClass('expanded')) {
				$sub_list.slideUp(300, function(){
					$(this).removeClass('expanded');
					$(this).parent().find('> a > i').removeClass('minus');
				});
			}
			else {
				if (self.active_category) {
					self.active_category.removeClass('expanded');
					self.active_category.parent().find('> a > i').removeClass('minus');
					self.active_category.slideUp(300);
				}
				$sub_list.slideDown(300, function(){
					$(this).addClass('expanded');
					$(this).parent().find('> a > i').addClass('minus');
					self.active_category = $sub_list;
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
				$place_details.slideUp(150, function(){
					$(this).removeClass('expanded');
					$elem.removeClass('expanded');
					$elem.find('.amenity > i').removeClass('minus');

				});
			}
			else {
				$place_details.slideDown(150, function(){
					$(this).addClass('expanded');
					$elem.addClass('expanded');
					$elem.find('.amenity > i').addClass('minus');
				});
			}
		});
	}

}
