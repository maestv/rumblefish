<script id="template-album-form" type="text/template">

<?php $this->load->view('page-subnavigation'); ?>
<?php $this->load->view('pages/general-header'); ?>
<?php $this->load->view('assets/asset-nav'); ?>

<form action="<?php echo str_replace("?", "", current_url()); ?>" method="post" class="form">
	<h3>General Information</h3>
	<dl class="form-item">
	
		<dt><label for="title">Album Title</label></dt>
        <dd>
			<input type="text" id="title" class="textInput" name="title" value="{{ album.title }}" />
		</dd>
		
		<dt><label for="upc">UPC</label></dt>
        <dd>
			<input type="text" id="upc" class="textInput" name="upc" value="{{ album.upc }}" />
		</dd>
		
		<dt><label for="record_label">Record Label</label></dt>
        <dd>
			<input type="text" id="record_label" class="textInput" name="record_label" value="{{ album.record_label }}" />
		</dd>
		
		<dt><label for="published_date">Publish Date</label></dt>
        <dd>
			<input type="text" id="published_date" class="textInput datePicker" name="published_date" value="{{ album.published_date }}" />
		</dd>
		
		<dt><label for="album_cover">Album Artwork</label></dt>
        <dd>
			<input type="file" id="album_cover" class="fileInput" name="file" value="" />
			{{#if album.cover_url}}
				<br />
				<input type="hidden" name="cover_filename" value="{{album.cover_filename}}" />
				<input type="hidden" name="cover_url" value="{{album.cover_url	}}" />
				<input type="hidden" name="cover_extension" value="{{album.cover_extension}}" />
				<input type="hidden" name="cover_width" value="{{album.cover_width}}" />
				<input type="hidden" name="cover_height" value="{{album.cover_height}}" />

				<img src="{{album.cover_url}}" alt="{{album.title}}" />
			{{/if}}
		</dd>
	</dl>
	
	<div class="formActions">
		<!-- <input type="submit" name="cancel" value="Cancel" /> -->
		<input type="submit" name="save" value="Save" />
	</div>
	
</form>
</script>