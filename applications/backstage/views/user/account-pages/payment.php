<h3>Payment Info</h3>
<strong>Does it make sense to present the user with text-boxes when they don't have the option to save this form?</strong>
<form action="" method="post" class="form">
    <dl class="form-item">
        <dt><label for="paypal_email">PayPal Email</label></dt>
        <dd><input type="text" class="textInput" name="paypal_email" id="paypal_email" value="{{target_user.paypal_email}}" /></dd>
    </dl>
    <dl class="form-item">
        <dt><label for="payable_to">Checks payable to</label></dt>
        <dd><input type="text" class="textInput" name="payable_to" id="payable_to" value="{{target_user.payable_to}}" /></dd>
    </dl>
	{{#if user.isAdmin}}
	<dl class="form-item">
        <dt><label for="split">Split</label></dt>
        <dd><input type="text" class="textInput" name="split" id="split" value="{{target_user.split}}" /></dd>
    </dl>
    <dl class="form-item">
        <dt></dt>
        <dd><input type="submit" class="button" value="Save" /></dd>
    </dl>
	{{/if}}
</form>
