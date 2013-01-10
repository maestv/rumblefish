<script id="template-assets-view-albums" type="text/template">
	<h2>{{page.current.title}}</h2>
	
	{{#if page.current.page_content}}
	<div class="content">
		{{{page.current.page_content}}}
	</div>
	{{/if}}

	
	<section class="asset-list" id="albums-list">
		<table>
			<thead>
				<tr>
					<th>Album Name</th>
					<th>Reccord Label</th>
					<th>Publish Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			{{#albums}}
				<tr>
					<td>{{title}}</td>
					<td>{{record_label}}</td>
					<td>{{publish_date}}</td>
					<td><a href="<?php echo base_url(); ?>/assets/editalbum/{{id}}">Edit</a></td>
				</tr>
			{{/albums}}
			{{^albums}}
				<tr>
					<td colspan="4">There are no albums associated with this account.</td>
				</tr>
			{{/albums}}
			</tbody>
		</table>
	</section>
	
</script>