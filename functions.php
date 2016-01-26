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
	wp_register_script( 'ajax', get_template_directory_uri() . '/assets/js/ajax.js', array( 'jquery' ) );
	wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ) );
	wp_enqueue_script( 'transit' );
	wp_enqueue_script( 'ajax' );
	wp_enqueue_script( 'main' );
	global $wp_query;
	wp_localize_script( 'ajax', 'ajaxpagination', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'query_vars' => json_encode( $wp_query->query )
	));
}
add_action( 'wp_enqueue_scripts', 'iscp_scripts' );

show_admin_bar(false);
add_theme_support( 'post-thumbnails' ); 
add_image_size( 'thumb', 500, 350, true );
add_image_size( 'slider', 9999, 500, false );


/////////////////////////////////////
/////////////////////////////////////
///////////AJAX AJAX AJAX////////////
/////////////////////////////////////
/////////////////////////////////////

function load_more() {
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query_vars['paged'] = $_POST['page'];
    $slug = $query_vars['pagename'];
    $post_type = $slug;
    if( strstr( $slug, 'residents' ) ):
    	$post_type = 'residents';
    endif;
    include( locate_template( 'sections/loops/' . $post_type . '.php' ) );
    die();
}
add_action( 'wp_ajax_nopriv_load_more', 'load_more' );
add_action( 'wp_ajax_load_more', 'load_more' );


/////////////////////////////////////
/////////////////////////////////////
//////////////LOOP QUERY/////////////
/////////////////////////////////////
/////////////////////////////////////

function add_query_vars_filter( $vars ){
  $vars[] .= 'when';
  $vars[] .= 'date';
  $vars[] .= 'country';
  $vars[] .= 'from';
  $vars[] .= 'residency_program';
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
			'meta_key' => 'event_date',
			'orderby' => 'meta_value_num'
		) );
	} else if ( isset( $vars['orderby'] ) && 'EventType' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'event_type',
			'orderby' => 'meta_value'
		) );
	}
	$vars = array_merge( $vars, array (
		'order' => $vars['order']
	) );
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
    	'residency_program' =>__( 'Program'),
    	'sponsor' =>__( 'Sponsor'),
		'start_date' => __('Start'),
	    'end_date' =>__( 'End'),
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

      case 'residency_program':
        $program = get_post_meta( $post_id , 'residency_program' , true );
        echo pretty($program);
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
    $columns['country'] = 'Country';
    $columns['sponsor'] = 'Sponsor';
    $columns['residency_program'] = 'Program';
    $columns['start_date'] = 'Start Date';
    $columns['end_date'] = 'End Date';

    return $columns;
}
add_filter( 'manage_edit-resident_sortable_columns', 'register_residents_sortable_columns' );



function resident_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'Country' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'country_temp',
			'orderby' => 'meta_value'
		) );
	} else if ( isset( $vars['orderby'] ) && 'Sponsor' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'sponsor_temp',
			'orderby' => 'meta_value'
		) );
	} else if ( isset( $vars['orderby'] ) && 'Program' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'residency_program',
			'orderby' => 'meta_value'
		) );
	} else if ( isset( $vars['orderby'] ) && 'StartDate' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'start_date',
			'orderby' => 'meta_value_num',
		) );
	} else if ( isset( $vars['orderby'] ) && 'EndDate' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'end_date',
			'orderby' => 'meta_value_num'
		) );
	}
	$vars = array_merge( $vars, array (
		'order' => $vars['order']
	) );
	return $vars;
}
add_filter( 'request', 'resident_column_orderby' );


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
	if($end_date) {
		if($end_date > $today) {
			return true;
		} else {
			return false;
		}
	}
}
function is_past( $id ) {
	$today = new DateTime();
	$today = $today->format('Ymd');
	$resident = get_post( $id );
	$end_date = get_resident_end_date( $id );
	if($end_date) {
		if($end_date < $today) {
			return true;
		} else {
			return false;
		}
	}
}
function is_ground_floor( $id ) {
	$residency_program = get_field( 'residency_program', $id );
	if( $residency_program == 'ground_floor' ) {
		return true;
	} else {
		return false;
	}
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

	if(is_numeric($end_date)):
		$check_date = $end_date;
	elseif(is_numeric($start_date)):
		$check_date = $start_date;
	endif;
	if( $check_date > $today ):
		$status = 'upcoming';
	elseif( $check_date < $today ):
		$status = 'past';
	endif;

	return $status;
}
function section_attr( $id, $slug, $classes ) {
	$classes .= ' static';
	echo 'class="' . $slug . ' ' . $classes . '"';
	echo 'id="' . $slug . '" '; 
	echo 'data-slug="' . $slug . '" ';
	if( $id ):
		echo 'data-id="' . $id . '" ';
		$permalink = get_the_permalink( $id );
		$title = get_the_title( $id );
		echo 'data-permalink="' . $permalink . '" ';
		echo 'data-title="' . $title . '"';
	endif;
}
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
	$post_type = get_post_type( $id );
	if($post_type == 'resident') {
		return 'residency_dates_0_start_date';
	} else {
		return 'start_date';
	}
}

