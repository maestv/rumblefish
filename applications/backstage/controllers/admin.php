<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("pages_model");
		
		$this->data->title = "Administration";
	}
	
	public function index()
	{
		$this->render_view('admin/dashboard');
	}

    public function test()
    {
        $this->data->message = 'Testing 123';
        $this->render_view('home');
    }

	public function pages_view($parent_id = 0)
	{
		$this->data->page_level = $parent_id;
		$this->data->current = $this->pages_model->get($parent_id);
		$this->data->pages = $this->pages_model->getChildren($parent_id);
		$this->render_view('admin/pages-view');
	}
	
	public function pages_edit($page_id = false)
	{
		if ( !$page_id || $page_id === 0 )
		{
			$this->data->redirect = base_url();
		}
		
		$page = $this->input->post(null, true);
		if ( $page )
		{
			if ( !$page = $this->pages_model->update($page_id, $page) )
			{
				// error
			} else {
				$this->data->redirect = base_url().'admin/page/view/'.$page->parent_id;
			}
		}
		
		$this->data->page_id = $page_id;
		$this->data->page = $this->pages_model->get($page_id);
		$this->data->submit_url = base_url().'admin/page/edit/';
		
		$lister = new List_Helper($this->pages_model->generate_site_map());
			$this->data->parent_options = $lister->getOptions($this->data->page->parent_id);
		
		
		$this->render_view('admin/pages-edit');
	}
	
	public function pages_new($parent_id = 0)
	{
		$page = $this->input->post(null, true);
		
		if ( $page )
		{
			$result = $this->pages_model->save($parent_id, $page);
			
			if ( $result->error == false ) {
				$this->data->redirect = base_url().'admin/page/view/'.$parent_id;
			}
		}
		
		$this->data->submit_url = base_url().'admin/page/new/'.$parent_id;
		
		$lister = new List_Helper($this->pages_model->generate_site_map());
			$this->data->parent_options = $lister->getOptions();

		$this->render_view('admin/pages-edit');
	}
}
