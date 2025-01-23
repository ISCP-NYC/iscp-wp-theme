<?php 
global $post;
setup_postdata( $post );
$title = get_the_title();
$slug = $post->post_name;
$id = get_the_ID();
$content = apply_filters( 'the_content', $post->post_content );
$date = get_the_date('F d, Y');
$featured_image = get_thumb( $id, null );
$featured_image_id = get_post_thumbnail_id();
$event_classes = 'journal-post single';
$author_f = get_the_author_meta('first_name');
$author_l = get_the_author_meta('last_name');
$author = $author_f . ' ' . $author_l;
$images = get_attached_media( 'image', $id );
$show_featured_image = get_field( 'show_featured_image' );
if( get_field( 'author' ) ):
  $author = get_field( 'author' );
endif;
$tags = get_the_tags();
?>
<section <?php section_attr( $id, $slug, $event_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<div class="content">
		<?php
			$tags_count = $tags ? count( $tags ) - 1 : null;
			$tag_index = 0;
			if( $tags ):
				echo '<div class="tags">';
				foreach( $tags as $tag ):
					$tag_name = $tag->name;
					$tag_slug = $tag->slug;
					$tag_url = site_url( '/journal/?tag=' . $tag_slug );
					echo '<a href="' . $tag_url . '">' . $tag_name . '</a>';
					if( $tag_index < $tags_count ):
						echo ', ';
					endif;
					$tag_index++;
				endforeach;
				echo '</div>';
			endif;
		?>
		<h2 class="title head"><?php echo $title ?></h2>
		<div class="inner">
			<div class="header">
				<?php if ($featured_image && $show_featured_image): ?>
					<div class="image">
						<img src="<?php echo $featured_image ?>"/>
					</div>
				<?php endif; ?>
				<h3 class="title date"><?php echo $date ?></h3>	
				<h3 class="title author">by <?php echo $author ?></h3>
			</div>
			<div class="text">
				<?php echo $content; ?>
			</div>

			<?php if( have_rows('gallery') ):
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
				  echo '</div>';
			  echo '</div>';
			endif; ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