function get_end_date_value( $id ) {
	$post_type = get_post_type( $id );
	if($post_type == 'resident') {
		return 'residency_dates_0_end_date';
	} else {
		return 'end_date';
	}
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

function get_residents( $resident_id, $direction, $count ) {
	$resident_end_date = get_resident_end_date( $resident_id );
	$resident_studio = get_field( 'studio_number', $resident_id );
	$today = new DateTime();
	$today = $today->format( 'Ymd' );

	if( is_current( $resident_id ) ):
		$date_compare = '>=';
		$direction_key = 'studio_number';
		$direction_value = $resident_studio;
		$resident_orderby = 'meta_value_num';
		$resident_meta_key = 'studio_number';
	else:
		$date_compare = '<=';
	endif;

	$date_args = array(
		'type' => 'DATE',
		'key' => 'residency_dates_0_end_date',
		'compare' => $date_compare,
		'value' => $today
	);

	if( $direction == 'prev' ):
		$direction_compare = '<';
	elseif ( $direction == 'next' ):
		$direction_compare = '>';
	endif;

	$direction_args = array(
		'type' => 'NUMERIC',
		'key' => $direction_key,
		'compare' => $direction_compare,
		'value' => $direction_value
	);

	$resident_args = array(
		'post_type' => 'resident',
		'posts_per_page' => $count,
		'order' => 'DESC',
		'orderby' => $resident_orderby,
		'meta_key' => $resident_meta_key,
		'meta_query' => array( $date_args, $direction_args )
	);

	$residents = new WP_Query( $resident_args );
	$reverse_residents = array_reverse($residents->posts);
	$residents->posts = $reverse_residents;

    while ( $residents->have_posts() ) : $residents->the_post();
		global $post;
		setup_postdata( $post );
		get_template_part('sections/resident');
		wp_reset_postdata();
	endwhile;
}

//http://stackoverflow.com/questions/2915864/php-how-to-find-the-time-elapsed-since-a-date-time
function humanTiming($time) {
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'y',
        2592000 => 'm',
        604800 => 'wk',
        86400 => 'day',
        3600 => 'hr',
        60 => 'min',
        1 => 'sec'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.$text.(($numberOfUnits>1)?'s':'');
    }
}


