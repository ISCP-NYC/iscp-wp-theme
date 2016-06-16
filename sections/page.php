<?php 
global $post;
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
$description = get_field( 'description', $id );
if( !$description ):
	$description = get_post( $id )->post_content;
	$description = apply_filters('the_content', $description);
endif;
?>
<section  <?php section_attr( $id, $slug, $slug ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="module">
			<?php echo $description; ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>