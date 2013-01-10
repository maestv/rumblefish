<?php
class Tags_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get_all()
	{
		return $this->db->select("*")->from('tags')->get()->result();	
	}
	
	public function get_type($foreign_id = false, $type = false)
	{
		if ( !$type || !$foreign_id ) { return array(); }
		return $this->translate_associated($this->db->get_where('associate_tags', array("foreign_id"=>$foreign_id, "type"=>$type))->result());
	}
	
	public function associate_to_asset($foreign_id = false, $tags = array(), $type = false)
	{
		$out = new stdClass();
			$out->error = false;
		
		if ( !$foreign_id ) {
			$out->error = true;
			$out->error_message = "Associate ID missing.";
		}
		if ( !is_array($tags) || empty($tags) ) {
			$out->error = true;
			$out->error_message = "Tags not an array.";
		}
		if ( !$type ) {
			$out->error = true;
			$out->error_message = "Type not set.";
		}
		
		// remove anything that is pre-exsisting
		$this->db->delete("associate_tags", array("type"=>$type, "foreign_id"=>$foreign_id));

		$insert = array();
		foreach ( $tags as $k=>$t ) {
			$insert[$k]['type'] = $type;
			$insert[$k]["tag_id"] = $t;
			$insert[$k]["foreign_id"] = $foreign_id;
		}
		
		if ( $out->error == true ) { return $out; }
		return $this->db->insert_batch('associate_tags', $insert); 
	}

	public function update($form = false)
	{
		if ( !$form ) { return false; }
		
		$data['tag'] = $form['tag'];

		$this->db->where('id', $form['id']);
		return $this->db->update('tags', $data);
	}
	
	public function create($form)
	{
		// Check that the name does not all ready exists
		$check = $this->db->get_where('tags', array("tag"=>$form['tag']));
		if ( $check->num_rows > 0 ) {
			return $check->row();
		}
		
		if ( !isset($form['created_by']) || trim($form['created_by']) == "" ) {
			$data['created_by'] = $this->data->user->id;
		} 
		else {
			$data['created_by'] = $form['created_by'];
		}
		
		$data['tag'] = strtolower($form['tag']);
		$data['created'] = date("c", time());
		
		$this->db->insert('tags', $data); 
		return $this->db->get_where("tags", array("id"=>$this->db->insert_id()))->row();
	}
	
	
	private function translate_associated($ass = array())
	{
		foreach ( $ass as &$a ) {
			$a = $this->db->get_where("tags", array("id"=>$a->tag_id))->row();
		} 
		
		return $ass;
	}
}
