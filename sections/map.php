<?php
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$paged = 1;
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
endif;
?>
<section <?php section_attr( $id, $slug, 'earth' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<!-- <h2 class="head"><?php the_title() ?></h2> -->
		<div id="mapWrap">

		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>