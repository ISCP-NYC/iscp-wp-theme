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
		echo '<h3 class="title head">';
		echo '<span class="counter">' . $search_count . '</span>';
		echo ' results for ';
		echo '&ldquo;<span class="value">'. $search_value . '</span>&rdquo;';
		echo '</h3>';
	endif;
	echo '<form role="search" method="get" class="searchform" class="searchform" autocomplete="off" action="' . esc_url( home_url( '/' ) ) . '">';
	echo '<input type="text" data-placeholder="Search" value="Search" name="s" class="s main-search" spellcheck="false" />';
	echo '</form>';
	echo '<div class="counter"></div>';
	echo '<div class="shelves results list">';
	if ( have_posts() ) :
		include( locate_template( 'sections/loops/search.php' ) );
	endif;
	echo '</div>';
	?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>

<?php get_footer(); ?>
