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
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $slug ?>">
						<?php
						if( $type_param ):
							$type_count = ': ' . ucwords( $type_param ) . ' (' . get_resident_count( 'type', $type_param, $page_query ) . ')';
						else:
							$type_count = null;
						endif;
						echo '<span>Resident Type</span><span class="count">' . $type_count . '</span>';
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
			
		</div>

		<div class="residents shelves filter-this grid items <?php echo $slug ?>" data-delay="<?php echo $delay ?>">
			
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>