<h4 class="title orange"><?php the_title() ?></h4>
<section class="resident single" id="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<?php 
		$id = get_the_ID();
		$sponsor = get_field('sponsor_temp', $id);
		$country = ucwords(get_field('country_temp', $id));
		$name = get_the_title();
		$bio = get_field('bio', $id);
		$website = get_field('website', $id);
		$wbst = implode('/', array_slice(explode('/', preg_replace('/https?:\/\/|www./', '', $website)), 0, 1));
		$studio_number = get_field('studio_number', $id);
		$resident_type = ucfirst(get_field('resident_type', $id));

		$start_date_dt = new DateTime(get_field('residency_dates_0_start_date', $id));
		$end_date_dt = new DateTime(get_field('residency_dates_0_end_date', $id));
		$start_date = $start_date_dt->format('M d, Y');
		$end_date = $end_date_dt->format('M d, Y');
		$dates = $start_date . ' — ' . $end_date;
	?>

	<div class="content">
		<header class="sub">
			<div class="left">
				<h4 class="country"><?php echo $country ?></h4>
			</div>

			<div class="center">
				<h4 class="dates"><?php echo $dates ?></h4>
				<h4 class="sponsor"><?php echo $sponsor ?></h4>
			</div>

			<div class="right">
				<h4 class="studio-number">Studio <?php echo $studio_number ?></h4>
				<h4 class="resident-type"><?php echo $resident_type ?></h4>
			</div>
		</header>

		<h1 class="title"><?php echo $name ?></h1>

		<div class="info">
			<div class="bio">
				<?php echo $bio ?>
				<?php if( $website && $website != '' ): ?>
					<a class="website" href="<?php echo $website ?>">
						<?php echo $wbst; ?>
					</a>
				<?php endif; ?>
			</div>

			<?php
				$events = get_posts(array(
					'post_type' => 'event',
					'meta_query' => array(
						array(
							'key' => 'residents',
							'value' => '"' . $id . '"',
							'compare' => 'LIKE'
						)
					)
				));

				if($events) {
					echo '<div class="events">';
					echo '<h3 class="title">Events &amp; Exhibitions</h3>';
					foreach( $events as $event ): 
						$event_id = $event->ID;
						$event_name =  get_the_title( $event_id );
						$event_url =  get_the_permalink( $event_id );
						echo '<a class="event" href="' . $event_url . '">';
						echo '<div class="name">' . $event_name . '</div>';
						echo '<div class="date">' . format_date( $event_id ) . '</div>';
						echo '</a>';
					endforeach;
					echo '</div>';
				}
			?>
		</div>

		<div class="gallery">
		<?php
			if( have_rows('gallery') ):
			    while ( have_rows('gallery') ) : the_row();

			        $gallery_image = get_sub_field( 'image' )['url'];
			        $image_artist = get_sub_field( 'artist' );
			        if( !$image_artist || $image_artist == '' ) {
			        	$image_artist = $name;
			        }
			        $image_title = get_sub_field( 'title' );
			        $image_year = get_sub_field( 'year' );
			        $image_medium = get_sub_field( 'medium' );
			        $image_dimensions = get_sub_field( 'dimensions' );
			        $image_credit = get_sub_field( 'credit' );

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

			        echo '<div class="piece">';
			        echo '<div class="image">';
			        echo '<img src="' . $gallery_image . '"/>';
			        echo '</div>';
			        echo '<div class="description">';
			        echo $caption;
			        echo '</div>';
			        echo '</div>';

			    endwhile;

			else :

			    // no rows found

			endif;
		?>

		</div>

		<div class="relations border-top shelves grid">
			<h4>Residents from <?php echo $country ?></h4>
			<div class="inner">
				<?php 

					$residents = get_posts(array(
						'posts_per_page'	=> 3,
						'post__not_in' => array($id),
						'post_type'			=> 'resident',
						'meta_query' => array(
							array(
								'key' => 'country_temp',
								'value' => $country,
								'compare' => 'LIKE'
							)
						)
					));
					foreach( $residents as $related ): 
						$related_id = $related->ID;
						$related_name = get_the_title( $related_id );
						$related_sponsor = get_field('sponsor_temp', $related_id );
						$related_start_date = get_field('residency_dates_0_start_date', $related_id );
						$related_year = new DateTime($related_start_date);
						$related_year = $related_year->format('Y');
						$related_url =  get_the_permalink( $related_id );
						$related_image = get_display_image( $related_id );

						echo '<div class="related shelf-item resident"><div class="inner">';
						echo '<a href="' . $related_url . '">';
						echo '<h3 class="name">' . $related_name . '</h3>';
						echo '<div class="image">';
						echo '<img src="' . get_display_image( $related_id ) . '"/>';
						echo '</div>';
						echo '</a>';
						echo '<div class="details">';
						echo '<div class="sponsor">' . $related_sponsor . '</div>';
						echo '<div class="year">' . $related_year . '</div>';
						echo '</div></div></div>';

					endforeach;
				?>
			</div>
		</div>

	</div>
	<?php get_template_part('partials/footer') ?>
</section>
