<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Error extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
	}
	
	public function error_404()
	{
		header("HTTP/1.1 404 Not Found");
		echo 'Error 404';
		die;
	}
}
