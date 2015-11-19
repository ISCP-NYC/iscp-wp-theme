<?php
	$about = get_page_by_path( 'about' );
	$about_id = $about->ID;
	$twitter_handle = str_replace( '@', '', get_field( 'twitter', $about_id ) );
	$twitter_url = 'http://twitter.com/' . $twitter_handle;
	$twitter_api = 'https://api.twitter.com/1.1/statuses/user_timeline/' . $twitter_handle . '.json?count=3&include_rts=1&callback=?';
?>


<div class="twitter module">
	<h3 class="title orange">
		<a href="<?php echo $twitter_url ?>" target="_blank">
			Follow us on Twitter @iscp_nyc
		</a>
	</h3>
	<div class="tweets">
		<div class="tweet">
			<div class="body medium">
				Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
			</div>
			<div class="orange time small">5h</div>
		</div>
		<div class="tweet">
			<div class="body medium">
				Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
			</div>
			<div class="orange time small">5h</div>
		</div>
		<div class="tweet">
			<div class="body medium">
				Resident So Yoon Lym solo exhibition opening 10/20 @Bumble @winsorandnewton @MStudio_Asbury
			</div>
			<div class="orange time small">5h</div>
		</div>
	</div>
</div>