<?php
	global $post;
	$slug = get_post( $post )->post_name;
?>

<aside id="<?php echo $slug ?>" class="left main">
	<a href="<?php echo site_url() ?>" class="logo swap">
		<div class="icon default"></div>
		<div class="icon hover"></div>
	</a>
	<a class="move left">
		<div class="label vertical-align"></div>
	</a>
	<a class="move right">
		<div class="label vertical-align"></div>
	</a>
</aside>
<aside id="<?php echo $slug ?>" class="right main">
	<a href="<?php echo site_url() ?>" class="logo swap">
		<div class="icon default"></div>
		<div class="icon hover"></div>
	</a>
	<a class="move left">
		<div class="label vertical-align"></div>
	</a>
	<a class="move right">
		<div class="label vertical-align"></div>
	</a>
</aside>