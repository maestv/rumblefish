<?php
class Pro_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
	public function get()
	{ // This is a dumb name for something that gets all.
		return $this->db->select("*")->from('pro')->get()->result();	
	}
	
	/**public function get_user($user_id=false){
		if ( !$user_id ) { return false; }
		return $this->db->select("*")->from('pro')->where(array("user_id" => $user_id))->get()->result();
	}**/
	
	public function get_pro($id = false)
	{
		if ( !$id || $id == 0) { return false; }
    $result = $this->db->get_where('pro', array('id' => $id))->row();
    return $result;
	}
    
    public function get_name($user_id){
        $this->db->select("name")->from('publishers');
        $this->db->where(array('user_id' => $user_id));

        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
	
	public function search($name)
	{
		$this->db->select("*")->from('pro');
		$this->db->like('name', $name);
		
		return $this->db->get()->result();
	}
	
	public function update($form = false)
	{
	    //die("Updating. " . print_r($form));
		if ( !$form ) { return false; }
        else { //die("We're not null.");
        }
        $data['id']   = $form['id'];
		$data['name'] = $form['name'];
        $data['user_id'] = $form['user_id'];

		$this->db->where('id', $form['id']);
		$result = $this->db->update('pro', $data);
        if ($result != 1)
            return Null;
        
        $updated = $this->db->get_where('pro', array('id' => $data['id']))->row();
        //die(print_r($updated));
        return $updated;
	}
	
	public function create($form)
	{
		// Check that the name does not all ready exists
		$check = $this->db->get_where('pro', array("name"=>$form['name']));
		if ( $check->num_rows > 0 ) {
			return false;
		}
		
		$data['name'] = $form['name'];
		$data['created'] = date("c", time());
		
		$this->db->insert('pro', $data); 
		return $this->db->get_where("pro", array("id"=>$this->db->insert_id()))->row();
	}
    
    public function delete($id){
        $this->db->query("DELETE FROM pro WHERE id='$id'");
    }
}
