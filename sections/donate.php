<?php 
global $post;
$id = $post->ID;
$title = $post->post_title;
$slug = $post->post_name;
// $content = $post->post_content;
$content = get_field('description', $id);
$paypal = get_field('paypal', $id);
$footnote = get_field('footnote', $id);
?>

<section  <?php section_attr( $id, $slug, 'support donate' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>
		<div class="module">
			<?php echo $content ?>	
			<a href="<?php echo $paypal ?>" class="paypal" target="_blank">
				<img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!">
			</a>
			<?php echo $footnote ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>