<script id="template-user-search" type="text/template">
	<div class="tabs clearfix" id="userTabs">
	    <ul class="nav clearfix">
		    <li><span class="anchor" data-href="#searchAccounts"><span>Search Accounts</span></span></li>
			<li><span class="anchor" data-href="#addAccount"><span>Add Account</span></span></li>
		</ul>
		<div id="searchAccounts" class="tab clearfix">
			<?php $this->load->view('admin/users/search'); ?>
		</div>
		<div id="addAccount" class="tab clearfix">
			<?php $this->load->view('admin/users/form'); ?>
		</div>
	</div>
</script>
