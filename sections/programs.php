<?php 
global $post;
$id = $post->ID;
$slug = $post->post_name;
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
	'sort_order' => 'asc',
	'sort_column' => 'post_title',
	'post_type' => 'page',
	'child_of' => $programs_id,
	'post_status' => 'publish'
); 
$programs = get_pages( $programs_query ); 
?>

<section <?php section_attr( $id, $slug, 'programs' ); ?>>
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h3 class="title head"><?php echo $parent_title ?></h3>
		<div class="program-links">
			<?php
			foreach( $programs as $program ):
				$full_title = get_field( 'full_title', $program );
				if ( $full_title == '' ):
					$full_title = get_post( $program )->post_title;
				endif;
				$slug = $program->post_name;
				echo '<a class="bullet" href="#' . $slug . '">' . $full_title . '</a></br>';
			endforeach;
			?>
		</div>

		<?php
			if( have_rows( 'images', $programs_id ) ):
			echo '<div class="images">';
			while ( have_rows( 'images', $programs_id ) ) : the_row();
				$image = get_sub_field( 'image' )['sizes']['thumb'];
				$caption = get_sub_field( 'caption' );
				echo '<div class="image">';
				echo '<div class="inner">';
		        echo '<img src="' . $image . '"/>';
		        echo '<div class="caption">' . $caption  . '</div>';
		        echo '</div>';
		        echo '</div>';
			endwhile;
			echo '</div>';
			endif;
		?>

		<?php
		foreach( $programs as $program ):
			$title = $program->post_title;
			$slug = $program->post_name;
			$description = get_field( 'description', $program );
			$apply_link = get_field( 'apply_link', $program );
			$deadline = get_field( 'deadline', $program );

			echo '<div class="border-top program" id="' . $slug . '">';
			echo '<h3 class="title orange ' . $slug . '">' . $title . '</h3>';
			echo '<div class="description">' . $description . '</div>';
			echo '<a class="bullet apply" href="' . $apply_link . '">Apply</a>';
			echo '<div class="deadline-notes">DEADLINES</br>' . $deadline . '</div>';

			if( have_rows( 'columns', $program ) ):
			echo '<div class="columns">';
			    while ( have_rows( 'columns', $program ) ) : the_row();
			    	echo '<div class="column">';
			    	echo '<div class="inner">';
				        $col_image = get_sub_field( 'image' )['sizes']['thumb'];
				        $col_title = get_sub_field( 'title' );
				        $col_description = get_sub_field( 'description' );

				        echo '<div class="image">';
				        echo '<img src="' . $col_image . '"/>';
				        echo '</div>';
				        echo '<h3 class="title">' . $col_title . '</h3>';
				        echo '<div class="description">' . $col_description . '</div>';

				        if( have_rows( 'links', $program ) ):
				        echo '<div class="links">';
				       	while ( have_rows( 'links', $program ) ) : the_row();
				       		$title = get_sub_field( 'title' );
				       		$link = get_sub_field( 'link' );
				     		echo '<a href="' . $link . '">Past ' . $title . '</a>';
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