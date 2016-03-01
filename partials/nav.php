<?php $menu_items = wp_get_nav_menu_items( 'blueprint' ); ?> 
<header class="main nav-hover">
	<nav>
		<?php 
		$menu_items = add_parent_class( $menu_items );
		foreach ( (array) $menu_items as $key => $menu_item ):
			if ( $menu_item->menu_item_parent == 0 ):
				$item_title = $menu_item->title;
				$item_id = $menu_item->ID;
				$item_url = $menu_item->url;
				$item_slug = basename($item_url);
				$classes_array = $menu_item->classes;
				array_push($classes_array, $item_slug);
				$classes = implode(' ', $classes_array);
			   	echo '<div class="cell' . $classes . '">';
				echo '<div class="inner">';
				echo '<div class="overlay">';
				if( $item_title === 'Past Events' ):
					echo '<a href="' . $item_url . '#past">';
				else:
					echo '<a href="' . $item_url . '">';
				endif;
			    if( $item_slug == 'greenroom' ):
			    	echo '<h1 class="link">';
			    	echo '<div class="iconWrap">';
			    	echo '<div class="swap">';
					echo '<div class="icon default"></div>';
					echo '<div class="icon hover"></div>';
					echo '</div>';
					echo $item_title;
			    	echo '</h1>';
			    	echo '</div>';
			    else:
			    	echo '<h1 class="link">';
			    	echo $item_title;
			    	echo '</h1>';
			    endif;
			    echo '</a>';
			    echo '</div>';
			    echo '<div class="sub-menu">';
			    foreach ( (array) $menu_items as $key => $child_menu_item ) {
			    	$child_title = $child_menu_item->title;
			    	$child_url = $child_menu_item->url;
			    	$parent_id = $child_menu_item->menu_item_parent;
			    	if ($parent_id == $item_id ) : 
			    		echo '<a href="' . $child_url . '" class="child-item bullet">';
			    		echo $child_title;
			    		echo '</a>';
			    	endif;
			    }

			   	echo '</div></div></div>';
			endif;
		endforeach; ?>
		<div class="cell search">
			<div class="inner">
				<form role="search" method="get" class="searchform" class="searchform" autocomplete="off" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="placeholder"><span>Search</span></div>
					<input type="text" name="s" class="s nav-search"/>
				</form>
				<div class="counter"></div>
			</div>
    	</div>
	</nav>
</header>
<a href="<?php echo site_url() ?>" class="mobile logo swap">
	<div class="icon default"></div>
	<div class="icon hover"></div>
</a>
<div class="nav-toggle nav-hover swap">
	<div class="icon default"></div>
	<div class="icon hover"></div>
</div>