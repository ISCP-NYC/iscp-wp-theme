<?php
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;

$country_param = get_query_var( 'from' );
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
$program_param = get_query_var( 'residency_program' );
$page_url = get_the_permalink();
?>

<section <?php section_attr( $sponsor_id, $sponsor_slug, 'sponsor residents' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h4 class="title orange">
			Sponsor</br>
			<?php echo $sponsor_country; ?>
		</h4>

		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						echo 'Country';
						if( $country_param ):
							echo ': ' . $country_param_title . ' (' . resident_count_by_country( $country_param_id, $sponsor_query ) . ')';
						endif;
						?>
					</div>
					<div class="select link dropdown year" data-filter="year" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( $year_param ):
							$year_count = ': ' . $year_param . ' (' . resident_count_by_year( $year_param, $sponsor_query ) . ')';
						else:
							$year_count = null;
						endif;
						echo 'Year' . $year_count;
						?>
					</div>
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $sponsor_slug ?>">
						<?php
						if( $program_param ):
							$program_count = ': ' . pretty_short( $program_param ) . ' (' . resident_count_by_program( $program_param, $sponsor_query ) . ')';
						else:
							$program_count = null;
						endif;
						echo 'Residency Program' . $program_count;
						?>
					</div>
					<div class="select link view toggle" data-slug="<?php echo $sponsor_slug ?>">
						<span class="list">List</span>
						<span class="grid">Grid</span>
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
				$residency_programs = array(
					'international',
					'ground_floor'
				);
				foreach( $residency_programs as $program ): 
					$filter_url = $page_url . '?residency_program=' . $program;
					$program_count = resident_count_by_program( $program, $sponsor_query );
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

		<div class="info">
			<div class="block name">
				<?php echo $sponsor_title ?>
			</div>
			<div class="block website">
				<a href="<?php echo $sponsor_website ?>">
					<?php echo pretty_url( $sponsor_website ) ?>
				</a>
			</div>
			<div class="block deadline">
			</div>
		</div>

		<div class="residents shelves filter-this grid <?php echo $sponsor_slug ?>">
			<?php
			if( $country_param ):
				$filter_query = array(
					'key' => 'country',
					'value' => '"' . $country_param_id . '"',
					'compare' => 'LIKE'
				);
				$append_query = '?from=' . $country_param;
			elseif( $year_param ):
				$year_begin = $year_param . '0101';
				$year_end = $year_param . '1231';
				$year_range = array( $year_begin, $year_end );
				$filter_query = array(
					'key' => 'residency_dates_0_start_date',
					'type' => 'DATE',
					'value' => $year_range,
					'compare' => 'BETWEEN'
				);
				$append_query = '?date=' . $year;
			elseif( $program_param ):
				$filter_query = array(
					'key' => 'residency_program',
					'type' => 'CHAR',
					'value' => $program_param,
					'compare' => 'LIKE'
				);
				$append_query = '?residency_program=' . $year;
			endif;

			if( $filter_query ):
				$sponsor_query = array_merge( $sponsor_query, $filter_query );
			endif;
			
			$residents_query = array(
				'post_type' => 'resident',
				'posts_per_page' => 18,
				'orderby' => 'last_name',
				'order' => 'ASC',
				'post_status' => 'publish',
				'meta_query' => array( $sponsor_query )
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
				$residency_year = ( new DateTime( $residency_date ) )->format( 'Y' );
				$thumb = get_thumb( $resident_id );
				$resident_status = get_status( $resident_id );
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
				if( $resident_status == 'current' ) {
					echo '<div class="value studio-number">Studio ' . $studio_number . '</div>';
				} elseif( $resident_status == 'alumni' ) {
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