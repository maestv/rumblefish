<script id="template-artist-form" type="text/template">
<?php $this->load->view('page-subnavigation'); ?>
	<section id="artist-view" class="">
		<h2>{{page.header}}</h2>
		
		<form action="<?php echo str_replace("?", "", current_url()); ?>" method="post">
			<dl>
				<dt><label for="name">Name</label></dt>
				<dd><input type="text" class="textInput" id="name" name="name" placeholder="" value="{{artist.name}}" /></dd>
				
				<dt><label for="location">Location</label></dt>
				<dd><input type="text" class="textInput" id="location" name="location" placeholder="" value="{{artist.location}}" /></dd>
				
				<dt><label for="website">Website</label></dt>
				<dd><input type="text" class="textInput" id="website" name="website" placeholder="http://" value="{{artist.website}}" /></dd>
				
				<dt><label for="facebook">Facebook</label></dt>
				<dd><input type="text" class="textInput" id="facebook" name="facebook" placeholder="https://www.facebook.com/weirdal" value="{{artist.facebook}}" /></dd>
				
				<dt><label for="twitter">Twitter</label></dt>
				<dd><input type="text" class="textInput" id="twitter" name="twitter" placeholder="https://twitter.com/..." value="{{artist.twitter}}" /></dd>
				
				<dt><label for="youtube">Youtube</label></dt>
				<dd><input type="text" class="textInput" id="youtube" name="youtube" placeholder="http://www.youtube.com/watch?v=oHg5SJYRHA0" value="{{artist.youtube}}" /></dd>
				
				<dt><label for="band_camp">Band Camp</label></dt>
				<dd><input type="text" class="textInput" id="band_camp" name="band_camp" placeholder="http://bandcamp.com/" value="{{artist.band_camp}}" /></dd>
				
				<dt><label for="sound_cloud">Sound Cloud</label></dt>
				<dd><input type="text" class="textInput" id="sound_cloud" name="sound_cloud" placeholder="http://soundcloud.com/flightfacilities" value="{{artist.sound_cloud}}" /></dd>
				
				<dt><label for="biography">Biography</label></dt>
				<dd><textarea class="textareaInput" id="biography" name="biography">{{artist.biography}}</textarea></dd>

				<dt><label for="album_cover">Artist Photo</label></dt>
		        <dd>
					{{#if artist.photo_url}}
						<img src="{{artist.photo_url}}" alt="" /><br />
					{{/if}}
					<input type="file" id="artist_photo" class="fileInput" name="file" value="" />
				</dd>
			</dl>
			<div class="formActions">
				<input type="submit" name="save" value="Save" />
			</div>
		</form>
	</section>
</script>
