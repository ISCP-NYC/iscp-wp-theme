<?php 
	global $post;
	$id = $post->ID;
	$title = $post->post_title;
	$slug = $post->post_name;
?>

<section  <?php section_attr( $id, $slug, 'support about' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>

		<?php
			$description = get_field( 'description', $id );
		?>

		<div class="main">
			<div class="module description">
				<?php echo $description ?>
			</div>
			<?php
			if( get_field( 'image_slider', $id ) ):
				echo '<div class="module">';
				echo '<div class="image_slider gallery">';
				echo '<div class="cursor"></div>';
				echo '<div class="left arrow"></div>';
				echo '<div class="right arrow"></div>';
				echo '<div class="slides">';
				while( has_sub_field( 'image_slider', $id ) ):
					$image = get_sub_field( 'image', $id );
			        $image_url = $image['url'];
			        $image_id = $image['id'];
			        $orientation = get_orientation( $image_id );
			        $caption = label_art( $image_id );
			        echo '<div class="piece slide">';
			        echo '<div class="inner">';
			        echo '<div class="image ' . $orientation . '">';
			        echo '<div class="wrap">';
			        echo '<img src="' . $image_url . '" alt="' . $image_caption . '"/>';
			        echo '<div class="caption">';
			        echo $caption;
			        echo '</div>';
			        echo '</div>';
			        echo '</div>';
			        echo '</div>';
			        echo '</div>';
				endwhile;
				echo '</div>';
				echo '</div>';
				echo '</div>';
			endif;
			?>

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
			?>
			<div class="info module">
				<div class="half left">
					<div class="address">
						<?php echo $address ?>
					</div>
					<div class="phone">
						<?php echo $phone ?>
					</div>
					<div class="email">
						<?php echo $email ?>
					</div>
					<div class="bullet facebook">
						Facebook
						<a href="<?php echo $facebook_url ?>" target="_blank">
							<?php echo $facebook ?>
						</a>
					</div>
					<div class="bullet twitter">
						Twitter 
						<a href="<?php echo $twitter_url ?>" target="_blank">
							&commat;<?php echo $twitter ?>
						</a>
					</div>
					<div class="bullet instagram">
						Instagram
						 <a href="<?php echo $instagram_url ?>" target="_blank">
						 	&commat;<?php echo $instagram ?>
						</a>
					</div>
				</div>
				<div class="half right">
					<a class="bullet" href="<?php echo $directions ?>" target="_blank">Map &amp; Directions</a>
					<a class="bullet" href="<?php echo $newsletter ?>" target="_blank">Newsletter Sign-Up</a>
					<a class="bullet" href="#" target="_blank">Internships</a>
					<a class="bullet" href="#" target="_blank">Space Rental</a>
				</div>
			</div>

			<?php $history = get_field( 'history', $id ); ?>
			<div class="module history">
				<?php echo $history ?>
			</div>

			<div class="module people">
			<?php
			if( get_field( 'board_members', $id ) ):
			echo '<div class="board list">';
			echo '<h4 class="orange">Board</h4>';
			echo '<ul>';
			while( has_sub_field( 'board_members', $id ) ):
				$name = get_sub_field( 'name', $id );
				echo '<li>' . $name . '</li>';
			endwhile;
			echo '</ul>';
			echo '</div>';
			endif;

			if( get_field( 'staff', $id ) ):
			echo '<div class="staff list">';
			echo '<h4 class="orange">Staff</h4>';
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
			?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>