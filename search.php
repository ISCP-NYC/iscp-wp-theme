<?php 
get_header();
if( $query_vars ):
	$paged = $query_vars['paged'];
else:
	$paged = 1;
endif;
$search_value = get_search_query();
if( !$search_value ):
	$search_value = 'Search';
endif;
// global $wp_query;
$search_count = $wp_query->found_posts;
?>
<section <?php section_attr( null, 'search', '' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
	<?php
	if( $search_value ):
		echo '<h3 class="title head">' . $search_count . ' results for &ldquo;' . $search_value . '&rdquo;';
	endif;
	get_search_form();
	if ( have_posts() ) :
		echo '<div class="shelves results list">';
			include( locate_template( 'sections/loops/search.php' ) );
		echo '</div>';
		get_template_part( 'partials/load-more' );
	else :	
		echo '<h2 class="no">No results found for "' . $search_value . '"</h2>';
	endif;
	?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>

<?php get_footer(); ?>
