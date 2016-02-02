<?php 
	global $visit;
	$title = $post->post_title;
	$slug = $post->post_name;
	$id = $post->ID;
	$about = get_page_by_path( 'about' );
?>

<section <?php section_attr( $id, $slug, 'visit' ); ?>>
	<?php get_template_part('partials/nav') ?>
	<?php get_template_part('partials/side') ?>
	<div class="content">
		<h2 class="head"><?php echo $title ?></h2>

		<?php
		$office_hours = get_field( 'office_hours', $visit );
		$exhibition_hours = get_field( 'exhibition_hours', $visit );
		$address = strip_tags( get_field( 'address', $about ) );
		$directions_base = 'https://www.google.com/maps/dir//';
		$directions_link = $directions_base . $address;
		$phone = get_field( 'phone', $about );
		$email = get_field( 'email', $about );
		$fax = get_field( 'fax', $about );

		$directions = get_field( 'directions', $visit );
		$directions_footnote = get_field( 'directions_footnote', $visit );
		?>

		<div class="info module">
			<h1>Office Hours: <?php echo $office_hours; ?></h1>
			<h1>Exhibition Hours: <?php echo $exhibition_hours; ?></h1>
			<h1><?php echo $address ?></h1>
		</div>

		<div class="map module">
			<div class="inner" id="map"></div>
			<div class="contact">
				<h1><?php echo $phone ?></h1>
				<h1>
					<a class="black" href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
				</h1>
				<h1><?php echo $fax ?></h1>
			</div>
		</div>

		<?php
		$theme = get_template_directory_uri();
		$marker = $theme .'/assets/images/marker.svg'
		?>
		<script type="text/javascript">
		function initMap() {
			setTimeout(function() {
				var map;
				var location = new google.maps.LatLng(40.7142351, -73.9368839);
				map = new google.maps.Map(document.getElementById('map'), {
					center: location,
					zoom: 15,
					scrollwheel: false,
					navigationControl: false,
					mapTypeControl: false,
					scaleControl: false,
					// draggable: false,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});

			  	var marker = new google.maps.Marker({
					position: location,
					map: map,
					icon: '<?php echo $marker; ?>'
				});

			  	google.maps.event.addDomListener(window, 'resize', function() {
			  		google.maps.event.trigger(map, 'resize');
				    map.setCenter(location);
				});

			},400);
		}
	    </script>
	    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApM4iQyAfb0hbmkeXc_zs58aA_Jy0SIac&callback=initMap"></script>
		<?php
		echo '<div class="module directions">';
		echo '<h3 class="title">Directions by Subway</h3>';
		if( get_field( 'directions', $visit ) ):
			echo '<ul class="steps">';
			while( has_sub_field( 'directions', $visit ) ):
				$step = get_sub_field( 'step', $home );
				echo '<li class="step">' . $step . '</li>';
			endwhile;
			echo '</ul>';
		endif;
		echo '<div class="footnote">';
		echo $directions_footnote;
		echo '</div>';
		echo '</div>';
		?>

		<div class="module newsletter">
			<form role="subscribe" method="get" class="newsletter">
				<input type="text" value="Subscribe to our newsletter" data-placeholder="Subscribe to our newsletter"/>
			</form>
		</div>
		
	</div>
	<?php get_template_part('partials/footer') ?>
</section>