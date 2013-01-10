<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Modal Window!</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">

    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>static/js/vendor/html5shiv.js"></script>
    <![endif]-->

    <link rel="stylesheet"  href="<?php echo base_url(); ?>static/css/normalize.css"> <?php // We only need one Reset, if replacing please list why in commit messages ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>static/css/main.css">
</head>
<body>
<div id="wrapper">
    <header class="clearfix">
		<div id="logo">
       		<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>static/images/rumblefish-logo.png" /></a>
		</div>
    </header>

	<div id="container">
	    <section id="body">
	    	
			
			
	    </section>
	</div>
</div>

<?php
if ( Core_Controller::isAdmin() ) :
	
	$this->load->view('admin/documents-form');

endif;
if ( Core_Model::LoggedIn() ) :


endif;


?>

	<script src="<?php echo base_url(); ?>static/js/vendor/jquery-1.8.2.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/jquery.lightbox_me.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/underscore.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/backbone.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/handlebars.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/advanced-parser-rules.js"></script>
	<script src="<?php echo base_url(); ?>static/js/vendor/wysihtml5-0.3.0.min.js"></script>

	<script src="<?php echo base_url(); ?>static/js/plugins.js"></script>
	<script type="text/javascript">
	    var path = '<?php echo base_url(); ?>',
			debug = false; 
	</script>
	<script src="<?php echo base_url(); ?>static/js/main.js"></script>
</body>
</html>
