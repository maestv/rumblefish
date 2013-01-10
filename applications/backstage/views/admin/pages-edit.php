<?php 
/*
	Adding Data to a page that is surplus goes into meta like:
	<input name="meta[keyword]" value="array, blob, single value" />
	
	Anything in the value will be converted to JSON and stuck into the
	page_meta table filed under page_id and keyword. Check out the example
	in the views/admin/pages/general.html file
*/ 
?>
<script id="template-admin-page-edit" type="text/template">
<section class="page-edit">
	<form action="{{ submit_url }}/{{ page_id }}" name="page" method="post">
		<div class="tabs clearfix" id="editPageTabs">
			<ul class="nav clearfix">
				<li><span class="anchor" data-href="#pageGeneral"><span>General</span></span></li>
				<li><span class="anchor" data-href="#pageContent"><span>Content</span></span></li>
				<li><span class="anchor" data-href="#pageForms"><span>Forms</span></span></li>
				<li><span class="anchor" data-href="#pageSettings"><span>Settings</span></span></li>
			</ul>
			<div id="pageGeneral" class="tab clearfix">
				<?php $this->load->view('admin/pages/general'); ?>
			</div>
			<div id="pageContent" class="tab clearfix">
				<?php $this->load->view('admin/pages/content'); ?>
			</div>
			<div id="pageForms" class="tab clearfix">
				<?php $this->load->view('admin/pages/forms'); ?>
			</div>
			
			<div id="pageSettings" class="tab clearfix">
				<?php $this->load->view('admin/pages/settings'); ?>
			</div>
		</div>
		
		<p class="clearfix">&nbsp;</p>
	
		<div class="form-functional">
			<div class="edit-comments">
				<label for="comments">Comments:</label>
				<textarea name="comments">{{ page.comments }}</textarea>
			</div>
			<input class="button" type="submit" name="save" value="Save" />
			<input class="button" type="submit" name="cancel" value="Cancel" />
		</div>
	</form>
</section>
</script>
