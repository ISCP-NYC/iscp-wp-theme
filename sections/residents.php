<?php include( locate_template( 'sections/params/residents.php' ) ); ?>
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
						if( $country_count != 0 ):
							$filter_url = $page_url . '&from=' . $country_slug;
							echo '<div class="option">';
							echo '<a href="' . $filter_url . '">';
							echo ucwords( $country_title );
							echo ' (' . $country_count . ')';
							echo '</a>';
							echo '</div>';
						endif;
					endforeach;
				?>
				</div>
			</div>
			<?php if($slug == 'past-residents'): ?>
			<div class="filter-list year <?php echo $slug ?>">
				<div class="options">
				<?php
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '&date=' . $year;
						$year_count = resident_count_by_year( $year, $page_query );
						if( $year_count  != 0 ):
							echo '<div class="option">';
							echo '<a href="' . $filter_url . '">';
							echo $year;
							echo ' (' . $year_count . ')';
							echo '</a>';
							echo '</div>';
						endif;
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
						$filter_url = $page_url . '&residency_program=' . $program;
						$program_count = resident_count_by_program( $program, $page_query );
						if( $program_count != 0 ):
							echo '<div class="option">';
							echo '<a href="' . $filter_url . '">';
							echo pretty( $program );
							echo ' (' . $program_count . ')';
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