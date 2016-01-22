<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;
	$id = get_post( $post )->ID;
	$today = new DateTime();
	$today = $today->format( 'Ymd' );

	$event_type_param = get_query_var( 'type' );
	$year_param = get_query_var( 'date' );
	$event_classes = $event_type_param . ' ' . $year_param;
	$page_url = get_the_permalink();
?>

<section <?php section_attr( $id, $slug, $events_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h3 class="title head">Events &amp; Exhibitions</h3>
		<div class="filter">
			<div class="bar">
				<div class="select link dropdown event-type" data-filter="event-type" data-slug="<?php echo $slug ?>">
					<?php
					if($event_type_param):
						$event_type_count = ': ' . pretty( $event_type_param ) . ' (' . event_count_by_type( $event_type_param ) . ')';
					else:
						$event_type_count = null;
					endif;
					echo '<span>Event Type' . $event_type_count . '</span>';
					?>
					<div class="swap">
						<div class="icon default"></div>
						<div class="icon hover"></div>
					</div>
				</div>
				<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">
					<?php
					if($year_param):
						$year_count = ': ' . $year_param . ' (' . event_count_by_year( $year_param ) . ')';
					else:
						$year_count = null;
					endif;
					echo '<span>Year' . $year_count . '</span>';
					?>
					<div class="swap">
						<div class="icon default"></div>
						<div class="icon hover"></div>
					</div>
				</div>
				<div class="select link view toggle" data-slug="<?php echo $slug ?>">
					<span class="option list">List</span>
					<span class="option grid">Grid</span>
					<div class="swap">
						<div class="icon default"></div>
						<div class="icon hover"></div>
					</div>
				</div>
			</div>
			<div class="filter-lists">
				<div class="filter-list event-type <?php echo $slug ?>">
					<div class="options">
						<?php
						$event_types = array( 'event', 'exhibition', 'off-site-project', 'iscp-talk', 'open-studios' );
						foreach( $event_types as $event_type ): 
							$filter_url =  $page_url . '?type=' . $event_type;
							$event_type_count = event_count_by_type( $event_type );

							if($event_type == $event_type_param) {
								$selected = 'selected';
								$filter_url = $page_url;
							} else {
								$selected = null;
							}

							$event_type = pretty( $event_type );
							echo '<div class="option ' . $selected . '">';
							echo '<a href="' . $filter_url . '">';
							echo ucwords( $event_type );
							echo ' (' . $event_type_count . ')';
							echo '</a>';
							echo '</div>';
						endforeach;
						?>
					</div>
				</div>

				<div class="filter-list sub year <?php echo $slug ?>">
					<div class="options">
						<?php
						$page_url = get_the_permalink();
						$start_date = 1994;
						$end_date = date( "Y" );
						$years = array_reverse( range( $start_date,$end_date ) );
						foreach( $years as $year ): 
							$filter_url = $page_url . '?date=' . $year;
							$year_count = event_count_by_year( $year );
							if($year == $year_param) {
								$selected = 'selected';
								$filter_url = $page_url;
							} else {
								$selected = null;
							}
							echo '<div class="option ' . $selected . '">';
							echo '<a href="' . $filter_url . '">';
							echo $year;
							echo ' (' . $year_count . ')';
							echo '</a>';
							echo '</div>';
						endforeach;
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="events shelves filter-this grid <?php echo $slug ?>">
			<?php
				if( $event_type_param ) {
					$filter_key = 'event_type';
					$filter_query = array(
						'key' => 'event_type',
						'type' => 'CHAR',
						'value' => $event_type_param,
						'compare' => 'LIKE'
					);
					$append_query = '?type=' . $event_type;
				} elseif( $year_param ) {
					$year_begin = $year_param . '0101';
					$year_end = $year_param . '1231';
					$year_range = array( $year_begin, $year_end );
					$filter_query = array(
						'relation' => 'OR',
						array(
							'key' => 'start_date',
							'type' => 'DATE',
							'value' => $year_range,
							'compare' => 'BETWEEN'
						),
						array(
							'key' => 'end_date',
							'type' => 'DATE',
							'value' => $year_range,
							'compare' => 'BETWEEN'
						),
						array(
							'key' => 'date',
							'type' => 'DATE',
							'value' => $year_range,
							'compare' => 'BETWEEN'
						)
					);
					$append_query = '?date=' . $year_param;
				}
				
				$args = array(
					'post_type' => 'event',
					'posts_per_page' => 6,
					'orderby' => 'meta_value',
					'order' => 'DESC',
					'meta_query' => array( $filter_query )
				);

				$events = new WP_Query( $args );
				while ( $events->have_posts() ) : $events->the_post();
					get_template_part( 'items/event' );
				endwhile;
				wp_reset_query(); 
			?>
		</div>
		<div class="clear">
			<a href="#" class="load-more">
				Load More.
			</a>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>