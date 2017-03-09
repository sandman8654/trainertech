<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trainer extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index($offset=0){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$data['groups'] = $this->admin_model->get_result('group',array('trainer_id'=>get_trainer_id(),'parent_id'=>0));						

        $where  = array();
        if($this->session->userdata('s_trn')){
        	$where = $this->session->userdata('s_trn');
        }
        $limit = 10;
		$data['trainee'] = $this->admin_model->get_trainee_all(get_trainer_id(),$where,$limit,$offset);		
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainer/index/';
		$config['total_rows'] = $this->admin_model->get_trainee_all(get_trainer_id(),$where,0,0);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();

		$data['select_groups'] = $this->admin_model->select_groups(0);
		$data['total_groups'] = $this->admin_model->select_groups(1);
		$data['group_members'] = $this->admin_model->select_groups_members(0);
		$data['total_members'] = $this->admin_model->select_groups_members(1);

		$data['template'] = 'trainer/dashboard';
        $this->load->view('templates/trainer_template', $data);
	}


	public function search_dashboard(){
		$this->session->set_userdata('s_trn',$_POST);
		redirect(_INDEX.'trainer/index');
	}

	

	public function all($offset=0){		

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



	public function add(){

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');

		

		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[trainer.email]');							

		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');

		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');	

		$this->form_validation->set_rules('address', ' address', 'required');							

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

				'status'=>1,

				'created' => date('Y-m-d H:i:s')			

			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();	

			

			$this->admin_model->insert('trainer',$data);		

			$this->session->set_flashdata('success_msg',"trainer has been added successfully.");

			redirect(_INDEX.'trainer/all');

		}



		$data['template'] = 'trainer/add';

        $this->load->view('templates/admin_template', $data);		

	}



	public function edit($slug = ""){

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');

		

		if($slug == "")

			redirect(_INDEX.'trainer/all');



		$data['trainer'] = $this->admin_model->get_row('trainer', array('slug'=>$slug));

		

		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_is_email_unique['.$data['trainer']->email.']');							

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

				'updated' => date('Y-m-d H:i:s')		

			);

			$update['lastupdated'] = time();			

			

			$this->admin_model->update('trainer',$update, array('slug'=>$slug));

			$this->session->set_flashdata('success_msg',"trainer has been updated successfully.");

			redirect(_INDEX.'trainer/all');

		}



		$data['template'] = 'trainer/edit';

        $this->load->view('templates/admin_template', $data);		

	}

	public function delete($slug=""){	

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');	

		

		$this->admin_model->delete('trainer',array('slug'=> $slug));		

		$this->session->set_flashdata('success_msg',"trainer has been deleted successfully.");

		redirect(_INDEX.'trainer/all');

	}

		

	public function logout(){
		$this->session->set_userdata('trainerInfo','');
		$this->session->unset_userdata('trainerInfo');
		$this->session->set_userdata('s_trn','');
		$this->session->unset_userdata('s_trn');
		$this->session->set_userdata('sort_trainee','');
		$this->session->unset_userdata('sort_trainee');
		$this->session->set_userdata('sort_group','');
		$this->session->unset_userdata('sort_group');
		$this->session->set_flashdata('success_msg','Logout successfully.');		
		redirect(_INDEX.'login/trainer_login');
	}



	public function check_is_email_unique($new_email, $old_email){

		if ($old_email === $new_email) {

				return TRUE;

		}else{

			$resp = $this->admin_model->get_row('trainer', array('email' => $new_email));

			if ($resp) {

				$this->form_validation->set_message('check_is_email_unique', 'This email is already exist.');

				return FALSE;

			}else{

				return TRUE;

			}

		}

	}





	// MANAGE TRAINEE



	public function check_is_email_unique_for_trainer($new_email, $old_email){

		if ($old_email === $new_email) {

				return TRUE;

		}else{

			$resp = $this->admin_model->get_row('trainee', array('email' => $new_email));

			if ($resp) {

				$this->form_validation->set_message('check_is_email_unique_for_trainer', 'This email is already exist.');

				return FALSE;

			}else{

				return TRUE;

			}

		}

	}



	public function manage_trainee($offset=0){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
		$limit=10;
		$where = array();
        if($this->session->userdata('sort_trainee')){
        	$where = $this->session->userdata('sort_trainee');
        }
		$data['trainee']=$this->admin_model->manage_trainee($limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainer/manage_trainee/';
		$config['total_rows'] = $this->admin_model->manage_trainee(0,0,$where);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
		$data['template'] = 'trainer/manage_trainee';
        $this->load->view('templates/trainer_template', $data);
	}



	public function add_trainee(){

		if(trainer_login_in()===FALSE)

			redirect(_INDEX.'login/trainer_login');



		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[trainee.email]');							

		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');

		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');	

		$this->form_validation->set_rules('address', 'address', 'required');							

		$this->form_validation->set_rules('city', 'city', 'required');	

								

		if ($this->form_validation->run() == TRUE){			

			$data=array(

				'slug'=>create_slug('trainee', $this->input->post('fname').' '.$this->input->post('lname')),

				'fname'=>$this->input->post('fname'),

				'lname'=>$this->input->post('lname'),

				'email'=>$this->input->post('email'),

				'password'=>sha1($this->input->post('password')),	

				'address'=>$this->input->post('address'),	

				'city'=>$this->input->post('city'),									

				'trainer_id'=>get_trainer_id(),	

				'created' => date('Y-m-d H:i:s')			

			);

			$data['token'] = get_token();
			$data['lastupdated'] = time();

			

			$this->admin_model->insert('trainee',$data);	

			$this->session->set_flashdata('success_msg',"trainee has been added successfully.");

			redirect(_INDEX.'trainer/manage_trainee');

		}

		
		$data['template'] = 'trainer/add_trainee';

        $this->load->view('templates/trainer_template', $data);	
    }



	public function edit_trainee($slug = ""){

		if(trainer_login_in()===FALSE)

			redirect(_INDEX.'login/trainer_login');



		$data['trainee'] = $this->admin_model->get_row('trainee', array('slug'=>$slug));

		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_is_email_unique_for_trainer['.$data['trainee']->email.']');							

		$this->form_validation->set_rules('address', 'address', 'required');							

		$this->form_validation->set_rules('city', 'city', 'required');	

		

								

		if ($this->form_validation->run() == TRUE){			

			$update=array(

				'slug'=>create_slug_for_update('trainee', $this->input->post('fname').' '.$this->input->post('lname'), $data['trainee']->id),

				'fname'=>$this->input->post('fname'),

				'lname'=>$this->input->post('lname'),

				'email'=>$this->input->post('email'),

				'address'=>$this->input->post('address'),	

				'city'=>$this->input->post('city'),		

				'trainer_id'=>get_trainer_id(),							

				'updated' => date('Y-m-d H:i:s')		

			);

			$update['lastupdated'] = time();			

			

			$this->admin_model->update('trainee',$update, array('slug'=>$slug));

			$this->session->set_flashdata('success_msg',"trainee has been updated successfully.");

			redirect(_INDEX.'trainer/manage_trainee');

		}

		$data['template'] = 'trainer/edit_trainee';

        $this->load->view('templates/trainer_template', $data);	
	}


	public function delete_trainee($slug=""){	

		if(trainer_login_in()===FALSE)

			redirect(_INDEX.'login/trainer_login');	

		

		$this->admin_model->delete('trainee',array('slug'=> $slug));		

		$this->session->set_flashdata('success_msg',"trainee has been deleted successfully.");

		redirect(_INDEX.'trainer/manage_trainee');

	}



	// MANAGE TRAINEE END



	// CHANGE PASSWORD



	public function changePassword(){

		if(trainer_login_in()===FALSE)

			redirect(_INDEX.'login/trainer_login');



		$id = get_trainer_id();

		$this->form_validation->set_rules('old', 'old password', 'required|callback_check_old_pwd');							

		$this->form_validation->set_rules('new', 'new password', 'required');							

		$this->form_validation->set_rules('con', 'confirm password', 'required|matches[new]');							

		if ($this->form_validation->run() == TRUE)

		{			

			$new = $this->input->post('new');

			$new = sha1(trim($new));

			$this->admin_model->update('trainer',array('password' => $new, 'lastupdated' => time()), array('id'=>$id));

			$this->session->set_flashdata('success_msg','Password has been changed successfully.');

			redirect(_INDEX.'trainer/changePassword');

		}



		$data['template'] = 'trainer/changePassword';

        $this->load->view('templates/trainer_template', $data);			

	}



 	public function  check_old_pwd($x)

 	{

		$id = get_trainer_id();

		$pwd=sha1($x);

		$row = $this->admin_model->get_row('trainer', array('id'=> $id));

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



	// CHANGE PASSWORD END	

	public function searchsort(){
		$group = $this->input->post('grp');
		$search = $this->input->post('src');		
		$res = $this->admin_model->filter_trainee($group,$search);
		$response = "";
		if(!empty($res)){
			  if ($res): foreach($res as $row):
				$response .= '<tr><td>'.$row->fname.' '.$row->lname.'</td><td>'.$row->grp_name.' - '.$row->sgrp_name.'</td></tr>';
                endforeach; endif; 
			//$res = json_encode('success'=>$res);
			echo $response;
			die();
		}else{	
			$response .= '<tr><td colspan="2">No Record Found</td></tr>';
			echo $response;
			die();
		}
	}

	public function view_trainee($slug=""){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		if($slug == "")
			redirect(_INDEX.'trainer/manage_trainee');
		

		$trainee_id = slug_to_id('trainee',$slug);

		$this->session->set_userdata('traineeInfo','');
		
		$this->session->set_userdata('trainee_id_set_by_trainer', $trainee_id);
		redirect(_INDEX.'trainee_dashboard');

		/*
		
		$data['trainee_id'] = slug_to_id('trainee',$slug);
		// $data['workout'] = $this->admin_model->get_todays_workout($data['trainee_id']);		
		$data['workout'] = $this->admin_model->get_todays_workout_t($data['trainee_id']);		
		

		// $workoutsDefualt = $this->getMyDefualtWorkouts($data['trainee_id']);
		// if ($workoutsDefualt['status']) {
		// 	$data['status'] = true;
		// 	$data['details'] = $workoutsDefualt['details'];
		// 	$data['currentWorkout'] = $workoutsDefualt['currentWorkout'];
		// 	$data['dateToshow'] = $workoutsDefualt['dateToshow'];
		// 	if (isset($workoutsDefualt['navigateArray'])) {
		// 		$data['navigateArray'] = $workoutsDefualt['navigateArray'];
		// 	}
		// }else{
		// 	$data['status'] = false;
		// }
		// echo "<pre>";
		// print_r($data);
		// die();
		$data['template'] = 'trainer/view_trainee';
        $this->load->view('templates/trainer_template', $data);
        */
	}

	public function getMyDefualtWorkouts($trainee_id='')
	{
		$date = date('Y-m-d');
		// $date = date('Y-m-d',strtotime('2014-6-28'));
		$navigateArray = array();
		$result = array();
		$workout = $this->admin_model->getWorkoutsOfTheDay($date, $trainee_id);
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

		$workout = $this->admin_model->getWorkoutsOfTheDay($date, $trainee_id);

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

	public function get_group_members(){
 		error_reporting(0);
		header('Content-Type: application/json');
		// $group_id = $this->input->post('group_id');
		// $members = $this->admin_model->get_groupmember($group_id, 10000, 0);
		// echo json_encode($members);
		$selected_groups = $this->input->post('selected_groups');
		$result = array();
		foreach($selected_groups as $group_id){
			$members = $this->admin_model->get_groupmember($group_id, 10000, 0);
			$grp = $this->admin_model->get_row('group', array('id' => $group_id));
			$result[] = array(
				'group_name' => $grp->name,
				'members' => $members
			);
		}
		echo json_encode($result);
 	}

 	public function get_group_members_exercise(){
 		error_reporting(0);
		header('Content-Type: application/json');
		$selected_members = $this->input->post('selected_members');
		$exercises = $this->admin_model->get_common_exercise($selected_members);
		echo json_encode($exercises);
 	}

 	public function get_workout_exercises(){
 		error_reporting(0);
		header('Content-Type: application/json');
		$exercises = $this->admin_model->get_result('exercise', array('workout_id' => $this->input->post('workout_id')));
		echo json_encode($exercises);	
 	}

 	public function create_graph($trainee_ids=0, $workout_id=0, $exercise_id=0, $reps, $from, $to ){
		$from = strtotime(str_replace('-','/',$from));	
		$to = strtotime(str_replace('-','/',$to));	

 		$data['reps'] = $reps;
 		$trainee_ids = explode('-', $trainee_ids);
 		$this->db->where_in('id', $trainee_ids);
 		$this->db->select('id, fname, lname');
 		$this->db->order_by('lname', 'asc');
 		$data['trainees'] = $this->admin_model->get_result('trainee');

 		$sorted_names = array();
 		foreach($data['trainees'] as $tr)
 			$sorted_names[] = $tr->fname.' '.$tr->lname;

 		$sorted_tid = array();
 		foreach($data['trainees'] as $tr)
 			$sorted_tid[] = $tr->id;

 		$trainee_ids = $sorted_tid;

 		$data['eid'] = $exercise_id;
 		$data['wid'] = $workout_id;
 		$data['exercise_id'] = $this->admin_model->get_row('exercise', array('id' => $exercise_id));

 		$default_exercise_id = $this->admin_model->get_default_exercise_id($exercise_id);

 		if(!$default_exercise_id){
 			$data['response'] = FALSE;
 			$this->load->view('trainer/graph', $data);
 			die();
 		}

 		$this->db->where_in('trainee_id', $trainee_ids);
 		$query = $this->db->get('trainee_workout');

 		if(!($query->num_rows() > 0)){
 			$data['response'] = FALSE;
 			$this->load->view('trainer/graph', $data);
 			die();
 		}

 		$workout_ids = array();
 		foreach($query->result() as $row)
 			$workout_ids[] = $row->workout_id;

 		$this->db->select('id, date');
 		$this->db->where_in('id', $workout_ids);
 		$this->db->order_by('date', 'asc');
 		$query = $this->db->get('workout');

 		if(!($query->num_rows() > 0)){
 			$data['response'] = FALSE;
 			$this->load->view('trainer/graph', $data);
 			die();
 		}

 		$result = $query->result();
 		$response = array();
 		foreach($result as $row){
 			$arr = array('status' => 1 );

 			if(strtotime($row->date) > time()){
 				continue;
 			}

 			$arr['date'] = $row->date; 

 			$iii = 0;
 			foreach($trainee_ids as $trainee_id){
				$arr[$trainee_id] = '0';
				$arr[$trainee_id.'_reps'] = '0';
				$arr[$trainee_id.'_name'] = $sorted_names[$iii];
				$arr[$trainee_id.'_wid'] = $row->id;
				$arr[$trainee_id.'_eid'] = '0';
				$iii++;
			}
 			
 			$this->db->where('default_exercise_id', $default_exercise_id);
 			$this->db->where('workout_id', $row->id);
 			$exercises = $this->db->get('exercise');
 			if($exercises->num_rows() > 0){
 				

 				foreach($exercises->result() as $e_row){
 					
 					$exercises_set = $this->admin_model->get_result('exercise_set', array('exercise_id' => $e_row->id));

 					// echo $this->db->last_query();

 					if($exercises_set){

 						$exercise_set_ids = array();

 						foreach($exercises_set as $e_s_row)
 							$exercise_set_ids[] = $e_s_row->id;

 						foreach($trainee_ids as $trainee_id){
 							$this->db->select('esr.*, es.exercise_id');
 							$this->db->where_in('esr.exercise_set_id', $exercise_set_ids);
 							$this->db->where('esr.trainee_id', $trainee_id);
 							$this->db->where('esr.resultweight !=', 'Body Weight');
 							$this->db->where('esr.resultweight !=', 'Failure');
 							
 							if($reps != 99999)
 								$this->db->where('esr.resultreps', $reps);


 							$this->db->order_by('esr.resultweight + 0', 'desc');
 							$this->db->limit(1);
 							$this->db->from('exercise_set_results as esr');
 							$this->db->join('exercise_set as es', 'es.id = esr.exercise_set_id');
 							


 							$esr = $this->db->get();

 							if($esr->num_rows() > 0){
 								$rval = $esr->row()->resultweight;

 								if($rval > $arr[$trainee_id]){
 									$arr[$trainee_id] = $rval;
 									$arr[$trainee_id.'_reps'] = $esr->row()->resultreps;
 									$arr[$trainee_id.'_eid'] = $esr->row()->exercise_id;
 								}
 							}
 						}


 						
 						

 					}

 				}



 			}
 			else{
 				continue;
 			}

 			if(TRUE){ // $reps != 99999
				// CHECK IF ALL VALUE IS ZERO THEN NO INSERT IN RESPONSE

				$check_all_zero = array();
				foreach($trainee_ids as $trainee_id){
					$check_all_zero[] = $arr[$trainee_id];
				}

				$flag = FALSE;
				foreach($check_all_zero as $zero){
					if($zero > 0)
						$flag = TRUE;
				}

				if($flag){
					if(count($trainee_ids) > 1){
						$bulb = TRUE;
						$same = $arr[$trainee_ids[0]];
						foreach($check_all_zero as $zero){
							if($same != $arr[$trainee_id]){
								$bulb = FALSE;
							}
						}

						if($bulb){
							$arr['status'] = 2;

							$arr_trainee_ids = array();
							$arr_trainee_weight = array();
							$arr_trainee_reps = array();
							$arr_trainee_name = array();
							$arr_trainee_wid = array();
							$arr_trainee_eid = array();

							foreach($trainee_ids as $trainee_id){
								$arr_trainee_ids[] = $trainee_id;
								$arr_trainee_weight[] = $arr[$trainee_id];
								$arr_trainee_reps[] = $arr[$trainee_id.'_reps'];
								$arr_trainee_name[] = $arr[$trainee_id.'_name'];
								$arr_trainee_wid[] = $arr[$trainee_id.'_wid'];
								$arr_trainee_eid[] = $arr[$trainee_id.'_eid'];
							}

							$arr['arr_trainee_ids'] = implode('<=**=>', $arr_trainee_ids);
							$arr['arr_trainee_weight'] = implode('<=**=>', $arr_trainee_weight);
							$arr['arr_trainee_reps'] = implode('<=**=>', $arr_trainee_reps);
							$arr['arr_trainee_name'] = implode('<=**=>', $arr_trainee_name);
							$arr['arr_trainee_wid'] = implode('<=**=>', $arr_trainee_wid);
							$arr['arr_trainee_eid'] = implode('<=**=>', $arr_trainee_eid);

						}
					}

					$exDate = strtotime($arr['date']);
					if( $exDate >= $from && $exDate <= $to ){
		 				$response[] = $arr;
					}
				}
			}
			else{
 				
 				$exDate = strtotime($arr['date']);
				if( $exDate >= $from && $exDate <= $to ){
	 				$response[] = $arr;
				}
			

			}

 		}

 		// echo "<pre>";
 		// print_r($response);
 		// echo "</pre>";
 		// die();

 		$data['response'] = $response;
 		$this->load->view('trainer/graph', $data);
 	}

 	/*
 	public function _create_graph($trainee_ids=0, $workout_id=0, $exercise_id=0, $reps=9999){
 		$data['reps'] = $reps;
 		
 		$data['workout'] = $this->admin_model->get_row('workout', array('id' => $workout_id));
 		
 		$trainee_ids = explode('-', $trainee_ids);
 		$this->db->where_in('id', $trainee_ids);
 		$this->db->select('id, fname, lname');
 		$this->db->order_by('lname', 'asc');
 		$data['trainees'] = $this->admin_model->get_result('trainee');

 		$data['eid'] = $exercise_id;

 		$data['wid'] = $workout_id;
 		
 		$data['exercise_id'] = $this->admin_model->get_row('exercise', array('id' => $exercise_id));
 		
 		$this->db->select('id, value, reps');
 		$data['sets'] = $this->admin_model->get_result('exercise_set', array('exercise_id' => $exercise_id));

 		$sorted_tid = array();
 		foreach($data['trainees'] as $tr)
 			$sorted_tid[] = $tr->id;

 		$nsets = array();
 		foreach($data['sets'] as $r){
 			$arr = (array)$r;
 			foreach($sorted_tid as $trid){
 				$row = $this->admin_model->get_row('exercise_set_results', array('trainee_id' => $trid , 'exercise_set_id' => $r->id));
 				if($row){
 					$arr[$trid] = $row->resultweight;
 				}
 				else{
 					$arr[$trid] = '0';
 				}
 			}
 			$nsets[] = $arr;
 		}

 		$nreps = array();
 		foreach($data['sets'] as $r){
 			$arr = (array)$r;
 			foreach($sorted_tid as $trid){
 				$row = $this->admin_model->get_row('exercise_set_results', array('trainee_id' => $trid , 'exercise_set_id' => $r->id));
 				if($row){
 					$arr[$trid] = $row->resultreps;
 					$arr['reps'] = $row->resultreps;
 					$arr['value'] = $row->resultweight;
 				}
 				else{
 					$arr[$trid] = '0';
 					$arr['reps'] = '0';
 					$arr['value'] = '0';
 				}
 			}
 			$nreps[] = $arr;
 		}

 		// echo "<pre>";
 		// print_r($data['trainees']);
 		// print_r($data['sets']);
 		// print_r($nsets);
 		// die();

 		$data['sets'] = $nsets;
 		$data['nreps'] = $nreps;
 		
 		$this->load->view('trainer/graph', $data);
 	}
 	*/

 	public function sort_trainee(){
		$this->session->set_userdata('sort_trainee',$_POST);
		redirect(_INDEX.'trainer/manage_trainee');
	}

	public function get_reps_by_exercise($id=0, $trainee_ids=0){
		$flag = TRUE;

		$exercise_ids = array($id);

		$default_exercise_id = $this->admin_model->get_default_exercise_id($id);
		if(!$default_exercise_id){
 			$flag = FALSE;
 		}

 		if($flag){
	 		$this->db->where_in('trainee_id', $trainee_ids);
	 		$query = $this->db->get('trainee_workout');
	 		if(!($query->num_rows() > 0)){
 				$flag = FALSE;
	 		}
	 		if($flag){
		 		$workout_ids = array();
		 		foreach($query->result() as $row)
		 			$workout_ids[] = $row->workout_id;

		 		$this->db->select('id, date');
		 		$this->db->where_in('id', $workout_ids);
		 		$this->db->order_by('date', 'asc');
		 		$query = $this->db->get('workout');
		 		if(!($query->num_rows() > 0)){
 					$flag = FALSE;
		 		}

		 		if($flag){
			 		$result = $query->result();
			 		foreach($result as $row){
			 			$this->db->where('default_exercise_id', $default_exercise_id);
			 			$this->db->where('workout_id', $row->id);
			 			$exercises = $this->db->get('exercise');
			 			if($exercises->num_rows() > 0){
			 				foreach($exercises->result() as $e_row){
			 					$exercise_ids[] = $e_row->id;
			 				}
			 			}
			 		}
			 	}
		 	}
	 	}




		$trainee_ids = explode('-', $trainee_ids);
		$this->db->order_by('reps','asc');

		$this->db->where_in('exercise_id', $exercise_ids);

		$sets = $this->admin_model->get_result('exercise_set');
		$res = '<option value="">Select Reps</option>';
		if($sets){
			foreach($sets as $row){
				foreach($trainee_ids as $t_id)
					$this->db->or_where("( `exercise_set_id` = '".$row->id."' AND `trainee_id` = '".$t_id."' )");
			}

			$this->db->group_by('resultreps');
			$query = $this->db->get('exercise_set_results');
			
			if($query->num_rows() > 0){
				
				$arr = array();

				foreach($query->result() as $row){
					if($row->resultreps == 'Failure' || $row->resultreps == 'N/A')
						continue;

					// $res .= '<option>'.$row->resultreps.'</option>';
					$arr[] = $row->resultreps;
				}

				sort($arr);
				foreach($arr as $var)
					$res .= '<option>'.$var.'</option>';
			}
		}

		echo $res;
		exit();

		// $res = '<option value="">Select Reps</option>';
		// if($sets){
		// 	foreach($sets as $set){
		// 		$array[$set->reps] = 1;
		// 	}
			
		// 	foreach($array as $index => $value){
		// 		$res .= '<option>'.$index.'</option>';
		// 	}
		// }
		// echo $res;
		// exit();
	}

	public function ajax_workout_details_for_dashboard_graph(){
		extract($_POST);
		$currentWorkout = $this->admin_model->get_workout_with_notes($workOutId);
		$trainee_workout_notes = $this->admin_model->trainee_workout_notes($workOutId,$trainee_id);
		$workouts = $this->admin_model->getWorkoutDetails($workOutId);
		$result = array();
		$i=0;
		if($workouts){
			foreach ($workouts as $key => $value) {
				$result[$value->eId]['exercise'] = $value->name;
				$result[$value->eId]['exercise_id'] = $value->eId;
				$result[$value->eId]['image'] = $value->image;
				$result[$value->eId]['trainee_notes'] = $this->admin_model->get_row('exercise_notes',array('exercise_id'=>$value->eId,'trainee_id'=>$trainee_id));
				
				if($result[$value->eId]['trainee_notes']){
					$result[$value->eId]['status'] = 1;
				}
				else{
					$result[$value->eId]['status'] = 0;
				}

				$result[$value->eId]['description'] = $value->description;
				$result[$value->eId]['resttime'] = $value->resttime;

				$value = (array)$value;

				$result[$value['eId']]['sets'][$i] = $value;
				$result[$value['eId']]['sets'][$i]['resultweight'] = '0';
				$result[$value['eId']]['sets'][$i]['resultreps'] = '0';
				$result[$value['eId']]['sets'][$i]['status'] = '0';
				$trainee_weight_reps = $this->admin_model->get_row('exercise_set_results',array('exercise_set_id'=>$value['id'],'trainee_id'=>$trainee_id));
				if($trainee_weight_reps){
					$result[$value['eId']]['sets'][$i]['resultweight'] = $trainee_weight_reps->resultweight;
					$result[$value['eId']]['sets'][$i]['resultreps'] = $trainee_weight_reps->resultreps;
					$result[$value['eId']]['sets'][$i]['resulttime'] = $trainee_weight_reps->resulttime;
					$result[$value['eId']]['sets'][$i]['status'] = '1';
				}
				$i++;
			}
		}
		$currentWorkout = (array)$currentWorkout;

		if($trainee_workout_notes){
			$currentWorkout['notes'] = $trainee_workout_notes->notes;
			$currentWorkout['status'] = 1;
		}
		else{
			$currentWorkout['notes'] = 'No comment';
			$currentWorkout['status'] = 0;
		}

		if (!empty($result)){
			$response = array('status' => true, 'details' => $result, 'currentWorkout' => $currentWorkout, 'dateToshow' => date('m/d/y', strtotime($date)));
			$response['trainee_workout_notes'] = $trainee_workout_notes;
			echo json_encode($response);
		}
		else{
			echo json_encode(array('status' => false));
		}
	}
}