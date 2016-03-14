<?php
/**
 * Twenty Fifteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 */

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Fifteen 1.1
 */
function twentyfifteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyfifteen_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function iscp_scripts() {
	wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/styles.css' );
	wp_register_script( 'transit', get_template_directory_uri() . '/assets/js/jquery.transit.min.js', array( 'jquery' ) );
	wp_register_script( 'jquery-ui', get_template_directory_uri() . '/assets/js/jquery-ui.min.js', array( 'jquery' ) );
	wp_register_script( 'imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.pkgd.min.js', array( 'jquery' ) );
	wp_register_script( 'masonry', get_template_directory_uri() . '/assets/js/masonry.pkgd.min.js', array( 'jquery' ) );
	wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'masonry', 'transit', 'jquery-ui' ) );
	wp_enqueue_script( 'webglearth', 'http://www.webglearth.com/v2/api.js' );
	wp_enqueue_script( 'transit' );
	wp_enqueue_script( 'jquery-ui' );
	wp_enqueue_script( 'masonry' );
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'main' );
	global $post;
	$page_slug = $post->post_name;
	$with_map = array('map', 'past-residents', 'current-residents');
	if(in_array( $page_slug, $with_map ) ):
		$countries_query = array(
			'post_type' => 'country',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);
		$countries = new WP_Query( $countries_query );
		$countries_array = array();
		while ( $countries->have_posts() ) : $countries->the_post(); 
			global $post;
			setup_postdata( $post );
			$country_id = $post->ID;
		    $country = array(
		       'name' => $post->post_title,
		       'slug' => $post->post_name,
		       'lat' => get_field( 'latitude', $country_id ),
		       'lng' => get_field( 'longitude', $country_id ),
		       'count' => get_resident_count( 'country', $country_id )
		    );
		    $countries_array[] = $country;
		    wp_reset_postdata();
		endwhile;
		$json = json_decode( json_encode( $countries_array ), true );
	    wp_localize_script( 'main', 'countries', $json );
	endif;
	global $wp_query;
	wp_localize_script( 'main', 'ajaxpagination', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'query_vars' => json_encode( $wp_query->query ),
	));
	wp_localize_script( 'main', 'wp_info', array(
		'theme_url' => get_stylesheet_directory_uri()
	));
}
add_action( 'wp_enqueue_scripts', 'iscp_scripts' );

show_admin_bar(false);
add_theme_support( 'post-thumbnails' ); 
add_image_size( 'thumb', 500, 350, true );
add_image_size( 'slider', 9999, 500, false );

function add_lat_lng( $post_id ) {
	$post_type = get_post_type( $post_id );
	if( $post_type == 'country' ):
		$country_name = urlencode( get_the_title( $id ) );
	    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $country_name;
	    $response = file_get_contents( $url );
	    $json = json_decode( $response, true );
	    $lat = $json['results'][0]['geometry']['location']['lat'];
	    $lng = $json['results'][0]['geometry']['location']['lng'];
	    if( is_numeric( $lat ) ):
	    	update_post_meta( $post_id, 'latitude', $lat );
	    endif;
	    if( is_numeric( $lng ) ):
		    update_post_meta( $post_id, 'longitude', $lng );
		endif;
	endif;
}
add_action( 'save_post', 'add_lat_lng' );


function add_ground_floor_studio_num( $post_id ) {
	$post_type = get_post_type( $post_id );
	if( $post_type == 'resident' ):
		$program = get_post_meta( $post_id, 'residency_program', true );
		if( $program == 'ground_floor' ):
			global $wpdb;
		    $query = "SELECT max(cast(meta_value as unsigned)) FROM wp_postmeta WHERE meta_key='studio_number'";
		    $new_num = $wpdb->get_var( $query ) + 1;
	    	update_post_meta( $post_id, 'studio_number', $new_num );
	    endif;
	endif;
}
add_action( 'save_post', 'add_ground_floor_studio_num' );

/////////////////////////////////////
/////////////////////////////////////
///////////AJAX AJAX AJAX////////////
/////////////////////////////////////
/////////////////////////////////////

