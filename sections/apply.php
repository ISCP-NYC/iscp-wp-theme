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
		<h2 class="title"><?php echo $title ?></h2>
		<?php foreach( $residency_programs as $program ):
			setup_postdata($program);
			$title = get_post( $program )->post_title; 
			$slug = get_post( $program )->post_name; 
		?>
			<div class="program module" id="<?php echo $slug ?>">
				<h3 class="title"><?php echo $title ?></h3>
				<div class="info">
					<?php $intro = get_field( 'intro', $program ); ?>
					<div class="intro">
						<?php echo $intro ?>
					</div>

					<?php if($slug == 'international'): ?>
						<h3 class="title">Upcoming Application Deadlines</h3>
						<div class="applications">
							<?php
							$today = new DateTime();
							$sponsors = get_posts( array(
								'posts_per_page' => -1,
								'post_type' => 'sponsor',
								'post_status' => 'publish',
								'orderby' => 'title',
								'sort_order' => 'asc',
								'meta_query' => array(
									'relation' => 'AND',
								    array(
								        'key' => 'applications',
								        'compare' => 'NOT IN',
								        'value'   => array('')
								    )
								)
							) );

							if( sizeof( $sponsors ) != 0 ):
								foreach( $sponsors as $sponsor ): 
									setup_postdata($sponsor);
									$country = get_field( 'country', $sponsor )[0]->post_title;
									$sponsor_id = $sponsor->ID;
									$sponsor_title = get_the_title( $sponsor );
									$sponsor_website = get_field( 'website', $sponsor_id );
									if( have_rows( 'applications', $sponsor ) ):
									    while ( have_rows( 'applications', $sponsor ) ) : the_row();
									        $deadline_date = new DateTime( get_sub_field( 'deadline' ) );
											$deadline = $deadline_date->format( 'M j, Y' );
											if( $deadline_date >= $today ):
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
											endif;
										endwhile;
									endif;
									wp_reset_postdata();
								endforeach;
							else:
								echo 'No upcoming applications available.';
							endif;
							?>
						</div>

						<div class="filter">
							<div class="bar">
								<span>Or select by&nbsp;</span>
								<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
									<?php
									echo '<span>Country</span>';
									?>
									</span>
									<div class="swap">
										<div class="icon default"></div>
										<div class="icon hover"></div>
									</div>
								</div>
							</div>
						</div>
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
								$page_url = get_permalink( get_page_by_path('support/sponsors')->ID );
								$filter_url = query_url( 'from', $country_slug, $page_url );
								echo '<div class="option">';
								echo '<a href="' . $filter_url . '" data-value="' . $country_slug . '">';
								echo $country_title;
								echo '<div class="swap">';
								echo '<div class="icon default"></div>';
								echo '<div class="icon hover"></div>';
								echo '</div>';
								echo '</a>';
								echo '</div>';
							endforeach;
							?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

		<?php wp_reset_postdata(); ?>
		<?php endforeach; ?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>