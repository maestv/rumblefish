<script id="template-first-login-1" type="text/template">
<h2>Welcome to Backstage, {{target_user.full_name}}</h2>
{{{page.current.page_content}}}

<?php $this->load->view('user/account-pages/contacts'); ?>
</script>

<script id="template-first-login-2" type="text/template">
<h2>Welcome to Backstage, {{target_user.full_name}}</h2>
{{{page.current.page_content}}}

<h3>Payment Info</h3>

<form action="" method="post" class="form">
    <dl class="form-item">
        <dt><label for="paypal_email">PayPal Email</label></dt>
        <dd><input type="text" class="textInput" name="paypal_email" id="paypal_email" value="{{target_user.paypal_email}}" /></dd>
    </dl>
    <dl class="form-item">
        <dt><label for="payable_to">Checks payable to</label></dt>
        <dd><input type="text" class="textInput" name="payable_to" id="payable_to" value="{{target_user.payable_to}}" /></dd>
    </dl>
    <dl class="form-item">
        <dt></dt>
        <dd><input type="submit" class="button" value="Save" /></dd>
    </dl>
</form>

</script>