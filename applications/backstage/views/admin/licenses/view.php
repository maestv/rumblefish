<script id="template-licenses-view" type="text/template">
	<h1>{{pages.currentPage.title}}</h1>
	
	<a href="<?php echo base_url(); ?>admin/licenses/create">+ Add License</a>
	<table>
		<thead>
			<tr>
				<th>Purpose</th>
				<th>Summary</th>
				<th>Type</th>
				<th>Version</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		{{#licenses}}
			<tr>
				<td>{{purpose}}</td>
				<td>{{short_description}}</td>
				<td>{{license_type}}</td>
				<td>{{version}}</td>
				<td><a href="<?php echo base_url(); ?>admin/licenses/details/{{id}}">details</a> <a href="<?php echo base_url(); ?>admin/licenses/edit/{{id}}">edit</a></td>
			</tr>
		{{/licenses}}
		{{^licenses}}
			<tr>
				<td colspan="5">There are no licences.</td>
			</tr>
		{{/licenses}}
		</tbody>
	</table>
</script>