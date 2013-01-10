<script id="template-admin-role-edit" type="text/template">
<section class="role-edit">
	<form action="<?php echo base_url(); ?>permissions/edit_role/{{role.id}}" method="post">
	
		<div class="formRow">
			<label for="name">Role Name</label>
			<input type="text" id="name" class="textInput" name="name" value="{{ role.name }}" />
		</div>
		
		<div class="formRow">
			<label for="description">Description</label>
			<textarea name="description" class="textareaInput">{{ role.description }}</textarea>
		</div>
		
		<div class="formRow">
			<ul>
			{{#permissions}}
				<li>
					<input type="checkbox" class="checkbox" name="permissions[{{ id }}]" value="{{ id }}"{{ checked }} />
					<label for="permissions[{{ id }}]">{{ description }}</label>
				</li>
			{{/permissions}}
			<ul>
		</div>
		<div class="formRow">
			<input class="button" type="submit" name="save" value="Save" />
			<input type="hidden" name="role_id" value="{{ role.id }}" />
		</div>
	</form>
</section>
</script>