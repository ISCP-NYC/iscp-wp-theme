<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<title><?php if(!is_home()){wp_title('',true); echo ' | ';} bloginfo('name') ?></title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script type="text/javascript">
	    (function() {
	        var path = '//easy.myfonts.net/v2/js?sid=4892(font-family=Life+Regular)&sid=4894(font-family=Life+Italic)&sid=5004(font-family=Life+Rounded)&key=xrgToixtG4',
	            protocol = ('https:' == document.location.protocol ? 'https:' : 'http:'),
	            trial = document.createElement('script');
	        trial.type = 'text/javascript';
	        trial.async = true;
	        trial.src = protocol + path;
	        var head = document.getElementsByTagName("head")[0];
	        head.appendChild(trial);
	    })();
	</script>
	<?php $theme = get_template_directory_uri(); ?>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $theme; ?>/assets/images/favicons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $theme; ?>/assets/images/favicons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $theme; ?>/assets/images/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo $theme; ?>/assets/images/favicons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $theme; ?>/assets/images/favicons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo $theme; ?>/assets/images/favicons/manifest.json">
	<meta name="msapplication-TileColor" content="#ff5000">
	<meta name="msapplication-TileImage" content="<?php echo $theme; ?>/assets/images/favicons/ms-icon-144x144.png">
	<meta name="theme-color" content="#ff5000">
	<?php wp_head(); ?>
</head>

<?php 

	$user = wp_get_current_user();

	$classes;

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
		$page_id = get_the_ID();
	endif;
	$post_type = get_post_type();
	switch($page_slug) {
		case 'current-residents':
			$classes = 'current-residents residents';
			break;
		case 'past-residents':
			$classes = 'past-residents residents';
			break;
		case 'resident-resources':
			if (is_user_logged_in()):
				$classes = 'resident-resources';
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


?>
<body <?php body_class(); ?>>
	<main data-center-slug="<?php echo $page_slug ?>" data-center-id="<?php echo $page_id ?>">