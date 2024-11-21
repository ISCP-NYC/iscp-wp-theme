<?php
global $post;
global $more;
$more = 0;
setup_postdata( $post );
$journal_post_id = $post->ID;
$title = get_the_title();
$url = get_permalink();
$date = get_the_date('F d, Y');
$thumb = get_thumb( $journal_post_id, null );
$author_f = get_the_author_meta('first_name');
$author_l = get_the_author_meta('last_name');
$author = $author_f . ' ' . $author_l;
if( get_field( 'author' ) ):
  $author = get_field( 'author' );
endif;
$tags = get_the_tags();
$joural_post_status = 'new';

echo '<div class="journal-post item border-bottom ' . $joural_post_status . '">';
echo '<div class="inner">';
echo '<header>';
if ($thumb != ''):
	echo '<a class="name" href="' . $url . '">';
	echo '<img src="' . $thumb . '"/>';
	echo '</a>';
endif;
echo '<div class="title-wrapper">';
echo '<a class="name" href="' . $url . '">';
echo '<h2 class="name">' . $title . '</h2>';
echo '</a>';
$tags_count = $tags ? count( $tags ) - 1 : null;
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
echo '<h3 class="date">' . $date . '</h3>';
echo '<h3 class="author">by ' . $author . '</h3>';
echo '</header>';
echo '<div class="excerpt">';
echo the_excerpt();
echo ' <a href="' . $url . '">Read more.</a>';
echo '</div>';
echo '</div>';
echo '</div>';
wp_reset_postdata();
?>