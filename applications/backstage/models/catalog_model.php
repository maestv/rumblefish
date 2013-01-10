<?php
class Catalog_Model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	############################################################
	## Having Jeff look into some bugs with GET requests
	## This will be on hold till he gets back
	## CB 12/27/2012
	############################################################
	public function get($catalog_id = false)
	{
		if ( !$catalog_id || !is_numeric($catalog_id) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_admin", "GET", array("id"=>$catalog_id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_all()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_admin", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	public function get_where($where = array())
	{
		if ( !is_array($where) || empty($where) ) { return false; }
		return $this->db->get_where("catalogs", $where)->result();
	}
	
	public function getProviders()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_provider", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
	
	/*	Throwing 500 error */
	public function getLicenses()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_license", "GET", null, $this->data->token);
		$api->execute();

		return json_decode($api->getResponseBody());
	}
	
	public function removelicense($id)
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_license?token=".$this->data->token."&id=".$id, "DELETE", null);
		$api->execute();

		return json_decode($api->getResponseBody());
	}
	
	public function addlicense($form = array())
	{
		if ( empty($form) ) { return false; }
		
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_license?", "POST", $form, $this->data->token);
		$api->execute();

		return json_decode($api->getResponseBody());
	}
	
	public function update($form)
	{	
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/catalog_admin?token=".$this->data->token, "PUT", $form);
		$api->execute();
		
		print_r($api);
		
		return json_decode($api->getResponseBody());
	}
	
	public function create($form = array())
	{
		if ( !is_array($form) || empty($form) ) { return false; }
		
		if ( !isset($form['user_id']) ) {
			$data['created_by'] = $this->data->user->id;
		} else {
			$data['created_by'] = $form['user_id'];
		}
		
		$data['name'] = $form['name'];
		$data['created'] = date("c");
		
		$this->db->insert('catalogs', $data); 
		return $this->db->get_where("catalogs", array("id"=>$this->db->insert_id()))->row();
	}
	
	public function countries()
	{
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/country", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}
}
