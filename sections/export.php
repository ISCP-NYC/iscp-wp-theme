<?php
$page = $_GET['num'];
if(!$page) {
	$page = 1;
}
$residents_query = array(
	'post_type' => 'resident',
	'posts_per_page' => 100,
	'paged' => $page,
	'post_status' => 'publish',
	'meta_key' => 'residency_dates_0_start_date',
	'orderby' => 'meta_value_num',
	'order' => 'DESC',
);
$residents = new WP_Query( $residents_query );
$GLOBALS['wp_query'] = $residents;
if( have_posts() ):
	echo '<table id="export">';
	while ( have_posts() ) :
		the_post();
		global $post;
		$id = $post->ID;
		$name = $post->post_title;
		$url = get_permalink( $id );
		$end_date = new DateTime( get_resident_end_date( $id ) );
		$year = $end_date->format('Y');
		$bio = get_field( 'bio', $resident_id );
		echo '<tr>';
		echo '<td>' . $name . '</td>';
		echo '<td>' . get_countries( $id ) . '</td>';
		echo '<td>';
		$residencies = array();
		if( have_rows( 'residency_dates', $resident_id ) ):
    	while ( have_rows( 'residency_dates' ) ) : the_row();
				$start_date_dt = new DateTime( get_sub_field( 'start_date', $resident_id ) );
				$end_date_dt = new DateTime( get_sub_field( 'end_date', $resident_id ) );
				$start_date = $start_date_dt->format( 'M j, Y' );
				$end_date = $end_date_dt->format( 'M j, Y' );
				$year = $end_date_dt->format( 'Y' );
				$sponsors = get_sub_field( 'sponsors', $resident_id );
				$residency_object = (object) array(
					'start_date_dt' => $start_date_dt,
					'end_date_dt'   => $end_date_dt,
					'start_date'    => $start_date,
					'end_date'      => $end_date,
					'date_range'    => $start_date . '&ndash;' . $end_date,
					'sponsors'		=> $sponsors,
					'year'			=> $year
				);
				array_push( $residencies, $residency_object );
			endwhile;
			usort($residencies, function($a, $b) {
				$ad = $a->start_date_dt;
				$bd = $b->start_date_dt;
				if ($ad == $bd) {
					return 0;
				}
				return $ad > $bd ? -1 : 1;
			});
		endif;
		$r_length = sizeof( $residencies );
		foreach( $residencies as $i=>$residency ):
			$start_year = $residency->start_date_dt->format('Y');
			$end_year = $residency->end_date_dt->format('Y');
			echo '<div class="residency">';
			echo $start_year;
			if( $start_year != $end_year ):
				echo '&ndash;' . $end_year;
			endif;
			if( $r_length > 1 && $i < $r_length - 1 ) {
				echo ', ';
			}
			echo '</div>';
		endforeach;
		echo '</td>';
		echo '<td>' . get_sponsors( $id ) . '</td>';
		echo '</tr>';
	endwhile;
	echo '</table>';
endif;
wp_reset_query(); 
?>