<?php include( locate_template( 'sections/params/sponsors.php' ) ); ?>
<section <?php section_attr( $id, $slug, 'sponsors' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head">
			Sponsors</br>
			<?php echo $sponsor_country; ?>
		</h2>

		<div class="top">
			<div class="filter">
				<div class="bar">
					<div class="select link dropdown country" data-filter="country" data-slug="<?php echo $slug ?>">
						<?php
						if($country_param):
							$country_count = ': ' . $country_param_title . ' (' . sponsor_count_by_country( $country_param_id, $sponsor_query ) . ')';
						endif;
						echo '<span>Country</span><span class="showing">' . $country_count . '</span>';
						?>
						</span>
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
			<div class="filter-list country <?php echo $slug ?>">
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
						$country_count = sponsor_count_by_country( $country_id, $sponsor_query );
						$filter_url = $page_url . '?from=' . $country_slug;
						if( $country_count != 0 ):
							if( $country_param == $country_slug ):
								$selected = ' selected';
							else:
								$selected = null;
							endif;
							echo '<div class="option' . $selected . '">';
							echo '<a href="' . $filter_url . '">';
							echo ucwords( $country_title );
							echo ' (' . $country_count . ')';
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

		<div class="sponsors shelves filter-this list items <?php echo $slug ?>">
			<?php include( locate_template( 'sections/loops/sponsors.php' ) ); ?>	
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>