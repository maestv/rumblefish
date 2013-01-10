<div class="edit-left">
	<div class="formRow">
		<label for="title">Page Title</label>
		<input class="textInput" type="text" name="title" value="{{ page.title }}" />
	</div>
	
	<div class="formRow">
		<label for="alias">Alias</label>
		<input class="textInput" type="text" name="alias" value="{{ page.alias }}" />
	</div>
	
	<div class="formRow">
		<label for="meta[page_header]">Page Header</label>
		<input class="textInput" type="text" name="meta[page_header]" value="{{ page.meta.page_header }}" />
	</div>
</div>

<div class="edit-right">
	<div class="formRow">
		<label for="status">Page Status</label>
		<select id="pageStatus" name="status" data-set="{{page.status}}">
		</select>
	</div>
	
	<div class="formRow">
		<label for="alias">Page Parent</label>
		<select name="parent_id">
			<option value="0">None</option>
			{{{parent_options}}}
		</select>
	</div>
	
	<div class="formRow">
		<label for="sort_order">Sort Order</label>
		<input class="textInput" type="text" name="sort_order" value="{{ page.sort_order }}" />
	</div>
</div>