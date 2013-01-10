<script id="template-user-change-password" type="text/template">
<section class="role-edit">
	<form action="<?php echo base_url(); ?>user/changepassword" method="post">
	
		{{#if showOld}}
		<div class="formRow">
			<label for="password">Current Password:</label>
			<input type="password" id="password" class="textInput" name="password" value="" />
		</div>
		{{/if}}
		
		<div class="formRow">
			<label for="new_password_1">New Password:</label>
			<input type="password" id="new_password_1" class="textInput" name="new_password_1" value="" />
		</div>
		
		<div class="formRow">
			<label for="new_password_2">Confirm Password:</label>
			<input type="password" id="new_password_2" class="textInput" name="new_password_2" value="" />
		</div>
		
		<div class="formRow">
			<input class="button" type="submit" name="save" value="Save" />
		</div>
	</form>
</section>
</script>