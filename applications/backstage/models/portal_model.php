<?php
class Portal_Model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get($id)
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal", "GET", array("id"=>$id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	
	public function get_all()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_where($where = array())
	{
		if ( !is_array($where) || empty($where) ) { return false; }
		return $this->db->get_where("portals", $where)->result();
	}
	
	public function get_licenses($portal_id = false) {
		if ( !$portal_id || !is_numeric($portal_id) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_license", "GET", array("portal_id"=>$portal_id), $this->data->token);
		$api->execute();

		return json_decode($api->getResponseBody());
	}
	
	public function add_license($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_license", "POST", $form, $this->data->token);
		$api->execute();

		return json_decode($api->getResponseBody());	
	}
	
	public function update_license($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return; }
		unset($form['license_id']);
		
		$str = http_build_query($form)."&token=".$this->data->token;

		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_license?".$str, "PUT", null);
		$api->execute();

		return json_decode($api->getResponseBody());	
	}
	
	public function removelicense($id = false) {
		if ( !$id || !is_numeric($id) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_license?token=".$this->data->token."&id=".$id, "DELETE", null);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function catalogs($portal_id = false) {
		if ( !$portal_id || !is_numeric($portal_id) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_catalog", "GET", array("portal_id"=>$portal_id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function add_catalog($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_catalog", "POST", $form, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function removecatalog($portal_catalog_id = false)
	{
		if ( !$portal_catalog_id || !is_numeric($portal_catalog_id) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal_catalog?token=".$this->data->token."&id=".$portal_catalog_id, "DELETE");
		$api->execute();

		return json_decode($api->getResponseBody());
	}
	
	public function create($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return false; }
		if ( empty($form['public_key']) || empty($form['password']) ) { return false; }
		
		foreach ( $form as $key=>$value ) {
			if ( !isset($value) || trim($value) == "" ) { // false things are cool.
				unset($form[$key]);
			}
		}
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal", "POST", $form, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function update($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return false; }
		if ( empty($form['id']) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/portal", "PUT", $form, $this->data->token);
		$api->execute();
		
		return $api->getResponseBody();
	}
}
