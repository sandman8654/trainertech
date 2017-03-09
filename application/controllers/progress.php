<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Progress extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index($offset=0){
		if(trainee_login_in()===FALSE){
			redirect(_INDEX.'login/trainee_login');
		}

		$data['reps'] = $this->get_reps();

		$trainee_id = get_trainee_id();
		$workouts = $this->admin_model->get_result('trainee_workout', array('trainee_id' => $trainee_id));
		/*
		$data['exercises'] = FALSE;
		if($workouts){
			foreach($workouts as $workout){
				$this->db->or_where('workout_id', $workout->workout_id);
			}
			$data['exercises'] = $this->admin_model->get_result('exercise');
		}
		*/

		$data['exercises'] = $this->admin_model->get_common_exercise(array($trainee_id));

		$data['template'] = 'progress/index';
        $this->load->view('templates/trainee_template', $data);
	}

	public function get_reps(){
		$trainee_id = get_trainee_id();
		$this->db->select( 'resultreps' );
		$this->db->where( 'trainee_id' , $trainee_id );
		$this->db->where( 'resultreps !=' , 'N/A' );
		$this->db->where( 'resultreps !=' , 'Failure' );
		$this->db->order_by( 'resultreps' , 'ASC' );
		$this->db->group_by( 'resultreps' );
		$query = $this->db->get('exercise_set_results');
		if( $query->num_rows() > 0 ){
			$result = $query->result();
			foreach( $result as $row ){
				$res_array[] = $row->resultreps;
			}
			sort($res_array);
			return $res_array;
		}else{
			return false;
		}
	}


	public function create_bar_graph($graph_sort_by=0,$given_reps=0, $from, $to){
		$from = strtotime(str_replace('-','/',$from));	
		$to = strtotime(str_replace('-','/',$to));	

		$trainee_id = get_trainee_id();
		$workouts = $this->admin_model->get_result('trainee_workout', array('trainee_id' => $trainee_id));
		$exercises = FALSE;
		$data['result'] = FALSE;
		if($workouts){
			foreach($workouts as $workout){
				$this->db->or_where('e.workout_id', $workout->workout_id);
			}
			
			$this->db->select('e.*, w.date');

			$this->db->join('workout as w', 'w.id = e.workout_id');

			$this->db->from('exercise as e');

			$exercises = $this->db->get();

			if($exercises->num_rows() > 0){
				$exercises = $exercises->result();
			}
			else{
				$exercises = FALSE;
			}
		}		
		
		if($exercises){
			if($graph_sort_by == '0'){
				$result = array();
				foreach($exercises as $row){
					$eid = $row->id;
					$arr = array();
					$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));
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
						
						if($query->row()->resultweight == 'Body Weight' || $query->row()->resultweight == 'N/A'){
							$rval = '0';
						}else{
							if($query->row()->resultreps < $given_reps || $query->row()->resultreps == 'Failure' || $query->row()->resultreps == 'N/A'){
								$rval = '0';
							}else{
								$rval = $query->row()->resultweight;
							}
						}
						
						if($org_row->value == 'Body Weight' || $org_row->value == 'N/A')
							$oval = '0';
						else
							$oval = $org_row->value;
						
						$arr = array(
							'ename' => $row->name,
							'date' => $row->date,
							'rval' => $rval,
							'oval' => $oval
						);
						
						$exDate = strtotime($arr['date']);
						if( $exDate >= $from && $exDate <= $to ){
							$result[] = $arr;
						}					

					}
				}
				if(count($result) > 0){
					$data['result'] = $result;
				}
			}
			else if($graph_sort_by == '1'){
				$result = array();
				foreach( $exercises as $row ){
					$eid = $row->id;
					$arr = array();
					$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));
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
						$rval = 0;
						$oval = 0;
						
						$i = 0;
						foreach( $query->result() as $val ){
							if($val->resultreps < $given_reps || $val->resultweight == 'Body Weight' || $val->resultweight == 'N/A'){
								$rval = $rval + 0;
							}else{
								if($val->resultreps < $given_reps || $val->resultreps == 'Failure' || $val->resultreps == 'N/A'){
									$rval = $rval + 0;
								}else{
									$rval = $rval + $val->resultweight;
									$i++; 
								}
							}	
						}
						
						$j = 0;
						foreach ($exercise_sets_vals as  $value) {
							if($value->value != 'Body Weight' && $value->value != 'N/A')
								$oval = $oval + $value->value;
							else	
								$oval = $oval + 0;
								$j++;
						}
								
						
						if($i == 0){
							$avg_rval = 0;
						}else{
							$avg_rval = (int)( $rval / $i );
						}

						$arr = array(
							'ename' => $row->name,
							'date' => $row->date,
							'rval' => $avg_rval,
							'oval' => (int)( $oval / $j )
						);

						$exDate = strtotime($arr['date']);
						if( $exDate >= $from && $exDate <= $to ){
							$result[] = $arr;
						}					


					}
				}
				if(count($result) > 0){
					$data['result'] = $result;
				}
			}
			else if($graph_sort_by == '2'){
				$result = array();
				foreach( $exercises as $row ){
					$eid = $row->id;
					$arr = array();
					$exercise_sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $eid));
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
								if($rval < $val->resultweight && $val->resultreps >= $given_reps){
									if($val->resultreps < $given_reps || $val->resultreps == 'Failure' || $val->resultreps == 'N/A'){
										$rval = 0;
									}else{
										$rval = $val->resultweight;
									}
								}
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
							'date' => $row->date,
							'rval' => (int)( $rval ),
							'oval' => (int)( $oval )
						);

						$exDate = strtotime($arr['date']);
						if( $exDate >= $from && $exDate <= $to ){
							$result[] = $arr;
						}					

					}
				}
				if(count($result) > 0){
					$data['result'] = $result;
				}
			}
		}

		$this->load->view('progress/iframe_bar_graph', $data);
	}


