<?php
global $post;
$id = $post->ID;
$slug = $post->post_name;
$title = $post->post_title;
$paged = 1;
?>
<section <?php section_attr( $id, $slug, 'critics' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="shelves filter-this list items <?php echo $slug ?>">
			<?php include( locate_template( 'sections/loops/critics.php' ) ); ?>	
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>