<?php 
global $post;
setup_postdata( $post );
$sponsor_id = $the_ID;
$title = get_the_title( $sponsor_id );
$country = get_field('country', $sponsor_id )[0]->post_title;
$country_permalink = get_field('country', $sponsor_id )[0]->permalink;
$permalink = get_permalink();
$website = get_field('website', $sponsor_id );
$pretty_website = pretty_url( $website );
echo '<div class="sponsor shelf-item border-bottom no-image"><div class="inner">';
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
wp_reset_postdata();
?>