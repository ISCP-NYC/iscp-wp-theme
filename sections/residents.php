<?php
// echo $the_page->post_name;
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$page_query = array(
	'key' => 'residency_dates_0_end_date',
	'type' => 'DATE',
	'value' => $today,
);
switch( $slug ) {
	case 'current-residents':
		$page_query = array_merge(
			$page_query, array(
				'compare' => '>='
			)
		);
		$resident_status = 'current';
		$alt_slug = 'past-residents';
		break;
	case 'past-residents':
		$page_query = array_merge(
			$page_query, array(
				'compare' => '<='
			)
		);
		$resident_status = 'past';
		$alt_slug = 'current-residents';
		break;
}

$country_param = get_query_var( 'from' );
$country_param_obj = get_page_by_path($country_param, OBJECT, 'country');
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
$year_param = get_query_var( 'date' );
$program_param = get_query_var( 'residency_program' );
$page_url = get_the_permalink();
?>

<section <?php section_attr( $id, $slug, 'residents' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title"><?php the_title() ?></h4>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
						<?php
						if($country_param):
							$country_count = ': ' . $country_param_title . ' (' . resident_count_by_country( $country_param_id, $page_query ) . ')';
						endif;
						echo '<span>Country' . $country_count . '</span>';
						?>
						</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<?php if($slug == 'past-residents'): ?>
					<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $slug ?>">
						<?php
						if($year_param):
							$year_count = ': ' . $year_param . ' (' . resident_count_by_year( $year_param, $page_query ) . ')';
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
					<?php endif; ?>
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $slug ?>">
						<?php
						if($program_param):
							$program_count = ': ' . pretty_short( $program_param ) . ' (' . resident_count_by_program( $program_param, $page_query ) . ')';
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
					<div class="select link view toggle" data-slug="<?php echo $slug ?>">
						<span class="option list">List</span>
						<span class="option grid">Grid</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="right alt <?php echo $alt_slug ?>">
				<?php
				$alt_page = get_page_by_path( $alt_slug );
				$alt_url = get_the_permalink( $alt_page );
				$alt_title = get_the_title( $alt_page );
				?>
				<a href="<?php echo $alt_url; ?>">View <?php echo $alt_title ?></a>
			</div>
		</div>

		<div class="filter-lists">
			<div class="filter-list country <?php echo $slug ?>">
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
						$country_count = resident_count_by_country( $country_id, $page_query );
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
			<?php if($slug == 'past-residents'): ?>
			<div class="filter-list year <?php echo $slug ?>">
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

			<div class="filter-list program <?php echo $slug ?>">
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
			if( $country_param ) {
				$filter_query = array(
					'key' => 'country',
					'value' => '"' . $country_param_id . '"',
					'compare' => 'LIKE'
				);
				$append_query = '?from=' . $country_param;
			} elseif( $year_param ) {
				$year_begin = $year . '0101';
				$year_end = $year . '1231';
				$year_range = array( $year_begin, $year_end );
				$filter_query = array(
					'key' => 'residency_dates_0_start_date',
					'type' => 'DATE',
					'value' => $year_range,
					'compare' => 'BETWEEN'
				);
				$append_query = '?date=' . $year_param;
			} elseif( $program_param ) {
				$filter_query = array(
					'key' => 'residency_program',
					'type' => 'CHAR',
					'value' => $residency_program,
					'compare' => 'LIKE'
				);
				$append_query = '?residency_program=' . $program_param;
			}
							
			$residents_query = array(
				'post_type' => 'resident',
				'posts_per_page' => 18,
				'orderby' => 'last_name',
				'order' => 'ASC',
				'post_status' => 'publish',
				'meta_query' => array( $page_query, $filter_query )
			);
			$loop = new WP_Query( $residents_query );
			while ( $loop->have_posts() ) : $loop->the_post();
				$resident_id = $the_ID;
				$title = get_the_title( $resident_id );
				$country = get_field('country', $resident_id )[0]->post_title;
				$studio_number = get_field( 'studio_number', $resident_id );
				$residency_program = get_field( 'residency_program', $resident_id );
				$url = get_permalink();
				$residency_date = get_field( get_end_date_value( $resident_id ), $resident_id );
				$residency_year = ( new DateTime( $residency_date ) )->format('Y');
				if( $append_query && is_past( $resident_id ) ) {
					$url .= $append_query;
				}
				$thumb = get_thumb( $resident_id );

				echo '<div class="resident shelf-item border-bottom ' . $resident_status . '"><div class="inner">';
				echo '<a class="wrap value name" href="' . $url . '">';
				echo '<h3 class="link">' . $title . '</h3>';
				echo '<div class="image">';
				echo '<img src="' . $thumb . '"/>';
				echo '</div>';
				echo '</a>';
				echo '<div class="details">';
				echo '<div class="left">';
				echo '<div class="value country"><a href="#">' . $country . '</a></div>';
				echo '<div class="value sponsors">';
				echo '<div class="vertical-align">';
				echo get_sponsors( $resident_id );
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '<div class="right">';
				if( $slug == 'current-residents' ) {
					echo '<div class="value studio-number">Studio ' . $studio_number . '</div>';
				} elseif( $slug == 'past-residents' ) {
					echo '<div class="value year">' . $residency_year . '</div>';
				}
				echo '</div>';
				echo '</div></div></div>';
			endwhile;
			wp_reset_query(); 
			?>
			<div class="clear">
				<a href="#" class="load-more">Load More.</a>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>