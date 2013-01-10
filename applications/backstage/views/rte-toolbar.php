<?php  // Pass me an ID dawg. -> $this->load->view("rte-toolbar", array("id"=>"page_content_toolbar")); ?>
<div id="<?php echo $id; ?>" class="rte_toolbar clearfix" style="display: none;">
	<nav>
		<ul>
			<li><a data-wysihtml5-command="bold" title="CTRL+B">bold</a></li>
			<li><a data-wysihtml5-command="italic" title="CTRL+I">italic</a></li>
			<li><a data-wysihtml5-command="createLink">insert link</a></li>
			<li><a data-wysihtml5-command="insertImage">insert image</a></li>
			<li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1">h1</a></li>
			<li><a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2">h2</a></li>
			<li><a data-wysihtml5-command="insertUnorderedList">UL</a></li>
			<li><a data-wysihtml5-command="insertOrderedList">OL</a></li>
			<li>
				<div>Font Colors</div>
				<ul>
					<li><a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red">red</a></li>
					<li><a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green">green</a></li>
					<li><a data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue">blue</a> </li>
				</ul>
			</li>
			<li><a data-wysihtml5-command="undo">undo</a></li>
			<li><a data-wysihtml5-action="change_view">switch to html view</a></li>
		</ul>
	</nav>
	
	<div data-wysihtml5-dialog="createLink" style="display: none;">
		<label>
		Link:
		<input data-wysihtml5-dialog-field="href" value="http://"></label>
		<a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
	</div>
    
	<div data-wysihtml5-dialog="insertImage" style="display: none;">
		<label>Image:<input data-wysihtml5-dialog-field="src" value="http://"></label>
		<label>
			Align:
			<select data-wysihtml5-dialog-field="className">
				<option value="">default</option>
				<option value="wysiwyg-float-left">left</option>
				<option value="wysiwyg-float-right">right</option>
			</select>
		</label>
		<a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
	</div>
</div>
