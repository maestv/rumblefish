<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Core_Router extends CI_Router {

	function __construct()
	{
		parent::__construct();
	}
	
	// Forward a controller not found to /page/load
	public function _validate_request($segments)
	{
		// If controller dosent exist forward us to the pages controller
		if (!file_exists(APPPATH.'controllers/'.$segments[0].EXT)) {
			$segments = array("pages", "load", $segments);
		}
		
		return parent::_validate_request($segments);
	}
}