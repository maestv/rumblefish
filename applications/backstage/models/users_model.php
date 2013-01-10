<?php
class Users_model extends Core_Model
{
	function __construct()
    {
		parent::__construct();
		$this->load->model('permissions_model');
		$this->load->model('notes_model');
	}
	
	function get($sid = false)
	{
		$user = new stdClass();
		
        // If there's no session, the user is clearly not logged in.
		if ( !$sid = $_SESSION['sid'] ) {
             return false;
		}
		
		$user = $this->get_where(array("sid"=>$sid));
		if ( empty($user) ) { return false; }
		
        return $this->getUser($user->id);
	}
	
	public function getUser($id = false)
	{
		if ( !$id ) { return false; }

		$user = $this->get_where(array("id"=>$id));
		if ( empty($user) ) { return false; }

		// get user permissions.
		$user->access = $this->permissions_model->get_user_permissions($user->id);
		
		// For Handlebars
		if ( $user->type == 'admin' ) { $user->isAdmin = true; } // Check me later, dont rely on this being secure.
		
		// We dont always need this.
		//$user->account_notes = $this->notes_model->get_user($user->id);
		
		return $user;
	}
	
	public function getAll()
	{
		return $this->db->get_where('users', array("verified"=>1))->result();
	}
	
	public function get_where($where = array()) {
		
		$this->user = $this->db->get_where("users", $where);
		
		if ( $this->user->num_rows == 1 ) {
			$this->user = $this->user->row();
		} else {
			$this->user = $this->user->result();
		}
		
		return $this->user;
	}
	
	public function login() {
		
		// Set the Session ID
		$sid = md5(microtime());
		$update = array('sid' => $sid,'last_login' => date("Y-m-d H:i:s", time()));
        $where = array('id' => $this->user->id);

		$this->db->update('users', $update, $where);
		
		if ( $this->user = $this->get_where(array("sid"=>$sid)) ) {
			$_SESSION['sid'] = $this->user->sid;
			return true;
		}
		
		return false;
	}
	
	public function is_logged_in() {
		
		if ( isset($_SESSION['sid']) && ($this->get($_SESSION['sid']) != false) ) {
			return true;
		}
		
		if ( $user = $this->get() ) {
			return true;
		} 
		
		return false;
	}
	
	public function logout() {
		$update = array('sid' => NULL);
        $where = array('id' => $this->data->user->id);
		$this->db->update('users', $update, $where);
		
		$this->session->sess_destroy();
			unset($_SESSION['sid']);
			unset($this->data->user);
		return true;
	}
	
	public function get_session()
    {
        $session = (object) $this->session->userdata;
        if ( !$session->sid ) {
            return false;
        }

		return $session->sid;
    }

    public function create($form)
    {
		$form['created'] = date("c");
	
        $this->db->insert('users', $form);
        return $this->db->get_where("users", array("id"=>$this->db->insert_id()))->row();
    }

    public function update($id, $form)
    {
        $form['modified'] = date("c");

		if ( !empty($form['license_agreement_sign_date']) ) {
			$form['license_agreement_sign_date'] = date("c", strtotime($form['license_agreement_sign_date']));
		}

		if ( !empty($form['license_agreement_expiration']) ) {
			$form['license_agreement_expiration'] = date("c", strtotime($form['license_agreement_expiration']));
		}


        $update = $this->db->update('users', $form, array('id' => $id));

		if ( $update == true ) {
			$user = $this->db->get_where("users", array("id"=>$id))->row();
				
			// How we do Payee!
			if ( $user->payee_id == 0 && $this->data->user->type == "admin" )
			{ // Were and admin and editing a user account. Lets see if we made it eligible for an API call.
				
				if ( $user->status == "active" && $user->type != "admin" && $user->verified == 1 ) {
					// Check if we have the required fields.
					if ( 
						!empty($user->role) &&
						!empty($user->full_name) && 
						!empty($user->company) &&
						!empty($user->email) &&
						!empty($user->street1) &&
						!empty($user->city) &&
						!empty($user->postal) &&
						!empty($user->country) &&
						!empty($user->license_agreement_sign_date) &&
						!empty($user->music_rep_contact)
					) 
					{ // Ok were a fairly new account, we just did a save and now we have all the required info.
						
						$user = (array) $user;
						$user['token'] = $this->data->token;
						
						$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/payee", "POST", $user);
						$api->execute();
						
						$response = json_decode($api->getResponseBody());
						
						/*
						 * On Hold, Invalid API Response.
						 */
						print_r($api);
						print_r($response);
						die();
						
						if ( is_numeric($response->payee_id) ) { // Update Payee ID in the database.
							$this->db->update("users", array("payee_id"=>$response->payee_id), array("id"=>$user['id']));
							$user['payee_id'] = $response->payee_id;
							
							$user = (object) $user;
							return $user;
						}
					}
				}
			} 
			else if ( $user->payee_id != 0 ) {
				// Check to do an update
				$api = new Rest_Helper("https://sandbox.rumblefish.com/v2/payee", "PUT", $user);
				$api->execute();
				
				$response = json_decode($api->getResponseBody());
				
				print_r($api);
				print_r($response);
				die();
			}
		}
		
		return $update;
    }

