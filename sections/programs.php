<?php 
global $post;
$id = $post->ID;
$slug = $post->post_name;
$title = $post->post_title;
$parent = $post->post_parent;
if( $parent != 0 ) {
	$parent_title = get_post( $parent )->post_title;
	$parent_slug = get_post( $parent )->post_name;	
	$parent_id = get_post( $parent )->ID;
	$programs_id = $parent_id;
} else {
	$programs_id = $post->ID;
}
$programs_query = array(
	'post_type' => 'page',
	'orderby' => 'menu_order',
	'order' => 'DESC',
	'child_of' => $programs_id,
	'post_status' => 'publish'
);
$programs = array_reverse( get_pages( $programs_query ) );  
?>
<section <?php section_attr( $id, $slug, 'programs' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h1 class="head"><?php echo $title ?></h1>
		<?php
			if( have_rows( 'images', $programs_id ) ):
			echo '<div class="images module">';
			while ( have_rows( 'images', $programs_id ) ) : the_row();
				$image = get_sub_field( 'image' ) ? get_sub_field( 'image' )['sizes']['thumb'] : null;
				$caption = get_sub_field( 'caption' );
				if( $image ):
					echo '<div class="image">';
					echo '<div class="inner">';
							echo '<img src="' . $image . '"/>';
							echo '<div class="caption">' . $caption  . '</div>';
							echo '</div>';
							echo '</div>';
					endif;
			endwhile;
			echo '</div>';
			endif;
		?>

		<?php
		foreach( $programs as $program ):
			$program_title = $program->post_title;
			$program_slug = $program->post_name;
			$program_description = get_field( 'description', $program );
			echo '<div class="module program" id="' . $program_slug . '">';
			if( $slug == 'public_programs' ):
				echo '<h3 class="title ' . $program_slug . '">' . $program_title . '</h3>';
			endif;
			echo '<div class="description">' . $program_description . '</div>';
			if( have_rows( 'image_columns', $program ) ):
			echo '<div class="grid masonry items">';
				echo '<div class="sizer"></div>';
					while ( have_rows( 'image_columns', $program ) ) : the_row();
						echo '<div class="item">';
						echo '<div class="inner">';
								$col_image = get_sub_field( 'image' ) ? get_sub_field( 'image' )['sizes']['thumb'] : null;
								$col_title = get_sub_field( 'title' );
								$col_description = get_sub_field( 'description' );
								if( $col_image ):
									echo '<div class="image">';
									echo '<img src="' . $col_image . '"/>';
									echo '</div>';
							endif;
								echo '<h3 class="title">' . $col_title . '</h3>';
								echo '<div class="description">' . $col_description . '</div>';

								if( have_rows( 'links', $program ) ):
								echo '<div class="links">';
								while ( have_rows( 'links', $program ) ) : the_row();
									$link_title = get_sub_field( 'title' );
									$link = get_sub_field( 'link' );
								echo '<a href="' . $link . '">' . $link_title . '</a>';
							endwhile;
							echo '</div>';
							endif;

							echo '</div>';
							echo '</div>';
					endwhile;
				echo '</div>';
				endif;
				echo '</div>';

		endforeach;
		?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>