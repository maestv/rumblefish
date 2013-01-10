<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Artist extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("artist_model");
		$this->load->model("asset_model");
	}
	
	public function index()
	{
		$this->data->redirect = base_url()."artist/view";
		die( json_encode($this->data) );
	}
	
	public function view($page = 0)
	{	
		$post = false;
		$post = $this->input->post(null, true);

		if ( $post ) {
			$this->data->artists = $this->artist_model->search($post['artist_name'], false, $page, '25');
			$this->data->post = $post;
		} else {
			$this->data->artists = $this->artist_model->get_all($page, '25');
		}
		
		$this->render_view('artist/view');
	}
	
	public function details($artist_id = false)
	{
		if ( !$artist_id ) $this->data->redirect = base_url(). "users/artists";
		
		$this->data->artist = $this->artist_model->get($artist_id);
		//$this->data->assets = $this->asset_model->search(array("artist_names"=>"Dennis Hitchcox"), 1); // array("artist_name"=>$this->data->artist->name)
		
		// Man, this works amazingly better.
		$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/search", "GET", array("artist_names"=>"Dennis Hitchcox"), $this->data->token);
		$api->execute();
		
		$this->data->assets = json_decode($api->getResponseBody());
		
		$this->render_view('artist/details');
	}
	
	public function add()
	{
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post ) {
			$this->data->artist = $this->artist_model->create($post);
			if ( $this->data->artist != false) {
				$this->data->redirect = base_url()."artist/view";
			} else {
				$this->data->error = "Unable to save Artist.";
			}
		}
		
		$this->data->page->header = "Add Artist";
		$this->render_view('artist/form');
	}
	
	public function edit($id = false)
	{
		$post = false;
		$post = $this->input->post(null, true);
		if ( $post ) {
			$this->data->artist = $this->artist_model->update($id, $post);
			if ( $this->data->artist != false) {
				$this->data->redirect = base_url()."artist/view";
			} else {
				$this->data->error = "Unable to save Artist.";
			}
		}
		
		$this->data->page->header = "Edit Artist";
		$this->data->artist = $this->artist_model->get($id);
		$this->render_view('artist/form');
	}
}
