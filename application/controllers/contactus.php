<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index($service_slug = ''){
		$data['menuactive'] = 'contactus';
		$data['pagetitle'] = 'Contact Us';

		$this->form_validation->set_rules('fname', 'first name', 'required');						
		$this->form_validation->set_rules('lname', 'last name', 'required');						
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');						
		$this->form_validation->set_rules('city', 'city', 'required');						
		$this->form_validation->set_rules('state', 'state', 'required');						
		$this->form_validation->set_rules('zip', 'zip', 'required');						
		$this->form_validation->set_rules('address', 'address', 'required');						
		$this->form_validation->set_rules('information', 'information', 'required');						
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'name'=>$this->input->post('fname').' '.$this->input->post('lname'),				
				'email'=>$this->input->post('email'),							
				'address'=>$this->input->post('address'),							
				'zip'=>$this->input->post('zip'),							
				'city'=>$this->input->post('city'),							
				'state'=>$this->input->post('state'),							
				'information'=>$this->input->post('information'),							
				'phone'=> $this->input->post('phone'),
				'created' => date('Y-m-d H:i:s')		
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();		

			$this->admin_model->insert('contact_us', $data);
			$this->session->set_flashdata('success_msg','successfully submitted');
			redirect(cms_current_url());
		}
		
		$data['services'] = $this->admin_model->get_result('services');
		$data['service_slug'] = $service_slug;
		$data['template'] = 'contactus/index';
		$this->load->view('templates/home_template', $data);
	}


	public function all($offset=0){		
		if(admin_login_in()===FALSE)
			redirect('login');

		$limit=10;
		$data['contactus']=$this->admin_model->get_pagination_result('contact_us', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'contactus/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('contact_us', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'contactus/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function view($id=''){
		if(admin_login_in()===FALSE)
			redirect('login');
		if($id == "")
			redirect('contactus/all');

		$data['detail'] = $this->admin_model->get_row('contact_us', array('id'=>$id));
		$data['template'] = 'contactus/view';
        $this->load->view('templates/admin_template', $data);			
	}

	public function delete($id=""){
		if(admin_login_in()===FALSE)
			redirect('login');
		if($id == "")
			redirect('contactus/all');

		$this->admin_model->delete('contact_us', array('id'=>$id));		
		$this->session->set_flashdata('success_msg','successfully Deleted');
			redirect('contactus/all');
	}
}