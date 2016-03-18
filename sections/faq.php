<?php
global $post;
$id = $post->ID;
$slug = $post->post_name;
$title = $post->post_title;
?>
<section <?php section_attr( $id, $slug, 'sponsors' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head">
			Frequently Asked Questions
		</h2>
		<div class="sponsors shelves filter-this list items <?php echo $slug ?>">
			<?php
			if( get_field( 'faqs', $id ) ):
				while( has_sub_field( 'faqs', $id ) ):
					$question = get_sub_field( 'question', $id );
					$answer = get_sub_field( 'answer', $id );
					echo '<div class="question module">';
					echo '<h4 class="title">';
					echo $question;
					echo '</h4>';
					echo '<div class="answer">';
					echo $answer;
					echo '</div>';
					echo '</div>';
				endwhile;
			endif;
			?>
		</div>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>