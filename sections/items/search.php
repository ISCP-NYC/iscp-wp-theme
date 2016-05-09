<?php
global $post;
setup_postdata( $post );
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
$thumb = get_thumb( $resident_id, 'thumbnail' );
$post_type = $post->post_type;
$permalink = get_permalink( $id );
$classes = $slug . ' ' . $post_type;
if( $post_type == 'contributor' ):
	$permalink = get_field( 'website', $id );
endif;

if( $thumb ):
	$classes .= ' hasthumb';
else:
	$classes .= ' nothumb';
endif;

if( $post_type == 'country' ):
	$residents_url = get_permalink( get_page_by_path( 'residents' )->ID);
	$permalink = $residents_url . '?filter=all&from=' . $slug;
endif;

echo '<div class="result item shelf-item border-bottom ' . $classes . '">';
echo '<div class="inner">';
if( $permalink ):
	echo '<a class="wrap" href="' . $permalink . '">';
else:
	echo '<div class="wrap">';
endif;
if( $thumb ):
	echo '<div class="thumb" style="background-image:url(' . $thumb . ')"></div>';
endif;
echo '<div class="title">' . $title . '</div>';
echo '<div class="value post-type">' . $post_type . '</div>';
echo '</div>';
if( $permalink ):
	echo '</a>';
else:
	echo '</div>';
endif;
echo '</div>';
wp_reset_postdata();
?>