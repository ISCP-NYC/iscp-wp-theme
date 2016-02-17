<?php
include(locate_template('sections/params/sponsors.php'));
if( $country_param ):
	$filter_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
	$append_query = '?from=' . $country_param;
endif;
$sponsors_query = array(
	'post_type' => 'sponsor',
	'posts_per_page' => 18,
	'orderby' => 'name',
	'order' => 'ASC',
	'paged' => $paged,
	'post_status' => 'publish',
	'meta_query' => array( $sponsors_query, $filter_query )
);
$sponsors = new WP_Query( $sponsors_query );
$GLOBALS['wp_query'] = $sponsors;
$last_page = $sponsors->max_num_pages;
if( have_posts() ):
	while( have_posts() ) :
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