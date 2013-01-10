<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		
		$this->load->model('asset_model');
		$this->load->model('songwriters_model');
        $this->load->model('publishers_model');
		$this->load->model('document_model');
		$this->load->model('payee_model');
		$this->load->model('artist_model');
		
	/* Sample on XML saving 

		$xml = new xmlingestion_helper($this->asset_model->get(5));
		$xml->save(); // Save will upload as well (and delete the tmp file)
	 */
	}
	
	public function index()
	{	
		$this->render_view('user/dashboard');
	}

    public function account($user_id = false)
    {
		if ( !$user_id ) { $user_id = $this->data->user->id; }

		// Check for admin if ID dosent match our ID!
		if ( $this->data->user->type != "admin" && $user_id != $this->data->user->id ) {
			$this->data->redirect = base_url();
		}		
	
        $form = $this->input->post(null, true);
        if ($form) {	
            $valid_fields = array(
                'user_name',
                'password',
				'type',
				'status',
				'full_name',
				'email',
				'legacy_reference',
				'role',
                'street1',
                'street2',
                'city',
                'state_province',
				'country',
				'company',
                'postal',
				'paypal_email',
				'payable_to',
				'music_rep_contact',
				'split',
				'license_agreement_expiration',
				'license_agreement_sign_date',
				'phone',
				'phone2',
				'role'
            );

            $fields = array();
            foreach ($valid_fields as $key) {
				if ( isset($form[$key]) && trim($form[$key]) != "" ) {
                	$fields[$key] = $form[$key];
				}
            }
			
            $this->users_model->update($user_id, $fields);
        }

		$this->data->target_user = $this->users_model->getUser($user_id);
		$this->data->documents = $this->document_model->get_user($user_id);
		$this->data->artists = $this->artist_model->get_user($user_id);
		
		if ( isset($form['artist_name']) ) {
			$this->data->artists = $this->artist_model->search($form['artist_name'], $user_id, 0, 25);
			$this->data->post = $form;
		}

        $this->render_view();
    }
	
	public function s( $page = 0 )
	{
		$this->data->states = $this->config->item('states');
		
		// Pass limmit though URL or something?
		$limit = 10;
		
		// Get the form
		$post = null;
		$post = $this->input->post(null, true);
		
		if ( $post ) {
			if ( isset($post['name']) ) {
				$post['full_name'] = $post['name'];
					unset($post['name']);
			}
		}
		
		// Go get the accounts
		$results = $this->users_model->search($post, $limit, $page);
		$this->data->accounts = $results->accounts;
		$this->data->searchInfo = $results->data;

		// Alter some post vars because mustache sucks
		if ( $post ) {
			$states = $this->config->item('states');
			foreach ( $states as $state ) {	
				if ( $state['abbreviation'] == $post['state'] ) {
					$post['state'] = $state['state'];
				}
			}
			$this->data->post = $post;
		}
	
		// Pretty Dates
		foreach ( $this->data->accounts as &$account ) {
			$account->created = date("m/d/Y", strtotime($account->created));
		}
		
		// Render View
		$this->render_view('user/search');
	}
	
	public function login() 
	{
		if ( Core_Model::loggedIn() ) {
			$this->data->redirect = base_url().'user';
		}
		
		// Check if were forwarding ourselves somewhere after login
		$get = $this->input->get(null, true);
		
		// Add a redirect function?
		if ( $login = $this->input->post(null, true) ) {
			$return = $this->_validateLogin($login);
			if ( $return ) {
				$this->data->reload = "http://".$_SERVER['SERVER_NAME'].$return;
			} else {
				$this->data->message = "Unable to Log you in!";
			}
		}
	
        $this->data->title = 'Log In';
		$this->render_view('admin/login');
	}
	
	public function adddocuments($target_user_id = false)
	{
		if ( !$target_user_id ) $this->data->redirect = base_url();
		
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {
			if ( empty($form['user_id']) && !$target_user_id ) { die(); }
			if ( empty($form['document_name']) ) { $this->data->message = "There was an error uploading this document"; }
			
			$result = $this->document_model->create($form, $target_user_id);
			if ( $result ) {
				$this->data->close_modal = true;
			} else {
				$this->data->message = "There was an error saving your document!";
			}
		}
		
		$this->data->target_user = $target_user_id;
		$this->render_modal('admin/add-documents');
	}
	
	public function removedocument($document_id = false)
	{
		if ( !$document_id || $this->data->user->type != "admnin" ) $this->data->redirect = base_url();
		
		if ( $this->document_model->remove($document_id) )
		{
			$this->data->success = true;
		} else {
			$this->data->message = "Unable to remove document.";
		}
		
		die(json_encode($this->data));
	}
	
    
    /**
     * The main function for emailing a user a password reset
     * link.
     */
    public function emailpassword(){
        $form = $this->input->post(null, true);

        if ( $form )
		{  // Check that the user hasn't already requested a new password.
			// This should be in a Model!
            $user = $this->db->get_where("users", array("email" => $form["reset-email"]))->row();
            $rst = $this->db->get_where("passwordreset", array("user" => $user->id, "done" => "0"));

            foreach ($rst->result() as $row){
                if (strtotime($row->expiration) > date("c") ){
                    $this->data->message = "You still have a password link sent to your email.";
                    $this->render_view("user/changepassword");
                    return false;
                }
            }

            $key = $this->send_mail_to($form["reset-email"], $user);
            if ($key !== null){
                $this->data->message = "Password reset link sent successfully";
                $this->generatePasswordReset($form["reset-email"], $key);
                $this->render_view("user/emailwassent");
            }
        }
    }

	public function passwordreset($key = false)
	{
		if ( !$key ) $this->data->redirect = base_url().'user/login';
		
		$this->data->key = $key;
		
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post ) { 
			if ( $post['new_password_1'] != $post['new_password_2'] ) { $this->data->message = "Your passwords don't match."; } else {
				
				if ( $this->users_model->resetPassword($post['key'], $post['new_password_1']) ) {
					$this->data->redirect = base_url().'user/login';
				} else {
					$this->data->message = "There was an error resetting your password, please contact an administrator.";
				}
			}
		}
	
		$this->render_view("user/reset-password");
	}
    
    /**
     * Generate a random URL for password reset and store it in the database.
     * @param {String} $email The email of the user.
     * @param {String} $key The key for the email link.
     * @return None
     */
    private function generatePasswordReset($email, $key){
        // Expire in 3 hours.
        //$this->data->message = "Generating password reset";
        $expiration = date("c", mktime(date("h")+3, date("i"), date("s"), date("m"), date("d"), date("Y")));
        $user = $this->db->get_where("users", array("email" => $email))->row()->id;
        $data = array(
            "key" => $key,
            "expiration" => $expiration,
            "user" => $user,
            "done" => "0"
        );
        $this->db->insert("passwordreset", $data);
    }
    
    /**
     * Send an email message to someone.
     * @param {String} $to The person to whom to send the email.
     * @return {String} A new key for password reset.
     */
    private function send_mail_to($to, $user){
        $last_index_query = "SELECT MAX(`id`) AS maxid FROM passwordreset";
        $last_index = $this->db->query($last_index_query)->row()->maxid;
        $key = md5($last_index + 1);
        $resetLink = 'http://'.$_SERVER['SERVER_NAME'].base_url() . "user/passwordreset/" . $key;
        $subject = "Rumblefish Password Reset";
        $from = "Rumblefish Admin <webmaster@rumblefish.com>";
        $replyto = $from;
        $message =
            "<html><body>" .
			"<h2>Hello ".$user->username."</h2>" .
            "<p>Your password has been has reset. Please" .
            " click on the link below or copy it into your browser.</p>" .
            "<p><a href=\"$resetLink\">$resetLink</a></p>" .
            "</body></html>";
        $headers = "From:" . $from . "\r\nReply-to:" . $replyto . "\r\nX-Mailer: PHP/" . phpversion() .
            "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=iso-8859-1\r\n";
        $sent = mail($to, $subject, $message, $headers);
        if (!$sent){
            $this->data->message = "Email could not be sent." . "\n" .
                $headers;
            return null;
        }
        $this->data->message = "We sent you link to reset your password.  Please look in your inbox.";
        return $key;
        //return null;
    }

    public function changepassword()
    {
		$form = false;
		$form = $this->input->post(null, true);
		
		if ( $form ) {	
			
			if ( $form['new_password_1'] != $form['new_password_2']  ) {
				$this->data->error = true;
				$this->data->error_message = "Passwords do not match.";
			}
			
			// Load up the password hashing class.
			$this->load->helper('hashPassword', "hashPassword");
			$this->hashPassword = new Passwordhash_Admin_Helper();
			
			if ( $this->hashPassword->CheckPassword($form['password'], $this->data->user->password) === true || $this->data->user->type == "admin" ) 
			{
				if ( isset($form['user_id']) && trim($form['user_id']) != "" ) {
					$user_id = $form['user_id'];
				}  else {
					$user_id = $this->data->user->id;
				}

				$this->users_model->changePassword($user_id, $form['new_password']);
				
				if ( $this->data->user->type == "admin" ) {
					$this->data->redirect = base_url(). "admin/users";
				} else {
					$this->data->redirect = base_url(). "users";
				}
			}
		}
		
        $this->data->title = 'Change Your Password';
        $this->render_view('user/change-password');
    }
    
    private function get_all_placements($type, $post) {
        $all = array();
        $pre = "placement-duplicate-" . $type;
        for ($i = 0; isset($post[$pre . "-" . $i]); ++$i){
            array_push($all, $post[$pre . "-" + $i]);
        }
        return join(",", $pieces);
    }

	public function register()
	{
	    $post = false;
		$post = $this->input->post(null, true);

        if ( $post ) {
	
			$tmpPassword = md5($this->users_model->hashPassword($post['email']));

			// Create the user
			$user['full_name'] = $post['full_name'];
			$user['email'] = $post['email'];
			$user['password'] = $tmpPassword;
			$user['street1'] = $post['street1'];
			$user['street2'] = $post['street2'];
			$user['city'] = $post['city'];
			$user['state_province'] = $post['state_province'];
			$user['country'] = $post['country'];
			$user['postal'] = $post['postal'];
			$user['type'] = $post['reg-type'];
			$user['status'] = 'inactive';
			
			$user = $this->users_model->create($user);
			if ( !empty($user) ) {
				
				// Send Confirmation Email to Verify Email Address.
				$confirm_link = 'http://'.$_SERVER['SERVER_NAME'].base_url() . "user/confirmemail/" . $tmpPassword;
		        $subject = "Rumblefish Password Reset";
		        $from = "Rumblefish Admin <webmaster@rumblefish.com>";
		
		        $message =
		            "<html><body>" .
					"<h2>Hello ".$user->full_name."</h2>" .
		            "<p>Thank you for registering on *site name* please click the link below to confirm your email address.</p>" .
		            "<p><a href=\"$confirm_link\">$confirm_link</a></p>" .
		            "</body></html>";
		
		        $headers = "From: Rumblefish Admin <webmaster@rumblefish.com>\r\nReply-to:Rumblefish Admin <webmaster@rumblefish.com>\r\nX-Mailer: PHP/".phpversion()."MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=iso-8859-1\r\n";
		        $sent = mail($user->email, $subject, $message, $headers);
				
				// Detect User Type
				switch( $post['reg-type'] ) 
				{
					case "label":
						
						$artist['name'] = $post['full_name'];
						$artist['location'] = $post['city'].', '.$post['state_province'];
						$artist['website'] = $post['label-website'];
						$artist['sound_cloud'] = $post['label-soundcloud'];
						$artist['band_camp'] = $post['label-bandcamp'];
						$artist['youtube'] = $post['label-youtube'];
						$artist['placements'] = json_encode($post['placement']);
						$artist['user_id'] = $user->id;
						
						// We only create the one Artist
						$artists = $this->artist_model->create($artist);
						
					break;
					case "artist":
					
						if ( !empty($post['artist']) ) {
							foreach ( $post['artist'] as $artist ) {
								$new_artist = array();
								
								$new_artist['name'] = $artist['name'];
								$new_artist['location'] = $post['city'].', '.$post['state_province'];
								$new_artist['website'] = $artist['website'];
								$new_artist['sound_cloud'] = $artist['soundcloud'];
								$new_artist['band_camp'] = $artist['bandcamp'];
								$new_artist['youtube'] = $artist['youtube'];
								$new_artist['placements'] = json_encode($artist['placement']);
								$new_artist['user_id'] = $user->id;
								
								// New for each one submittited
								$this->artist_model->create($new_artist);
							}	
						}
						
					break;
				}
				
				$this->data->out = $user;
				
			} else { // IF user is empty
				$this->data->out = array("error" => "Unable to create user.");
			}
		} // end post

		$this->render_view('user/register');
    }

	public function confirmemail($md5 = false)
	{
		if ( $md5 == false ) $this->data->redirect = base_url();
		
		if ( !$this->users_model->confirmemail($md5) ) {
			$this->data->message = "There was an error confirming your email address.";
		} else {
			$this->data->message = "Email Successfully Confirmed!";
		}
		
		$this->render_view('user/comfirm-success');
	}
	
	
	public function firsttime($mode = 1) // Mode really just for Javascript. (mode 2 sets user var)
	{ 
		// Confirm your Music Rep Contact Information.
		$form = false;
		$form = $this->input->post(null, true);
		
        if ($form) {	
            $valid_fields = array(
				'full_name',
				'email',
                'street1',
                'street2',
                'city',
                'state_province',
				'country',
				'company',
                'postal',
				'paypal_email',
				'payable_to',
				'music_rep_contact',
				'phone',
				'phone2',
				"first_login"
            );

			if ( $mode == 2 ) { $form['first_login'] = 0; }
			
            $fields = array();
            foreach ($valid_fields as $key) {
				if ( isset($form[$key]) && trim($form[$key]) != "" ) {
                	$fields[$key] = $form[$key];
				}
            }

            if ( $this->users_model->update($this->data->user->id, $fields) ) {
				if ( $this->uri->uri_string == "users/welcome/1" ) {
					$this->data->redirect = base_url()."users/welcome/2";
				}
				elseif ( $this->uri->uri_string == "users/welcome/2" ) {
					$this->data->redirect = base_url()."users";
				}
			}
        }

		$this->data->target_user = $this->users_model->getUser($this->data->user->id);
		$this->render_view('user/firsttime');
	}
	
	/**
	* handles login
	*/
	private function _validateLogin($form) {
		
		if (empty($form['user_name'])) { $this->session->set_flashdata('notify','Please enter in a username'); return false; }
		if (empty($form['password']))  { $this->session->set_flashdata('notify','Please enter in a password'); return false; }
		
		if ( !$user = $this->users_model->get_where(array("user_name"=>$form['user_name'], 'verified'=>1, "status"=>"active")) ) {
			$this->session->set_flashdata('notify','Incorrect Login Information.'); return false;
		}
		
		// Load up the password hashing class.
		$this->load->helper('hashPassword', "hashPassword");
		$this->hashPassword = new Passwordhash_Admin_Helper();
		
		// Check if our password matches the one provided
		if ( $this->hashPassword->CheckPassword($form['password'], $user->password) === true ) {
			
			// Attempt to log in the user.
			if ( !$this->users_model->login() ) { $this->session->set_flashdata('notify','Incorrect Login Information.'); return false; }	
			$this->session->set_userdata($this->user->user);
			
			// Check for that redirect var!
			if ( isset($form['redirect']) && trim($form['redirect']) != "" ) {
				return $form['redirect'];
			}
			
			return base_url();
		}
		// if we got here then the username and/or password didn't match
		$this->session->set_flashdata('notify','Incorrect Login Information.');
		return false;
	}

	/**
	* logout page
	*/
	public function logout() {		
		$this->users_model->logout();
		$this->data->reload = "http://".$_SERVER['SERVER_NAME'].base_url();
		die( json_encode($this->data) );
	}
}
