<?php
class Search_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		
	}
	
	public function search($table, $criteria)
	{
		$this->db->select("*")->from($table);
		
		if ( !empty($criteria['date']['start']) ) {
			$this->db->where("created >=", date("c", strtotime($criteria['date']['start'])));
			unset($criteria['date']['start']);
		}
		
		if ( !empty($criteria['date']['end']) ) {
			$this->db->where("created <=", date("c", strtotime($criteria['date']['end'])));
			unset($criteria['date']['end']);
		}
		
		$query = $this->db->like($criteria)->get();
		return $query->result();
	}
}
