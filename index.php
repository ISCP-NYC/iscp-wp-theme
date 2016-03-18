<?php
get_header();
global $post;
// $visit_page_id = get_page_by_path( 'visit' )->ID;
// $post = get_post( $visit_page_id, OBJECT );
// setup_postdata( $post );
// get_template_part( 'sections/visit' );
// wp_reset_postdata();

get_template_part( 'sections/home' );

$current_residents_page_id = get_page_by_path('current-residents')->ID;
$post = get_post( $current_residents_page_id, OBJECT );
$post->delay = 1;
setup_postdata( $post );
global $post;
get_template_part( 'sections/residents' );
wp_reset_query();

$past_residents_page_id = get_page_by_path('past-residents')->ID;
$post = get_post( $past_residents_page_id, OBJECT );
$post->delay = 2;
setup_postdata( $post );
global $post;
get_template_part( 'sections/residents' );
wp_reset_query();

get_footer();
?>
