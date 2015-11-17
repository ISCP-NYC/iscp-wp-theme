<?php
	global $post;
	$slug = get_post( $post )->post_name;
?>

<aside id="<?php echo $slug ?>" class="left main">
	<a href="<?php echo site_url() ?>" class="logo"></a>
</aside>

<aside id="<?php echo $slug ?>" class="right main">
	<a href="<?php echo site_url() ?>" class="logo"></a>
</aside>