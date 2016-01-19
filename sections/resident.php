<?php 
	$resident_id = get_the_ID();
	$resident_slug = $post->post_name;
	$resident = get_post( $resident_id );
	$country = ucwords( get_field( 'country_temp', $resident_id ) );
	$name = get_the_title();
	$bio = get_field( 'bio', $resident_id );
	$website = get_field( 'website', $resident_id );
	$wbst = implode( '/', array_slice(explode('/', preg_replace('/https?:\/\/|www./', '', $website ) ), 0, 1));
	$studio_number = get_field( 'studio_number', $resident_id );
	$resident_type = ucfirst( get_field( 'resident_type', $resident_id ) );
	$residencies = array();
 
	$resident_classes = 'resident single';
	if( is_alumni( $resident_id ) ):
		$resident_classes .= ' alumni';
	endif;
	if( have_rows( 'gallery' ) == false ):
		$resident_classes .= ' one_col';
	endif;
?>

<section <?php section_attr( $resident_id, $resident_slug, $resident_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<header class="sub">
			<div class="left">
				<h4 class="country"><?php echo $country ?></h4>
			</div>

			<div class="center">
				<?php
				if( have_rows( 'residency_dates', $resident_id ) ):
			    	while ( have_rows( 'residency_dates' ) ) : the_row();
						$start_date_dt = new DateTime( get_sub_field( 'start_date', $resident_id ) );
						$end_date_dt = new DateTime( get_sub_field( 'end_date', $resident_id ) );
						$start_date = $start_date_dt->format( 'M d, Y' );
						$end_date = $end_date_dt->format( 'M d, Y' );
						$year = $start_date_dt->format( 'Y' );
						$sponsors = get_sub_field( 'sponsors', $resident_id );
						$residency_object = (object) array(
							'start_date_dt' => $start_date_dt,
							'end_date_dt'   => $end_date_dt,
							'start_date'    => $start_date,
							'end_date'      => $end_date,
							'date_range'    => $start_date . ' — ' . $end_date,
							'sponsors'		=> $sponsors,
							'year'			=> $year
						);
						array_push( $residencies, $residency_object );
					endwhile;
				endif;
				if( is_alumni( $resident_id ) ):
					echo 'Alumni ';
					foreach ($residencies as $index=>$residency):
						if( $index != 0 ):
							echo ', ';
						endif;
						$year = $residency->year;
						echo $year;
						echo '</br>';
						echo get_sponsors( $resident_id );
					endforeach;
				elseif( is_current( $resident_id ) ):
					$current_residency = $residencies[0];
					$date_range = $current_residency->date_range;
					echo 'Current Resident: ' . $date_range;
					echo '</br>';
					echo get_sponsors( $resident_id );
				endif; ?>
			</div>

			<div class="right">
				<?php if( is_current( $resident_id ) ): 
					echo '<h4 class="studio-number">Studio ' . $studio_number . '</h4>';
				endif; ?>
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
							'value' => '"' . $resident_id . '"',
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

		<?php
		if( have_rows( 'gallery' ) ):
			echo '<div class="gallery">';
			echo '<div class="cursor"></div>';
			echo '<div class="images slides">';
		    while ( have_rows( 'gallery' ) ) : the_row();
		        $gallery_image = get_sub_field( 'image' )['url'];
		        $caption = label_art( $the_ID );
		        echo '<div class="piece slide">';
		        echo '<div class="inner">';
		        echo '<div class="image">';
		        echo '<img src="' . $gallery_image . '"/>';
		        echo '</div>';
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
		    endwhile;
		    echo '</div>';
		    echo '</div>';
		endif;
		?>

		<div class="relations border-top shelves grid">
			<?php
			if( is_ground_floor( $resident_id ) ):
				echo '<h4>Ground Floor Residents</h4>';
				$meta_query = array(
					'key' => 'residency_program',
					'value' => 'ground_floor',
					'compare' => 'LIKE'
				);
			else:
				echo '<h4>Residents from ' . $country . '</h4>';
				$meta_query = array(
					'key' => 'country_temp',
					'value' => $country,
					'compare' => 'LIKE'
				);
			endif;
			?>
			<div class="inner">
				<?php 
				$residents = get_posts(array(
					'posts_per_page' => 3,
					'post__not_in' => array($resident_id),
					'post_type'	=> 'resident',
					'meta_query' => array( $meta_query )
				));
				foreach( $residents as $related ): 
					$related_id = $related->ID;
					$related_name = get_the_title( $related_id );
					$related_sponsor = get_field('sponsor_temp', $related_id );
					$related_start_date = get_field('residency_dates_0_start_date', $related_id );
					$related_year = new DateTime($related_start_date);
					$related_year = $related_year->format('Y');
					$related_url =  get_the_permalink( $related_id );
					$related_thumb = get_thumb( $related_id );
					echo '<div class="related shelf-item resident"><div class="inner">';
					echo '<a href="' . $related_url . '">';
					echo '<h3 class="name">' . $related_name . '</h3>';
					echo '<div class="image">';
					echo '<img src="' . $related_thumb . '"/>';
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