function load_more() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query_vars['paged'] = $_POST['page'];
    $slug = $query_vars['pagename'];
    $page_type = $query_vars['pagetype'];
    $post_type = $slug;
    if( strstr( $slug, 'residents' ) || $page_type == 'sponsor' ):
    	$post_type = 'residents';
    elseif( $post_type == 'journal' ):
    	$post_type .= 's';
    endif;
    include( locate_template( 'sections/loops/' . $post_type . '.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_load_more', 'load_more' );
add_action( 'wp_ajax_load_more', 'load_more' );

function insert_filter_list() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query_vars['paged'] = $_POST['page'];
    $slug = $query_vars['pagename'];
    $page_type = $query_vars['pagetype'];
    $post_type = $slug;
    if( strstr( $slug, 'residents' ) || $page_type == 'sponsor' ):
    	$post_type = 'residents';
    elseif( $post_type == 'journal' ):
    	$post_type .= 's';
    endif;
    include( locate_template( 'sections/filters/' . $post_type . '.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_insert_filter_list', 'insert_filter_list' );
add_action( 'wp_ajax_insert_filter_list', 'insert_filter_list' );

function get_search_count() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
	include( locate_template( 'other/search-count.php' ) );
	die();
}
add_action( 'wp_ajax_nopriv_get_search_count', 'get_search_count' );
add_action( 'wp_ajax_get_search_count', 'get_search_count' );

function update_search_results() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $text = $query_vars['text'];
    include( locate_template( 'sections/loops/search.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_update_search_results', 'update_search_results' );
add_action( 'wp_ajax_update_search_results', 'update_search_results' );

function get_map_list() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $country_slug = $query_vars['pagename'];
    include( locate_template( 'other/map-list.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_get_map_list', 'get_map_list' );
add_action( 'wp_ajax_get_map_list', 'get_map_list' );

function filter_items() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query_vars['paged'] = $_POST['page'];
    $slug = $query_vars['pagename'];
    $page_type = $query_vars['pagetype'];
    $post_type = $slug;
    if( strstr( $slug, 'residents' ) || $page_type == 'sponsor' ):
    	$post_type = 'residents';
    elseif( $post_type == 'journal' ):
    	$post_type .= 's';
    endif;
    include( locate_template( 'sections/loops/' . $post_type . '.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_filter_items', 'filter_items' );
add_action( 'wp_ajax_filter_items', 'filter_items' );

function get_filter_count() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
	include( locate_template( 'other/filter-count.php' ) );
	die();
}
add_action( 'wp_ajax_nopriv_get_filter_count', 'get_filter_count' );
add_action( 'wp_ajax_get_filter_count', 'get_filter_count' );

/////////////////////////////////////
/////////////////////////////////////
//////////////LOOP QUERY/////////////
/////////////////////////////////////
/////////////////////////////////////

function add_query_vars_filter( $vars ){
  $vars[] .= 'when';
  $vars[] .= 'date';
  $vars[] .= 'year';
  $vars[] .= 'country';
  $vars[] .= 'from';
  $vars[] .= 'residency_program';
  $vars[] .= 'program';
  $vars[] .= 'type';
  $vars[] .= 'filter';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

/////////////////////////////////////
/////////////////////////////////////
//////////EVENT FILTERING////////////
/////////////////////////////////////
/////////////////////////////////////

function event_order( $wp_query ) {
	global $pagenow;
  	if ( is_admin() && 'edit.php' == $pagenow && !isset( $_GET['orderby'] )) {
  		$screen = get_current_screen();
  		$post_type = $screen->post_type;
  		if( $post_type == 'event' ):
	    	$wp_query->set( 'order', 'DESC' );
	    	$wp_query->set( 'meta_key', 'start_date' );
	    	$wp_query->set( 'orderby',  'meta_value_num' );
	    endif;
  	}
}
add_filter('pre_get_posts', 'event_order' );


function add_event_columns($columns) {
	unset(
		$columns['date']
	);
    return array_merge($columns, array(
		'event_type' => __( 'Event Type' ),
		'start_date' => __( 'Start Date' ),
		'end_date' => __( 'End Date' )
    ));
}
add_filter('manage_event_posts_columns' , 'add_event_columns');

function custom_event_column( $column, $post_id ) {
    switch ( $column ) {
		case 'event_type':
			$event_type = get_post_meta( $post_id , 'event_type' , true );
			if($event_type && $event_type != '-'):
				echo pretty( $event_type );
			else:
				echo '';
			endif;
			break;

		case 'start_date':
			$start_date = get_post_meta( $post_id , 'start_date' , true );
			if($start_date && $start_date != '-' && $start_date != 'Invalid date'):
				$start_date = new DateTime($start_date);
				echo $start_date->format('m/n/Y');
			else:
				echo '';
			endif;
			break;

		case 'end_date':
			$end_date = get_post_meta( $post_id , 'end_date' , true );
			if($end_date && $end_date != '-' && $end_date != 'Invalid date'):  
				$end_date = new DateTime($end_date);
				echo $end_date->format('m/n/Y');
			else:
				echo '';
			endif;
			break;
    }
}
add_action( 'manage_event_posts_custom_column' , 'custom_event_column', 10, 2 );


function register_event_sortable_columns( $columns ) {
    $columns['start_date'] = 'start_date';
    $columns['end_date'] = 'end_date';
    $columns['event_type'] = 'event_type';

    return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'register_event_sortable_columns' );

function event_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'start_date',
			'orderby' => 'meta_value_num'
		) );
	elseif ( isset( $vars['orderby'] ) && 'end_date' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'end_date',
			'orderby' => 'meta_value_num'
		) );
	elseif ( isset( $vars['orderby'] ) && 'event_type' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'event_type',
			'orderby' => 'meta_value'
		) );
	endif;
	if( isset($vars['order'] ) ):
		$vars = array_merge( $vars, array (
			'order' => $vars['order']
		) );
	endif;
	return $vars;
}
add_filter( 'request', 'event_column_orderby' );

function event_column_select() {
  	global $wpdb;
  	global $pagenow;
  	if ( is_admin() && $_GET['post_type'] == 'event' && $pagenow == 'edit.php' ) {

	    $event_types = array( 'iscp-talk', 'exhibition', 'open-studios', 'event', 'off-site-project' );
	    echo '<select name="event_type">';
	      echo '<option value="">' . __( 'Event Type', 'textdomain' ) . '</option>';
	    foreach( $event_types as $value ) {
	      $selected = ( !empty( $_GET['event_type'] ) && $_GET['event_type'] == $value ) ? 'selected="selected"' : '';
	      echo '<option ' . $selected . 'value="' . $value . '">' . pretty( $value ) . '</option>';
	    }
	    echo '</select>';
  	}
}
add_action( 'restrict_manage_posts', 'event_column_select' );

function event_column_filter( $query ) {
	global $pagenow;
  	if( is_admin() && $query->query['post_type'] == 'event' && $pagenow == 'edit.php' ) {
	    $qv = &$query->query_vars;
	    $qv['meta_query'] = array();

	    if( !empty( $_GET['event_type'] ) ) {
	      $qv['meta_query'][] = array(
	        'field' => 'event_type',
	        'value' => $_GET['event_type'],
	        'compare' => '=',
	        'type' => 'CHAR'
	      );
    	}
  	}
}
add_filter( 'parse_query','event_column_filter' );

/////////////////////////////////////
/////////////////////////////////////
////////RESIDENT FILTERING///////////
/////////////////////////////////////
/////////////////////////////////////
function add_resident_columns($columns) {
	// unset(
	// 	$columns['date']
	// );
    return array_merge($columns, array(
    	'country' =>__( 'Country'),
    	'sponsors' =>__( 'Sponsors'),
    	'residency_program' =>__( 'Program'),
		'start_date' => __('Start Date'),
	    'end_date' =>__( 'End Date'),
    ));
}
add_filter('manage_resident_posts_columns' , 'add_resident_columns');

