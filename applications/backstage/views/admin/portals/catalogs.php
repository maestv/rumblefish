<script id="template-portal-catalogs" type="text/template">
	<h1>Portal Catalogs</h1>
	<form action="<?php echo base_url(); ?>admin/portals/addcatalog" method="post">
		<dl>
			<dt></dt>
			<dd>
				<select name="catalog_id">
				{{#all_catalogs}}
					<option value="{{id}}">{{name}} {{#if provider.name}}({{provider.name}}){{/if}}</option>
				{{/all_catalogs}}
				</select>
			</dd>
			
			<dt><input type="hidden" name="portal_id" value="{{portal_id}}" /></dt>
			<dd><input type="submit" value="Add Catalog" /></dd>
		</dl>
	</form>
	<div id="portal-catalogs">
		<ul class="titles">
			<li>Name</li>
			<li>Provider</li>
			<li>Actions</li>
		</ul>
		
		<div class="current-catalogs">
		</div>	
	</div>
</script>

<script id="template-portal-catalog-row" type="text/template">
	<li>{{catalog.name}}</li>
	<li>{{catalog.provider}}</li>
	<li><a href="<?php echo base_url(); ?>admin/portals/removecatalog/{{id}}" class="remove">Remove</a></li>
</script>