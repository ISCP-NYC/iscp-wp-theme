<?php
global $post;
setup_postdata( $post );
$id = $post->ID;
$title = $post->post_title;
$thumb = get_thumb( $resident_id, 'thumbnail' );
$post_type = $post->post_type;
$permalink = get_permalink( $id );

echo '<div class="result item shelf-item border-bottom">';
echo '<div class="inner">';
echo '<a class="value wrap" href="' . $permalink . '">';
if($thumb):
	echo '<div class="thumb" style="background-image:url(' . $thumb . ')"></div>';
endif;
echo '<div class="title">' . $title . '</div>';
echo '<div class="value post-type">' . $post_type . '</div>';
echo '</div>';
echo '</a>';
echo '</div>';
wp_reset_postdata();
?>