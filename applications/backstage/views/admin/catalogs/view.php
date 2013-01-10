<script id="template-catalog-view" type="text/template">
<h2>Catalog Providers</h2>
	<section class="asset-list" id="catalogList">
	
		<div id="catalogProviders">
			<ul>
				<li>Name</li>
				<li>Composition</li>
				<li>Sound Effects</li>
				<li>Provider</li>
				<li></li>
			</ul>
			<div class="catalogProviders-container">
				{{#catalogs}}
					<ul>
						<li>{{name}}</li>
						<li>{{#if composition}}True{{else}}False{{/if}}</li>
						<li>{{#if sound_effects}}True{{else}}False{{/if}}</li>
						<li>{{provider.name}}</li>
						<li>
							<a href="<?php echo base_url(); ?>admin/catalog/edit/{{id}}">edit</a>
							<a href="<?php echo base_url(); ?>admin/catalog/licenses/{{id}}">license</a>
						</li>
					</ul>
				{{/catalogs}}
			</div>
		</div>
	</section>
</script>