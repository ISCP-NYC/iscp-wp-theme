<?php
get_header();
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
?>
<section <?php section_attr( null, 'home', null ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title">
			International Studio &amp; Curatorial Program
			<a href="<?php echo site_url() ?>/events">Events &amp; Exhibitions</a>
		</h4>
		<?php 
			$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');
			$args = array(
				'post_type' => 'event',
				'posts_per_page' => 3,
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'start_date',
						'compare' => '>=',
						'value' => $today,
						'type' => 'DATE',
						'orderby' => 'meta_value',
						'order' => 'DESC'
					),
					array(
						'key' => 'end_date',
						'compare' => '>=',
						'value' => $today,
						'type' => 'DATE',
						'orderby' => 'meta_value',
						'order' => 'DESC'
					),
					array(
						'key' => 'date',
						'compare' => '>=',
						'value' => $today,
						'type' => 'DATE',
						'orderby' => 'meta_value',
						'order' => 'DESC'
					),
				),
				'orderby' => 'meta_value',
			    'order' => 'ASC'
			);
			$upcoming_events = new WP_Query( $args );
			$count = $upcoming_events->found_posts;
			$count_class = 'cols_' . $count;
			
			echo '<div class="events shelves grid upcoming ' . $count_class . '">';
				while ( $upcoming_events->have_posts() ) : $upcoming_events->the_post();

					$event_id = $the_ID;
					$event_title = get_the_title( $event_id );
					$event_url = get_permalink();
					$event_type = get_field( 'event_type', $event_id );
					$event_type_name = pretty( $event_type );
					$event_date_format = get_event_date( $event_id );
					if( $append_query && is_alumni( $event_id ) ) {
						$url .= $append_query;
					}
					$event_thumb = get_thumb( $resident_id );

					echo '<div class="event shelf-item border-bottom"><div class="inner">';
					echo '<a class="wrap value date" href="' . $event_url . '">';
					echo '<h3 class="link">' . $event_date_format . '</h3>';
					echo '<div class="image">';
					echo '<img src="' . $event_thumb . '"/>';
					echo '</div>';
					echo '</a>';
					echo '<div class="details">';
					echo '<div class="value title"><a href="' . $event_url . '">' . $event_title . '</a></div>';
					echo '<div class="value event-type">';
					echo '<a href="' . site_url() . '/events?type=' . $event_type . '">';
					echo $event_type_name;
					echo '</a>';
					echo '</div></div></div></div>';

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
			<div class="large">
				<?php echo $mission ?>
			</div>
		</div>


		<?php
		$home_id = get_page_by_path( 'home' );
		if( get_field( 'image_slider', $home_id ) ):
			echo '<div class="image_slider gallery module bg">';
			echo '<div class="cursor"></div>';
			echo '<div class="left arrow"></div>';
			echo '<div class="right arrow"></div>';
			echo '<div class="slides">';
			while( has_sub_field( 'image_slider', $home_id ) ):
				$home_image = get_sub_field( 'image', $home_id );
				$home_image_url = $home_image['sizes']['slider'];
				$home_image_caption = get_sub_field( 'caption', $home_id );
				echo '<div class="slide">';
				echo '<div class="image" style="background-image:url(' . $home_image_url . ')">';
				echo '</div>';
				echo '<div class="caption">';
				echo $home_image_caption;
				echo '</div>';
				echo '</div>';
			endwhile;
			echo '</div>';
			echo '</div>';
		endif;
		?>


		<?php
			$residency_program = get_page_by_path( 'residency-programs' );
			$residency_tagline = get_field('tagline', $residency_program);
			$residency_tagline .= ' <a href="#">Learn more.</a>';
		?>

		<div class="residency_program border-top border-bottom">
			<h3 class="title orange">Residency Program</h3>
			<div class="text xlarge">
				<?php echo $residency_tagline ?>
			</div>
		</div>

		<? get_tweets( 3 ); ?>

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
