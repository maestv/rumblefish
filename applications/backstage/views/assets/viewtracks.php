<script id="template-asset-view-tracks" type="text/template">
<h2>My Assets (API or Local Search?)</h2>
<form action="<?php echo base_url(); ?>assets/search" method="post">

	<h2>{{page.current.title}}</h2>

	{{#if page.current.page_content}}
	<div class="content">
		{{{page.current.page_content}}}
	</div>
	{{/if}}

	<section id="asset-search">
		<dl>
			<dt><label for="title">Title</label></dt>
			<dd>
				{{#form.title}}
					<input type="text" class="textInput" id="title" name="title[{{@index}}]" value="{{.}}" placeholder="60-90" />
				{{/form.title}}
				{{^form.title}} 
					<input type="text" class="textInput" id="title" name="title[0]" value="" placeholder="" />
				{{/form.title}}
			</dd>
		
			<dt><label for="artist_name">Artist Name</label></dt>
			<dd>
				{{#form.artist_name}}
					<input type="text" class="textInput" id="artist_name" name="artist_name[{{@index}}]" value="{{.}}" placeholder="Snoop Lion" />
				{{/form.artist_name}}
				{{^form.artist_name}} 
					<input type="text" class="textInput" id="artist_name" name="artist_name[0]" value="" placeholder="Snoop Lion" />
				{{/form.artist_name}}
			</dd>
		
			<dt><label for="genre">Album</label></dt>
			<dd>
				{{#form.album}}
					<input type="text" class="textInput" id="album" name="album[{{@index}}]" value="{{.}}" placeholder="" />
				{{/form.album}}
				{{^form.album}} 
					<input type="text" class="textInput" id="album" name="album[0]" value="" placeholder="" />
				{{/form.album}}
			</dd>
			
			<dt><label for="genre">Published Date</label></dt>
			<dd>
				From: <input type="text" class="textInput" id="published_date" name="published_date" value="{{form.published_date}}" placeholder="" />
				To: <input type="text" class="textInput" id="published_date" name="published_date" value="{{form.published_date}}" placeholder="" />
			</dd>
		</dl>
	
		<div class="formActions">
			<input type="submit" name="reset" class="reset" value="Reset" />
			<input type="submit" name="search" value="Search" />
		</div>
	</section>
	
	{{search_url}}

	<section lass="asset-list" id="tracks-list">
		<table>
			<thead>
				<tr>
					<th>Track Name</th>
					<th>Album Name</th>
					<th>Status</th>
					<th>Band Name</th>
					<th>Published Date</th>
					<th></th>
				</tr>
			</thead>
			{{#tracks}}
				<tr>
					<td>{{title}}</td>
					<td>{{album.title}}</td>
					<td>Not Set</td>
					<td>{{artist.name}}</td>
					<td>{{album.published_date}}</td>
					<td><a href="<?php echo base_url(); ?>assets/edittrack/{{id}}">Edit</a></td>
				</tr>
			{{/tracks}}
			{{^tracks}}
			<tbody>
				<tr>
					<td colspan="6">Nothing found.</td>
				</tr>
			</tbody>
			{{/tracks}}
		</tbody>
		
		<?php $this->load->view('pages'); ?>
	
	</section>
</form>
</script>