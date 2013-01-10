<script id="template-register-page" type="text/template">
    <div class="errorDisp"></div>
    <form action="<?php echo base_url(); ?>user/register" method="post" class="form">

        <div id="reg-form-0" class="collapsable-form">
            <h1>Thank you for your interest in Rumblefish</h1>
            <frameset>
				<legend><b>Step 1:</b> Contact Information</legend>

            	<dl class="form-item">
	                <dt><label for="full_name">Full name (ex. John Smith)</label></dt>
	                <dd><input type="text" name="full_name" id="full_name" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="email">Email address</label></dt>
	                <dd><input type="text" name="email" id="email" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="confirm-email">Confirm email address</label></dt>
	                <dd><input type="text" name="confirm-email" id="confirm-email" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="street1">Address</label></dt>
	                <dd><input type="text" name="street1" id="street1" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="street2">Address 2</label></dt>
	                <dd><input type="text" name="street2" id="street2" value="" /></dd>
	            </dl>
				<dl class="form-item">
	                <dt><label for="city">City</label></dt>
	                <dd><input type="text" name="city" id="city" value="" /></dd>
	            </dl>
				<dl class="form-item">
	                <dt><label for="state_province">State / Province</label></dt>
	                <dd><input type="text" name="state_province" id="state_province" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="country">Country</label></dt>
	                <dd><input type="text" name="country" id="country" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="postal">Postal</label></dt>
	                <dd><input type="text" name="postal" id="postal" value="" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt>Are you a (check one):</dt>
	                <dd></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-music-rep">Artist/Music Rep</label></dt>
	                <dd><input type="radio" name="reg-type" id="reg-type-artist" class="reg-type" value="artist" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-music-rep">Label/Distro/Publisher</label></dt>
	                <dd><input type="radio" name="reg-type" id="reg-type-label" class="reg-type" value="label" /></dd>
	            </dl>
	            <input type="hidden" id="selected-reg-type">
	            <dl class="form-item">
	                <dt></dt>
	                <dd><li id="next-reg-form-1-_type_" class="like-link next-button">Next</li></dd>
	            </dl>
			</frameset>
        </div>

        <div id="reg-form-1-artist" class="collapsable-form artist-form">
			<frameset>
				<legend><b>Step 2:</b> Artist Profile</legend>
            	<dl class="form-item" id="artist-name">
	                <dt><label for="artist-name">Artist Name</label></dt>
	                <dd><input type="text" name="artist[0][name]" id="artist-name" class="textInput" /></dd>
	            </dl>
	
	            <dl class="form-item" id="placement-original">
	                <dt><label for="placement">Placement</label></dt>
	                <dd>
						<input type="text" name="artist[0][placement][]" class="placement-duplicate-label placement-text-input placement-original textInput" />
					</dd>
	            </dl>
	
	            <dl class="form-item">
	                <dt><input type="hidden" name="placement-array-artist" id="placement-array-artist"/></dt>
	                <dd><li id="add-additional-placement-artist" class="like-link add-additional">Add Additional Placement</li></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-soundcloud">SoundCloud URL</label></dt>
	                <dd><input type="text" name="artist[0][soundcloud]" id="soundcloud" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-bandcamp">Bandcamp URL</label></dt>
	                <dd><input type="text" name="artist[0][bandcamp]" id="bandcamp" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-website">website</label></dt>
	                <dd><input type="text" name="artist[0][website]" id="artist-website" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="artist-youtube">Artist YouTube channel</label></dt>
	                <dd><input type="text" name="artist[0][youtube]" id="artist-youtube" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt></dt>
	                <dd>
						<a id="addArtists" href="#" class="button">Add Another Artists</a>
						<input type="submit" value="Finnish" class="button" />
					</dd>
	            </dl>
			</frameset>
        </div>

        <div id="reg-form-1-label" class="collapsable-form">
			<frameset>
				<legend>Label Information</legend>
           	 	<p>Please fill out the following information to submit your music for consideration.</p>

	            <dl class="form-item" id="placement-duplicate-label-0">
	                <dt><label for="placement">Placement</label></dt>
	                <dd>
						<input type="text" name="placement[]" class="placement-duplicate-label placement-text-input placement-original" />
					</dd>
	            </dl>
	
	            <dl class="form-item">
	                <dt></dt>
	                <dd><li id="add-additional-placement-label" class="like-link add-additional">Add Additional Placement</li></dd>
	            </dl>
	
	            <dl class="form-item">
	                <dt><label for="label-soundcloud">SoundCloud URL</label></dt>
	                <dd><input type="text" name="label-soundcloud" id="label-soundcloud" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="label-bandcamp">Bandcamp URL</label></dt>
	                <dd><input type="text" name="label-bandcamp" id="label-bandcamp" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="label-website">Website</label></dt>
	                <dd><input type="text" name="label-website" id="label-website" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt><label for="label-youtube">YouTube channel</label></dt>
	                <dd><input type="text" name="label-youtube" id="label-youtube" /></dd>
	            </dl>
	            <dl class="form-item">
	                <dt></dt>
	                <input type="submit" value="Finish Registration" />
	            </dl>
			</frameset>
        </div>

        <div id="reg-form-final" class="collapsable-form">
            <h1>Thank You</h1>
            <p>We will get back to you shortly.</p>
        </div>
    </form>
</script>

<script id="template-register-success-page" type="text/template">
    Success!
</script>
