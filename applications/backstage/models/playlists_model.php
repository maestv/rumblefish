<?php
class Playlists_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
	}
	
    public function get($id)
    {   
        $api = new Rest_Helper("https://sandbox.rumblefish.com/v2/playlist_admin", "GET", array("id"=>$id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
    }
	
	public function get_all()
	{
        $api = new Rest_Helper("https://sandbox.rumblefish.com/v2/playlist_admin", "GET", null, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}

	public function create($form)
	{
        $data['user_id'] = $form['user_id'];
        $data['title'] = $form['title'];
//        $data['created'] = date("c", time());

        $api = new Rest_Helper("https://sandbox.rumblefish.com/v2/playlist_admin", "POST", $data, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}

	public function update($form)
	{
        $data['user_id'] = $form['user_id'];
        $data['title'] = $form['title'];
//        $data['created'] = date("c", time());

        $api = new Rest_Helper("https://sandbox.rumblefish.com/v2/playlist_admin", "PUT", $data, $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
	}

    public function add_song($playlist_id, $song_id)
    {
        
        $api = new Rest_Helper("https://sandbox.rumblefish.com/v2/playlist_admin", "PUT", array('playlist_id' => $playlist_id, 'song_id' => $song_id), $this->data->token);
		$api->execute();
		
		return json_decode($api->getResponseBody());
    }

    public function get_songs($playlist_id)
    {

    }
}