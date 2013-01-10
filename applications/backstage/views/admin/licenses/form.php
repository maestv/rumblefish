<script id="template-licenses-form" type="text/template">
	<h1>License Form</h1>
    <div id="warning-message">
        
    </div>
	<form action="" method="post">
		<dl>
			<dt><label for="purpose">Purpose</label></dt>
			<dd><input type="text" class="textInput" id="purpose" name="purpose" value="{{license.purpose}}" placeholder="" /></dd>
			
			<dt><label for="downloads">Allow Downloads</label></dt>
			<dd><input type="checkbox" class="textInput" id="download" name="download" value="true"{{#if license.download}} checked="checked"{{/if}} /></dd>
			
			<dt><label for="license_type">Type</label></dt>
			<dd><input type="text" class="textInput" id="type" name="license_type" value="{{license.license_type}}" placeholder="" /></dd>
			
			<dt><label for="version">Version</label></dt>
			<dd><input type="text" class="textInput" id="version" name="version" value="{{license.version}}" placeholder="" /></dd>
			
			<dt><label for="rights">Rights</label></dt>
			<dd><input type="text" class="textInput" id="rights" name="rights" value="{{license.rights}}" placeholder="You gotta fight!" /></dd>
			
			<dt><label for="region">Region</label></dt>
			<dd><input type="text" class="textInput" id="region" name="region" value="{{license.region}}" placeholder="" /></dd>
			
			<dt><label for="term">Term</label></dt>
			<dd><input type="text" class="textInput" id="term" name="term" value="{{license.term}}" placeholder="" /></dd>
			
			<dt><label for="short_description">Excerpt</label></dt>
			<dd>
				<div class="edit-full">
					<?php $this->load->view("rte-toolbar", array("id"=>"short_description_toolbar")); ?>
					<textarea id="short_description" class="rte" name="short_description">{{{license.short_description}}}</textarea>
				</div>
			</dd>
			
			<dt><label for="long_description">Description</label></dt>
			<dd>
				<div class="edit-full">
					<?php $this->load->view("rte-toolbar", array("id"=>"long_description_toolbar")); ?>
					<textarea id="long_description" class="rte" name="long_description">{{{license.long_description}}}</textarea>
				</div>
			</dd>
			
			<dt><label for="text">Text</label></dt>
			<dd>
				<div class="edit-full">
					<?php $this->load->view("rte-toolbar", array("id"=>"license_text_toolbar")); ?>
					<textarea id="text" class="rte" name="license_text">{{{license.text}}}</textarea>
				</div>
			</dd>
			
		</dl>
		<div class="form-functional">
			<input type="hidden" name="id" value="{{license.id}}" /> 
			<input class="button" type="submit" name="save" value="Save" />
		</div>
	</form>
</script>