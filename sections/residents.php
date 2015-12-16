<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;

	$today = new DateTime();
	$today = $today->format( 'Ymd' );
	switch( $slug ) {
		case 'current-residents':
			$page_query = array(
				'key' => 'residency_dates_0_end_date',
				'type' => 'DATE',
				'value' => $today,
				'compare' => '>='
			);
			break;
		case 'alumni':
			$page_query = array(
				'key' => 'residency_dates_0_end_date',
				'type' => 'DATE',
				'value' => $today,
				'compare' => '<='
			);
			break;
	}

	$country_param = get_query_var( 'from' );
	$year_param = get_query_var( 'date' );
	$program_param = get_query_var( 'residency_program' );
	$page_url = get_the_permalink();
?>

<section class="<?php echo $slug ?> residents">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php the_title() ?></h4>
		<div class="filter">
			<div class="bar">
				<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
					<?php
					if($country_param):
						$country_count = ': ' . $country_param . ' (' . resident_count_by_country( $country_param, $page_query ) . ')';
					else:
						$country_count = null;
					endif;
					echo 'Country' . $country_count;
					?>
				</div>
				<?php if($slug == 'alumni'): ?>
				<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">
					<?php
					if($year_param):
						$year_count = ': ' . $year_param . ' (' . resident_count_by_year( $year_param, $page_query ) . ')';
					else:
						$year_count = null;
					endif;
					echo 'Year' . $year_count;
					?>
				</div>
				<?php endif; ?>
				<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $slug ?>">
					<?php
					if($program_param):
						$program_count = ': ' . pretty_short( $program_param ) . ' (' . resident_count_by_program( $program_param, $page_query ) . ')';
					else:
						$program_count = null;
					endif;
					echo 'Residency Program' . $program_count;
					?>
				</div>
				<div class="select link view toggle" data-slug="<?php echo $slug ?>">
					<span class="list">List</span>
					<span class="grid">Grid</span>
				</div>
			</div>
			<div class="filter-list sub country <?php echo $slug ?>">
				<div class="options">
				<?php
					$page_url = get_the_permalink();
					$countries = get_posts( array(
						'posts_per_page'	=> -1,
						'post_type'			=> 'country',
						'orderby' 			=> 'title',
						'order' 			=> 'ASC'
					) );
					foreach( $countries as $country ): 
						$filter_url = $page_url . '?from=' . $country_slug;
						$country_id = $country->ID;
						$country_slug = $country->post_name;
						$country_title = $country->post_title;
						$country_count = resident_count_by_country( $country_slug, $page_query );
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo ucwords( $country_title );
						echo ' (' . $country_count . ')';
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
				</div>
			</div>
			<?php if($slug == 'alumni'): ?>
			<div class="filter-list sub year <?php echo $slug ?>">
				<div class="options">
				<?php
					$page_url = get_the_permalink();
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '?date=' . $year;
						$year_count = resident_count_by_year( $year, $page_query );
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo $year;
						echo ' (' . $year_count . ')';
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
				</div>
			</div>
			<?php endif; ?>

			<div class="filter-list sub program <?php echo $slug ?>">
				<div class="options">
				<?php
					$residency_programs = array(
						'international',
						'ground_floor'
					);
					foreach( $residency_programs as $program ): 
						$filter_url = $page_url . '?residency_program=' . $program;
						$program_count = resident_count_by_program( $program, $page_query );
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo pretty( $program );
						echo ' (' . $program_count . ')';
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
				</div>
			</div>
		</div>

		<div class="residents shelves filter-this grid <?php echo $slug ?>">
			<?php
				$country = get_query_var( 'country_temp' );
				$year = get_query_var( 'date' );
				$residency_program = get_query_var( 'residency_program' );

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
					$append_query = '?date=' . $year;
				} elseif( $residency_program ) {
					$filter_query = array(
						'key' => 'residency_program',
						'type' => 'CHAR',
						'value' => $residency_program,
						'compare' => 'LIKE'
					);
					$append_query = '?residency_program=' . $year;
				}
								
				$args = array(
					'post_type' => 'resident',
					'posts_per_page' => 30,
					'orderby' => 'last_name',
    				'order' => 'ASC',
					'meta_query' => array( $page_query, $filter_query )
				);

				$loop = new WP_Query( $args );

				while ( $loop->have_posts() ) : $loop->the_post();
					$resident_id = $the_ID;
					$title = get_the_title( $resident_id );
					$country = ucwords( get_field('country_temp', $resident_id ) );
					$sponsor = get_field( 'sponsor_temp', $resident_id );
					$studio_number = get_field( 'studio_number', $resident_id );
					$residency_program = get_field( 'residency_program', $resident_id );
					$url = get_permalink();
					if( $append_query && is_alumni( $resident_id ) ) {
						$url .= $append_query;
					}
					$thumb = get_thumb( $resident_id );

					echo '<div class="resident orange shelf-item border-bottom"><div class="inner">';
					echo '<h3 class="value name"><a href="' . $url . '">' . $title . '</a></h3>';
					echo '<a href="' . $url . '">';
					echo '<div class="image">';
					echo '<img src="' . $thumb . '"/>';
					echo '</div>';
					echo '</a>';
					echo '<div class="details">';
					echo '<div class="left">';
					echo '<div class="value country"><a href="#">' . $country . '</a></div>';
					echo '<div class="value sponsor"><a href="#">' . $sponsor . '</a></div>';
					echo '</div>';
					echo '<div class="right">';
					if( $slug == 'current-residents' ) {
						echo '<div class="value studio-number">Studio 0' . $studio_number . '</div>';
					}
					if($residency_program == 'ground_floor') {
						echo '<div class="value ground-floor"><a href="#">Ground Floor</a></div>';
					}
					echo '</div>';
					echo '</div></div></div>';
				endwhile;

			?>
			<div class="clear">
				<a href="#" class="load-more">Load More.</a>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>