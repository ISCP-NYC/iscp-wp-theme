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
				<h1>
					<a href="call:<?php echo $phone ?>"><?php echo $phone ?></a>
				</h1>
				<h1>
					<a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
				</h1>
			</div>
		</div>

		
		<script type="text/javascript">
		function initMap() {
			var mapStyle = new google.maps.StyledMapType([
			    {
			        "featureType": "all",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#ffffff"
			            }
			        ]
			    },
			    {
			        "featureType": "all",
			        "elementType": "geometry.stroke",
			        "stylers": [
			            {
			                "color": "#ff5000"
			            }
			        ]
			    },
			    {
			        "featureType": "all",
			        "elementType": "labels.text.fill",
			        "stylers": [
			            {
			                "color": "#14233E"
			            }
			        ]
			    },
			    {
			        "featureType": "all",
			        "elementType": "labels.text.stroke",
			        "stylers": [
			            {
			                "color": "#ffffff"
			            }
			        ]
			    },
			    {
			        "featureType": "road.arterial",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#facab4"
			            }
			        ]
			    },
			    {
			        "featureType": "road.local",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#facab4"
			            }
			        ]
			    },
			    {
			        "featureType": "road.highway.controlled_access",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#facab4"
			            }
			        ]
			    },
			    {
			        "featureType": "transit.line",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#14233E"
			            }
			        ]
			    },
			    {
			        "featureType": "transit.line",
			        "elementType": "geometry.stroke",
			        "stylers": [
			            {
			                "color": "#14233E"
			            }
			        ]
			    },
			    {
			        "featureType": "water",
			        "elementType": "geometry.fill",
			        "stylers": [
			            {
			                "color": "#b3d1ff"
			            }
			        ]
			    }
			]);
			var mapStyleId = 'orange';
			var map;
			var location = new google.maps.LatLng(40.714229, -73.934692);
			map = new google.maps.Map(document.getElementById('map'), {
				center: location,
				zoom: 15,
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: false,
				mapTypeId: mapStyleId
			});
			map.mapTypes.set(mapStyleId, mapStyle);
			map.setMapTypeId(mapStyleId);

			<?php
			$theme = get_template_directory_uri();
			$marker = $theme .'/assets/images/marker.svg'
			?>

			var markerImage = new google.maps.MarkerImage('<?php echo $marker; ?>',
			    new google.maps.Size(50, 50),
			    new google.maps.Point(0, 0),
			    new google.maps.Point(0, 50));
			var marker = new google.maps.Marker({
			    position: location,
			    title: 'ISCP',
			    map: map,
			    icon: markerImage
			});

		  	google.maps.event.addDomListener(window, 'resize', function() {
		  		google.maps.event.trigger(map, 'resize');
			    map.setCenter(location);
			});
		}
	    </script>
	    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApM4iQyAfb0hbmkeXc_zs58aA_Jy0SIac&callback=initMap"></script>
		<?php
		echo '<div class="module directions">';
		echo '<h3 class="title">Directions by Subway</h3>';
		if( get_field( 'directions', $visit ) ):
			echo '<ul class="steps">';
			$home = get_page_by_path( 'home' )->ID;
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
				<div class="placeholder"><span>Subscribe to our newsletter</span></div>
				<input type="text" value spellcheck="false"/>
			</form>
		</div>
		
	</div>
	<?php get_template_part('partials/footer') ?>
</section>