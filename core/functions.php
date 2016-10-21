<?php  
	function get_post_amenities(){

		global $post;

		$args = array(
			'post_type' => 'amenities',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1
		);

		$results = new WP_Query( $args );
		$array = array();

		if( $results->found_posts  > 0 ) {
			
			$array['found_posts'] = $results->found_posts;

			foreach( $results->posts as $post ) {

				$post->amenity_address = get_field('address', $post->ID);
				$post->amenity_category = set_post_tax_array(wp_get_post_terms( $post->ID, 'amenity_category' ));
				$array['posts'][] = $post;

			}

		}
		else {
			$array['posts'] = array('no dice');
		}
		return $array;

	}

	function set_post_tax_array($terms) {

		$array = array();
		if( $terms ) {
			foreach ($terms as $key => $term) {
				$array[] = $term->slug;
			}
		}
		else {
			$array = false;
		}
		return $array;

	}

	function display_amenity_category_menu() { ?>

		<?php $terms = get_terms('amenity_category', array('hide_empty' => false)) ?>
		<ul class="amenities-list">
			<li class="term-all "><a href="#" data-term-slug="all" class="active">All</a></li>
		<?php foreach ($terms as $key => $term): ?>
			<li class="side-nav-item term-<?php echo $term->term_id; ?> "><a href="#" data-term-slug="<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></li>
		<?php endforeach ?>
		</ul>

	<?php }
?>