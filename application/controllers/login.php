<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->admin_login();
	}

	public function admin_login(){
		if(admin_login_in()===TRUE)
			redirect(_INDEX.'admin');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == TRUE){
			$this->load->model('turnskey_model');			
			$status = $this->turnskey_model->login($this->input->post('email'),$this->input->post('password'),1);	
			
			if($status){
				redirect(_INDEX.'admin');
			}
			else{
				redirect(_INDEX.'login/admin_login');
			}
		}

		$this->load->view('login/admin_login');
	}
	
	public function manager_login(){
		if(manager_login_in()===TRUE){
			redirect(_INDEX.'manager');
		}
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == TRUE){
			$this->load->model('manager_model');			
			$status = $this->manager_model->login($this->input->post('email'),$this->input->post('password'));				
			if($status){
				redirect(_INDEX.'manager');
			}
			else{
				redirect(_INDEX.'login/manager_login');
			}
		}
		$this->load->view('login/manager_login');
	}	

	public function trainer_login(){
		if(trainer_login_in()===TRUE)
			redirect(_INDEX.'trainer');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == TRUE){
			$this->load->model('trainer_model');			
			$status = $this->trainer_model->login($this->input->post('email'),$this->input->post('password'));				
			if($status){
				redirect(_INDEX.'trainer');
			}
			else{
				redirect(_INDEX.'login/trainer_login');
			}
		}

		$this->load->view('login/trainer_login');
	}


	public function trainee_login(){
		if(trainee_login_in()===TRUE)
			redirect(_INDEX.'trainee');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run() == TRUE){
			$this->load->model('trainee_model');			
			$status = $this->trainee_model->login($this->input->post('email'),$this->input->post('password'));	
			
			if($status){
				redirect(_INDEX.'trainee');
			}
			else{
				redirect(_INDEX.'login/trainee_login');
			}
		}

		$this->load->view('login/trainee_login');
	}
}