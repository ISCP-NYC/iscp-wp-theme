<?php
$paged = $query_vars['paged'];
if( !$paged ):
	$paged = 1;
endif;
$critics_query = array(
	'post_type' => 'critic',
	'posts_per_page' => 36,
	'meta_key' => 'years_0_year',
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'paged' => $paged,
	'post_status' => 'publish'
);
$url = get_permalink();
$critics = new WP_Query( $critics_query );
$GLOBALS['wp_query'] = $critics;
$last_page = $critics->max_num_pages;
if( have_posts() ):
	while( have_posts() ) :
		the_post();
		$critic_id = $the_ID;
		$name = get_the_title( $critic_id );
		$title = get_field( 'title', $critic_id );
		$institution = get_field( 'institution', $critic_id );
		$city = get_field( 'city', $critic_id );
		$years = get_field( 'years', $critic_id );
		echo '<div class="critic shelf-item border-bottom">';
		echo '<div class="inner">';
		echo '<div class="value title">';
			echo '<h3>' . $name . '</h3>';
		echo '</div>';
		echo '<div class="value title">' . $title . '</div>';
		echo '<div class="value institution">' . $institution;
		if( $city ):
			if( $institution ):
				echo '</br>';
			endif;
			echo $city;
		endif;
		echo '</div>';
		echo '<div class="value years">';
		foreach( $years as $index=>$year ):
			if( $index > 0 ):
				echo ', ';
			endif;
			echo $year['year'];
		endforeach;
		echo '</div>';
		echo '</div>';
		echo '</div>';
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query();
?>