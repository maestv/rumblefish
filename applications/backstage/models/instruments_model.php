<?php
class Instruments_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get_all()
	{
		return $this->db->select("*")->from('instruments')->get()->result();	
	}
	
	public function get_asset($asset_id = false)
	{
		if ( !$asset_id ) { return false; }
		return $this->translate_associated($this->db->get_where("associate_instruments", array("asset_id"=>$asset_id))->result());
	}
	
	public function associate_to_asset($asset_id = false, $instruments = array())
	{
		$out = new stdClass();
			$out->error = false;
		
		if ( !$asset_id ) {
			$out->error = true;
			$out->error_message = "Associate ID missing.";
		}
		if ( !is_array($instruments) || empty($instruments) ) {
			$out->error = true;
			$out->error_message = "Instruments not an array.";
		}
		
		// remove anything that is pre-exsisting
		$this->db->delete("associate_instruments", array("asset_id"=>$asset_id));
		
		$insert = array();
		foreach ( $instruments as $k=>$t ) {
			$insert[$k]["instruments_id"] = $t;
			$insert[$k]["asset_id"] = $asset_id;
		}
		
		if ( $out->error == true ) { return $out; }
		return $this->db->insert_batch('associate_instruments', $insert); 
	}
	
	public function create($form)
	{
		// Check that the name does not all ready exists
		$check = $this->db->get_where('instruments', array("name"=>$form['name']));
		if ( $check->num_rows > 0 ) {
			return $check->row();
		}
	
		$data['name'] = strtolower($form['name']);
		
		$this->db->insert('instruments', $data); 
		return $this->db->get_where("instruments", array("id"=>$this->db->insert_id()))->row();
	}
	
	
	private function translate_associated($ass = array())
	{
		foreach ( $ass as &$a ) {
			$a = $this->db->get_where("instruments", array("id"=>$a->instrument_id))->row();
		} 
		
		return $ass;
	}
}
