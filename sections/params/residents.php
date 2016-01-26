<?php 
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$paged = 1;
$page_query = array(
	'key' => 'residency_dates_0_end_date',
	'type' => 'DATE',
	'value' => $today,
);

if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$page_param = $slug;
	$country_param = $query_vars['from'];
	$year_param = $query_vars['date'];
	$program_param = $query_vars['residency_program'];
else:
	$page_param = get_query_var( 'filter' ) . '-residents';
endif;

switch( $slug ) {
	case 'current-residents':
		$page_query = array_merge(
			$page_query, array(
				'compare' => '>='
			)
		);
		$resident_status = 'current';
		$alt_slug = 'past-residents';
		break;
	case 'past-residents':
		$page_query = array_merge(
			$page_query, array(
				'compare' => '<='
			)
		);
		$resident_status = 'past';
		$alt_slug = 'current-residents';
		break;
}
$page_param = get_query_var( 'filter' ) . '-residents';
if( $page_param == $slug ):
	$country_param = get_query_var( 'from' );
	$country_param_obj = get_page_by_path($country_param, OBJECT, 'country');
	$country_param_title = $country_param_obj->post_title;
	$country_param_id = $country_param_obj->ID;
	$year_param = get_query_var( 'date' );
	$program_param = get_query_var( 'residency_program' );
endif;
$short_slug = str_replace('-residents', '', $slug);
$page_url = get_the_permalink() . '?filter=' . $short_slug;
?>