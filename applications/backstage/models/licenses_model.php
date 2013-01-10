<?php
class Licenses_Model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get($id = false)
	{
		if ( !$id ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/license_admin", "GET", array("id"=>$id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_all()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/license_admin", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_where($where = array())
	{
		if ( !is_array($where) || empty($where) ) { return false; }
		return $this->db->get_where("licenses", $where)->result();
	}
	
	public function update($form = array())
	{
		if ( empty($form) ) { return false; }
		
		if ( isset($form['_wysihtml5_mode']) ) { unset($form['_wysihtml5_mode']); }
		if ( isset($form['version']) ) {
			$form['license_type_version'] = (int) $form['version'];
			//$form['version'] = (int) $form['version'];
			unset($form['version']);
		}
		
		print_r($form);
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/license_admin?token=".$this->data->token, "PUT", $form);
		$api->execute();
		
		var_dump($api);
		
		return json_decode( $api->getResponseBody() );
	}
	
	public function create($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return false; }
		
		if ( isset($form['version']) ) {
			$form['license_type_version'] = (int) $form['version'];
			$form['version'] = (int) $form['version'];
			//unset($form['version']);
		}
		
		if ( isset($form['download']) ) { $form['download'] = (bool) $form['download']; }
		if ( isset($form['_wysihtml5_mode']) ) { unset($form['_wysihtml5_mode']); }
		
		// API Demands Int!
		if ( isset($form['license_type']) ) { $form['license_type'] = (int) $form['license_type']; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/license_admin", "POST", $form, $this->data->token);
		$api->execute();
		
		return json_decode( $api->getResponseBody() );
	}
}
