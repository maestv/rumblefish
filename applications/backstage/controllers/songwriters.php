<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Songwriters extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("songwriters_model");
		$this->load->model("publishers_model");
        $this->load->model("pro_model");
	}
	
	public function index()
	{
		$this->data->songwriters = $this->songwriters_model->get_user($this->data->user->id);
        $this->data->all_pros = $this->pro_model->get();
        $this->data->all_publishers = $this->publishers_model->get_all();

		$this->render_view();
	}
	
	public function get($user_id = false)
	{		
		if ( $user_id === false ) {
			$this->data->songwriters = $this->songwriters_model->get_all();
            $this->data->all_pros = $this->pro_model->get();
            $this->data->all_publishers = $this->publishers_model->get_all();
		}
		else {
			$this->data->songwriters = $this->songwriters_model->get_user($user_id);
            $this->data->all_pros = $this->pro_model->get();
            $this->data->all_publishers = $this->publishers_model->get_all();
		}	
	}
	
	public function get_publisher($songwriter_id){
	    $this->data->publisher = $this->songwriters_model->get_publisher($songwriter_id);
	}

    public function add()
    {
        $post = $this->input->post(null, true);

        if ($post) {
            $search = $this->songwriters_model->get_name($post['name'], $this->data->user->id);
            if (!$search) {
                // Add new
                $form = array(
                    'name' => $post['field-new-songwriter-name'],
                    'publisher_id' => $post['field-new-songwriter-publisher'],
                    'pro_id' => $post['field-new-songwriter-pro'],
                    'user_id' => $this->data->user->id,
                );
                $newsw = $this->songwriters_model->create($form);
                $this->data->newsongwriter->songwriter = $newsw;
                $this->data->newsongwriter->pro = $this->pro_model->get_pro($form['pro_id']);
                $this->data->newsongwriter->publisher = $this->publishers_model->get_publisher($form['publisher_id']);
                $this->data->newsongwriter->all_pros = $this->pro_model->get();
                $this->data->newsongwriter->all_publishers = $this->publishers_model->get_all();
            }
            // Else it already exists--do nothing
        }else{
            $this->data->message = "Songwriter already exists";
        }
        $this->render_view();
    }
	
	public function update()
	{
		$post = $this->input->post(null, true);
		
		if ( $post ) {
			
            $form = array(
              'id' => $post['field-songwriter-id'],
              'name' => $post['field-songwriter-name'],
              'pro_id' => $post['field-songwriter-pro'],
              'publisher_id' => $post['field-songwriter-publisher'],
              'user_id' => $this->data->user->id,
            );

            $songwriters = $this->songwriters_model->update($form);
			if ( $songwriters == false ) {
				$this->data->message = "Error updating songwriter!";
			} else {
				$this->data->row = $songwriters;	
			}

            die( json_encode($this->data) );
		}
	}
	
	public function create()
	{
		$post = $this->input->post(null, true);
		
		if ( $post )
		{
			$songwriter = $this->songwriters_model->create($post);
			if ( $songwriter ) {
				$this->data->out = $songwriter;
			} 
			else {
				$this->data->out = array("error"=>"Unable to create Songwriter");
			}
		}
		
		//die( json_encode($this->data->out) );
	}
    
    public function delete($id){
        echo "delete($id);\n";
        $this->songwriters_model->delete($id);
    }
	
	public function search($name)
	{
		$result = $this->songwriters_model->search($name);
		$this->data->songwriters = $result;
	}
    
    public function attr($id, $name){
        $result = $this->songwriters_model->get_attr($id, $name);
        $this->data->attr = $result;
        return $result;
    }
}
