<section id="residents">
	<?php get_template_part('nav') ?>

	<?php 
		$args = array( 'post_type' => 'resident', 'posts_per_page' => 10 );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
		  echo '<div class="resident">';
		  the_title();
		  echo '</div>';
		endwhile;
	?>
</section>