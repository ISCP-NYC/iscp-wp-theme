<?php
include(locate_template('sections/params/events.php'));
$page_url = get_the_permalink();
?>
<section <?php section_attr( $id, $slug, $events_classes ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<div class="wrapper upcoming">
			<h2 class="title head">Current and Upcoming Events &amp; Exhibitions</h2>
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
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $slug ?>">
						<?php
						if($event_type_param):
							$event_type_count = ': ' . pretty( $event_type_param );
							 // . ' (' . get_event_count( 'type', $event_type_param ) . ')';
						else:
							$event_type_count = null;
						endif;
						echo '<span>Event Type</span><span class="count">' . $event_type_count . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">
						<?php
						if($year_param):
							$year_count = ': ' . $year_param;
							 // . ' (' . get_event_count( 'year', $year_param ) . ')';
						else:
							$year_count = null;
						endif;
						echo '<span>Year</span><span class="count">' . $year_count . '</span>';
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
				</div>
			</div>
			<div class="events shelves filter-this grid items residents past" data-delay="<?php echo $delay ?>">
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>