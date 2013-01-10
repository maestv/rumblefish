<section class="search">
	<form action="<?php echo base_url(); ?>user/s" method="post">
		
		<div class="formRow">
			<label for="full_name">Full Name:</label>
			<input type="text" id="full_name" class="textInput" name="full_name" value="{{ post.full_name }}" />
		</div>
		
		<div class="formRow">
			<label for="type">Account Type:</label>
			<select name="type" id="type" class="selectInput">
				<option value="false">Please Select</option>
				<option value="admin">Admin</option>
				<option value="label">Label</option>
				<option value="artist">Artist</option>
			</select>
		</div>
		
		<div class="formRow">
			<label for="user_name">User name:</label>
			<input type="text" id="user_name" class="textInput" name="user_name" value="{{ post.user_name }}" />
		</div>
		
			<div class="formRow">
				<label for="company">Company:</label>
				<input type="text" id="company" class="textInput" name="company" value="{{ post.company }}" />
			</div>
	
		<div class="formRow">
			<label for="email">Email:</label>
			<input type="text" id="email" class="textInput" name="email" value="{{ post.email }}" />
		</div>
	
		<div class="formRow">
			<label for="status">Status:</label>
			<select id="status" name="status" class="selectInput" value="{{post.status}}">
				<option value="false">Please Select...</option>
				<option value="active">Active</option>
				<option value="proto">Proto</option>
				<option value="inactive">Inactive</option>
			</select>
		</div>

		<div class="formRow">
			<input class="button" type="submit" name="search" value="Search" />
		</div>
	</form>
</section>

<div id="searchInformation">
	{{#if searchInfo.num_rows}}
		Your search returned {{searchInfo.num_rows}} results.
	{{/if}}
</div>

<?php $this->load->view('the-alphabet'); ?>

<section class="search-results">
	<table>
		<thead>
			<tr>
				<th>Account</th>
				<th>Account Type</th>
				<th>Status</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		{{#accounts}}
			<tr>
				<td>{{email}}</td>
				<td>{{type}}</td>
				<td>{{status}}</td>
				<td><a href="<?php echo base_url() ?>admin/users/edit/{{id}}">Edit</a></td>
			</tr>
		{{/accounts}}
		</tbody>
	</table>
</section>