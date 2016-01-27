<?php
if( $country_param ):
	$filter_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
	$append_query = '?from=' . $country_param;
elseif( $year_param ):
	$year_begin = $year_param . '0101';
	$year_end = $year_param . '1231';
	$year_range = array( $year_begin, $year_end );
	$filter_query = array(
		'key' => 'residency_dates_0_start_date',
		'type' => 'DATE',
		'value' => $year_range,
		'compare' => 'BETWEEN'
	);
	$append_query = '?date=' . $year;
elseif( $program_param ):
	$filter_query = array(
		'key' => 'residency_program',
		'type' => 'CHAR',
		'value' => $program_param,
		'compare' => 'LIKE'
	);
	$append_query = '?residency_program=' . $year;
endif;

if( $filter_query ):
	$page_query = array_merge( $sponsors_query, $filter_query );
endif;

$sponsors_query = array(
	'post_type' => 'sponsor',
	'posts_per_page' => 18,
	'orderby' => 'name',
	'order' => 'ASC',
	'post_status' => 'publish',
	'meta_query' => array( $sponsors_query )
);
$sponsors = new WP_Query( $sponsors_query );
$GLOBALS['wp_query'] = $sponsors;
$last_page = $sponsors->max_num_pages;
if ( have_posts() ):
	while ( have_posts() ) :
		the_post();
		$sponsor_id = $the_ID;
		$title = get_the_title( $sponsor_id );
		$country = get_field('country', $sponsor_id )[0]->post_title;
		$country_permalink = get_field('country', $sponsor_id )[0]->permalink;
		$permalink = get_permalink();
		$website = get_field('website', $sponsor_id );
		$pretty_website = pretty_url( $website );
		echo '<div class="sponsor shelf-item border-bottom"><div class="inner">';
		echo '<a class="value name" href="' . $permalink . '">';
		echo '<h3 class="link">' . $title . '</h3>';
		echo '</a>';
		echo '<div class="value country"><a href="' . $country_permalink . '">' . $country . '</a></div>';
		if($website):
			echo '<div class="value website">';
			echo '<a href="' . $website . '" target="_blank">' . $pretty_website . '</a>';
			echo '</div>';
		endif;
		echo '</div></div>';
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query();
?>