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
	
	wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
	wp_enqueue_script( 'main' );
}
add_action( 'wp_enqueue_scripts', 'iscp_scripts' );


show_admin_bar(false);
add_theme_support( 'post-thumbnails' ); 

/////////////////////////////////////
/////////////////////////////////////
//////////EVENT FILTERING////////////
/////////////////////////////////////
/////////////////////////////////////


// http://www.paulund.co.uk/add-custom-post-meta-data-to-list-post-table
// add_filter( 'manage_${post_type}_posts_columns', 'add_new_columns');

function add_event_columns($columns) {
	unset(
		$columns['date']
	);
    return array_merge($columns, array(
		'event_type' => __( 'Event Type' ),
		'event_date' => __( 'Date' )
    ));
}
add_filter('manage_event_posts_columns' , 'add_event_columns');



function custom_event_column( $column, $post_id ) {
    switch ( $column ) {
      case 'event_type':
        $event_type = get_post_meta( $post_id , 'event_type' , true );
        if($event_type && $event_type != '') { 
        	echo $event_type;
        } else {
        	echo '';
        }
        break;

      case 'event_date':
        $start_date = get_post_meta( $post_id , 'start_date' , true );
        $event_date = get_post_meta( $post_id , 'date' , true );
        if($start_date && $start_date != '-' && $start_date != 'Invalid date') {  
        	$start_date = new DateTime($start_date);
        	echo $start_date->format('Y/m/d');
        } else if($event_date && $event_date != '-' && $event_date != 'Invalid date') {  
        	$event_date = new DateTime($event_date);
        	echo $event_date->format('Y/m/d');
        } else {
        	echo '';
        }
        break;
    }
}
add_action( 'manage_event_posts_custom_column' , 'custom_event_column', 10, 2 );


function register_event_sortable_columns( $columns ) {
    $columns['event_date'] = 'Event Date';
    $columns['event_type'] = 'Event Type';

    return $columns;
}
add_filter( 'manage_edit-event_sortable_columns', 'register_event_sortable_columns' );



function event_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'EventDate' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'date',
			'orderby' => 'meta_value',
			'order' => 'desc'
		) );
	} else if ( isset( $vars['orderby'] ) && 'EventType' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'event_type',
			'orderby' => 'meta_value',
			'order' => 'asc'
		) );
	}
	return $vars;
}
add_filter( 'request', 'event_column_orderby' );

/////////////////////////////////////
/////////////////////////////////////
////////RESIDENT FILTERING///////////
/////////////////////////////////////
/////////////////////////////////////
function add_resident_columns($columns) {
	unset(
		$columns['date']
	);
    return array_merge($columns, array(
    	'country' =>__( 'Country'),
    	'sponsor' =>__( 'Sponsor'),
		'start_date' => __('Start Date'),
	    'end_date' =>__( 'End Date'),
	    'old_id' =>__( 'Reference ID')
    ));
}
add_filter('manage_resident_posts_columns' , 'add_resident_columns');



function custom_resident_column( $column, $post_id ) {
    switch ( $column ) {
      case 'country':
        $country = get_post_meta( $post_id , 'country_temp' , true );
        echo $country;
        break;

      case 'sponsor':
        $sponsor = get_post_meta( $post_id , 'sponsor_temp' , true );
        echo $sponsor;
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

      case 'old_id':
        $old_id = get_post_meta( $post_id , 'old_id' , true );
        echo $old_id;
        break;
    }
}
add_action( 'manage_resident_posts_custom_column' , 'custom_resident_column', 10, 2 );


function register_residents_sortable_columns( $columns ) {
    $columns['country'] = 'Country';
    $columns['sponsor'] = 'Sponsor';
    $columns['start_date'] = 'Start Date';
    $columns['end_date'] = 'End Date';
    $columns['old_id'] = 'Reference ID';

    return $columns;
}
add_filter( 'manage_edit-resident_sortable_columns', 'register_residents_sortable_columns' );



function resident_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'Country' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'country_temp',
			'orderby' => 'meta_value',
			'order' => 'asc'
		) );
	} else if ( isset( $vars['orderby'] ) && 'Sponsor' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'sponsor_temp',
			'orderby' => 'meta_value',
			'order' => 'asc'
		) );
	} else if ( isset( $vars['orderby'] ) && 'StartDate' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'start_date',
			'orderby' => 'meta_value',
			'order' => 'desc'
		) );
	} else if ( isset( $vars['orderby'] ) && 'EndDate' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'end_date',
			'orderby' => 'meta_value',
			'order' => 'desc'
		) );
	} else if ( isset( $vars['orderby'] ) && 'ReferenceID' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'old_id',
			'orderby' => 'meta_value_num',
			'order' => 'asc'
		) );
	}
	return $vars;
}
add_filter( 'request', 'resident_column_orderby' );



