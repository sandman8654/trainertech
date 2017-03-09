<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manager extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

/**
		ADMIN PANEL
*/

	public function all($offset=0){		
		if(admin_login_in()===FALSE){
			redirect(_INDEX.'login');
		}
		$limit=10;
		$data['manager']=$this->admin_model->get_pagination_result('manager', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'manager/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('manager', 0, 0);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'manager/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function add(){
		if(admin_login_in()===FALSE){
			redirect(_INDEX.'login');
		}
		$this->form_validation->set_rules('fname', 'firstname', 'required');							
		$this->form_validation->set_rules('lname', 'lastname', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[manager.email]');							
		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');	
		$this->form_validation->set_rules('address', ' address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'slug'=>create_slug('manager', $this->input->post('fname').' '.$this->input->post('lname')),
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>sha1($this->input->post('password')),
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),									
				'status'=>1,
				'created' => date('Y-m-d H:i:s')			
			);
			$data['token'] = get_token();
			$data['lastupdated'] = time();	
			$this->admin_model->insert('manager',$data);		
			$this->session->set_flashdata('success_msg',"manager has been added successfully.");
			redirect(_INDEX.'manager/all');
		}
		$data['template'] = 'manager/add';
        $this->load->view('templates/admin_template', $data);		
	}

	public function edit($slug = ""){
		if(admin_login_in()===FALSE){
			redirect(_INDEX.'login');
		}
		if($slug == ""){
			redirect(_INDEX.'manager/all');
		}
		$data['manager'] = $this->admin_model->get_row('manager', array('slug'=>$slug));
		$this->form_validation->set_rules('fname', 'firstname', 'required');							
		$this->form_validation->set_rules('lname', 'lastname', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_is_email_unique['.$data['manager']->email.']');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');							
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'slug'=>create_slug_for_update('manager', $this->input->post('fname').' '.$this->input->post('lname'), $data['manager']->id),
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),								
				'updated' => date('Y-m-d H:i:s')		
			);
			$update['lastupdated'] = time();			
			$this->admin_model->update('manager',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"manager has been updated successfully.");
			redirect(_INDEX.'manager/all');
		}
		$data['template'] = 'manager/edit';
        $this->load->view('templates/admin_template', $data);		
	}

	public function delete($slug=""){	
		if(admin_login_in()===FALSE){
			redirect(_INDEX.'login');	
		}
		$this->admin_model->delete('manager',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"manager has been deleted successfully.");
		redirect(_INDEX.'manager/all');
	}

	public function check_is_email_unique($new_email, $old_email){
		if ($old_email === $new_email) {
			return TRUE;
		}else{
			$resp = $this->admin_model->get_row('manager', array('email' => $new_email));
			if ($resp) {
				$this->form_validation->set_message('check_is_email_unique', 'This email is already exist.');
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}


/**
		MANAGER PANEL
*/

	public function index(){
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');
		}
		$data['template'] = 'manager/dashboard';
        $this->load->view('templates/manager_template', $data);
	}

	public function logout(){
		$this->session->set_userdata('managerInfo','');
		$this->session->unset_userdata('managerInfo');
		$this->session->set_flashdata('success_msg','Logout successfully.');		
		redirect(_INDEX.'login/manager_login');
	}

	public function check_is_email_unique_for_manager($new_email, $old_email){
		if ($old_email === $new_email) {
				return TRUE;
		}else{
			$resp = $this->admin_model->get_row('trainer', array('email' => $new_email));
			if ($resp) {
				$this->form_validation->set_message('check_is_email_unique_for_manager', 'This email is already exist.');
				return FALSE;
			}else{
				return TRUE;
			}
		}
	}

	public function manage_trainer($offset=0){
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');
		}
		$limit=10;
		$where = array('manager_id' => get_manager_id());
		$data['trainer']=$this->admin_model->get_pagination_result('trainer', $limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'manager/manage_trainer/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('trainer', 0, 0,$where);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
		$data['template'] = 'manager/manage_trainer';
        $this->load->view('templates/manager_template', $data);
	}

	public function add_trainer(){
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');
		}
		$this->form_validation->set_rules('fname', 'firstname', 'required');							
		$this->form_validation->set_rules('lname', 'lastname', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[trainer.email]');							
		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');
		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');	
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');	
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'slug'=>create_slug('trainer', $this->input->post('fname').' '.$this->input->post('lname')),
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>sha1($this->input->post('password')),	
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),									
				'manager_id'=>get_manager_id(),	
				'status'=>1,
				'created' => date('Y-m-d H:i:s')			
			);
			$data['token'] = get_token();
			$data['lastupdated'] = time();
			$this->admin_model->insert('trainer',$data);	
			$this->session->set_flashdata('success_msg',"trainer has been added successfully.");
			redirect(_INDEX.'manager/manage_trainer');
		}
		$data['template'] = 'manager/add_trainer';
        $this->load->view('templates/manager_template', $data);	
	}

	public function edit_trainer($slug = ""){
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');
		}
		$data['trainer'] = $this->admin_model->get_row('trainer', array('slug'=>$slug));
		$this->form_validation->set_rules('fname', 'firstname', 'required');							
		$this->form_validation->set_rules('lname', 'lastname', 'required');							
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_is_email_unique_for_manager['.$data['trainer']->email.']');							
		$this->form_validation->set_rules('address', 'address', 'required');							
		$this->form_validation->set_rules('city', 'city', 'required');	
		if ($this->form_validation->run() == TRUE){			
			$update=array(
				'slug'=>create_slug_for_update('trainer', $this->input->post('fname').' '.$this->input->post('lname'), $data['trainer']->id),
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),		
				'manager_id'=>get_manager_id(),							
				'updated' => date('Y-m-d H:i:s')		
			);
			$update['lastupdated'] = time();			
			$this->admin_model->update('trainer',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"trainer has been updated successfully.");
			redirect(_INDEX.'manager/manage_trainer');
		}
		$data['template'] = 'manager/edit_trainer';
        $this->load->view('templates/manager_template', $data);	
	}

	public function delete_trainer($slug=""){	
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login/manager_login');	
		}
		$this->admin_model->delete('trainer',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"trainer has been deleted successfully.");
		redirect(_INDEX.'manager/manage_trainer');
	}

	public function changePassword(){
		if(manager_login_in()===FALSE){
			redirect(_INDEX.'login');
		}
		$id = get_manager_id();
		$this->form_validation->set_rules('old', 'old password', 'required|callback_check_old_pwd');							
		$this->form_validation->set_rules('new', 'new password', 'required');							
		$this->form_validation->set_rules('con', 'confirm password', 'required|matches[new]');							
		if ($this->form_validation->run() == TRUE){			
			$new = $this->input->post('new');
			$new = sha1(trim($new));
			$this->admin_model->update('manager',array('password' => $new, 'lastupdated' => time()), array('id'=>$id));
			$this->session->set_flashdata('success_msg','Password has been changed successfully.');
			redirect(_INDEX.'manager/changePassword');
		}
		$data['template'] = 'manager/changePassword';
        $this->load->view('templates/manager_template', $data);			
	}

 	public function  check_old_pwd($x){
		$id = get_manager_id();
		$pwd=sha1($x);
		$row = $this->admin_model->get_row('manager', array('id'=> $id));
		if($row->password == $pwd){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_old_pwd','Please enter correct old password');
			return FALSE;
		}
 	}

	public function searchsort(){
		$group = $this->input->post('grp');
		$search = $this->input->post('src');		
		$res = $this->admin_model->filter_trainer($group,$search);
		$response = "";
		if(!empty($res)){
			  if ($res): foreach($res as $row):
				$response .= '<tr><td>'.$row->fname.' '.$row->lname.'</td><td>'.$row->grp_name.' - '.$row->sgrp_name.'</td></tr>';
                endforeach; endif; 
			echo $response;
			die();
		}else{	
			$response .= '<tr><td colspan="2">No Record Found</td></tr>';
			echo $response;
			die();
		}
	}

	public function view_trainer($slug=""){
		if(manager_login_in()===FALSE)
			redirect(_INDEX.'login');

		if($slug == "")
			redirect(_INDEX.'manager/manage_trainer');
		$data['trainer_id'] = slug_to_id('trainer',$slug);
		$workoutsDefualt = $this->getMyDefualtWorkouts($data['trainer_id']);
		if ($workoutsDefualt['status']) {
			$data['status'] = true;
			$data['details'] = $workoutsDefualt['details'];
			$data['currentWorkout'] = $workoutsDefualt['currentWorkout'];
			$data['dateToshow'] = $workoutsDefualt['dateToshow'];
			if (isset($workoutsDefualt['navigateArray'])) {
				$data['navigateArray'] = $workoutsDefualt['navigateArray'];
			}
		}else{
			$data['status'] = false;
		}
		$data['template'] = 'manager/view_trainer';
        $this->load->view('templates/manager_template', $data);
	}

	public function getMyDefualtWorkouts($trainer_id='')
	{
		$date = date('Y-m-d');
		$navigateArray = array();
		$result = array();
		$workout = $this->admin_model->getWorkoutsOfTheDay($date, $trainer_id);
		if (!empty($workout)) {
			foreach ($workout as $key => $value) {
				if ($key == 0) {
					$currentWorkout = $this->admin_model->get_row('workout', array('id' => $value->id));
					if (isset($workout[$key+1])) {
						$navigateArray['nex'] = $workout[$key+1]->id;
						$navigateArray['date'] = date("D M j G:i:s T Y", strtotime($date));//Sat Jun 28 2014 15:42:00 GMT+0530 (India Standard Time)
					}
				}
			}
			$workouts = $this->admin_model->getWorkoutDetails($currentWorkout->id);
			foreach ($workouts as $key => $value) {
				$result[$value->eId]['exercise'] = $value->name;
				$result[$value->eId]['sets'][] = $value;
			}
		}

		if (!empty($result)){
			$response = array('status' => true, 'details' => $result, 'currentWorkout' => $currentWorkout, 'dateToshow' => date('m/d/y', strtotime($date)));
			if (!empty($navigateArray)) {
				$response['navigateArray'] = $navigateArray;
			}
			return $response;
		}
		else
			return false;
	}

	public function ajaxWorkoutDetails()
	{
		extract($_POST);
		$gmtDate = $date;
		$date = explode(" ", $date);
		$date = $date[1].' '.$date[2].' '.$date[3];
		$date = date('Y-m-d', strtotime($date));
		$currentWorkout = $this->admin_model->get_row('workout', array('id' => $workOutId));
		$navigateArray = array();

		$workout = $this->admin_model->getWorkoutsOfTheDay($date, $trainer_id);

		if (!empty($workout) && !empty($currentWorkout)) {
			foreach ($workout as $key => $value) {
				if ($currentWorkout->id == $value->id) {
					if (isset($workout[$key+1])) {
						$navigateArray['nex'] = $workout[$key+1]->id;
						$navigateArray['date'] = $gmtDate;
					}
					if (isset($workout[$key-1])) {
						$navigateArray['prev'] = $workout[$key-1]->id;
						$navigateArray['date'] = $gmtDate;
					}
				}
			}
		}

		$workouts = $this->admin_model->getWorkoutDetails($workOutId);

		$result = array();
		foreach ($workouts as $key => $value) {
			$result[$value->eId]['exercise'] = $value->name;
			$result[$value->eId]['sets'][] = $value;
		}

		if (!empty($result)){
			$response = array('status' => true, 'details' => $result, 'currentWorkout' => $currentWorkout, 'dateToshow' => date('m/d/y', strtotime($date)));
			if (!empty($navigateArray)) {
				$response['navigateArray'] = $navigateArray;
			}
			echo json_encode($response);
		}
		else{
			echo json_encode(array('status' => false));
		}
	}


}