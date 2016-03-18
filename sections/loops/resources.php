<?php
$resource_title = $post->post_title;
$resource_slug = $post->post_name;
setup_postdata( $post );
$resource_permalink = get_the_permalink();
echo '<div class="resource-list ' . $resource_slug . '">';
echo '<h2>';
echo '<a href="' . $resource_permalink . '">';
echo $resource_title . ':';
echo '</a>';
echo '</h2>';
if( have_rows( 'item' ) ):
	$index = 0;
	while ( have_rows('item') ) : the_row();
		$item_title = get_sub_field( 'title' );
		$index++;
		echo '<div>';
		echo '<a class="bullet" href="' . $resource_permalink . '#' . $index . '">' . $item_title . '</a>';
		echo '</div>';
	endwhile;
else:
	echo 'Nothing for now';
endif;
echo '</div>';
wp_reset_postdata();
?>