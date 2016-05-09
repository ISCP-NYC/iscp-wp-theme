<?php
$country = get_page_by_path($country_slug, OBJECT, 'country');
$country_id = $country->ID;
$residents_id = get_page_by_path( 'residents' )->ID;
$residents_url = get_permalink( $residents_id );
$country_query = array(
	'key' => 'country',
	'value' => '"' . $country_id . '"',
	'compare' => 'LIKE'
);
$residents_query = array(
	'post_type' => 'resident',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'meta_key' => 'residency_dates_0_start_date',
	'orderby' => 'meta_value_num',
	'order' => 'DESC',
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
		$year_url = $residents_url . '?filter=all&date=' . $year;
		$year = '<a href="' . $year_url . '">' . $year . '</a>';
		$bio = get_field( 'bio', $resident_id );
		echo '<div class="row shelf-item resident">';
		echo '<div class="inner">';
		echo '<div class="value name">';
		if($bio):
			echo '<a href="' . $url . '">';
			echo $name;
			echo '</a>';
		else:
			echo $name;
		endif;
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