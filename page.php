<?php
	get_header();
	global $post;
	$slug = get_post( $post )->post_name;
?>
	
	<section id="<?php echo $slug ?>">
		<?php get_template_part('partials/nav') ?>
		<?php get_template_part('partials/side') ?>
		<div class="content">
			<?php the_title( '<h3 class="title">', '</h1>' ); ?>
		</div>
	</section>

<?php get_footer(); ?>