function custom_resident_column( $column, $post_id ) {
    switch ( $column ) {
      	case 'country':
	        $country = get_field( 'country', $post_id )[0]->post_title;
	        echo $country;
	        break;

	    case 'sponsors':
	        $sponsor_list = '';
			if( have_rows( 'residency_dates', $post_id ) ):
				$sponsors = get_field( 'residency_dates', $post_id )[0]['sponsors'];
				if( $sponsors ):
					foreach ($sponsors as $index=>$sponsor):
						if( $index != 0 ):
							echo ', ';
						endif;
						$sponsor_name = $sponsor->post_title;
						echo $sponsor_name;
					endforeach;
				endif;
			endif;
			break;

      	case 'residency_program':
	        $program = get_post_meta( $post_id , 'residency_program' , true );
	        echo get_program_title( $program );
	        break;

		case 'start_date':
			$start_date = get_post_meta( $post_id , 'residency_dates_0_start_date' , true );
			if($start_date && $start_date != '' && $start_date != 'Invalid date') {  
				$start_date = new DateTime($start_date);
				echo $start_date->format('Y/m/d');
			} else {
				echo '';
			}
			break;

      	case 'end_date':
	        $end_date = get_post_meta( $post_id , 'residency_dates_0_end_date' , true );
	        if($end_date && $end_date != '' && $end_date != 'Invalid date') {  
	        	$end_date = new DateTime($end_date);
	        	echo $end_date->format('Y/m/d');
	        } else {
	        	echo '';
	        }
	        break;
    }
}
add_action( 'manage_resident_posts_custom_column' , 'custom_resident_column', 10, 2 );


function register_residents_sortable_columns( $columns ) {
    $columns['country'] = 'country';
    // $columns['residency_program'] = 'residency_program';
    // $columns['start_date'] = 'start_date';
    // $columns['end_date'] = 'end_date';
    return $columns;
}
add_filter( 'manage_edit-resident_sortable_columns', 'register_residents_sortable_columns' );



function resident_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'country' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'country',
			'orderby' => 'meta_value'
		) );
	elseif ( isset( $vars['orderby'] ) && 'program' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'residency_program',
			'orderby' => 'meta_value'
		) );
	elseif ( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'residency_dates_0_start_date',
			'orderby' => 'meta_value_num',
		) );
	elseif ( isset( $vars['orderby'] ) && 'end_date' == $vars['orderby'] ):
		$vars = array_merge( $vars, array(
			'meta_key' => 'residency_dates_0_end_date',
			'orderby' => 'meta_value_num'
		) );
	endif;
	if( isset( $vars['order'] ) ):
		$vars = array_merge( $vars, array (
			'order' => $vars['order']
		) );
	endif;
	return $vars;
}
add_filter( 'request', 'resident_column_orderby' );

function resident_column_select() {
  global $wpdb;
  global $pagenow;
  if ( is_admin() && $_GET['post_type'] == 'resident' && $pagenow == 'edit.php' ) {

    $residency_programs = array( 'international', 'ground_floor' );
    echo '<select name="residency_program">';
      echo '<option value="">' . __( 'Residency Program', 'textdomain' ) . '</option>';
    foreach( $residency_programs as $value ) {
      $selected = ( !empty( $_GET['residency_program'] ) && $_GET['residency_program'] == $value ) ? 'selected="selected"' : '';
      echo '<option ' . $selected . 'value="' . $value . '">' . get_program_title( $value ) . '</option>';
    }
    echo '</select>';

  }
}
add_action( 'restrict_manage_posts', 'resident_column_select' );

function resident_column_filter( $query ) {
	global $pagenow;
	if( is_admin() && $query->query['post_type'] == 'resident' && $pagenow == 'edit.php' ) {
		$qv = &$query->query_vars;
		$qv['meta_query'] = array();
		if( !empty( $_GET['residency_program'] ) ) {
		  $qv['meta_query'][] = array(
		    'field' => 'residency_program',
		    'value' => $_GET['residency_program'],
		    'compare' => '=',
		    'type' => 'CHAR'
		  );
	    }
  	}
}
add_filter( 'parse_query','resident_column_filter' );


/////////////////////////////////////
/////////////////////////////////////
/////////SPONSOR FILTERING///////////
/////////////////////////////////////
/////////////////////////////////////


function add_sponsor_columns($columns) {
    return array_merge($columns, array(
		'country' => __( 'Country' )
    ));
}
add_filter('manage_sponsor_posts_columns' , 'add_sponsor_columns');

function custom_sponsor_column( $column, $post_id ) {
    switch ( $column ) {
		case 'country':
			$country = get_field( 'country', $post_id )[0]->post_title;
			echo $country;
			break;
    }
}
add_action( 'manage_sponsor_posts_custom_column' , 'custom_sponsor_column', 10, 2 );


/////////////////////////////////////
/////////////////////////////////////
//////////HELPER QUESTIONS///////////
/////////////////////////////////////
/////////////////////////////////////
function is_current( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$resident = get_post( $id );
	$end_date = get_resident_end_date( $id );
	if($end_date):
		if($end_date > $today):
			return true;
		else:
			return false;
		endif;
	endif;
}
function is_past( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$resident = get_post( $id );
	$end_date = get_resident_end_date( $id );
	if($end_date):
		if($end_date < $today):
			return true;
		else:
			return false;
		endif;
	endif;
}
function is_ground_floor( $id ) {
	$residency_program = get_field( 'residency_program', $id );
	if( $residency_program == 'ground_floor' ):
		return true;
	else:
		return false;
	endif;
}	

