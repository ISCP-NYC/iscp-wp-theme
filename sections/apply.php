<?php 
	global $post;
	$id = $post->ID;
	$title = get_post( $post )->post_title;
	$slug = get_post( $post )->post_name;

	$residency_programs_query = array(
		'post_type' => 'page',
		'child_of' => get_page_by_path('apply')->ID,
		'post_status' => 'publish',
		'orderby' => 'menu_order',
		'sort_order' => 'desc'
	); 
	$residency_programs = get_pages( $residency_programs_query ); 
?>

<section <?php section_attr( $id, $slug, 'apply' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h3 class="title"><?php echo $title ?></h3>

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
			<div class="program border-top" id="<?php echo $slug ?>">
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

					<h5>Upcoming Application Deadlines</h5>
					<div class="applications">
						<?php
						$sponsors = get_posts( array(
							'posts_per_page' => 20,
							'post_type' => 'sponsor',
							'post_status' => 'publish',
							'orderby' => 'title',
							'sort_order' => 'asc',
							'meta_query' => array( array(
								'key' => 'deadlines',
								'value' => array(''),
								'compare' => 'NOT IN'
							) )
						) ); 
						foreach( $sponsors as $sponsor ): 
							setup_postdata($sponsor);
							$country = get_field( 'country', $sponsor )[0]->post_title;
							$sponsor_id = $sponsor->ID;
							$sponsor_title = get_the_title( $sponsor );
							$sponsor_website = get_field( 'website', $sponsor_id );
							if( have_rows( 'applications', $sponsor ) ):
						    while ( have_rows( 'applications', $sponsor ) ) : the_row();
						        $deadline_date = new DateTime( get_sub_field( 'deadline' ) );
								$deadline = $deadline_date->format( 'M d, Y' );
								$brief = get_sub_field( 'brief' );
								echo '<div class="application row">';
									echo '<div class="cell date">';
										echo $deadline;
									echo '</div>';
									echo '<div class="cell country">';
										echo $country;
									echo '</div>';
									echo '<div class="cell brief">';
									if( $sponsor_title ):
										echo '<a href="' . $sponsor_website . '">';
											echo $sponsor_title;
										echo '</a>';
									endif;
									if( $brief ):
										echo ' – ';
										echo $brief;
									endif;
									echo '</div>';
								echo '</div>';
							endwhile;
							endif;
							wp_reset_postdata();
						endforeach;
						?>
					</div>

					<div class="filter">
						Or choose a sponsor by
						<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">Country</div>
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