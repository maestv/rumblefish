<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Licenses extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model("licenses_model");
	}
	
	public function index()
	{
	}
	
	public function view() 
	{	
		$licenses = $this->licenses_model->get_all();
		$this->data->licenses = $licenses->licenses;

		$this->render_view('admin/licenses/view');
	}
	
	public function details($id = false)
	{
		if ( !$id ) { return false; }
		
		$licence = $this->licenses_model->get($id);
		$this->data->licence = $licence->license;
		
		$this->render_view('admin/licenses/details');
	}
	
	############################################################
	## Having Jeff look into some bugs with edit and create
	## for licences. This will be on hold till he gets back
	## CB 12/27/2012
	############################################################
	public function edit($id = false)
	{
		if ( !$id ) { return false; }
		
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			
			foreach ( $form as $key=>$value ) {
				if ( trim($value) == "" ) {
					unset($form[$key]);
				}
			}

			var_dump($this->licenses_model->update($form));
			exit();
			
			if ( !is_null($this->licenses_model->update($id, $form)) ) {
				$this->data->redirect = base_url()."admin/licenses/view";
			} else {
				$this->data->error = "Unable to update license!";
			}
		}
		
		$license = $this->licenses_model->get($id);
		$this->data->license = $license->license;
		
		$this->render_view('admin/licenses/form');
	}
	
	public function create()
	{
		$license = false;
		$license = $this->input->post(null, true);
		
		if ( $license ) {
			
			print_r( $this->licenses_model->create($license) );
			exit;
			
			if ( $this->licenses_model->create($license) ) {
				$this->data->redirect = base_url(). 'admin/licenses/view';
			} else {
				$this->data->message = "Unable to create License!";
			}
		}
		
		$this->render_view('admin/licenses/form');
	}
}
