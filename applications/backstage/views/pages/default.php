<script id="template-pages-default" type="text/template">
<?php // $this->load->view('page-subnavigation'); ?>
<h2>{{page.current.title}}</h2>
<small>Default Page Controller. Check out $this->data->page</small>

<div id="page_content">
{{{page.current.page_content}}}
</div>
</script>