<?php
include( locate_template( 'sections/params/contributors.php' ) );
$url = get_permalink();
$contributors = new WP_Query( $contributors_query );
$GLOBALS['wp_query'] = $contributors;
$last_page = $contributors->max_num_pages;
if( have_posts() ):
	while( have_posts() ) :
		the_post();
		$contributor_id = $the_ID;
		$title = get_the_title( $contributor_id );
		$country = get_field('country', $contributor_id )[0]->post_title;
		$country_slug = get_field('country', $contributor_id )[0]->post_name;
		$country_permalink = query_url( 'from', $country_slug, $url );
		$permalink = get_permalink();
		$website = get_field('website', $contributor_id );
		$pretty_website = pretty_url( $website );
		echo '<div class="contributor shelf-item border-bottom"><div class="inner">';
		echo '<div class="value name">';
		echo '<h3>' . $title . '</h3>';
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