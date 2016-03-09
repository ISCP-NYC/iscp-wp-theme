<?php
$home_id = get_page_by_path( 'home' )->ID;
?>
<section <?php section_attr( null, 'home', null ); ?> data-title="Home">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head">
			International Studio &amp; Curatorial Program
		</h2>
		<h3 class="title">
			<?php
			$events_id = get_page_by_path( 'events' )->ID;
			$events_permalink = get_the_permalink( $events_id );
			echo '<a href="' . $events_permalink . '">Events &amp; Exhibitions</a>';
			?>
		</h3>
		<?php 
			$today = new DateTime();
			$today = $today->format('Y-m-d H:i:s');
			$events_query = array(
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

			$upcoming_events = new WP_Query( $events_query );
			$count = $upcoming_events->found_posts;
			$count_class = 'cols_' . $count;
			$GLOBALS['wp_query'] = $upcoming_events;
			if ( have_posts() ):
				echo '<div class="events module shelves grid upcoming ' . $count_class . '">';
				echo '<div class="eventsWrap">';
				while ( have_posts() ) :
					the_post();
					get_template_part( 'sections/items/event' );
				endwhile;
				echo '</div>';
				echo '</div>';
			else:
				get_template_part( 'partials/no-posts' );
			endif;
			wp_reset_query();
			?>
		<div class="about module">
			<h3 class="title">
			<?php
			$description = get_field('description', $home_id);
			$about_id = get_page_by_path( 'about' )->ID;
			$address = get_field('address', $about_id);
			$visit_id = get_page_by_path( 'visit' )->ID;
			$visit_permalink = get_the_permalink( $visit_id );
			echo '<a href="' . $visit_permalink . '">' . $address . '</a>';
			?>
			</h3>
			<div class="text">
				<?php echo $description ?>
			</div>
		</div>


		<?php
		$image_slider = get_field( 'image_slider', $home_id );
		if( $image_slider ):
			echo '<div class="module">';
			echo '<div class="image_slider gallery">';
			echo '<div class="cursor"></div>';
			if( count( $image_slider ) > 1 ):
				echo '<div class="left arrow swap">';
				echo '<div class="icon default"></div>';
				echo '<div class="icon hover"></div>';
				echo '</div>';
				echo '<div class="right arrow swap">';
				echo '<div class="icon default"></div>';
				echo '<div class="icon hover"></div>';
				echo '</div>';
			endif;
			echo '<div class="slides">';
			while( has_sub_field( 'image_slider', $home_id ) ):
				$image = get_sub_field( 'image', $home_id );
		        $image_url = $image['url'];
		        $image_id = $image['id'];
		        $orientation = get_orientation( $image_id );
		        $caption = label_art( $image_id );
		        $image_url = $image['url'];
		        $orientation = get_orientation( $image['id'] );
		        echo '<div class="piece slide">';
		        echo '<div class="image ' . $orientation . '">';
		        echo '<div class="captionWrap">';
		        echo '<img src="' . $image_url . '"/>';
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
			endwhile;
			echo '</div>';
			echo '</div>';
			echo '</div>';
		endif;
		?>

		<div class="residency_program module">
			<h3 class="title">
				<?php
				$programs_text = get_field('residency_programs', $home_id);
				$programs_id = get_page_by_path( 'residency-programs' )->ID;
				$programs_permalink = get_the_permalink( $programs_id );
				echo '<a href="' . $programs_permalink . '">Residency Programs</a>';
				?>
			</h3>
			<div class="text">
				<?php
				echo strip_tags( $programs_text );
				echo '<a href="' . $programs_permalink .'">Learn more.</a>';
				?>
			</div>
		</div>

		<div class="module social">
			<?php
			get_tweets( 3 );
			$facebook_url = 'http://facebook.com/' . get_field( 'facebook', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
			?>
		</div>
		<div class="module links">
			<div class="link medium facebook half-border-right">
				<a href="<?php echo $facebook_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						Facebook
					</h3>
				</a>
			</div>
			<div class="link medium instagram half-border-left">
				<a href="<?php echo $instagram_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						Instagram
					</h3>
				</a>
			</div>
		</div>
		<div class="module newsletter">
			<?php get_template_part('partials/newsletter') ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>