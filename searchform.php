<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<?php
		// $search_value = get_search_query();
		// if(!$search_value):
			$search_value = 'Search';
		// endif;
		?>
		<input type="text" value="<?php echo $search_value ?>" name="s" id="s" />
		<!-- <input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" /> -->
	</div>
</form>