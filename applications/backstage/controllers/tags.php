<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tags extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("tags_model");
	}
	
	public function get()
	{		
		$this->data->tags = $this->tags_model->get_all();
	}
	
	public function update()
	{
		$post = $this->input->post(null, true);
		
		if ( $post )
		{
			if ( empty($post['id']) ) {
				$this->data->error = "You cant update an unspecified tag!";
			}
			
			if ( $this->tags_model->update($post) )
			{
				$this->data->message = "Tag Updated Successfully!";
			}
		}
	}
	
	public function create()
	{
		$post = $this->input->post(null, true);
		if ( $post )
		{
			$tag = $this->tags_model->create($post);
			if ( $tag ) {
				$this->data->out = $tag;
			} 
			else {
				$this->data->out = array("error"=>"Unable to create tag");
			}
		}
		
		die( json_encode($this->data->out) );
	}
}
