<?php
if( $query_vars ):
	$pageType = $query_vars['pagename'];
	if( strpos( $pageType, 'residents' ) ):
		$pageType = 'residents';
	endif;
	include( locate_template( 'sections/params/' . $pageType . '.php' ) );
	$query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
	$options = $query_vars['options'];
	$counts = array();
	foreach( $options as $index=>$option ):
		$type = $option['type'];
		$value = $option['value'];
		$_value = $value;
		if( $type == 'country' && !is_numeric( $value ) ):
			$value= get_page_by_path( $value, OBJECT, 'country' )->ID;
		endif;

		$count = get_resident_count( $type, $value, $query );

		$array = array(
			'type' => $type,
			'value' => $_value,
			'count' => $count,
		);
		$counts[$index] = $array;
	endforeach;
	echo json_encode( $counts );
endif;
?>