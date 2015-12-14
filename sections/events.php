<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;
?>

<section id="<?php echo $slug ?>" class="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php the_title() ?></h4>
		<div class="filter">
			<div class="bar">
				<div class="select dropdown event-type" data-filter="event-type" data-slug="<?php echo $slug ?>">Event Type</div>
				<div class="select dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">Year</div>
				<div class="select view toggle" data-slug="<?php echo $slug ?>">
					<span class="list">List</span>
					<span class="grid">Grid</span>
				</div>
			</div>
			<div class="filter-list sub event-type <? echo $slug ?>">
				<?php
					$page_url = get_the_permalink();
					$event_types = array( 'event', 'exhibition', 'off-site-project', 'iscp-talk', 'open-studios' );
					foreach( $event_types as $event_type ): 
						$event_type = pretty( $event_type );
						$filter_url =  $page_url . '?event_type=' . $event_type;
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo ucwords( $event_type );
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
			</div>

			<div class="filter-list sub year <? echo $slug ?>">
				<?php
					$page_url = get_the_permalink();
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '?when=' . $year;
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo $year;
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
			</div>
		</div>

		<div class="events shelves filter-this grid <? echo $slug ?>">
			<?php
				$country = get_query_var( 'country_temp' );
				$year = get_query_var( 'when' );

				if( $country ) {
					$filter_key = 'country_temp';
					$filter_query = array(
						'key' => 'country_temp',
						'type' => 'CHAR',
						'value' => $country,
						'compare' => 'LIKE'
					);
					$append_query = '?country_temp=' . $country;
				} elseif( $year ) {
					$year_begin = $year . '0101';
					$year_end = $year . '1231';
					$year_range = array( $year_begin, $year_end );
					$filter_query = array(
						'key' => 'residency_dates_0_start_date',
						'type' => 'DATE',
						'value' => $year_range,
						'compare' => 'BETWEEN'
					);
					$append_query = '?when=' . $year;
				}
				$today = new DateTime();
				$today = $today->format( 'Ymd' );

				$page_slug = get_post( $post )->post_name;
				
				$args = array(
					'post_type' => 'event',
					'posts_per_page' => 30,
					'meta_query' => array( $page_query, $filter_query )
				);

				$loop = new WP_Query( $args );

				while ( $loop->have_posts() ) : $loop->the_post();

					$id = $the_ID;
					$title = get_the_title( $id );
					$url = get_permalink();
					$type = pretty( get_field( 'event_type', $id ) );
					$date_format = get_event_date( $id );
					if( $append_query && is_alumni( $id ) ) {
						$url .= $append_query;
					}
					$thumbnail = get_display_image( $id );
					if( !$featured_image ) {
						$thumbnail = get_field( 'gallery' )[0]['image']['sizes']['event_thumb'];
					}

					echo '<div class="event orange shelf-item border-bottom"><div class="inner">';
					echo '<h3 class="value name"><a href="' . $url . '">' . $title . '</a></h3>';
					echo '<a href="' . $url . '">';
					echo '<div class="image">';
					echo '<img src="' . $thumbnail . '"/>';
					echo '</div>';
					echo '</a>';
					echo '<div class="details">';
					echo '<div class="left">';
					echo '<div class="value date"><a href="#">' . $date_format . '</a></div>';
					echo '<div class="value sponsor"><a href="#">' . $sponsor . '</a></div>';
					echo '</div>';
					echo '<div class="right">';
					echo '<div class="value event-type">' . $type . '</div>';
					echo '</div></div></div></div>';

				endwhile;

			?>
			<a href="#" class="load-more">Load More.</a>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>