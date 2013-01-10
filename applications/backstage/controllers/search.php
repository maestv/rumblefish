<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model('search_model');
	}
	
	public function index($table = false)
	{	// Maybe some kinda template switching case in the URL or on the var or something (not intended to be main search).
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( !$table || !$form ) {	
			$this->data->error = "Invalid search criteria.";
			exit;
		}
		
		$this->data->search = $this->search_model->search($table, $form);
		die( json_encode($this->data->search) );
	}
}
