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
$open_hours = get_field( 'open_hours', $id );
$opening_reception = new DateTime( get_field( 'opening_reception', $id ) );
$opening_reception = $opening_reception->format('M d, Y');
$opening_reception_hours = get_field( 'opening_reception_hours', $id );
$logos = get_field( 'logos', $id );
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
				echo '<div class="gallery image_slider">';
				echo '<div class="cursor"></div>';
				echo '<div class="images slides">';
			    while ( have_rows( 'gallery' ) ) : the_row();
						$media_type = get_sub_field( 'media_type', $home_id );
		        if( $media_type == 'video' ):
		        	$video = get_sub_field( 'vimeo_id', $home_id );
			      	$orientation = 'landscape';
			      else:
			      	$image = get_sub_field( 'image' );
			      	$image_id = $image['id'];
			        $image_url = $image['url'];
			        $orientation = get_orientation( $image['id'] );
			      endif;
			      $caption = label_art( $the_ID );
		        echo '<div class="piece slide">';
		        echo '<div class="image ' . $orientation . '">';
		        echo '<div class="captionWrap">';
		        if( $media_type == 'video' ):
		        	echo embed_vimeo( $video );
		        else:
		        	echo '<img src="' . $image_url . '"/>';
		       	endif;
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
			    endwhile;
		    echo '</div>';
		    echo '</div>';
			endif;
		endif;
		?>
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
				echo 'This program is supported, in part, by ';
				foreach( $contributors as $index => $contributor ):
					$contributor_name = $contributor->post_title;
					if( $index > 0 && $index+1 != $c_length ):
						echo ', ';
					elseif( $index+1 == $c_length && $index!=0 ):
						echo ' and ';
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
			if( $logos ):
				echo '<div class="logos">';
				foreach( $logos as $logo ):
					echo '<img class="logo" src="' . $logo['logo'] . '"/>';
				endforeach;
				echo '</div>';
			endif;

			?>
			<div class="bullets">	
				<?php
				if( $event_type == 'exhibition' || $event_type == 'open-studios' || $event_type == 'offsite-project' ):
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
				if( $open_hours ):
					echo '<div class="bullet">Open Hours: ' . $open_hours . '</div>';
				endif;
				if( $event_type == 'offsite-project' ):
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
						$media_type = get_sub_field( 'media_type', $home_id );
		        if( $media_type == 'video' ):
		        	$video = get_sub_field( 'vimeo_id', $home_id );
			      	$orientation = 'landscape';
			      else:
			      	$image = get_sub_field( 'image' );
			      	$image_id = $image['id'];
			        $image_url = $image['url'];
			        $orientation = get_orientation( $image['id'] );
			      endif;
			      $caption = label_art( $the_ID );
		        echo '<div class="piece slide">';
		        echo '<div class="image ' . $orientation . '">';
		        echo '<div class="captionWrap">';
		        if( $media_type == 'video' ):
		        	echo embed_vimeo( $video );
		        else:
		        	echo '<img src="' . $image_url . '"/>';
		       	endif;
		        echo '<div class="caption">';
		        echo $caption;
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
		        echo '</div>';
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


		$upcoming_events = array_slice( sort_upcoming_events(), 0, 3 );
		if( $related || $upcoming_events ):
			echo '<div class="bottom-modules">';
			$count = sizeof( $upcoming_events );
			if ( $count ):
				echo '<div class="events module shelves grid upcoming ' . $count_class . '">';
				echo '<h3 class="title">Current and Upcoming Events &amp; Exhibitions</h3>';
				echo '<div class="eventsWrap">';
				foreach( $upcoming_events as $event ):
					$post = $event;
					global $post;
					get_template_part( 'sections/items/event' );
				endforeach;
				echo '</div>';
				echo '</div>';
			else:
				get_template_part( 'partials/no-posts' );
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
