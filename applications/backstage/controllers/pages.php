<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
	}
	
	public function index()
	{
		redirect(base_url().'pages/view');
	}
	
	public function view($parent_id = 0)
	{
		$this->data->pages = $this->pages_model->getChildren($parent_id);
		$this->render_view('admin/pages-view');
	}
	
	public function edit($page_id = false)
	{ // Editing basic pages
		if ( !$page_id || $page_id === 0 )
		{
			redirect(base_url());
		}
		
		$this->data->page = $this->pages_model->get($page_id);
		$this->render_view('admin/pages-edit');
	}
	
	public function load() 
	{ // Hey there! Im the fallback for any non file related pages!
		$page = (array) $this->data->page;
		
		if ( empty($page) ) { die('404 here'); }
	
		$this->render_view('pages/default');
	}
}
