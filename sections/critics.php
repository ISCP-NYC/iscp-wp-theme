<?php
global $post;
$id = $post->ID;
$slug = $post->post_name;
$title = $post->post_title;
$paged = 1;
$intro_image = get_field( 'intro_image', $id );
$intro_text = get_field( 'intro_text', $id );
?>
<section <?php section_attr( $id, $slug, 'critics' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h1 class="head"><?php echo $title ?></h1>
		<?php if ( $intro_image || $intro_text ):
			echo '<div class="intro module">';
			if ( !empty($intro_image) ):
				echo '<figure class="hero">';
				echo wp_get_attachment_image( $intro_image, 'full' );
				echo '<figcaption>' . wp_get_attachment_caption( $intro_image ) . '</figcaption>';
				echo '</figure>';
			endif;
			if ( !empty($intro_text) ):
				echo $intro_text;
			endif;
			echo '</div>';
		endif; ?>
		<div class="shelves filter-this list items <?php echo $slug ?>">
			<?php include( locate_template( 'sections/loops/critics.php' ) ); ?>	
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>