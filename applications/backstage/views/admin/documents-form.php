<script id="template-admin-documents-add" type="text/template">
<section class="add-documents">
	<h2>Add A Document</h2>
	<form action="" method="post">
		<dl>
			<dt>Document:</dt>
			<dd><input type="file" id="document" class="fileInput" name="file" value="" /></dd>
			
			<dt></dt>
			<dd>
				<input type="hidden" name="user_id" value="{{target_user}}" />
				<input type="submit" value="Save" />
			</dd>
		</dl>
	</form>
</section>
</script>
