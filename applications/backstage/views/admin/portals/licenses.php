<script id="template-portal-licenses" type="text/template">
	<h1>Portal Licence</h1>
	<form action="<?php echo base_url(); ?>admin/portals/addlicense" method="post">
		<dl>
			<dt></dt>
			<dd>
				<select name="license_id">
				{{#all_licenses}}
					<option value="{{id}}">{{purpose}} {{#if short_description}}({{short_description}}){{/if}}</option>
				{{/all_licenses}}
				</select>
			</dd>
			
			<dt>Download</dt>
			<dd><input type="checkbox" name="download" value="true" /></dd>
			
			<dt>Price</dt>
			<dd><input type="text" name="price" value="" placeholder="$0.99" /></dd>
			
			<dt><input type="hidden" name="portal_id" value="{{portal_id}}" /></dt>
			<dd><input type="submit" value="Add License" /></dd>
		</dl>
	</form>
	
	<div id="portal-licenses">
		<ul class="titles">
			<li>Purpose</li>
			<li>Type</li>
			<li>Price</li>
			<li>Download</li>
			<li>Actions</li>
		</ul>
		
		<div class="current-licenses">
		</div>
		
	</div>
</script>

<script id="template-portal-license-row-view" type="text/template">
	<li>{{license.purpose}}</li>
	<li>{{license.type}}</li>
	<li>{{price}}</li>
	<li>{{#if download}}True{{else}}False{{/if}}</li>
	<li><a href="#" class="edit">Edit</a></li>
</script>

<script id="template-portal-license-row-edit" type="text/template">
<form action="<?php echo base_url(); ?>admin/portals/updatelicense" method="post">
	<li>
		{{license.purpose}}
		<input type="hidden" name="id" value="{{id}}" />
		<input type="hidden" name="license_id" value="{{license.id}}" />
	</li>
	<li>{{license.type}}</li>
	<li><input type="text" name="price" value="{{price}}" /></li>
	<li><input type="checkbox" name="download" value="true"{{#if download}} checked="checked"{{/if}} /></li>
	<li>
		<input type="submit" value="Save" />
		<a href="<?php echo base_url(); ?>admin/portals/removelicense/{{id}}" class="delete">Remove</a>
	</li>
</form>
</script>