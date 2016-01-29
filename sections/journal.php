<?php 
global $post;
setup_postdata( $post );
$title = get_the_title();
$slug = $post->post_name;
$date = get_the_date('F d, Y');date;
$id = get_the_ID();
$event_classes = 'journal single';
?>
<section <?php section_attr( $id, $slug, $event_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<div class="content">
		<h3 class="title"><?php echo $date ?></h3>	

		<h2 class="title head"><?php echo $title ?></h2>
		<div class="text">
			<?php the_content(); ?>
		</div>

		<?php
		if( have_rows('gallery') ):
			echo '<div class="gallery stack">';
			echo '<div class="cursor"></div>';
			echo '<div class="images slides">';
		    while ( have_rows('gallery') ) : the_row();
		        $image = get_sub_field( 'image' );
		        $image_artist = get_sub_field( 'artist' );
		        $image_title = get_sub_field( 'title' );
		        $image_year = get_sub_field( 'year' );
		        $image_medium = get_sub_field( 'medium' );
		        $image_dimensions = get_sub_field( 'dimensions' );
		        $image_credit = get_sub_field( 'credit' );
		        $image_orientation = get_orientation( $image['id'] );
		        $image_url = $image['url'];
		        $caption = $image_artist;
		        if( $image_title ) {
		        	$caption .= ', <em>' . $image_title . ',</em>';
		        }
		        if( $image_year ) {
		        	$caption .= ' ' . $image_year;
		        }
		        if( $image_medium ) {
		        	$caption .= ', ' . $image_medium;
		        }
		        if( $image_dimensions ) {
		        	$caption .= ', ' . $image_dimensions;
		        }

		        echo '<div class="piece slide">';
		        echo '<div class="inner">';
		        echo '<div class="image ' . $image_orientation . '">';
		        echo '<div class="wrap">';
		        echo '<img src="' . $image_url . '"/>';
		        echo '</div>';
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';

		    endwhile;
		endif;
		echo '</div>';
		echo '</div>';
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
