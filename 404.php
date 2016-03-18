<?php
global $post;
get_header();

$home_page_id = get_page_by_path('home')->ID;
$post = get_post( $home_page_id, OBJECT );
setup_postdata( $post );
get_template_part( 'sections/home' );
wp_reset_postdata();

get_template_part( 'sections/error' );

$about_page_id = get_page_by_path('current-about')->ID;
$post = get_post( $about_page_id, OBJECT );
setup_postdata( $post );
get_template_part( 'sections/about' );
wp_reset_postdata();

get_footer();
?>