/**
				LINE GRAPH
*/

 	public function create_line_graph($exercise_id=0, $reps, $from, $to ){
		$from = strtotime(str_replace('-','/',$from));	
		$to = strtotime(str_replace('-','/',$to));	

 		$data['reps'] = $reps;
 		$trainee_ids = array(get_trainee_id());
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
 			echo "<h3>No results.</h3>";
 			// $this->load->view('progress/iframe_line_graph', $data);
 			die();
 		}

 		$this->db->where_in('trainee_id', $trainee_ids);
 		$query = $this->db->get('trainee_workout');

 		if(!($query->num_rows() > 0)){
 			$data['response'] = FALSE;
 			echo "<h3>No results.</h3>";
 			// $this->load->view('progress/iframe_line_graph', $data);
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
 			echo "<h3>No results.</h3>";
 			// $this->load->view('progress/iframe_line_graph', $data);
 			die();
 		}

 		$result = $query->result();
 		$response = array();
 		foreach($result as $row){
 			$arr = array();

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

 		// 	$check_all_zero = array();

			// foreach($trainee_ids as $trainee_id){
			// 	$check_all_zero[] = $arr[$trainee_id];
			// }

			// $flag = FALSE;
			// foreach($check_all_zero as $zero){
			// 	if($zero > 0)
			// 		$flag = TRUE;
			// }

			// if($flag)
 		// 		$response[] = $arr;

 			$exDate = strtotime($arr['date']);
 			if( $exDate >= $from && $exDate <= $to ){
	 			$response[] = $arr;
 			}

 		}

 		// echo '<pre>';
 		// print_r($response);
 		// die();


 		$data['response'] = $response;
 		// $this->load->view('progress/iframe_line_graph', $data);

 		$this->load->view('progress/iframe_line_graph', $data);
 	}

	public function get_reps_by_exercise($id=0){
		$trainee_ids = array(get_trainee_id());

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

	 	// $trainee_ids = explode('-', $trainee_ids);
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

		// $this->db->order_by('reps','asc');
		// $sets = $this->admin_model->get_result('exercise_set', array('exercise_id' => $id) );
		// $res = '<option value="">Select Reps</option>';
		
		// if($sets){
		// 	foreach($sets as $row){
		// 		$this->db->or_where("( `exercise_set_id` = '".$row->id."' AND `trainee_id` = '".get_trainee_id()."' )");
		// 	}

		// 	$this->db->group_by('resultreps');
		// 	$query = $this->db->get('exercise_set_results');
			
		// 	if($query->num_rows() > 0){
		// 		foreach($query->result() as $row){
		// 			if($row->resultreps == 'Failure' || $row->resultreps == 'N/A')
		// 				continue;

		// 			$res .= '<option>'.$row->resultreps.'</option>';
		// 		}
		// 	}
		// }


		// if($sets){
		// 	foreach($sets as $set){ $array[$set->reps] = 1; }
		// 	foreach($array as $index => $value){
		// 		$res .= '<option>'.$index.'</option>';
		// 	}
		// }
		
		// echo $res;
		exit();
	}

	public function get_workout_details($workOutId=0){
		$date = date('Y-m-d');
		$trainee_id = get_trainee_id();
		$currentWorkout = $this->admin_model->get_workout_with_notes($workOutId);
		$trainee_workout_notes = $this->admin_model->trainee_workout_notes($workOutId,$trainee_id);
		$navigateArray = array();
		$workout = $this->admin_model->getWorkoutsOfTheDay($date, $trainee_id);
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
					$result[$value['eId']]['sets'][$i]['status'] = '1';
				}
				$i++;
			}
		}
		$currentWorkout = (array)$currentWorkout;
		if($trainee_workout_notes){
			$currentWorkout['notes'] = $trainee_workout_notes->notes;
			$currentWorkout['status'] = 1;
		}else{
			$currentWorkout['notes'] = 'No comment';
			$currentWorkout['status'] = 0;
		}
		
		if (!empty($result)){
			$response = array('details' => $result, 'currentWorkout' => $currentWorkout, 'dateToshow' => date('m/d/y', strtotime($date)));
			if (!empty($navigateArray)) {
				$response['navigateArray'] = $navigateArray;
			}
			$response['trainee_workout_notes'] = $trainee_workout_notes;
			return $response;
		}else{
			return false;
		}
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