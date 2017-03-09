<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Trainee extends CI_Controller {

	public function __construct(){

		parent::__construct();

	    $this->load->model('admin_model');

	}

	

	public function index($offset = 0){
		redirect(_INDEX.'trainee_dashboard');
		/*

		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');
		
		$info = $this->session->userdata('traineeInfo');
		$data['trainee_id'] = $info['id'];
		// echo "<pre>";				
		$data['workout'] = $this->admin_model->get_todays_workout_t($info['id']);
		// print_r($data); 
		// die();

		

		$data['template'] = 'trainee/dashboard';

        $this->load->view('templates/trainee_template', $data);
        */

	}

	

	public function logout(){

		$this->session->set_userdata('traineeInfo','');

		$this->session->unset_userdata('traineeInfo');

		$this->session->set_flashdata('success_msg','Logout successfully.');		

		redirect(_INDEX.'login/trainee_login');

	}

	

	public function all($offset=0){		

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



	public function add(){

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');



		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[trainee.email]');							

		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]');

		$this->form_validation->set_rules('confirm_password', 'confirm password', 'required|matches[password]');	

		$this->form_validation->set_rules('address', 'address', 'required');							

		$this->form_validation->set_rules('city', 'city', 'required');	

		$this->form_validation->set_rules('trainer_id', 'trainer', 'required');

								

		if ($this->form_validation->run() == TRUE){			

			$data=array(

				'slug'=>create_slug('trainee', $this->input->post('fname').' '.$this->input->post('lname')),

				'fname'=>$this->input->post('fname'),

				'lname'=>$this->input->post('lname'),

				'email'=>$this->input->post('email'),

				'password'=>sha1($this->input->post('password')),	

				'address'=>$this->input->post('address'),	

				'city'=>$this->input->post('city'),									

				'trainer_id'=>$this->input->post('trainer_id'),	

				'created' => date('Y-m-d H:i:s')			

			);	

			$data['token'] = get_token();

			$data['lastupdated'] = time();

			$this->admin_model->insert('trainee',$data);	

			$this->session->set_flashdata('success_msg',"trainee has been added successfully.");

			redirect(_INDEX.'trainee/all');

		}

		$data['trainer'] = $this->admin_model->get_result('trainer');

		

		if(!$data['trainer']){

			$this->session->set_flashdata('error_msg',"Please add trainer then try adding trainee.");

			redirect(_INDEX.'trainer/all');

		}



		$data['template'] = 'trainee/add';

        $this->load->view('templates/admin_template', $data);		

	}



	public function edit($slug = ""){

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');



		$data['trainee'] = $this->admin_model->get_row('trainee', array('slug'=>$slug));

		// echo "<pre>";

		// print_r($data['trainer']); die();

		

		$this->form_validation->set_rules('fname', 'firstname', 'required');							

		$this->form_validation->set_rules('lname', 'lastname', 'required');							

		$this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_check_is_email_unique['.$data['trainee']->email.']');							

		$this->form_validation->set_rules('address', 'address', 'required');							

		$this->form_validation->set_rules('city', 'city', 'required');	

		$this->form_validation->set_rules('trainer_id', 'trainer', 'required');



								

		if ($this->form_validation->run() == TRUE){			

			$update=array(

				'slug'=>create_slug_for_update('trainee', $this->input->post('fname').' '.$this->input->post('lname'), $data['trainee']->id),

				'fname'=>$this->input->post('fname'),

				'lname'=>$this->input->post('lname'),

				'email'=>$this->input->post('email'),

				'address'=>$this->input->post('address'),	

				'city'=>$this->input->post('city'),		

				'trainer_id'=>$this->input->post('trainer_id'),							

				'updated' => date('Y-m-d H:i:s')		

			);			

			$update['lastupdated'] = time();

			$this->admin_model->update('trainee',$update, array('slug'=>$slug));

			//print_r($update); die();

			$this->session->set_flashdata('success_msg',"trainee has been updated successfully.");

			redirect(_INDEX.'trainee/all');

		}



		$data['trainer'] = $this->admin_model->get_result('trainer');

		

		if(!$data['trainer']){

			$this->session->set_flashdata('error_msg',"Please add trainer then try adding trainee.");

			redirect(_INDEX.'trainer/all');

		}



		$data['template'] = 'trainee/edit';

        $this->load->view('templates/admin_template', $data);		

	}

	

	public function delete($slug=""){	

		if(admin_login_in()===FALSE)

			redirect(_INDEX.'login');

		

		$this->admin_model->delete('trainee',array('slug'=> $slug));		

		$this->session->set_flashdata('success_msg',"trainee has been deleted successfully.");

		redirect(_INDEX.'trainee/all');

	}



	public function check_is_email_unique($new_email, $old_email){

		if ($old_email === $new_email) {

				return TRUE;

		}else{

			$resp = $this->admin_model->get_row('trainee', array('email' => $new_email));

			if ($resp) {

				$this->form_validation->set_message('check_is_email_unique', 'This email is already exist.');

				return FALSE;

			}else{

				return TRUE;

			}

		}

	}



	// CHANGE PASSWORD



	public function changePassword(){

		if(trainee_login_in()===FALSE)

			redirect(_INDEX.'login/trainee_login');



		$id = get_trainee_id();

		$this->form_validation->set_rules('old', 'old password', 'required|callback_check_old_pwd');							

		$this->form_validation->set_rules('new', 'new password', 'required');							

		$this->form_validation->set_rules('con', 'confirm password', 'required|matches[new]');							

		if ($this->form_validation->run() == TRUE)

		{			

			$new = $this->input->post('new');

			$new = sha1(trim($new));

			$this->admin_model->update('trainee',array('password' => $new, 'lastupdated' => time()), array('id'=>$id));

			$this->session->set_flashdata('success_msg','Password has been changed successfully.');

			redirect(_INDEX.'trainee/changePassword');

		}



		$data['template'] = 'trainee/changePassword';

        $this->load->view('templates/trainee_template', $data);		

	}



 	public function  check_old_pwd($x)

 	{

		$id = get_trainee_id();

		$pwd=sha1($x);

		$row = $this->admin_model->get_row('trainee', array('id'=> $id));

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



	public function support($offset=0){		
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$limit=10;
		$data['support']=$this->admin_model->get_pagination_result('support', $limit,$offset, array('trainee_id'=>get_trainee_id()));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainee/support/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('support', 0, 0, array('trainee_id'=>get_trainer_id()));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'trainee/support';
        $this->load->view('templates/trainee_template', $data);			
	}	

	public function add_query(){
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$this->form_validation->set_rules('subject', 'subject', 'required|min_length[3]|max_length[25]');							
		$this->form_validation->set_rules('message', 'message', 'required');							

		$traineeinfo = get_trainee_info();										
		if ($this->form_validation->run() == TRUE){			
			$data=array(
				'subject'=>$this->input->post('subject'),				
				'message'=>$this->input->post('message'),				
				'trainee_id'=>$traineeinfo->id,		
				'trainer_id'=>$traineeinfo->trainer_id,		
				'trainee_name'=>$traineeinfo->fname.' '.$traineeinfo->lname,		
				'trainee_email'=>$traineeinfo->email,
				'by'=>2,				
				'status'=>1,				
				'is_read'=>0,				
				'created' => date('Y-m-d H:i:s')			
			);

			$data['token2'] = get_token();
			$data['lastupdated'] = time();		
			
			$id = $this->admin_model->insert('support',$data);		
			$token = time();
			$token = $token.$id;
			$this->admin_model->update('support', array('token'=>$token , 'lastupdated' => time()), array('id'=>$id));
			$this->session->set_flashdata('success_msg',"Query has been submitted successfully.");
			redirect(_INDEX.'trainee/support');
		}

		$data['template'] = 'trainee/add_query';
        $this->load->view('templates/trainee_template', $data);		
	}

	// public function reply($token2 = ""){
	// 	if(trainee_login_in()===FALSE)
	// 		redirect(_INDEX.'login/trainee_login');

	// 	if($token2 == "")
	// 		redirect(_INDEX.'trainee/support');

	// 	$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
	// 	$this->form_validation->set_rules('reply', 'reply', 'required');									
	// 	if ($this->form_validation->run() == TRUE)
	// 	{			
	// 		$update=array(
	// 			'message'=>$this->input->post('reply'),				
	// 			'support_token2'=>$token2,				
	// 			// 'support_id'=>$id,				
	// 			'sender_id'=>get_trainee_id(),	
	// 			'send_by'=>3,	
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
	// 	// $data['conversation'] = $this->admin_model->get_conversation($data['support']->id);		
	// 	$data['template'] = 'trainee/reply';
 //        $this->load->view('templates/trainee_template', $data);		
	// }


	public function reply($token2 = ""){
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		if($token2 == "")
			redirect(_INDEX.'trainee/support');

		/*Update read status*/
		$update_read = array('trainee_read'=>1);
		$this->admin_model->update('conversation',$update_read,array('support_token2'=>$token2));
		/*Update read status*/

		$data['support'] = $this->admin_model->get_row('support', array('token2'=>$token2));
		
		$this->form_validation->set_rules('reply', 'reply', 'required');									
		if ($this->form_validation->run() == TRUE)
		{			
			$update=array(
				'message'=>$this->input->post('reply'),				
				'support_token2'=>$token2,				
				// 'support_id'=>$id,				
				'sender_id'=>get_trainee_id(),	
				'send_by'=>3,	
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
		// $data['conversation'] = $this->admin_model->get_conversation($data['support']->id);		
		$data['template'] = 'trainee/reply';
        $this->load->view('templates/trainee_template', $data);		
	}

	/*
	public function delete($id=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login');

		$this->admin_model->delete('support',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"support has been deleted successfully.");
		redirect(_INDEX.'trainee/support');
	} */


	public function status($support_id,$status){
		$this->admin_model->update('support', array('status'=>$status, 'lastupdated' => time()), array('token2'=>$support_id));
		redirect(_INDEX.'trainee/reply/'.$support_id);
	}

	public function workouts($offset=0){
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$trainee_id = get_trainee_id();
		$limit = 10;
		$data['workouts']=$this->admin_model->get_trainee_workouts($trainee_id, $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'trainee/workouts/';
		$config['total_rows'] = $this->admin_model->get_trainee_workouts($trainee_id, 0, 0);
		$config['per_page'] = $limit;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();

		$data['template'] = 'trainee/workouts';
		$this->load->view('templates/trainee_template', $data);	
	}

	public function view_workout($workout_id=0){
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$data['workout'] = $this->admin_model->get_workout($workout_id);
		$data['template'] = 'trainee/view_workout';
        $this->load->view('templates/trainee_template', $data);
	}

	public function ajaxWorkoutDetails(){
		extract($_POST);
		$gmtDate = $date;
		$date = explode(" ", $date);
		$date = $date[1].' '.$date[2].' '.$date[3];
		$date = date('Y-m-d', strtotime($date));

		$currentWorkout = $this->admin_model->get_workout_with_notes($workOutId);
		
		$trainee_workout_notes = $this->admin_model->trainee_workout_notes($workOutId,$trainee_id);

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
		$i=0;
		if($workouts){
			foreach ($workouts as $key => $value) {

				$result[$value->eId]['exercise'] = $value->name;
				$result[$value->eId]['exercise_id'] = $value->eId;
				$result[$value->eId]['image'] = $value->image;
				// $result[$value->eId]['notes'] = $value->notes;

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
					$result[$value['eId']]['sets'][$i]['status'] = '1';
				}
				$i++;
			}
		}



		$currentWorkout = (array)$currentWorkout;

		// if($currentWorkout['notes'] == null){
		// 	$currentWorkout['notes'] = 'No comment';
		// 	$currentWorkout['status'] = 0;
		// }

		if($trainee_workout_notes){
			$currentWorkout['notes'] = $trainee_workout_notes->notes;
			$currentWorkout['status'] = 1;
		}
		else{
			$currentWorkout['notes'] = 'No comment';
			$currentWorkout['status'] = 0;
		}

		// print_r($result); die();
		if (!empty($result)){
			$response = array('status' => true, 'details' => $result, 'currentWorkout' => $currentWorkout, 'dateToshow' => date('m/d/y', strtotime($date)));
			if (!empty($navigateArray)) {
				$response['navigateArray'] = $navigateArray;
			}
			$response['trainee_workout_notes'] = $trainee_workout_notes;
			echo json_encode($response);
		}
		else{
			echo json_encode(array('status' => false));
		}
	}

	public function ajax_update_sets(){
		$set_id = $this->input->post('setid');
		$trainee_id = get_trainee_id();
		$row = $this->admin_model->get_row('exercise_set_results', array('trainee_id' => $trainee_id , 'exercise_set_id' => $set_id));
		$data = array(
			'resulttime' => $this->input->post('time'), 
			'resultweight' => $this->input->post('weight'), 
			'resultreps' => $this->input->post('reps'), 
			'lastupdated' => time()
		);
		if($row){
			$this->admin_model->update('exercise_set_results', $data , array('id'=>$row->id));
		}else{
			$data['exercise_set_id'] = $set_id;
			$data['trainee_id'] = $trainee_id;
			$data['token'] = get_token();
			$this->admin_model->insert('exercise_set_results', $data);
		}
		echo "1";
	}

	public function ajax_update_set_view($set_id='',$workout_id=''){
		$trainee_id = get_trainee_id();

		$where = array('id' => $set_id);
		$data['set'] = $this->admin_model->get_row('exercise_set',$where);
		
		$where = array('exercise_set_id' => $set_id,'trainee_id' => $trainee_id);
		$data['result'] = $this->admin_model->get_row('exercise_set_results',$where);

		$data['set_id'] = $set_id;
		$data['workout_id'] = $workout_id;
		$view = $this->load->view('trainee/ajax_update_set_view',$data,TURE);
		echo $view;
	}

	public function create_bar_graph(){
		$workout_id = $this->input->post('workout_id');
		$trainee_id = $this->input->post('trainee_id');
		$graph_sort_by = $this->input->post('graph_sort_by');

		$exercises = $this->admin_model->get_result('exercise', array('workout_id' => $workout_id));

		if(!$exercises){
			echo '0';
			die();
		}

		if($graph_sort_by == '0'){

			$result = array();

			foreach( $exercises as $row ){

				$eid = $row->id;

				$arr = array();

				$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));

				if(!$exercise_sets){
					echo '0';
					die();
				}

				$exercise_set_ids = array();

				foreach( $exercise_sets as $var ){
					$exercise_set_ids[] = $var->id;
				}

				$this->db->where('trainee_id', $trainee_id);
				$this->db->where_in('exercise_set_id', $exercise_set_ids);

				$this->db->limit(1);
				$this->db->order_by('id', 'desc');
				$query = $this->db->get('exercise_set_results'); 

				if($query->num_rows() > 0){

					$org_row = $this->admin_model->get_row('exercise_set', array('id' => $query->row()->exercise_set_id));

					if($query->row()->resultweight == 'Body Weight' && $query->row()->resultweight == 'N/A')
						$rval = '0';
					else
						$rval = $query->row()->resultweight;

					if($org_row->value == 'Body Weight' && $org_row->value == 'N/A')
						$oval = '0';
					else
						$oval = $org_row->value;


					$arr = array(
						'ename' => $row->name,
						'rval' => $rval,
						'oval' => $oval
					);

					$result[] = $arr;
				}
			}

			if(count($result) > 0){
				echo json_encode($result);
				die();
			}
		}
		else if($graph_sort_by == '1'){

			$result = array();

			foreach( $exercises as $row ){

				$eid = $row->id;

				$arr = array();

				$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));

				if(!$exercise_sets){
					echo '0';
					die();
				}

				$exercise_set_ids = array();
				$exercise_sets_vals = array();

				foreach( $exercise_sets as $var ){
					$exercise_set_ids[] = $var->id;
					$exercise_sets_vals[$var->id] = $var;
				}

				$this->db->where('trainee_id', $trainee_id);
				$this->db->where_in('exercise_set_id', $exercise_set_ids);
				$query = $this->db->get('exercise_set_results'); 

				if($query->num_rows() > 0){

					$i = 0;

					$rval = 0;

					$oval = 0;

					foreach( $query->result() as $val ){
						if($val->resultweight != 'Body Weight' && $val->resultweight != 'N/A')
							$rval = $rval + $val->resultweight;
						else	
							$rval = $rval + 0;
					}

					foreach ($exercise_sets_vals as  $value) {
						if($value->value != 'Body Weight' && $value->value != 'N/A')
							$oval = $oval + $value->value;
						else	
							$oval = $oval + 0;

						$i++;
					}

					$arr = array(
						'ename' => $row->name,
						'rval' => (int)( $rval / $i ),
						'oval' => (int)( $oval / $i )
					);

					$result[] = $arr;

				}
			}

			if(count($result) > 0){
				echo json_encode($result);
				die();
			}
		}
		else if($graph_sort_by == '2'){

			$result = array();

			foreach( $exercises as $row ){

				$eid = $row->id;

				$arr = array();

				$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));

				if(!$exercise_sets){
					echo '0';
					die();
				}

				$exercise_set_ids = array();
				$exercise_sets_vals = array();

				foreach( $exercise_sets as $var ){
					$exercise_set_ids[] = $var->id;
					$exercise_sets_vals[$var->id] = $var;
				}

				$this->db->where('trainee_id', $trainee_id);
				$this->db->where_in('exercise_set_id', $exercise_set_ids);
				$query = $this->db->get('exercise_set_results'); 

				if($query->num_rows() > 0){

					$i = 0;

					$rval = 0;

					$oval = 0;

					foreach( $query->result() as $val ){
						if($val->resultweight != 'Body Weight' && $val->resultweight != 'N/A'){
							if($rval < $val->resultweight)
								$rval = $val->resultweight;
						}
					}

					foreach ($exercise_sets_vals as  $value) {
						if($value->value != 'Body Weight' && $value->value != 'N/A'){
							if($oval < $value->value)
								$oval = $value->value;
						}

						$i++;
					}

					$arr = array(
						'ename' => $row->name,
						'rval' => (int)( $rval ),
						'oval' => (int)( $oval )
					);

					$result[] = $arr;

				}
			}

			if(count($result) > 0){
				echo json_encode($result);
				die();
			}
		}

		echo '0';
		die();
	}
}