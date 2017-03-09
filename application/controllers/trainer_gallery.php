<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trainer_gallery extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$limit=10;
		$data['gallery'] =$this->admin_model->get_pagination_result('gallery', $limit, $offset, array('trainer_id'=>get_trainer_id()));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainer_gallery/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('gallery', 0, 0, array('trainer_id'=>get_trainer_id()));
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'trainer_gallery/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('submit', 'submit', 'required');		
		
		if ($this->form_validation->run() == TRUE){
			$data = array(
				'image' => '',
				'trainer_id' => get_trainer_id(),
				'created' => date("Y-m-d H:i:s")
			);

			if(isset($_FILES['gallery_image']['name']) && $_FILES['gallery_image']['name']!=""){
				$file = upload_to_bucket_server('gallery_image', 'g_');				
				if($file['status']){
					$data['image'] = $file['filename'];
				}
				else{
					$this->session->set_flashdata('error_msg', 'File not uploaded.');			
					redirect(_INDEX.'trainer_gallery/add');
				}
				$this->admin_model->insert('gallery',$data);		
				$this->session->set_flashdata('success_msg',"Image has been added successfully.");
				redirect(_INDEX.'trainer_gallery/all');
			}else{
				$this->session->set_flashdata('error_msg',"Please select image");
				redirect(_INDEX.'trainer_gallery/add');
			}
		}

		$data['template'] = 'trainer_gallery/add';
        $this->load->view('templates/trainer_template', $data);		
	}	

	public function delete($id=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->admin_model->delete('gallery', array( 'id' => $id));		
		$this->session->set_flashdata('success_msg',"Image has been deleted successfully.");
		redirect(_INDEX.'trainer_gallery/all');
	}
}