/////////////////////////////////////
/////////////////////////////////////
///////QUERY FILTER VARIABLES////////
/////////////////////////////////////
/////////////////////////////////////
function add_query_vars_filter( $vars ){
  $vars[] = 'when';
  $vars[] .= 'country_temp';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


/////////////////////////////////////
/////////////////////////////////////
//////////HELPER QUESTIONS///////////
/////////////////////////////////////
/////////////////////////////////////
function is_alumni( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$residents = get_post( $id );
	$end_date = get_field('residency_dates_0_end_date', $id);
	if($end_date > $today) {
		return false;
	} else {
		return true;
	}
}

function is_current( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$residents = get_post( $id );

	$ed = get_end_date_value( $id );

	$end_date = get_field($ed, $id);
	if($end_date < $today) {
		return true;
	} else {
		return false;
	}
}
function is_ground_floor( $id ) {
	return false;
}	

/////////////////////////////////////
/////////////////////////////////////
///////////HELPER METHODS////////////
/////////////////////////////////////
/////////////////////////////////////
function format_date( $id ) {
	$sd = get_start_date_value( $id );
	$ed = get_end_date_value( $id );
	$start_date_dt = new DateTime(get_field($sd, $id));
	$start_date = $start_date_dt->format('M d, Y');

	if($ed != '') {
		$end_date_dt = new DateTime(get_field($ed, $id));
		$end_date = $end_date_dt->format('M d, Y');
		$date = $start_date . ' — ' . $end_date;
	} else {
		$date = $start_date;
	}

	return $date;
}

function get_start_date_value( $id ) {
	$post_type = get_field('post_type', $id);
	if($post_type == 'resident') {
		return 'residency_dates_0_start_date';
	} else {
		return 'start_date';
	}
}

function get_end_date_value( $id ) {
	$post_type = get_field('post_type', $id);
	if($post_type == 'resident') {
		return 'residency_dates_0_end_date';
	} else {
		return 'end_date';
	}
}

function get_display_image( $id ) {
	if( has_post_thumbnail( $id ) ) {
		$thumb_id = get_post_thumbnail_id( $id );
		$thumb_url_array = wp_get_attachment_image_src($thumb_id, '', true);
		$thumb_url = $thumb_url_array[0];
		return $thumb_url;
	} elseif( have_rows('gallery') ) {
		return get_sub_field( 'image', $id )['url'];
	} else {
		return '';
	}
}

function get_next_residents( $count ) {
	$country = get_query_var( 'country_temp' );
	$year = get_query_var( 'when' );

	if( $country ) {
		$filter_key = 'country_temp';
		$filter_query = array(
			'key' => 'country_temp',
			'type' => 'CHAR',
			'value' => $country,
			'compare' => 'LIKE'
		);
		$append_query = '?country_temp=' . $country;
	} elseif( $year ) {
		$year_begin = $year . '0101';
		$year_end = $year . '1231';
		$year_range = array( $year_begin, $year_end );
		$filter_query = array(
			'key' => 'start_date',
			'type' => 'DATE',
			'value' => $year_range,
			'compare' => 'BETWEEN'
		);
		$append_query = '?when=' . $year;
	}

	$today = new DateTime();
	$today = $today->format( 'Ymd' );

	$resident_id = get_the_ID();

	if ( is_current( $resident_id ) ) {
		$page_query = array(
			'key' => 'residency_dates_0_end_date',
			'type' => 'DATE',
			'value' => $today,
			'compare' => '>='
		);
	} else if ( is_alumni($resident_id ) ) {
		$page_query = array(
			'key' => 'residency_dates_0_end_date',
			'type' => 'DATE',
			'value' => $today,
			'compare' => '<='
		);
	} else if ( is_ground_floor( $resident_id ) ) {
		$page_query = array(
			'key' => 'ground_floor',
			'type' => 'BINARY',
			'value' => 1,
			'compare' => '='
		);
	}

	
	$args = array(
		'post_type' => 'resident',
		'posts_per_page' => $count,
		'meta_query' => array( $page_query, $filter_query )
	);

	$next_residents = new WP_Query( $args );
	return $next_residents;
}