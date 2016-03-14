<?php include( locate_template( 'sections/params/sponsors.php' ) ); ?>
<section <?php section_attr( $id, $slug, 'sponsors' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head">
			Sponsors
		</h2>

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
						</span>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
					</div>
				</div>	
				<div class="filter-lists">
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
								$country_count = get_sponsor_count( 'country', $country_id );
								if( $country_count != 0 ):
									if( $country_param == $country_slug ):
										$filter_url = $page_url;
										$selected = ' selected';
									else:
										$filter_url = $page_url . '?from=' . $country_slug;
										$selected = null;
									endif;
									echo '<div class="option' . $selected . '">';
									echo '<a href="' . $filter_url . '" data-value="' . $country_slug . '">';
									echo $country_title;
									echo '<div class="swap">';
									echo '<div class="icon default"></div>';
									echo '<div class="icon hover"></div>';
									echo '</div>';
									echo '</a>';
									echo '</div>';
								endif;
							endforeach;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sponsors shelves filter-this list items <?php echo $slug ?>">
			<?php include( locate_template( 'sections/loops/sponsors.php' ) ); ?>	
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>