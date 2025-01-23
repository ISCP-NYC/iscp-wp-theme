<?php
$url = get_permalink();
$GLOBALS['wp_query'] = $supporters;
$last_page = $supporters->max_num_pages;
if( have_posts() ):
	while( have_posts() ) :
		the_post();
		// $supporter_id = $the_ID;
		$supporter_id = $post->ID;
		$title = get_the_title( $supporter_id );
		$country = get_field('country', $supporter_id ) ? get_field('country', $supporter_id )[0]->post_title : null;
		$country_slug = get_field('country', $supporter_id ) ? get_field('country', $supporter_id )[0]->post_name : null;
		// $country = get_field('country', $supporter_id )[0]->post_title;
		// $country_slug = get_field('country', $supporter_id )[0]->post_name;
		$country_permalink = query_url( 'from', $country_slug, $url );
		$permalink = get_permalink();
		$website = get_field('website', $supporter_id );
		$pretty_website = pretty_url( $website );
		$relationship = get_field('relationship', $supporter_id );
		echo '<div class="supporter shelf-item border-bottom"><div class="inner">';
		echo '<div class="value name">';
			echo '<h3>' . $title . '</h3>';
		echo '</div>';
		echo '<div class="value middle">';
			if( $country ) {
				echo '<a href="' . $country_permalink . '">' . $country . '</a>';
			} else if ( $relationship ) {
				echo $relationship;
			}
		echo '</div>';
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