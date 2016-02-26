<?php
global $post;
setup_postdata( $post );
$event_id = $post->ID;
$event_status = get_event_status( $event_id );
$event_title = get_the_title( $event_id );
$event_url = get_permalink();
$event_type = get_field( 'event_type' );
$event_type_name = pretty( $event_type );
$event_status = get_event_status( $event_id );
$event_date_format = get_event_date( $event_id );
$event_thumb = get_thumb( $event_id, 'thumb' );

echo '<div class="event item shelf-item border-bottom ' . $event_status . '" data-id="' . $event_id . '">';
echo '<div class="inner">';
echo '<a class="wrap value date" href="' . $event_url . '">';
echo '<h2 class="link name title">' . $event_title . '</h2>';
echo '<div class="image">';
echo '<img src="' . $event_thumb . '"/>';
echo '</div>';
echo '<div class="value date link">' . $event_date_format . '</div>';
echo '</a>';
echo '<div class="value event-type">';
echo '<a href="' . query_url( 'type', $event_type, site_url() . '/events' ) . '">';
echo $event_type_name;
echo '</a>';
echo '</div>';
echo '</div></div>';
wp_reset_postdata();
?>