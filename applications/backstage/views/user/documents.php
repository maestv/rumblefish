<table id="documents">
	<thead>
		<tr>
			<td>Title</td>
			<td>Date</td>
			<td></td>
		</tr>
	</thead>
	<tbody>
	{{#documents}}
		<tr>
			<td>{{document_name}}</td>
			<td>{{uploaded}}</td>
			<td>
				<a href="{{document_url}}" target="_blank">Document Icon</a>
				<a href="<?php echo base_url(); ?>user/removedocument/{{id}}" class="delete">Remove</a>	
			</td>
		</tr>
	{{/documents}}
	{{^documents}}
		<tr>
			<td colspan="3"><p>There are no documents attached to your account!</p></td>
		</tr>
	{{/documents}}
	</tbody>
</table>
{{#if user.isAdmin}}
<a class="button modal" href="<?php echo base_url(); ?>admin/users/adddocuments/{{target_user.id}}" data-width="600" data-height="350">Add Files</a>
{{/if}}