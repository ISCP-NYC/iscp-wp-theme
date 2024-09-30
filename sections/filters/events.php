<?php
$page_url = $query_vars['url'];
$type_param = array_key_exists('type', $query_vars) ? $query_vars['type'] : null;
$year_param = array_key_exists('date', $query_vars) ? $query_vars['date'] : null;
?>
<div class="filter-list type <?php echo $slug ?>" data-filter="type">
	<div class="options">
		<?php
		$event_types = array( 'exhibition', 'offsite-project', 'iscp-talk', 'open-studios', 'events' );
		foreach( $event_types as $event_type ):
			$event_type_count = get_event_count( 'type', $event_type );
			if( $event_type_count ):
				$filter_url = query_url( 'type', $event_type, $page_url );
				if( $event_type == $type_param ):
					$classes = 'selected ';
				else:
					$classes = null;
				endif;
				$classes .= $event_type;
				echo '<div class="option ' . $classes . '">';
				echo '<a href="' . $filter_url . '" data-value="' . $event_type . '">';
				$event_type = pretty( $event_type );
				echo $event_type;
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

<div class="filter-list sub year <?php echo $slug ?>" data-filter="year">
	<div class="options">
		<?php
		$start_date = 2010;
		$end_date = date( 'Y' );
		$years = array_reverse( range( $start_date, $end_date ) );
		foreach( $years as $year ): 
			$filter_url = query_url( 'date', $year, $page_url );
			$year_count = get_event_count( 'year', $year );
			if( $year_count != 0 ):
				$classes = 'year';
				if( $year == $year_param ):
					$classes .= ' selected';
				endif;
				echo '<div class="option ' . $classes . '">';
				echo '<a href="' . $filter_url . '" data-value="' . $year . '">';
				echo $year;
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