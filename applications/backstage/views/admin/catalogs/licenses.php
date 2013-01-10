<script id="template-catalog-licenses" type="text/template">
<h2>Catalog Licenses</h2>
	<section class="asset-list" id="catalogLicenses">
		<div>
			<ul>
				<li>Purpose</li>
				<li>Split Percentage</li>
				<li>Price</li>
				<li></li>
			</ul>
			
			<div class="catalog-licenses">
			
			</div>
		</div>
	</section>
	
	<form class="addLicense" action="<?php echo base_url() ?>admin/catalog/addlicense" method="post">
		<dl>
			<dt><label for="license">License</label></dt>
			<dd>
				<select id="license" name="license_id">
				{{#all_licenses}}
					<option value="{{id}}">{{purpose}} {{#if short_description}}({{short_description}}){{/if}}</option>
				{{/all_licenses}}
				</select>
			</dd>
			
			<dt><label for="split_percentage">Split Percentage</label></dt>
			<dd><input type="text" id="split_percentage" name="split_percentage" class="textInput" value="" placeholder="Decimal value like: 0.5" /></dd>
			
			<dt><label for="price">Price</label></dt>
			<dd>$<input type="text" id="price" name="price" class="textInput" value="" placeholder="1.99" /></dd>
			
			<dt></dt>
			<dd>
				<input type="hidden" id="catalog_id" name="catalog_id" value="{{catalog.catalog.id}}" />
				<input type="submit" value="Add License" class="button" />
			</dd>
		</dl>
	</form>
</script>

<script id="template-catalog-licenses-row" type="text/template">
	<li><marquee>Why no Purpose!?</marquee></li>
	<li>%{{split_percentage}}</li>
	<li>${{price}}</li>
	<li>
		<a href="#" class="edit">Edit</a>
		<a href="<?php echo base_url(); ?>admin/catalog/removelicenses/{{id}}" class="remove">Remove</a>
	</li>
</script>

<script id="template-catalog-licenses-edit" type="text/template">
	<li><input type="text" class="textInput" name="purpose" value="" /></li>
	<li><input type="text" class="textInput" name="split_percentage" value="{{split_percentage}}" /></li>
	<li><input type="text" class="textInput" name="price" value="{{price}}" /></li>
	<li>
		<input type="hidden" name="id" value="{{id}}" />
		<input type="submit" value="Save" />
	</li>
</script>