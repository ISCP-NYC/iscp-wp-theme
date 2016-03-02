<?php 
$resident_id = get_the_ID();
$resident_slug = $post->post_name;
$resident = get_post( $resident_id );
$countries = get_countries( $resident_id );
$countries_array = get_field( 'country', $resident_id );
$name = get_the_title();
$bio = get_field( 'bio', $resident_id );
$website = get_field( 'website', $resident_id );
$studio_number = get_field( 'studio_number', $resident_id );
$resident_type = ucfirst( get_field( 'resident_type', $resident_id ) );
$residencies = array();
$resident_classes = 'resident single';
$past_residents_id = get_page_by_path( 'past-residents' )->ID;
$past_residents_url = preg_replace( '{/$}', '', get_permalink( $past_residents_id ) );
if( is_past( $resident_id ) ):
	$resident_classes .= ' past';
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
				<?php
				if($country):
					$country_url = $past_residents_url . '?filter=past&from=' . $country_slug;
					echo '<h2>';
					echo '<a href="' . $country_url . '">';
					echo $country_title;
					echo '</a>';
					echo '</h2>';
				endif;
				?>
			</div>
			<div class="center">
				<?php
				if( have_rows( 'residency_dates', $resident_id ) ):
			    	while ( have_rows( 'residency_dates' ) ) : the_row();
						$start_date_dt = new DateTime( get_sub_field( 'start_date', $resident_id ) );
						$end_date_dt = new DateTime( get_sub_field( 'end_date', $resident_id ) );
						$start_date = $start_date_dt->format( 'M n, Y' );
						$end_date = $end_date_dt->format( 'M n, Y' );
						$year = $start_date_dt->format( 'Y' );
						$sponsors = get_sub_field( 'sponsors', $resident_id );
						$residency_object = (object) array(
							'start_date_dt' => $start_date_dt,
							'end_date_dt'   => $end_date_dt,
							'start_date'    => $start_date,
							'end_date'      => $end_date,
							'date_range'    => $start_date . '&ndash;' . $end_date,
							'sponsors'		=> $sponsors,
							'year'			=> $year
						);
						array_push( $residencies, $residency_object );
					endwhile;
				endif;
				if( is_past( $resident_id ) ):
					echo '<h2>';
					echo 'Past Resident: ';
					foreach ($residencies as $index=>$residency):
						if( $index != 0 ):
							echo ', ';
						endif;
						$year = $residency->year;
						echo $year;
						echo '</br>';
						echo get_sponsors( $resident_id );
					endforeach;
					echo '</h2>';
				elseif( is_current( $resident_id ) ):
					$current_residency = $residencies[0];
					$date_range = $current_residency->date_range;
					echo '<h2>';
					echo 'Current Resident: ' . $date_range;
					echo '</br>';
					echo '</h2>';
					echo '<h2>';
					echo get_sponsors( $resident_id );
					echo '</h2>';
				endif;
				?>
			</div>

			<div class="right">
				<?php
				if( is_current( $resident_id ) ): 
					if( is_ground_floor( $resident_id )  ):
						$ground_floor_url = $past_residents_url . '?filter=past&program=ground_floor';
						echo '<h2 class="value">';
						echo '<a href="' . $ground_floor_url . '">';
						echo 'Ground Floor';
						echo '</a>';
						echo '</h2>';
					else:
						echo '<h2 class="studio-number">Studio #' . $studio_number . '</h2>';
					endif;
				endif;
				?>
				<h2 class="resident-type"><?php echo $resident_type ?></h2>
			</div>
		</header>

		<h1 class="title"><?php echo $name ?></h1>

		<div class="info">
			<div class="bio">
				<?php echo $bio ?>
				<?php if( $website && $website != '' && is_current( $resident_id ) ): ?>
					<a class="website bullet" href="<?php echo $website ?>">
						<?php echo pretty_url( $website ) ?>
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

			if($events):
				echo '<div class="events">';
				echo '<h3 class="title">Events &amp; Exhibitions</h3>';
				foreach( $events as $event ): 
					$event_id = $event->ID;
					$event_name =  get_the_title( $event_id );
					$event_url =  get_the_permalink( $event_id );
					echo '<a class="event" href="' . $event_url . '">';
					echo '<div class="name">' . $event_name . '</div>';
					echo '<div class="date">' . get_event_date( $event_id ) . '</div>';
					echo '</a>';
				endforeach;
				echo '</div>';
			endif;
			?>
		</div>

		<?php
		if( have_rows( 'gallery' ) ):
			echo '<div class="gallery stack">';
			echo '<div class="cursor"></div>';
			echo '<div class="images slides">';
		    while ( have_rows( 'gallery' ) ) : the_row();
		        $image = get_sub_field( 'image' );
		        if( $image ):
			        $image_url = $image['url'];
			        $orientation = get_orientation( $image['id'] );
			        $caption = label_art( $the_ID );
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
			    endif;
		    endwhile;
		    echo '</div>';
		    echo '</div>';
		endif;
		?>

		<?php
		if( is_ground_floor( $resident_id ) ):
			$relation_title = '<h4>Ground Floor Residents</h4>';
			$meta_query = array( array(
				'key' => 'residency_program',
				'value' => 'ground_floor',
				'compare' => 'LIKE'
			) );
		elseif( $countries_array ):
			$meta_query = array(
				'relation' => 'OR'
			);
			foreach ($countries_array as $key => $country) {
				$country_name = $country->post_title;
				$country_id = $country->ID;
				$country_ids[] .= $country_id;
				if( $key != 0 ):
					$country_names .= ', ';
				endif;
				$country_names .= $country_name;
				$country_query = array(
					'key' => 'country',
					'value' => $country_id,
					'compare' => 'LIKE'
				);
				array_push( $meta_query, $country_query );
			}
			$relation_title = '<h4>Residents from ' . $country_names . '</h4>';
		endif;
		$residents_query = array(
			'post_type'	=> 'resident',
			'posts_per_page' => 3,
			'post__not_in' => array( $resident_id ),
			'meta_query' => $meta_query
		);
		$residents = new WP_Query( $residents_query );
		$GLOBALS['wp_query'] = $residents;
		if( $residents->have_posts() && $meta_query ):
			echo '<div class="relations border-top">';
			echo '<h3 class="title">' . $relation_title . '</h3>';
			echo '<div class="inner residents shelves grid">';
			while ( $residents->have_posts() ) : 
				the_post();
				get_template_part( 'sections/items/resident' );
			endwhile;
			echo '</div>';
			echo '</div>';
		endif;
		wp_reset_query(); 
		?>

	</div>
	<?php get_template_part('partials/footer') ?>
</section>