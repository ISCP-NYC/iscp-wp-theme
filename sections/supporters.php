<?php
global $post;
$id = $post->ID;
$slug = $post->post_name;
$description = get_field( 'description' );
?>
<section <?php section_attr( $id, $slug, 'supporters' ); ?> data-page="<?php echo $paged ?>">
	<?php get_template_part( 'partials/nav' ) ?>
	<?php get_template_part( 'partials/side' ) ?>
	<div class="content">
		<h2 class="head">
			2016 Supporters
		</h2>
		<?php
		if( have_rows( 'images', $id ) ):
			echo '<div class="images module">';
				while ( have_rows( 'images', $id ) ) : the_row();
					$image = get_sub_field( 'image' )['sizes']['thumb'];
					$caption = get_sub_field( 'caption' );
					if( $image ):
						echo '<div class="image">';
						echo '<div class="inner">';
								echo '<img src="' . $image . '"/>';
								echo '<div class="caption">' . $caption  . '</div>';
								echo '</div>';
								echo '</div>';
						endif;
				endwhile;
			echo '</div>';
		endif;

		echo '<div class="module description">';
			echo $description;
		echo '</div>';

		$types = array(
			'found-trust' => 'Foundations &amp; Trusts',
			'individual' => 'Individual',
			'gov' => 'Government',
			'corp' => 'Corporations',
			'probono' => 'In-kind & Pro-Bono Support'

		);
		foreach( $types as $type_slug => $type ) {
			include( locate_template( 'sections/params/supporters.php' ) );
			$supporters = new WP_Query( $supporters_query );
			if( $supporters->post_count ) {
				echo '<h2 class="head">' . $type . '</h2>';
				echo '<div class="supporters shelves filter-this list items">';
					include( locate_template( 'sections/loops/supporters.php' ) );
				echo '</div>';
			}
		}
		?>
	</div>
	<?php get_template_part( 'partials/footer' ); ?>
</section>