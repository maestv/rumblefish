<!-- <script id="template-user-search" type="text/template">


	<div class="tabs clearfix" id="userTabs">
	    <ul class="nav clearfix">
		    <li><span class="anchor" data-href="#searchAccounts"><span>Search Accounts</span></span></li>
			<li><span class="anchor" data-href="#addAccount"><span>Add Account</span></span></li>
		</ul>
		<div id="searchAccounts" class="tab clearfix">
			<?php $this->load->view('user/account-pages/contacts'); ?>
		</div>
		<div id="addAccount" class="tab clearfix">
			<?php $this->load->view('user/account-pages/contacts'); ?>
		</div>




	<section class="search">
		<form action="<?php echo base_url(); ?>user/s" method="post">
	
			<div class="formRow">
				<label for="username">Username:</label>
				<input type="text" id="username" class="textInput" name="username" value="{{ post.username }}" />
			</div>
		
			<div class="formRow">
				<label for="email">Email:</label>
				<input type="text" id="email" class="textInput" name="email" value="{{ post.email }}" />
			</div>
		
			<div class="formRow">
				<label for="firstname">First name:</label>
				<input type="text" id="firstname" class="textInput" name="firstname" value="{{ post.firstname }}" />
			</div>
		
			<div class="formRow">
				<label for="lastname">Last name:</label>
				<input type="text" id="lastname" class="textInput" name="lastname" value="{{ post.lastname }}" />
			</div>
		
			<div class="formRow">
				<label for="city">City:</label>
				<input type="text" id="city" class="textInput" name="city" value="{{ post.city }}" />
			</div>
		
			<div class="formRow">
				<label for="state">State:</label>
				<select id="state" name="state" class="selectInput" value="{{ post.state }}">
				
				{{#post.state}}
					<option value="{{ post.state }}">{{ post.state }}</option>
					<option value="false">Unset</option>
				{{/post.state}}
				
				{{^post.state}}
					<option value="false">Please Select...</option>
				{{/post.state}}
				
				{{#states}}
					<option value="{{abbreviation}}">{{state}}</option>
				{{/states}}
				</select>
			</div>
		
			<div class="formRow">
				<label for="zip">Zip:</label>
				<input type="text" id="zip" class="textInput" name="zip" value="{{ post.zip }}" />
			</div>
		
			<div class="formRow">
				<label for="created">Created:</label>
				<input type="text" id="createdBefore" class="textInput datePicker" name="created[before]" value="{{ post.created.before }}" />
				<input type="text" id="createdAfter" class="textInput datePicker" name="created[after]" value="{{ post.created.after }}" />
			</div>
		
			<div class="formRow">
				<label for="">Modified:</label>
				<input type="text" id="modifiedBefore" class="textInput datePicker" name="modified[before]" value="{{ post.modified.before }}" />
				<input type="text" id="modifiedAfter" class="textInput datePicker" name="modified[after]" value="{{ post.modified.after }}" />
			</div>
		
			<div class="formRow">
				<label for="last_login">Last Login:</label>
				<input type="text" id="last_loginBefore" class="textInput datePicker" name="last_login[before]" value="{{ post.last_login.before }}" />
				<input type="text" id="last_loginAfter" class="textInput datePicker" name="last_login[after]" value="{{ post.last_login.after }}" />
			</div>
		
			<div class="formRow">
				<input class="button" type="submit" name="search" value="Search" />
			</div>
		</form>
	</section>

	<section class="search-results">
		<table>
			<thead>
				<tr>
					<th>Username</th>
					<th>Email</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Albums</th>
					<th>Assets</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
			{{#accounts}}
				<tr>
					<td>{{ username }}</td>
					<td>{{ email }}</td>
					<td>{{ firstname }}</td>
					<td>{{ lastname }}</td>
					<td></td>
					<td></td>
					<td>{{ created }}</td>
				</tr>
			{{/accounts}}
			</tbody>
		</table>
	</section>
</script> -->
