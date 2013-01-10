<script id="template-license-details" type="template/text">

	<h1>{{pages.currentPage.title}}</h1>
	
	<section class="details">
		<ul>
			<li>Version: {{licence.version}}</li>
			<li>Licence Type: {{licence.licence_type}}</li>
			<li>Rights: {{licence.rights}}</li>
			<li>Purpose: {{licence.purpose}}</li>
			<li>Region: {{licence.region}}</li>
			<li>Term: {{licence.term}}</li>
			<li>Excerpt: {{licence.short_description}}</li>
			<li>Description: {{licence.long_description}}</li>
			<li>Download: {{licence.download}}</li>
		</ul>
		
		<h2>License Verbage</h2>
		<section id="license_text">
			{{{licence.text}}}
		</section>
	</section>

</script>