	public function resetPassword($key, $new_password)
	{
		$map = $this->db->get_where("passwordreset", array("key"=>$key, "done"=>"0"));
		if ( $map->num_rows == 0 ) { return false; } 
		
		if ( !$this->changePassword($map->row()->user, $new_password) ) {
			return false;
		}
		
		$this->db->update('passwordreset', array("done"=>1), array("key"=>$key));
		return true;
	}

	public function changePassword($user_id, $new_password) 
	{
		$password = $this->hashPassword($new_password);
		
		$update = array('password' => $password);
        $where = array('id' => $user_id);

		return $this->db->update('users', $update, $where);
	}
	
	public function search($search, $limit, $offet, $order = "status ASC")
	{
		// Set us up the bomb
		$select = $this->db->select("*");
		
		if ( !empty($search['user_name']) ) {
			$select = $this->db->where('user_name', $search['user_name']);
		}
		
		if ( !empty($search['email']) ) {
			$select = $this->db->where('email', $search['email']);
		}
		
		if ( !empty($search['full_name']) ) {
			$select = $this->db->like('full_name', $search['full_name'], 'after');
		}
		
		if ( !empty($search['city']) ) {
			$select = $this->db->where('city', $search['city']);
		}
		
		if ( !empty($search['state_province']) && $search['state_province'] != "false" ) {
			$select = $this->db->where('state_province', $search['state_province']);
		}
		
		if ( !empty($search['postal']) ) {
			$select = $this->db->where('postal', $search['postal']);
		}
		
		if ( !empty($search['status']) && $search['status'] != "false" ) {
			$select = $this->db->where('status', $search['status']);
		}
		
		if ( !empty($search['type']) && $search['type'] != "false" ) {
			$select = $this->db->where('type', $search['type']);
		}
		
		if ( !empty($search['company']) ) {
			$select = $this->db->like('company', $search['company']);
		}
		
		// Account Created Search
		if ( isset( $search['created'] ) && !empty( $search['created'] ) ) {
			if ( !empty($search['created']['before']) ) {
				$before = date("c", strtotime($search['created']['before']));
				$select = $this->db->where(array("created >= "=> $before));
			}
			if ( !empty($search['created']['after']) ) {
				$after = date("c", strtotime($search['created']['after']));
				$select = $this->db->where(array("created <= "=> $after));
			}
		}
		
		// Last Modified Search
		if ( isset( $search['modified'] ) && !empty( $search['modified'] ) ) {
			if ( !empty($search['modified']['before']) ) {
				$before = date("c", strtotime($search['modified']['before']));
				$select = $this->db->where(array("modified >= "=> $before));
			}
			if ( !empty($search['modified']['after']) ) {
				$after = date("c", strtotime($search['modified']['after']));
				$select = $this->db->where(array("modified <= "=> $after));
			}
		}
		
		// Last Login search
		if ( isset( $search['last_login'] ) && !empty( $search['last_login'] ) ) {
			if ( !empty($search['last_login']['before']) ) {
				$before = date("c", strtotime($search['last_login']['before']));
				$select = $this->db->where(array("last_login >= "=> $before));
			}
			if ( !empty($search['last_login']['after']) ) {
				$after = date("c", strtotime($search['last_login']['after']));
				$select = $this->db->where(array("last_login <= "=> $after));
			}
		}
		
		$select->order_by($order);
		
		$return->data = $select->get('users', $limit, $offset);
		$return->accounts = $return->data->result();
		
		return $return;
	}
	
	public function confirmemail($md5 = false) {
		$update = array('verified' => 1);
        $where = array('password' => $md5);
		
		return $this->db->update('users', $update, $where);
	}
	
	/**
	* hashes password
	*/
	public function hashPassword($password) {
		$this->load->helper("hashpassword_helper");
		
		$hash = new Passwordhash_Admin_Helper();
		return $hash->HashPassword($password);
	}
}
