<?php
$sponsor = $post;
$sponsor_title = get_the_title();
$sponsor_slug = $sponsor->post_name;
$sponsor_id = $sponsor->ID;
$sponsor_country = get_field( 'country', $sponsor_id )[0]->post_title;
$sponsor_website = get_field( 'website', $sponsor_id );
$sponsor_type = get_field( 'type', $sponsor_id );
$today = new DateTime();
$today = $today->format( 'Ymd' );

$country_param = get_query_var( 'from' );
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
$year_param = get_query_var( 'date' );

$program_param = get_query_var( 'residency_program' );
$program_param_name = get_program_title( $program_param );

$page_url = get_the_permalink();
$sponsor_query = array(
	'key' => 'residency_dates_0_sponsors',
	'value' => '"' . $sponsor_id . '"',
	'compare' => 'LIKE'
);
$paged = 1;
?>

<section <?php section_attr( $sponsor_id, $sponsor_slug, 'sponsor residents' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head">
			Sponsor</br>
			<?php echo $sponsor_country; ?>
		</h2>
		<div class="info">
			<div class="block name">
				<div class="horizontal-align">
					<?php echo $sponsor_title ?>
				</div>
			</div>
			<div class="block website">
				<div class="horizontal-align">
					<a href="<?php echo $sponsor_website ?>">
						<?php echo pretty_url( $sponsor_website ) ?>
					</a>
				</div>
			</div>
			<div class="block deadline">
				<div class="horizontal-align">
					<?php 
					if( have_rows( 'applications', $sponsor_id ) ):
			    		while ( have_rows( 'applications' ) ) : the_row();
							$app_title = get_sub_field( 'title', $sponsor_id );
							$app_deadline = get_sub_field( 'deadline', $sponsor_id );
							$app_deadline_dt = new DateTime( $app_deadline );
							$app_deadline_format = $app_deadline_dt->format('M. dS Y');
							$app_brief = get_sub_field( 'brief', $sponsor_id );
							$app_link = get_sub_field( 'link', $sponsor_id );
							if( $app_deadline > $today ):
								echo '<a href="' . $app_link . '">';
								echo '<div>Application Deadline</div>';
								echo '<div>' . $app_deadline_format . '</div>';
								echo '</a>';
							endif;
						endwhile;
					else:
						echo 'No applications available';
					endif;
					?>

				</div>
			</div>
		</div>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if($country_param):
							$country_count = ': ' . $country_param_title . ' (' . resident_count_by_country( $country_param_id, $sponsor_query ) . ')';
						endif;
						echo '<span>Country</span><span class="showing">' . $country_count . '</span>';
						?>
						</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown date" data-filter="date" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if($year_param):
							$year_count = ': ' . $year_param . ' (' . resident_count_by_year( $year_param, $sponsor_query ) . ')';
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
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if($program_param):
							$program_count = ': ' . get_program_title( $program_param ) . ' (' . resident_count_by_program( $program_param, $sponsor_query ) . ')';
						else:
							$program_count = null;
						endif;
						echo '<span>Residency Program</span><span class="showing">' . $program_count . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link view toggle" data-slug="<?php echo $sponsor_slug ?>">
						<span class="option list">List</span>
						<span class="option grid">Grid</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<?php 
					$map_id = get_page_by_path( 'map' )->ID;
					$map_permalink = get_the_permalink( $map_id );
					?>
					<a href="<?php echo $map_permalink ?>" class="select link map" data-slug="<?php echo $sponsor_slug ?>">
						<span class="option map">Residents Map</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</a>
				</div>
			</div>
		</div>	
		<div class="filter-lists">
			<div class="filter-list country <?php echo $sponsor_slug ?>" data-filter="country">
				<div class="options">
				<?php
				$countries = get_posts( array(
					'posts_per_page'	=> -1,
					'post_type'			=> 'country',
					'orderby' 			=> 'title',
					'order' 			=> 'ASC',
					'post_status' 		=> 'publish'
				) );
				foreach( $countries as $country ): 
					$country_id = $country->ID;
					$country_slug = $country->post_name;
					$country_title = $country->post_title;
					$country_count = resident_count_by_country( $country_id, $sponsor_query );
					$filter_url = $page_url . '?from=' . $country_slug;
					if( $country_count != 0 ):
						if( $country_param == $country_slug ):
							$selected = ' selected';
						else:
							$selected = null;
						endif;
						echo '<div class="option' . $selected . '">';
						echo '<a data-from="' . $country_slug . '" href="' . $filter_url . '">';
						echo ucwords( $country_title );
						echo ' (<span class="count">';
						echo $country_count;
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
			<div class="filter-list date <?php echo $sponsor_slug ?>" data-filter="date">
				<div class="options">
				<?php
				$start_date = 1994;
				$end_date = date( "Y" );
				$years = array_reverse( range( $start_date, $end_date ) );
				foreach( $years as $year ): 
					$year_count = resident_count_by_year( $year, $sponsor_query );
					$filter_url = $page_url . '?date=' . $year;
					if( $year_count  != 0 ):
						if( $year_param == $year ):
							$selected = ' selected';
						else:
							$selected = null;
						endif;
						echo '<div class="option' . $selected . '">';
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
			<div class="filter-list program <?php echo $sponsor_slug ?>" data-filter="program">
				<div class="options">
					<?php
					$residency_programs = array(
						'international',
						'ground_floor'
					);
					foreach( $residency_programs as $program ): 
						$program_count = resident_count_by_program( $program, $sponsor_query );
						$filter_url = $page_url . '?program=' . $program;
						$program_title = get_program_title( $program );
						if( $program_count != 0 ):
							if( $program_param == $program ):
								$selected = ' selected';
							else: 
								$selected = null;
							endif;
							echo '<div class="option' . $selected . '">';
							echo '<a data-program="' . $program . '" href="' . $filter_url . '">';
							echo $program_title;
							echo ' (<span class="count">';
							echo $program_count;
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

		<div class="residents shelves filter-this grid items <?php echo $sponsor_slug ?>">
			<?php
			$sponsor_param = $sponsor;
			$sponsor_param_id = $sponsor_id;
			include(locate_template('sections/loops/residents.php'));
			?>
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>