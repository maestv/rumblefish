<?php
class Songwriters_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
    $this->load->model("pro_model");
    $this->load->model("publishers_model");
	}
	
	public function get_user($user_id = false)
	{
		if ( !$user_id ) { return false; }
		$songwriters = $this->db->select("*")->from('songwriters')->where(array("user_id" => $user_id))->get()->result();
		
		foreach ($songwriters as &$songwriter) {
			$songwriter->publisher = $this->publishers_model->get($songwriter->publisher_id);
			$songwriter->pro = $this->pro_model->get_pro($songwriter->pro_id);
		}
		
		return $songwriters;
	}
    
    public function get_attr($id,$attr){
        $result = $this->db->select($attr)->from('songwriter')->where(array('id' => $id))->get()->row();
        die("<h1>Result = " . print_r($result) . "</h1>");
        return $result[0];
    }
	
	public function get_all()
	{
		$songwriters = $this->db->select("*")->from('songwriters')->get()->result();
		$all_data = array();
		$sz = count($songwriters);
		for ($i = 0; $i < $sz; ++$i){
		  $id_s = $songwriters[$i]->id;
		  $publisher = $this->get_songwriter_publisher($id_s);
		  $pro = $this->get_songwriter_pro($id_s);
		  $all_data[$i]["songwriter"] = $songwriters[$i];
		  $all_data[$i]["publisher"] = $publisher;
		  $all_data[$i]["pro"] = $pro;
		}
		return $all_data;
	}
  
  public function get($id){
      $songwriter = $this->db->select("*")->from('songwriters')->where(array('id' => $id))->get()->row();
      $pro = $this->pro_model->get_pro($songwriter->pro_id);
      $publisher = $this->publishers_model->get($songwriter->publisher_id);
      $all_data = array();
      /**echo "Songwriter: " . $songwriter->id . "\n";
      echo "PRO: " . $songwriter->pro_id . "\n";
      echo "Publisher: " . $songwriter->publisher_id . "\n";
      echo "Publisher (name): " . $publisher->name . "\n";
      echo "PRO(name): " . $pro->name . "\n";**/
      $all_data["songwriter"] = $songwriter;
      $all_data["pro"] = $pro;
      $all_data["publisher"] = $publisher;
      return $all_data;
  }
	
	public function get_songwriter($songwriter_id, $user_id = false){
	    return $this->db->get_where("songwriters", array("id"=>$songwriter_id))->row();
	}
	
	public function get_songwriters_publishers(){
	  $publishers_list = array();
	  $songwriters = $this->get_all();
	  foreach ($songwriters as $s){
	    array_push($publishers_list, get_songwriter_publisher($s["id"]));
	  }
	  return $publishers_list;
	}
	
	public function get_name($name, $user_id)
	{
		$this->db->select("*")->from('songwriters');
		$this->db->where(array('name' => $name, 'user_id' => $user_id));

        $result = $this->db->get()->result();
        if (!empty($result)) {
            return $result[0];
        }
        return null;
	}
	
	public function get_songwriter_publisher($songwriter_id){
	    $pub_id = $this->get_publisher_id($songwriter_id);
	    return $this->db->select("*")->from("publishers")->where(array("id" => $pub_id))->get()->row();
	}
	
	public function get_songwriter_publisher_name($songwriter_id){
	  $pub = get_publisher($songwriter_id);
	  return $pub["name"];
	}
	
	public function get_songwriter_pro($songwriter_id){
	  $pro_id = $this->get_pro_id($songwriter_id);
	  $where_clause = array("id" => $pro_id);
	  return $this->db->select("*")->from("pro")->where($where_clause)->get()->row();
	}
	
	public function get_asset($asset_id = false)
	{
		if ( !$asset_id ) { return false; }
		return $this->translate_associated($this->db->get_where("songwriters_assets", array("asset_id"=>$asset_id))->result());
	}
	
	public function get_publisher_id($songwriter_id){
	  $res = $this->db->select("publisher_id")->from("songwriters")->where(array("id" => $songwriter_id))->get()->row();
	  return $res->publisher_id;
	}
	
	public function get_pro_id($songwriter_id){
	  $res = $this->db->select("pro_id")->from("songwriters")->where(array("id" => $songwriter_id))->get()->row();
	  return $res->pro_id;
	}

	public function search($name)
	{
		$this->db->select("*")->from('songwriters');
		$this->db->like('name', $name);

		return $this->db->get()->result();
	}
	
	public function associate_to_asset($asset_id = false, $songwriters = array())
	{
		$out = new stdClass();
			$out->error = false;
			
		if ( !$asset_id ) {
			$out->error = true;
			$out->error_message = "Associate ID missing.";
		}
		if ( !is_array($songwriters) || empty($songwriters) ) {
			$out->error = true;
			$out->error_message = "Songwriters not an array.";
		}
		
		// remove anything that is pre-exsisting
		$this->db->delete("songwriters_assets", array("asset_id"=>$asset_id));
		
		$insert = array();
		foreach ( $songwriters as $k=>$s ) {
			$insert[$k]["songwriter_id"] = $s;
			$insert[$k]["asset_id"] = $asset_id;
		}
		
		if ( $out->erro == true ) { return $out; }
		return $this->db->insert_batch('songwriters_assets', $insert); 
	}
	
	
	public function update($form = false)
	{
		if ( !$form ) { return false; }
		
        $data['id']            = $form['id'];
		$data['pro_id'] 	   = $form['pro_id'];
		$data['publisher_id']  = $form['publisher_id'];
		$data['user_id']       = $form['user_id'];
		$data['name']          = $form['name'];

		$this->db->where('id', $data['id']);
		$this->db->update('songwriters', $data);
    return $this->get($data["id"]);
	}
	
	public function create($form)
	{
		// Check that the name does not all ready exists
		$check = $this->db->get_where('songwriters', array("name"=>$form['name']));
		if ( $check->num_rows > 0 ) {
			return $check->row();
		}
		
		if ( !isset($form['user_id']) || trim($form['user_id']) == "" ) {
			$data['user_id'] = $this->data->user->id;
		} 
		else {
			$data['user_id'] = $form['user_id'];
		}
		
		if ( !empty($form['pro_id']) ) {
			$data['pro_id'] = $form['pro_id'];
		}
		
		if ( !empty($form['publisher_id'])){
        $data['publisher_id'] = $form['publisher_id'];
		}
		
		$data['name'] = $form['name'];
		$data['created'] = date("c", time());
		
		$this->db->insert('songwriters', $data); 
		return $this->db->get_where("songwriters", array("id"=>$this->db->insert_id()))->row();
	}
	
	private function translate_associated($ass = array())
	{
		foreach ( $ass as &$a ) {
			$a = $this->db->get_where("songwriters", array("id"=>$a->songwriter_id))->row();
		} 
		
		return $ass;
	}
	
    public function delete($id){
        $this->db->query("DELETE FROM songwriters WHERE id='$id'");
    }
}
