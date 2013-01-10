<?php
class Payee_Model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get($id = false) {
		if ( !$id ) return false;
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/payee", "GET", array("id"=>$id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_all() {	
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/payee", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
}
