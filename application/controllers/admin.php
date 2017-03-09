<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		clear_cache();
		$this->load->model('admin_model');
	}

	public function index(){
	
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
		$data['template'] = 'admin/dashboard';
		$this->load->view('templates/admin_template', $data);
	}

	public function form(){
		$data['template'] = 'admin/form';
		$this->load->view('templates/admin_template', $data);
	}
	
	public function alltrainer($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['trainer']=$this->admin_model->get_pagination_result('trainer', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainer/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('trainer', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'trainer/all';
        $this->load->view('templates/admin_template', $data);			
	}

	public function addtrainer(){
		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
		$this->form_validation->set_rules('fname', 'trainer name', 'required');							
		$this->form_validation->set_rules('fname', 'Trainer Firstname', 'required');							
		$this->form_validation->set_rules('lname', 'Trainer lasttname', 'required');							
		$this->form_validation->set_rules('email', 'Email address', 'required');							
		$this->form_validation->set_rules('password', 'Password', 'required');	
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');						
		$this->form_validation->set_rules('address', ' Address field', 'required');							
		$this->form_validation->set_rules('city', 'City name', 'required');							
								
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>sha1($this->input->post('password')),	//md5
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),							
				'created' => date('Y-m-d H:i:s')			
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();	
			
			$uid=$this->admin_model->insert('trainer',$data);
			if ($uid) {
				$this->send_registration_email($data['fname'], $data['lname'], $data['email'], $this->input->post('password'));
				$this->session->set_flashdata('success_msg', 'Sign up has been successfully commpleted.');
					//redirect('trainer/all');

			}else
				$this->session->set_flashdata('error_msg', "Can't commplete sign up process.");
				redirect(cms_current_url());
		}
		
		$data['template'] = 'trainer/add';
        $this->load->view('templates/admin_template', $data);		
	}

	public function send_registration_email($fname, $lname, $email, $password){
		$this->load->library('smtp_lib/smtp_email');
		$subject = 'sportsapp User';	// Subject for email
		$from = array('no-reply@sportsapp.com' =>'sportsapp.com');	// From email in array form
		$to = array(
			 $email,
		);
		$html = $this->template_for_resgistration($fname, $lname, $email, $password);
		$is_fail = $this->smtp_email->sendEmail($from, $to, $subject, $html);
		if($is_fail){
			echo "ERROR :";
			print_r($is_fail);
		}
	}

	public function template_for_resgistration($fname, $lname, $email, $password){
		$message = '';
		$message .= '<html>
						<body>
						<h3>Hello '.$fname." ".$lname.',</h3><h4>Your Account has been successfully created. Now you can login with the following credentials<br> Email : '.$email.'<br> Password : '.$password.' </h4> <br><br>';
		$message .= '<h4> Login URL '.base_url().'login/trainer_login <br><br></h4>';
		
		$message .=	'</body></html>';

		return $message;
	}

	public function edittrainer($id = ""){
		if($id == "")
			redirect(_INDEX.'admin/all');

		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
	$data['trainer'] = $this->admin_model->get_row('trainer', array('id'=>$id));
		// echo "<pre>";
		// print_r($data['trainer']); die();
		$this->form_validation->set_rules('fname', 'Trainer Firstname', 'required');							
		$this->form_validation->set_rules('lname', 'Trainer lasttname', 'required');							
		$this->form_validation->set_rules('email', 'Email address', 'required');							
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');							
		$this->form_validation->set_rules('address', ' Address field', 'required');							
		$this->form_validation->set_rules('city', 'City name', 'required');							
								
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>$this->input->post('password'),	
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),								
				'created' => date('Y-m-d H:i:s')		
			);

			$update['lastupdated'] = time();		
			
			$this->admin_model->update('trainer',$update, array('id'=>$id));
			//print_r($update); die();
			$this->session->set_flashdata('success_msg',"trainer has been updated successfully.");
			redirect(_INDEX.'admin/all');
		}

		$data['template'] = 'trainer/edit';
        $this->load->view('templates/admin_template', $data);		
	}
	public function deletetrainer($id=""){	
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');	
		
		$data =	$this->admin_model->get_row('trainer', array('id'=> $id));
		
		$this->admin_model->delete('trainer',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"trainee has been deleted successfully.");
		redirect(_INDEX.'admin/all');
	}


	public function logout(){
		$this->session->set_userdata('AdminInfo','');
		$this->session->unset_userdata('AdminInfo');
		$this->session->set_flashdata('success_msg','Logout successfully.');		
		redirect(_INDEX.'login/admin_login');
	} 

	public function social_links(){		
		$this->form_validation->set_rules('facebook', 'facebook', 'required');			
		$this->form_validation->set_rules('twitter', 'twitter', 'required');			
		$this->form_validation->set_rules('instagram', 'instagram', 'required');			
		$this->form_validation->set_rules('twitter_username', 'twitter username', 'required');					
		if ($this->form_validation->run() == TRUE){
			$data = array(
				'facebook' => $this->input->post('facebook'),
				'twitter' => $this->input->post('twitter'),
				'instagram' => $this->input->post('instagram'),
				'twitter_username' => $this->input->post('twitter_username'),				
			);

			$data['lastupdated'] = time();

			$this->admin_model->update('social_links',$data);		
			$this->session->set_flashdata('success_msg',"Updated");
			redirect(cms_current_url());
		}

		$data['link'] = $this->admin_model->get_row('social_links', array('id'=>1));
		$data['template'] = 'admin/social_links';
        $this->load->view('templates/admin_template', $data);
	}

	public function alltrainee($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['trainee']=$this->admin_model->get_pagination_result('trainee', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainee/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('trainee', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'trainee/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function addtrainee(){
		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
								
		$this->form_validation->set_rules('fname', 'Trainer Firstname', 'required');							
		$this->form_validation->set_rules('lname', 'Trainer lasttname', 'required');							
		$this->form_validation->set_rules('email', 'Email address', 'required');							
		$this->form_validation->set_rules('password', 'Password', 'required');	
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');						
		$this->form_validation->set_rules('address', ' Address field', 'required');							
		$this->form_validation->set_rules('city', 'City name', 'required');					if ($this->form_validation->run() == TRUE){			
			$data=array(
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>sha1($this->input->post('password')),	
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),							
				'created' => date('Y-m-d H:i:s')			
			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();	
			
			$this->admin_model->insert('trainee',$data);		
			$this->session->set_flashdata('success_msg',"trainer has been added successfully.");
			redirect(_INDEX.'admin/alltrainee');
		}

		$data['template'] = 'trainer_controller/add';
        $this->load->view('templates/trainer_template', $data);		
		}

	
	public function edittrainee($id = ""){
		if($id == "")
			redirect(_INDEX.'admin/alltrainee');

		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
	$data['trainee'] = $this->admin_model->get_row('trainee', array('id'=>$id));
		// echo "<pre>";
		// print_r($data['trainer']); die();
		$this->form_validation->set_rules('fname', 'Trainer Firstname', 'required');							
		$this->form_validation->set_rules('lname', 'Trainer lasttname', 'required');							
		$this->form_validation->set_rules('email', 'Email address', 'required');							
		$this->form_validation->set_rules('password', 'Password', 'required');	
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');						
		$this->form_validation->set_rules('address', ' Address field', 'required');							
		$this->form_validation->set_rules('city', 'City name', 'required');							
								
		if ($this->form_validation->run() == TRUE){			
			$update=array(

				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>$this->input->post('password'),	
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),								
				'created' => date('Y-m-d H:i:s')		
			);

			$update['lastupdated'] = time();		
			
			$this->admin_model->update('trainee',$update, array('id'=>$id));
			//print_r($update); die();
			$this->session->set_flashdata('success_msg',"trainee has been updated successfully.");
			redirect(_INDEX.'admin/alltrainee');
		}

		$data['template'] = 'trainee/edit';
        $this->load->view('templates/admin_template', $data);		
	}
	public function deletetrainee($id=""){	
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');	
		
		$data =	$this->admin_model->get_row('trainee', array('id'=> $id));
		$this->admin_model->delete('trainee',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"trainee has been deleted successfully.");
		redirect(_INDEX.'admin/alltrainee');
	}


	public function support($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');	

		$limit=10;
		$data['support']=$this->admin_model->get_pagination_result('support', $limit,$offset, array('by'=>1));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'admin/support/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('support', 0, 0, array('by'=>1));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'admin/support';
        $this->load->view('templates/admin_template', $data);			
	}


	// public function reply($token2 = ""){
	// 	if(admin_login_in()===FALSE)
	// 		redirect(_INDEX.'login');

	// 	if($token2 == "")
	// 		redirect(_INDEX.'admin/support');

	// 	$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
	// 	$this->form_validation->set_rules('reply', 'reply', 'required');									
	// 	if ($this->form_validation->run() == TRUE){			
	// 		$update=array(
	// 			'message'=>$this->input->post('reply'),				
	// 			'support_token2'=>$token2,								
	// 			'send_by'=>1,	
	// 			'recieve_by'=>2,	
	// 			'created' => date('Y-m-d H:i:s')		
	// 		);

	// 		$update['token'] = get_token();
	// 		$update['lastupdated'] = time();	
				
	// 		$this->admin_model->insert('conversation',$update);
	// 		//$this->session->set_flashdata('success_msg',"successfully Sent");
	// 		redirect(cms_current_url());
	// 	}

	// 	$data['conversation'] = $this->admin_model->get_conversation($token2);		
	// 	$data['template'] = 'admin/reply';
 //        $this->load->view('templates/admin_template', $data);		
	// }


	public function reply($token2 = ""){
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($token2 == "")
			redirect(_INDEX.'admin/support');

		/*Update read status*/
		$update_read = array('admin_read'=>1);
		$this->admin_model->update('conversation',$update_read,array('support_token2'=>$token2));
		/*Update read status*/

		$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
		$this->form_validation->set_rules('reply', 'reply', 'required');									
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'message'=>$this->input->post('reply'),				
				'support_token2'=>$token2,								
				'send_by'=>1,	
				'recieve_by'=>2,	
				'created' => date('Y-m-d H:i:s')		
			);

			$update['token'] = get_token();
			$update['lastupdated'] = time();	
				
			$this->admin_model->insert('conversation',$update);
			//$this->session->set_flashdata('success_msg',"successfully Sent");
			redirect(cms_current_url());
		}

		$data['conversation'] = $this->admin_model->get_conversation($token2);		
		$data['template'] = 'admin/reply';
        $this->load->view('templates/admin_template', $data);		
	}

	public function status($support_token2,$status){
		$this->admin_model->update('support', array('status'=>$status , 'lastupdated' => time() ), array('token2'=>$support_token2));
		$this->session->set_flashdata('success_msg',"Status Changed");
		redirect(_INDEX.'admin/reply/'.$support_token2);
	}


	public function delete_support($support_token2 = ""){
		if($support_token2 == "")
			redirect(_INDEX.'admin/support');

		$this->admin_model->delete('support', array('token2'=>$support_token2));
		$this->admin_model->delete('conversation', array('support_token2'=>$support_token2));
		$this->session->set_flashdata('success_msg',"Successfully Deleted");
		redirect(_INDEX.'admin/support');

	}
}