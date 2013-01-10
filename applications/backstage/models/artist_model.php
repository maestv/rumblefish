<?php
class Artist_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		
		//$this->load->model();
	}
	
	public function get($id = false) 
	{
		if ( !$id ) { return false; }
		$artist = $this->db->get_where('artists', array("id"=>$id))->row();
		
		$artist->placements = json_decode($artist->placements);
		return $artist;
	}
	
	public function get_user($user_id = false, $offset = 0, $limit = 25)
	{
		if ( !$user_id ) { return false; }
		
		$select = $this->db->select("*")->where(array("user_id"=>$user_id));
		return $this->db->get('artists', $limit, $offset)->result();
	}
	
	public function get_all($page, $limit) // WHy page and limmit!?
	{
		if ( $this->data->user->type == "admin" ) {
			$artists = $this->db->select("*")->from('artists')->get()->result();
		}
		else {
			$artists = $this->db->select("*")->from('artists')->where('user_id', $this->data->user->id)->get()->result();
		}
		
		foreach ($artists as &$artist) {
			$artist->music_rep = $this->users_model->get($artist->user_id);
		}
		
		return $artists;
	}
	
	public function search($nameSearch = false, $user_id = false, $offset = 0, $limit = 25) 
	{
		$artists = $this->db->select("*");
		
		if ( $user_id != false ) { // If we specified an user_id
			$artists = $this->db->where('user_id', $user_id);
		}
		elseif ( !$user_id && $this->data->user->type != "admin" ) { // If we diddent and were not an admin
			$artists = $this->db->where('user_id', $this->data->user->id);
		}
		
		if ( $nameSearch ) { $artists = $this->db->like('name', $nameSearch, 'after'); }
		
		return $artists->get('artists', $limit, $offset)->result();
	}
	
	public function update($artist_id = false, $form = false)
	{
		if ( !$form && !$artist_id ) { return false; }

		$data["name"] = $form['name'];
		$data["location"] = $form['location'];
		$data["website"] = $form['website'];
		$data["facebook"] = $form['facebook'];
		$data["twitter"] = $form['twitter'];
		$data["youtube"] = $form['youtube'];
		$data["band_camp"] = $form['band_camp'];
		$data["sound_cloud"] = $form['sound_cloud'];
		$data["biography"] = $form['biography'];
		
		if ( isset($form["photo_filename"]) && trim($form["photo_filename"]) != "" ) {
			$data["photo_filename"] = $form['photo_filename'];
			$data["photo_url"] = $form['photo_url'];
			$data["photo_extension"] = $form['photo_extension'];
		}
	
		$this->db->where('id', $artist_id);
		return $this->db->update('artists', $data);
	}
	
	public function create($form = false)
	{
		if ( !$form ) { return false; }

		$data["name"] = $form['name'];
		$data["location"] = $form['location'];
		$data["placements"] = $form['placements'];
		$data["website"] = $form['website'];
		$data["facebook"] = $form['facebook'];
		$data["twitter"] = $form['twitter'];
		$data["youtube"] = $form['youtube'];
		$data["band_camp"] = $form['band_camp'];
		$data["sound_cloud"] = $form['sound_cloud'];
		$data["biography"] = $form['biography'];
		$data["photo_filename"] = $form['photo_filename'];
		$data["photo_url"] = $form['photo_url'];
		$data["photo_extension"] = $form['photo_extension'];
		$data["created"] = date("c");
		
		if ( !isset($form['user_id']) || trim($form['user_id']) == "" ) {
			$data["user_id"] = $this->data->user->id;
		} else {
			$data["user_id"] = $form['user_id'];
		}
		
		$this->db->insert('artists', $data); 
		return $this->db->get_where("artists", array("id"=>$this->db->insert_id()))->row();
	}
}
