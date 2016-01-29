<?php
global $post;
setup_postdata( $post );
$joural_post_id = $the_ID;
$title = get_the_title();
$url = get_permalink();
$date = get_the_date('F d, Y');
$thumb = get_thumb( $joural_post_id, null, false );
$author_f = get_the_author_meta('first_name');
$author_l = get_the_author_meta('last_name');
$author = $author_f . ' ' . $author_l;
$tags = get_the_tags();
if( $append_query ) {
	$url .= $append_query;
}
$joural_post_status = 'new';

echo '<div class="journal-post item border-bottom ' . $joural_post_status . '">';
echo '<div class="inner">';
echo '<header>';
echo '<a class="name" href="' . $url . '">';
echo '<h2 class="name">' . $title . '</h2>';
echo '</a>';
echo '<h3 class="date">' . $date . '</h3>';
echo '<h3 class="author">by ' . $author . '</h3>';
echo '</header>';
echo '<a class="image" href="' . $url . '">';
echo '<div class="image">';
if ($thumb != ''):
echo '<img src="' . $thumb . '"/>';
endif;
echo '</div>';
echo '</a>';
echo '<div class="excerpt">';
echo the_excerpt();
echo '</div>';
$tags_count = count( $tags ) - 1;
$tag_index = 0;
if( $tags ):
  echo '<div class="tags">';
  foreach( $tags as $tag ):
  	$tag_name = $tag->name;
  	$tag_slug = $tag->slug;
  	$tag_url = site_url( '/journal/?tag=' . $tag_slug );
  	echo '<a href="' . $tag_url . '">' . $tag_name . '</a>';
  	if( $tag_index < $tags_count ):
  		echo ', ';
  	endif;
  	$tag_index++;
  endforeach;
  echo '</div>';
endif;
echo '</div>';
echo '</div>';
wp_reset_postdata();
?>