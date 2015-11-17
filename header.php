<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<?php 

	$classes;

	$page_slug = get_post( $post )->post_name;
	$page_id = get_the_ID();
	$post_type = get_post_type();

	switch($page_slug) {
		case 'current_residents':
			$classes = 'current_residents residents';
			break;
		case 'alumni':
			$classes = 'alumni residents';
			break;
	}
	
	switch($post_type) {
		case 'resident':
			if( is_alumni( $page_id ) ) {
				$classes .= ' alumni';
			} else {
				$classes .= ' current';
			}
			break;
	}


?>
<body <?php body_class($classes); ?>>
	<main>