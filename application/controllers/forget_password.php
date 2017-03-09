<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forget_password extends CI_Controller {
	public function __construct(){
		parent::__construct();
		clear_cache();
		$this->load->model('admin_model');
	}

	public function index(){
		$this->trainee();
	}

	public function check_email($email, $tablename){
		$result = $this->admin_model->get_result($tablename, array('email'=> $email));
		if($result){
			return TRUE;
		} 
		else{
			$this->form_validation->set_message('check_email','This email is not exist in our system.');
			return FALSE;
		}
 	}

	public function admin(){
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_email[users]');							
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		if ($this->form_validation->run() == TRUE){			
			$email = $this->input->post('email');
			$row = $this->admin_model->get_row('users', array('email'=> $email));

			$newPassword = createRandomPassword(12);                
    		$changePasswordKey = sha1(uniqid());
    		$obj = array(
    			'forgot_pwd'		=> sha1($newPassword),
				'forgot_pwd_key'	=> $changePasswordKey
    		);
    	
    		$obj['lastupdated'] = time();
    		
    		$this->db->where('id', $row->id);
    		$this->db->update('users', $obj);

			$subject = 'SportsApp : Forget Password';
			$to = array(
				$row->email
			);

			$profile = $this->admin_model->get_row('users_profile', array('user_id'=> $row->id));
			
			$data['name'] = $profile->fname.' '.$profile->lname;
			$data['new_password'] = $newPassword;
			$data['key'] = $changePasswordKey;
			
			$html = $this->load->view('email/forgot_password', $data, TRUE);
		//	echo $html;
			$this->send_email($subject, $to, $html);

			$this->session->set_flashdata('success_msg', 'A new password has been sent to your registered email-id.');
			redirect(cms_current_url());
		}
		$this->load->view('forget_password/index');
		//$this->load->view('forget_password/admin');
	}

	public function manager(){
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_email[manager]');							
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		if ($this->form_validation->run() == TRUE){			
			$email = $this->input->post('email');
			$row = $this->admin_model->get_row('manager', array('email'=> $email));

			$newPassword = createRandomPassword(12);                
    		$changePasswordKey = sha1(uniqid());
    		$obj = array(
    			'forgot_pwd'		=> sha1($newPassword),
				'forgot_pwd_key'	=> $changePasswordKey
    		);
    		$obj['lastupdated'] = time();
    		$this->db->where('id', $row->id);
    		$this->db->update('manager', $obj);
			$subject = 'SportsApp : Forget Password';
			$to = array(
				$row->email
			);
			$data['name'] = $row->fname.' '.$row->lname;
			$data['new_password'] = $newPassword;
			$data['key'] = $changePasswordKey;
			$html = $this->load->view('email/forgot_password', $data, TRUE);
			$this->send_email($subject, $to, $html);
			$this->session->set_flashdata('success_msg', 'A new password has been sent to your registered email-id.');
			redirect(cms_current_url());
		}
		$this->load->view('forget_password/index');
	}


	public function trainer(){
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_email[trainer]');							
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		if ($this->form_validation->run() == TRUE){			
			$email = $this->input->post('email');
			$row = $this->admin_model->get_row('trainer', array('email'=> $email));

			$newPassword = createRandomPassword(12);                
    		$changePasswordKey = sha1(uniqid());
    		$obj = array(
    			'forgot_pwd'		=> sha1($newPassword),
				'forgot_pwd_key'	=> $changePasswordKey
    		);

    		$obj['lastupdated'] = time();

    		$this->db->where('id', $row->id);
    		$this->db->update('trainer', $obj);

			$subject = 'SportsApp : Forget Password';
			$to = array(
				$row->email
			);

			$data['name'] = $row->fname.' '.$row->lname;
			$data['new_password'] = $newPassword;
			$data['key'] = $changePasswordKey;
			
			$html = $this->load->view('email/forgot_password', $data, TRUE);

			$this->send_email($subject, $to, $html);

			$this->session->set_flashdata('success_msg', 'A new password has been sent to your registered email-id.');
			redirect(cms_current_url());
		}
		$this->load->view('forget_password/index');
	}

	public function trainee(){

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_email[trainee]');							
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		if ($this->form_validation->run() == TRUE){			
			$email = $this->input->post('email');
			$row = $this->admin_model->get_row('trainee', array('email'=> $email));

			$newPassword = createRandomPassword(12);                
    		$changePasswordKey = sha1(uniqid());
    		$obj = array(
    			// 'forgot_pwd'		=> sha1($newPassword),
				'forgot_pwd_key'	=> $changePasswordKey
    		);

    		$obj['lastupdated'] = time();

    		$this->db->where('id', $row->id);
    		$this->db->update('trainee', $obj);

			$subject = 'SportsApp : Forget Password';
			$to = array(
				$row->email
			);

			$data['name'] = $row->fname.' '.$row->lname;
			$data['new_password'] = $newPassword;
			$data['key'] = $changePasswordKey;
			
			$html = $this->load->view('email/forget_traineepassword', $data, TRUE);

			$this->send_email($subject, $to, $html);

			$this->session->set_flashdata('success_msg', 'A new password has been sent to your registered email-id.');
			redirect(cms_current_url());
		}
		$this->load->view('forget_password/index');
	}

	public function activate_password($key=''){
		$users_row = $this->admin_model->get_row('users', array('forgot_pwd_key' => $key));
		$manager_row = $this->admin_model->get_row('manager', array('forgot_pwd_key' => $key));
		$trainer_row = $this->admin_model->get_row('trainer', array('forgot_pwd_key' => $key));
		$trainee_row = $this->admin_model->get_row('trainee', array('forgot_pwd_key' => $key));

		$row = FALSE;

    	if($users_row){
    		$tablename = 'users';
    		$row = $users_row;
    		$redirect = 'login';
    	}elseif($manager_row){
    		$tablename = 'manager';
    		$row = $manager_row;
    		$redirect = 'login/manager_login';
    	}elseif($trainer_row){
    		$tablename = 'trainer';
    		$row = $trainer_row;
    		$redirect = 'login/trainer_login';
    	}elseif($trainee_row){
    		$tablename = 'trainee';	
    		$row = $trainee_row;
    		$redirect = 'login/trainee_login';
    	}
    	
    	if($row){
    		$this->db->where('id', $row->id);
    		$obj = array(
    			'password' => $row->forgot_pwd,
    			'forgot_pwd_key' => uniqid()
    		);

    		$obj['lastupdated'] = time();

    		$this->db->update($tablename, $obj);
    		$this->session->set_flashdata('success_msg', 'Your password is successfully reset. Please login with your new password.');
    		redirect($redirect);
		}
    	else{
    		$this->session->set_flashdata('error_msg', 'Oops!!! invalid key.');
			redirect('forget_password/trainee');
		}
	}

	public function send_email($subject, $to, $html, $from = array('no-reply@sportsApp.com' =>'SportsApp')){
	//	$this->load->library('smtp_lib/smtp_email');
		 ini_set('display_errors', '1');
		 // To send HTML mail, the Content-type header must be set
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		//$headers[] = 'To: '.$to[0];
		$headers[] = 'From: SportsApp <no-reply@sportsApp.com>';
	//	echo implode("\r\n", $headers);
		mail($to[0],$subject,$html,implode("\r\n", $headers));
	//	$this->smtp_email->sendEmail($from, $to, $subject, $html);
		return TRUE;
	}

	public function password_reset($key=''){	
		if($key == ""){
			$this->session->set_flashdata('error_msg', 'Oops!!! invalid key.');
			redirect('forget_password/trainee');
		}

		$trainee_row = $this->admin_model->get_row('trainee', array('forgot_pwd_key' => $key));		
    	if($trainee_row){    		
    		$data['status'] = TRUE;
    	}else{
    		$this->session->set_flashdata('error_msg', 'Oops!!! invalid key.');
			redirect('forget_password/trainee');
    	}    	

    	$this->form_validation->set_rules('password', 'password', 'required|matches[confirm]');
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		if ($this->form_validation->run() == TRUE){
			$password = $this->input->post('password');			
			$this->admin_model->update('trainee', array('password'=>sha1($password),'forgot_pwd_key'=>"",'lastupdated'=>time()), array('id'=>$trainee_row->id));
			$this->session->set_flashdata('success_msg', 'Password changed successfully');
			redirect('login/trainee_login');
			
		}
    	$this->load->view('forget_password/reset_trainee',$data);
	}
}