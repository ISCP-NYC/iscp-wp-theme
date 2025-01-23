<?php
$home = get_page_by_path( 'home' );
if($home){
	$home_id = get_page_by_path( 'home' )->ID;
}
else {
	$home_id = get_option('page_on_front');
}
?>
<section <?php section_attr( null, 'home', null, 'International Studio &amp; Curatorial Program' ); ?> data-title="Home">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<div class="module">
			<h2 class="head">
				International Studio &amp; Curatorial Program
				<?php
					$anniversaryCounter = get_field('anniversary_counter', $home_id);
					if ( $anniversaryCounter ) {
						echo '<span class="din no-wrap">is <span id="counter" class="din">30</span></span>';
					}
				?>
			</h2>
			<div class="introduction-text">
				<?php
					$introductionText = get_field('introduction_text', $home_id);
					if ( $introductionText ) {
						echo $introductionText;
					}
				?>
			</div>
		</div>
		<?php 
		$sorted_events = sort_upcoming_events();
		$count = sizeof( $sorted_events );
		$count_class = 'cols_' . $count;
		if ( $count ):
			echo '<div class="slideshow module shelves grid upcoming ' . $count_class . '">';
			echo '<h3 class="title">';
			$events_id = get_page_by_path( 'events' )->ID;
			$events_permalink = get_the_permalink( $events_id );
			echo '<a href="' . $events_permalink . '">Programs &amp; Exhibitions</a>';
			echo '</h3>';
			echo '<div class="swiper image_slider">';
			echo '<div class="left arrow swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '<div class="right arrow swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '<div class="swiper-wrapper eventsWrap">';
			foreach( $sorted_events as $event ):
				$et = get_field( 'event_type', $event->ID );
				if( $et == 'open-studios' ):
					array_unshift( $sorted_events, $event );
				endif;
			endforeach;
			$sorted_events = array_slice( $sorted_events, 0, 3 );
			foreach( $sorted_events as $event ):
				$post = $event;
				global $post;
				get_template_part( 'sections/items/home-event', null,
					array(
						'class' => 'swiper-slide'
					)
				);
			endforeach;
			echo '</div>';
			echo '</div>';
			echo '</div>';
		endif;
		wp_reset_query();
		?>
		<div class="introduction module">
			<h3 class="title">
			<?php
			$description = get_field('description', $home_id);
			$about_id = get_page_by_path( 'about' )->ID;
			$address = get_field('address', $about_id);
			$visit_id = get_page_by_path( 'visit' )->ID;
			$visit_permalink = get_the_permalink( $visit_id );
			$intro_video = get_field('introduction_video', $home_id);
			// echo '<a href="' . $visit_permalink . '">' . $address . '</a>';
			echo 'Introduction';
			?>
			</h3>
			<div class="flex-wrapper">
				<?php if ($intro_video): ?>
					<div class="video cols_2">
						<?= $intro_video ?>
					</div>
				<?php endif; ?>
				<div class="text <?= $intro_video ? 'cols_2' : '' ?>">
					<?= $description ?>
				</div>
			</div>
		</div>

		<div class="module newsletter mouse-enter">
			<h3 class="title">Stay Connected</h3>
			<?php get_template_part('partials/newsletter') ?>
		</div>

		<div class="module links">
		<?php
			// get_tweets( 3 );
			$facebook_url = 'http://facebook.com/' . get_field( 'facebook', $about_id );
			$twitter_url = 'http://twitter.com/' . get_field( 'twitter', $about_id );
			$vimeo_url = 'http://vimeo.com/' . get_field( 'vimeo', $about_id );
			$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
			$instagram_url = 'http://instagram.com/' . $instagram_handle;
		?>
			<div class="link medium facebook half-border-right">
				<a href="<?php echo $facebook_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						<?= get_field( 'facebook', $about_id ) ?>
					</h3>
				</a>
			</div>
			<div class="link medium instagram half-border-right half-border-left">
				<a href="<?php echo $instagram_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						@<?= get_field( 'instagram', $about_id ) ?>
					</h3>
				</a>
			</div>
			<div class="link medium vimeo half-border-right half-border-left">
				<a href="<?php echo $vimeo_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						@<?= get_field( 'vimeo', $about_id ) ?>
					</h3>
				</a>
			</div>
			<div class="link medium twitter half-border-left">
				<a href="<?php echo $twitter_url ?>" target="_blank">
					<h3>
						<div class="swap">
							<div class="icon default"></div>
							<div class="icon hover"></div>
						</div>
						@<?= get_field( 'twitter', $about_id ) ?>
					</h3>
				</a>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>