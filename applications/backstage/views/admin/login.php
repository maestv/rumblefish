<script id="template-login-page" type="text/template">
<section id="loginForm">
	<form action="<?php echo base_url(); ?>user/login" method="post" class="form">
		<dl class="form-item">
			<dt><label for="user_name">Username</label></dt>
			<dd><input type="text" name="user_name" id="user_name"  /></dd>

			<dt><label for="password">Password</label></dt>
			<dd><input type="password" name="password" id="password" class="form-item" /></dd>

			<dt></dt>
			<dd><input type="submit" name="submit" class="submit" id="submit" value="Go" /></dd>
		</dl>
		<input type="hidden" name="redirect" value="{{ form_redirect }}" />
	</form>

	<p><a id="reset-password-button" href="#">Forgot username or password?</a></p>
	<p>Interested in promoting your music with Rumblefish? <a href="<?php echo base_url(); ?>contact-us">Contact Us</a></p>
	
</section>
</script>
<script id="template-user-email-password" type="text/template">
        <form action="<?php echo base_url(); ?>user/emailpassword">
            <dl class="form-item">
                <dt><label for="reset-email">Email address or username:</label></dt>
                <dd><input type="text" name="reset-email" id="reset-email" /></dd>
            <dl class="form-item">
                <dt><input name="submit" class="submit" id="submit" type="submit" value="Email Me My Password" /></dt>
                <dd></dd>
            </dl>
        </form>
</script>
