<?php 
	
	add_action('wp_head', 'am_navigation_amenities_style');
	function am_navigation_amenities_style() {
		?>
		<style>
			<?php
				global $post;
				$args = array(
					'post_type' => 'amenities',
					'orderby' => 'name',
		  			'order' => 'ASC',
		  			'posts_per_page' => -1
				);
				$categories = get_terms('amenity_category', $args);
				foreach($categories as $category) {
					?>
					.am_navigation .<?php echo $category->slug ?> {
						color: <?php echo am_get_term_color( $category->term_id ) ?>;
						background-color: <?php echo am_get_term_color( $category->term_id ) ?>;
					}
					<?php
				}
			?>
			.am_navigation a {
				color: #fff;
			}
		</style>
		<?php
	}
?>