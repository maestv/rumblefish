<script id="template-admin-page-view" type="text/template">
<section class="table-list">
	<div id="page_actions">
		<ul>
			<li><a href="<?php echo base_url(); ?>admin/page/view/{{ current.parent_id }}">Back</a></li>
			<li><a href="<?php echo base_url(); ?>admin/page/new/{{ page_level }}">Add New Page</a></li>
		</ul>
	</div>
	<table>
		<thead>
			<th></th>
			<th>Name</th>
			<th>Status</th>
			<th></th>
		</thead>
		<tbody>
		{{#pages}}
		<tr>
			<td>[order]</td>
			<td>
				<p class="title"><a href="<?php echo base_url(); ?>admin/page/view/{{ id }}">{{ title }}</a></p>
				<a href="<?php echo base_url(); ?>admin/page/edit/{{ id }}">edit</a>
			</td>
			<td>{{ status }}</td>
			<td>[disable]</td>
		</tr>
		{{/pages}}
		{{^pages}}
		<tr>
			<td class="no-results" colspan="3"><p>This page has no children.</p></td>
		</tr>
		{{/pages}}
		</tbody>
	</table>
</section>
</script>