<?php
include(locate_template('sections/params/journals.php'));
$journal_query = array(
	'post_type' => 'journal',
	'posts_per_page' => 12,
	'paged' => $paged,
	'orderby' => 'date',
	'order' => 'DESC',
	'post_status' => 'publish'
);
if( $filter_query ):
	$journal_query = array_merge( $filter_query, $journal_query );
endif;
$journal = new WP_Query( $journal_query );
$GLOBALS['wp_query'] = $journal;
$last_page = $journal->max_num_pages;
if( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/journal' );
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query(); 
?>