<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Portals extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model("portal_model");
		$this->load->model("licenses_model");
		$this->load->model("catalog_model");
	}
	
	public function index()
	{
	
	}
	
	public function view() 
	{	
		$portals = $this->portal_model->get_all();
		$this->data->portals = $portals->portals;
		
		$this->render_view('portals/view');
	}
	
	public function details($id = false) 
	{	
		if ( !$id ) { $this->data->redirect = base_url().'admin/portals/view'; }
		
		$update = false;
		$update = $this->input->post(null, true);
		
		if ($update) {
			$this->portal_model->update($form);
			$this->data->redirect = base_url().'admin/portals/view';
		}
		
		$portal = $this->portal_model->get($id);
		$this->data->portal = $portal->portal; // Yo Dog.
		
		$this->render_view('portals/form');
	}
	
	public function licenses($id = false) {
		
		if ( !$id ) { $this->data->redirect = base_url().'admin/portals/view'; }
		
		$this->data->portal_id = $id;
		
		$licenses = $this->portal_model->get_licenses($id);
		if ( count($licenses->portal_licenses) > 0 ) {
			$this->data->current_licenses = $licenses->portal_licenses;
		}
		
		$licenses = $this->licenses_model->get_all();
		if ( count($licenses->licenses) > 0 ) {
			$this->data->all_licenses = $licenses->licenses; // Yo Dog.
		}
		
		$this->render_view('portals/licenses');
	}
	
	public function addlicense()
	{
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			if ( !isset($form['download']) ) { $form['download'] = "false"; }
			
			$attach = $this->portal_model->add_license($form);
			if ( isset($attach->error) ) {
				foreach ( $attach->error as $error ) {
					$this->data->message = $error."\n";
				}
			} else { 
				$this->data->license = $attach->portal_license;
				$this->data->portal_id = $form['portal_id'];
			}
		}
		
		$this->render_view('portals/licenses');
	}
	
	public function updatelicense()
	{
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			if ( !isset($form['download']) ) { $form['download'] = "false"; }
			
			$attach = $this->portal_model->update_license($form);
			if ( isset($attach->error) ) {
				foreach ( $attach->error as $error ) {
					$this->data->message = $error."\n";
				}
			} else {
				$this->data->licence = $attach->portal_license;
			}
		}
		
		$this->render_view('portals/licenses');
	}
	
	public function removelicense($id = false)
	{
		if ( !$id || !is_numeric($id) ) { $this->data->redirect = base_url().'admin/portals/view'; }
		$result = $this->portal_model->removelicense($id);
		
		if ( !isset($result->deleted_id) ) {
			$this->data->message = "Unable to delete license: API error!";
		} else {
			$this->data->success = "true";
		}
		
		$this->render_view('portals/licenses');
	}
	
	public function catalogs($portal_id = false) {
		if ( !$portal_id || !is_numeric($portal_id) ) { $this->data->redirect = base_url().'admin/portals/view'; }
		
		$current_catalogs = $this->portal_model->catalogs($portal_id);
		$this->data->current_catalogs = $current_catalogs->portal_catalogs;
		
		
		$all_catalogs = $this->catalog_model->get_all();
		$this->data->all_catalogs = $all_catalogs->catalogs;
		$this->data->portal_id = $portal_id;
		
		$this->render_view('portals/catalogs');
	}
	
	public function addcatalog() 
	{
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			$result = $this->portal_model->add_catalog($form);
			if ( !isset($result->error) ) {
				$this->data->catalog = $result->portal_catalog;
			} else {
				$this->data->message = "There was an error associating this catalog and portal!";
			}
		}
		
		die( json_encode($this->data) );
	}
	
	public function removecatalog($portal_catalog_id = false)
	{
		if ( !$portal_catalog_id || !is_numeric($portal_catalog_id) ) {
			$this->data->redirect = base_url(). "admin/portals/view";
		}
		
		$result = $this->portal_model->removecatalog($portal_catalog_id);
		if ( isset($result->deleted_id) ) {
			$this->data->success = "true";
		} else {
			$this->data->error = "Unable to remove catalog.";
		}
		
		die( json_encode($this->data) );
	}
	
	public function create()
	{
		$portal = false;
		$portal = $this->input->post(null, true);

		if ( $portal ) {
			unset($portal['_wysihtml5_mode']);
			
			$checkboxes = array("receipt_value", "invoicing", "admin");
			foreach ( $checkboxes as $key=>$row ) {
				if ( !isset($portal[$row]) ) {
					$portal[$row] = "false";
				} else {
					$portal[$row] = "true";
				}
			}
			
			$portal_id = $this->portal_model->create($portal);
			if ( $portal_id ) {
				$this->data->redirect = base_url(). 'admin/portals/view';
			} else {
				$this->data->message = "Unable to create Portal!";
			}
		}
		
		$this->render_view('portals/form');
	}
}
