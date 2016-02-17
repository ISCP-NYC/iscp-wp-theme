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
				echo '<div class="images slides">';
			    while ( have_rows( 'gallery' ) ) : the_row();
			        $image = get_sub_field( 'image' );
			        if( $image ):
				        $image_url = $image['url'];
				        $orientation = get_orientation( $image['id'] );
				        $caption = label_art( $the_ID );
				        echo '<div class="piece slide">';
				        echo '<div class="inner">';
				        echo '<div class="image ' . $orientation . '">';
				        echo '<div class="imageWrap">';
				        echo '<img src="' . $image_url . '"/>';
				        echo '<div class="caption">';
				        echo $caption;
				        echo '</div>';
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
				<?php echo $description ?>
			</div>
			<?php
			if( $footnote ):
				echo '<div class="footnote">';
				echo $footnote;
				echo '</div>';
			endif;
			?>
			<div class="links">
				<a href="#" class="link bullet">Share</a>
			</div>
		</div>

		<?php 
		if( $page_columns == 'two_col' ):
			if( have_rows( 'gallery' ) ):
				echo '<div class="gallery stack">';
				echo '<div class="images">';
			    while ( have_rows( 'gallery' ) ) : the_row();
			        $image = get_sub_field( 'image' );
			        if( $image ):
				        $image_url = $image['url'];
				        $orientation = get_orientation( $image['id'] );
				        $caption = label_art( $the_ID );
				        echo '<div class="piece slide">';
				        echo '<div class="inner">';
				        echo '<div class="image ' . $orientation . '">';
				        echo '<div class="imageWrap">';
				        echo '<img src="' . $image_url . '"/>';
				        echo '<div class="caption">';
				        echo $caption;
				        echo '</div>';
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
		$participating_residents = get_field( 'residents' );
		if( $participating_residents ):
			echo '<div class="participating residents shelves grid">';
			echo '<h4>Participating Residents</h4>';
			echo '<div class="inner">';
			foreach( $participating_residents as $participating_resident ): 
				$post = $participating_resident;
				global $post;
				get_template_part( 'sections/items/resident' );
			endforeach;
			echo '</div>';
			echo '</div>';
		endif;
		$GLOBALS['wp_query'] = $upcoming_events;
		if ( have_posts() ):
			echo '<div class="upcoming module">';
			echo '<h4>Upcoming Events &amp; Exhibitions</h4>';
			echo '<div class="shelves grid">';
			while ( have_posts() ) :
				the_post();
				get_template_part( 'sections/items/event' );
			endwhile;
			echo '</div>';
			echo '</div>';
		endif;
		wp_reset_query();
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>
