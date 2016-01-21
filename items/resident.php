<?php
global $post;
setup_postdata( $post );
$resident_id = $the_ID;
$title = get_the_title( $resident_id );
$country = get_field('country', $resident_id )[0]->post_title;
$studio_number = get_field( 'studio_number', $resident_id );
$residency_program = get_field( 'residency_program', $resident_id );
$url = get_permalink();
$residency_date = get_field( get_end_date_value( $resident_id ), $resident_id );
$residency_year = ( new DateTime( $residency_date ) )->format('Y');
if( $append_query && is_past( $resident_id ) ) {
	$url .= $append_query;
}
$thumb = get_thumb( $resident_id );

echo '<div class="resident shelf-item border-bottom ' . $resident_status . '"><div class="inner">';
echo '<a class="wrap value name" href="' . $url . '">';
echo '<h3 class="link">' . $title . '</h3>';
echo '<div class="image">';
echo '<img src="' . $thumb . '"/>';
echo '</div>';
echo '</a>';
echo '<div class="details">';
echo '<div class="left">';
echo '<div class="value country"><a href="#">' . $country . '</a></div>';
echo '<div class="value sponsors">';
echo '<div class="vertical-align">';
echo get_sponsors( $resident_id );
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="right">';
if( $slug == 'current-residents' ) {
	echo '<div class="value studio-number">Studio ' . $studio_number . '</div>';
} elseif( $slug == 'past-residents' ) {
	echo '<div class="value year">' . $residency_year . '</div>';
}
echo '</div>';
echo '</div></div></div>';
wp_reset_postdata();
?>