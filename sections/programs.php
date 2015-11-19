<?php 
	global $post;
	$slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_title = get_post( $parent )->post_title;
	$parent_slug = get_post( $parent )->post_name;

	$programs_query = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'child_of' => $parent,
		'post_type' => 'page',
		'post_status' => 'publish'
	); 
	$programs = get_pages($programs_query); 
?>

<section class="programs" id="<?php echo $parent_slug ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h4 class="title orange"><?php echo $parent_title ?></h4>
		<div class="program-links">
			<?php
			foreach( $programs as $program ):
				$full_title = get_field('full_title', $program);
				if ($full_title == ''):
					$full_title = get_post( $program )->post_title;
				endif;
				$slug = $program->post_name;
				echo '<a class="bullet" href="#' . $slug . '">' . $full_title . '</a></br>';
			endforeach;
			?>
		</div>

		<?php
		foreach( $programs as $program ):
			$title = $program->post_title;
			$slug = $program->post_name;
			$description = get_field('description', $program);
			$apply_link = get_field('apply_link', $program);
			$deadline = get_field('deadline', $program);

			echo '<div class="border-top program" id="' . $slug . '">';
			echo '<h3 class="title orange ' . $slug . '">' . $title . '</h3>';
			echo '<div class="description">' . $description . '</div>';
			echo '<a class="bullet apply" href="' . $apply_link . '">Apply</a>';
			echo '<div class="deadline-notes">DEADLINES</br>' . $deadline . '</div>';

			if( have_rows('image_columns', $program) ):
			echo '<div class="columns">';
			    while ( have_rows('image_columns', $program) ) : the_row();
			    	echo '<div class="column">';
			    	echo '<div class="inner">';
				        $col_image = get_sub_field( 'image' )['url'];
				        $col_title = get_sub_field( 'title' );
				        $col_description = get_sub_field( 'description' );

				        echo '<div class="background">';
				        echo '<div class="image" style="background-image:url(' . $col_image . ')"></div>';
				        echo '</div>';
				        echo '<h3 class="title">' . $col_title . '</h3>';
				        echo '<div class="description">' . $col_description . '</div>';

				        if( have_rows('links', $program) ):
				        echo '<div class="links">';
				       	while ( have_rows('links', $program) ) : the_row();
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