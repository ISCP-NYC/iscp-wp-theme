<?php
include( locate_template( 'sections/params/residents.php' ) );
// $filter = $_GET['filter'];
?>
<section <?php section_attr( $id, $slug, 'residents' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h1 class="head">
		<?php
		if($slug == 'residents'):
			if( $program_param ):
				if( $program_param == 'ground_floor'):
					echo 'Ground Floor ';
				else:
					echo 'International ';
				endif;
			endif;
			if( $type_param ):
				echo ucwords( $type_param ) . 's';
			else:
				echo 'Residents';
			endif;

			if( isset($country_param) && isset($resident_title) ):
				echo $resident_title . ' from ' . $country_param_title;
			endif;
			if( isset($year_param) && isset($resident_title) ):
				echo $resident_title . ' in ' . $year_param;
			endif;
		else:
			the_title();
		endif;
		?>
		</h2>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
						<?php
						if( isset($country_param) && isset($country_param_title) ):
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
					<?php if($slug != 'current-residents'): ?>
						<div class="select link dropdown date" data-filter="date" data-slug="<?php echo $slug ?>">
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
					<?php endif; ?>
					<div class="select link dropdown program" data-filter="program" data-slug="<?php echo $slug ?>">
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
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $slug ?>">
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
				<div class="filter-lists"></div>
			</div>
		</div>
		
		<div class="residents shelves filter-this grid items <?php echo $slug ?>" data-delay="<?php echo $delay ?>">
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>