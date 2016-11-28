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
		 
		 // console.log(categoryAmenityList);

		var $catParent = this.accordianList.find('#amenity-category-' + categoryAmenityList.category.term_id );
		for( var i in categoryAmenityList.amenities ) {
			 
			 // console.log(categoryAmenityList.amenities);
			 
			 var amenity = categoryAmenityList.amenities[i];
			 var html = '';
			 html += '<li class="amenity-list-item amenity-' + amenity.ID  + '">' + amenity.post_title + '<li>';
		}
		$catParent.find('ul').html(html);
	}

}
