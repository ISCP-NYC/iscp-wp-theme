<section <?php section_attr( 'error', 'error', 'error', 'Error' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
	<h1 class="title">
		Sorry, this page can't be found
	</h1>	
	<?php
	if( !isset( $search_value ) ):
		$search_value = search_broken_link();
		$query_vars['s'] = $search_value;
		$search_query = new WP_Query( array(
			's' => $search_value,
			'posts_per_page' => 24,
			'paged' => 1,
			'post_status' => 'publish'
		) );
		$GLOBALS['wp_query'] = $search_query;
	endif;

	if( $search_value ):
		echo '<h3 class="title head">';
		echo '<span class="counter">' . $search_count . '</span>';
		echo ' results for ';
		echo '&ldquo;<span class="value">'. $search_value . '</span>&rdquo;';
		echo '</h3>';
		$placeholder_opacity = ' style="opacity: 0"';
	endif;
	echo '<form role="search" method="get" class="searchform" class="searchform" autocomplete="off" action="' . esc_url( home_url( '/' ) ) . '">';
	echo '<div class="placeholder"' . $placeholder_opacity . '><span>Search</span></div>';
	echo '<input type="text" value="' . $search_value . '" name="s" class="s main-search" spellcheck="false" />';
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