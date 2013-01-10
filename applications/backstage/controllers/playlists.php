<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Playlists extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("playlists_model");
	}
	
	public function index()
	{
        $this->data->playlists = $this->playlists_model->get_all();
        $this->render_view();
	}

	public function edit()
	{
        $post = $this->input->post(null, true);
        if ( $post ) {
            $post['user_id'] = $this->data->user->id;
            if ( $post['id'] ) {
                // Edit
                $this->data->playlist = $this->playlists_model->update($post);
            } else {
                // Create
                $this->data->playlist = $this->playlists_model->create($post);
            }
        }
        $this->render_view();
	}

    public function add_song()
    {
        $post = $this->input->post(null, true);
        if ( $post ) {
            $this->data->playlist = $this->playlists_model->add_song($post['playlist'], $post['song']);
        }
        $this->render_view();
    }
    
    public function view($id){
	    
    }
}
