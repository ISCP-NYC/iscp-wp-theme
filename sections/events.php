<?php
include(locate_template('sections/params/events.php'));
$page_url = get_the_permalink();
?>
<section <?php section_attr( $id, $slug, $event_classes ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<div class="wrapper upcoming">
			<h2 class="title head">Current and Upcoming Events &amp; Exhibitions</h2>
			<?php 
			$sorted_events = sort_upcoming_events();
			$count = sizeof( $sorted_events );
			if ( $count ):
				echo '<div class="events shelves grid items residents upcoming">';
				foreach( $sorted_events as $event ):
					$post = $event;
					global $post;
					get_template_part( 'sections/items/event' );
				endforeach;
				echo '</div>';
			else:
				get_template_part( 'partials/no-posts' );
			endif;
			wp_reset_query();
			?>
		</div>
		<div class="wrapper past">
			<h2 class="title head">Past Events &amp; Exhibitions</h2>
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $slug ?>">
						<?php
						if( isset($event_type_param) ):
							$selected = ': ' . $event_type_param_title;
						else:
							$selected = null;
						endif;
						echo '<span class="label">Event Type</span><span class="value">' . $selected . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">
						<?php
						if( isset($year_param) ):
							$selected = ': ' . $year_param;
						else:
							$selected = null;
						endif;
						echo '<span class="label">Year</span><span class="value">' . $selected . '</span>';
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