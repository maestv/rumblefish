<?php
class Album_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get($album_id = false)
	{
		if ( !$album_id ) { return false; }
		return $this->db->get_where('albums', array("id"=>$album_id, "user_id"=>$this->data->user->id))->row();
	}
	
	public function get_user($user_id = false)
	{
		if ( !$user_id ) { return false; }
		return $this->db->get_where('albums', array("user_id"=>$user_id))->result();
	}
	
	public function create($album = false)
	{
		if ( !$album ) { return false; }
		if ( empty($album['title']) || empty($album['published_date']) ) {
			return false;
		}
		
		$data['user_id'] = $this->data->user->id;
		if ( isset($album['user_id']) && trim($album['user_id']) != "" ) {
			$data['user_id'] = $album['user_id'];
		}
		
		// Check that we dont exsist. 
		$albumTest = $this->db->get_where('albums', array("user_id"=>$data['user_id'], "title"=>$album['title']));
		if ( $albumTest->num_rows > 0 ) { return false; }
		
		// Get data insert ready
		$data["title"] = $album['title'];
		$data["upc"] = $album['upc'];
		$data["record_label"] = $album['record_label'];
		$data["published_date"] = date("c", strtotime($album['published_date']));
		$data["cover_url"] = $album['cover_url'];
		$data["cover_filename"] = $album['cover_filename'];
		$data["cover_extension"] = $album['cover_extension'];
		$data["cover_width"] = $album['cover_width'];
		$data["cover_height"] = $album['cover_height'];
		$data["created"] = date("c");

		$this->db->insert('albums', $data); 
		return $this->db->get_where("albums", array("id"=>$this->db->insert_id()))->row();
	}
	
	public function update($album_id = false, $album = false)
	{
		if ( !$album || !$album_id ) { return false; }
		if ( empty($album['title']) || empty($album['published_date']) ) {
			return false;
		}
		
		// Get data insert ready
		$data["title"] = $album['title'];
		$data["upc"] = $album['upc'];
		$data["record_label"] = $album['record_label'];
		$data["published_date"] = date("c", strtotime($album['published_date']));
		$data["cover_url"] = $album['cover_url'];
		$data["cover_filename"] = $album['cover_filename'];
		$data["cover_extension"] = $album['cover_extension'];
		$data["cover_width"] = $album['cover_width'];
		$data["cover_height"] = $album['cover_height'];
		
		$this->db->where('id', $album_id);
		return $this->db->update('albums', $data);
	}
}