/////////////////////////////////////
/////////////////////////////////////
///////////OUTPUT METHODS////////////
/////////////////////////////////////
/////////////////////////////////////
function get_status( $id ) {
	if( is_current( $id ) ):
		return 'current';
	elseif( is_past( $id ) ):
		return 'past';
	else:
		return null;
	endif;
}
function get_event_status( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$start_date = get_field( 'start_date', $id );
	$end_date = get_field( 'end_date', $id );
	if( $start_date > $today || $end_date > $today ):
		$status = 'upcoming';
	else:
		$status = 'past';
	endif;
	return $status;
}
function section_attr( $id, $slug, $classes, $title = null ) {
	$classes .= ' static';
	echo 'class="' . $slug . ' ' . $classes . '"';
	echo 'id="' . $slug . '" '; 
	echo 'data-slug="' . $slug . '" ';
	if( $id ):
		echo 'data-id="' . $id . '" ';
		$permalink = get_the_permalink( $id );

		if( $slug === 'current-residents' ):
			$permalink .= '?filter=current';
		elseif( $slug === 'past-residents' ):
			$permalink .= '?filter=past';
		endif;

		if( !$title ):
			$title = get_the_title( $id );
		endif;
		echo 'data-permalink="' . $permalink . '" ';
		echo 'data-title="' . $title . '"';
	endif;
}
function get_start_date_value( $id ) {
	$post_type = get_post_type( $id );
	if($post_type == 'resident'):
		return 'residency_dates_0_start_date';
	else:
		return 'start_date';
	endif;
}

function get_end_date_value( $id ) {
	$post_type = get_post_type( $id );
	if($post_type == 'resident'):
		return 'residency_dates_0_end_date';
	else:
		return 'end_date';
	endif;
}

function get_resident_end_date( $id ) {
	$residency_dates = get_field( 'residency_dates', $id );
	if( is_array( $residency_dates ) ):
		$end_date = $residency_dates[0]['end_date'];
		return $end_date;
	else:
		return null;
	endif;
}

function get_display_image( $id ) {
	if( has_post_thumbnail( $id ) ):
		$thumb_id = get_post_thumbnail_id( $id );
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, '', true);
		$thumb_url = $thumb_url_array[0];
		return $thumb_url;
	elseif( have_rows('gallery') ):
		return get_sub_field( 'image', $id )['url'];
	else:
		return '';
	endif;
}

function get_neighbor_journal_posts() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $post_id = $query_vars['id'];
    $direction = $query_vars['direction'];
    $count = 1;
	insert_neighbor_journal_posts( $post_id, $direction, $count );   	
    die();
}
add_action( 'wp_ajax_nopriv_get_neighbor_journal_posts', 'get_neighbor_journal_posts' );
add_action( 'wp_ajax_get_neighbor_journal_posts', 'get_neighbor_journal_posts' );

function insert_neighbor_journal_posts( $post_id, $direction, $count = 3 ) {
	$post = get_post( $post_id );
	$post_date = $post->post_date;

	if( $direction == 'new' ):
		$compare = '>';
		$order = 'ASC';
		$when = 'after';
	elseif ( $direction == 'old' ):
		$compare = '<';
		$order = 'DESC';
		$when = 'before';
	endif;

	$posts_args = array(
		'post_type' => 'journal',
		'posts_per_page' => $count,
		'type' => 'DATE',
		'date_query' => array( $when => $post_date ),
		'post__not_in' => array( $post_id ),
		'order' => $order,
		'orderby' => 'post_date'
	);

	$posts = new WP_Query( $posts_args );
	$last_page = $posts->max_num_pages;

	if ( $direction == 'new' ):
		$reverse_posts = array_reverse( $posts->posts );
		$posts->posts = $reverse_posts;
	endif;
	if( $posts->have_posts() ):
		while ( $posts->have_posts() ) : $posts->the_post();
			global $post;
			setup_postdata( $post );
			get_template_part( 'sections/journal' );
			wp_reset_postdata();
		endwhile;
	endif;

	wp_reset_query();
}


function insert_neighbor_events( $event_id, $direction, $count = 3 ) {
	$post = get_post( $event_id );
	$event_date = get_post_meta( $event_id , 'start_date', true );
	$today = new DateTime();
	$today = $today->format( 'Ymd' );
	if( $direction == 'new' ):
		// $compare = '>=';
		// $order = 'ASC';
		// $when = 'after';
	elseif ( $direction == 'old' ):
		// $compare = '<=';
		$order = 'DESC';
		$when = 'before';
	endif;

	$date_query = array(
		'relation' => 'AND',
		array(
			'key' => 'start_date',
			'compare' => '<',
			'value' => $today
		),
		array(
			'key' => 'end_date',
			'compare' => '<',
			'value' => $today
		)
	);

	$posts_args = array(
		'post_type' => 'event',
		'posts_per_page' => $count,
		'type' => 'DATE',
		'date_query' => array( $when => $event_date ),
		'post__not_in' => array( $event_id ),
		'orderby' => 'meta_value post_title',
		'order' => $order,
		'post_status' => 'publish',
		'meta_query' => array(
			array( 'key'=>'start_date' ),
			$date_query
		)
	);
	$posts = new WP_Query( $posts_args );
	$last_page = $posts->max_num_pages;

	if ( $direction == 'new' ):
		$reverse_posts = array_reverse( $posts->posts );
		$posts->posts = $reverse_posts;
	endif;

	if( $posts->have_posts() ):
		while ( $posts->have_posts() ) : $posts->the_post();
			global $post;
			setup_postdata( $post );
			get_template_part( 'sections/event' );
			wp_reset_postdata();
		endwhile;
	endif;

	wp_reset_query();
}




function get_neighbor_residents() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $resident_id = $query_vars['id'];
    $direction = $query_vars['direction'];
    $count = 1;
	insert_neighbor_residents( $resident_id, $direction, $count, $not );   	
    die();
}
add_action( 'wp_ajax_nopriv_get_neighbor_residents', 'get_neighbor_residents' );
add_action( 'wp_ajax_get_neighbor_residents', 'get_neighbor_residents' );

