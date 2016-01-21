<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;
	$id = get_the_ID();
	$description = get_field( 'description', $id );
	$footnote = get_field( 'footnote', $id );
	$event_type = get_field( 'event_type', $id );
	$event_type_pretty = pretty( $event_type );
	$dates = get_event_date( $id );
	$dates = preg_replace("~</br>~", ' ', $dates);
	$event_classes = 'event single';
	
	$end_date_key = get_end_date_value( $id );
	$end_date = get_field( $end_date_key, $id );
	$today = new DateTime();
	$today = $today->format( 'Ymd' );
	if($today > $end_date):
		$event_classes .= ' past';
	endif;

	$page_columns = get_field( 'page_columns', $id );
	if( $page_columns ):
		$event_classes .= ' ' . $page_columns;
	else:
		if( have_rows('gallery') ):
			$event_classes .= ' cols_2';
		else:
			$event_classes .= ' cols_1';
		endif;		
	endif;

?>
<section <?php section_attr( $id, $slug, $event_classes ); ?>>
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
			<div class="footnote">
				<?php echo $footnote ?>
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
			<div class="gallery stack">
			<div class="cursor"></div>
				<div class="images slides">
				<?php
					if( have_rows('gallery') ):
					    while ( have_rows('gallery') ) : the_row();
					        $image = get_sub_field( 'image' );
					        $image_artist = get_sub_field( 'artist' );
					        $image_title = get_sub_field( 'title' );
					        $image_year = get_sub_field( 'year' );
					        $image_medium = get_sub_field( 'medium' );
					        $image_dimensions = get_sub_field( 'dimensions' );
					        $image_credit = get_sub_field( 'credit' );
					        $image_orientation = get_orientation( $image['id'] );
					        $image_url = $image['url'];
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

					        echo '<div class="piece slide">';
					        echo '<div class="inner">';
					        echo '<div class="image ' . $image_orientation . '">';
					        echo '<div class="wrap">';
					        echo '<img src="' . $image_url . '"/>';
					        echo '</div>';
					        echo '<div class="caption">';
					        echo $caption;
					        echo '</div>';
					        echo '</div>';
					        echo '</div>';
					        echo '</div>';

					    endwhile;
					endif;
				?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php 
		$residents = get_field( 'residents' );
		if( $residents ):
			echo '<div class="residents shelves grid">';
			echo '<h4>Participating Residents</h4>';
			echo '<div class="inner">';
			foreach( $residents as $resident ): 
				$post = $resident;
				get_template_part( 'items/resident' );
			endforeach;
			echo '</div>';
			echo '</div>';
		endif;
		?>
		<div class="events">
			<h4>Upcoming Events &amp; Exhibitions</h4>
			<div class="shelves grid">
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
					get_template_part( 'items/event' );
				endwhile;
				?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
