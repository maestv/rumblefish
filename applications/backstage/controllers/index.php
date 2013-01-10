<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
	}
	
	public function index()
	{	
		
		if ( empty($this->data->user) ) {
			$this->data->redirect = base_url().'user/login';
		}
		
		if ( $this->data->user->type == "admin" ) {
			$this->data->template = "admin-dashboard";
		} 
		elseif ( !empty($this->data->user) ) {
			$this->data->reload = base_url()."users";
		}
		
		$this->render_view('user/dashboard');
	}
}