function insert_neighbor_residents( $resident_id, $direction, $count ) {
	$resident_name = get_the_title( $resident_id );
	$resident_end_date = get_post_meta( $resident_id , 'residency_dates_0_end_date', true );
	$resident_start_date = get_post_meta( $resident_id , 'residency_dates_0_start_date', true );
	$resident_studio = get_field( 'studio_number', $resident_id );
	$today = new DateTime();
	$today = $today->format( 'Ymd' );

	if( is_current( $resident_id ) ):

		$date_compare = '>';
		$direction_type = 'NUMERIC';
		$direction_key = 'studio_number';
		$direction_value = $resident_studio;
		$resident_orderby = 'meta_value_num post_title';
		$resident_meta_key = 'studio_number';

		if( $direction == 'prev' ):
			$direction_compare = '<';
			$order = 'DESC';
		elseif ( $direction == 'next' ):
			$direction_compare = '>';
			$order = 'ASC';
		endif;

	else:

		$date_compare = '<=';
		$direction_type = 'DATE';
		$direction_key = 'residency_dates_0_end_date';
		$direction_value = $resident_end_date;
		$resident_orderby = 'meta_value_num post_title';
		$resident_meta_key = 'residency_dates_0_end_date';

		if( $direction == 'prev' ):
			$direction_compare = '>=';
			$order = 'ASC';
		elseif ( $direction == 'next' ):
			// $direction_compare = '>=';
			// $order = 'DESC';
		endif;

	endif;

	$date_args = array(
		'type' => 'DATE',
		'key' => 'residency_dates_0_end_date',
		'compare' => $date_compare,
		'value' => $today
	);

	$has_bio = array(
		'type' => 'CHAR',
		'key' => 'bio',
		'compare' => '!=',
		'value' => ''
	);

	$direction_args = array(
		'type' => $direction_type,
		'key' => $direction_key,
		'compare' => $direction_compare,
		'value' => $direction_value
	);
	$resident_args = array(
		'post_type' => 'resident',
		'posts_per_page' => $count,
		'order' => $order,
		'orderby' => $resident_orderby,
		'meta_key' => $resident_meta_key,
		'post__not_in' => array( $resident_id ),
		'meta_query' => array( 
			'relation' => 'AND',
			$has_bio,
			$date_args,
			$direction_args
		)
	);
	$residents = new WP_Query( $resident_args );
	$last_page = $residents->max_num_pages;
	if ( $direction == 'prev' ):
		$reverse_residents = array_reverse( $residents->posts );
		$residents->posts = $reverse_residents;
	endif;

	if( $residents->have_posts() ):
		while ( $residents->have_posts() ) : $residents->the_post();
			global $post;
			setup_postdata( $post );
			get_template_part( 'sections/resident' );
			wp_reset_postdata();
		endwhile;
	endif;

	wp_reset_query();
}

//http://stackoverflow.com/questions/2915864/php-how-to-find-the-time-elapsed-since-a-date-time
function humanTiming( $time ) {
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => ' year',
        2592000 => ' month',
        604800 => ' week',
        86400 => ' day',
        3600 => ' hour',
        60 => ' minute',
        1 => ' second'
    );

    foreach ($tokens as $unit => $text):
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . $text . (($numberOfUnits>1)?'s':'') . ' ago';
    endforeach;
}


function makeClickableLinks( $s ) {
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a target="blank" rel="nofollow" href="$1" target="_blank">$1</a>', $s);
}

function get_tweets( $count ) {
	$about = get_page_by_path( 'about' );
	$handle = get_field( 'twitter', $about );

	include_once( get_template_directory() . '/libraries/twitteroauth/twitteroauth.php' );

	$twitter_customer_key           = 'w6jdx2IiW59vScHvUyYR6LJ5i';
	$twitter_customer_secret        = '4kYwLxdDXyIPi5ndLAht3Ln1oFX3iRTHxYqakghmeAGEVglTpY';
	$twitter_access_token           = '4343711140-4bb6E3bLjnIChxGwtD71disJm3C6H3Oo2u4qXFX';
	$twitter_access_token_secret    = '7NVIEwXODgcK6hB46UheZG9bPkFT63Ck8Fbwi4UaKzl1T';

	$connection = new TwitterOAuth($twitter_customer_key, $twitter_customer_secret, $twitter_access_token, $twitter_access_token_secret);

	$raw_tweets = $connection->get('statuses/user_timeline', array('screen_name' => $handle, 'count' => $count ) );
	$tweets = new ArrayObject();
	$twitter_url = 'http://twitter.com/'.$handle;
	echo '<div class="twitter">';
	echo '<div class="follow">';
	echo '<h3 class="title">';
	echo '<a href="'.$twitter_url.'" target="_blank">Follow us on Twitter @' . $handle . '</a>';
	echo '</h3>';
	echo '</div>';
	echo '<div class="tweets">';

	$counter = 0;
	foreach ( $raw_tweets as $tweet ):
		if( isset( $tweet->errors ) ):           
		    // $tweet = 'Error :'. $raw_tweets[$counter]->errors[0]->code. ' - '. $raw_tweets[$counter]->errors[0]->message;
		else:
		    $text = makeClickableLinks( $tweet->text );
		    $timestamp = strtotime( $tweet->created_at );
		    $elapsed = humanTiming( $timestamp );
		    $id = $tweet->id;
		    $url = 'http://twitter.com/' . $handle . '/status/' . $id;
		    echo '<div class="tweet">';
		    echo '<div class="text">';
			echo utf8_encode( $text );
			echo '</div>';
			echo '<a href="' . $url . '" target="_blank" class="timestamp">';
			echo $elapsed;
			echo '</a>';
			echo '</div>';
		endif;
	endforeach;

	echo '</div>';
	echo '</div>';
}

