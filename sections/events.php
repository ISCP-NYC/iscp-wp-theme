<?php
include(locate_template('sections/params/events.php'));
$page_url = get_the_permalink();
?>
<section <?php section_attr( $id, $slug, $event_classes ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<div class="wrapper upcoming module">
			<h2 class="title head">Programs &amp; Exhibitions</h2>
			<h3 class="title orange">Current and Upcoming</h3>
		</div>	
			<?php 
			// $sorted_events = sort_upcoming_events();
			// $count = sizeof( $sorted_events );
			// if ( $count ):
			// 	echo '<div class="events shelves grid items residents upcoming">';
			// 	foreach( $sorted_events as $event ):
			// 		$post = $event;
			// 		global $post;
			// 		get_template_part( 'sections/items/event' );
			// 	endforeach;
			// 	echo '</div>';
			// else:
			// 	get_template_part( 'partials/no-posts' );
			// endif;
			// wp_reset_query();
			$sorted_events = sort_upcoming_events();
			$count = sizeof( $sorted_events );
			$count_class = 'cols_' . $count;
			if ( $count ):
				echo '<div class="events module shelves grid upcoming ' . $count_class . '">';
				echo '<div class="swiper image_slider">';
				echo '<div class="left arrow swap">';
				echo '<div class="icon default"></div>';
				echo '<div class="icon hover"></div>';
				echo '</div>';
				echo '<div class="right arrow swap">';
				echo '<div class="icon default"></div>';
				echo '<div class="icon hover"></div>';
				echo '</div>';
				echo '<div class="swiper-wrapper eventsWrap">';
				foreach( $sorted_events as $event ):
					$et = get_field( 'event_type', $event->ID );
					if( $et == 'open-studios' ):
						array_unshift( $sorted_events, $event );
					endif;
				endforeach;
				$sorted_events = array_slice( $sorted_events, 0, 3 );
				foreach( $sorted_events as $event ):
					$post = $event;
					global $post;
					get_template_part( 'sections/items/home-event', null,
						array(
							'class' => 'swiper-slide'
						)
					);
				endforeach;
				echo '</div>';
				echo '</div>';
				echo '</div>';
			endif;
			wp_reset_query();
			?>
		<div class="wrapper past">
			<h3 class="title">Past Programs &amp; Exhibitions</h3>
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