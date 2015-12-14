<?php get_header(); ?>

<section id="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h3 class="title">International Studio &amp; Curitorial Program</h3>

		<h3 class="title">
			<a href="/events">
				Events &amp; Exhibitions
			</a>
		</h3>

		<div class="events upcoming">
			<?php 
				$today = new DateTime();
				$today = $today->format('Y-m-d H:i:s');
				$args = array(
					'post_type' => 'event',
					'posts_per_page' => 3,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'end_date',
							'compare' => '>=',
							'value' => $today,
							'type' => 'DATE',
							'orderby' => 'meta_value',
							'order' => 'DESC'
						),
						array(
							'key' => 'date',
							'compare' => '>=',
							'value' => $today,
							'type' => 'DATE',
							'orderby' => 'meta_value',
							'order' => 'DESC'
						)
					),
					'orderby' => 'meta_value',
				    'order' => 'ASC'
				);

				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
					$id = $the_ID;
					$slug = get_post( $id )->post_name;
					$title = get_the_title( $id );
					$url = get_permalink();
					$type = get_field('event_type', $id);
					$date_format = get_event_date( $id );

					$thumb = get_thumb( $id );

					echo '<div class="event ' . $type . '" id="' . $slug . '">';
						echo '<a href="' . $url . '">';
					  		echo '<h2 class="date">';
					  			echo $date_format;
					  		echo '</h2>';
					  		echo '<div class="thumb">';
					  		echo '<img src="' . $thumb . '"/>';
					  		echo '</div>';
					  		echo '<h4 class="type">' . $type_name . '</h4>';
					  		echo '<h4 class="title">' . $title . '</h4>';
					  	echo '</a>';
					echo '</div>';
				endwhile;
			?>
		</div>	

		<?php
			$about = get_page_by_path( 'about' );
			$about_id = $about->ID;
			$mission = get_field('mission', $about_id);
			$address = get_field('address', $about_id);
		?>

		<div class="mission border-top border-bottom">
			<h3 class="title orange"><?php echo $address ?></h3>
			<p class="large"><?php echo $mission ?></p>
		</div>


		<?php
		$home = get_page_by_path( 'home' );
		if( get_field( 'image_slider', $home ) ):
			echo '<div class="image_slider module">';
			echo '<div class="left arrow"></div>';
			echo '<div class="right arrow"></div>';
			echo '<div class="slides">';
			while( has_sub_field( 'image_slider', $home ) ):
				$image = get_sub_field( 'image', $home );
				$image_url = $image['url'];
				echo '<div class="slide">';
				echo '<div class="vert">';
				echo '<div class="image">';
				echo '<img src="' . $image_url . '" alt=""/>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			endwhile;
			echo '</div>';
			echo '</div>';
		endif;
		?>


		<?php
			$residency_program = get_page_by_path( 'residency-programs' );
			$residency_tagline = get_field('tagline', $residency_program);
			$residency_tagline .= ' <a href="#">Learn more.</a>';
		?>

		<div class="residency_program border-top border-bottom">
			<h3 class="title orange">Residency Program</h3>
			<p class="xlarge"><?php echo $residency_tagline ?></p>
		</div>

		<? get_tweets( 3 ); ?>

		<?php
			$facebook_url = get_field( 'facebook', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
		?>

		<div class="social border-top border-bottom">
			<div class="link medium facebook half-border-right">
				<a href="<?php echo $facebook_url ?>" target="_blank">Facebook</a>
			</div>
			<div class="link medium instagram half-border-left">
				<a href="<?php echo $instagram_url ?>" target="_blank">Instagram</a>
			</div>
		</div>

		<?php
			$newsletter_url = get_field( 'newsletter', $about_id );
		?>

		<h3 class="newsletter title orange">
			<a href="<?php echo $newsletter_url ?>" target="_blank">
				Subscribe for our newsletter
			</a>
		</h3>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>

<?php get_footer(); ?>
