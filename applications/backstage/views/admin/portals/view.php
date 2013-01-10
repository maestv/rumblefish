<script id="template-portals-view" type="text/template">
	<h1>Portal View</h1>
	<a href="<?php echo base_url(); ?>admin/portals/create">+ Add Portal</a>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Client</th>
				<th>Logo</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		{{#portals}}
			<tr>
				<td>{{public_key}}</td>
				<td>{{client}}</td>
				<td>
					{{#if logo_url}}
						<a href="{{logo_url}}" target="_blank">view</a>
					{{/if}}
				</td>
				<td>
					<a href="<?php echo base_url(); ?>admin/portals/details/{{id}}" target="_blank">Details</a>
					<a href="<?php echo base_url(); ?>admin/portals/licenses/{{id}}" target="_blank">Licenses</a>
					<a href="<?php echo base_url(); ?>admin/portals/catalogs/{{id}}" target="_blank">Catalogs</a>
				</td>
			</tr>
		{{/portals}}
		{{^portals}}
			<tr>
				<td colspan="5">There are no portals!</td>
			</tr>
		{{/portals}}
		</tbody>
	</table>
</script>