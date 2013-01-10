<script id="template-admin-role-view" type="text/template">
<table>
	<thead>
		<th>Name</th>
		<th>Description</th>
		<th></th>
	</thead>
	<tbody>
	{{#roles}}
		<tr>
			<td>{{ name }}</td>
			<td>{{ description }}</td>
			<td><a href="<?php echo base_url() ?>permissions/edit_role/{{id}}">edit</a></td>
		</tr>
	{{/roles}}
	{{^roles}}
		<tr>
			<td colspan="3">No Roles Defined</td>
		</tr>
	{{/roles}}
	<tbody>
</table>
</script>