<div class="edit-left">
	
	<div class="formRow">
		<label for="display">Display In Navigation</label>
		<input class="textInput" type="checkbox" id="display" name="display" value="1" {{#if page.display}}checked="checked"{{/if}} />
	</div>
	
	<div class="formRow">
		<label for="show_children">Display Children</label>
		<input class="textInput" type="checkbox" name="meta[display_child_nav]" id="show_children" value="1" {{#if page.meta.display_child_nav}}checked="checked"{{/if}} />
	</div>
	<div class="formRow">
		<label for="forward">Forward to</label>
		<input class="textInput" type="text" name="forward" id="forward" value="{{page.forward}}" />
	</div>
	
</div>
<div class="edit-right">
	
</div>