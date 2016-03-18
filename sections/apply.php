<?php
global $post;
$id = $post->ID;
$title = get_post( $post )->post_title;
$slug = get_post( $post )->post_name;
$faq_id = get_page_by_path('apply/faq')->ID;
$faq_link = get_permalink( $faq_id );
$programs_link = get_permalink( get_page_by_path('residency-programs')->ID );
$residency_programs_query = array(
	'post_type' => 'page',
	'post__not_in' => array( $faq_id ),
	'child_of' => get_page_by_path('apply')->ID,
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'sort_order' => 'desc',
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
		$id = get_post( $program )->ID;
		if($id!=$faq_id):
		?>
		<div class="program module" id="<?php echo $slug ?>">
			<h3 class="title"><?php echo $title ?></h3>
			<div class="info">
				<?php $intro = get_field( 'intro', $program ); ?>
				<div class="intro">
					<?php echo $intro ?>
				</div>
			</div>
			<div class="links">
				<?php if( $slug == 'international' ): ?>
					<a class="bullet small" href="<?php echo $faq_link ?>">Application FAQ</a>
				<?php endif; ?>
				<a class="bullet small" href="<?php echo $programs_link . '#' . $slug ?>">Read more about this Residency Program</a>
			</div>
		</div>
		<?php if($slug == 'international'): ?>
			<div class="applications module <?php echo $slug ?>">
				<h3 class="title">Upcoming Application Deadlines</h3>
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
					echo '<div class="rows">';
					foreach( $sponsors as $sponsor ):
						setup_postdata($sponsor);
						$country = get_field( 'country', $sponsor )[0]->post_title;
						$sponsor_id = $sponsor->ID;
						$sponsor_title = get_the_title( $sponsor );
						$sponsor_website = get_field( 'website', $sponsor_id );
						$sponsor_permalink = get_permalink( $sponsor_id );
						if( have_rows( 'applications', $sponsor ) ):
						    while ( have_rows( 'applications', $sponsor ) ) : the_row();
								$application_title = get_sub_field( 'title' );
						        $deadline_date = new DateTime( get_sub_field( 'deadline' ) );
								$deadline = $deadline_date->format( 'M j, Y' );
								$attachment = get_sub_field( 'attachment' );
								if( $deadline_date >= $today ):
									$brief = get_sub_field( 'brief' );
									echo '<div class="application row">';
										echo '<div class="date">';
											echo $deadline;
										echo '</div>';
										echo '<div class="cell title">';
											echo $application_title;
										echo '</div>';
										echo '<div class="cell country">';
											echo $country;
										echo '</div>';
										echo '<div class="cell sponsors">';
											echo '<a href="' . $sponsor_permalink . '">';
												echo $sponsor_title;
											echo '</a>';
										echo '</div>';
										if($attachment):
											echo '<div class="links">';
											echo '<a class="bullet small" href="' . $attachment . '" class="attachment">Download Application</a>';
											echo '</div>';
										endif;
										echo '<div class="brief">';
											echo $brief;
										echo '</div>';
									echo '</div>';
								endif;
							endwhile;
						endif;
						wp_reset_postdata();
					endforeach;
					echo '</div>';
				else:
					echo 'No upcoming applications available.';
				endif;
				?>

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
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>