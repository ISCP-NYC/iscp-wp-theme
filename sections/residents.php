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
					<?php if($slug == 'past-residents'): ?>
					<div class="select link dropdown date" data-filter="date" data-slug="<?php echo $slug ?>">
						<?php
						if( $year_param ):
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
						if( $program_param ):
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
						if( $type_param ):
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