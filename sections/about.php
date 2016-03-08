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
			$image_slider = get_field( 'image_slider', $id );
			if( $image_slider ):
				echo '<div class="module">';
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
				$internships_id = get_page_by_path( 'about/internships' )->ID;
				$internships_permalink = get_the_permalink( $internships_id );
			?>
			<div class="info module" id="contact">
				<div class="half left">
					<div class="address">
						<?php echo $address ?>
					</div>
					<div class="bullet phone">
						<?php echo '<a href="tel:' . $phone . '">' . $phone . '</a>'; ?>
					</div>
					<div class="bullet email">
						<?php echo '<a href="mailto:' . $email . '">' . $email . '</a>'; ?>
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
					<a class="bullet" href="<?php echo $internships_permalink ?>">Internships</a>
				</div>
			</div>

			<?php 
			$history = get_field( 'history', $id );
			if( $history ):
				echo '<div class="module history" id="history">';
				echo '<h4 class="title orange">History</h4>';
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
			echo '<h4 class="title orange">Staff</h4>';
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
			echo '<h4 class="title orange">Board</h4>';
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