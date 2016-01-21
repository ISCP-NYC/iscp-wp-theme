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
		<h4 class="title orange">
			Sponsors</br>
			<?php echo $sponsor_country; ?>
		</h4>

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
				$page_query = array_merge( $sponsors_query, $filter_query );
			endif;
			
			$sponsors_query = array(
				'post_type' => 'sponsor',
				'posts_per_page' => 18,
				'orderby' => 'name',
				'order' => 'ASC',
				'post_status' => 'publish',
				'meta_query' => array( $sponsors_query )
			);
			$sponsors_loop = new WP_Query( $sponsors_query );
			while ( $sponsors_loop->have_posts() ) : $sponsors_loop->the_post();
				$sponsor_id = $the_ID;
				$title = get_the_title( $sponsor_id );
				$country = get_field('country', $sponsor_id )[0]->post_title;
				$country_permalink = get_field('country', $sponsor_id )[0]->permalink;
				$permalink = get_permalink();
				$website = get_field('website', $sponsor_id );
				$pretty_website = pretty_url( $website );
				echo '<div class="sponsor shelf-item border-bottom"><div class="inner">';
				echo '<a class="value name" href="' . $permalink . '">';
				echo '<h3 class="link">' . $title . '</h3>';
				echo '</a>';
				echo '<div class="value country"><a href="' . $country_permalink . '">' . $country . '</a></div>';
				if($website):
					echo '<div class="value website">';
					echo '<a href="' . $website . '" target="_blank">' . $pretty_website . '</a>';
					echo '</div>';
				endif;
				echo '</div></div>';
			endwhile;
			wp_reset_query(); 
			?>
		</div>
		<div class="clear">
			<a href="#" class="load-more">Load More.</a>
		</div>
	</div>
	<?php get_template_part('partials/footer'); ?>
</section>