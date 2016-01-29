<?php include( locate_template( 'sections/params/journals.php' ) ); ?>
<section <?php section_attr( $id, $slug, 'journal' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php the_title() ?></h2>
		<div class="top">
			<div class="filter">
				<div class="bar">
					<?php
					if( $tag_param ):
						$tag_name = get_term_by('slug', $tag_param, 'post_tag')->name;
						echo '<div class="select tag">';
						echo '<span>Tagged: "' . $tag_name . '"</span>';
						echo '<a href="' . site_url( '/journal' ) . '" class="swap">';
						echo '<div class="icon default"></div>';
						echo '<div class="icon hover"></div>';
						echo '</a>';
						echo '</div>';
					endif;
					?>
				</div>
			</div>
		</div>
		<div class="journal filter-this grid items">
			<?php include( locate_template( 'sections/loops/journals.php' ) ); ?>	
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>