<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permissions extends Core_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('permissions_model');
    }

    public function index()
    {
    }

	public function roles()
	{
		if ( !$this->data->user ) { redirect('login'); }
		
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			$role = $this->permissions_model->create_role($form);
			$result = $this->permissions_model->permissions_to_role($role->result, $form['permissions']);
		}
		
		// Get a list of avaliable permissions
		$this->data->permissions = $this->permissions_model->list_permissions();
		
		// Get a list of roles
		$this->data->roles = $this->permissions_model->list_roles();

        $this->render_view('permissions/roles-view', $this->data);
	}
	
	public function edit_role ( $role_id = false )
	{
		if ( !$role_id ) { redirect(base_url(). 'permissions/roles'); }
		
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form )
		{
			if ( $this->permissions_model->update_role($form) ) {
				$this->data->redirect = base_url().'permissions/roles';
			}
		}	
		
		// Get the role
		$this->data->role = $this->permissions_model->get_role($role_id);
		
		// Get a list of avaliable permissions
		$this->data->permissions = $this->permissions_model->list_permissions();
		
		foreach ( $this->data->permissions as $permission ) {
			$permission->checked = false;
			if ( isset($this->data->role->permissions[$permission->id]) ) {
				$permission->checked = ' checked="true"';
			}
		}
		
		$this->render_view('permissions/role-edit', $this->data);
	}
}
