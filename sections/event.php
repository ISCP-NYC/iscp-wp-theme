<?php 
global $post;
$title = $post->post_title;
$slug = $post->post_name;
$id = $post->ID;
$permalink = get_the_permalink( $id );
$description = get_field( 'description', $id );
$footnote = get_field( 'footnote', $id );
$event_type = get_field( 'event_type', $id );
$event_type_name = pretty( $event_type );
$date = get_event_date( $id );
$event_classes = 'event single ' . get_event_status( $id );
$page_columns = get_field( 'page_columns', $id );
$contributors = get_field( 'contributors', $id );
$credits = get_field( 'credits', $id );
$attachments = get_field( 'attachments', $id );
$rsvp = get_field( 'rsvp', $id );
$venue = get_field( 'venue_name', $id );
$location = get_field( 'venue_location', $id );
$time = get_field( 'time', $id );
$opening_reception = new DateTime( get_field( 'opening_reception', $id ) );
$opening_reception = $opening_reception->format('M d, Y');
$opening_reception_hours = get_field( 'opening_reception_hours', $id );
if( $page_columns ):
	$event_classes .= ' ' . $page_columns;
else:
	if( have_rows('gallery') ):
		$event_classes .= ' cols_2';
	else:
		$event_classes .= ' cols_1';
	endif;		
