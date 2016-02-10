<?php
$country = get_page_by_path($country_slug, OBJECT, 'country');
$country_id = $country->ID;
$country_query = array(
	'key' => 'country',
	'value' => '"' . $country_id . '"',
	'compare' => 'LIKE'
);
$residents_query = array(
	'post_type' => 'resident',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'ordrby' => 'last_name',
	'post_status' => 'publish',
	'meta_query' => array( $country_query )
);
$residents = new WP_Query( $residents_query );
$GLOBALS['wp_query'] = $residents;
if( have_posts() ):
	while ( have_posts() ) :
		the_post();
		global $post;
		$id = $post->ID;
		$name = $post->post_title;
		$url = get_permalink( $id );
		$end_date = new DateTime( get_resident_end_date( $id ) );
		$year = $end_date->format('Y');
		echo '<div class="row shelf-item resident">';
		echo '<div class="inner">';
		echo '<div class="value name">';
		echo '<a href="' . $url . '">';
		echo $name;
		echo '</a>';
		echo '</div>';
		echo '<div class="value years">' . $year . '</div>';
		echo '<div class="value sponsors">' . get_sponsors( $id ) . '</div>';
		echo '</div>';
		echo '</div>';
		echo $sponsors;
	endwhile;
endif;
wp_reset_query(); 
?>