function get_event_date( $id ) {
	$today = new DateTime();
	$today_year = $today->format('Y');
	$_start_date = get_field( 'start_date', $id );
	$_end_date = get_field( 'end_date', $id );

	if ( $_start_date && $_start_date != '-' ):
		$start_date = new DateTime( $_start_date );
		$start_month = $start_date->format('F');
		$start_day_word = $start_date->format('l');
		$start_day = $start_date->format('j');
		$start_year = $start_date->format('Y');
	endif;

	if ( $_end_date && $_end_date != '-' ):
		$end_date = new DateTime( $_end_date );
		$end_month = $end_date->format('F');
		$end_day_word = $end_date->format('l');
		$end_day = $end_date->format('j');
		$end_year = $end_date->format('Y');
	endif;

	$time = get_field('time', $id);

	if ( $end_date ):
		if ( $today > $start_date && $today < $end_date ):
			$date_format = 'Through ' . $end_month . ' ' . $end_day;
		else:
			$date_format = $start_month . '&nbsp;' . $start_day;
			if ( $end_year && $start_year != $end_year ):
				$date_format .= ', ' . $start_year;
			endif;
			$date_format .= '&ndash;' . $end_month . '&nbsp;' . $end_day;
			$date_format .= ',&nbsp;' . $end_year;
		endif;
	else:
		$date_format = $start_month . ' ' . $start_day;
		$date_format .= ',&nbsp;' . $start_year;
		if( $time ):
			$date_format .= ', ' . $time;
		endif;
	endif;

	return $date_format;
}

function get_thumb( $id, $size = undefined ) {
	$thumbnail = get_display_image( $id );
	if($size == undefined):
		$size = 'thumb';
	endif;
	if( !$thumbnail ):
		$thumbnail = get_field( 'gallery', $id )[0]['image']['sizes'][$size];
	endif;
	if( !$thumbnail ):
		$thumbnail = false;
	endif;
	return $thumbnail;
}

function get_sponsors( $id, $index = 0 ) {
	$sponsor_list = '';
	if( have_rows( 'residency_dates', $id ) ):
		$sponsors = get_field( 'residency_dates', $id )[$index]['sponsors'];
		if( $sponsors ):
			foreach ($sponsors as $index=>$sponsor):
				if( $index != 0 ):
					$sponsor_list .= ', ';
				endif;
				$sponsor_name = $sponsor->post_title;
				$url = get_permalink( $sponsor->ID );
				if($url):
					$sponsor_list .= '<a href="' . $url . '">' . $sponsor_name . '</a>';
				else:
					$sponsor_list .= $sponsor_name;
				endif;
			endforeach;
		endif;
	endif;
	return $sponsor_list;
}

function get_countries( $id ) {
	$country_list = '';
	$residents_id = get_page_by_path( 'past-residents' )->ID;
	$residents_url = get_permalink( $residents_id );
	if( have_rows( 'country', $id ) ):
		$countries = get_field( 'country', $id );
		if( $countries ):
			foreach ($countries as $index=>$country):
				if( $index != 0 ):
					$country_list .= ', ';
				endif;
				$country_name = $country->post_title;
				$country_slug = $country->post_name;
				$url = $residents_url . '?filter=past&from=' . $country_slug;
				if($url):
					$country_list .= '<a href="' . $url . '">' . $country_name . '</a>';
				else:
					$country_list .= $country_name;
				endif;
			endforeach;
		endif;
	endif;
	return $country_list;
}

function get_orientation( $id ) {
	$imgmeta = wp_get_attachment_metadata( $id );
	if ($imgmeta['width'] > $imgmeta['height']):
		return 'landscape';
	else:
		return 'portait';
	endif;
}

function get_program_title( $program_slug ) {
	$program_slug = str_replace( '_', '-', $program_slug );
	$program_obj = get_page_by_path( 'residency-programs/' . $program_slug, OBJECT, 'page' );
	if($program_obj->post_parent != 0):
		$program_title = $program_obj->post_title;
		$program_id = $program_obj->ID;
		return $program_title;
	else:
		return;
	endif;
}

function pretty( $string ) {
	switch ( $string ) {
		case 'event':
			return 'Event';
			break;
		case 'iscp-talk':
			return 'ISCP Talk';
			break;
		case 'exhibition':
			return 'Exhibition';
			break;
		case 'open-studios':
			return 'Open Studios';
			break;
		case 'off-site-project':
			return 'Off-Site Project';
			break;
	}
}

function pretty_url( $url ) {
	$url = preg_replace( '#^https?://#', '', $url );
	$url = preg_replace( '#^www\.(.+\.)#i', '$1', $url );
	$url = preg_replace( '{/$}', '', $url );
	return $url;
}

function label_art() {
	$artist = get_sub_field( 'artist' );
	$title = get_sub_field( 'title' );
    $year = get_sub_field( 'year' );
    $medium = get_sub_field( 'medium' );
    $credit = get_sub_field( 'credit' );
    $photo_credit = get_sub_field( 'photo_credit' );
	$post_type = get_post_type();
	$dimensions = get_dimensions();

	if ( $post_type == 'resident' && !$artist ):
		$artist = get_the_title();
	endif;
    $caption = $artist;
    if( $title && $title != ' ' ):
    	if( $artist ):
    		$caption .= ', ';
    	endif;
    	$caption .= '<em>' . $title . '</em>';
    endif;
    if( $year && $year != ' ' ):
    	$caption .= ', ' . $year;
    endif;
    if( $medium && $medium != ' ' ):
    	$caption .= ', ' . $medium;
    endif;
    if( $dimensions && $dimensions != ' ' ):
    	$caption .= ', ' . $dimensions . '.';
    elseif( $caption ):
    	$caption .= '.';
    endif;
    if( $credit && $credit != ' ' ):
    	$caption .= ' ' . $credit . '.';
    endif;
    if( $photo_credit && $photo_credit != ' ' ):
    	$caption .= ' ' . $photo_credit . '.';
    endif;
    return $caption;
}

