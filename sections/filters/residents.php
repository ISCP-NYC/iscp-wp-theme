<?php 
!isset( $slug ) && $name = $query_vars['pagename'];
$short_slug = str_replace('-residents', '', $slug);
$page_url = get_the_permalink() . '?filter=' . $short_slug;
$country_param = $query_vars['from'];
$year_param = $query_vars['date'];
$program_param = $query_vars['program'];
?>
<div class="filter-list country <?php echo $slug ?>" data-filter="country">
	<div class="options">
	<?php
	$countries = get_posts( array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'country',
		'orderby' 			=> 'title',
		'order' 			=> 'ASC',
		'post_status' 		=> 'publish'
	) );
	foreach( $countries as $country ): 
		$country_id = $country->ID;
		$country_slug = $country->post_name;
		$country_title = $country->post_title;
		$country_count = get_resident_count( 'country', $country_id, $page_query );
		$filter_url = query_url( 'from', $country_slug, $page_url, $short_slug );
		if( $country_count != 0 ):
			if( $country_param == $country_slug ):
				$selected = ' selected';
			else:
				$selected = null;
			endif;
			echo '<div class="option' . $selected . '">';
			echo '<a href="' . $filter_url . '" data-value="' . $country_slug . '">';
			echo $country_title;
			echo ' (<span class="count">';
			echo $country_count;
			echo '</span>)';
			echo '<div class="swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '</a>';
			echo '</div>';
		endif;
	endforeach;
	?>
	</div>
</div>
<?php if($slug == 'past-residents'): ?>
<div class="filter-list date <?php echo $slug ?>" data-filter="date">
	<div class="options">
	<?php
	$start_date = 1994;
	$end_date = date( "Y" );
	$years = array_reverse( range( $start_date, $end_date ) );
	foreach( $years as $year ): 
		$year_count = get_resident_count( 'year', $year, $page_query );
		$filter_url = query_url( 'date', $year, $page_url, $short_slug );
		if( $year_count  != 0 ):
			if( $year_param == $year ):
				$selected = ' selected';
			else:
				$selected = null;
			endif;
			echo '<div class="option' . $selected . '">';
			echo '<a href="' . $filter_url . '" data-value="' . $year . '">';
			echo $year;
			echo ' (<span class="count">';
			echo $year_count;
			echo '</span>)';
			echo '<div class="swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '</a>';
			echo '</div>';
		endif;
	endforeach;
	?>
	</div>
</div>
<?php endif; ?>

<div class="filter-list program <?php echo $slug ?>" data-filter="program">
	<div class="options">
		<?php
		$residency_programs = array(
			'international',
			'ground_floor'
		);
		foreach( $residency_programs as $program ): 
			$program_count = get_resident_count( 'program', $program, $page_query );
			$filter_url = query_url( 'program', $program, $page_url, $short_slug );
			$program_title = get_program_title( $program );
			if( $program_count != 0 ):
				if( $program_param == $program ):
					$selected = ' selected';
				else: 
					$selected = null;
				endif;
				echo '<div class="option' . $selected . '">';
				echo '<a href="' . $filter_url . '" data-value="' . $program . '">';
				echo $program_title;
				echo ' (<span class="count">';
				echo $program_count;
				echo '</span>)';
				echo '<div class="swap">';
				echo '<div class="icon default"></div>';
				echo '<div class="icon hover"></div>';
				echo '</div>';
				echo '</a>';
				echo '</div>';
			endif;
		endforeach;
		?>
	</div>
</div>