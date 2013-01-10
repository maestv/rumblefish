<script id="template-assets-manage" type="text/template">
	<h2>{{page.current.title}}</h2>
	<div class="content">
		{{{page.current.page_content}}}
	</div>
	
	<div class="sub-header">
		{{#if page.sub_navagation}}
		<aside class="subnav">
			<ul>
			{{#page.sub_navagation}}
				<li><a href="{{url}}">{{title}}</a></li>
			{{/page.sub_navagation}}
			</ul>
		</aside>
		{{/if}}
	</div>
	
	
	<section class="asset-list">
		
	</section>
	
	
</script>