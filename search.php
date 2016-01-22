<?php 
get_header();
?>
<section <?php section_attr( null, 'search', '' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
	<?php
	echo '<h3 class="title head">Results for: ';
	echo '<form role="search" method="get" class="searchform" action="' . esc_url( home_url( '/' ) ) . '">';
			$search_value = get_search_query();
			if( !$search_value ):
				$search_value = 'Search';
			endif;
			echo '<input type="text" value="' . $search_value . '" name="s" class="search" />';
	echo '</form>';
	echo '</h3>';

	if ( have_posts() ) :
		echo '<div class="shelves results list">';
		while ( have_posts() ) : the_post();
			get_template_part( 'items/search');
		endwhile;
		echo '</div>';
	else :	
			echo '<h2 class="no">No results found for "' . $search_value . '"</h2>';
	endif;
	?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>

<?php get_footer(); ?>
