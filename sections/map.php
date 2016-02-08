<?php
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$paged = 1;
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
endif;
?>
<section <?php section_attr( $id, $slug, 'earth' ); ?> data-page="<?php echo $paged ?>">
	<a href="<?php echo site_url() ?>" class="logo swap">
		<div class="icon default"></div>
		<div class="icon hover"></div>
	</a>
	<div id="mapWrap"></div>
	<div class="residents">
		<div class="head"></div>
		<div class="content">
			<div class="titles">
				<div class="title name">Resident</div>
				<div class="title years">Year(s)</div>
				<div class="title sponsors">Sponsor(s)</div>
			</div>
			
			<div class="shelves list">
				

			</div>
		</div>
		<div class="close swap">
			<div class="icon default"></div>
			<div class="icon hover"></div>
		</div>
	</div>
</section>