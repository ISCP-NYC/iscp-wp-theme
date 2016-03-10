<?php
$page_url = $query_vars['url'];
$type_param = $query_vars['type'];
$year_param = $query_vars['date'];
?>
<div class="filter-list type <?php echo $slug ?>" data-filter="type">
	<div class="options">
		<?php
		$event_types = array( 'exhibition', 'off-site-project', 'iscp-talk', 'open-studios', 'events' );
		foreach( $event_types as $event_type ):
			$filter_url = query_url( 'type', $event_type, $page_url );
			$event_type_count = get_event_count( 'type', $event_type );
			if( $event_type_count != 0 ):
				if( $event_type == $type_param ):
					$selected = 'selected';
				else:
					$selected = null;
				endif;
				echo '<div class="option ' . $selected . '">';
				echo '<a href="' . $filter_url . '" data-value="' . $event_type . '">';
				$event_type = pretty( $event_type );
				echo $event_type;
				// echo ' (<span class="count">';
				// echo $event_type_count;
				// echo '</span>)';
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
				if( $year == $year_param ):
					$selected = 'selected';
				else:
					$selected = null;
				endif;
				echo '<div class="option ' . $selected . '">';
				echo '<a href="' . $filter_url . '" data-value="' . $year . '">';
				echo $year;
				// echo ' (<span class="count">';
				// echo $year_count;
				// echo '</span>)';
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