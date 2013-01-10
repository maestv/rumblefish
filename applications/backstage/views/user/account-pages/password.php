<h3>Password</h3>

<form action="<?php echo base_url() ?>user/changepassword" method="post" class="form">
    <dl class="form-item">
        <dt><label for="password">Old Password</label></dt>
        <dd><input type="password" class="textInput" name="password" id="password" value="" /></dd>
    </dl>
    <dl class="form-item">
        <dt><label for="new_password">New Password</label></dt>
        <dd><input type="password" class="textInput" name="new_password" id="new_password" value="" /></dd>
    </dl>
	<dl class="form-item">
        <dt><label for="confirm_password">Repeat New Password</label></dt>
        <dd><input type="password" class="textInput" name="confirm_password" id="confirm_password" value="" /></dd>
    </dl>
    <dl>
        <dt></dt>
        <dd>				
			<input type="submit" class="submit" id="submit" value="Update" />
			
			{{#if user.isAdmin}}
				<input type="hidden" name="user_id" value="{{target_user.id}}" />
				<a class="resetEmail button" href="<?php echo base_url(); ?>user/emailpassword" data-email="{{target_user.email}}">Reset Password Email</a>	
			{{/if}}
		</dd>
    </dl>
</form>
