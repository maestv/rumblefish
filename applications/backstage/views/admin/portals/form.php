<script id="template-portal-form" type="text/template">
	<h1>Portal Form</h1>
	<div id="reasons-warning">
	    <p>Could not create a new portal because:</p>
	    <ol id='reasons-warning-ol' type='1'>
	        
	    </ol>
	</div>
	<form class="newPortal" action="" method="post">
		<dl>
			<dt><label for="public_key">Public Key</label></dt>
			<dd><input type="text" class="textInput" id="portalName" name="public_key" value="{{portal.public_key}}" placeholder="" /></dd>
		
			<dt><label for="password">Password</label></dt>
			<dd><input type="password" class="textInput" id="portalPassword" name="password" value="" placeholder="" /></dd>
			
			<dt><label for="logo">Portal Logo</label></dt>
			<dd>
				<input type="file" class="fileInput" id="portalLogo" name="file" />
				{{#if portal.logo_url}}
					<img src="{{portal.logo_url}}" alt="" />
				{{/if}}
			</dd>
			
			<dt><label for="receipt_header">Receipt Header</label></dt>
			<dd>
				<div class="edit-full">
					<?php $this->load->view("rte-toolbar", array("id"=>"receipt_header_toolbar")); ?>
					<textarea id="receipt_header" class="rte" name="receipt_header">{{portal.receipt_header_text}}</textarea>
				</div>
			</dd>
		
			<dt><label for="receipt_footer">Receipt Footer</label></dt>
			<dd>
				<div class="edit-full">
					<?php $this->load->view("rte-toolbar", array("id"=>"receipt_footer_toolbar")); ?>
					<textarea id="receipt_footer" class="rte" name="receipt_footer">{{portal.receipt_footer_text}}</textarea>
				</div>
			</dd>
		
			<dt><label for="billing_name">Billing Name</label></dt>
			<dd><input type="text" class="textInput" id="billing_name" name="billing_name" value="{{portal.billing_name}}" placeholder="" /></dd>
			
			<dt><label for="billing_email">Billing Email</label></dt>
			<dd><input type="text" class="textInput" id="billing_email" name="billing_email" value="{{portal.billing_email}}" placeholder="" /></dd>
			
			<dt><label for="billing_phone">Billing Phone</label></dt>
			<dd><input type="text" class="textInput" id="billing_phone" name="billing_phone" value="{{portal.billing_phone}}" placeholder="" /></dd>
			
			<dt><label for="billing_address1">Billing Address 1</label></dt>
			<dd><input type="text" class="textInput" id="billing_address1" name="billing_address1" value="{{portal.billing_address1}}" placeholder="" /></dd>
			
			<dt><label for="billing_address2">Billing Address 2</label></dt>
			<dd><input type="text" class="textInput" id="billing_address2" name="billing_address2" value="{{portal.billing_address2}}" placeholder="" /></dd>
			
			<dt><label for="billing_city">Billing City</label></dt>
			<dd><input type="text" class="textInput" id="billing_city" name="billing_city" value="{{portal.billing_city}}" placeholder="" /></dd>
			
			<dt><label for="billing_state">Billing State</label></dt>
			<dd><input type="text" class="textInput" id="billing_state" name="billing_state" value="{{portal.billing_state}}" placeholder="" /></dd>
			
			<dt><label for="billing_country">Billing Country</label></dt>
			<dd><input type="text" class="textInput" id="billing_country" name="billing_country" value="{{portal.billing_country}}" placeholder="" /></dd>
			
			<dt><label for="billing_postal">Billing Zip</label></dt>
			<dd><input type="text" class="textInput" id="billing_postal" name="billing_postal" value="{{portal.billing_postal}}" placeholder="" /></dd>
			
			<dt><label for="contact_name">Contact Name</label></dt>
			<dd><input type="text" class="textInput" id="contact_name" name="contact_name" value="{{portal.contact_name}}" placeholder="" /></dd>
			
			<dt><label for="contact_email">Contact Email</label></dt>
			<dd><input type="text" class="textInput" id="contact_email" name="contact_email" value="{{portal.contact_email}}" placeholder="" /></dd>
			
			<dt><label for="contact_phone">Contact Phone</label></dt>
			<dd><input type="text" class="textInput" id="contact_phone" name="contact_phone" value="{{portal.contact_phone}}" placeholder="" /></dd>
			
			<dt><label for="receipt_value">Recipt Value {{portal.receipt_value}}</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="receipt_value" name="receipt_value" value="true"{{#if portal.receipt_value}} checked="checked"{{/if}} /></dd>
			
			<dt><label for="receipt_subject">Recipt Subject</label></dt>
			<dd><input type="text" class="textInput" id="receipt_subject" name="receipt_subject" value="{{portal.receipt_subject}}" placeholder="" /></dd>
			
			<dt><label for="invoicing">Invoicing {{portal.invoicing}}</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="invoicing" name="invoicing" value="true"{{#if portal.invoicing}} checked="checked"{{/if}} /></dd>
			
			<dt><label for="rate_card">Rate Card</label></dt>
			<dd><textarea class="textareaInput" id="rate_card" name="rate_card">{{portal.rate_card}}</textarea></dd>
			
			<dt><label for="rate_card_description">Rate Card Description</label></dt>
			<dd><textarea class="textareaInput" id="rate_card_description" name="rate_card_description">{{portal.rate_card_description}}</textarea></dd>
			
			<dt><label for="admin">Admin {{portal.admin}}</label></dt>
			<dd><input type="checkbox" class="checkboxInput" id="admin" name="admin" value="true"{{#if portal.admin}} checked="checked"{{/if}} /></dd>
				
		</dl>
		<div class="form-functional">
			<input class="button" type="submit" name="save" value="Save" />
		</div>
	</form>
</script>