<?php 
!isset( $slug ) && $name = $query_vars['pagename'];
$short_slug = str_replace('-residents', '', $slug);
$page_url = $page_url = $query_vars['url'];
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
		$country_count = get_resident_count( 'country', $country_id, $count_query );
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
		echo '<span class="value">' . $country_title . '</span>';
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
<?php 
if( $short_slug == 'past' || $page_type == 'sponsor' ): ?>
<div class="filter-list date <?php echo $slug ?>" data-filter="date">
	<div class="options">
	<?php
	$start_date = 1994;
	$end_date = date( "Y" );
	$years = array_reverse( range( $start_date, $end_date ) );
	foreach( $years as $year ): 
		$year_count = get_resident_count( 'year', $year, $count_query );
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
		echo '<span class="value">' . $year . '</span>';
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
		foreach( $residency_programs as $program ): 
			$program_count = get_resident_count( 'program', $program, $count_query );
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
			echo '<span class="value">' . $program_title . '</span>';
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
		foreach( $resident_types as $type ): 
			$type_count = get_resident_count( 'type', $type, $count_query );
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
			echo '<span class="value">' . $type_title . '</span>';
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