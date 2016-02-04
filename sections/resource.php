<?php 
	global $current_user;
    get_currentuserinfo();
    $name = $current_user->user_firstname . ' ' . $current_user->user_lastname;

    global $post;
	$id = get_the_ID();
	$slug = get_post( $post )->post_name;
	$title = get_the_title();
	$event_classes = 'resident-resources';
?>

<section <?php section_attr( $id, $slug, $event_classes ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="user-info">
			<div>Hello, <?php echo $name ?></div>
			<a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
		</div>
		<?php
		if( have_rows( 'item' ) ):
			while ( have_rows('item') ) : the_row();
				$title = get_sub_field( 'title' );
				$text = get_sub_field( 'text' );
				echo '<div class="module item">';
				echo '<h3 class="title">' . $title . '</h3>';
				echo $text;
				echo '</div>';
			endwhile;
		endif;
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>