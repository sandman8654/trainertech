<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_group extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all();
	}
	
	public function all($parent_slug = '', $offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$group_id = slug_to_id('group', $parent_slug);

		$limit=10;
		$data['sub_group']=$this->admin_model->get_pagination_result('sub_group', $limit,$offset, array('group_id' => $group_id));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'sub_group/all/'.$parent_slug.'/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('sub_group', 0, 0, array('group_id' => $group_id));
		$config['per_page'] = $limit;
		$config['uri_segment'] = 3;	
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();

		$data['parent_slug'] = $parent_slug;

        $data['template'] = 'sub_group/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add($parent_slug=''){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->form_validation->set_rules('name', 'name', 'required');							
								
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug('sub_group', $this->input->post('name')),
				'group_id'=> slug_to_id('group', $parent_slug),	
				'created' => date('Y-m-d H:i:s')			
			);		
			
			$data['token'] = get_token();
			$data['lastupdated'] = time();

			$this->admin_model->insert('sub_group',$data);		
			$this->session->set_flashdata('success_msg',"Sub-group has been added successfully.");
			redirect(_INDEX.'sub_group/all/'.$parent_slug);
		}

		$data['template'] = 'sub_group/add';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function edit($slug = ""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($slug == "")
			redirect(_INDEX.'sub_group/all');

		$data['sub_group'] = $this->admin_model->get_row('sub_group', array('slug'=>$slug));
		
		$this->form_validation->set_rules('name', 'name', 'required');							
		
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug_for_update('sub_group', $this->input->post('name'), $data['sub_group']->id),
				'updated' => date('Y-m-d H:i:s')		
			);			
			
			$update['lastupdated'] = time();

			$this->admin_model->update('sub_group',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"Sub-group has been updated successfully.");
			redirect(_INDEX.'sub_group/all/'.id_to_slug('group', $data['sub_group']->group_id));
		}

		$data['template'] = 'sub_group/edit';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function delete($slug=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$sub_group = $this->admin_model->get_row('sub_group', array('slug'=>$slug));

		$this->admin_model->delete('sub_group',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"Sub-group has been deleted successfully.");
		redirect(_INDEX.'sub_group/all/'.id_to_slug('group', $sub_group->group_id));
	}

	public function alltrainee($sgroup_id, $offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['member']=$this->admin_model->get_subgroupmember($sgroup_id, $limit,$offset, array('trainer_id'=>get_trainer_id()));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'sub_group/alltrainee/';
		$config['total_rows'] = $this->admin_model->get_subgroupmember($sgroup_id, 0, 0, array('trainer_id'=>get_trainer_id()));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
		$data['subgroup_id'] = $sgroup_id;
        $data['template'] = 'sub_group/alltrainee';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function addtrainee($sg_id=""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($sg_id=="")
			redirect(_INDEX.'group/all');

		$trainer_id = get_trainer_id();	
		$group_members = $this->admin_model->get_result('group_members');						
		if ($_POST){				
			$trainee_arr = array();
			$sgroup_arr  = array();
			foreach ($group_members  as $key ) {
				$trainee_arr[] = $key->trainee_id;
				$sgroup_arr[] = $key->subgroup_id;
			}
			if ($_POST['trainee'][0] !=""){			
				$post_arr = $_POST['trainee'];				
				$postdata = array('subgroup_id' =>$sg_id);	
				foreach ($post_arr as $key => $value) {	
				
				//if(in_array($value,$trainee_arr) && in_array($sg_id, $sgroup_arr))
				if(in_array($value,$trainee_arr))
					continue;	

					$postdata['trainee_id'] = $value;

					$postdata['token'] = get_token();
					$postdata['lastupdated'] = time();

					$this->admin_model->insert('group_members',$postdata);							
				}
				$this->session->set_flashdata('success_msg',"Group has been added successfully.");
				redirect(_INDEX.'sub_group/alltrainee/'.$sg_id);
			}else{
				$this->session->set_flashdata('error_msg',"please select Trainee");
				redirect(cms_current_url());
			}
		}


		
		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>$trainer_id));		
		$data['sub_group_id'] = $sg_id;
		$data['template'] = 'sub_group/addtrainee';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function edittrainee($id = ""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($id == "")
			redirect(_INDEX.'group/alltrainee');

		$data['group'] = $this->admin_model->get_row('group', array('id'=>$id));
		
		$this->form_validation->set_rules('name', 'name', 'required');							
		
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'name'=>$this->input->post('name'),				
				'trainer_id'=>get_trainer_id(),	
				'updated' => date('Y-m-d H:i:s')		
			);

			$update['lastupdated'] = time();			
				
			$this->admin_model->update('group',$update, array('id'=>$id));
			$this->session->set_flashdata('success_msg',"Group has been updated successfully.");
			redirect(_INDEX.'group/alltrainee');
		}

		$data['template'] = 'group/edittrainee';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function deletetrainee($id="", $sg_id){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->admin_model->delete('group_members',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"Trainee has been deleted successfully.");
		redirect(_INDEX.'sub_group/alltrainee/'.$sg_id);
	}
}