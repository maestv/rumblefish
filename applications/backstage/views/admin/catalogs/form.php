<script id="template-catalog-form" type="text/template">
	<h2>{{pages.currentPage.title}}</h2>
	
	<blockquote>
		Waiting on Response from Jeff: https://basecamp.com/2015613/projects/1473222-backstage/messages/7297576-editing-a-catalog
	</blockquote>
	
	<frameset>
		<form action="<?php echo base_url(); ?>admin/catalog/edit/{{catalog.catalog.id}}" method="post">
			<legend>Catalog</legend>
			<dl>
				<dt><label for="name">Catalog Name</label></dt>
				<dd><input type="text" id="name" name="name" class="textInput" value="{{catalog.catalog.name}}" /></dd>
			
				<dt><label for="composition">Composition</label></dt>
				<dd><input type="checkbox" id="composition" name="composition" class="checkbox" value="true"{{#if catalog.catalog.composition}} checked="checked"{{/if}} /></dd>
			
				<dt><label for="sound_effects">Sound Effects</label></dt>
				<dd><input type="checkbox" id="sound_effects" name="sound_effects" class="checkbox" value="true"{{#if catalog.catalog.sound_effects}} checked="checked"{{/if}} /></dd>
			
				<dt><label for="content_id">Content ID</label></dt>
				<dd><input type="checkbox" id="content_id" name="content_id" class="checkbox" value="true"{{#if catalog.catalog.content_id}} checked="checked"{{/if}} /></dd>
			
				<dt><label for="catalog_provider">Provider</label></dt>
				<dd>
					<select name="catalog_provider" id="catalog_provider" class="selectInput">
					{{#providers}}
						<option value="{{id}}">{{name}}</ooption>
					{{/providers}}
					</select>
				</dd>
			</dl>
			<input type="submit" value="Save" />
			
		</form>
	</frameset>	
</script>