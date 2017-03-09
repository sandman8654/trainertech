<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index()
	{
		redirect(base_url());
	}

	public function trainee()
	{
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$admin = $this->session->userdata('traineeInfo');
		$id = $admin['id'];
		
		$data['users'] = $this->admin_model->get_row('trainee', array('id' => $id) );
		
		$this->form_validation->set_rules('fname', 'first name', 'required');							
		$this->form_validation->set_rules('lname', 'last name', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_trainee_old_email');							
		$this->form_validation->set_rules('phone', 'phone', 'required');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');						
		$this->form_validation->set_rules('state', 'state', 'required');							
		$this->form_validation->set_rules('zip', 'zip', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'slug'				=>create_slug_for_update('trainee', $this->input->post('fname').' '.$this->input->post('lname'), $data['users']->id),
				'fname'				=>$this->input->post('fname'),
				'lname'				=>$this->input->post('lname'),
				'email'				=>$this->input->post('email'),
				'phone'				=>$this->input->post('phone'),
				'address'			=>$this->input->post('address'),
				'city'				=>$this->input->post('city'),
				'state'				=>$this->input->post('state'),
				'zip'				=>$this->input->post('zip'),
				'updated' 			=> date('Y-m-d H:i:s')		
			);

			if($_FILES['userfile']['name'] != ''){
				$file = upload_to_bucket_server('userfile', 't_');
				if($file['status']){
					delete_from_bucket_server($data['users']->image);
					$update['image'] = $file['filename'];
				}
				else{
					$this->session->set_flashdata('error_msg', $file['error']);
					redirect(cms_current_url());
				}
			}

			$update['lastupdated'] = time();

			$this->admin_model->update('trainee', $update, array('id'=>$id));

			$this->session->set_flashdata('success_msg', 'Profile updated successfully.');
			redirect(cms_current_url());
		}
		$data['template'] = 'profile/trainee';
        $this->load->view('templates/trainee_template', $data);		
	}

	public function check_trainee_old_email($x)
 	{
		$admin = $this->session->userdata('traineeInfo');	
		$id = $admin['id'];
		$row = $this->admin_model->get_row('trainee', array('email'=> $x));
		if($row){
			if( $row->id != $id ){
				$this->form_validation->set_message('check_trainee_old_email','This email already exist.');
				return FALSE;
			}
		} 
		
		return TRUE;
	}

	public function trainer()
	{
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$admin = $this->session->userdata('trainerInfo');
		$id = $admin['id'];
		
		$data['users'] = $this->admin_model->get_row('trainer', array('id' => $id) );
		
		$this->form_validation->set_rules('fname', 'first name', 'required');							
		$this->form_validation->set_rules('lname', 'last name', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_trainer_old_email');							
		$this->form_validation->set_rules('phone', 'phone', 'required');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');						
		$this->form_validation->set_rules('state', 'state', 'required');							
		$this->form_validation->set_rules('zip', 'zip', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'slug'				=>create_slug_for_update('trainer', $this->input->post('fname').' '.$this->input->post('lname'), $data['users']->id),
				'fname'				=>$this->input->post('fname'),
				'lname'				=>$this->input->post('lname'),
				'email'				=>$this->input->post('email'),
				'phone'				=>$this->input->post('phone'),
				'address'			=>$this->input->post('address'),
				'city'				=>$this->input->post('city'),
				'state'				=>$this->input->post('state'),
				'zip'				=>$this->input->post('zip'),
				'updated' 			=> date('Y-m-d H:i:s')		
			);

			$data['lastupdated'] = time();

			$this->admin_model->update('trainer', $data, array('id'=>$id));

			$this->session->set_flashdata('success_msg', 'Profile updated successfully.');
			redirect(cms_current_url());
		}
		$data['template'] = 'profile/trainer';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function check_trainer_old_email($x)
 	{
		$admin = $this->session->userdata('trainerInfo');	
		$id = $admin['id'];
		$row = $this->admin_model->get_row('trainer', array('email'=> $x));
		if($row){
			if( $row->id != $id ){
				$this->form_validation->set_message('check_trainer_old_email','This email already exist.');
				return FALSE;
			}
		} 
		
		return TRUE;
	}

	public function admin()
	{
		if(admin_login_in()===FALSE){
			redirect(_INDEX.'login');
		}

		$admin = $this->session->userdata('AdminInfo');
		$id = $admin['id'];
		$data['users'] = $this->admin_model->get_row('users', array('id' => $id) );
		$data['users_profile'] = $this->admin_model->get_row('users_profile', array('user_id' => $id) );
		
		$this->form_validation->set_rules('fname', 'first name', 'required');							
		$this->form_validation->set_rules('lname', 'last name', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_old_email');							
		$this->form_validation->set_rules('phone', 'phone', 'required');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');						
		$this->form_validation->set_rules('state', 'state', 'required');							
		$this->form_validation->set_rules('zip', 'zip', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'fname'				=>$this->input->post('fname'),
				'lname'				=>$this->input->post('lname'),
				'phone'				=>$this->input->post('phone'),
				'address'			=>$this->input->post('address'),
				'city'				=>$this->input->post('city'),
				'state'				=>$this->input->post('state'),
				'zip'				=>$this->input->post('zip'),
				'updated' 			=> date('Y-m-d H:i:s')		
			);

			$data['lastupdated'] = time();

			$this->admin_model->update('users_profile', $data, array('id'=>$id));

			$data=array(
				'email'				=>$this->input->post('email'),
				'updated' 			=> date('Y-m-d H:i:s')		
			);

			$data['lastupdated'] = time();
			$this->admin_model->update('users', $data, array('id'=>$id));

			$this->session->set_flashdata('success_msg', 'Profile updated successfully.');
			redirect(cms_current_url());
		}
		$data['template'] = 'profile/admin';
        $this->load->view('templates/admin_template', $data);		
	}

	public function check_old_email($x)
 	{
		$admin = $this->session->userdata('AdminInfo');	
		$id = $admin['id'];
		$row = $this->admin_model->get_row('users', array('email'=> $x));
		if($row){
			if( $row->id != $id ){
				$this->form_validation->set_message('check_old_email','This email already exist');
				return FALSE;
			}
		} 
		
		return TRUE;
	}

/**
		MANAGER SECTION
*/

	public function manager()
	{
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');
		}
		$id = get_manager_id();
		$data['users'] = $this->admin_model->get_row('manager', array('id' => $id) );
		$this->form_validation->set_rules('fname', 'first name', 'required');							
		$this->form_validation->set_rules('lname', 'last name', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_manager_old_email');							
		$this->form_validation->set_rules('phone', 'phone', 'required');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');						
		$this->form_validation->set_rules('state', 'state', 'required');							
		$this->form_validation->set_rules('zip', 'zip', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'slug'				=>create_slug_for_update('manager', $this->input->post('fname').' '.$this->input->post('lname'), $data['users']->id),
				'fname'				=>$this->input->post('fname'),
				'lname'				=>$this->input->post('lname'),
				'email'				=>$this->input->post('email'),
				'phone'				=>$this->input->post('phone'),
				'address'			=>$this->input->post('address'),
				'city'				=>$this->input->post('city'),
				'state'				=>$this->input->post('state'),
				'zip'				=>$this->input->post('zip'),
				'updated' 			=> date('Y-m-d H:i:s')		
			);

			$data['lastupdated'] = time();

			$this->admin_model->update('manager', $data, array('id'=>$id));

			$this->session->set_flashdata('success_msg', 'Profile updated successfully.');
			redirect(cms_current_url());
		}
		$data['template'] = 'profile/manager';
        $this->load->view('templates/manager_template', $data);		
	}

	public function check_manager_old_email($x)
 	{
		$manager = $this->session->userdata('managerInfo');	
		$id = $manager['id'];
		$row = $this->admin_model->get_row('manager', array('email'=> $x));
		if($row){
			if( $row->id != $id ){
				$this->form_validation->set_message('check_manager_old_email','This email already exist.');
				return FALSE;
			}
		} 
		return TRUE;
	}



}