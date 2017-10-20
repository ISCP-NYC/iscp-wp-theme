<?php 
global $post;
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
$description = get_post( $id )->post_content;
$description = apply_filters('the_content', $description);
$closing = get_field('closing', $id);
?>
<section  <?php section_attr( $id, $slug, $slug ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="module">
			<?php
			echo $description;
			if( have_rows( 'opportunities' ) ):
				echo '<div class="bullets">';
					$index = 0;
					while ( have_rows('opportunities') ) : the_row();
						echo '<div class="bullet link">';
							$title = get_sub_field( 'title' );
							echo '<a href="#opportunity_'.$index.'">';
								echo $title;
							echo '</a>';
						echo '</div>';
						$index++;
					endwhile;
				echo '</div>';
			endif;
			?>

		</div>

		<div class="module">
			<?php
			if( have_rows( 'opportunities' ) ):
				echo '<div class="masonry grid items">';
					echo '<div class="sizer"></div>';
					$index = 0;
					while ( have_rows('opportunities') ) : the_row();
						echo '<div class="item" id="opportunity_' . $index . '">';
							echo '<div class="inner">';
				        $image = get_sub_field( 'image' )['sizes']['thumb'];
				        $title = get_sub_field( 'title' );
				        $description = get_sub_field( 'description' );
				        $link = get_sub_field( 'link' );
				        $link_text = get_sub_field( 'link_text' );
				        if( $image ):
					        echo '<div class="image">';
						        echo '<img src="' . $image . '"/>';
					        echo '</div>';
						    endif;
				        echo '<h3 class="title">' . $title . '</h3>';
				        echo '<div class="description">' . $description . '</div>';
				        if( $link && $link_text ) {
				        	echo '<a class="donate" href="' . $link . '" target="_blank">' . $link_text . '</a>';
				        }
			        echo '</div>';
			      echo '</div>';
			      $index++;
					endwhile;
				echo '</div>';
			endif;
			?>
		</div>

		<div class="module">
			<?php echo $closing; ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>