<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Circuit_workout extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all(0);	
	}

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$trainer_id = get_trainer_id();

		$limit=10;
		$data['workout'] = $this->admin_model->get_pagination_result('workout', $limit,$offset, array('trainer_id'=>$trainer_id , 'type' => 2));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'circuit_workout/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('workout', 0, 0, array('trainer_id'=>$trainer_id , 'type' => 2));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

		$data['template'] = 'circuit_workout/all';
		$this->load->view('templates/trainer_template', $data);			
	}
	
	public function add(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		// $ip = '113.193.104.54';
		// $ip = $this->input->ip_address();		
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];		
		$arr = file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip);
		$response = unserialize($arr);		
		
		if(isset($response['geoplugin_countryCode']) && ($response['geoplugin_countryCode'] != '') ){
			$timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $response['geoplugin_countryCode']);		
			$zo = new DateTimeZone($timezone[0]);
			// $zo = new DateTimeZone('Europe/Paris');
			$dat = new DateTime("now",$zo);				
			$data['mdate'] = $dat->format('m/d/Y'); 		
		}else{
			$data['mdate'] = date('m/d/Y');
		}

		$trainer_id = get_trainer_id();
		$exer = $this->admin_model->get_exercise_pre_name(get_trainer_id());
		$data['default'] = json_encode($exer);		
		$data['default'] = str_replace("'", "", $data['default']);
		
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$this->form_validation->set_rules('date_of_workout', 'date of workout', 'required');							
		$this->form_validation->set_rules('name_of_workout', 'name of workout', 'required');	
		$this->form_validation->set_rules('desc_of_workout', 'desc of workout', 'required');
								
		if ($this->form_validation->run() == TRUE){
			// echo "<pre>";
			// print_r($_POST);
			// die();

			if($this->input->post('anytime') == '1'){
				$_POST['time_of_workout'] = '23:59:59';
			}

			$workout_data = array(				
				'trainer_id'	=> $trainer_id,
				'date'			=> date("Y-m-d", strtotime($this->input->post('date_of_workout'))),
				'time'			=> date("H:i:s", strtotime($this->input->post('time_of_workout'))),
				'name'			=> $this->input->post('name_of_workout'),
				'description'	=> $this->input->post('desc_of_workout'),
				'anytime'		=> $this->input->post('anytime'),
				'created'		=> date("Y-m-d H:i:s"),
			);


			$workout_data['type'] = 2;
			
			if(isset($_FILES['image_workout']['name']) && $_FILES['image_workout']['name']!=""){
				@$file = upload_to_bucket_server('image_workout', 'w_');
				if($file['status']){
					$workout_data['image'] = $file['filename'];
				}
			}else{
				$workout_data['image'] = $this->input->post('wrkoutimg');
			}

			$workout_data['token'] = get_token();
			$workout_data['lastupdated'] = time();

			$workout_id = $this->admin_model->insert('workout',$workout_data);
			
			$trainee_arr = $this->input->post('trainee_id');
			$ind_trainee = array();
			if($trainee_arr){
				$i = 0;
				foreach ($trainee_arr as $row){
					$ind_trainee['trainee_id'] = $row;
					$ind_trainee['is_group'] = 0;
					$ind_trainee['group_id'] = 0;
					$ind_trainee['workout_id'] = $workout_id;
					$ind_trainee['token'] = get_token();
					$ind_trainee['lastupdated'] = time();
					$this->admin_model->insert('trainee_workout', $ind_trainee);
				}
			}

			$group_arr = $this->input->post('group_id');
			$g_trainee = array();

			if($group_arr){
				$i = 0;
				foreach ($group_arr as $group){
					$g_members = $this->admin_model->get_result('group_members',array('group_id'=>$group));
					if($g_members){
						foreach($g_members as $member){
							if(!in_array($member->trainee_id, $trainee_arr)){
								$g_trainee['trainee_id'] = $member->trainee_id;
								$g_trainee['is_group'] = 1;
								$g_trainee['group_id'] = $group;
								$g_trainee['workout_id'] = $workout_id;
								$g_trainee['token'] = get_token();
								$g_trainee['lastupdated'] = time();
								$this->admin_model->insert('trainee_workout', $g_trainee);
							}
						}
					}
				}
			}

			$circuit = $_POST['circuit'];
			$circuit_e_count = $_POST['circuit_e_count'];
			$circuit_description = $_POST['circuit_description'];
			$circuitimg = $_POST['circuitimg'];
			$image = $_FILES['image_circuit']['name'];
			$image_temp = $_FILES['image_circuit']['tmp_name'];

			$cid = array();
			$cid_keys = 0;
			for($i=1; $i<=count($circuit); $i++){
				
				$circuit_data = array(
					'workout_id'	=> $workout_id,
					'name'			=> $circuit[$i-1],
					'description'	=> $circuit_description[$i-1],
					'created'		=> date("Y-m-d H:i:s")
				);

				if(empty($image[$i-1]) && $circuitimg[$i-1] != ""){					
						$circuit_data['image'] = $circuitimg[$i-1];
				}else{
					$file = upload_exercise_to_bucket_server($image[$i-1], $image_temp[$i-1], 'c_');
					if($file['status']){
						$circuit_data['image'] = $file['filename'];
					}
				}

				$circuit_data['token'] = get_token();
				$circuit_data['lastupdated'] = time();

				$circuit_id = $this->admin_model->insert('circuit',$circuit_data);

				$circuit_e_count[$i-1] = $circuit_e_count[$i-1] + $cid_keys;

				while( $cid_keys < $circuit_e_count[$i-1]){
					$cid[] = $circuit_id;
					$cid_keys++;
				}
			}

			// echo "<pre>";
			// print_r($cid);
			// die();


			$exercise = $_POST['exercise'];
			$resttime = $_POST['resttime'];
			$description = $_POST['description'];
			$image = $_FILES['image_exercise']['name'];
			$image_temp = $_FILES['image_exercise']['tmp_name'];
			$default_exercises_id = $_POST['exercise_def_id'];
			$exerciseimg = $_POST['exerciseimg'];
			
			for($i=1;$i<=count($exercise);$i++){
				$exercise_data = array(
					'workout_id'	=> $workout_id,
					'circuit_id'	=> $cid[$i-1],
					'name'			=> $exercise[$i-1],
					'resttime'		=> '', // $resttime[$i-1]
					'description'	=> '',
					'created'		=> date("Y-m-d H:i:s")
				);

				// if(empty($image[$i-1]) && $exerciseimg[$i-1] != ""){					
				// 		$exercise_data['image'] = $exerciseimg[$i-1];
				// }else{
				// 	$file = upload_exercise_to_bucket_server($image[$i-1], $image_temp[$i-1], 'e_');
				// 	if($file['status']){
				// 		$exercise_data['image'] = $file['filename'];
				// 	}

				// }

				$exercise_data['token'] = get_token();
				$exercise_data['lastupdated'] = time();

				$exercise_id = $this->admin_model->insert('exercise',$exercise_data);

				if($this->input->post('sets'.$i)){
					foreach($this->input->post('sets'.$i) as $key => $var){
						$set_data = array(
							'exercise_id'	=> $exercise_id,
							'time'			=> @$_POST['time'.$i][$key],
							'value'			=> $var,
							'reps'			=> @$_POST['reps'.$i][$key],
							'created'		=> date("Y-m-d H:i:s")
						);
						$set_data['token'] = get_token();
						$set_data['lastupdated'] = time();
						$this->admin_model->insert('exercise_set', $set_data);
					}
				}
			}

			$this->session->set_flashdata('success_msg',"Circuit workout has been added successfully.");
			redirect(_INDEX.'circuit_workout/all');
		}
		
		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>get_trainer_id()));
		if(!$data['trainee']){
			$this->session->set_flashdata('error_msg',"Please add trainee first then try to add circuit workout.");
			redirect(_INDEX.'trainer/manage_trainee');
		}

		$data['group'] = $this->admin_model->get_result('group', array('trainer_id'=>$trainer_id,'parent_id'=>0));
		$data['template'] = 'circuit_workout/add';
		$this->load->view('templates/trainer_template', $data);
	}

	public function edit($workout_id = ''){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		// $ip = '113.193.104.54';
		// $ip = $this->input->ip_address();

		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$arr = file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip);
		$response = unserialize($arr);
		if(isset($response['geoplugin_countryCode']) && ($response['geoplugin_countryCode'] != '') ){
			$timezone = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $response['geoplugin_countryCode']);		
			$zo = new DateTimeZone($timezone[0]);
			$dat = new DateTime("now",$zo);				
			$data['mdate'] = strtotime($dat->format('m/d/Y')); 		
		}else{
			$data['mdate'] = time();
		}
		
		// $data['mdate'] = time();

		$trainer_id = get_trainer_id();	
		
		$data['workout'] = $this->admin_model->get_circuit_workout($workout_id);

		// echo "<pre>";
		// print_r($data['workout']);
		// print_r($data['workout']['circuits_data']);
		$old_circuit = array();
		$old_exercise = array();
		$old_sets = array();
		if($data['workout']['circuits_data']){
			foreach ($data['workout']['circuits_data'] as $key => $circuit) {				
				$old_circuit[] = $circuit['id'];

				if($circuit['exercises']){
					foreach ($circuit['exercises'] as $key1 => $exercises) {
						$old_exercise[$exercises['circuit_id']][] = $exercises['exercise_id'];
						if($exercises['sets']){		
							foreach ($exercises['sets'] as $key2 => $sets) {
								$old_sets[$sets->exercise_id][] = $sets->id;
							}
						}
					}
				}
			}
		}

		// print_r($old_circuit);
		// print_r($old_exercise);
		// print_r($old_sets);
		// die();

		// print_r($data['workout']['circuits_data'][0]['exercises'][0]['sets']);
		// die();

		$exer = $this->admin_model->get_exercise_pre_name(get_trainer_id());
		$data['default'] = json_encode($exer);		
		$data['default'] = str_replace("'", "", $data['default']);
		$data['galleryimages'] = $this->admin_model->get_result('gallery');
		$work_info = $this->admin_model->get_row('workout', array('id' => $workout_id));
		$data['work_image_info'] = $work_info;
		$trainee_workout = $this->admin_model->get_result('trainee_workout', array('workout_id'=>$workout_id));
		$data['trainee_workout'] = array();
		$data['group_workout'] = array();
		if($trainee_workout){
			foreach($trainee_workout as $row){
				if($row->is_group == 1 && $row->group_id != 0){
					$data['group_workout'][] = $row->group_id;	
				}else{
					$data['trainee_workout'][]= $row->trainee_id;
				}
			}
		}

		// echo "<pre>";
		// print_r($data['trainee_workout']);
		// print_r($data['group_workout']);
		// die();

		$this->form_validation->set_rules('date_of_workout', 'date of workout', 'required');							
		$this->form_validation->set_rules('name_of_workout', 'name of workout', 'required');	
		$this->form_validation->set_rules('desc_of_workout', 'desc of workout', 'required');

		if ($this->form_validation->run() == TRUE){
			
			// $this->admin_model->delete_circuit_workout($workout_id);

			// echo "In progress.";
			// echo "<pre>";
			// print_r($_POST);
			// die();

			// ####################################################

			if($this->input->post('anytime') == '1'){
				$_POST['time_of_workout'] = '23:59:59';
			}

			$workout_data = array(				
				'trainer_id'	=> $trainer_id,
				'date'			=> date("Y-m-d", strtotime($this->input->post('date_of_workout'))),
				'time'			=> date("H:i:s", strtotime($this->input->post('time_of_workout'))),
				'name'			=> $this->input->post('name_of_workout'),
				'description'	=> $this->input->post('desc_of_workout'),
				'anytime'		=> $this->input->post('anytime'),
				'created'		=> date("Y-m-d H:i:s"),
			);


			$workout_data['type'] = 2;
			
			if(isset($_FILES['image_workout']['name']) && $_FILES['image_workout']['name']!=""){
				@$file = upload_to_bucket_server('image_workout', 'w_');
				if($file['status']){
					$workout_data['image'] = $file['filename'];
				}
			}else{
				$workout_data['image'] = $this->input->post('wrkoutimg');
			}

			$workout_data['token'] = get_token();
			$workout_data['lastupdated'] = time();

			// $workout_id = $this->admin_model->insert('workout',$workout_data);
			$this->admin_model->update('workout',$workout_data, array('id'=>$workout_id));
			
			$trainee_arr = $this->input->post('trainee_id');
			$ind_trainee = array();
			if($trainee_arr){
				$i = 0;
				foreach ($trainee_arr as $row){					
					// if not in old insert
					if(!in_array($row, $data['trainee_workout'])){
						$ind_trainee['trainee_id'] = $row;
						$ind_trainee['is_group'] = 0;
						$ind_trainee['group_id'] = 0;
						$ind_trainee['workout_id'] = $workout_id;
						$ind_trainee['token'] = get_token();
						$ind_trainee['lastupdated'] = time();
						$this->admin_model->insert('trainee_workout', $ind_trainee);	
					}					

				}

				if($data['trainee_workout']){
					foreach($data['trainee_workout'] as $old_t => $tid){
						if(!in_array($tid, $trainee_arr)){
							$this->admin_model->delete('trainee_workout', array('trainee_id'=>$tid,'workout_id'=>$workout_id));
						}
					}
				}

			}



			// delete old if not in post

			$group_arr = $this->input->post('group_id');
			$g_trainee = array();

			if($group_arr){				
				$i = 0;
				foreach ($group_arr as $group){
					// if not in old group insert					
					if(!in_array($group, $data['group_workout'])){						
						
						$g_members = $this->admin_model->get_result('group_members',array('group_id'=>$group));
						if($g_members){
							foreach($g_members as $member){							
								if(!in_array($member->trainee_id, $trainee_arr)){								
									$g_trainee['trainee_id'] = $member->trainee_id;
									$g_trainee['is_group'] = 1;
									$g_trainee['group_id'] = $group;
									$g_trainee['workout_id'] = $workout_id;
									$g_trainee['token'] = get_token();
									$g_trainee['lastupdated'] = time();
									$this->admin_model->insert('trainee_workout', $g_trainee);
								
								}
							}
						}
					} 
				}

				// print_r($data['group_workout']); die();

				if($data['group_workout']){
					foreach($data['group_workout'] as $old_g => $gid){					
						if(!in_array($gid, $group_arr)){
							$this->admin_model->delete('trainee_workout', array('group_id'=>$gid,'workout_id'=>$workout_id));
						}
					}
				}

				//  delete old if not in post
			}else{
				$this->admin_model->delete('trainee_workout', array('is_group'=>1,'workout_id'=>$workout_id));				
			}			 //else delete all group

			// die();

			// post circuit with circuit id when update otherwise insert
			// post exercise with exercise id for update and for insert post with circuit id
			// post set with set id for update and for insert post with exercise id

			
			if($data['workout']['circuits_data']){
				foreach ($data['workout']['circuits_data'] as $key => $circuit) {				
					$c_id = $circuit['id'];
					if(isset($_POST['circuit'.$c_id])){
						$ncircuitimg = $_POST['circuitimg'.$c_id];
						$nimage = $_FILES['image_circuit'.$c_id]['name'];
						$nimage_temp = $_FILES['image_exercise'.$c_id]['tmp_name'];

						$cdata = array(
							'name'=>$_POST['circuit'.$c_id],
							'description'=>$_POST['circuit_description'.$c_id]
							);

						if(empty($nimage) && $ncircuitimg != ""){					
								$cdata['image'] = $ncircuitimg;
						}else{
							$file = upload_exercise_to_bucket_server($nimage, $nimage_temp, 'c_');
							if($file['status']){
								$cdata['image'] = $file['filename'];
							}
						}

						$this->admin_model->update('circuit',$cdata,array('id'=>$c_id));

						// image_circuit30
					}

					// if(isset($_POST['exercise'.$c_id])){
					// 	print_r($_POST);
					// 	// print_r($_POST['exercise'.$c_id]); die();
					// 	foreach($_POST['exercise'.$c_id] as $row){

					// 	}
					// 	// echo "string"; die();
					// }

					if($circuit['exercises']){
						foreach ($circuit['exercises'] as $key1 => $exercises) {
							// $old_exercise[$exercises['circuit_id']][] = $exercises['exercise_id'];
								$e_id = $exercises['exercise_id'];
							if(isset($_POST['exercise'.$e_id])){
								$this->admin_model->update('exercise', array('name'=>$_POST['exercise'.$e_id]), array('id'=>$e_id));
							}

							if($exercises['sets']){		
								foreach ($exercises['sets'] as $key2 => $sets) {
									$s_id = $sets->id;
									if($_POST['time_'.$s_id]){
										$sdata = array(											
											'time'			=> @$_POST['time_'.$s_id],
											'value'			=> @$_POST['set_'.$s_id],
											'reps'			=> @$_POST['reps_'.$s_id],											
										);

										$this->admin_model->update('exercise_set', $sdata, array('id'=>$s_id));

									}
									// $old_sets[$sets->exercise_id][] = $sets->id;
								}
							}						
							
							if($_POST['time_'.$e_id]){								
								$i=0;								
								// for($i=0; $i<=count($_POST['time_'.$e_id]); $i++){
								foreach($_POST['time_'.$e_id] as $tim){
									$nsdata = array(								
											'exercise_id'	=>$e_id,			
											'time'			=> @$_POST['time_'.$e_id][$i],
											'value'			=> @$_POST['set_'.$e_id][$i],
											'reps'			=> @$_POST['reps_'.$e_id][$i],
											'token' => get_token(),
											'lastupdated' => time()
										);
									$this->admin_model->insert('exercise_set', $nsdata);
									$i++;
								}
							}

						}
					}
				}
			}

			// die();



			// $circuit = $_POST['circuit'];
			// $circuit_e_count = $_POST['circuit_e_count'];
			// $circuit_description = $_POST['circuit_description'];
			// $circuitimg = $_POST['circuitimg'];
			// $image = $_FILES['image_circuit']['name'];
			// $image_temp = $_FILES['image_circuit']['tmp_name'];

			// $cid = array();
			// $cid_keys = 0;
			// for($i=1; $i<=count($circuit); $i++){
				
			// 	$circuit_data = array(
			// 		'workout_id'	=> $workout_id,
			// 		'name'			=> $circuit[$i-1],
			// 		'description'	=> $circuit_description[$i-1],
			// 		'created'		=> date("Y-m-d H:i:s")
			// 	);

			// 	if(empty($image[$i-1]) && $circuitimg[$i-1] != ""){					
			// 			$circuit_data['image'] = $circuitimg[$i-1];
			// 	}else{
			// 		$file = upload_exercise_to_bucket_server($image[$i-1], $image_temp[$i-1], 'c_');
			// 		if($file['status']){
			// 			$circuit_data['image'] = $file['filename'];
			// 		}
			// 	}

			// 	$circuit_data['token'] = get_token();
			// 	$circuit_data['lastupdated'] = time();

			// 	$circuit_id = $this->admin_model->insert('circuit',$circuit_data);

			// 	$circuit_e_count[$i-1] = $circuit_e_count[$i-1] + $cid_keys;

			// 	while( $cid_keys < $circuit_e_count[$i-1]){
			// 		$cid[] = $circuit_id;
			// 		$cid_keys++;
			// 	}
			// }

			// // echo "<pre>";
			// // print_r($cid);
			// // die();


			// $exercise = $_POST['exercise'];
			// $resttime = $_POST['resttime'];
			// $description = $_POST['description'];
			// $image = $_FILES['image_exercise']['name'];
			// $image_temp = $_FILES['image_exercise']['tmp_name'];
			// $default_exercises_id = $_POST['exercise_def_id'];
			// $exerciseimg = $_POST['exerciseimg'];
			
			// for($i=1;$i<=count($exercise);$i++){
			// 	$exercise_data = array(
			// 		'workout_id'	=> $workout_id,
			// 		'circuit_id'	=> $cid[$i-1],
			// 		'name'			=> $exercise[$i-1],
			// 		'resttime'		=> '', // $resttime[$i-1]
			// 		'description'	=> '',
			// 		'created'		=> date("Y-m-d H:i:s")
			// 	);

			// 	// if(empty($image[$i-1]) && $exerciseimg[$i-1] != ""){					
			// 	// 		$exercise_data['image'] = $exerciseimg[$i-1];
			// 	// }else{
			// 	// 	$file = upload_exercise_to_bucket_server($image[$i-1], $image_temp[$i-1], 'e_');
			// 	// 	if($file['status']){
			// 	// 		$exercise_data['image'] = $file['filename'];
			// 	// 	}

			// 	// }

			// 	$exercise_data['token'] = get_token();
			// 	$exercise_data['lastupdated'] = time();

			// 	$exercise_id = $this->admin_model->insert('exercise',$exercise_data);

			// 	if($this->input->post('sets'.$i)){
			// 		foreach($this->input->post('sets'.$i) as $key => $var){
			// 			$set_data = array(
			// 				'exercise_id'	=> $exercise_id,
			// 				'time'			=> @$_POST['time'.$i][$key],
			// 				'value'			=> $var,
			// 				'reps'			=> @$_POST['reps'.$i][$key],
			// 				'created'		=> date("Y-m-d H:i:s")
			// 			);
			// 			$set_data['token'] = get_token();
			// 			$set_data['lastupdated'] = time();
			// 			$this->admin_model->insert('exercise_set', $set_data);
			// 		}
			// 	}
			// }

			

			$this->session->set_flashdata('success_msg',"Circuit workout has been updated successfully.");
			redirect(_INDEX.'circuit_workout/all');
		}

		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>get_trainer_id()));
		if(!$data['trainee']){
			$this->session->set_flashdata('error_msg',"Please add trainee first then try to update circuit workout.");
			redirect(_INDEX.'trainer/manage_trainee');
		}

		$data['group'] = $this->admin_model->get_result('group', array('trainer_id'=>$trainer_id,'parent_id'=>0));
		$data['template'] = 'circuit_workout/edit';
		$this->load->view('templates/trainer_template', $data);
	}

	public function delete($workout_id=''){
		$this->admin_model->delete_circuit_workout($workout_id);
		$this->session->set_flashdata('success_msg',"Circuit workout has been deleted successfully.");
		redirect(_INDEX.'circuit_workout/all');
	}

	public function delete_set($set_id)
	{
		$this->admin_model->delete('exercise_set',array('id'=>$set_id));
		echo 1;
	}

	public function get_default($id){
		if(strtolower($id) == 'undefined' || $id == ""){
			$res = array('status'=>FALSE);
			echo json_encode($res);
		}

		$result = $this->admin_model->get_row('default_exercises', array('id'=>$id));
		$res = array('status'=>TRUE,'response'=>$result);
		echo json_encode($res);
	}

	public function ajax_remove_set($set_id = ""){
		if($set_id == ""){
			return FALSE;
			die();
		}
			$this->admin_model->delete('exercise_set', array('id'=>$set_id));
	}


	public function ajax_remove_exercise($e_id = ""){
		if($e_id == ""){
			return FALSE;
			die();
		}
			$this->admin_model->delete('exercise', array('id'=>$e_id));
			$this->admin_model->delete('exercise_set', array('exercise_id'=>$e_id));
	}

	public function ajax_remove_circuit($c_id = ""){
		if($c_id == ""){
			return FALSE;
			die();
		}
			$this->admin_model->delete('circuit', array('id'=>$c_id));
			$this->admin_model->delete('exercise', array('circuit_id'=>$c_id));
	}

	/* NOTES */

	public function notes($workout_id="",$offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
        
        if(!$workout_id){
			redirect(_INDEX.'circuit_workout/all');
        }

		$where =  array('workout_id'=>$workout_id);

		$limit=10;
		$data['workout']=$this->admin_model->get_row('workout',array('id'=>$workout_id));
		$data['workout_notes']=$this->admin_model->get_workout_notes($limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'circuit_workout/notes/'.$workout_id;
		$config['total_rows'] = $this->admin_model->get_workout_notes(0, 0,$where);
		$config['per_page'] = $limit;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'circuit_workout/notes';
        $this->load->view('templates/trainer_template', $data);			
	}

	public function delete_notes($id="",$workout_id=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
        if(!$id){
			redirect(_INDEX.'circuit_workout/all');
        }
		$this->admin_model->delete('workout_notes',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"Deleted successfully.");
		redirect(_INDEX.'circuit_workout/notes/'.$workout_id);
	}

	/* NOTES END */
}