<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;
	$id = get_the_ID();
	$description = get_field( 'description', $id );
	$event_type = get_field( 'event_type', $id );
	$event_type_pretty = pretty( $event_type );
	$dates = get_event_date( $id );
	$dates = preg_replace("~</br>~", ' ', $dates);
	$event_classes = array('event', 'single');
	
	$end_date_key = get_end_date_value( $id );
	$end_date = get_field( $end_date_key, $id );
	$today = new DateTime();
	$today = $today->format( 'Ymd' );
	if($today > $end_date):
		$event_classes[] = 'past';
	endif;

	$page_columns = get_field( 'page_columns', $id );
	if( $page_columns ):
		$event_classes[] = $page_columns;
	else:
		if( have_rows('gallery') ):
			$event_classes[] = 'two_col';
		else:
			$event_classes[] = 'one_col';
		endif;		
	endif;

?>
<section <?php post_class( $event_classes ) ?> id="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<div class="content">
		<h4 class="title orange">
			<?php echo $event_type_pretty; ?>
			</br>
			<?php echo $dates; ?>
		</h4>	

		<h1 class="title"><?php echo $title ?></h1>


		<?php if( $page_columns == 'one_col' ): ?>
			<?php
				if( have_rows('gallery') ):
					echo '<div class="image_slider">';
					echo '<div class="left arrow"></div>';
					echo '<div class="right arrow"></div>';
					echo '<div class="slides">';
				    while ( have_rows('gallery') ) : the_row();

				        $gallery_image = get_sub_field( 'image' )['url'];
				        $image_artist = get_sub_field( 'artist' );
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

						echo '<div class="slide piece">';
						echo '<div class="vert">';
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

		<?php endif; ?>

		<div class="info">
			<div class="description">
				<?php echo $description ?>
			</div>
			<div class="links">
				<a href="#" class="link bullet">RSVP</a>
				<a href="#" class="link bullet">Add to calendar</a>
				<a href="#" class="link bullet">Share</a>
			</div>

			<?php
				$events = get_posts(array(
					'post_type' => 'event',
					'meta_query' => array(
						array(
							'key' => 'events',
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

		<?php if( $page_columns == 'two_col' ): ?>
			<div class="gallery">
			<?php
				if( have_rows('gallery') ):
				    while ( have_rows('gallery') ) : the_row();

				        $gallery_image = get_sub_field( 'image' )['url'];
				        $image_artist = get_sub_field( 'artist' );
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
				        echo '<div class="caption">';
				        echo $caption;
				        echo '</div>';
				        echo '</div>';

				    endwhile;

				else :

				    // no rows found

				endif;
			?>

			</div>
		<?php endif; ?>
		
		<?php 
		$residents = get_field( 'residents' );
		if( $residents ):
			echo '<div class="residents shelves grid">';
			echo '<h4>Participating Residents</h4>';
			echo '<div class="inner">';
			foreach( $residents as $resident ): 
				$resident_id = $resident->ID;
				$resident_name = get_the_title( $resident_id );
				$resident_country = ucwords( get_field('country_temp', $resident_id ) );
				$resident_sponsor = ucwords( get_field('sponsor_temp', $resident_id ) );
				$resident_start_date = get_field('residency_dates_0_start_date', $resident_id );
				$resident_year = new DateTime($resident_start_date);
				$resident_year = $resident_year->format('Y');
				$resident_url =  get_the_permalink( $resident_id );
				$thumb = get_thumb( $resident_id );
				if( is_alumni( $id ) ) {
					$resident_status = 'Current';
					$resident_studio = get_field('studio_number', $resident_id );
				} else {
					$resident_status = 'Alumni';
				}

				echo '<div class="resident shelf-item event"><div class="inner">';
				echo '<a href="' . $resident_url . '">';
				echo '<h3 class="name">' . $resident_name . '</h3>';
				echo '<div class="image">';
				echo '<img src="' . $thumb . '"/>';
				echo '</div>';
				echo '</a>';
				echo '<div class="details">';
				echo '<div class="left half">';
				echo '<div class="detail country">' . $resident_country . '</div>';
				echo '<div class="detail sponsor">' . $resident_sponsor . '</div>';
				echo '</div>';
				echo '<div class="right half">';
				echo '<div class="detail status">' . $resident_status . ' Resident</div>';
				if( $resident_studio ) {
					echo '<div class="detail studio">Studio #' . $resident_studio . ' Resident</div>';
				}
				echo '</div></div></div></div>';

			endforeach;
			echo '</div>';
			echo '</div>';
		endif;
		?>

		<div class="upcoming shelves grid">
			<h4>Upcoming Events &amp; Exhibitions</h4>
			<div class="inner">
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
					    'order' => 'ASC',
					    'post__not_in' => array($id)
					);

					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) : $loop->the_post();
						$upcoming_id = $the_ID;
						$upcoming_title = get_the_title( $upcoming_id );
						$upcoming_url = get_permalink( $upcoming_id );
						$upcoming_type = get_field('event_type', $upcoming_id);
						$upcoming_dates = get_event_date( $upcoming_id );
						$upcoming_dates = preg_replace("~</br>~", ' ', $dates);
						$upcoming_thumb = get_thumb( $upcoming_id );
						echo '<div class="event shelf-item event' . $upcoming_type . '"><div class="inner">';
						echo '<a href="' . $upcoming_url . '">';
						echo '<h3 class="name">' . $upcoming_title . '</h3>';
						echo '<div class="image">';
						echo '<img src="' . $upcoming_thumb . '"/>';
						echo '</div>';
						echo '</a>';
						echo '<div class="details">';
						echo '<div class="detail dates">' . $upcoming_dates . '</div>';
						// echo '<div class="detail extra">' . $resident_sponsor . '</div>';
						echo '</div></div></div>';
					endwhile;
				?>
			</div>
		</div>

	</div>
	<?php get_template_part('partials/footer') ?>
</section>
