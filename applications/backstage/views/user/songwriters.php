<script id="template-songwriters" type="text/template">

<?php $this->load->view('page-subnavigation'); ?>
<?php $this->load->view('pages/general-header'); ?>
<?php $this->load->view('assets/asset-nav'); ?>


<div class="tabs clearfix" id="songwritersTabs">
	<ul class="nav clearfix">
		<li><span class="anchor" data-href="#songwriterSearch"><span>Search</span></span></li>
		<li><span class="anchor" data-href="#addSongwriter"><span>Add Songwriter</span></span></li>
	</ul>
	<div id="songwriterSearch" class="tab clearfix">
		<div class="like-table">
		    <div class="like-header">
		        <div class="like-header-cell">Songwriter</div>
		        <div class="like-header-cell">Publisher</div>
		        <div class="like-header-cell">Songwriter PRO</div>
				<div class="like-header-cell">Publisher PRO</div>
		    </div>

		    <div class="songwritersList">

		    </div>

		    <div class="like-row row-add" id="add-songwriter-button">
		        <div class="like-cell">
		            <li id="songwritersnew-tab" class="tab like-link add">+ Add Songwriter</a>
		        </div>
		    </div>
		</div>
	</div>
	
	<div id="addSongwriter" class="tab clearfix">
		
		
		
	</div>
</div>
</script>

<script id="template-songwriters-row-display" type="text/template">
	<div class="like-row" id="songwriter-{{ songwriter.id }}">
		<input type="hidden" value="{{ songwriter.id }}" />
		<div class="like-cell" class="disp-songwriter-name">{{ name }}</div>
		<div class="like-cell" class="disp-songwriter-pro">{{ publisher.name }}</div>
		<div class="like-cell" class="disp-songwriter-publisher">{{ pro.name }}</div>
		<div class="like-cell" class="disp-publisher-pro">{{ publisher.pro.name }}</div>
		<div class="like-cell" class="disp-songwriter-edit">
			<a href="#" class="edit">Edit</a> 
		</div>
	</div>
</script>

<script id="template-songwriters-row-edit" type="text/template">
<div class="like-row">
	<form action="<?php echo base_url(); ?>songwriters/update" class="form-edit">
	
		<input type="hidden" name="field-songwriter-id" value="{{ id }}" class="field-songwriter-id" />
		<input type="hidden" name="field-songwriter-pro-id" value="{{ pro_id }}" class="field-songwriter-pro-id" />
		<input type="hidden" name="field-songwriter-publisher-id" value="{{ publisher_id }}" class="field-songwriter-publisher-id" />
		
		<div class="like-cell" id="songwriter-field">
			<input type="text" name="field-songwriter-name" class="field-songwriter-name" value='{{ name }}'>
		</div>
		
		<div class="like-cell" id="publisher-field">
			<select name="field-songwriter-publisher" class="field-songwriter-publisher">
				<option value="">Select Publisher</option>
				{{#all_publishers}}
					<option value="{{ id }}">{{ name }}</option>
				{{/all_publishers}}
			</select>
		</div>
		
		<div class="like-cell" id="pro-field">
			<select name="field-songwriter-pro" class="field-songwriter-pro">
				<option value="" >Select PRO</option>
				{{#all_pros}}
					<option value="{{ id }}" >{{ name }}</option>
				{{/all_pros}}
			</select>
		</div>
		
		<div class="like-cell" id="publisher-pro-field">
			<select name="field-songwriter-publisher-pro" class="field-songwriter-publisher-pro">
				<option value="">Select Publisher PRO</option>
				{{#all_pros}}
					<option value="{{ id }}" >{{ name }}</option>
				{{/all_pros}}
			</select>
		</div>
		
		<div class="like-cell" id="save-field">
			<input type="submit" value="Save" name="save-songwriter" class="like-submit" id="save-songwriter" />
		</div>
	</form>
	<div class="like-cell">
		<a class="cancel" href="#">Cancel</a>
	
		<form action="<?php echo base_url() ?>songwriters/delete/{{ id }}">
			<img src="<?php echo base_url()?>static/images/icon-delete-small.png" id="delete-songwriter-{{ id }}" class="delete like-link"/>
		</form>
	</div>
</div>
</script>