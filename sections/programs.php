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
		<h2 class="head"><?php echo $title ?></h2>
		<?php
			if( have_rows( 'images', $programs_id ) ):
			echo '<div class="images module">';
			while ( have_rows( 'images', $programs_id ) ) : the_row();
				$image = get_sub_field( 'image' )['sizes']['thumb'];
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
			$title = $program->post_title;
			$slug = $program->post_name;
			$description = get_field( 'description', $program );
			// $apply_link = get_field( 'apply_link', $program );
			// $deadline = get_field( 'deadline', $program );

			echo '<div class="module program" id="' . $slug . '">';
			echo '<h3 class="title orange ' . $slug . '">' . $title . '</h3>';
			echo '<div class="description">' . $description . '</div>';
			// echo '<a class="bullet apply" href="' . $apply_link . '">Apply</a>';
			// echo '<div class="deadline-notes">Deadlines</br>' . $deadline . '</div>';
			// echo '<h3 class="title orange">Deadlines</h3>';
			if( have_rows( 'columns', $program ) ):
			echo '<div class="columns">';
			    while ( have_rows( 'columns', $program ) ) : the_row();
			    	echo '<div class="column">';
			    	echo '<div class="inner">';
				        $col_image = get_sub_field( 'image' )['sizes']['thumb'];
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
				       		$title = get_sub_field( 'title' );
				       		$link = get_sub_field( 'link' );
				     		echo '<a href="' . $link . '">' . $title . '</a>';
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