<div class="edit-left">
	
	<div class="formRow">
		<label for="meta_title">Meta Title</label>
		<input class="textInput" type="text" name="meta_title" value="{{ page.meta_title }}" />
	</div>
	
	<div class="formRow">
		<label for="meta_keywords">Meta Keywords</label>
		<input class="textInput" type="text" name="meta_keywords" value="{{ page.meta_keywords }}" />
	</div>
	
	<div class="formRow">
		<label for="meta_description">Meta Description</label>
		<textarea name="meta_description">{{ page.meta_description }}</textarea>
	</div>
	
</div>
<div class="edit-right">
	
	<div class="formRow">
		<label for="custom_head_content">Custom Header Content</label>
		<textarea name="custom_head_content">{{ page.custom_head_content }}</textarea>
	</div>	
	
</div>