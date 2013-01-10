<h3>Music Rep Contact</h3>
<form action="" method="post" class="form">
	{{#if user.isAdmin}}
		<frameset>
			<legend>Account Status</legend>
			
			<dl class="form-item">
		        <dt><label for="status">Account Status</label></dt>
		        <dd>
		        	<select name="status" id="status" class="selectInput" data-default="{{target_user.status}}">
						<option value="active">Active</option>
						<option value="proto">Proto</option>
						<option value="inactive">Inactive</option>
					</select>
		        </dd>

		        <dt><label for="type">Account Type</label></dt>
		        <dd>
		        	<select id="type" name="type" class="selectInput" data-default="{{target_user.type}}">
						<option value="admin">Admin</option>
						<option value="label">Label</option>
						<option value="artist">Artist</option>
					</select>
		        </dd>
		
				<dt><label for="role">User Role</label></dt>
		        <dd><input type="text" class="textInput" name="role" id="role" value="{{target_user.role}}" /></dd>
		
				<dt><label for="license_agreement_sign_date">License Agreement Sign Date</label></dt>
		        <dd><input type="text" name="license_agreement_sign_date" id="license_agreement_sign_date" class="textInput datePicker" value="{{target_user.license_agreement_sign_date}}"></dd>

				<dt><label for="license_agreement_expiration">License Agreement Expiration</label></dt>
		        <dd><input type="text" name="license_agreement_expiration" id="license_agreement_expiration" class="textInput datePicker" value="{{target_user.license_agreement_expiration}}"></dd>
		
			</dl>	
		</frameset>
	{{/if}}
	
    <dl class="form-item">
        <dt><label for="full_name">Full Name</label></dt>
        <dd><input type="text" class="textInput" name="full_name" id="full_name" value="{{target_user.full_name}}" /></dd>

        <dt><label for="company">Company</label></dt>
        <dd><input type="text" class="textInput" name="company" id="company" value="{{target_user.company}}" /></dd>

        <dt><label for="user_name">User name</label></dt>
		{{#if user.isAdmin}}
			<dd><input type="text" class="textInput" name="user_name" id="user_name" value="{{target_user.user_name}}" /></dd>
		{{else}}
			<dd>{{target_user.user_name}}</dd>
		{{/if}}

        <dt><label for="email">Email address</label></dt>
        <dd><input type="text" class="textInput" name="email" id="email" value="{{target_user.email}}" /></dd>

        <dt><label for="street1">Address</label></dt>
        <dd><input type="text" class="textInput" name="street1" id="street1" value="{{target_user.street1}}" /></dd>

        <dt><label for="street2">Address 2</label></dt>
        <dd><input type="text" class="textInput" name="street2" id="street2" value="{{target_user.street2}}" /></dd>

        <dt><label for="city">City</label></dt>
        <dd><input type="text" class="textInput" name="city" id="city" value="{{target_user.city}}" /></dd>

        <dt><label for="state_province">State / Province</label></dt>
        <dd><input type="text" class="textInput" name="state_province" id="state_province" value="{{target_user.state_province}}" /></dd>

        <dt><label for="country">Country</label></dt>
        <dd><input type="text" class="textInput" name="country" id="country" value="{{target_user.country}}" /></dd>

        <dt><label for="postal">Postal</label></dt>
        <dd><input type="text" class="textInput" name="postal" id="postal" value="{{target_user.postal}}" /></dd>

        <dt><label for="phone">Phone</label></dt>
        <dd><input type="text" class="textInput" name="phone" id="phone" value="{{target_user.phone}}" /></dd>

        <dt><label for="phone2">Phone 2</label></dt>
        <dd><input type="text" class="textInput" name="phone2" id="phone2" value="{{target_user.phone2}}" /></dd>

        <dt></dt>
        <dd><input type="submit" name="submit" class="submit" id="submit" value="Update" /></dd>
    </dl>
</form>
