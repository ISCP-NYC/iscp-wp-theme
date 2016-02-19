<section <?php section_attr( 'error', 'error', 'error', 'Error' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
	<h1 class="title">
		Sorry, this page can't be found
	</h1>	
	<?php
	if( $search_value ):
		echo '<h3 class="title head">';
		echo '<span class="counter">' . $search_count . '</span>';
		echo ' results for ';
		echo '&ldquo;<span class="value">'. $search_value . '</span>&rdquo;';
		echo '</h3>';
	endif;
	echo '<form role="search" method="get" class="searchform" class="searchform" autocomplete="off" action="' . esc_url( home_url( '/' ) ) . '">';
	echo '<input type="text" data-placeholder="Search" value="Search" name="s" class="s main-search" />';
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