endif;
$today = new DateTime();
$today = $today->format('Y-m-d H:i:s');
$upcoming_query = array(
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
    'order' => 'ASC',
    'post__not_in' => array($id)
);
$upcoming_events = new WP_Query( $upcoming_query );
?>
<section <?php section_attr( $id, $slug, $event_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<div class="content">
		<h2 class="title">
			<?php
			echo $event_type_name;
			echo '</br>';
			echo $date;
			?>
		</h2>	

		<h1 class="title"><?php echo $title ?></h1>
		<?php 
		if( $page_columns == 'one_col' ):
			if( have_rows( 'gallery' ) ):
				echo '<div class="image_slider gallery">';
				echo '<div class="cursor"></div>';
				if( count( get_field( 'gallery' ) ) > 1 ):
					echo '<div class="left arrow swap">';
					echo '<div class="icon default"></div>';
					echo '<div class="icon hover"></div>';
					echo '</div>';
					echo '<div class="right arrow swap">';
					echo '<div class="icon default"></div>';
					echo '<div class="icon hover"></div>';
					echo '</div>';
				endif;
				echo '<div class="images slides">';
			    while ( have_rows( 'gallery' ) ) : the_row();
			        $image = get_sub_field( 'image' );
			        if( $image ):
				        $image_url = $image['url'];
				        $orientation = get_orientation( $image['id'] );
				        $caption = label_art( $the_ID );
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
				    endif;
			    endwhile;
			    echo '</div>';
			    echo '</div>';
			endif;
		endif; ?>
		<div class="info">
			<div class="description">
				<?php
				echo $description;
				?>
			</div>
			<?php
			if( $contributors ):
				echo '<div class="contributors">';
				$c_length = sizeof( $contributors );
				// echo '<em>' . $title .'</em>';
				echo 'This program is supported, in part, by ';
				foreach( $contributors as $index => $contributor ):
					$contributor_name = $contributor->post_title;
					if( $index > 0 && $index+1 != $c_length ):
						echo ', ';
					elseif( $index+1 == $c_length && $index!=0 ):
						echo ' & ';
					endif;
					echo $contributor_name;
					if( $index+1 == $c_length ):
						echo '.';
					endif;
				endforeach;
				echo '</div>';
			endif;
			if( $credits ):
				echo '<div class="credits">';
				echo $credits;
				echo '</div>';
			endif;

			?>
			<div class="bullets">	
				<?php
				if( $event_type == 'exhibition' || $event_type == 'open-studios' || $event_type == 'off-site-project' ):
					if( $opening_reception ):
						echo '<div class="bullet">Opening Reception: ';
						echo $opening_reception;
						if( $opening_reception_hours ):
							echo ', ' . $opening_reception_hours;
						endif;
						echo '</div>';
					endif;
				endif;
				if( $time ):
					echo '<div class="bullet">' . $time . '</div>';
				endif;
				if( $event_type == 'off-site-project' ):
					if( $venue ):
						echo '<div class="bullet">' . $venue . '</div>';
					endif;
					if($location):
						echo '<div class="bullet">' . $location . '</div>';
					endif;
				endif;
				if( $rsvp ):
					echo '<a href="' . $rsvp . '" class="link bullet" target="_blank">RSVP</a>';
				endif;
				foreach( $attachments as $index => $attachment ):
					$attachment_name = $attachment['name'];
					$attachment_url = $attachment['file']['url'];
					echo '<a href="' . $attachment_url . '" class="link bullet" target="_blank">';
					echo $attachment_name;
					echo'</a>';
				endforeach;
				?>
				<div class="share bullet link">
					<span class="toggle">Share</span>
					<div class="links">
						<span><a href="https://twitter.com/share?url=<?php echo urlencode( $permalink ) ?>&text=<?php echo urlencode( $title ) ?>" target="_blank">Twitter</a></span>
						<span><a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?php echo $permalink ?>" target="_blank">Facebook</a></span>
						<span class="copy link" data-clipboard-text="<?php echo $permalink ?>">Copy link</span>
					</div>
				</div>
				<div class="bullet mobileOnly link"><a href="https://twitter.com/share?url=<?php echo urlencode( $permalink ) ?>&text=<?php echo urlencode( $title ) ?>" target="_blank">Twitter</a></div>
				<div class="bullet mobileOnly link"><a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?php echo $permalink ?>" target="_blank">Facebook</a></div>
				<div class="bullet mobileOnly link" class="copy link" data-clipboard-text="<?php echo $permalink ?>">Copy link</div>
			</div>
			<?php
			$residents = get_field( 'residents' );
			if( $residents ):
				echo '<div class="residents module">';
				echo '<h3 class="title">Participating Residents</h3>';
				echo '<div class="inner">';
				foreach( $residents as $resident ): 
					$resident_name = $resident->post_title;
					$resident_id = $resident->ID;
					$resident_permalink = get_permalink( $resident_id );
					$resident_status = get_status( $resident_id );
					$resident_bio = get_field( 'bio', $resident_id );
					echo '<div class="resident '. $resident_status .'">';
					if( $resident_bio ):
						echo '<a href="' . $resident_permalink . '">';
						echo $resident_name;
						echo '</a>';
					else:
						echo $resident_name;
					endif;
					echo '</div>';
				endforeach;
				echo '</div>';
				echo '</div>';
			endif;
			?>
		</div>

		<?php 
		if( $page_columns == 'two_col' ):
			if( have_rows( 'gallery' ) ):
				echo '<div class="gallery stack">';
				echo '<div class="cursor"></div>';
				echo '<div class="images slides">';
			    while ( have_rows( 'gallery' ) ) : the_row();
			        $image = get_sub_field( 'image' );
			        if( $image ):
				        $image_url = $image['url'];
				        $orientation = get_orientation( $image['id'] );
				        $caption = label_art( $the_ID );
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
				    endif;
			    endwhile;
			    echo '</div>';
			    echo '</div>';
			endif;
		endif; ?>
		
		<?php 
		$related = get_field( 'related' );
		if( $related ):
			usort($related, function($a, $b) {
			   return strcasecmp( 
	            	get_field( 'start_date', $b->ID ), 
	            	get_field( 'start_date', $a->ID ) 
	        	);
			});
		endif;

		if( $related || $upcoming_events ):
			echo '<div class="bottom-modules">';
			$GLOBALS['wp_query'] = $upcoming_events;
			if ( have_posts() ):
				echo '<div class="upcoming module">';
				echo '<h3 class="title">Current and Upcoming Events &amp; Exhibitions</h3>';
				echo '<div class="shelves grid">';
				while ( have_posts() ) :
					the_post();
					get_template_part( 'sections/items/event' );
				endwhile;
				echo '</div>';
				echo '</div>';
			endif;
			wp_reset_query();

			if( $related ):
				echo '<div class="related module">';
				echo '<h3 class="title">Related Events &amp; Exhibitions</h3>';
				echo '<div class="shelves grid">';
				foreach( $related as $related_event ): 
					$post = $related_event;
					global $post;
					get_template_part( 'sections/items/event' );
				endforeach;
				echo '</div>';
				echo '</div>';
			endif;

			echo '</div>';
		endif;
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
