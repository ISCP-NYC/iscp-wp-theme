<?php
$sponsor = $post;
$sponsor_title = get_the_title();
$sponsor_slug = $sponsor->post_name;
$sponsor_id = $sponsor->ID;
$sponsor_country = get_field( 'country', $sponsor_id ) ? get_field( 'country', $sponsor_id )[0]->post_title : null;
$sponsor_website = get_field( 'website', $sponsor_id );
$sponsor_type = get_field( 'type', $sponsor_id );
$today = new DateTime();
$today = $today->format( 'Ymd' );

$country_param = get_query_var( 'from' );
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj ? $country_param_obj->post_title : null;
$country_param_id = $country_param_obj ? $country_param_obj->ID : null;
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
			<!-- <div class="block deadline">
				<div class="horizontal-align"> -->
					<?php 
					// if( have_rows( 'applications', $sponsor_id ) ):
			    // 		while ( have_rows( 'applications' ) ) : the_row();
					// 		$app_title = get_sub_field( 'title', $sponsor_id );
					// 		$app_deadline = get_sub_field( 'deadline', $sponsor_id );
					// 		$app_deadline_dt = new DateTime( $app_deadline );
					// 		$app_deadline_format = $app_deadline_dt->format('F d, Y');
					// 		$app_brief = get_sub_field( 'brief', $sponsor_id );
					// 		$app_link = get_sub_field( 'link', $sponsor_id );
					// 		if( $app_deadline > $today ):
					// 			if( $app_link ):
					// 				echo '<a href="' . $app_link . '">';
					// 			endif;
					// 			echo '<div>' . $app_title . '</div>';
					// 			echo '<div>Deadline: ' . $app_deadline_format . '</div>';
					// 			if( $app_link ):
					// 				echo '</a>';
					// 			endif;
					// 		endif;
					// 	endwhile;
					// else:
					// 	echo 'No applications available';
					// endif;
					?>

				<!-- </div>
			</div> -->
		</div>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( isset($country_param) ):
							$selected = ': ' . $country_param_title;
						else:
							$selected = null;
						endif;
						echo '<span class="label">Country</span><span class="value">' . $selected . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown date" data-filter="date" data-slug="<?php echo $sponsor_slug ?>">
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
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( isset($program_param) && isset($program) ):
							$selected = ': ' . get_program_title( $program );
						else:
							$selected = null;
						endif;
						echo '<span class="label">Residency Program</span><span class="value">' . $selected . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( isset($type_param) ):
							$selected = ': ' . ucwords( $type_param );
						else:
							$selected = null;
						endif;
						echo '<span class="label">Resident Type</span><span class="value">' . $selected . '</span>';
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

				<div class="filter-lists">
				</div>
			</div>
		</div>
		
		<div class="residents shelves filter-this grid items sponsor <?php echo $sponsor_slug ?>" data-delay="<?= isset($delay) ? $delay : null ?>">
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>