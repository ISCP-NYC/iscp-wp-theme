<?php 
	global $post;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;

	$residency_programs_query = array(
		'post_type' => 'page',
		'child_of' => get_page_by_path('apply')->ID,
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'sort_order' => 'asc'
	); 
	$residency_programs = get_pages( $residency_programs_query ); 
?>

<section class="apply" id="apply">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php echo $title ?></h4>

		<div class="program-links">
			<?php
			foreach( $residency_programs as $program ):
				setup_postdata($program);
				$title = get_post( $program )->post_title;
				$slug = $program->post_name;
				echo '<div class="bullet">';
				echo '<a href="#' . $slug . '">' . $title . '</a></br>';
				echo '</div>';
				wp_reset_postdata();
			endforeach;
			?>
		</div>



		<?php foreach( $residency_programs as $program ):
			setup_postdata($program);
			$title = get_post( $program )->post_title; 
			$slug = get_post( $program )->post_name; 
		?>
			<div class="program border-top">
				<div class="title">
					<?php echo $title ?>
				</div>
				<div class="links">
					<div class="bullet">
						<a href="#">Read more about this residency program</a>
					</div>
					<div class="bullet">
						<a href="#">Application FAQ</a>
					</div>
				</div>
				<div class="inner">
					<?php $intro = get_field( 'intro', $program ); ?>
					<div class="intro">
						<?php echo $intro ?>
					</div>

					<h5>Upcoming deadlines</h5>
					<div class="deadlines">
						<?php
						while( has_sub_field( 'deadlines', $program ) ):
						setup_postdata($deadline);
							$datetime = new DateTime( get_sub_field( 'date', $program ) );
							$date = $datetime->format('M d, Y');
							$countries = get_sub_field( 'country', $program );
							$country = $countries[0]->post_title;
							$sponsors = get_sub_field( 'sponsor', $program );
							$sponsor = $sponsors[0]->post_title;
							$sponsor_link = get_field('link', $sponsors[0]);
							$brief = get_sub_field( 'brief', $program );
							
							if ($country && $date):
								echo '<div class="deadline row">';
									echo '<div class="cell date">';
										echo $date;
									echo '</div>';
									echo '<div class="cell country">';
										echo $country;
									echo '</div>';
									echo '<div class="cell brief">';
									if($sponsor):
										echo '<a href="' . $sponsor_link . '">';
											echo $sponsor;
										echo '</a>';
									endif;
									if($brief):
										echo ' – ';
										echo $brief;
									endif;
									
									echo '</div>';
								echo '</div>';
							endif;
						wp_reset_postdata();
						endwhile;
						?>
					</div>

					<div class="filter">
						Or choose a sponsor by
						<div class="select dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">Country</div>
					</div>
					<div class="filter-list sub country <? echo $slug ?>">
						<?php
							$page_url = get_the_permalink();
							$countries = get_posts( array(
								'posts_per_page'	=> 1000,
								'post_type'			=> 'country',
								'orderby' 			=> 'title',
								'order' 			=> 'ASC'
							) );
							foreach( $countries as $country ): 
							setup_postdata($country);
								$id = $country->ID;
								$filter_country_name = get_the_title( $id );
								//need to rewrite this with relationship
								$filter_url =  $page_url . '?country_temp=' . $filter_country_name;
								echo '<div class="option">';
								echo '<a href="' . $filter_url . '">';
								echo ucwords( $filter_country_name );
								echo '</a>';
								echo '</div>';
							wp_reset_postdata();
							endforeach;
						?>
					</div>
				</div>
			</div>

		<? wp_reset_postdata(); ?>
		<?php endforeach; ?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>