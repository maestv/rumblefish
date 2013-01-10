<?php
class Publishers_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		$this->load->model("pro_model");
	}
	
	public function get_publisher($publisher_id, $user_id = false)
	{
		if ( !$user_id ) { return false; }
		return $this->db->get_where('songwriters', array("user_id"=>$user_id, "publisher_id"=>$publisher_id))->result();
	}
    
    public function get_name($name, $user_id){
        $this->db->select("*")->from('publishers');
        $this->db->where(array('name' => $name, 'user_id' => $user_id));

        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
  
	public function get($id){
		$result = $this->db->get_where('publishers', array('id' => $id))->row();
		if ( !empty($result) ) {
			$result->pro = $this->pro_model->get_pro($result->pro_id);
			
			return $result;
		}

		return false;
	}
	
	public function get_all()
	{
		$pubs = $this->db->select("*")->from('publishers')->get()->result();
		// Add the pro name to each element
		foreach ($pubs as $p){
		    $p->pro = $this->pro_model->get_pro($p->pro_id);
		}
		return $pubs;
	}
	
	public function search($name)
	{
		$this->db->select("*")->from('publishers');
		$this->db->like('name', $name);
		
		return $this->db->get()->result();
	}
	
	public function update($form = false)
	{
		if ( !$form ) { return false; }
		
		$data['pro_id'] 	= $form['pro_id'];
		$data['user_id'] 	= $form['user_id'];
		$data['name'] 		= $form['name'];

		$this->db->where('id', $form['id']);
		return $this->db->update('publishers', $data);
	}
	
	public function create($form)
	{
		// Check that the name does not all ready exists
		$check = $this->db->get_where('publishers', array("name"=>$form['name']));
		if ( $check->num_rows > 0 ) {
			return false;
		}
		
		if ( !isset($form['user_id']) && trim($form['user_id']) != "" ) {
			$data['user_id'] = $this->data->user->id;
		} 
		else {
			$data['user_id'] = $form['user_id'];
		}
		
		if ( !empty($form['pro_id']) ) {
			$data['pro_id'] = $form['pro_id'];
		}
		
		$data['name'] = $form['name'];
		$data['created'] = date("c", time());
		
		$this->db->insert('publishers', $data); 
		return $this->db->get_where("publishers", array("id"=>$this->db->insert_id()))->row();
	}
    
    public function delete($id){
        $this->db->query("DELETE FROM publishers WHERE id='$id'");
    }
}
