<?php
class Permissions_model extends Core_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_role($role_id)
	{
		// Get the role
		$role = $this->db->get_where("roles", array("id"=>$role_id));
		if ( $role->num_rows == 0 ) { return false; }
		
		$role = $role->row();
	
		// Get the permissions for each role. 
		$permissions = array();
		$role_permissions = $this->get_role_permissions($role->id);
		if ( $role_permissions != false ) {
			foreach ( $role_permissions as $permission )
			{
				$permissions[$permission->permission_id] = $this->db->get_where('permissions', array('id'=>$permission->permission_id))->row();
			}
		}
		
		if ( !empty($permissions) )
		{
			$role->permissions = $permissions;
		}
		
		return $role;
	}
	
	public function list_permissions()
	{
		return $this->db->select("*")->from('permissions')->get()->result();
	}
	
	public function list_roles()
	{
		return $this->db->select("*")->from('roles')->get()->result();
	}
	
	public function create_role($args)
	{
		$test = $this->db->get_where('roles', array("name"=>$name));
		if ( $test->num_rows > 0 ) {
			return $test->row();
		}
		
		$insert = array(
			"name"=>$name,
			"description"=>$description,
			"created"=>date("c", time())
		);
		
		$this->db->insert('roles', $insert);
		return $this->db->get_where('roles', array("id"=>mysql_insert_id()))->row();
	}
	
	public function permissions_to_role($role, $permissions)
	{
		// Remove all permissions associated with this role.
		$this->db->delete('role_permissions', array('role_id' => $role->id));
		
		// Build Insert
		foreach ( $permissions as $k=>$permission ) {
			$insert[$k]['permission_id'] = $permission;
			$insert[$k]['role_id'] = $role_id;
		}
		
		// Put into the database.
		return $this->db->insert_batch('role_permissions', $insert);
	}
	
	public function update_role($form)
	{
		$this->db->where('id', $form['role_id']);
		$this->db->update('roles', array("name"=>$form['name'], "description"=>$form['description']));
		
		// Remove all permissions associated with this role.
		$this->db->delete('role_permissions', array('role_id' => $form['role_id']));
		
		// Build Insert
		if ( isset($form['permissions']) ) {
			$insert = array();
			foreach ( $form['permissions'] as $k=>$permission ) {
				$insert[$k]['permission_id'] = $permission;
				$insert[$k]['role_id'] = $form['role_id'];
			}
			
			// Put into the database.
			return $this->db->insert_batch('role_permissions', $insert);
		}
		
		return true;
	}
	
	public function update_user_roles($user_id, $roles = false)
	{
		$this->db->delete('user_roles', array('user_id' => $user_id));
		
		if ( $roles ) { 
			foreach ( $roles as $k=>$role ) {
				$insert[$k]['user_id'] = $user_id;
				$insert[$k]['role_id'] = $role;
			}
		
			return $this->db->insert_batch('user_roles', $insert);
		}
		
		return;
	}
	
	public function get_user_permissions($user_id)
	{
		// Get the roles associated with a user.
		$roles = $this->db->get_where('user_roles', array("user_id"=>$user_id));
		if ( $roles->num_rows == 0 ) { return false; }
		$roles = $roles->result();
		
		// Get all permissions associated with each role. 
		$user_permissions = array();
		$user_roles = array();
		foreach ( $roles as $role ) {
			$tmpPerms = $this->get_role_permissions($role->role_id);
			if ( $tmpPerms ) {
				foreach ( $tmpPerms as $permission )
				{
					$user_permissions[$permission->permission_id] = $this->db->get_where('permissions', array('id'=>$permission->permission_id))->row();
				}
			}
			
			// Get each role just incase we need it.
			$user_roles[$role->role_id] = $this->get_role($role->role_id);
		}
		
		return array("permissions"=>$user_permissions, "roles"=>$user_roles);
	}	
	
	public function get_role_permissions($role_id)
	{
		$permissions = $this->db->get_where('role_permissions', array("role_id"=>$role_id));
		if ( $permissions->num_rows == 0 ) { return false; }
		
		return $permissions->result();
	}
	
	public function check_access($access_id)
	{

		if ( isset($this->data->user->access['permissions'][$access_id]) ) {
			$this->_output_json_success(array('result' => 'true'));
			exit;
		}
		
		$this->_output_json_error('300', "You don't have permission to access this area.");
	}
}
