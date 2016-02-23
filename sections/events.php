<?php
include(locate_template('sections/params/events.php'));
?>
<section <?php section_attr( $id, $slug, $events_classes ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<div class="wrapper upcoming">
			<h2 class="title head">Upcoming Events &amp; Exhibitions</h2>
			<div class="events shelves grid items residents upcoming">
				<?php 
				$GLOBALS['events_section'] = 'upcoming';
				get_template_part('sections/loops/events');
				?>
			</div>
		</div>
		<div class="wrapper past">
			<h2 class="title head">Past Events &amp; Exhibitions</h2>
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown event-type" data-filter="event-type" data-slug="<?php echo $slug ?>">
						<?php
						if($event_type_param):
							$event_type_count = ': ' . pretty( $event_type_param ) . ' (' . event_count_by_type( $event_type_param ) . ')';
						else:
							$event_type_count = null;
						endif;
						echo '<span>Event Type</span><span class="showing">' . $event_type_count . '</span>';
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
						echo '<span>Year</span><span class="showing">' . $year_count . '</span>';
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
					<div class="filter-list event-type <?php echo $slug ?>" data-filter="event-type">
						<div class="options">
							<?php
							$event_types = array( 'exhibition', 'off-site-project', 'iscp-talk', 'open-studios' );
							foreach( $event_types as $event_type ): 
								$filter_url =  $page_url . '?type=' . $event_type;
								$event_type_count = event_count_by_type( $event_type );
								if( $event_type_count != 0 ):
									if($event_type == $event_type_param):
										$selected = 'selected';
									else:
										$selected = null;
									endif;
									echo '<div class="option ' . $selected . '">';
									echo '<a data-type="' . $event_type . '" href="' . $filter_url . '">';
									$event_type = pretty( $event_type );
									echo $event_type;
									echo ' (<span class="count">';
									echo $event_type_count;
									echo '</span>)';
									echo '<div class="swap">';
									echo '<div class="icon default"></div>';
									echo '<div class="icon hover"></div>';
									echo '</div>';
									echo '</a>';
									echo '</div>';
								endif;
							endforeach;
							?>
						</div>
					</div>

					<div class="filter-list sub year <?php echo $slug ?>" data-filter="year">
						<div class="options">
							<?php
							$page_url = get_the_permalink();
							$start_date = 1994;
							$end_date = date( "Y" );
							$years = array_reverse( range( $start_date, $end_date ) );
							foreach( $years as $year ): 
								$filter_url = $page_url . '?when=' . $year;
								$year_count = event_count_by_year( $year );
								if( $year_count != 0 ):
									if( $year == $year_param ):
										$selected = 'selected';
									else:
										$selected = null;
									endif;
									echo '<div class="option ' . $selected . '">';
									echo '<a data-date="' . $year . '" href="' . $filter_url . '">';
									echo $year;
									echo ' (<span class="count">';
									echo $year_count;
									echo '</span>)';
									echo '<div class="swap">';
									echo '<div class="icon default"></div>';
									echo '<div class="icon hover"></div>';
									echo '</div>';
									echo '</a>';
									echo '</div>';
								endif;
							endforeach;
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="events shelves filter-this grid items residents past">
				<?php 
				$GLOBALS['events_section'] = 'past';
				get_template_part('sections/loops/events');
				?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>