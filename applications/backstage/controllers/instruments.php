<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Instruments extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("instruments_model");
	}
	
	public function get()
	{
		$this->data->instruments = $this->instruments_model->get_all();
	}
	
	public function create()
	{
		$post = $this->input->post(null, true);
		if ( $post )
		{
			$instrument = $this->instruments_model->create($post);
			if ( $instrument ) {
				$this->data->out = $instrument;
			} 
			else {
				$this->data->out = array("error"=>"Unable to create Instrument");
			}
		}
		
		die( json_encode($this->data->out) );
	}
}
