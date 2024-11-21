<?php
global $post;
setup_postdata( $post );
$event_id = $post->ID;
$event_status = get_event_status( $event_id );
$event_title = get_the_title( $event_id );
$event_url = get_permalink();
$event_type = get_field( 'event_type' );
$event_type_name = pretty( $event_type );
$event_status = get_event_status( $event_id );
$event_date_format = get_event_date( $event_id );
$event_thumb = get_thumb( $event_id, 'thumb' );
$args_class = $args['class'] ? $args['class'] : '';$today = date('Ymd');
$opening = null;
$type_url = query_url( 'type', $event_type, site_url() );
if( get_field( 'opening_reception', $event_id ) >= $today ):
	$opening = get_field( 'opening_reception', $event_id );
	if( $opening ) {
		$opening = new DateTime( $opening );
		$opening = $opening->format('M d, Y');
	}
	$opening_hours = get_field( 'opening_reception_hours', $event_id );
	if( $opening_hours ):
		$opening .= ', ' . $opening_hours;
	endif;
endif;
?>

<div class="event item <?= $args_class  . ' ' . $event_status?>" data-id="<?= $event_id ?>">
  <div class="inner">
    <a class="wrap value" href="<?= $event_url ?>">
      <figure class="image">
        <img src="<?= $event_thumb ?>"/>
      </figure>
      <h3 class="link name title"><?=  $event_title ?></h3>
    </a>
    <div class="value event-type">
      <a href="<?= $type_url . '/events' ?>">
        <?= $event_type_name ?>
      </a>
    </div>
      <?php if( $opening ): ?>
        <div class="value date">Opening Reception:&nbsp; <?= $opening ?></div>
      <?php endif; ?>
      <div class="value date"><?= $event_date_format ?></div>
  </div>
</div>

<?php 
wp_reset_postdata();
?>