<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index(){
		$data['menuactive'] = '';
		$data['pagetitle'] = 'Homepage';

		$data['slides'] = $this->admin_model->get_result('slider');
		$data['neighbor'] = $this->admin_model->get_row('home_neighbour', array('id'=>1));
		$data['listing'] = $this->admin_model->get_limited_results('properties',3);
		
		$data['template'] = 'home/index';
		$this->load->view('templates/home_template', $data);
	}

	public function neighborhood(){
		 if(admin_login_in()===FALSE)
			redirect('login');		
		$data['neighbor'] = $this->admin_model->get_row('home_neighbour');
		$this->form_validation->set_rules('title', 'title', 'required');				
		$this->form_validation->set_rules('excerpt', 'excerpt', 'required');						
		$this->form_validation->set_rules('description', 'description', 'required');						
		if ($this->form_validation->run() == TRUE){			
			$updatedata=array(								
				'title'=>$this->input->post('title'),				
				'excerpt'=>$this->input->post('excerpt'),				
				'description'=>$this->input->post('description'),								
			);

			if($_FILES['userfile']['name']!=''){
				$config['upload_path'] = './assets/uploads/home/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '';
				$this->load->library('upload', $config);
				if (! $this->upload->do_upload()){
					$this->session->set_flashdata('error_msg', $this->upload->display_errors());
					redirect('home/neighborhood');
				}else{
				   $upload_data = $this->upload->data();			
				   $updatedata['image']=$upload_data['file_name'];
				   create_thumb($updatedata['image'], './assets/uploads/home/');
				}
			}

			$updatedata['lastupdated'] = time();		
			
			$this->admin_model->update('home_neighbour',$updatedata, array('id'=>'1'));		
			$this->session->set_flashdata('success_msg',"Content has been updated successfully.");
			redirect('home/neighborhood');
		}

		$data['template'] = 'home/neighborhood';
        $this->load->view('templates/admin_template', $data);		
	}

	public function success($invoice = ''){
		echo $invoice."--> PAYMENT SUCCESSFULL.";
	}

	public function cancel($invoice = ''){
		echo $invoice."--> PAYMENT FAILED.";
	}
}