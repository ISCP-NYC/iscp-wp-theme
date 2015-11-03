<?php
	global $post;
	$slug = get_post( $post )->post_name;
?>

<aside id="<?php echo $slug ?>" class="left main">
	<div class="logo"></div>
</aside>

<aside id="<?php echo $slug ?>" class="right main">
	<div class="logo"></div>
</aside>