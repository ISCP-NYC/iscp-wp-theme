<?php 
global $post;
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
$description = get_post( $id )->post_content;
$description = apply_filters('the_content', $description);
$intro_image = get_field( 'intro_image', $id );
$intro_text = get_field( 'intro_text', $id );
$closing = get_field('closing', $id);
$supporters_post = get_page_by_path('support/support-iscp');
$supporters_id = $supporters_post ? $supporters_post->ID : null;
$supporters_description = $supporters_id ? get_field('description', $supporters_id) : null;
?>
<section <?php section_attr( $id, $slug, $slug ); ?>>
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
		<!-- <div class="module"> -->
			<?php
			// echo $description;
			// if( have_rows( 'opportunities' ) ):
			// 	echo '<div class="bullets">';
			// 		$index = 0;
			// 		while ( have_rows('opportunities') ) : the_row();
			// 			echo '<div class="bullet link">';
			// 				$title = get_sub_field( 'title' );
			// 				echo '<a href="#opportunity_'.$index.'">';
			// 					echo $title;
			// 				echo '</a>';
			// 			echo '</div>';
			// 			$index++;
			// 		endwhile;
			// 	echo '</div>';
			// endif;
			?>

		<!-- </div> -->

		<div class="module">
			<?php
			if( have_rows( 'opportunities' ) ):
				echo '<div class="masonry grid items">';
					echo '<div class="sizer"></div>';
					$index = 0;
					while ( have_rows('opportunities') ) : the_row();
						echo '<div class="item" id="opportunity_' . $index . '">';
							echo '<div class="inner">';
				        $image = get_sub_field( 'image' );
				        $title = get_sub_field( 'title' );
				        $description = get_sub_field( 'description' );
				        $link = get_sub_field( 'link' );
				        $link_text = get_sub_field( 'link_text' );
				        if( $image ):
					        echo '<figure class="image">';
										// echo wp_get_attachment_image( $image, 'thumb' );
						        echo '<img src="' . esc_url($image['url']) . '"/>';
					        echo '</figure>';
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

		<div class="module description">
			<h2 class="head">Supporters</h2>
			<?= $supporters_description; ?>
		</div>
		<div class="module supporters">
			<?php 
				$types = array(
					'found-trust' => 'Foundations &amp; Trusts',
					'individual' => 'Individual',
					'gov' => 'Government',
					'corp' => 'Corporations',
					'probono' => 'In-kind & Pro-Bono Support'
				);
				foreach( $types as $type_slug => $type ) {
					include( locate_template( 'sections/params/supporters.php' ) );
					$supporters = new WP_Query( $supporters_query );
					if( $supporters->post_count ) {
						echo '<h2 class="head">' . $type . '</h2>';
						echo '<div class="supporters shelves filter-this list items">';
							include( locate_template( 'sections/loops/supporters.php' ) );
						echo '</div>';
					}
				}
			?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>