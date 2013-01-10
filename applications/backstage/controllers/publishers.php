<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Publishers extends Core_Controller {
	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model("publishers_model");
        $this->load->model("pro_model");
	}
	
	public function index()
	{
	
	}
	
	public function get($id = false)
	{		
		if ( $id == false ) {
			$this->data->publishers = $this->publishers_model->get_all();
            $this->data->publishers->all_pros = $this->pro_model->get();
		}
		else {
			$this->data->publishers = $this->publishers_model->get_publisher($id);
            $this->data->publishers->all_pros = $this->pro_model->get();
		}
	}
	
	public function update()
	{
        $post = $this->input->post(null, true);
        
        if ( $post )
        {
            $form = array(
              'id' => $post['field-publisher-id'],
              'name' => $post['field-publisher-name'],
              'pro_id' => $post['field-publisher-pro'],
              'user_id' => $this->data->user->id,
            );
          //print_r($form);
          //die();
            $publishers = $this->publishers_model->update($form);
            foreach ($publishers as $key => $value) {
                $this->data->$key = $value;
            }
            $this->data->all_pros = $this->pro_model->get();
            $this->data->publishers = $publishers;
            $this->data->all_publishers = $this->publishers_model->get_all();
            if ($this->data->publishers == false){
                $this->data->message = "Publisher error";
            }
            $this->render_view();
        }
	}
    
    public function add(){
        $post = $this->input->post(null, true);

        if ($post) {
            $search = $this->publishers_model->get_name($post['name'], $this->data->user->id);
            if (!$search) {
               // Add new
                $form = array(
                    'name' => $post['field-new-publisher-name'],
                    'pro_id' => $post['field-new-publisher-pro'],
                    'user_id' => $this->data->user->id,
                );
                $newpub = $this->publishers_model->create($form);
                $this->data->newpublisher->songwriter = $newsw;
                $this->data->newpublisher->pro = $this->pro_model->get_pro($form['pro_id']);
                $this->data->newpublisher->publisher = $this->publishers_model->get($form['publisher_id']);
                $this->data->newpublisher->all_pros = $this->pro_model->get();
                $this->data->all_pros = $this->pro_model->get();
                $this->data->all_publishers = $this->publishers_model->get_all();
            }
            // Else it already exists--do nothing
        }else{
            $this->data->message = "Publisher already exists";
        }
        $this->render_view();
    }
    
    public function delete($id){
        echo "delete($id);\n";
        $this->publishers_model->delete($id);
    }
	
	public function create()
	{
		$post = false;
		$post = $this->input->post(null, true);
		
		if ( $post )
		{
			$songwriter = $this->publishers_model->create($post);
			if ( $songwriter ) {
				$this->data->publishers = $songwriter;
			} 
			else {
				$this->data->error = "Unable to create Publisher";
			}
		}
	}
	
	public function search($name)
	{
		$result = $this->publishers_model->search($name);
		$this->data->publishers = $result;
	}
}
