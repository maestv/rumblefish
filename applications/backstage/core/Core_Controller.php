<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Core_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->data = new stdClass();
		$this->data->user = $this->users_model->get(); // User model loaded by default
		
		//Load the page info from the database
		$this->loadPage();
		
		// Check if were logged in
		$this->_verifiyAccount(); // THis is where my accounts stuff should be
		
		// Get the API initialized
		$this->_initializeApi();
		
		// Check for running first Login function
		$this->_fistLogin();
	}
	
	protected function render_view()
    {
        $this->data = (array) $this->data;

        if ( $this->input->is_ajax_request() ) {
            echo json_encode($this->data);
        } else {
            $this->data['json'] = json_encode($this->data);
			$this->data['navigation'] = $this->pages_model->build_navigation();
            $this->load->view('base', $this->data);
        }
    }

	protected function render_modal()
    {
        $this->data = (array) $this->data;
		
		if ( $this->input->is_ajax_request() ) {
            echo json_encode($this->data);
        } else {
            $this->data['json'] = json_encode($this->data);
			$this->data['navigation'] = $this->pages_model->build_navigation();
            $this->load->view('modal-base', $this->data);
        }
    }

	protected function _verifiyAccount()
	{
		$redirect = base_url().'user/login';
		$uri = $this->uri->segments;
	
		$oneSegs = array(
			"user",
			"terms-of-service",
			"contact-us"
		);
		
		$twoSegs = array(
			"login",
			"passwordreset",
			"emailpassword",
			"register"
		);
		
		if ( !$this->users_model->is_logged_in() ) {
			if ( !in_array($uri[1], $oneSegs) ) {
				$this->data->redirect = $redirect;
			} 
			else if ( !in_array($uri[2], $twoSegs) || empty($uri) ) {
				$this->data->redirect = $redirect;
			}
		}	
	}
	
	private function _initializeApi()
	{
		// Located in the config file.
		$rfConfig = $this->config->item('rumblefish');
		
		rfExchange::setUp($rfConfig['user'], $rfConfig['password']);
		rfExchange::logDir( $rfConfig['logsDir'] );
		$this->data->token = rfExchange::authenticate();
		
		if ( $this->input->is_ajax_request() ) {
			rfExchange::setOutput_format('JSON'); // Tells the API to return JSON Objects
		}
	}
	
	private function _configMeta()
	{
		$states = $this->config->item('states');
		return array("states"=>$states);
	}
	
	public function isAdmin()
	{
		if ( $this->data['user']->type == 'admin' ) {
			return true;
		}
		
		return false;
	}
	
	// Looks for any url requested and builds the structure out of the database
	public function loadPage() {
		$this->data->page = new stdClass();
		
		$uri = $this->uri->segments;
		$uri = $this->checkForPage($uri);
	
		$pages = array();
		$parent_id = 0;
		$i = 0;

		foreach ( $uri['url'] as $segment ) {
			$page = $this->pages_model->get_uri_segment($parent_id, $segment);
			if ( !empty($page) ) {
				$pages[$i] = $page;
				$parent_id = $page->id;
				$i++;
			} else {
				break;
			}
		}
		
		// If this fails, I'll to try and match the forwarding URL.
		if ( !empty($pages) ) {
			$this->data->page->structure = (object) $pages;
			$page = array_pop($pages);

			if ( $page->url != '/'.implode("/", $uri['url']) ) {
				// Try to get the page by this URL as a forward				
				$newPage = $this->pages_model->get_where(array("forward"=>'/'.implode("/", $uri['url'])));
				if ( count($newPage) === 1 ) {
					$this->data->page->current = array_pop($newPage);
				}
			} else {
				// Hey our URL's generated matched the requested one!
				$this->data->page->current = $page;
			}
		} else {
			
		}
		
		if ( !empty($this->data->page->structure) ) {
			$array = (array) $this->data->page->structure;
			$this->data->page->top = $array[array_shift(array_keys((array) $this->data->page->structure))]; /// zzzzz
				unset($array);
				
			if ( $this->data->page->top->meta->display_child_nav == 1 ) {
				$this->data->page->top->children = $this->pages_model->getchildrenAsNav($this->data->page->top->id);
			}
		}
		
		return true;
	}
	
	public function checkForPage($URI = array()) {	
		// Check for page var (for pagination)
		if ( $key = array_search('page', $URI) ) {
			// A little more validation... 
			// Page must be immidiatly followed by page num /page/2
			if ( is_numeric($URI[$key + 1]) ) {
				// Remove these off the URI and pass them through later
				$page = $URI[$key + 1];

				unset($URI[$key]);
				unset($URI[$key+1]);
			}	
		}
		
		// Returns the URI Minus the Page Var's
		return array('url'=>$URI, "page"=>$page);	
	}
	
	public function _fistLogin()
	{
		if ( $this->data->user ) {
			$okUrls = array(
				base_url()."users/welcome/1",
				base_url()."users/welcome/2",
			);
			
			if ( $this->data->user->first_login == 1 && !in_array(base_url().$this->uri->uri_string, $okUrls) && $this->data->user->type != "admin" ) {
				$this->data->redirect = array_shift($okUrls);
			}
		}	
	}
}
