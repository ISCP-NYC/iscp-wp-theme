<?php
global $post;
setup_postdata( $post );
$resident_id = $post->ID;
$title = get_the_title( $resident_id );
$countries = get_countries( $resident_id );
$resident_status = get_status( $resident_id );
$bio = get_field( 'bio', $resident_id );
$residents_url = get_permalink( get_page_by_path( 'residents' ) );
$studio_number = get_field( 'studio_number', $resident_id );
$residency_program = get_field( 'residency_program', $resident_id );
$sponsors = get_sponsors( $resident_id );
$url = get_permalink();
if( $bio ):
	if( have_rows( 'residency_dates', $resident_id ) ):
		while ( have_rows( 'residency_dates' ) ) : the_row();
			$start_date_dt = new DateTime( get_sub_field( 'start_date', $resident_id ) );
			$start_date = $start_date_dt->format( 'M j, Y' );
			$end_date_dt = new DateTime( get_sub_field( 'end_date', $resident_id ) );
			$end_date = $end_date_dt->format( 'M j, Y' );
			$start_year = $start_date_dt->format( 'Y' );
			$end_year = $end_date_dt->format( 'Y' );
		endwhile;
	endif;
else:
	$no_link = 'nolink';
	$end_date_dt = new DateTime( get_resident_end_date( $resident_id ) );
	$end_year = $end_date_dt->format( 'Y' );
endif;

$thumb = get_thumb( $resident_id );

echo '<div class="resident item shelf-item border-bottom ' . $resident_status . ' ' . $no_link . '"><div class="inner">';
if( $bio ):
echo '<a class="wrap value name" href="' . $url . '">';
endif;
echo '<h2 class="link title name">' . $title . '</h2>';
echo '<div class="image">';
if( $bio && $thumb ):
	echo '<img src="' . $thumb . '"/>';
endif;
echo '</div>';
if( $bio ):
	echo '</a>';
endif;
echo '<div class="details">';
echo '<div class="left">';
if( $countries ):
	echo '<div class="value country">';
	echo $countries;
	echo '</div>';
endif;
if( $sponsors ):
	echo '<div class="value sponsors">';
	echo '<div class="vertical-align">';
	echo $sponsors;
	echo '</div>';
	echo '</div>';
endif;
echo '</div>';
echo '<div class="right">';
if( is_current( $resident_id ) ):
	if( is_ground_floor( $resident_id )  ):
		$ground_floor_url = $residents_url . '?filter=all&program=ground_floor';
		echo '<div class="value"><a href="' . $ground_floor_url . '">Ground Floor</a></div>';
	elseif( $studio_number ):
		echo '<div class="value studio-number">Studio #' . $studio_number . '</div>';
	endif;
elseif( is_past( $resident_id ) && $end_year ):
	echo '<div class="value year">';
	echo $end_year;
	echo '</div>';
endif;
echo '</div>';
echo '</div></div></div>';
wp_reset_postdata();
?>