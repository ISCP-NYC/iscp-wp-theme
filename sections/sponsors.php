<?php
$page_title = get_the_title();
$page_slug = $post->post_name;
$page_id = $post->ID;

$country_param = get_query_var( 'from' );
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
$program_param = get_query_var( 'residency_program' );
$page_url = get_the_permalink();
?>

<section <?php section_attr( $page_id, $page_slug, null ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h3 class="title head">
			Sponsors</br>
			<?php echo $sponsor_country; ?>
		</h3>

		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $page_slug ?>">
						<?php
						if( $country_param ):
							$country_title = ': ' . $country_param_title . ' (' . resident_count_by_country( $country_param_id, $sponsor_query ) . ')';
						endif;
						echo '<span>Country' . $country_count . '</span>';
						?>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
					<div class="select link dropdown type" data-filter="type" data-slug="<?php echo $page_slug ?>">
						<?php
						if( $program_param ):
							$program_count = '';
						else:
							$program_count = null;
						endif;
						echo '<span>Sponsor Type' . $program_count . '</span>';
						?>
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
			<div class="filter-list country <?php echo $page_slug ?>">
				<div class="options">
					<?php
					$page_url = get_the_permalink();
					$countries = get_posts( array(
						'posts_per_page'	=> -1,
						'post_type'			=> 'country',
						'orderby' 			=> 'title',
						'order' 			=> 'ASC',
						'post_status' => 'publish'
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
			<!-- <div class="filter-list program <?php echo $page_slug ?>"> -->
				<!-- <div class="options"> -->
				<?php
				// $residency_programs = array(
				// 	'international',
				// 	'ground_floor'
				// );
				// foreach( $residency_programs as $program ): 
				// 	$filter_url = $page_url . '?residency_program=' . $program;
				// 	$program_count = resident_count_by_program( $program, $sponsor_query );
				// 	echo '<div class="option">';
				// 	echo '<a href="' . $filter_url . '">';
				// 	echo pretty( $program );
				// 	echo ' (' . $program_count . ')';
				// 	echo '</a>';
				// 	echo '</div>';
				// endforeach;
				?>
			<!-- 	</div>
			</div> -->
		</div>

		<div class="sponsors shelves filter-this list <?php echo $page_slug ?>">
			<?php get_template_part('sections/loops/sponsors'); ?>	
		</div>
		<?php get_template_part('partials/load-more'); ?>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>