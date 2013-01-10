<script id="template-assets-user-search" type="text/template">
<?php // $this->load->view('page-subnavigation'); ?>

<h2>{{page.current.title}}</h2>
<div id="page_content">
{{{page.current.page_content}}}
</div>

<?php $this->load->view('assets/asset-nav'); ?>

<form action="<?php echo base_url(); ?>users/assets/search" method="post">
	<section id="asset-search">
		<dl>
			<dt><label for="search">Keywords</label></dt>
			<dd><input type="text" class="textInput" id="search" name="q" value="{{form.q}}" placeholder="" /></dd>
		
			<dt><label for="artist_names">Artist</label></dt>
			<dd><input type="text" class="textInput" id="artist_names" name="artist_names" value="{{form.artist_names}}" placeholder="" /></dd>
		
			<dt><label for="title">Track title</label></dt>
			<dd><input type="text" class="textInput" id="title" name="title" value="{{form.title}}" placeholder="" /></dd>
		
			<dt><label for="album_title">Album title</label></dt>
			<dd><input type="text" class="textInput" id="album_title" name="album_title" value="{{form.album_title}}" placeholder="" /></dd>
		
			<dt><label for="publisher_id">Publisher ID</label></dt>
			<dd><input type="text" class="textInput" id="publisher_id" <?php //name="publisher_id" ?> value="This Field is not in the search options." placeholder="" /></dd>
		
			<dt><label for="duration">Duration (secconds)</label></dt>
			<dd><input type="text" class="textInput" id="duration" name="duration" value="{{form.duration}}" placeholder="60 - 90" /></dd>
		
			<dt><label for="instrumental">Instrumental</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="instrumental" name="instrumental" value="true" /></dd>
		
			<dt><label for="explicit">Explicit</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="explicit" name="explicit" value="true" /></dd>
		
			<dt><label for="isrc">ISRC</label></dt>
			<dd>
				<select>
					<option>How do I get this?</option>
				</select>
			</dd>
			
			<dt><label for="removed">Show removed assets</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="removed" name="removed" value="true" /></dd>
		</dl>
	
		<div class="formActions">
			<input type="hidden" name="sort" value="{{form.sort}}" />
			<input type="hidden" name="page" value="{{form.page}}" />
			
			<input type="submit" name="reset" class="reset" value="Reset" />
			<input type="submit" name="search" value="Search" />
			
		</div>
	</section>
	
	<?php $this->load->view('pages'); ?>

	<section id="search_result">
		<table>
			<thead>
				<tr>
					<th></th>
					<th>Artists</th>
					<th>Title</th>
					<th>Ingested</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{{#search.media}}	
				<tr id="asset-{{id}}">
					<td class="play"><a href="{{ waveform_url }}">Play</a></td>
					<td class="artist_name">{{#if artists.0.name}} {{artists.0.name}} {{else}}none{{/if}}</td>
					<td class="asset_title">{{title}}</td>
					<td class="ingested-date">NA</td>
					<td class="status">NA</td>
					<td class="actions">
						<a href="#" class="details">Details</a>
						<a href="<?php echo base_url(); ?>/users/assets/edittrack/{{id}}" class="edit">Edit</a>
					</td>
				</tr>
				<tr id="asset-{{id}}-row" class="search-row-details">
					<td colspan="6">
						<ul>
							<li>
								<span class="label">Ingested at:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">Published date:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">Publisher ID:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">Duration:</span>
								{{duration}}
							</li>
							
							<li>
								<span class="label">Provider:</span>
								{{provider.name}}
							</li>
							
							<li>
								<span class="label">Label:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">Genere:</span>
								{{genre}}
							</li>
							
							<li>
								<span class="label">Subgenere:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">BPM</span>
								{{bpm}}
							</li>
							
							<li>
								<span class="label">Explicit:</span>
								{{#if explicit}}True{{else}}False{{/if}}
							</li>
							
							<li>
								<span class="label">Instrumental:</span>
								{{#if instrumental}}True{{else}}False{{/if}}
							</li>
							
							<li>
								<span class="label">Provider reference:</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">UPC</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">ISWC</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">ISRC</span>
								<strong>Not Provided</strong>
							</li>
							
							<li>
								<span class="label">Song credits writer:</span>
								<strong>Not Provided</strong>
							</li>
						</ul>
						
						<div class="keywords">
							<span class="label">Keywords</span>
							<ul>
								<li><strong>Not Provided</strong></li>
							</ul>
						</div>
					</td>
				</tr>
			{{/search.media}}
			{{^search.media}}
				<tr>
					<td colspan="5">{{#if search.error}} {{search.error.q}} {{else}}Nothing Found{{/if}}</td>
				</tr>
			{{/search.media}}
			</tbody>
		</table>
		<?php $this->load->view('pages'); ?>
	</section>
</form>
</script>
