<?php
global $post;
$id = $post->ID;
$title = get_post( $post )->post_title;
$slug = get_post( $post )->post_name;
$faq_id = get_page_by_path('apply/faq')->ID;
$faq_link = get_permalink( $faq_id );
$programs_link = get_permalink( get_page_by_path('residency-programs')->ID );
$residency_programs_query = array(
	'post_type' => 'page',
	'post__not_in' => array( $faq_id ),
	'child_of' => get_page_by_path('apply')->ID,
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'sort_order' => 'desc',
);
$residency_programs = get_pages( $residency_programs_query );
$intro_image = get_field( 'intro_image', $id );
$intro_text = get_field( 'intro_text', $id );
?>
<section <?php section_attr( $id, $slug, 'apply' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="title"><?php echo $title ?></h2>
		<?php if ( $intro_image || $intro_text ):
			echo '<div class="intro module">';
			if ( !empty($intro_image) ):
				echo '<figure class="hero">';
				echo wp_get_attachment_image( $intro_image, 'full' );
				echo '<figcaption>' . wp_get_attachment_caption( $intro_image ) . '</figcaption>';
				echo '</figure>';
			endif;
			if ( !empty($intro_text) ):
				echo $intro_text;
			endif;
			echo '</div>';
		endif; ?>

		<?php foreach( $residency_programs as $program ):
			setup_postdata($program);
			$title = get_post( $program )->post_title;
			$slug = get_post( $program )->post_name;
			$id = get_post( $program )->ID;
			if($id!=$faq_id):
		?>
		<div class="program module" id="<?php echo $slug ?>">
			<h3 class="title"><?php echo $title ?></h3>
			<div class="info">
				<?php 
					$intro = get_field( 'intro', $id ); 
					$linkrows = get_field( 'links', $id );
				?>
				<div class="intro">
					<?php echo $intro ?>
				</div>
			</div>
			<?php if( !empty($linkrows) ): ?>
			<div class="links">
				<?php 
					foreach( $linkrows as $row ): 
					$link = $row['page_link'];
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
				?>
					<a class="bullet small" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php if( $slug == 'current-open-calls'): ?>
			<div class="block deadline">
				<div class="horizontal-align">
				<?php 
					if( have_rows( 'applications', $id ) ):
			    		while ( have_rows( 'applications' ) ) : the_row();
							$app_title = get_sub_field( 'title', $id );
							$app_deadline = get_sub_field( 'deadline', $id );
							$app_deadline_dt = new DateTime( $app_deadline );
							$app_deadline_format = $app_deadline_dt->format('F d, Y');
							$app_brief = get_sub_field( 'brief', $id );
							$app_link = get_sub_field( 'link', $id );
							if( $app_deadline > $today ):
								if( $app_link ):
									echo '<a href="' . $app_link . '">';
								endif;
								echo '<div>' . $app_title . '</div>';
								echo '<div>Deadline: ' . $app_deadline_format . '</div>';
								if( $app_link ):
									echo '</a>';
								endif;
							endif;
						endwhile;
					else:
						echo 'No applications available';
					endif;
				?>
				</div>
			</div>
			<?php endif; ?>
		</div>


		<?php wp_reset_postdata(); ?>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>