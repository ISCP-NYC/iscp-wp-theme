<?php 
global $visit;
$title = $post->post_title;
$slug = $post->post_name;
$id = $post->ID;
$intro_image = get_field( 'intro_image', $id );
$intro_text = get_field( 'intro_text', $id );
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

		<div class="info map module">
		<iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyApM4iQyAfb0hbmkeXc_zs58aA_Jy0SIac&q=International+Studio+%26+Curatorial+Program&zoom=16&maptype=roadmap" width="600" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			<!-- <div class="inner" id="map"></div> -->
			<!-- <h3 class="orange title">
				Office Hours:  -->
				<?php 
					// echo $office_hours; 
				?>
			<!-- </h3> -->
			<h3 class="orange title">Exhibition Hours: <?php echo $exhibition_hours; ?></h3>
			<h3 class="orange title address"><?php echo $address ?></h3>
		</div>
<!-- 
		<div class="map module">
			<div class="inner" id="map"></div>
		</div> -->

		
		<!-- <script type="text/javascript">
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
			    new google.maps.Point(25, 25));
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
	    </script> -->
	    <!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApM4iQyAfb0hbmkeXc_zs58aA_Jy0SIac&callback=initMap"></script> -->
		<?php
		echo '<div class="module directions">';
		echo '<h3 class="title">How to Get to ISCP</h3>';
		if ( !empty($intro_image) ):
			echo '<figure class="hero">';
			echo wp_get_attachment_image( $intro_image, 'full' );
			echo '<figcaption>' . wp_get_attachment_caption( $intro_image ) . '</figcaption>';
			echo '</figure>';
		endif;
		if ( !empty($intro_text) ):
			echo $intro_text;
		endif;
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

		<?php if ( $intro_image || $intro_text ):
			// echo '<div class="intro module">';

			// echo '</div>';
		endif; ?>

		<?php if( get_field( 'group_visits', $visit ) || get_field( 'bloomberg_connects', $visit ) ): ?>
			<div class="module visits">
				<?php if( get_field( 'group_visits', $visit ) ): ?>
					<div class="section">
						<h3 class="title">Group Visits</h3>
						<?php the_field( 'group_visits', $visit ); ?>
					</div>
				<?php endif; ?>
				<?php if( get_field( 'bloomberg_connects', $visit ) ): ?>
					<div class="section">
						<h3 class="title">Bloomberg Connects</h3>
						<?php the_field( 'bloomberg_connects', $visit ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if( get_field( 'accessibility', $visit ) ): ?>
			<div class="module accessibility directions">
				<h3 class="title">Accessibility</h3>
				<div class="text-wrapper">
					<?php the_field( 'accessibility', $visit ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="module newsletter">
			<?php get_template_part('partials/newsletter') ?>
		</div>
		
	</div>
	<?php get_template_part('partials/footer') ?>
</section>