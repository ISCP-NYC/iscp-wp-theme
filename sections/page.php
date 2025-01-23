<?php 
global $post;
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
$description = get_field( 'description', $id );
$intro_image = get_field( 'intro_image', $id );
$intro_text = get_field( 'intro_text', $id );
if( !$description ):
	$description = get_post( $id )->post_content;
	// $content = $content_post;
	$description = apply_filters('the_content', $description);
	// $description = str_replace(']]>', ']]&gt;', $content);
endif;
?>
<section  <?php section_attr( $id, $slug, $slug ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
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
		<?php if ( $description ):
			echo '<div class="module description">';
			echo $description;
			echo '</div>';
			endif;
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>