<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all();
	}
	
	public function sort_group(){
		$this->session->set_userdata('sort_group',$_POST);
		redirect(_INDEX.'group/all');
	}

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');
		$limit=10;
		$where = array();
        if($this->session->userdata('sort_group')){
        	$where = $this->session->userdata('sort_group');
        }
		$data['group']=$this->admin_model->manage_groups($limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'group/all/';
		$config['total_rows'] = $this->admin_model->manage_groups(0,0,$where);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'group/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->form_validation->set_rules('name', 'name', 'required');	
		$data['groups'] = $this->admin_model->get_result('group',array('parent_id'=>0));						
								
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug('group', $this->input->post('name')),
				'trainer_id'=>get_trainer_id(),
				'parent_id'=>$this->input->post('group_id'),		
				'created' => date('Y-m-d H:i:s')			
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();
			
			$this->admin_model->insert('group',$data);		
			$this->session->set_flashdata('success_msg',"Group has been added successfully.");
			redirect(_INDEX.'group/all');
		}

		$data['template'] = 'group/add';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function edit($slug = ""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($slug == "")
			redirect(_INDEX.'group/all');

		$data['group'] = $this->admin_model->get_row('group', array('slug'=>$slug));
		
		$this->form_validation->set_rules('name', 'name', 'required');							
		
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug_for_update('group', $this->input->post('name'), $data['group']->id),
				'trainer_id'=>get_trainer_id(),	
				'updated' => date('Y-m-d H:i:s')		
			);

			$update['lastupdated'] = time();			
				
			$this->admin_model->update('group',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"Group has been updated successfully.");
			redirect(_INDEX.'group/all');
		}

		$data['template'] = 'group/edit';
        $this->load->view('templates/trainer_template', $data);		
	}



	public function delete($slug=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$row = $this->admin_model->get_row('group',array('slug'=> $slug));
		
		$this->admin_model->delete('group',array('slug'=> $slug));
		
		$this->admin_model->delete('group_members', array('group_id'=> $row->id));

        if($row->parent_id == 0){

        	$result = $this->admin_model->get_result('group', array('parent_id'=> $row->id));

			$this->admin_model->delete('group', array('parent_id'=> $row->id));

			foreach($result as $row){
				$this->admin_model->delete('group_members', array('group_id'=> $row->id));
			}
        }

		$this->session->set_flashdata('success_msg',"Group has been deleted successfully.");
		redirect(_INDEX.'group/all');
	}



	public function alltrainee($group_id, $offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$is_parent = $this->admin_model->get_row('group',array('parent_id'=>$group_id));
        
        if($is_parent){
			$this->session->set_flashdata('error_msg',"Cannot add member to a group if it has a sub-group");
			redirect(_INDEX.'group/all');
        }

		$limit=10;
		$data['member']=$this->admin_model->get_groupmember($group_id, $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'group/alltrainee/'.$group_id;
		$config['total_rows'] = $this->admin_model->get_groupmember($group_id, 0, 0);
		$config['per_page'] = $limit;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
		$data['group_id'] = $group_id;
        $data['template'] = 'group/alltrainee';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function addtrainee($group_id=""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($group_id=="")
			redirect(_INDEX.'group/all');

		$trainer_id = get_trainer_id();	
		$group_members = $this->admin_model->get_result('group_members');						
		
		if (isset($_POST['trainee'])){
				$trainee_array = $_POST['trainee'];				
				foreach ($trainee_array as $key => $value) {	

					$insert['trainee_id'] = $value;
					$insert['group_id'] = $group_id;
					$insert['token'] = get_token();
					$insert['lastupdated'] = time();
					$this->admin_model->insert('group_members',$insert);							
				}

				$this->session->set_flashdata('success_msg',"Group has been added successfully.");
				redirect(_INDEX.'group/alltrainee/'.$group_id);
		}


		
		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>$trainer_id));		
		$data['group_trainee'] = $this->admin_model->get_trainer_trainee($trainer_id);		
		// $data['group_trainee'] = $this->admin_model->get_result('group_members', array('group_id'=>$group_id));		
		$data['group_id'] = $group_id;
		$data['template'] = 'group/addtrainee';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function removetrainee($id="", $group_id){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->admin_model->delete('group_members',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"Trainee has been removed successfully.");
		redirect(_INDEX.'group/alltrainee/'.$group_id);
	}


	
}