<section id="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php the_title() ?></h4>
		<div class="filter">
			<div class="bar">
				<div class="select dropdown country" data-filter="country">Country</div>
				<div class="select dropdown year" data-filter="year">Year</div>
				<div class="select view toggle">
					<span class="list">List</span>
					<span class="grid">Grid</span>
				</div>
			</div>
			<div class="sub country">
				<?php
					$page_url = get_the_permalink();
					$countries = get_posts( array(
						'posts_per_page'	=> 1000,
						'post_type'			=> 'country',
						'orderby' 			=> 'title',
						'order' 			=> 'ASC'
					) );
					foreach( $countries as $country ): 
						$id = $country->ID;
						$country_name = get_the_title( $id );
						//need to rewrite this with relationship
						$filter_url =  $page_url . '?country_temp=' . $country_name;
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo ucwords( $country_name );
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
			</div>

			<div class="sub year">
				<?php
					$page_url = get_the_permalink();
					$start_date = 1994;
					$end_date = date( "Y" );
					$years = array_reverse( range( $start_date,$end_date ) );
					foreach( $years as $year ): 
						$filter_url = $page_url . '?when=' . $year;
						echo '<div class="option">';
						echo '<a href="' . $filter_url . '">';
						echo $year;
						echo '</a>';
						echo '</div>';
					endforeach;
				?>
			</div>
		</div>

		<div class="residents shelves grid">
			<?php
				$country = get_query_var( 'country_temp' );
				$year = get_query_var( 'when' );

				if( $country ) {
					$filter_key = 'country_temp';
					$filter_query = array(
						'key' => 'country_temp',
						'type' => 'CHAR',
						'value' => $country,
						'compare' => 'LIKE'
					);
					$append_query = '?country_temp=' . $country;
				} elseif( $year ) {
					$year_begin = $year . '0101';
					$year_end = $year . '1231';
					$year_range = array( $year_begin, $year_end );
					$filter_query = array(
						'key' => 'residency_dates_0_start_date',
						'type' => 'DATE',
						'value' => $year_range,
						'compare' => 'BETWEEN'
					);
					$append_query = '?when=' . $year;
				}
				$today = new DateTime();
				$today = $today->format( 'Ymd' );

				$page_slug = get_post( $post )->post_name;

				switch( $page_slug ) {
					case 'current-residents':
						$page_query = array(
							'key' => 'residency_dates_0_end_date',
							'type' => 'DATE',
							'value' => $today,
							'compare' => '>='
						);
						break;
					case 'alumni':
						$page_query = array(
							'key' => 'residency_dates_0_end_date',
							'type' => 'DATE',
							'value' => $today,
							'compare' => '<='
						);
						break;
					case 'ground-floor':
						$page_query = array(
							'key' => 'ground_floor',
							'type' => 'BINARY',
							'value' => 1,
							'compare' => '='
						);
						break;
				}
				
				$args = array(
					'post_type' => 'resident',
					'posts_per_page' => 30,
					'meta_query' => array( $page_query, $filter_query )
				);

				$loop = new WP_Query( $args );

				while ( $loop->have_posts() ) : $loop->the_post();

					$id = $the_ID;
					$title = get_the_title( $id );
					$country = ucwords( get_field('country_temp', $id ) );
					$sponsor = get_field( 'sponsor_temp', $id );
					$studio_number = get_field( 'studio_number', $id );
					$url = get_permalink();
					if($append_query && is_alumni( $id )) {
						$url .= $append_query;
					}

					echo '<div class="resident orange shelf-item border-bottom"><div class="inner">';
					echo '<h3 class="value name"><a href="' . $url . '">' . $title . '</a></h3>';
					echo '<a href="' . $url . '">';
					echo '<div class="image">';
					echo '<img src="' . get_display_image( $id ) . '"/>';
					echo '</div>';
					echo '</a>';
					echo '<div class="details">';
					echo '<div class="left">';
					echo '<div class="value country"><a href="#">' . $country . '</a></div>';
					echo '<div class="value sponsor"><a href="#">' . $sponsor . '</a></div>';
					echo '</div>';
					echo '<div class="value studio-number"><span>Studio 0' . $studio_number . '</span></div>';
					echo '</div></div></div>';

				endwhile;

			?>
			<a href="#" class="load-more">Load More.</a>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>