<?php include( locate_template( 'sections/params/journals.php' ) ); ?>
<section <?php section_attr( $id, $slug, 'journal' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php the_title() ?></h2>
		<div class="top">
			<div class="filter">
				<?php
				if( $tag_param ):
					$tag_name = get_term_by('slug', $tag_param, 'post_tag')->name;
					echo '<div class="bar">';
				else:
					echo '<div class="bar hide">';
				endif;
					echo '<span>Tagged: </span>';
					echo '<span class="select tag link">';
						echo '<span>"' . $tag_name . '"</span>';
						echo '<div class="swap">';
							echo '<div class="icon default"></div>';
							echo '<div class="icon hover"></div>';
						echo '</div>';
					echo '</span>';
				echo '</div>';
				?>
			</div>
		</div>
		<div class="journal filter-this masonry grid items" data-delay="<?php echo $delay ?>">
			<div class="sizer"></div>
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>