<script id="template-user-dashboard" type="text/template">
	<section id="userDashboard" class="">
		<h2>Welcome to Backstage, {{user.full_name}}</h2>
		
		<div>
			<h3>Account Messages</h3>
		</div>
		
		<div>
			<h3>Rumblefish Artists News</h3>
		</div>
		

		<a class="button" href="<?php echo base_url(); ?>/assets/createtrack">Add Tracks</a>
		<a class="button" href="<?php echo base_url(); ?>/assets/createalbum">Add Albums</a>
	</section>
</script>
