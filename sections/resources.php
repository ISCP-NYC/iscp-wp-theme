<?php 
	global $post;
	$id = get_the_ID();
	$slug = get_post( $post )->post_name;
	$title = get_the_title();

	global $current_user;
    get_currentuserinfo();
    $name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
?>

<section <?php section_attr( $id, $slug, null ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="user-info">
			<a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
		</div>
		<h1 class="hello">
			Hello, <?php echo $name ?>
		</h1>
		<div class="dashboard module">
			<div class="half left">
				<?php 
				$to_do_page_id = get_page_by_path( $slug . '/to-do' )->ID;
				$post = get_post( $to_do_page_id, OBJECT );
				setup_postdata( $post );
				get_template_part( 'sections/loops/resources' );
				wp_reset_postdata();

				$staff_messages_page_id = get_page_by_path( $slug . '/staff-messages' )->ID;
				$post = get_post( $staff_messages_page_id, OBJECT );
				setup_postdata( $post );
				get_template_part( 'sections/loops/resources' );
				wp_reset_postdata();
				?>
			</div>
			<div class="half right">
				<?php 
				$at_iscp_page_id = get_page_by_path( $slug . '/at-iscp' )->ID;
				$post = get_post( $at_iscp_page_id, OBJECT );
				setup_postdata( $post );
				get_template_part( 'sections/loops/resources' );
				wp_reset_postdata();

				$in_nyc_page_id = get_page_by_path( $slug . '/in-nyc' )->ID;
				$post = get_post( $in_nyc_page_id, OBJECT );
				setup_postdata( $post );
				get_template_part( 'sections/loops/resources' );
				wp_reset_postdata();
				?>
			</div>
		</div>
		<div class="welcome module">
			<h3 class="title">Welcome!</h3>
			<div class="text">
				<?php $welcome = get_field( 'welcome' ); ?>
				<?php echo $welcome; ?>
			</div>
		</div>
		<div class="contact module">
			<h3 class="title">Hours and Contact</h3>
			<div class="half left">
				<?php $hours = get_field( 'hours' ); ?>
				<?php echo $hours; ?>
			</div>
			<div class="half right">
				<?php $contact = get_field( 'contact' ); ?>
				<?php echo $contact; ?>
			</div>
		</div>
		<div class="staff module">
			<h3 class="title">Staff</h3>
			<div class="members">
			<?php
			while ( have_rows('staff') ) : the_row();
				$name = get_sub_field( 'name' );
				$role = get_sub_field( 'role' );
				$email = get_sub_field( 'email' );
				$description = get_sub_field( 'description' );
				$thumbnail = get_sub_field( 'image' )['sizes']['thumb'];
				if( !$thumbnail ) {
					$thumbnail = get_template_directory_uri() . '/assets/images/placeholder.svg';
				}

				echo '<div class="staff-member">';
				echo '<div class="inner">';
				echo '<h3 class="name">' . $name . '</h3>';
				echo '<div class="image">';
				echo '<img src="' . $thumbnail . '"/>';
				echo '</div>';
				echo '<div class="info">';
				echo '<div class="role">';	
				echo $role;
				echo '</div>';
				echo '<a class="email" href="mailto:' . $email . '">';	
				echo $email;
				echo '</a>';
				echo '<div class="description">';	
				echo $description;
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			endwhile;
			?>
			</div>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>