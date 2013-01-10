<script id="template-artist-details" type="text/template">
	<h2>{{artist.name}}</h2>
	<div class="tabs clearfix" id="accountTabs">
	    <ul class="nav clearfix">
		    <li><span class="anchor" data-href="#artistProfile"><span>Profile</span></span></li>
			<li><span class="anchor" data-href="#artistAssets"><span>Assets</span></span></li>
		</ul>
		<div id="artistProfile" class="tab clearfix">
			
			<section id="artist-profile">
				<aside class="left">
					<div id="imageContainer">
						<img src="{{artist.photo_url}}" alt="" />
					</div>
				</aside>
				
				<frameset>
					<dl>
						<dt><label>Artist / Band</label></dt>
						<dd>{{artist.name}}</dd>
						
						<dt><label>Soundcloud URL</label></dt>
						<dd><a href="{{artist.sound_cloud}}" target="_blank">{{artist.sound_cloud}}</a></dd>
						
						<dt><label>Bandcamp URL</label></dt>
						<dd><a href="{{artist.band_camp}}" target="_blank">{{artist.band_camp}}</a></dd>
						
						<dt><label>Twitter</label></dt>
						<dd><a href="{{artist.twitter}}" target="_blank">{{artist.twitter}}</a></dd>
						
						<dt><label>Website</label></dt>
						<dd><a href="{{artist.website}}" target="_blank">{{artist.website}}</a></dd>
						
						<dt><label>Artists YouTube Channel</label></dt>
						<dd><a href="{{artist.youtube}}" target="_blank">{{artist.youtube}}</a></dd>
						
						<dt></dt>
						<dd><a href="<?php echo base_url(); ?>users/artists/edit/{{id}}">Edit Icon</a></dd>
					</dl>
				</frameset>
				
				<frameset>
					<aside class="left">
						<p><strong>Placements</strong></p>
						<ul>
						{{#artist.placements}}
							<li><label>Placement</label> {{.}}</li>
						{{/artist.placements}}		
						</ul>
					</aside>
					<p>Artist Bio</p>
					<div>
						{{{artist.biography}}}
					</div>
				</frameset>
			</section>
			
		</div>
		<div id="artistAssets" class="tab clearfix">
			<div>
			<ul class="super-sub-nav">
				<li><a href="<?php echo base_url(); ?>">Search</a></li>
				<li><a href="<?php echo base_url(); ?>">Add Track</a></li>
				<li><a href="<?php echo base_url(); ?>">Add Album</a></li>
			</ul>
			</div>
			<br />
			
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Artist</th>
						<th>Title</th>
						<th>Ingested</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				{{#assets.media}}
					<tr>
						<td><a href="<?php echo base_url(); ?>">Play</a></td>
						<td>{{artists.0.name}}</td>
						<td>{{title}}</td>
						<td>NA</td>
						<td>NA</td>
						<td><a href="<?php echo base_url(); ?>">Edit</a></td>
					</tr>
				{{/assets.media}}
				</tbody>
			</table>
		</div>
	</div>
</script>