<?php
get_header();


$visit_page_id = get_page_by_path( 'visit' )->ID;
$post = get_post( $visit_page_id, OBJECT );
setup_postdata( $post );
get_template_part( 'sections/visit' );
wp_reset_postdata();


global $post;
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
				while ( have_posts() ) :
					the_post();
					get_template_part( 'sections/items/event' );
				endwhile;
				echo '</div>';
			else:
				get_template_part( 'partials/no-posts' );
			endif;
			wp_reset_query();
			?>
		<div class="about module">
			<h3 class="title">
			<?php
			$about = get_page_by_path( 'about' );
			$about_id = $about->ID;
			$description = get_field('description', $about_id);
			$address = get_field('address', $about_id);
			$visit_id = get_page_by_path( 'visit' )->ID;
			$visit_permalink = get_the_permalink( $visit_id );
			echo '<a href="' . $visit_permalink . '">' . $address . '</a>';
			?>
			</h3>
			<div class="text">
				<?php echo strip_tags( $description ) ?>
			</div>
		</div>


		<?php
		$home_id = get_page_by_path( 'home' );
		if( get_field( 'image_slider', $home_id ) ):
			echo '<div class="module">';
			echo '<div class="imageSlider gallery">';
			echo '<div class="cursor"></div>';
			echo '<div class="left arrow swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '<div class="right arrow swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '<div class="slides">';
			while( has_sub_field( 'image_slider', $home_id ) ):
				$image = get_sub_field( 'image', $home_id );
		        $image_url = $image['url'];
		        $image_id = $image['id'];
		        $orientation = get_orientation( $image_id );
		        $caption = label_art( $image_id );
		        echo '<div class="piece slide">';
			        echo '<div class="inner">';
			        echo '<div class="image ' . $orientation . '">';
			        echo '<div class="imageWrap">';
			        echo '<img src="' . $image_url . '" alt="' . $caption . '"/>';
			        echo '<div class="caption">';
			        echo $caption;
			        echo '</div>';
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
				$programs = get_page_by_path( 'residency-programs' );
				$programs_tagline = get_field('tagline', $programs) . ' <a href="#">Learn more.</a>';
				$programs_id = $programs->ID;
				$programs_permalink = get_the_permalink( $programs_id );
				echo '<a href="' . $programs_permalink . '">Residency Programs</a>';
				?>
			</h3>
			<div class="text">
				<?php echo $programs_tagline ?>
			</div>
		</div>

		<div class="module social">
			<?php
			get_tweets( 3 );
			$facebook_url = get_field( 'facebook', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
			?>

			<div class="links">
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
		</div>
		<?php $newsletter_url = get_field( 'newsletter', $about_id ); ?>

		<div class="module newsletter">
			<form role="subscribe" method="get" class="newsletter">
				<input type="text" value="Subscribe to our newsletter" data-placeholder="Subscribe to our newsletter"/>
			</form>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
<?php
$current_residents_page_id = get_page_by_path('current-residents')->ID;
$post = get_post( $current_residents_page_id, OBJECT );
setup_postdata( $post );
global $post;
get_template_part( 'sections/residents' );
wp_reset_query();

$past_residents_page_id = get_page_by_path('past-residents')->ID;
$post = get_post( $past_residents_page_id, OBJECT );
setup_postdata( $post );
global $post;
get_template_part( 'sections/residents' );
wp_reset_query();

get_footer();
?>
