<?php
class Notes_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get($id = false)
	{
		if ( $id === false ) { return false; }
		return $this->db->get_where("account_notes", array("id"=>$id))->row();
	}
	
	public function get_user($user_id = false)
	{
		if ( $user_id === false ) { return false; } 
		return $this->db->get_where("account_notes", array("user_id"=>$user_id))->result();	
	}
	
	public function get_where($where = array()) {
		
		$this->notes = $this->db->get_where("account_notes", $where);
		
		if ( $this->notes->num_rows == 1 ) {
			$this->notes = $this->notes->row();
		} else {
			$this->notes = $this->notes->result();
		}
		
		return $this->notes;
	}
	
	public function addNote($note)
	{ 
		if ( !isset($note['user_id']) || !isset($note['notes']) )
		{
			return false;
		}
		
		$note['note_taker'] = $this->data->user->id;
		$note['created'] = date("c", time());
		
		return $this->db->insert('account_notes', $note);	
	}
}