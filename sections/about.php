<?php 
	global $post;
	$id = $post->ID;
	$title = $post->post_title;
	$slug = $post->post_name;
	$intro_image = get_field( 'intro_image', $id );
	$intro_text = get_field( 'intro_text', $id );
?>
<section  <?php section_attr( $id, $slug, 'about' ); ?>>
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
		<?php 
			$description = get_field( 'description', $id ); 
			$deia = get_field( 'deia', $id );
			$land_acknowledgement = get_field( 'land_acknowledgement', $id );
		?>

		<div class="main">
			<?php if( !empty($description) ): ?>
			<div class="module description">
				<?php echo $description ?>
			</div>
			<?php endif; ?>
			<?php if( !empty($deia) ): ?>
			<div class="module description">
				<h3 class="orange title">Diversity, Equity, Accessibility and Inclusion</h3>
				<?php echo $deia ?>
			</div>
			<?php endif; ?>
			<?php
				$address = get_field( 'address', $id );
				$directions_base = 'https://www.google.com/maps/dir//';
				$directions = $directions_base . strip_tags( $address );
				$phone = get_field( 'phone', $id );
				$email = get_field( 'email', $id );
				$twitter = get_field( 'twitter', $id );
				$twitter_url = 'http://www.twitter.com/' . $twitter;
				$facebook = get_field( 'facebook', $id );
				$facebook_url = 'http://www.facebook.com/' . $facebook;
				$instagram = get_field( 'instagram', $id );
				$instagram_url = 'http://www.instagram.com/' . $instagram;
				$internships_id = get_page_by_path( 'about/internships' )->ID;
				$internships_permalink = get_the_permalink( $internships_id );
			?>
			<div class="info module" id="contact">
				<div class="half left">
					<h3 class="title orange">Address</h3>
					<p><?php echo $address ?><br /></p>
					<a href="<?php echo $directions ?>" target="_blank">Map &amp; Directions</a>
				</div>
				<div class="half right">
					<h3 class="title orange">Contact</h3>
					<?php
						$contact = get_field( 'contact', $id );
						if( !empty($contact) ):
							echo $contact;
						endif;
					// $children = query_posts(array('post_parent' => $id, 'post_type' => 'page'));
					// foreach( $children as $child ):
					// 	echo '<a class="bullet" href="' . get_permalink( $child->ID ) . '">' . $child->post_title . '</a>';
					// endforeach;
					?>					
				</div>
			</div>

			<?php if( !empty($land_acknowledgement) ): ?>
			<div class="module description">
				<h3 class="orange title">Land Acknowledgement</h3>
				<?php echo $land_acknowledgement ?>
			</div>
			<?php endif; ?>

			<?php 
			$history = get_field( 'history', $id );
			if( $history ):
				echo '<div class="module history" id="history">';
				echo '<h3 class="title orange">History</h3>';
			
				$image_slider = get_field( 'image_slider', $id );
				if( $image_slider ):
					echo '<div class="image_slider gallery">';
					echo '<div class="cursor"></div>';
					if( count( $image_slider ) > 1 ):
						echo '<div class="left arrow swap">';
						echo '<div class="icon default"></div>';
						echo '<div class="icon hover"></div>';
						echo '</div>';
						echo '<div class="right arrow swap">';
						echo '<div class="icon default"></div>';
						echo '<div class="icon hover"></div>';
						echo '</div>';
					endif;
					echo '<div class="slides">';
					while( has_sub_field( 'image_slider', $id ) ):
						$image = get_sub_field( 'image', $id );
								$image_url = $image['url'];
								$caption = get_sub_field( 'caption', $id );
								$orientation = get_orientation( $image['id'] );
								echo '<div class="piece slide">';
								echo '<div class="image ' . $orientation . '">';
								echo '<div class="captionWrap">';
								echo '<img src="' . $image_url . '"/>';
								echo '<div class="caption">';
								echo $caption;
								echo '</div>';
								echo '</div>';
								echo '</div>';
								echo '</div>';
					endwhile;
					echo '</div>';
					echo '</div>';
				endif;
				echo '<div class="text">';
				echo $history;
				echo '</div>';
				echo '</div>';
			endif;
			?>

			<div class="module people" id="staff">
			<?php
			if( get_field( 'staff', $id ) ):
			echo '<div class="staff list half">';
			echo '<h3 class="title orange">Staff</h3>';
			echo '<ul>';
			while( has_sub_field( 'staff', $id ) ):
				$name = get_sub_field( 'name', $id );
				$role = get_sub_field( 'role', $id );
				echo '<li>';
				echo $name;
				echo '<em class="role">' . $role . '</em>';
				echo '</li>';
			endwhile;
			echo '</ul>';
			echo '</div>';
			endif;
			if( get_field( 'board_members', $id ) ):
			echo '<div class="board list half">';
			echo '<h3 class="title orange">Board</h3>';
			echo '<ul>';
			while( has_sub_field( 'board_members', $id ) ):
				$name = get_sub_field( 'name', $id );
				echo '<li>' . $name . '</li>';
			endwhile;
			echo '</ul>';
			echo '</div>';
			endif;
			?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>