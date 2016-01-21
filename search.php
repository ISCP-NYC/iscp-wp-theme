<?php 
get_header();
?>
<section <?php section_attr( null, 'search', '' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
	<?php 
	if ( have_posts() ) :
		echo '<h4 class="title orange">';
			echo 'Results for "' . get_search_query() . '"';
		echo '</h4>';
		echo '<div class="shelves results grid">';
		while ( have_posts() ) : the_post();
			global $post;
			setup_postdata( $post );
			$post_type = $post->post_type;
			get_template_part( 'items/' . $post_type );
			wp_reset_postdata();
		endwhile;
		echo '</div>';
	else :
		get_template_part( 'content', 'none' );
	endif;
	?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>

<?php get_footer(); ?>
