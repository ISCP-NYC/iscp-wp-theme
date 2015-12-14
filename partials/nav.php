<?php $menu_items = wp_get_nav_menu_items( 'blueprint' ); ?> 
<header class="main">
	<nav>
		<?php 
		$menu_items = add_parent_class( $menu_items );
		foreach ( (array) $menu_items as $key => $menu_item ) {
			if ( $menu_item->menu_item_parent == 0 ) :
				$title = $menu_item->title;
				$id = $menu_item->ID;
				$url = $menu_item->url;
				$slug = basename($url);
				$classes_array = $menu_item->classes;
				array_push($classes_array, $slug);
				$classes = implode(' ', $classes_array);
			   	echo '<div class="cell ' . $classes . ' ">';
				echo '<div class="inner">';
				echo '<a class="overlay" href="' . $url . '">';
			    echo '<h1>' . $title . '</h1>';
			    echo '</a>';
			    echo '<div class="sub-menu">';

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
			    		echo $child_item_html;
			    	endif;
			    }

			   	echo '</div></div></div>';
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