function makeClickableLinks($s) {
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
	echo '<a href="'.$twitter_url.'" target="_blank">';
	echo '<h3 class="title">Follow us on Twitter @' . $handle . '</h3>';
	echo '</a>';
	echo '</div>';
	echo '<div class="tweets">';

	$counter = 0;
	foreach ($raw_tweets as $tweet) {
		if(isset($tweet->errors)) {           
		    // $tweet = 'Error :'. $raw_tweets[$counter]->errors[0]->code. ' - '. $raw_tweets[$counter]->errors[0]->message;
		} else {
		    $text = makeClickableLinks($tweet->text);
		    $timestamp = strtotime($tweet->created_at);
		    $elapsed = humanTiming($timestamp);
		    $id = $tweet->id;
		    $url = 'http://twitter.com/'.$handle.'/status/'.$id;
		    echo '<div class="tweet">';
		    echo '<div class="text">';
			echo $text;
			echo '</div>';
			echo '<a href="'.$url.'" target="_blank" class="timestamp">';
			echo $elapsed;
			echo '</a>';
			echo '</div>';
		}
	}

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
		$start_day = $start_date->format('d');
		$start_year = $start_date->format('Y');
	endif;

	if ( $_end_date && $_end_date != '-' ):
		$end_date = new DateTime( $_end_date );
		$end_month = $end_date->format('F');
		$end_day_word = $end_date->format('l');
		$end_day = $end_date->format('d');
		$end_year = $end_date->format('Y');
	endif;

	$start_time = get_field('start_time', $id);
	$end_time = get_field('end_time', $id);

	if ( $end_date ):
		if ( $today > $start_date ):
			$date_format = 'Thru ' . $end_month . ' ' . $end_day;
		else:
			$date_format = $start_month . '&nbsp;' . $start_day;
			if ( $start_year != $today_year ):
				$date_format .= ', ' . $start_year;
			endif;
			$date_format .= ' &ndash; ' . $end_month . '&nbsp;' . $end_day;
			if ( $end_year != $today_year ):
				$date_format .= ',&nbsp;' . $end_year;
			endif;
		endif;
	else:
		$date_format = $start_month . ' ' . $start_day;
		if($start_year != $today_year):
		 	$date_format .= ',&nbsp;' . $start_year;
		endif;
		if($start_time):
			$date_format .= '</br>' . $start_time;
			if($end_time):
				$date_format .= ' &ndash; ' . $end_time;
			endif;
		endif;
	endif;

	return $date_format;
}

function get_thumb( $id, $size = undefined ) {
	$thumbnail = get_display_image( $id );
	if($size == undefined):
		$size = 'thumb';
	endif;
	if( !$thumbnail ) {
		$thumbnail = get_field( 'gallery', $id )[0]['image']['sizes'][$size];
	}
	if( !$thumbnail ) {
		$thumbnail = get_template_directory_uri() . '/assets/images/placeholder.svg';
	}
	return $thumbnail;
}

