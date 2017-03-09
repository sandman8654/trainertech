<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exercise extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	// public function index($slug=""){
	// 	if($slug == ""){
	// 		redirect(_INDEX.'home');
	// 	}
	// 	$data['menuactive'] = 'exercise';
	// 	$data['exercisetitle'] = 'exercise';		
	// 	$data['exercise'] = $this->admin_model->get_row('default_exercises', array("slug" => $slug));
	// 	$data['template'] = 'exercise/index';
	// 	$this->load->view('templates/home_template', $data);
	// }	

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$limit=10;
		
		$data['exercise']=$this->admin_model->get_pagination_result('default_exercises', $limit, $offset, array('trainer_id' => get_trainer_id()));
		// print_r($data);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'exercise/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('default_exercises', 0, 0, array('trainer_id' => get_trainer_id()));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'exercise/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('name', 'name', 'required|min_length[3]|max_length[25]');							
		$this->form_validation->set_rules('description', 'description', 'required');										
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'name'=>$this->input->post('name'),
				'slug' => create_slug('default_exercises', $this->input->post('name')),										
				'description'=>$this->input->post('description'),
				'trainer_id'=>get_trainer_id(),							
				'created' => date('Y-m-d H:i:s')		
			);

			if(isset($_FILES['exercise_image']['name']) && $_FILES['exercise_image']['name']!=""){
				$file = upload_to_bucket_server('exercise_image', 'd_');
				if($file['status']){
					$data['image'] = $file['filename'];
				}else{
					$this->session->set_flashdata('error_msg', 'File not uploaded.');
					redirect(current_url());
				}
			}
			else{
				$data['image'] = $this->input->post('ex_img');
			}


			// $data['token'] = get_token();
			// $data['lastupdated'] = time();		
			
			$this->admin_model->insert('default_exercises',$data);		
			$this->session->set_flashdata('success_msg',"Exercise has been added successfully.");
			redirect(_INDEX.'exercise/all');
		}

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$data['template'] = 'exercise/add';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function edit($slug=""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('name', 'name', 'required|min_length[3]|max_length[25]');							
		$this->form_validation->set_rules('description', 'description', 'required');

		$data['exercise'] = $this->admin_model->get_row('default_exercises', array('slug' => $slug , 'trainer_id' => get_trainer_id()));

		if (empty($data['exercise'])) {
			$this->session->set_flashdata('error_msg',"No exercise found.");
			redirect(_INDEX.'exercise/all');
		}

		if ($this->form_validation->run() == TRUE){			
			$updatedata=array(
				'name'=>$this->input->post('name'),
				'slug' => create_slug_for_update('default_exercises', $this->input->post('name'), $data['exercise']->id),											
				'description'=>$this->input->post('description')
			);

			if(isset($_FILES['exercise_image']['name']) && $_FILES['exercise_image']['name']!=""){

				$file = upload_to_bucket_server('exercise_image', 'd_');
				if($file['status']){
					delete_from_bucket_server($data['exercise']->image);
					$updatedata['image'] = $file['filename'];
				}else{
					$this->session->set_flashdata('error_msg', 'File not uploaded.');
					redirect(current_url());
				}
			}
			else{
				$updatedata['image'] = $this->input->post('ex_img');
			}		

			// $updatedata['lastupdated'] = time();			

			$this->admin_model->update('default_exercises',$updatedata, array('slug'=>$slug));		
			$this->session->set_flashdata('success_msg',"Exercise has been updated successfully.");
			redirect(_INDEX.'exercise/all');
		}

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$data['template'] = 'exercise/edit';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function delete($slug=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->admin_model->delete('default_exercises',array('slug' => $slug , 'trainer_id' => get_trainer_id()));		
		$this->session->set_flashdata('success_msg',"exercise has been deleted successfully.");
		redirect(_INDEX.'exercise/all');
	}
}