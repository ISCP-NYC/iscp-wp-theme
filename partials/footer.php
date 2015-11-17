<?php 
	$menu_items = wp_get_nav_menu_items( 'footer' );
	$about = get_page_by_path( 'about' );
	$about_id = $about->ID;
	$address = get_field( 'address', $about_id );
?> 
<footer>
	<aside class="left footer">
		<a href="<?php echo site_url() ?>" class="logo"></a>
	</aside>
	<div class="content">
		<h3 class="title black">
			International Studio &amp; Curatorial Program
		</h3>
		<h3 class="title white address">
			<?php echo $address ?>
		</h3>
		<nav>
			<?php foreach ( (array) $menu_items as $key => $menu_item ) {
				if ( $menu_item->menu_item_parent == 0 ) :
					$title = $menu_item->title;
					$id = $menu_item->ID;
					$url = $menu_item->url;
					$slug = basename($url);
				    $menu_item_html = '
				    	<div class="cell ' . $slug . '">
				    		<div class="inner">
				    			<a href="' . $url . '">
				    				<h3 class="title">' . $title . '</h3>
				    			</a>
				    			<div class="sub-menu">';

				    $menu_item_html .= $sub_menu_html;
				    foreach ( (array) $menu_items as $key => $child_menu_item ) {
				    	$child_title = $child_menu_item->title;
				    	$child_url = $child_menu_item->url;
				    	$child_slug = str_replace('#','-',basename($child_url));
				    	$parent_id = $child_menu_item->menu_item_parent;
				    	if ($parent_id == $id ) : 
				    		$child_item_html = '
				    			<div class="child-item ' . $child_slug . '">
				    				<a href="' . $child_url . '">' .
				    					$child_title .
				    				'</a>
				    			</div>';
				    		$menu_item_html .= $child_item_html;
				    	endif;
				    }

				   	$menu_item_html .= '</div></div></div>';
				   	echo $menu_item_html;
				endif;
			} ?>

			<div class="cell search">
	    		<div class="inner">
	    			<a href="">
	    				<h3 class="title">Search</h3>
	    			</a>
	    			<div class="sub-menu">
	    				<input type="text"/>
	    			</div>
	    		</div>
	    	</div>

	    	<?php
				$twitter_handle = str_replace( '@', '', get_field( 'twitter', $about_id ) );
				$twitter_url = 'http://twitter.com/' . $twitter_handle;
				$facebook_url = get_field( 'facebook', $about_id );
				$instagram_handle = str_replace('@', '', get_field( 'instagram', $about_id ) );
				$instagram_url = 'http://instagram.com/' . $instagram_handle;
				$newsletter_url = get_field( 'newsletter', $about_id );
			?>

	    	<div class="cell connect">
	    		<div class="inner">
	    			<a href="">
	    				<h3 class="title">Connect</h3>
	    			</a>
	    			<div class="sub-menu">
	    				<div class="child-item twitter">
	    					<a href="<?php echo $twitter_url ?>">Twitter</a>
	    				</div>
	    				<div class="child-item facebook">
	    					<a href="<?php echo $facebook_url ?>">Facebook</a>
	    				</div>
	    				<div class="child-item instagram">
	    					<a href="<?php echo $instagram_url ?>">Instagram</a>
	    				</div>
	    			</div>
	    		</div>
	    	</div>

	    	<div class="cell newsletter">
	    		<div class="inner">
	    			<a href="<?php echo $newsletter_url ?>">
	    				<h3 class="title">Newsletter</h3>
	    			</a>
	    			<div class="sub-menu">
	    				<input type="text" placeholder="Subscribe">
	    			</div>
	    		</div>
	    	</div>
    	</nav>
	</div>
	<aside class="right footer">
		<a href="<?php echo site_url() ?>" class="logo"></a>
	</aside>
</footer>