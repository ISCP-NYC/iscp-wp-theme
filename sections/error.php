<?php
$event_classes = 'error';
?>
<section <?php section_attr( 'error', 'error', 'error', 'Error' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>

	<div class="content">
		<h2 class="title">
			Sorry, this page can't be found
		</h2>	
	</div>

	<?php get_template_part('partials/footer') ?>
</section>
