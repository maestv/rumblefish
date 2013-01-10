<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model('album_model');
		$this->load->model('asset_model');
		$this->load->model('artist_model');
	}
	
	public function index()
	{	
		$this->data->albums = $this->album_model->get_user($this->data->user->id);
		$this->data->tracks = $this->asset_model->get_user($this->data->user->id);
		
		if ( !empty($this->data->page->current) ) { 
			$this->data->page->sub_navagation = $this->pages_model->getchildrenAsNav($this->data->page->current->id);
		}
			
		$this->render_view('assets/manage');
	}
	
	public function search()
	{	
		$this->data->search = false;
		$this->data->search = $this->input->post(null, true);	
		
		if ( $this->data->search ) {
			$this->data->form = $this->data->search;
			
			// Why cant I do simple math right today?
			if ( $this->data->search['page'] == 1 || !isset($this->data->search['page']) ) {
				$this->data->search['start'] = 0;
			} else {
				$this->data->search['start'] = ($this->data->search['page'] - 1) * 25;
				
				if ( $this->data->search['start'] <= 0 ) {
					$this->data->search['start'] = 25;
				}
			}
			
			$current_url = base_url().str_replace(array($this->data->search['page'], "/".$this->data->search['page']), "", implode("/", $this->uri->segments));
			$this->data->search = $this->asset_model->search($this->data->search, $current_url);
			if ( isset($this->data->search->error) ) {
				$this->data->error = $this->data->search->error->q;
			} else {
				$this->data->search->current_page = $page;
				$this->data->pages = $this->data->search->pages;
			}
		} else {
			// Search Defaults
			$this->data->form['sort'] = "picked_at";
			$this->data->form['page'] = 1;
		}

		$this->render_view('assets/search');	
	}
	
	public function view($type)
	{ // Just an easy way to view the model		
		switch($type) {
			case "tracks":
				default;
				
				$this->data->template = "asset-view-tracks";
				
				$this->data->tracks = $this->asset_model->get_user($this->data->user->id);
				$this->render_view('assets/viewtracks');
				
			break;
			case "albums":
			
				$this->data->template = "assets-view-albums";
			
				$this->data->albums = $this->album_model->get_user($this->data->user->id);
				$this->render_view('assets/viewalbums');
				
			break;
		}
	}
	
	public function createalbum()
	{
		$album = false;
		$album = $this->input->post(null, true);
	
		if ( $album ) {		
			$this->data->album = $this->album_model->create($album);
			if ( $this->data->album != false ) 
			{
				$this->data->redirect = base_url()."assets/createtrack/".$this->data->album->id;
			} else {
				$this->data->error = "Unable to create album.";
			}
		}
		
		$this->render_view('assets/albumform');	
	}
	
	public function editalbum($id)
	{
		$album = false;
		$album = $this->input->post(null, true);
		
		$this->data->album = $this->album_model->get($id);
		if ( empty($this->data->album) )
		{
			$this->data->message = "You don't have access to this album!";
		}
	
		if ( $album ) {		
			$this->data->album = $this->album_model->update($id, $album);
			if ( $this->data->album != false ) 
			{
				$this->data->redirect = base_url()."assets/view/albums";
			} else {
				$this->data->error = "Unable to save album.";
			}
		}
	
		$this->render_view('assets/albumform');	
	}
	
	public function createtrack($album_id = false)
	{
		$this->data->albums = $this->album_model->get_user($this->data->user->id);
		$this->data->artists = $this->artist_model->get_all();
		
		$track = false;
		$track = $this->input->post(null, true);
		
		if ( $track ) {
			$this->data->track = $this->asset_model->create($track);
			if ( $this->data->track ) {
				$this->data->message = $this->data->track->title . " created successfully!";
				$this->data->redirect = base_url()."assets/createtrack/".$album_id;
			} else {
				$this->data->error = "Unable to create track.";
			}
		}
		
		$this->render_view('assets/trackform');	
	}
	
	public function edittrack($track_id = false)
	{
		$this->data->track = $this->asset_model->get($track_id);
		
		$track = false;
		$track = $this->input->post(null, true);
		
		if ( $track ) {
			$this->data->track = $this->asset_model->update($track_id, $track);
			if ( $this->data->track ) {
				$this->data->message = $this->data->track->title . " saved successfully!";
				$this->data->redirect = base_url()."assets/view/tracks";
			} else {
				$this->data->error = "Unable to save track.";
			}
		}
		
		$this->data->artists = $this->artist_model->get_all();
		$this->data->albums = $this->album_model->get_user($this->data->user->id);
		$this->data->track->album = $this->album_model->get($this->data->track->album_id);
		
		if ( $this->data->track->instrumental == 0 ) { unset($this->data->track->instrumental); }
		if ( $this->data->track->explicit == 0 ) { unset($this->data->track->explicit); }
	
		$this->render_view('assets/trackform');	
	}
	
	public function upload_file()
	{
		$folder = date("Y/m/d", time());
		$path = $this->_check_and_reate_dir($folder, $_SERVER['DOCUMENT_ROOT'].'/uploads');
		
		$types = array('png', 'jpg', 'jpeg', "mp3", 'm4a', 'wav', "mp4", "pdf");
		
		// Set up config
		$config['upload_path'] = $path;
		$config['allowed_types'] = implode("|", $types);
		$config['overwrite'] = false;
		$config['remove_spaces'] = true;
		
		// Figure out the type
		$ext = ".".substr(strrchr($_FILES['file']['name'], '.'), 1);
		
		// URL Friendly File_names
		$find = array(' ', '--', '&', "_", '.');
		$replace = array('-', '-', 'and', '-', '');
		$_FILES['file']['name'] = str_replace($find, $replace, preg_replace('/[^a-z0-9_+-]/i', '', str_replace($find, $replace, strtolower(trim(str_replace($types, "", $_FILES['file']['name']))))));

		$config['file_name'] = $_FILES['file']['name'];
		while ( file_exists($config['upload_path']."/".$config['file_name'].$ext) == true ) {
			$config['file_name'] = $_FILES['file']['name']."-".md5(time());
		}
		
		$config['file_name'] .= $ext;
		$_FILES['file']['name'] = $config['file_name']; // Stupid CodeIgniter script
	
		// Upload the file
		$this->load->library('upload', $config);
		if ( !$this->upload->do_upload('file') ) {
			$this->data->result->error = "Unable to upload file! Reason: ". strip_tags( $this->upload->display_errors() );
		} 
		else {
			$this->data->result = (object) $this->upload->data();
			$this->data->result->url = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path).'/'.$this->data->result->file_name;
		}
		
		echo  json_encode($this->data->result);
		exit;
	}
	
	private function _check_and_reate_dir($dir, $initalPath)
	{
		foreach ( explode("/", $dir) as $directory )
		{
			if ( !file_exists($initalPath."/".$directory) ) { mkdir($initalPath."/".$directory); }
			$initalPath .= "/".$directory;
		}
		return $initalPath;
	}
	
	private function clean_search($search = array())
	{
		if ( !is_array($search) ) { return false; }
		$new = array();
		
		foreach ( $search as $key => $value) {
			
			if ( is_array($value) && count($value) > 1 ) {
				$new[$key] = $value;
			}
			elseif ( !empty($value) && count($value) == 1  ) {
				if ( trim($value = array_shift($value)) != "" ) {
					$new[$key] = $value;
				}
			}
			elseif (!is_array($value) && trim($value) != "" ) {
				$new[$key] = $value;
			}
		}
		return $new;
	}
}
