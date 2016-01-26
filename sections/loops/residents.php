<?php
include(locate_template('sections/params/residents.php'));
if( $country_param ) {
	$filter_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
	$append_query = '?from=' . $country_param;
} elseif( $year_param ) {
	$year_begin = $year_param . '0101';
	$year_end = $year_param . '1231';
	$year_range = array( $year_begin, $year_end );
	$filter_query = array(
		'key' => 'residency_dates_0_start_date',
		'type' => 'DATE',
		'value' => $year_range,
		'compare' => 'BETWEEN'
	);
	$append_query = '?date=' . $year_param;
} elseif( $program_param ) {
	$filter_query = array(
		'key' => 'residency_program',
		'type' => 'CHAR',
		'value' => $residency_program,
		'compare' => 'LIKE'
	);
	$append_query = '?residency_program=' . $program_param;
}
$residents_query = array(
	'post_type' => 'resident',
	'posts_per_page' => 12,
	'paged' => $paged,
	'orderby' => 'last_name',
	'order' => 'ASC',
	'post_status' => 'publish',
	'meta_query' => array( $page_query, $filter_query )
);
$residents = new WP_Query( $residents_query );
$GLOBALS['wp_query'] = $residents;
if ( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/resident' );
	endwhile;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query(); 
?>