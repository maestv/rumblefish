<script id="template-asset-form" type="text/template">

<?php $this->load->view('page-subnavigation'); ?>
<?php $this->load->view('pages/general-header'); ?>
<?php $this->load->view('assets/asset-nav'); ?>

<form action="<?php echo str_replace("?", "", current_url()); ?>" method="post" class="form">
	<h3>General Information</h3>
	<dl class="form-item">
		<dt><label for="title">Album</label></dt>
        <dd>
			<select name="album">
			{{#if track}}
				<option value="{{track.album.id}}">{{track.album.title}}</option>
			{{else}}
				{{#if album}}
					<option value="{{album.id}}">{{album.title}}</option>
				{{else}}
					<option>Please Select...</option>
				{{/if}}
			{{/if}}
			
			{{#albums}}
				<option value="{{id}}">{{title}}</option>
			{{/albums}}
			</select>
		</dd>
		
		<dt><label for="track_file">Track File</label></dt>
        <dd>
			<input type="file" id="track_file" class="fileInput" name="file" value="" />

			{{#if track.filename}}
				<input type="text" name="visual" readonly="readonly" value="{{track.filename}}" />
			
				<input type="hidden" name="filename" value="{{track.filename}}" />
				<input type="hidden" name="file_path" value="{{track.file_path}}" />
			{{/if}}
		</dd>
		
        <dt><label for="title">Title</label></dt>
        <dd><input type="text" id="title" class="textInput" name="title" value="{{ track.title }}" /></dd>

		<dt><label for="artist_id">Artist</label></dt>	
        <dd>
			<select name="artist_id" id="artist" class="selectInput">
				{{#if track.artist}}
					<option value="{{track.artist.id}}">{{track.artist.name}}</option>
				{{else}}
					<option value="false">Please Select...</option>
				{{/if}}
				
				{{#artists}}
					<option value="{{id}}">{{name}}</option>
				{{/artists}}
			</select>
		</dd>

		<dt><label for="isrc">ISRC</label></dt>
        <dd>
			<input type="text" id="isrc" class="textInput" name="isrc" value="{{ track.isrc }}" />
			<a class="button" href="#">Get</a>
		</dd>

		<dt><label for="type">Type</label></dt>	
        <dd>
			<select name="type" id="type" class="selectInput">
				{{#if track}}
					<option value="{{track.type}}">{{track.type}}</option>
				{{else}}
					<option value="false">Please Select...</option>
				{{/if}}
				
				<option value="main">Main</option>
				<option value="instrumental">Instrumental</option>
				<option value="acoustic">Acoustic</option>
				<option value="remix">Remix</option>
			</select>
		</dd>

		<dt><label for="track_order">Track Number</label></dt>
        <dd><input type="text" id="track_order" class="textInput" name="track_order" value="{{ track.track_order }}" /></dd>

		<dt><label for="lyrics">Lyrics</label></dt>
        <dd><textarea name="lyrics" class="textareaInput" id="lyrics">{{ track.lyrics }}</textarea></dd>
    </dl>

	<h3>Manny to Manny Relationships</h3>
	<dl>
		<dt class="clearfix">
			<label>Songwriters</label> 
			<div action="<?php echo base_url(); ?>search/songwriters" class="inlineSearch" data-target="songwriters-container" data-element="songwriters" method="post">
				<ul>
					<li class="top">
						<input id="searchSongwriters" class="search textInput" name="name" placeholder="Zola Jesus.." value="" autocomplete="off" />
						<a class="add" href="<?php echo base_url(); ?>songwriters/create">+Add</a>
					</li>
				</ul>
			</div>
		</dt>
		<dd>
			<div id="songwriters-container">
				{{#track.songwriters}}
					<p class="tag">{{name}}<input name="songwriters[{{id}}]" type="hidden" value="{{id}}"></p>
				{{/track.songwriters}}
			</div>
		</dd>
		
		<dt class="clearfix">
			<label>Tags (keywords)</label> 
			<div action="<?php echo base_url(); ?>search/tags" class="inlineSearch" data-target="tags-container" data-element="tags" method="post">
				<ul>
					<li class="top">
						<input id="searchTags" class="search textInput" name="tag" placeholder="Ambient.." value="" autocomplete="off" />
						<a class="add" href="<?php echo base_url(); ?>tags/create">+Add</a>
					</li>
				</ul>
			</div>
		</dt>
		<dd>
			<div id="tags-container">
				{{#track.tags}}
					<p class="tag">{{tag}}<input name="tags[{{id}}]" type="hidden" value="{{id}}"></p>
				{{/track.tags}}
			</div>
		</dd>
		
		<dt>
			<label for="">Instruments</label> 
			<div action="<?php echo base_url(); ?>search/instruments" class="inlineSearch" data-target="instruments-container" data-element="instruments" method="post">
				<ul>
					<li class="top">
						<input id="searchInstruments" class="search textInput" name="name" placeholder="Didgeridoo.." value="" autocomplete="off" />
						<a class="add" href="<?php echo base_url(); ?>instruments/create">+Add</a>
					</li>
				</ul>
			</div>
		</dt>
		<dd>
			<div id="instruments-container">
				{{#track.instruments}}
					<p class="tag">{{name}}<input name="instruments[{{id}}]" type="hidden" value="{{id}}"></p>
				{{/track.instruments}}
			</div>
		</dd>
		
		<dt>
			<label for="">Like \ Recommended</label> 
			<input id="like" class="textInput" name="like" placeholder="Aqua.." value="" />
			<a class="button" href="#">Add</a>
		</dt>
		<dd>
		</dd>
	</dl>

	<h3>Specifics</h3>
	</dl>
		<dt><label for="instrumental">Track is Instrumental</label></dt>
        <dd><input type="checkbox" id="instrumental" class="textInput" name="instrumental" value="true"{{#if track.instrumental}} checked="checked"{{/if}} /></dd>

		<dt><label for="vocals">Vocals</label></dt>
        <dd>
			<select name="vocals" id="vocals" class="selectInput">
				{{#if track.vocals}}
				<option value="{{track.vocals}}">{{track.vocals}}</option>
				{{else}}
				<option value="false">Please Select...</option>
				{{/if}}
				
				<option value="male">Male</option>
				<option value="female">Female</option>
			</select>
		</dd>

		<dt><label for="explicit">Track is Explicit</label></dt>
        <dd><input type="checkbox" id="explicit" class="textInput" name="explicit" value="true"{{#if track.explicit}} checked="checked"{{/if}} /></dd>

		<dt><label for="bpm">BPM</label></dt>
        <dd><input type="text" id="bpm" class="textInput" name="bpm" value="{{ track.bpm }}" /></dd>

		<dt><label for="youtube_id">Youtube</label></dt>
        <dd><input type="text" id="youtube_id" class="textInput" name="youtube_id" placeholder="http://www.youtube.com/watch?v=dQw4w9WgXcQ" value="{{ track.youtube_id }}" /></dd>
		
    </dl>

	<div class="formActions">
		<input type="submit" name="cancel" value="Cancel" />
		<input type="submit" name="save" value="Save" />
	</div>
</form>
</script>