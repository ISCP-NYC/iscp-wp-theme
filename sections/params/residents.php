<?php 
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$paged = 1;
$post_type = $post->post_type;
$page_query = array(
	'key' => 'residency_dates_0_end_date',
	'type' => 'DATE',
	'value' => $today,
);
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$page_type = $query_vars['pagetype'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$page_param = $slug;
	$country_param = $query_vars['from'];
	$year_param = $query_vars['date'];
	$program_param = $query_vars['program'];
else:
	$page_param = get_query_var( 'filter' ) . '-residents';
endif;
if( $slug == 'current-residents' ):
	$page_query = array_merge(
		$page_query, array(
			'compare' => '>=',
		)
	);
	$orderby_array = array(
		'meta_key' => 'studio_number',
		'orderby' => 'meta_value_num',
		'order' => 'ASC'
	);
	$resident_status = 'current';
	$alt_slug = 'past-residents';
elseif( $slug == 'past-residents' ):
	$page_query = array_merge(
		$page_query, array(
			'compare' => '<='
		)
	);
	$orderby_array = array(
		'meta_key' => 'residency_dates_0_start_date',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	);
	$resident_status = 'past';
	$alt_slug = 'current-residents';
elseif( $post_type == 'sponsor' || $page_type == 'sponsor' || $post_type == 'residents' ):
	$page_query = null;
	$orderby_array = array(
		'meta_key' => 'residency_dates_0_start_date',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	);
endif;
$page_param = get_query_var( 'filter' ) . '-residents';
if( $page_param == $slug || $post_type == 'sponsor' ):
	$country_param = get_query_var( 'from' );
	$year_param = get_query_var( 'date' );
	$program_param = get_query_var( 'program' );
endif;
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
$short_slug = str_replace('-residents', '', $slug);
$page_url = get_the_permalink() . '?filter=' . $short_slug;
?>