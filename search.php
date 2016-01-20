<?php 
get_header();
?>
<section <?php section_attr( null, 'search', '' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">

	<?php if ( have_posts() ) : ?>
		<h4 class="title orange">
			<?php echo 'Results for "' . get_search_query() . '"'; ?>
		</h4>
		<?php
		while ( have_posts() ) : the_post();
			global $post;
			setup_postdata( $post );
			print_r( $post );
			wp_reset_postdata();
		endwhile;

		// the_posts_pagination( array(
		// 	'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
		// 	'next_text'          => __( 'Next page', 'twentyfifteen' ),
		// 	'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
		// ) );

	else :
		get_template_part( 'content', 'none' );
	endif;
	?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>

<?php get_footer(); ?>
