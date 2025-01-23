<?php
include( locate_template( 'sections/params/sponsors.php' ) );
$url = get_permalink();
$sponsors = new WP_Query( $sponsors_query );
$GLOBALS['wp_query'] = $sponsors;
$last_page = $sponsors->max_num_pages;
if( have_posts() ):
	while( have_posts() ) :
		the_post();
		// $sponsor_id = $the_ID;
		$sponsor_id = $post->ID;
		$title = get_the_title( $sponsor_id );
		$country = get_field('country', $sponsor_id ) ? get_field('country', $sponsor_id )[0]->post_title : null;
		$country_slug = get_field('country', $sponsor_id ) ? get_field('country', $sponsor_id )[0]->post_name : null;
		$country_permalink = query_url( 'from', $country_slug, $url );
		$permalink = get_permalink();
		$website = get_field('website', $sponsor_id );
		$pretty_website = explode( '/', pretty_url( $website ) )[0];
		echo '<div class="sponsor shelf-item border-bottom"><div class="inner">';
			echo '<div class="value link name">';
				echo '<a href="' . $permalink . '">';
					echo '<h3>' . $title . '</h3>';
				echo '</a>';
			echo '</div>';
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