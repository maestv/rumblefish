<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pro extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("pro_model");
		$this->load->model("publishers_model");
		$this->load->model("songwriters_model");
	}
	
	public function index()
	{
	
	}
	
	public function get($id = false)
	{		
		if ( $id == false ) {
			$this->data->pro = $this->pro_model->get_all();
		}
		else {
			$this->data->pro = $this->pro_model->get_pro($id);
		}	
	}
	
	public function update()
	{
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post )
		{
			if ( empty($post['id']) ) {
				$this->data->error = "You cant update an unspecified Pro!";
			}
            // Update
            $form = array(
                'id' => $post['field-pro-id'],
                'name' => $post['field-pro-name'],
                'user_id' => $this->data->user->id,
            );
            

			$updated = $this->pro_model->update($form);
            if($updated != Null){
    			$this->data->message = "Pro Updated Successfully!";
                $this->data->new_pro = $updated;
                $this->data->all_pros = $this->pro_model->get();
                $this->data->all_publishers = $this->publishers_model->get_all();
                $this->data->songwriters = $this->songwriters_model->get_user($form['user_id']);
                //die(print_r($this->data));
            }else{
                $this->data->message = "Could not update PRO";
                //die("NEW PRO IS EMPTY");
            }
            $this->render_view();
		}else{
		    $this->data->message = "Something happened to the PRO";
		    //die("SOMETHING HAPPENED");
		}
	}

    public function add()
    {
        $post = $this->input->post(null, true);

        if ($post) {
            $search = $this->pro_model->get_name($post['name'], $this->data->user->id);
            if (!$search) {
                // Add new
                $form = array(
                    'name' => $post['field-new-pro-name'],
                    'user_id' => $this->data->user->id,
                );
                $newpro = $this->pro_model->create($form);
                $this->data->newpro->pro = $newpro;
                $this->data->all_pros = $this->pro_model->get();
                $this->data->all_publishers = $this->publishers_model->get_all();
                $this->data->songwriters = $this->songwriters_model->get_user($user_id);
            }
            // Else it already exists--do nothing
        }else{
            $this->data->message = "Songwriter already exists";
        }
        $this->render_view();
    }
	
	public function create()
	{
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post )
		{
			$pro = $this->pro_model->create($post);
			if ( $pro ) {
				$this->data->pro = $pro;
			} 
			else {
				$this->data->error = "Unable to create Pro";
			}
		}
	}
	
    public function delete($id){
        echo "delete($id);\n";
        $this->pro_model->delete($id);
    }
    
	public function search($name)
	{
		$result = $this->pro_model->search($name);
		$this->data->pro = $result;
	}
}
