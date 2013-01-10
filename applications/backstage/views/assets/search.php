<script id="template-assets-search" type="text/template">
<?php // $this->load->view('page-subnavigation'); ?>

<h2>{{page.current.title}}</h2>
<div id="page_content">
{{{page.current.page_content}}}
</div>

<?php $this->load->view('assets/asset-nav'); ?>

<form action="<?php echo base_url(); ?>assets/search" method="post">
	<section id="asset-search">
		<dl>
			<dt><label for="search">Keywords</label></dt>
			<dd>
				{{#form.q}}
					<input type="text" class="textInput" id="search" name="q[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.q}}
				{{^form.q}}
					<input type="text" class="textInput" id="search" name="q[0]" value="" placeholder="" />
				{{/form.q}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="bpm">Beats Per Minute</label></dt>
			<dd>
				{{#form.bpm}}
					<input type="text" class="textInput" id="bpm" name="bpm[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.bpm}}
				{{^form.bpm}}
					<input type="text" class="textInput" id="bpm" name="bpm[0]" value="" placeholder="" />
				{{/form.bpm}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="duration">Duration (Seconds)</label></dt>
			<dd>
				{{#form.duration}}
					<input type="text" class="textInput" id="duration" name="duration[{{@index}}]" value="{{.}}" placeholder="60-90" />
				{{/form.duration}}
				{{^form.duration}} 
					<input type="text" class="textInput" id="duration" name="duration[0]" value="" placeholder="60-90" />
				{{/form.duration}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="title">Title</label></dt>
			<dd>
				{{#form.title}}
					<input type="text" class="textInput" id="title" name="title[{{@index}}]" value="{{.}}" placeholder="60-90" />
				{{/form.title}}
				{{^form.title}} 
					<input type="text" class="textInput" id="title" name="title[0]" value="" placeholder="" />
				{{/form.title}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="artist_name">Artist Name</label></dt>
			<dd>
				{{#form.artist_name}}
					<input type="text" class="textInput" id="artist_name" name="artist_name[{{@index}}]" value="{{.}}" placeholder="Snoop Lion" />
				{{/form.artist_name}}
				{{^form.artist_name}} 
					<input type="text" class="textInput" id="artist_name" name="artist_name[0]" value="" placeholder="Snoop Lion" />
				{{/form.artist_name}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="genre">Genre</label></dt>
			<dd>
				{{#form.genre}}
					<input type="text" class="textInput" id="genre" name="genre[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.genre}}
				{{^form.genre}} 
					<input type="text" class="textInput" id="genre" name="genre[0]" value="" placeholder="" />
				{{/form.genre}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
		
		
		
		
		
		
			<dt><label for="catalog_id">Catalog Id</label></dt>
			<dd>
				{{#form.catalog_id}}
					<input type="text" class="textInput" id="catalog_id" name="catalog_id[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.catalog_id}}
				{{^form.catalog_id}} 
					<input type="text" class="textInput" id="catalog_id" name="catalog_id[0]" value="" placeholder="" />
				{{/form.catalog_id}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="catalog_name">Catalog Name</label></dt>
			<dd>
				{{#form.catalog_name}}
					<input type="text" class="textInput" id="catalog_name" name="catalog_name[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.catalog_name}}
				{{^form.catalog_name}} 
					<input type="text" class="textInput" id="catalog_name" name="catalog_name[0]" value="" placeholder="" />
				{{/form.catalog_name}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="catalog_provider_id">Catalog Provider ID</label></dt>
			<dd>
				{{#form.catalog_provider_id}}
					<input type="text" class="textInput" id="catalog_provider_id" name="catalog_provider_id[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.catalog_provider_id}}
				{{^form.catalog_provider_id}} 
					<input type="text" class="textInput" id="catalog_provider_id" name="catalog_provider_id[0]" value="" placeholder="" />
				{{/form.catalog_provider_id}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="catalog_provider_name">Catalog Provider Name</label></dt>
			<dd>
				{{#form.catalog_provider_name}}
					<input type="text" class="textInput" id="catalog_provider_name" name="catalog_provider_name[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.catalog_provider_name}}
				{{^form.catalog_provider_name}} 
					<input type="text" class="textInput" id="catalog_provider_name" name="catalog_provider_name[0]" value="" placeholder="" />
				{{/form.catalog_provider_name}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="explicit">Explicit</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="explicit" name="explicit" value="true" /></dd>
		
			<dt><label for="instrumental">Instrumental</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="instrumental" name="instrumental" value="true" /></dd>
		
			<dt><label for="isrc">ISRC</label></dt>
			<dd>
				{{#form.isrc}}
					<input type="text" class="textInput" id="isrc" name="isrc[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.isrc}}
				{{^form.isrc}} 
					<input type="text" class="textInput" id="isrc" name="isrc[0]" value="" placeholder="" />
				{{/form.isrc}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="grid">GRID</label></dt>
			<dd>
				{{#form.grid}}
					<input type="text" class="textInput" id="grid" name="grid[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.grid}}
				{{^form.grid}} 
					<input type="text" class="textInput" id="grid" name="grid[0]" value="" placeholder="" />
				{{/form.grid}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="iswc">ISWC</label></dt>
			<dd>
				{{#form.iswc}}
					<input type="text" class="textInput" id="iswc" name="iswc[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.iswc}}
				{{^form.iswc}} 
					<input type="text" class="textInput" id="iswc" name="iswc[0]" value="" placeholder="" />
				{{/form.iswc}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		
			<dt><label for="upc">UPC</label></dt>
			<dd>
				{{#form.upc}}
					<input type="text" class="textInput" id="upc" name="upc[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.upc}}
				{{^form.upc}} 
					<input type="text" class="textInput" id="upc" name="upc[0]" value="" placeholder="" />
				{{/form.upc}}
				<a href="#" class="addToSearch">Add</a>
			</dd>
		</dl>
	
		<div class="formActions">
			<input type="submit" name="reset" class="reset" value="Reset" />
			<input type="submit" name="search" value="Search" />
		</div>
	</section>
	
	{{search_url}}

	<section id="search_result">
		<table>
			<thead>
				<tr>
					<th>Title</th>
					<th>Genre</th>
					<th>BPM</th>
					<th>Duration</th>
					<th>Catalogs</th>
					<th>Providers</th>
					<th>Artists</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{{#search.media}}	
				<tr id="asset-{{ id }}">
					<td class="title">{{ title }}</td>
					<td class="genre">{{ genre }}</td>
					<td class="bpm">{{ bpm }}</td>
					<td class="duration">{{ duration }}</td>
					<td>
						{{#catalogs}}
							{{ name }}
						{{/catalogs}}
					</td>
					<td>
					{{#providers}}
						{{ name }}
					{{/providers}}
					</td>
					<td>
					{{#artists}}
						{{ name }}
					{{/artists}}
					</td>
					<td><a href="{{ preview_url }}">Preview</a></td>
					<td><a href="{{ waveform_url }}">Play</a></td>
					<td><a class="add-to-playlist" href="/playlists/add_song">Add to Playlist</a></td>
				</tr>
			{{/search.media}}
			{{^search.media}}
				<tr>
					<td colspan="9">Nothing Found!</td>
				</tr>
			{{/search.media}}
			</tbody>
		</table>
		
		<?php $this->load->view('pages'); ?>
	
	</section>
</form>
</script>