function get_dimensions() {
	$dimensions = get_sub_field( 'dimensions' );
    $width = get_sub_field( 'width' );
    $height = get_sub_field( 'height' );
    $depth = get_sub_field( 'depth' );
    $units = get_sub_field( 'units' );
    if( (!$width && !$height) || (!$width && !$depth) || (!$height && !$depth) ):
    	return $dimensions;
    endif;
    if( $units == 'in' ):

	    if( $width ):
	    	$ins .= dec_to_frac( $width );
	    	$cms .= in_to_cm( $width );
	    else:
	    	$ins .= 0;
	    	$cms .= 0;
	    endif;
	    $ins .= ' &times; ';
	    $cms .= ' &times; ';
	    if( $height ):
	    	$ins .= dec_to_frac( $height ) ?: 0;
	    	$cms .= in_to_cm( $height ) ?: 0;
	    else:
	    	$ins .= 0;
	    	$cms .= 0;
	    endif;
	    if( $depth ):
	    	$ins .= ' &times; ' . dec_to_frac( $depth );
	    	$cms .= ' &times; ' . in_to_cm( $depth );
	    endif;

	elseif( $units == 'cm' ):

		if( $width ):
	    	$cms .= dec_to_hund( $width ) ?: 0;
	    	$ins .= cm_to_in( $width ) ?: 0;
	    else:
	    	$ins .= 0;
	    	$cms .= 0;
	    endif;
	    $cms .= ' &times; ';
	    $ins .= ' &times; ';
	    if( $height ):
	    	$cms .= dec_to_hund( $height ) ?: 0;
	    	$ins .= cm_to_in( $height ) ?: 0;
	    else:
	    	$cms .= 0;
	    	$ins .= 0;
	    endif;
	    if( $depth ):
	    	$cms .= ' &times; ' . dec_to_hund( $depth );
	    	$ins .= ' &times; ' . cm_to_in( $depth );
	    endif;

	endif;


    return $ins . ' in. (' . $cms . ' cm)';
}

function convert_unit( $int, $unit ) {
	if( $unit == 'cm' ):
		return cm_to_in( $int );
	elseif( $unit == 'in' ):
		return in_to_cm( $int );
	endif;
}

function in_to_cm( $inches = 0 ) {
    return dec_to_hund( $inches / 0.393701 );
}

function cm_to_in( $cm = 0 ) {
    return dec_to_frac( $cm * 0.393701 );
}

function dec_to_frac( $float = 0 ) {
	$whole = floor ( $float );
    $decimal = $float - $whole;
    $leastCommonDenom = 16;
    $denominators = array ( 2, 3, 4, 8, 16 );
    $roundedDecimal = round ( $decimal * $leastCommonDenom ) / $leastCommonDenom;
    if ($roundedDecimal == 0)
	    return $whole;
    if ($roundedDecimal == 1)
	    return $whole + 1;
    foreach ( $denominators as $d ) {
	    if ($roundedDecimal * $d == floor ( $roundedDecimal * $d )) {
		    $denom = $d;
		    break;
	    }
    }
    $whole = ($whole == 0 ? '' : $whole);
    $frac = '<span class="fraction"><sup>' . ($roundedDecimal * $denom) . '</sup>/<sub>' . $denom . '</sub></span>';
    return $whole . $frac;
}

function dec_to_hund( $float = 0 ) {
	return round( $float, 2 );
}


$result = add_role( 'resident', __( 'Resident' ),
	array(
		'read' => true,
		'edit_posts' => false,
		'edit_pages' => false,
		'edit_others_posts' => false,
		'create_posts' => false,
		'manage_categories' => false,
		'publish_posts' => false,
		'install_plugins' => false,
		'update_plugin' => false,
		'update_core'
	)
);

function user_is_resident() {
	$user = wp_get_current_user();
	$allowed_roles = array('editor', 'administrator', 'author', 'resident');
	if( array_intersect( $allowed_roles, $user->roles ) ):
		return true;
	else:
		return false;
	endif;
}

function query_url( $key, $value, $url, $filter = null, $remove = false ) {
	$this_query = array( $key => $value );
	$url = explode('#', $url)[0];
	$parsed_url = parse_url($url);
	parse_str($parsed_url['query'], $params);
	$params = array_merge( $params, $this_query );
	$url = strtok($url, '?');
	if( $remove ):
		unset( $params[$key] );
	endif;
	foreach($params as $_key => $_value):
		if( array_key_exists( $_key, $params ) ):
			unset( $params[$key] );
		endif;
		if( strpos($url, '?') !== FALSE ):
			$url .= '&';
		else:
			$url .= '?';
		endif;
		$url .= $_key . '=' . $_value;
	endforeach;
	return $url;
}

function get_resident_count( $type, $value, $query = null ) {
	$meta_query = array();
	unset( $query['paged'] );
	$query['posts_per_page'] = -1;
	if( $type == 'country' ):
		$country_query = array(
			'key' => 'country',
			'value' => '"' . $value . '"',
			'compare' => 'LIKE'
		);
		$meta_query = array_merge( $meta_query, $country_query );
	endif;

	if( $type == 'year' ):
		$year = $value;
		$year_begin = $year . '0101';
		$year_end = $year . '1231';
		$year_range = array( $year_begin, $year_end );
		$year_query = array(
			'key' => 'residency_dates_0_start_date',
			'type' => 'DATE',
			'value' => $year_range,
			'compare' => 'BETWEEN'
		);
		$meta_query = array_merge( $meta_query, $year_query );
	endif;

	if( $type == 'program' ):
		$program_query = array(
			'key' => 'residency_program',
			'type' => 'CHAR',
			'value' => $value,
			'compare' => 'LIKE'
		);
		$meta_query = array_merge( $meta_query, $program_query );
	endif;

	if( $type == 'type' ):
		$type_query = array(
			'key' => 'resident_type',
			'type' => 'CHAR',
			'value' => $value,
			'compare' => 'LIKE'
		);
		$meta_query = array_merge( $meta_query, $type_query );
	endif;	
	$empty_index = sizeof( $query['meta_query'] ) + 1;
	$query['meta_query'][$empty_index] = $meta_query;
	$query = new WP_Query( $query );
	$count = $query->found_posts;
	return $count;
}

