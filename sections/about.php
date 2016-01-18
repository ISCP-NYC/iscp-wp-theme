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
		<h4 class="title orange"><?php echo $title ?></h4>

		<?php
			$address = get_field( 'address', $id );
			$directions_base = 'https://www.google.com/maps/dir//';
			$directions = $directions_base . strip_tags($address);
			$phone = get_field( 'phone', $id );
			$email = get_field( 'email', $id );
		?>
		<div class="contact module">
			<div class="half left">
				<div class="address">
					<?php echo $address ?>
				</div>
				<a href="<?php echo $directions ?>" target="_blank">Directions</a>
			</div>
			<div class="half right">
				<div class="phone">
					<?php echo $phone ?>
				</div>
				<div class="email">
					<?php echo $email ?>
				</div>
			</div>
		</div>

		<?php
			$mission = get_field( 'mission', $id );
			$description = get_field( 'description', $id );
		?>

		<div class="main">
			<div class="half left">
				<div class="module mission">
					<?php echo $mission ?>
				</div>
				<?php
				if( get_field( 'image_slider', $id ) ):
					echo '<div class="image_slider module">';
					echo '<div class="left arrow"></div>';
					echo '<div class="right arrow"></div>';
					echo '<div class="slides">';
					while( has_sub_field( 'image_slider', $id ) ):
						$image = get_sub_field( 'image', $id );
						$image_url = $image['url'];
						$caption = label_art( $the_ID );
						echo '<div class="slide">';
						echo '<div class="vert">';
						echo '<div class="image">';
						echo '<img src="' . $image_url . '" alt=""/>';
						echo '</div>';
						echo '<div class="caption">' . $caption . '</div>';
						echo '</div>';
						echo '</div>';
					endwhile;
					echo '</div>';
					echo '</div>';
				endif;
				?>

				<?php 
				$twitter = get_field( 'twitter', $id );
				$twitter_url = 'http://www.twitter.com/' . $twitter;
				$facebook_url = get_field( 'facebook', $id );
				$instagram = get_field( 'instagram', $id );
				$instagram_url = 'http://www.instagram.com/' . $instagram;
				?>
				<div class="module social">
					<? get_tweets( 1 ); ?>

					<h3 class="facebook">
						Find us on <a href="<?php echo $facebook_url ?>" target="_blank">Facebook</a>
					</h3>
					<h3 class="instagram">
						Follow us on <a href="<?php echo $instagram_url ?>" target="_blank">Instagram</a>
					</h3>
				</div>
			</div>
			<div class="half right">
				<?php
				if( get_field( 'image_stack', $id ) ):
				echo '<div class="image_stack module">';
				while( has_sub_field( 'image_stack', $id ) ):
					$image = get_sub_field( 'image', $id );
					$image_url = $image['url'];
					$caption = get_sub_field( 'caption', $id );
					echo '<div class="image">';
					echo '<img src="' . $image_url . '" alt=""/>';
					echo '<h4 class="caption">' . $caption . '</h4>';
					echo '</div>';
				endwhile;
				echo '</div>';
				endif;
				?>

				<?php $description = get_field( 'description', $id ); ?>
				<div class="module description">
					<?php echo $description ?>
				</div>
				
				<?php
				if( get_field( 'board_members', $id ) ):
				echo '<div class="module">';
				echo '<div class="board list">';
				echo '<h4>Board</h4>';
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
				echo '<h4>Staff</h4>';
				echo '<ul>';
				while( has_sub_field( 'staff', $id ) ):
					$name = get_sub_field( 'name', $id );
					$role = get_sub_field( 'role', $id );
					echo '<li>' . $name . ', ' . $role . '</li>';
				endwhile;
				echo '</ul>';
				echo '</div>';

				echo '</div>';
				endif;
				?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>