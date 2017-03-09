<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all();
	}
	
	public function all($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['plans']=$this->admin_model->get_pagination_result('plans', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'plans/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('plans', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'plans/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function add(){
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->form_validation->set_rules('name', 'name', 'required');							
		$this->form_validation->set_rules('description', 'description', 'required');							
		$this->form_validation->set_rules('price', 'price', 'required|numeric');							
								
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug('plans', $this->input->post('name')),	
				'description'=>$this->input->post('description'),
				'price'=>$this->input->post('price'),
				'created' => date('Y-m-d H:i:s')			
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();		
			
			$this->admin_model->insert('plans',$data);		
			$this->session->set_flashdata('success_msg',"Plan has been added successfully.");
			redirect(_INDEX.'plans/all');
		}

		$data['template'] = 'plans/add';
        $this->load->view('templates/admin_template', $data);		
	}

	public function edit($slug = ""){
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($slug == "")
			redirect(_INDEX.'plans/all');

		$data['plan'] = $this->admin_model->get_row('plans', array('slug'=>$slug));
		
		$this->form_validation->set_rules('name', 'name', 'required');							
		$this->form_validation->set_rules('description', 'description', 'required');							
		$this->form_validation->set_rules('price', 'price', 'required|numeric');							
		
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'name'=>$this->input->post('name'),
				'slug'=>create_slug_for_update('plans', $this->input->post('name'), $data['plan']->id),
				'description'=>$this->input->post('description'),
				'price'=>$this->input->post('price'),
				'updated' => date('Y-m-d H:i:s')		
			);

			$update['lastupdated'] = time();			
				
			$this->admin_model->update('plans',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"Plan has been updated successfully.");
			redirect(_INDEX.'plans/all');
		}

		$data['template'] = 'plans/edit';
        $this->load->view('templates/admin_template', $data);		
	}
	public function delete($slug=""){	
		$this->admin_model->delete('plans',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"Plan has been deleted successfully.");
		redirect(_INDEX.'plans/all');
	}
}