<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Circuit extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$limit=10;
		
		$data['circuit'] = $this->admin_model->get_pagination_result('default_circuit', $limit, $offset, array('trainer_id' => get_trainer_id()));
		// print_r($data);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'circuit/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('default_circuit', 0, 0, array('trainer_id' => get_trainer_id()));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'circuit/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('name', 'name', 'required');							
		$this->form_validation->set_rules('description', 'circuit_description', 'required');										
		
		if ($this->form_validation->run() == TRUE){
			$data=array(
				'name'=>$this->input->post('name'),
				'slug' => create_slug('default_circuit', $this->input->post('name')),										
				'description'=>$this->input->post('circuit_description'),
				'trainer_id'=>get_trainer_id(),							
				'created' => date('Y-m-d H:i:s')		
			);

			$circuit_id = $this->admin_model->insert('default_circuit',$data);		
			
			$this->add_circuit($circuit_id);

			$this->session->set_flashdata('success_msg',"Circuit has been added successfully.");
			redirect(_INDEX.'circuit/all');
		}

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$this->db->order_by('name', 'asc');
		$data['default_exercises'] = $this->admin_model->get_result('default_exercises', array('trainer_id' => get_trainer_id()));

		if(!$data['default_exercises']){
			$this->session->set_flashdata('error_msg',"Please add system exercise first. Then try to add system circuits.");
			redirect(_INDEX.'exercise/all');
		}

		$data['template'] = 'circuit/add';
        $this->load->view('templates/trainer_template', $data);		
	}

	private function add_circuit($circuit_id=0){
		$default_exercise_id = $_POST['default_exercise_id'];
		$exercise = $_POST['exercise'];
		$description = $_POST['description'];
		$image = $_FILES['image_exercise']['name'];
		$image_temp = $_FILES['image_exercise']['tmp_name'];
		$exerciseimg = $_POST['exerciseimg'];

		for($i=1;$i<=count($exercise);$i++){
			$exercise_data = array(
				'circuit_id'			=> $circuit_id,
				'name'					=> $exercise[$i-1],
				'default_exercise_id'	=> $default_exercise_id[$i-1],
				'description'			=> $description[$i-1],
				'image'					=> '',
				'created'				=> date("Y-m-d H:i:s")
			);

			if(empty($image[$i-1]) && $exerciseimg[$i-1] != ""){					
				$exercise_data['image'] = $exerciseimg[$i-1];
			}else{
				$file = upload_exercise_to_bucket_server($image[$i-1], $image_temp[$i-1], 'e_');
				if($file['status']){
					$exercise_data['image'] = $file['filename'];
				}
			}

            $circuit_exercise_id = $this->admin_model->insert('default_circuit_exercise', $exercise_data);

			if($this->input->post('sets'.$i)){
				foreach($this->input->post('sets'.$i) as $key => $var){
					$set_data = array(
						'circuit_exercise_id'	=> $circuit_exercise_id,
						'time'					=> @$_POST['time'.$i][$key],
						'value'					=> $var,
						'reps'					=> @$_POST['reps'.$i][$key],
						'created'				=> date("Y-m-d H:i:s")
					);
					$this->admin_model->insert('default_circuit_exercise_set', $set_data);
				}
			}
		}
		return TRUE;
	}

	public function edit($slug=""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('name', 'name', 'required');							
		$this->form_validation->set_rules('description', 'circuit_description', 'required');

		$data['circuit'] = $this->admin_model->get_circuit($slug);
		// echo "<pre>";
		// print_r($data['circuit']);
		// die();

		if (empty($data['circuit'])) {
			$this->session->set_flashdata('error_msg',"No circuit found.");
			redirect(_INDEX.'circuit/all');
		}

		if ($this->form_validation->run() == TRUE){
			$this->admin_model->delete_circuit($data['circuit']['circuit_slug']);
			$circuit_id = $data['circuit']['circuit_id'];

			$data=array(
				'id'=>$circuit_id,
				'name'=>$this->input->post('name'),
				'slug' => create_slug('default_circuit', $this->input->post('name')),										
				'description'=>$this->input->post('circuit_description'),
				'trainer_id'=>get_trainer_id(),							
				'created' => date('Y-m-d H:i:s')		
			);

			$this->admin_model->insert('default_circuit',$data);		
			
			$this->add_circuit($circuit_id);

			$this->session->set_flashdata('success_msg',"Circuit has been updated successfully.");
			redirect(_INDEX.'circuit/all');
		}

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$this->db->order_by('name', 'asc');
		$data['default_exercises'] = $this->admin_model->get_result('default_exercises', array('trainer_id' => get_trainer_id()));

		if(!$data['default_exercises']){
			$this->session->set_flashdata('error_msg',"Please add system exercise first. Then try to add system circuits.");
			redirect(_INDEX.'exercise/all');
		}

		$data['template'] = 'circuit/edit';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function delete($slug=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->admin_model->delete_circuit($slug);
		
		$this->session->set_flashdata('success_msg',"Circuit has been deleted successfully.");
		redirect(_INDEX.'circuit/all');
	}

	public function add_exercise_box(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$id = $this->input->post('id');

		$data['exercise'] = $this->admin_model->get_row('default_exercises', array( 'id' => $id , 'trainer_id' => get_trainer_id()));
			
		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		if($data['exercise']){
			echo $this->load->view('circuit/add_exercise_box', $data, TRUE);
		}
		else{
			echo 0;
		}
	}
}