<?php
include(locate_template('sections/params/events.php'));
if(!$events_section):
	$events_section = $GLOBALS['events_section'];
endif;
$today = date('Ymd');
if( $events_section == 'past' ):
	if( $event_type_param ):
		$filter_key = 'event_type';
		$filter_query = array(
			'key' => 'event_type',
			'type' => 'CHAR',
			'value' => $event_type_param,
			'compare' => 'LIKE'
		);
		$append_query = '?type=' . $event_type;
	elseif( $year_param ):
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
			)
		);
		$append_query = '?date=' . $year_param;
	endif;
	$date_query = array(
		'relation' => 'AND',
		array(
			'key' => 'start_date',
			'compare' => '<',
			'value' => $today
		),
		array(
			'key' => 'end_date',
			'compare' => '<',
			'value' => $today
		)
	);
	if( !$upcoming_ids ):
		$upcoming_ids = $GLOBALS['upcoming_ids'];
	endif;
else:
	$date_query = array(
		'relation' => 'OR',
		array(
			'key' => 'start_date',
			'compare' => '>',
			'value' => $today
		),
		array(
			'key' => 'end_date',
			'compare' => '>',
			'value' => $today
		)
	);
endif;
$events_query = array(
	'post_type' => 'event',
	'posts_per_page' => 12,
	'paged' => $paged,
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'post_status' => 'publish',
	'post__not_in' => $upcoming_ids,
	'meta_query' => array( array( 'key'=>'start_date' ), $filter_query, $date_query )
);
$upcoming_ids = array();
$events = new WP_Query( $events_query );
$GLOBALS['wp_query'] = $events;
$last_page = $events->max_num_pages;
if ( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/event' );
		if( $events_section == 'upcoming' ):
			$upcoming_ids[] = get_the_ID();
		endif;
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
$GLOBALS['upcoming_ids'] = $upcoming_ids;
wp_reset_query();
?>