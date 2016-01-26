<?php
include(locate_template('sections/params/events.php'));
if( $event_type_param ) {
	$filter_key = 'event_type';
	$filter_query = array(
		'key' => 'event_type',
		'type' => 'CHAR',
		'value' => $event_type_param,
		'compare' => 'LIKE'
	);
	$append_query = '?type=' . $event_type;
} elseif( $year_param ) {
	$year_begin = $year_param . '0101';
	$year_end = $year_param . '1231';
	$year_range = array( $year_begin, $year_end );
	$filter_query = array(
		'relation' => 'OR',
		array(
			'key' => 'start_date',
			'type' => 'DATE',
			'value' => $year_range,
			'compare' => 'BETWEEN'
		),
		array(
			'key' => 'end_date',
			'type' => 'DATE',
			'value' => $year_range,
			'compare' => 'BETWEEN'
		),
		array(
			'key' => 'date',
			'type' => 'DATE',
			'value' => $year_range,
			'compare' => 'BETWEEN'
		)
	);
	$append_query = '?date=' . $year_param;
}

$events_query = array(
	'post_type' => 'event',
	'posts_per_page' => 12,
	'paged' => $paged,
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'post_status' => 'publish',
	'meta_query' => array( $filter_query )
);

$events = new WP_Query( $events_query );
$GLOBALS['wp_query'] = $events;
if ( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/event' );
	endwhile;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query();
?>