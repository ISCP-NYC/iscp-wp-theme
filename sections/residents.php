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
?>

<section class="<?php echo $slug ?> residents">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php the_title() ?></h4>
		<div class="filter">
			<div class="bar">
				<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">Country</div>
				<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">Year</div>
				<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $slug ?>">Residency Program</div>
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
						$country_id = $country->ID;
						$country_title = $country->post_title;
						$country_slug = $country->post_name;
						$filter_url = $page_url . '?country=' . $country_slug;
						$country_meta_query = array(
							'key' => 'country_temp',
							'type' => 'CHAR',
							'value' => $country_slug,
							'compare' => 'LIKE'
						);
						$country_query_args = array(
							'post_type' => 'resident',
							'meta_query' => array( $country_meta_query, $filter_query )
						);
						$country_query = new WP_Query( $country_query_args );
						$country_count = $country_query->found_posts;
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

			<div class="filter-list sub year <?php echo $slug ?>">
				<div class="options">
				<?php
					$page_url = get_the_permalink();
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '?date=' . $year;
						$year_begin = $year . '0101';
						$year_end = $year . '1231';
						$year_range = array( $year_begin, $year_end );
						$year_meta_query = array(
							'key' => 'residency_dates_0_start_date',
							'type' => 'DATE',
							'value' => $year_range,
							'compare' => 'BETWEEN'
						);
						$year_query_args = array(
							'post_type' => 'resident',
							'meta_query' => array( $year_meta_query, $filter_query )
						);
						$year_query = new WP_Query( $year_query_args );
						$year_count = $year_query->found_posts;
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

			<div class="filter-list sub program <?php echo $slug ?>">
				<div class="options">
				<?php
					$residency_programs = (object) array(
						0 => array('international' , 'International Artist & Curator Program'),
						1 => array('ground_floor', 'Ground Floor Residencies for New York City Artists')
					);
					foreach( $residency_programs as $program ): 
						$filter_url = $page_url . '?residency_program=' . $program[0];
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo $program[1];
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
					if( $sponsor ) {
						echo '<div class="value sponsor"><a href="#">' . $sponsor . '</a></div>';
					} else if( is_ground_floor( $resident_id ) ) {
						echo '<div class="value sponsor"><a href="#">Ground Floor</a></div>';
					}
					echo '</div>';
					echo '<div class="value studio-number">Studio 0' . $studio_number . '</div>';
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