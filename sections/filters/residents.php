<?php 
!isset( $slug ) && $name = $query_vars['pagename'];
$short_slug = str_replace('-residents', '', $slug);
$page_url = $page_url = $query_vars['url'];
$page_query = array(
	'post_type' => 'resident',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'meta_query' => array( $page_query )
);
// $country_param = $query_vars['from'];
// $year_param = $query_vars['date'];
// $program_param = $query_vars['program'];
// $type_param = $query_vars['type'];
include( locate_template( 'sections/params/residents.php' ) );
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
		$country_count = get_resident_count( 'country', $country_id, $query );
		$classes = $country_slug;
		if( $country_count == 0 ):
			$classes .= ' hide';
		endif;
		if( $country_param == $country_slug ):
			$classes .= ' selected';
			$remove = true;
		else:
			$remove = false;
		endif;
		$filter_url = query_url( 'from', $country_slug, $page_url, $short_slug, $remove );
		echo '<div class="option ' . $classes . '">';
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
	if( $year_param ):
		
	endif;
	foreach( $years as $year ): 
		$year_count = get_resident_count( 'year', $year, $query );
		$classes = $year;
		if( $year_count == 0 ):
			$classes .= ' hide';
		endif;
		if( $year_param == $year ):
			$classes .= ' selected';
			$remove = true;
		else:
			$remove = false;
		endif;
		$filter_url = query_url( 'date', $year, $page_url, $short_slug, $remove );
		echo '<div class="option ' . $classes . '">';
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
		if( $program_param ):
			
		endif;
		foreach( $residency_programs as $program ): 
			$program_count = get_resident_count( 'program', $program, $query );
			$program_title = get_program_title( $program );
			$classes = $program;
			if( $program_count == 0 ):
				$classes .= ' hide';
			endif;
			if( $program_param == $program ):
				$classes .= ' selected';
				$remove = true;
			else:
				$remove = false;
			endif;
			$filter_url = query_url( 'program', $program, $page_url, $short_slug, $remove );
			echo '<div class="option ' . $classes . '">';
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
		endforeach;
		?>
	</div>
</div>

<div class="filter-list type <?php echo $slug ?>" data-filter="type">
	<div class="options">
		<?php
		$resident_types = array(
			'artist',
			'curator'
		);
		if( $type_param ):
			
		endif;
		foreach( $resident_types as $type ): 
			$type_count = get_resident_count( 'type', $type, $query );
			$type_title = ucwords( $type );
			$classes = $type;
			if( $type_count == 0 ):
				$classes .= ' hide';
			endif;
			if( $type_param == $type ):
				$classes .= ' selected';
				$remove = true;
			else:
				$remove = false;
			endif;
			$filter_url = query_url( 'type', $type, $page_url, $short_slug, $remove );
			echo '<div class="option ' . $classes . '">';
			echo '<a href="' . $filter_url . '" data-value="' . $type . '">';
			echo $type_title;
			echo ' (<span class="count">';
			echo $type_count;
			echo '</span>)';
			echo '<div class="swap">';
			echo '<div class="icon default"></div>';
			echo '<div class="icon hover"></div>';
			echo '</div>';
			echo '</a>';
			echo '</div>';
		endforeach;
		?>
	</div>
</div>