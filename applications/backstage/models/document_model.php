<?php
class Document_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		
	}
	
	// Try and run all internal functions though get. (we we have a consistant data model)
	public function get($id = false) 
	{
		if ( !$id ) { return false; }
		
		$document = $this->db->get_where('documents', array("id"=>$id, 'active'=>1))->row();
		// Attach anyhing you want here
		$document->uploaded = date("m/d/Y", strtotime($document->uploaded));
		
		return $document;
	}
	
	public function get_where($array = array())
	{
		if ( empty($array) ) return false;
		
		$documents = $this->db->select("id")->where($array)->get("documents")->result();
		if ( empty($documents) ) { return false; }
		
		foreach ( $documents as &$document ) {
			$document = $this->get($document->id);
		}
		
		return $documents;
	}
	
	public function get_user($user_id = false)
	{
		if ( !$user_id ) return false;
		return $this->get_where(array("user_id"=>$user_id));
	}
	
	public function getAll() 
	{
		if ( $this->data->user->type == "admin" ) {
			return $this-get_where(array("id"=>"NOT NULL"));
		} else {
			return $this->get_user($this->data->user->type);
		}
	}
	
	public function create($form = array(), $user_id = false)
	{
		// Check for needed things
		if ( empty($form) ) return false;
		if ( empty($form['user_id']) && !$user_id ) return false;
 		
		// Set the USER_ID to passed unless in form
		if ( !empty($form['user_id']) ) $user_id = $form['user_id'];

		// prepare the rest of the data.
		$data['user_id'] = $user_id;
		$data['document_name'] = $form['document_name'];
		$data['document_url'] = $form['document_url'];
		$data['document_path'] = $form['document_path'];
		$data['uploaded'] = date("c");
		$data['created_by'] = $this->data->user->id;
		
		// Hammertime
		$this->db->insert('documents', $data); 
		return $this->get($this->db->insert_id());
	}
	
	public function remove($document_id = false)
	{
		if ( !$document_id ) return false;
		
		$update = array('active' => 0);
    	$where = array('id' => $document_id);

		return $this->db->update('documents', $update, $where);
	}
}
