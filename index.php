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
		<h2 class="head">
			International Studio &amp; Curatorial Program
		</h2>
		<h3 class="title">
			<a href="<?php echo site_url() ?>/events">Events &amp; Exhibitions</a>
		</h3>
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
			
			echo '<div class="events module shelves grid upcoming ' . $count_class . '">';
				while ( $upcoming_events->have_posts() ) : $upcoming_events->the_post();

					get_template_part( 'items/event' );

				endwhile;
			?>
		</div>	

		<?php
			$about = get_page_by_path( 'about' );
			$about_id = $about->ID;
			$mission = get_field('mission', $about_id);
			$address = get_field('address', $about_id);
		?>

		<div class="mission module">
			<h3 class="title"><?php echo $address ?></h3>
			<div class="medium">
				<?php echo strip_tags( $mission ) ?>
			</div>
		</div>


		<?php
		$home_id = get_page_by_path( 'home' );
		if( get_field( 'image_slider', $home_id ) ):
			echo '<div class="module">';
			echo '<div class="image_slider gallery bg">';
			echo '<div class="cursor"></div>';
			echo '<div class="left arrow"></div>';
			echo '<div class="right arrow"></div>';
			echo '<div class="slides">';
			while( has_sub_field( 'image_slider', $home_id ) ):
				$image = get_sub_field( 'image', $home_id );
		        $image_url = $image['url'];
		        $image_id = $image['id'];
		        $orientation = get_orientation( $image_id );
		        $caption = label_art( $image_id );
		        echo '<div class="piece slide">';
		        echo '<div class="image" style="background-image:url(' . $image_url . ')">';
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
			endwhile;
			echo '</div>';
			echo '</div>';
			echo '</div>';
		endif;
		?>


		<?php
			$residency_program = get_page_by_path( 'residency-programs' );
			$residency_tagline = get_field('tagline', $residency_program);
			$residency_tagline .= ' <a href="#">Learn more.</a>';
		?>

		<div class="residency_program module">
			<h3 class="title">Residency Program</h3>
			<div class="text large">
				<?php echo $residency_tagline ?>
			</div>
		</div>

		<div class="module">
			<? get_tweets( 3 ); ?>
		</div>

		<?php
			$facebook_url = get_field( 'facebook', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
		?>

		<div class="social module">
			<div class="link medium facebook half-border-right">
				<a href="<?php echo $facebook_url ?>" target="_blank">
					<h3>Facebook</h3>
				</a>
			</div>
			<div class="link medium instagram half-border-left">
				<a href="<?php echo $instagram_url ?>" target="_blank">
					<h3>Instagram</h3>
			</div>
		</div>

		<?php
			$newsletter_url = get_field( 'newsletter', $about_id );
		?>

		<h3 class="newsletter title">
			<a href="<?php echo $newsletter_url ?>" target="_blank">
				Subscribe for our newsletter
			</a>
		</h3>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>

<?php get_footer(); ?>
