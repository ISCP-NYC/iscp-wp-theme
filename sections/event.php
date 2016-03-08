<?php 
global $post;
$title = $post->post_title;
$slug = $post->post_name;
$id = $post->ID;
$description = get_field( 'description', $id );
$footnote = get_field( 'footnote', $id );
$event_type = get_field( 'event_type' );
$event_type_name = pretty( $event_type );
$date = get_event_date( $id );
$event_classes = 'event single ' . get_event_status( $id );
$page_columns = get_field( 'page_columns', $id );
$contributors = get_field( 'contributors' );
$credits = get_field( 'credits' );
$attachments = get_field( 'attachments' );
$rsvp = get_field( 'rsvp' );
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
			<?php echo $event_type_name; ?>
			</br>
			<?php echo $date; ?>
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
				echo '<em>' . $title .'</em>';
				echo ' is made possible through the generous support of ';
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
			<div class="links">
				<!-- <a href="#" class="link bullet">Share</a> -->
				<?php
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
					echo '<div class="resident">';
					echo '<a class="'. $resident_status .'" href="' . $resident_permalink . '">';
					echo $resident_name;
					echo '</a>';
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
		usort($related, function($a, $b) {
		   return strcasecmp( 
            	get_field( 'start_date', $b->ID ), 
            	get_field( 'start_date', $a->ID ) 
        	);
		});

		if( $related || $upcoming_events ):
			echo '<div class="bottom-modules">';
			$GLOBALS['wp_query'] = $upcoming_events;
			if ( have_posts() ):
				echo '<div class="upcoming module">';
				echo '<h3 class="title">Upcoming Events &amp; Exhibitions</h3>';
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
