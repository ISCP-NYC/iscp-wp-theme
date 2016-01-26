<?php
include(locate_template('sections/params/events.php'));
?>
<section <?php section_attr( $id, $slug, $events_classes ); ?> data-page="<?php echo $paged ?>">
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
			<?php get_template_part('sections/loops/events'); ?>	
		</div>
		<?php get_template_part('partials/load-more'); ?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>