function get_event_count( $type, $value ) {
	$meta_query = array();

	if( $type == 'type' ):
		$event_type_query = array(
			'key' => 'event_type',
			'type' => 'CHAR',
			'value' => $value,
			'compare' => 'LIKE'
		);
		$meta_query = array_merge( $meta_query, $event_type_query );
	endif;

	if( $type == 'year' ):
		$year = $value;
		$year_begin = $year . '0101';
		$year_end = $year . '1231';
		$year_range = array( $year_begin, $year_end );
		$year_query = array(
			'relation' => 'OR',
			array(
				'key' => 'start_date',
				'type' => 'DATE',
				'value' => $year_range,
				'compare' => 'BETWEEN'
			),
			array(
				'key' => 'end_date',
				'type' => 'DATE',
				'value' => $year_range,
				'compare' => 'BETWEEN'
			),
			array(
				'key' => 'date',
				'type' => 'DATE',
				'value' => $year_range,
				'compare' => 'BETWEEN'
			)
		);
		$meta_query = array_merge( $meta_query, $year_query );
	endif;

	//only filter past events
	$today = date('Ymd');
	$date_query = array(
		'relation' => 'AND',
		array(
			'key' => 'start_date',
			'compare' => '<',
			'value' => $today
		),
		array(
			'key' => 'end_date',
			'compare' => '<',
			'value' => $today
		)
	);

	$query_args = array(
		'post_type' => 'event',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array( $meta_query, $date_query )
	);

	$query = new WP_Query( $query_args );
	$count = $query->found_posts;
	return $count;
}


function get_sponsor_count( $type, $value ) {
	if( $type == 'country' ):
		$country_query = array(
			'key' => 'country',
			'value' => '"' . $value . '"',
			'compare' => 'LIKE'
		);
	endif;

	$query_args = array(
		'post_type' => 'sponsor',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array( $country_query )
	);
	$query = new WP_Query( $query_args );
	$count = $query->found_posts;
	return $count;
}

function get_contributor_count( $type, $value, $page_query ) {
	$meta_query = array();

	if( $type == 'country' ):
		$country_query = array(
			'key' => 'country',
			'value' => '"' . $value . '"',
			'compare' => 'LIKE'
		);
		$meta_query = array_merge( $meta_query, $country_query );
	endif;

	$query_args = array(
		'post_type' => 'contributor',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array( $meta_query, $page_query )
	);
	$query = new WP_Query( $query_args );
	$count = $query->found_posts;
	return $count;
}

function unsetRepeat( $query = null, $value ) {
	if( $query['meta_query'] ):
		foreach( $query['meta_query'] as $index=>$array ):
			if( is_array( $array ) ):			
				if( array_key_exists( 'key', $array ) ):
					foreach( $array as $key=>$nestedValue ):
						if( $key == 'key' && $nestedValue == $value ):
							unset( $query['meta_query'][$index] );
						endif;
					endforeach;
				endif;
			endif;
		endforeach;
		return $query;
	endif;
}

add_filter( 'posts_orderby', function( $orderby, \WP_Query $q ) {
    if( 'last_name' === $q->get( 'orderby' ) && $get_order =  $q->get( 'order' ) ):
        if( in_array( strtoupper( $get_order ), ['ASC', 'DESC'] ) ):
            global $wpdb;
            $orderby = " SUBSTRING_INDEX( {$wpdb->posts}.post_title, ' ', -1 ) " . $get_order;
        endif;
    endif;
    return $orderby;
}, PHP_INT_MAX, 2 );


function add_parent_class( $items ) {
    $parents = wp_list_pluck( $items, 'menu_item_parent');
    foreach ( $items as $item )
        in_array( $item->ID, $parents ) && $item->classes[] = 'parent';
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'add_parent_class' );

function new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

function add_favicon() {
  	$favicon_url = get_template_directory_uri() . '/assets/images/favicons/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');


function remove_menu_items() {
    remove_menu_page( 'index.php' );
    remove_menu_page( 'edit.php' );
    remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menu_items' );

function reorder_menu_items( $menu_order ) {
    return array(
        'edit.php?post_type=resident',
        'edit.php?post_type=event',
        'edit.php?post_type=journal',
        'edit.php?post_type=sponsor',
        'edit.php?post_type=contributor',
        'edit.php?post_type=country',
        'edit.php?post_type=page',
        'upload.php'
    );
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'reorder_menu_items' );

function wpdocs_register_my_custom_menu_page() {
	$lock_icon = get_template_directory_uri() . '/assets/images/lock-white-small.svg';


	$to_do_id = get_page_by_path( 'greenroom/to-do' )->ID;
    add_menu_page(
        __( 'To-Do', 'textdomain' ),
        'To-Do',
        'manage_options',
        'post.php?post=' . $to_do_id . '&action=edit',
        '',
        $lock_icon,
        10
    );

    $staff_messages_id = get_page_by_path( 'greenroom/staff-messages' )->ID;
    add_menu_page(
        __( 'Staff Messages', 'textdomain' ),
        'Staff Messages',
        'manage_options',
        'post.php?post=' . $staff_messages_id . '&action=edit',
        '',
        $lock_icon,
        11
    );

    $at_iscp_id = get_page_by_path( 'greenroom/at-iscp' )->ID;
    add_menu_page(
        __( 'At ISCP', 'textdomain' ),
        'At ISCP',
        'manage_options',
        'post.php?post=' . $at_iscp_id . '&action=edit',
        '',
        $lock_icon,
        12
    );

    $in_nyc_id = get_page_by_path( 'greenroom/in-nyc' )->ID;
    add_menu_page(
        __( 'In NYC', 'textdomain' ),
        'In NYC',
        'manage_options',
        'post.php?post=' . $in_nyc_id . '&action=edit',
        '',
        $lock_icon,
        13
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

function greenroom_login_redirect( $redirect_to, $request, $user ) {
	global $user;
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'resident', $user->roles ) ) {
			$greenroom_id = get_page_by_path( 'greenroom' )->ID;
			$greenroom_url = get_permalink( $greenroom_id );
			return $greenroom_url;
		} else {
			return $redirect_to;
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'greenroom_login_redirect', 10, 3 );