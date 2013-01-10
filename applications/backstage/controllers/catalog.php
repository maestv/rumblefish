<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Catalog extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("catalog_model");
		$this->load->model("licenses_model");
	}

	public function view() 
	{
		$catalogs = $this->catalog_model->get_all();
		if ( !empty($catalogs->catalogs) ) {
			$this->data->catalogs = $catalogs->catalogs;
		}
		
		$this->render_view('admin/catalogs/view');
	}
	
	// Incomplete
	public function edit($catalog_id = false)
	{
		if ( !$catalog_id || !is_numeric($catalog_id) ) {
			$this->data->redirect = base_url(). 'admin/catalog/view';
		}
		
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			$form['id'] = $catalog_id;
			
			print_r($form);
			$result = $this->catalog_model->update($form);
			
			if ( isset($result->error) ) {
				$this->data->message = $result->error->catalog_provider_find;
			} else {
				$this->data->redirect = base_url().'admin/catalog/view';
			}
		} else {
			
			$catalog = $this->catalog_model->get($catalog_id);
			$this->data->catalog = $catalog;
		
			$providers = $this->catalog_model->getProviders();
			$this->data->providers = $providers->providers;
		}
		
		$this->render_view('admin/catalogs/form');
	}
	
	public function licenses($catalog_id = false)
	{
		if ( !$catalog_id || !is_numeric($catalog_id) ) {
			$this->data->redirect = base_url(). 'admin/catalog/view';
		}
		
		$catalog = $this->catalog_model->get($catalog_id);
		$this->data->catalog = $catalog;
		
		$all_licenses = $this->licenses_model->get_all();
		$this->data->all_licenses = $all_licenses->licenses;
		
		$this->render_view('admin/catalogs/licenses');
	}
	
	public function addlicense()
	{
		$form = false;
		$form = $this->input->post(null, true);
		
		// Finnish adding licenses.
		$result = $this->catalog_model->addlicense($form);
		if ( isset($result->error) ) { 
			foreach ( $result->error as $message ) {
				$this->data->message = $message."\n"; 
			}
		} else {	
			$this->data->license->id = $result->catalog_license->id;
			$this->data->license->license_id = $result->catalog_license->license->id;
			$this->data->license->price = $result->catalog_license->price;
			$this->data->license->split_percentage = $result->catalog_license->split_percentage;
		}
		
		die( json_encode($this->data) );
	}
	
	public function create()
	{
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post ) {
			if ( $this->catalog_model->create($post) ) {
				$this->data->redirect = base_url().'admin/catalog/view';
			} else {
				$this->data->message = "Unable to create new Catalog!";
			}
		}
		
		$this->render_view('admin/catalogs/view');
	}
	
	public function removelicenses($catalog_license_id = false)
	{
		if ( !$catalog_license_id || !is_numeric($catalog_license_id) ) {
			$this->data->redirect = base_url(). 'admin/catalog/view/';
		}
		
		$result = $this->catalog_model->removelicense($catalog_license_id);
		if ( !isset($result->error) ) {
			$this->data->success = "true";
		} else {
			$this->data->message = $result->error;
		}
		
		$this->render_view('admin/catalogs/licenses');
	}
}