function get_sponsors( $id ) {
	$sponsor_list = '';
	if( have_rows( 'residency_dates', $id ) ):
		$sponsors = get_field( 'residency_dates', $id )[0]['sponsors'];
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

function get_orientation( $id ) {
	$imgmeta = wp_get_attachment_metadata( $id );
	if ($imgmeta['width'] > $imgmeta['height']) {
		return 'landscape';
	} else {
		return 'portait';
	}
}

function pretty($string) {
	switch ($string) {
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
		case 'international':
			return 'International Artist & Curator Program';
			break;
		case 'ground_floor':
			return 'Ground Floor Residencies for New York City Artists';
			break;
	}
}

function pretty_short($string) {
	switch ($string) {
		case 'international':
			return 'International';
			break;
		case 'ground_floor':
			return 'Ground Floor';
			break;
	}
}

function pretty_url( $url ) {
	$url = preg_replace( '#^https?://#', '', $url );
	$url = preg_replace( '#^www\.(.+\.)#i', '$1', $url );
	$url = preg_replace( '{/$}', '', $url );
	return $url;
}

function label_art($id) {
	$artist = get_sub_field( 'artist' );
	$title = get_sub_field( 'title' );
    $year = get_sub_field( 'year' );
    $medium = get_sub_field( 'medium' );
    $dimensions = get_sub_field( 'dimensions' );
    $credit = get_sub_field( 'credit' );
	$post_type = get_post_type( get_the_ID() );

	if ( $post_type == 'resident' && !$image_artist ) {
		$artist = get_the_title();
	}
    $caption = $artist;
    if( $title && $title != ' ' ) {
    	$caption .= ', <em>' . $title . '</em>';
    }
    if( $year && $year != ' ' ) {
    	$caption .= ', ' . $year;
    }
    if( $medium && $medium != ' ' ) {
    	$caption .= ', ' . $medium;
    }
    if( $dimensions && $dimensions != ' ' ) {
    	$caption .= ', ' . $dimensions;
    }
    if( $credit && $credit != ' ' ) {
    	$caption .= '. Courtesy of ' . $credit;
    }
    if( $photo_credit && $photo_credit != ' ' ) {
    	$caption .= '. Photo courtesy of ' . $photo_credit;
    }
    return $caption;
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
	if( array_intersect($allowed_roles, $user->roles ) ) {
		return true;
	} else {
		return false;
	}
}

function resident_count_by_country( $country_id, $page_query ) {
	$country_meta_query = array(
		'key' => 'country',
		'value' => '"' . $country_id . '"',
		'compare' => 'LIKE'
	);
	$country_query_args = array(
		'post_type' => 'resident',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'meta_query' => array( $country_meta_query, $page_query )
	);
	$country_query = new WP_Query( $country_query_args );
	$country_count = $country_query->found_posts;
	return $country_count;
}

function resident_count_by_year( $year, $page_query ) {
	$year_begin = $year . '0101';
	$year_end = $year . '1231';
	$year_range = array( $year_begin, $year_end );
	$year_meta_query = array(
		'key' => 'residency_dates_0_start_date',
		'type' => 'DATE',
		'value' => $year_range,
		'compare' => 'BETWEEN'
	);
	$year_query_args = array(
		'post_type' => 'resident',
		'meta_query' => array( $year_meta_query, $page_query )
	);
	$year_query = new WP_Query( $year_query_args );
	$year_count = $year_query->found_posts;
	return $year_count;
}

function resident_count_by_program( $program, $page_query ) {
	$program_meta_query = array(
		'key' => 'residency_program',
		'type' => 'CHAR',
		'value' => $program,
		'compare' => $compare
	);
	$program_query_args = array(
		'post_type' => 'resident',
		'meta_query' => array( $program_meta_query, $page_query )
	);
	$program_query = new WP_Query( $program_query_args );
	$program_count = $program_query->found_posts;
	return $program_count;
}

function event_count_by_type( $event_type ) {
	$event_type_meta_query = array(
		'key' => 'event_type',
		'type' => 'CHAR',
		'value' => $event_type,
		'compare' => 'LIKE'
	);
	$event_type_query_args = array(
		'post_type' => 'event',
		'meta_query' => array( $event_type_meta_query )
	);
	$event_type_query = new WP_Query( $event_type_query_args );
	$event_type_count = $event_type_query->found_posts;
	return $event_type_count;
}


function event_count_by_year( $year ) {
	$year_begin = $year . '0101';
	$year_end = $year . '1231';
	$year_range = array( $year_begin, $year_end );
	$year_meta_query = array(
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

	$year_query_args = array(
		'post_type' => 'event',
		'meta_query' => $year_meta_query
	);
	$year_query = new WP_Query( $year_query_args );
	$year_count = $year_query->found_posts;
	return $year_count;
}

add_filter( 'posts_orderby', function( $orderby, \WP_Query $q ) {
    if( 'last_name' === $q->get( 'orderby' ) && $get_order =  $q->get( 'order' ) )
    {
        if( in_array( strtoupper( $get_order ), ['ASC', 'DESC'] ) )
        {
            global $wpdb;
            $orderby = " SUBSTRING_INDEX( {$wpdb->posts}.post_title, ' ', -1 ) " . $get_order;
        }
    }
    return $orderby;
}, PHP_INT_MAX, 2 );


function add_parent_class( $items ) {
    $parents = wp_list_pluck( $items, 'menu_item_parent');
    foreach ( $items as $item )
        in_array( $item->ID, $parents ) && $item->classes[] = 'parent';
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'add_parent_class' );

function add_favicon() {
  	$favicon_url = get_template_directory_uri( ). '/assets/images/favicons/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
  
add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');

