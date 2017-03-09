<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all();
	}
	
	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
		$limit=10;
		$data['support']=$this->admin_model->get_pagination_result('support', $limit,$offset, array('trainer_id'=>get_trainer_id(), 'by'=>'1'));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'support/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('support', 0, 0, array('trainer_id'=>get_trainer_id(), 'by'=>'1'));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'support/all';
        $this->load->view('templates/trainer_template', $data);			
	}	

	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$this->form_validation->set_rules('subject', 'subject', 'required');							
		$this->form_validation->set_rules('message', 'message', 'required');							

		$trainerinfo = get_trainer_info();										
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'subject'=>$this->input->post('subject'),				
				'message'=>$this->input->post('message'),				
				'trainer_id'=>$trainerinfo->id,		
				'trainer_name'=>$trainerinfo->fname.' '.$trainerinfo->lname,		
				'trainer_email'=>$trainerinfo->email,
				'by'=>1,				
				'status'=>1,				
				'is_read'=>0,				
				'created' => date('Y-m-d H:i:s')			
			);	

			$data['token2'] = get_token();
			$data['lastupdated'] = time();
			
			$id = $this->admin_model->insert('support',$data);		
			$token = time();
			$token = $token.$id;
			$this->admin_model->update('support', array('token'=>$token, 'lastupdated' => time()), array('id'=>$id));
			$this->session->set_flashdata('success_msg',"Query has been submitted successfully.");
			redirect(_INDEX.'support/all');
		}

		$data['template'] = 'support/add';
        $this->load->view('templates/trainer_template', $data);		
	}

	// public function reply($token2 = ""){
	// 	if(trainer_login_in()===FALSE)
	// 		redirect(_INDEX.'login/trainer_login');

	// 	if($token2 == "")
	// 		redirect(_INDEX.'support/all');

	// 	$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
	// 	$this->form_validation->set_rules('reply', 'reply', 'required');									
	// 	if ($this->form_validation->run() == TRUE){			
	// 		$update=array(
	// 			'message'=>$this->input->post('reply'),				
	// 			'support_token2'=>$token2,				
	// 			'sender_id'=>get_trainer_id(),	
	// 			'send_by'=>2,	
	// 			'recieve_by'=>1,	
	// 			'created' => date('Y-m-d H:i:s')		
	// 		);

	// 		$update['token'] = get_token();
	// 		$update['lastupdated'] = time();			
				
	// 		$this->admin_model->insert('conversation',$update);
	// 		//$this->session->set_flashdata('success_msg',"successfully Sent");
	// 		redirect(cms_current_url());
	// 	}

	// 	$data['conversation'] = $this->admin_model->get_conversation($token2);		
	// 	$data['template'] = 'support/reply';
 //        $this->load->view('templates/trainer_template', $data);		
	// }


	public function reply($token2 = ""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		if($token2 == "")
			redirect(_INDEX.'support/all');

		/*Update read status*/
		$update_read = array('trainer_read'=>1);
		$this->admin_model->update('conversation',$update_read,array('support_token2'=>$token2));
		/*Update read status*/

		$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
		$this->form_validation->set_rules('reply', 'reply', 'required');									
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'message'=>$this->input->post('reply'),				
				'support_token2'=>$token2,				
				'sender_id'=>get_trainer_id(),	
				'send_by'=>2,	
				'recieve_by'=>1,	
				'created' => date('Y-m-d H:i:s')		
			);

			$update['token'] = get_token();
			$update['lastupdated'] = time();			
				
			$this->admin_model->insert('conversation',$update);
			//$this->session->set_flashdata('success_msg',"successfully Sent");
			redirect(cms_current_url());
		}

		$data['conversation'] = $this->admin_model->get_conversation($token2);		
		$data['template'] = 'support/reply';
        $this->load->view('templates/trainer_template', $data);		
	}

	public function delete($token2=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$row = $this->admin_model->get_row('support',array('token2'=> $token2));		
		$this->admin_model->delete('support',array('token2'=> $token2));		
		$this->admin_model->delete('conversation',array('support_token2'=> $token2));		

		$this->session->set_flashdata('success_msg',"support has been deleted successfully.");
		redirect(_INDEX.'support/all');
	}


	public function status($support_token2,$status){
		$this->admin_model->update('support', array('status'=>$status, 'lastupdated' => time()), array('token2'=>$support_token2));
		redirect(_INDEX.'support/reply/'.$support_token2);
	}



	public function trainee_queries($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$limit=10;
		$data['support']=$this->admin_model->get_trainee_queries($limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'support/trainee_queries/';
		$config['total_rows'] = $this->admin_model->get_trainee_queries(0,0);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'support/trainee_queries';
        $this->load->view('templates/trainer_template', $data);			
	}

	public function delete_queries($token2=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$row = $this->admin_model->get_row('support',array('token2'=> $token2));		
		$this->admin_model->delete('support',array('token2'=> $token2));		
		$this->admin_model->delete('conversation',array('support_token2'=> $token2));		
		
         
		$this->session->set_flashdata('success_msg',"Query has been deleted successfully.");
		redirect(_INDEX.'support/trainee_queries');
	}
}