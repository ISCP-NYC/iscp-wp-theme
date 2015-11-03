<?php get_header(); ?>

<section id="<?php echo $slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h3 class="title">International Studio &amp; Curitorial Program</h1>

		<h3 class="title">
			<a href="/events">
				Events &amp; Exhibitions
			</a>
		</h3>

		<div class="events upcoming">
			<?php 
				$args = array(
					'post_type' => 'event',
					'posts_per_page' => 3,
					'meta_key' => 'start_date',
				    'orderby' => 'meta_value_date',
				    'order' => 'DESC'
				);
				$loop = new WP_Query( $args );
				while ( $loop->have_posts() ) : $loop->the_post();
					$id = $the_ID;
					$title = get_the_title($id);
					$url = get_permalink();

					$start_date = new DateTime(get_field('start_date', $id));
					$start_year = $start_date->format('Y');
					$start_month = $start_date->format('F');
					$start_day_word = $start_date->format('l');
					$start_day = $start_date->format('d');

					$end_date = new DateTime(get_field('end_date', $id));
					$end_year = $end_date->format('Y');
					$end_month = $end_date->format('F');
					$end_day_word = $end_date->format('l');
					$end_day = $end_date->format('d');

					$type = get_field('event_type', $id);

					switch ($type) {
					    case 'iscp-talk':
					    	$type_name = 'ISCP Talk';
					    	$date_format = '';
					    	break;
					    case 'exhibition':
					    	$type_name = 'Exhibition';
					    	$date_format = 'Thru ' . $end_month . ' ' . $end_day;
					    	$date_format .= '</br>' . $end_year;
					    	break;
					    case 'open-studio':
					    	$type_name = 'Open Studio';
					    	$date_format = '';
					    	break;
					    case 'off-site-project':
					    	$type_name = 'Off-Site Project';
					    	$date_format = '';
					    	break;
					}
			
					echo '<div class="event">';
						echo '<a href="' . $url . '">';
					  		echo '<h2 class="date">';
					  			echo $date_format;
					  		echo '</h2>';
					  		echo '<div class="thumb"></div>';
					  		echo '<h4 class="type">' . $type_name . '</h4>';
					  		echo '<h4 class="title">' . $title . '</h4>';
					  	echo '</a>';
					echo '</div>';
				endwhile;
			?>
		</div>	

		<?php
			$about = get_page_by_path( 'about' );
			$about_id = $about->ID;
			$mission = get_field('mission', $about_id);
			$address = get_field('address', $about_id);
		?>

		<div class="mission border-top border-bottom">
			<h3 class="title orange"><?php echo $address ?></h3>
			<p class="large"><?php echo $mission ?></p>
		</div>


		<div class="slider"></div>


		<?php
			$residencies = get_page_by_path( 'residencies' );
			$residencies_id = $residencies->ID;
			$tagline = get_field('tagline', $residencies_id);
			$tagline .= ' <a href="#">Learn more.</a>';
		?>

		<div class="tagline border-top border-bottom">
			<h3 class="title orange">Residency Program</h3>
			<p class="xlarge"><?php echo $tagline ?></p>
		</div>

		<?php
			$twitter_handle = str_replace( '@', '', get_field( 'twitter', $about_id ) );
			$twitter_url = 'http://twitter.com/' . $twitter_handle;
		?>

		<div class="twitter module">
			<h3 class="title orange">
				<a href="<?php echo $twitter_url ?>" target="_blank">
					Follow us on Twitter @iscp_nyc
				</a>
			</h3>
			<div class="tweets">
				<div class="tweet">
					<div class="body medium">
						Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
					</div>
					<div class="orange time small">5h</div>
				</div>
				<div class="tweet">
					<div class="body medium">
						Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
					</div>
					<div class="orange time small">5h</div>
				</div>
				<div class="tweet">
					<div class="body medium">
						Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
					</div>
					<div class="orange time small">5h</div>
				</div>
			</div>
		</div>


		<?php
			$facebook_url = get_field( 'facebook', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
		?>

		<div class="social border-top border-bottom">
			<div class="link medium facebook half-border-right">
				<a href="<?php echo $facebook_url ?>" target="_blank">Facebook</a>
			</div>
			<div class="link medium instagram half-border-left">
				<a href="<?php echo $instagram_url ?>" target="_blank">Instagram</a>
			</div>
		</div>

		<?php
			$newsletter_url = get_field( 'newsletter', $about_id );
		?>

		<h3 class="newsletter title orange">
			<a href="<?php echo $newsletter_url ?>" target="_blank">
				Subscribe for our newsletter
			</a>
		</h3>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>

<?php get_footer(); ?>
