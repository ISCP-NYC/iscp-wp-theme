<?php
global $post;
setup_postdata( $post );
$event_id = $post->ID;
$event_title = get_the_title( $event_id );
$event_url = get_permalink();
$event_type = get_field( 'event_type', $event_id );
$event_type_name = pretty( $event_type );
$event_status = get_event_status( $event_id );
$event_date_format = get_event_date( $event_id );
if( $append_query && is_past( $event_id ) ) {
	$event_url .= $append_query;
}
$event_thumb = get_thumb( $resident_id );

echo '<div class="event shelf-item border-bottom ' . $event_status . '"><div class="inner">';
echo '<a class="wrap value" href="' . $event_url . '">';
echo '<h3 class="link date">' . $event_date_format . '</h3>';
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
wp_reset_postdata();
?>