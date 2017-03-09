<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trainee_dashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index($offset = 0){
		if((trainee_login_in()===FALSE) && (trainer_login_in()===FALSE))
			redirect(_INDEX.'login/trainee_login');
		
		if(!(trainee_login_in()===FALSE)){
			$info = $this->session->userdata('traineeInfo');
			$data['trainee_id'] = $info['id'];
			$data['is_trainee'] = TRUE;
			$trainee_id = $info['id'];
		}
		elseif(!((trainer_login_in()===FALSE))){
			$data['trainee_id'] = get_trainee_id_set_by_trainer();
			$data['is_trainee'] = FALSE;
			$trainee_id = get_trainee_id_set_by_trainer();
		}
		else{
			redirect(_INDEX.'login/trainee_login');
		}
		
		$data['workout'] = $this->admin_model->get_todays_workout_t($trainee_id);
		
		$data['template'] = 'trainee_dashboard/dashboard';

		if(!(trainee_login_in()===FALSE)){
			$this->load->view('templates/trainee_template', $data);
		}
		elseif(!((trainer_login_in()===FALSE))){
			$this->load->view('templates/trainer_template', $data);
		}
		else{
			redirect(_INDEX.'login/trainee_login');
		}
	}

	public function ajaxWorkoutDetails(){
		// $_POST = array(
		// 	'trainee_id' => 251,
		// 	'workOutId' => 781,
		// 	'date' => 'Sun Feb 01 2015 00:00:00 GMT+0530 (India Standard Time)'
		// );

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
				
			// faraz work
			$circuit_id = $value->circuit_id;
			$circuit_name = '';
			$circuit_description = '';
			if($circuit_id != 0){
				$circuit = $this->admin_model->get_row('circuit',array('id' => $value->circuit_id));
				if($circuit){
					$circuit_name = trim($circuit->name);
					$circuit_description = trim($circuit->description);
				}
			}
			$result[$value->eId]['circuit_id'] = $circuit_id;
			$result[$value->eId]['circuit_name'] = $circuit_name;
			$result[$value->eId]['circuit_description'] = $circuit_description;				
			// faraz work

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
				$result[$value['eId']]['sets'][$i]['resulttime'] = '0';
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
			// echo '<pre>';
			// print_r($response);
		}
		else{
			echo json_encode(array('status' => false));
		}
	}


	public function ajax_update_sets(){
		$set_id = $this->input->post('setid');
		
		if(!(trainee_login_in()===FALSE)){
			$trainee_id = get_trainee_id();
		}
		elseif(!((trainer_login_in()===FALSE))){
			$trainee_id = get_trainee_id_set_by_trainer();
		}
		else{
			echo '';
			die();
		}

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

		$this->db->select('e.workout_id');
		$this->db->where('es.id', $set_id);
		$this->db->join('exercise as e', 'e.id = es.exercise_id');
		$this->db->from('exercise_set as es');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			$workout_id = $query->row()->workout_id;
			$exercises = $this->admin_model->get_result('exercise', array('workout_id' => $workout_id));
			if($exercises){
				$exercise_ids = array();
				foreach($exercises as $row)
					$exercise_ids[] = $row->id;

				$this->db->where_in('exercise_id', $exercise_ids);
				$query = $this->db->get('exercise_set');
				if($query->num_rows() > 0){
					$orig_e_set = $query->num_rows();

					$exercise_set_ids = array();

					foreach($query->result() as $row)
						$exercise_set_ids[] = $row->id;

					$this->db->where_in('es.id', $exercise_set_ids);
					$this->db->join('exercise_set_results as esr', 'esr.exercise_set_id = es.id');
					$this->db->where('esr.trainee_id', $trainee_id);
					$this->db->from('exercise_set as es');
					$query = $this->db->get();

					if($query->num_rows() > 0){
						$res_e_set = $query->num_rows();

						if($res_e_set == $orig_e_set){
							$this->db->where('workout_id', $workout_id);
							$this->db->where('trainee_id', $trainee_id);
							$query = $this->db->get('workout_notes');
							
							if($query->num_rows() > 0){
								// DO NOTHING
							}
							else{
								$udata = array(
									'token'=>get_token(),
									'lastupdated'=>time(),
									'workout_id'=> $workout_id,
									'notes'=>'',
									'trainee_id'=>$trainee_id
								);
								$this->db->insert('workout_notes', $udata);
							}
						}
					}
				}
			}
		}

		echo "1";
	}

	public function ajax_update_set_view($set_id='',$workout_id=''){
		if(!(trainee_login_in()===FALSE)){
			$trainee_id = get_trainee_id();
		}
		elseif(!((trainer_login_in()===FALSE))){
			$trainee_id = get_trainee_id_set_by_trainer();
		}
		else{
			echo '';
			die();
		}

		$where = array('id' => $set_id);
		$data['set'] = $this->admin_model->get_row('exercise_set',$where);
		
		$where = array('exercise_set_id' => $set_id,'trainee_id' => $trainee_id);
		$data['result'] = $this->admin_model->get_row('exercise_set_results',$where);

		$data['set_id'] = $set_id;
		$data['workout_id'] = $workout_id;
		$view = $this->load->view('trainee_dashboard/ajax_update_set_view',$data,TURE);
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