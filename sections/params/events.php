<?php
$title = get_post( $post )->post_title;
$slug = get_post( $post )->post_name;
$id = get_post( $post )->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$event_classes = $event_type_param . ' ' . $year_param;
$page_url = get_the_permalink();
$paged = 1;
?>