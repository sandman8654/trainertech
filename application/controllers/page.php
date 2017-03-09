<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index($slug=""){
		if($slug == ""){
			redirect(_INDEX.'home');
		}
		$data['menuactive'] = 'page';
		$data['pagetitle'] = 'page';		
		$data['page'] = $this->admin_model->get_row('pages', array("slug" => $slug));
		$data['template'] = 'page/index';
		$this->load->view('templates/home_template', $data);
	}	

	public function all($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['page']=$this->admin_model->get_pagination_result('pages', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'page/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('pages', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'page/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function add(){
		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
		$this->form_validation->set_rules('title', 'title', 'required');							
		$this->form_validation->set_rules('description', 'description', 'required');										
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'title'=>$this->input->post('title'),
				'slug' => create_slug('pages', $this->input->post('title')),										
				'description'=>$this->input->post('description'),							
				'created' => date('Y-m-d H:i:s')		
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();		
			
			$this->admin_model->insert('pages',$data);		
			$this->session->set_flashdata('success_msg',"page has been added successfully.");
			redirect(_INDEX.'page/all');
		}

		$data['template'] = 'page/add';
        $this->load->view('templates/admin_template', $data);		
	}

	public function edit($slug=""){
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->form_validation->set_rules('title', 'title', 'required');						
		$this->form_validation->set_rules('description', 'description', 'required');		
		$data['page'] = $this->admin_model->get_row('pages', array('slug'=> $slug));

		if (empty($data['page'])) {
			$this->session->set_flashdata('error_msg',"No page found.");
			redirect(_INDEX.'page/all');
		}

		if ($this->form_validation->run() == TRUE){			
			$updatedata=array(
				'title'=>$this->input->post('title'),
				'slug' => create_slug_for_update('pages', $this->input->post('title'), $data['page']->id),											
				'description'=>$this->input->post('description'),	
			);

			$updatedata['lastupdated'] = time();			

			$this->admin_model->update('pages',$updatedata, array('slug'=>$slug));		
			$this->session->set_flashdata('success_msg',"page has been updated successfully.");
			redirect(_INDEX.'page/all');
		}		
		$data['template'] = 'page/edit';
        $this->load->view('templates/admin_template', $data);		
	}

	public function delete($slug=""){	
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');	
		$this->admin_model->delete('pages',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"page has been deleted successfully.");
		redirect(_INDEX.'page/all');
	}
}