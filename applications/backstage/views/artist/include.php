<?php // For admin use the view to include if you need the script tag. ?>
<h2>Manage Artist</h2>
<aside class="subnav">
	<ul>
		<li><a href="<?php echo base_url(); ?>users/artists/add">Add Artist</a></li>
	</ul>
</aside>

<div id="artistSearch" class="search">
	<form action="<?php echo str_replace("?", "", current_url()); ?>/" method="post">
		<label for="artist_name">Artists Name</label>
		<input id="artist_name" name="artist_name" value="{{post.artist_name}}" /> 
		<button>Search</button>
	</form>
</div>
	<?php $this->load->view('the-alphabet'); ?>
	
	<section id="artist-view" class="">
		<?php $this->load->view('pages'); ?>
		
		<div id="artists-list" class="listing">
			<ul class="artists-header listing-header clearfix">
				<li>Artist</li>
				<li>Assets</li>
				<li>Actions</li>
			</ul>
			
			<div class="artists-listing list">
				{{#artists}}
				<div class="list-item clearfix">
					<ul>
						<li>{{name}}</li>
						<li></li>
						<li><a class="button" href="<?php echo base_url(); ?>users/artists/edit/{{id}}">Edit</a></li>
						<li class="search-dropdown">					
							<div>
								<ul class="detailed_info">
									<li>Info:</li>
									<li>Music Rep: {{music_rep.firstname}} {{music_rep.lastname}}</li>
									<li>{{music_rep.company}}</li>
									<li>{{music_rep.email}}</li>
									<li>{{music_rep.address}} {{music_rep.address2}}</li>
									<li>{{music_rep.city}}</li>
									<li>{{music_rep.country}}</li>
									<li>{{music_rep.zip}}</li>
									<li>{{music_rep.phone}} - {{music_rep.phone2}}</li>
									<li>Profile:</li>
									<li><a href="http://<?php echo $_SERVER['SERVER_NAME']."{{photo_url}}"; ?>" target="_blank">Photo</a></li>
									<li><a href="{{sound_cloud}}" target="_blank">Soundcloud</a></li>
									<li><a href="{{band_camp}}" target="_blank">Bandcamp</a></li>
									<li><a href="{{twitter}}" target="_blank">Twitter</a></li>
									<li><a href="{{website}}" target="_blank">Website</a></li>
									<li><a href="{{youtube}}" target="_blank">YouTube</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
				{{/artists}}
				{{^artists}}
				<ul>
					<li>
						{{#if post.artist_name}}
						Nothing found for <strong>{{post.artist_name}}</strong>
						{{else}}
						Nothing was found.
						{{/if}}
					</li>
				</ul>
				{{/artists}}
			</div>
		</div>
		<?php $this->load->view('pages'); ?>
	</section>