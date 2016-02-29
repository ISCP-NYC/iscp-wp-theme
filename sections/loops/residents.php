<?php
include( locate_template( 'sections/params/residents.php' ) );
if( $country_param ):
	$country_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
	$filter_query = array(
		'relation' => 'AND',
		$filter_query,
		$country_query
	);
endif;

if( $year_param ):
	$year_begin = $year_param . '0101';
	$year_end = $year_param . '1231';
	$year_range = array( $year_begin, $year_end );
	$year_query = array(
		'key' => 'residency_dates_0_start_date',
		'type' => 'DATE',
		'value' => $year_range,
		'compare' => 'BETWEEN'
	);
	$filter_query = array(
		'relation' => 'AND',
		$filter_query,
		$year_query
	);
endif;
if( $program_param ):
	$program_query = array(
		'key' => 'residency_program',
		'type' => 'CHAR',
		'value' => $program_param,
		'compare' => 'LIKE'
	);
	$filter_query = array(
		'relation' => 'AND',
		$filter_query,
		$program_query
	);
endif;
if( $sponsor_param ):
	$sponsor_query = array(
		'key' => 'residency_dates_0_sponsors',
		'value' => '"' . $sponsor_id . '"',
		'compare' => 'LIKE'
	);
	$sponsor_query = array(
		'relation' => 'AND',
		$filter_query,
		$sponsor_query
	);
endif;
if( $post_type == 'sponsor' || $page_type == 'sponsor' ):
	$sponsor_id = get_page_by_path( $slug, OBJECT, 'sponsor' )->ID;
	$sponsor_query = array(
		'key' => 'residency_dates_0_sponsors',
		'value' => '"' . $sponsor_id . '"',
		'compare' => 'LIKE'
	);
	$filter_query = array(
		'relation' => 'AND',
		$filter_query,
		$sponsor_query
	);
endif;
$residents_query = array(
	'post_type' => 'resident',
	'posts_per_page' => 12,
	'paged' => $paged,
	'post_status' => 'publish',
	'meta_query' => array( $page_query, $filter_query )
);
$residents_query = array_merge( $residents_query, $orderby_array );
$residents = new WP_Query( $residents_query );
$GLOBALS['wp_query'] = $residents;
$last_page = $residents->max_num_pages;
if( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/resident' );
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query(); 
?>