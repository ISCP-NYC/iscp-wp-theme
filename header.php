<?php header('Content-Type: text/html; charset=UTF-8');
$page_id = get_the_ID();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php 
	$user = wp_get_current_user();
	$classes = '';
	if( is_home() ):
		$page_slug = 'home';
		$page_id = null;
	elseif( is_search() ):
		$page_slug = 'search';
		$page_id = null;
	elseif( is_404() ):
		$page_slug = 'error';
		$page_id = null;
	else:
		$page_slug = get_post( $post )->post_name;
	endif;
	$post_type = get_post_type();
	switch($page_slug) {
		case 'current-residents':
			$classes = 'current-residents residents';
			break;
		case 'past-residents':
			$classes = 'past-residents residents';
			break;
		case 'greenroom':
			if (is_user_logged_in()):
				$classes = 'greenroom';
			else:
				$classes = 'login';
			endif;
			break;
	}
	switch($post_type) {
		case 'resident':
			if( is_current( $page_id ) ) {
				$classes .= 'current';
			} elseif( is_past( $page_id ) ) {
				$classes .= 'past';
			}
			break;
	}

	$theme = get_template_directory_uri();
	if( is_admin() ):
		$fav_dir = 'favicons/square';
	elseif( is_404() || strpos( $classes , 'past' ) > -1 ):
		$fav_dir = 'favicons/blue';
	else:
		$fav_dir = 'favicons/orange';
	endif;

	$permalink = get_the_permalink($page_id);
	$thumb = get_thumb( $page_id );
	?>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $theme; ?>/assets/images/favicons/orange/apple-touch-icon-180x180.png">
	<link rel="icon" data-update type="image/png" href="<?php echo $theme; ?>/assets/images/<?php echo $fav_dir ?>/favicon-16x16.png" sizes="16x16">
	<link rel="icon" data-update type="image/png" href="<?php echo $theme; ?>/assets/images/<?php echo $fav_dir ?>/favicon-32x32.png" sizes="32x32">
	<link rel="icon" data-update type="image/png" href="<?php echo $theme; ?>/assets/images/<?php echo $fav_dir ?>/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="<?php echo $theme; ?>/assets/images/favicons/orange/android-chrome-192x192.png" sizes="192x192">
	<link rel="manifest" href="<?php echo $theme; ?>/assets/images/favicons/orange/manifest.json">
	<link rel="mask-icon" href="<?php echo $theme; ?>/assets/images/favicons/orange/safari-pinned-tab.svg" color="#ff5000">
	<meta name="msapplication-TileColor" content="#ff5000">
	<meta name="msapplication-TileImage" content="<?php echo $theme; ?>/assets/images/favicons/orange/mstile-144x144.png">
	<meta name="theme-color" content="#ff5000">
	<meta property="description" content="<?php get_meta_description( $page_id ) ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php rtrim( wp_title('',true) ) ?>" />
	<meta property="og:description" content="<?php get_meta_description( $page_id ) ?>" />
	<meta property="og:image" content="<?php echo get_thumb( $page_id, 'thumb' ) ?>" />
	<meta property="og:image:width" content="200" />
	<meta property="og:image:height" content="200" />
	<meta property="fb:app_id" content="1007372126002973" />
	<?php wp_head(); ?>
	<title><?php
		if(is_search()):
			echo 'Search results for ';
			the_search_query();
			echo ' | ';
		elseif($page_slug != 'home'):
			wp_title('',true);
			echo ' | ';
		endif;
		bloginfo('name');
	?></title>
</head>
<body>
	<main data-center-slug="<?php echo $page_slug ?>" data-center-id="<?php echo $page_id ?>">