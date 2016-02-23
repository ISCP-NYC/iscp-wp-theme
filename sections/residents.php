<?php
include( locate_template( 'sections/params/residents.php' ) );
$filter = $_GET['filter'];
?>
<section <?php section_attr( $id, $slug, 'residents' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php the_title() ?></h2>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
						<?php
						if( $country_param ):
							$country_count = ': ' . $country_param_title . ' (' . get_resident_count( 'country', $country_param_id, $page_query ) . ')';
						endif;
						echo '<span>Country</span><span class="count">' . $country_count . '</span>';
						?>
						</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<?php if($slug == 'past-residents'): ?>
					<div class="select link dropdown date" data-filter="date" data-slug="<?php echo $slug ?>">
						<?php
						if( $year_param ):
							$year_count = ': ' . $year_param . ' (' . get_resident_count( 'year', $year_param, $page_query ) . ')';
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
					<?php endif; ?>
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $slug ?>">
						<?php
						if( $program_param ):
							$program_count = ': ' . get_program_title( $program_param ) . ' (' . get_resident_count( 'program', $program_param, $page_query ) . ')';
						else:
							$program_count = null;
						endif;
						echo '<span>Residency Program</span><span class="count">' . $program_count . '</span>';
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
					<?php 
					$map_id = get_page_by_path( 'map' )->ID;
					$map_permalink = get_the_permalink( $map_id );
					?>
					<a href="<?php echo $map_permalink ?>" class="select link map" data-slug="<?php echo $slug ?>">
						<span class="option map">Residents Map</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="right alt <?php echo $alt_slug ?>">
				<?php
				$alt_page = get_page_by_path( $alt_slug );
				$alt_url = get_the_permalink( $alt_page );
				$alt_title = get_the_title( $alt_page );
				?>
				<!-- <a href="<?php echo $alt_url; ?>">View <?php echo $alt_title ?></a> -->
			</div>
		</div>

		<div class="filter-lists">
			<div class="filter-list country <?php echo $slug ?>" data-filter="country">
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
					$country_count = get_resident_count( 'country', $country_id, $page_query );
					$filter_url = query_url( 'from', $country_slug, $page_url, $short_slug );
					if( $country_count != 0 ):
						if( $country_param == $country_slug ):
							$selected = ' selected';
						else:
							$selected = null;
						endif;
						echo '<div class="option' . $selected . '">';
						echo '<a href="' . $filter_url . '" data-value="' . $country_slug . '">';
						echo $country_title;
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
			<?php if($slug == 'past-residents'): ?>
			<div class="filter-list date <?php echo $slug ?>" data-filter="date">
				<div class="options">
				<?php
				$start_date = 1994;
				$end_date = date( "Y" );
				$years = array_reverse( range( $start_date, $end_date ) );
				foreach( $years as $year ): 
					$year_count = get_resident_count( 'year', $year, $page_query );
					$filter_url = query_url( 'date', $year, $page_url, $short_slug );
					if( $year_count  != 0 ):
						if( $year_param == $year ):
							$selected = ' selected';
						else:
							$selected = null;
						endif;
						echo '<div class="option' . $selected . '">';
						echo '<a href="' . $filter_url . '" data-value="' . $year . '">';
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
			<?php endif; ?>

			<div class="filter-list program <?php echo $slug ?>" data-filter="program">
				<div class="options">
					<?php
					$residency_programs = array(
						'international',
						'ground_floor'
					);
					foreach( $residency_programs as $program ): 
						$program_count = get_resident_count( 'program', $program, $page_query );
						$filter_url = query_url( 'program', $program, $page_url, $short_slug );
						$program_title = get_program_title( $program );
						if( $program_count != 0 ):
							if( $program_param == $program ):
								$selected = ' selected';
							else: 
								$selected = null;
							endif;
							echo '<div class="option' . $selected . '">';
							echo '<a href="' . $filter_url . '" data-value="' . $program . '">';
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

		<div class="residents shelves filter-this grid items <?php echo $slug ?>">
			<?php include(locate_template('sections/loops/residents.php')); ?>	
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>