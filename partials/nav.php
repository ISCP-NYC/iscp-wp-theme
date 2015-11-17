<?php $menu_items = wp_get_nav_menu_items( 'blueprint' ); ?> 
<header class="main">
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
			    			<a class="overlay" href="' . $url . '">
			    				<h1>' . $title . '</h1>
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
			    			<div class="child-item bullet ' . $child_slug . '">
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
				<a class="overlay" href="#">
					<h1>Search</h1>
				</a>
			</div>
    	</div>
	</nav>
</header>
<div class="nav-toggle"></div>