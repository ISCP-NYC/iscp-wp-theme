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
			</br>
			<form id="donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="blank"><input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" type="image" /> <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" border="0" />
			</br>
			<?php echo $footnote ?>
		</div>
	</div>
	<?php get_template_part('partials/footer') ?>
</section>