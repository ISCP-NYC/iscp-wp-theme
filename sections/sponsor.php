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

		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( $country_param ):
							$country_count = ': ' . $country_param_title . ' (' . resident_count_by_country( $country_param_id, $sponsor_query ) . ')';
						endif;
						echo '<span>Country' . $country_count . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( $year_param ):
							$year_count = ': ' . $year_param . ' (' . resident_count_by_year( $year_param, $sponsor_query ) . ')';
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
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( $program_param ):
							$program_count = ': ' . $program_param_title . ' (' . resident_count_by_program( $program_param, $sponsor_query ) . ')';
						else:
							$program_count = null;
						endif;
						echo '<span>Residency Program' . $program_count . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link view toggle" data-slug="<?php echo $sponsor_slug ?>">
						<span class="list">List</span>
						<span class="grid">Grid</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="right type">
				<?php echo ucwords( $sponsor_type ) ?>
			</div>
		</div>	
		<div class="filter-lists">
			<div class="filter-list country <?php echo $sponsor_slug ?>">
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
						$country_slug = $country->post_name;
						$country_title = $country->post_title;
						$country_count = resident_count_by_country( $country_id, $sponsor_query );
						$filter_url = $page_url . '?from=' . $country_slug;
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
			<div class="filter-list year <?php echo $sponsor_slug ?>">
				<div class="options">
					<?php
					$page_url = get_the_permalink();
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '?date=' . $year;
						$year_count = resident_count_by_year( $year, $sponsor_query );
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

			<div class="filter-list program <?php echo $sponsor_slug ?>">
				<div class="options">
				<?php
				$residency_programs_parent_id = get_page_by_path( 'residency-programs', OBJECT, 'page' )->ID;
				$residency_programs = get_posts( array(
					'posts_per_page'	=> -1,
					'post_type'			=> 'page',
					'orderby' 			=> 'title',
					'order' 			=> 'ASC',
					'post_parent'		=> $residency_programs_parent_id
				) );

				foreach( $residency_programs as $program ): 
					$program_name = $program->post_title;
					$program_slug = $program->post_name;
					$filter_url = $page_url . '?residency_program=' . $program_slug;
					$program_count = resident_count_by_program( $program_slug, $sponsor_query );
					echo '<div class="option">';
					echo '<a href="' . $filter_url . '">';
					echo $program_name;
					echo ' (' . $program_count . ')';
					echo '</a>';
					echo '</div>';
				endforeach;
				?>
				</div>
			</div>
		</div>

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

		<div class="residents shelves filter-this grid <?php echo $sponsor_slug ?>">
			<?php
			$sponsor_param = $sponsor;
			$sponsor_param_id = $sponsor_id;
			include(locate_template('sections/loops/residents.php'));
			?>
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>