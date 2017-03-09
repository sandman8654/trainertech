<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
		$this->load->model('admin_model');
	}
	
	public function changePassword()
	{
		$admin = $this->session->userdata('AdminInfo');	
		$id = $admin['id'];
		$this->form_validation->set_rules('old', 'old password', 'required|callback_check_old_pwd');							
		$this->form_validation->set_rules('new', 'new password', 'required');							
		$this->form_validation->set_rules('con', 'confirm password', 'required|matches[new]');							
		if ($this->form_validation->run() == TRUE)
		{			
			$new = $this->input->post('new');
			$new = sha1(trim($new));

			$this->admin_model->update('users',array('password' => $new, 'lastupdated' => time()), array('id'=>$id));
			$this->session->set_flashdata('success_msg','Password has been changed successfully.');
			redirect(_INDEX.'settings/changePassword');
		}

		$data['template'] = 'settings/changePassword';
        $this->load->view('templates/admin_template', $data);		
	}

 	public function  check_old_pwd($x)
 	{
		$admin = $this->session->userdata('AdminInfo');	
		$id = $admin['id'];
		$pwd=sha1($x);
		$row = $this->admin_model->get_row('users', array('id'=> $id));
		if($row->password == $pwd)
		{
			return TRUE;
		} 
		else
		{
			$this->form_validation->set_message('check_old_pwd','Please enter correct old password');
			return FALSE;
		}
 	}





}