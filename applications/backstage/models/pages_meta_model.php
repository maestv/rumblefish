<?php
class Pages_Meta_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function getPage($page_id = false, $decode = true)
	{
		if ( $page_id == false ) { return false; }
		
		$meta = $this->db->get_where('pages_meta', array("page_id"=>$page_id))->result();
		
		if ( !empty($meta) ) {
			$page_meta = array();
			foreach ( $meta as $r ) {
				$page_meta[$r->keyword] = ($decode)? json_decode($r->data) : $r->data;
			}
		}
	
		return (object) $page_meta;
	}
	
	public function update($page_id = false, $meta = array())
	{
		if ( $page_id == false ) { return false; }
		
		// Remove all of the old Meta (delete all base on page id)
		// Possible to add a revision ID down the road and only do inserts
		$this->db->where('page_id', $page_id);
		$this->db->delete('pages_meta');
		
		if ( !empty($meta) ) {
			$meta_insert = array();
			$c = 0;
			foreach ( $meta as $keyword=>$blob )
			{
				$meta_insert[$c]["page_id"] = $page_id;
				$meta_insert[$c]["keyword"] = $keyword;
				$meta_insert[$c]["data"] = json_encode($blob);
				
				$c++;
			}
		}
		
		return $this->db->insert_batch('pages_meta', $meta_insert); 
	}
}