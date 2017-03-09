<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workout extends CI_Controller {
	public function __construct(){
		parent::__construct();
	    $this->load->model('admin_model');

	}
	
	public function index(){
		$this->add();	
	}

	public function all($offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$trainer_id = get_trainer_id();

		$limit=10;
		$data['workout'] = $this->admin_model->get_pagination_result('workout', $limit,$offset, array('trainer_id'=>$trainer_id));
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'workout/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('workout', 0, 0, array('trainer_id'=>$trainer_id));
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'workout/all';
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

		// $data['mdate'] = date('m/d/Y');
		
		$trainer_id = get_trainer_id();

		$exer = $this->admin_model->get_exercise_pre_name(get_trainer_id());

		$data['default'] = json_encode($exer);		
		$data['default'] = str_replace("'", "", $data['default']);
		
		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		$this->form_validation->set_rules('date_of_workout', 'workout date', 'required');							
		$this->form_validation->set_rules('name_of_workout', 'workout name', 'required');	
		$this->form_validation->set_rules('desc_of_workout', 'workout description', 'required');
		$this->form_validation->set_rules('exercise_or_circuit_name', 'exercise_or_circuit_name', 'required');
								
		if ($this->form_validation->run() == TRUE){
			$exercise_or_circuit_name = $this->input->post('exercise_or_circuit_name');
			$exercise_or_circuit_name = explode(',', $exercise_or_circuit_name);
			$key_array = array();
			$type_array = array();
			foreach($exercise_or_circuit_name as $var){
				$temp = explode('_', $var);
				$key_array[] = array(
					$temp[0] => $temp[1]
				);
				$type_array[$temp[0]] = $temp[0];
			}

			$type = 0;
			if((in_array('exercise', $type_array)) && (in_array('circuit', $type_array))){
				$type = 3;
			}
			elseif(in_array('exercise', $type_array)){
				$type = 1;
			}
			elseif(in_array('circuit', $type_array)){
				$type = 2;
			}

			$workout_data = array(				
				'trainer_id'	=> $trainer_id,
				'date'			=> date("Y-m-d", strtotime($this->input->post('date_of_workout'))),
				'time'			=> '23:59:59',
				'name'			=> $this->input->post('name_of_workout'),
				'description'	=> $this->input->post('desc_of_workout'),
				'anytime'		=> '1',
				'type'			=> $type,
				'token'			=> get_token(),
				'lastupdated'	=> time(),
				'created'		=> date("Y-m-d H:i:s")
			);

			if(isset($_FILES['image_workout']['name']) && $_FILES['image_workout']['name']!=""){
				@$file = upload_to_bucket_server('image_workout', 'w_');
				if($file['status']){
					$workout_data['image'] = $file['filename'];
				}
			}else{
				$workout_data['image'] = $this->input->post('wrkoutimg');
			}			

			$workout_id = $this->admin_model->insert('workout', $workout_data);

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

			foreach($key_array as $temp){
				foreach($temp as $key => $field){
					if($key == 'exercise'){
						$exercise_data = array(
							'workout_id'			=> $workout_id,
							'name'					=> $this->input->post($field.'_name'),
							'description'			=> $this->input->post($field.'_desc'),
							'default_exercise_id'	=> $this->input->post($field.'_default_id'),
							'token'					=> get_token(),
							'lastupdated'			=> time(),
							'created'				=> date("Y-m-d H:i:s")
						);

						$image = $_FILES[$field.'_file']['name'];
						$image_temp = $_FILES[$field.'_file']['tmp_name'];
						$exerciseimg = $_POST[$field.'_galleryname'];

						if(empty($image) && $exerciseimg != ""){					
							$exercise_data['image'] = $exerciseimg;
						}
						else{
							$file = upload_exercise_to_bucket_server($image, $image_temp, 'e_');
							if($file['status']){
								$exercise_data['image'] = $file['filename'];
							}
						}

						$exercise_id = $this->admin_model->insert('exercise', $exercise_data);
						
						foreach($this->input->post($field.'_time') as $keyy => $varr){
							$set_data = array(
								'exercise_id'	=> $exercise_id,
								'time'			=> @$_POST[$field.'_time'][$keyy],
								'value'			=> @$_POST[$field.'_weight'][$keyy],
								'reps'			=> @$_POST[$field.'_reps'][$keyy],
								'token'			=> get_token(),
								'lastupdated'	=> time(),
								'created'		=> date("Y-m-d H:i:s")
							);
							$this->admin_model->insert('exercise_set', $set_data);
						}
					}
					elseif($key == 'circuit'){

						$circuit_data = array(
							'workout_id'	=> $workout_id,
							'name'			=> $this->input->post($field.'_name'),
							'description'	=> $this->input->post($field.'_desc'),
							'token'			=> get_token(),
							'lastupdated'	=> time(),
							'image'			=> '',
							'created'		=> date("Y-m-d H:i:s")
						);

						$circuit_id = $this->admin_model->insert('circuit', $circuit_data);

						$exercise 		= $_POST[$field.'_exercise_name'];
						$exercise_def_id= $_POST[$field.'_exercise_def_id'];
						$description 	= $_POST[$field.'_exercise_desc'];
						$exerciseimg 	= $_POST[$field.'_exercise_galleryname'];
						$image 			= $_FILES[$field.'_exercise_file']['name'];
						$image_temp 	= $_FILES[$field.'_exercise_file']['tmp_name'];

						for( $ind=1; $ind <= count($exercise); $ind++){
							$exercise_data = array(
								'workout_id'			=> $workout_id,
								'circuit_id'			=> $circuit_id,
								'name'					=> $exercise[$ind-1],
								'description'			=> $description[$ind-1],
								'default_exercise_id'	=> $exercise_def_id[$ind-1],
								'token'					=> get_token(),
								'lastupdated'			=> time(),
								'created'				=> date("Y-m-d H:i:s")
							);

							if(empty($image[$ind-1]) && $exerciseimg[$ind-1] != ""){					
								$exercise_data['image'] = $exerciseimg[$ind-1];
							}
							else{
								$file = upload_exercise_to_bucket_server($image[$ind-1], $image_temp[$ind-1], 'e_');
								if($file['status']){
									$exercise_data['image'] = $file['filename'];
								}
							}

							$exercise_id = $this->admin_model->insert('exercise', $exercise_data);

							foreach($this->input->post($field.'_exercise_time'.$ind) as $keyy => $varr){
								$set_data = array(
									'exercise_id'	=> $exercise_id,
									'time'			=> @$_POST[$field.'_exercise_time'.$ind][$keyy],
									'value'			=> @$_POST[$field.'_exercise_weight'.$ind][$keyy],
									'reps'			=> @$_POST[$field.'_exercise_reps'.$ind][$keyy],
									'token'			=> get_token(),
									'lastupdated'	=> time(),
									'created'		=> date("Y-m-d H:i:s")
								);
								$this->admin_model->insert('exercise_set', $set_data);
							}
						}
					}
				}
			}
			$this->session->set_flashdata('success_msg',"Workout has been added successfully.");
			redirect(_INDEX.'workout/all');
		}
		
		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>get_trainer_id()));
		if(!$data['trainee']){
			$this->session->set_flashdata('error_msg',"Please add trainee first then try to add workout.");
			redirect(_INDEX.'trainer/manage_trainee');
		}

		$data['group'] = $this->admin_model->get_result('group', array('trainer_id'=>$trainer_id,'parent_id'=>0));
		
		$data['select_groups'] = $this->admin_model->select_groups(0);
		$data['total_groups'] = $this->admin_model->select_groups(1);
		$data['group_members'] = $this->admin_model->select_groups_members(0);
		$data['total_members'] = $this->admin_model->select_groups_members(1);

		$this->db->order_by('name', 'asc');
		$data['default_exercises'] = $this->admin_model->get_result('default_exercises', array('trainer_id' => get_trainer_id()));

		$this->db->order_by('name', 'asc');
		$data['default_circuit'] = $this->admin_model->get_result('default_circuit', array('trainer_id' => get_trainer_id()));

		$data['template'] = 'workout/add';
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
			// $zo = new DateTimeZone('Europe/Paris');
			$dat = new DateTime("now",$zo);				
			$data['mdate'] = strtotime($dat->format('m/d/Y')); 		
		}else{
			$data['mdate'] = time();
		}

		// $data['mdate'] = date('m/d/Y');
		
		$trainer_id = get_trainer_id();

		$this->form_validation->set_rules('date_of_workout', 'date of workout', 'required');							
		$this->form_validation->set_rules('name_of_workout', 'name of workout', 'required');	
		$this->form_validation->set_rules('desc_of_workout', 'desc of workout', 'required');
								
		$data['workout'] = $this->admin_model->get_new_workout($workout_id, $trainer_id);

		/*
		echo "<pre>";
		print_r($data['workout']);
		die();
		*/

		if(!$data['workout']){
			$this->session->set_flashdata('error_msg',"Workout not found.");
			redirect(_INDEX.'workout/all');
		}

		$exer = $this->admin_model->get_exercise_pre_name(get_trainer_id());

		$data['default'] = json_encode($exer);		
		$data['default'] = str_replace("'", "", $data['default']);

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

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
		// $workout_structure = $this->admin_model->get_new_workout_structure_for_edit($workout_id);
		// print_r($workout_structure);
		// die();


		if ($this->form_validation->run() == TRUE){
			// echo "<pre>"; 
			// print_r($_POST);
			// die();
			

			$delete_exercises = $this->input->post('delete_exercises');
			if($delete_exercises != ''){
				$delete_exercises = explode(',', $delete_exercises);
				foreach($delete_exercises as $exercise_id){
					$this->admin_model->delete_new_exercise($exercise_id);
				}
			}

			$delete_circuits = $this->input->post('delete_circuits');
			if($delete_circuits != ''){
				$delete_circuits = explode(',', $delete_circuits);
				foreach($delete_circuits as $circuit_id){
					$this->admin_model->delete_new_circuit($circuit_id);
				}
			}

			$delete_sets = $this->input->post('delete_sets');
			if($delete_sets != ''){
				$delete_sets = explode(',', $delete_sets);
				foreach($delete_sets as $set_id){
					$this->admin_model->delete_new_exercise_set($set_id);
				}
			}

			$workout_structure = $this->admin_model->get_new_workout_structure_for_edit($workout_id);
			
			$exercise_or_circuit_name = $this->input->post('exercise_or_circuit_name');
			$exercise_or_circuit_name = explode(',', $exercise_or_circuit_name);
			$key_array = array();
			$type_array = array();
			foreach($exercise_or_circuit_name as $var){
				$temp = explode('_', $var);
				$key_array[] = array(
					$temp[0] => $temp[1]
				);
				$type_array[$temp[0]] = $temp[0];
			}

			$type = 0;
			if((in_array('exercise', $type_array)) && (in_array('circuit', $type_array))){
				$type = 3;
			}
			elseif(in_array('exercise', $type_array)){
				$type = 1;
			}
			elseif(in_array('circuit', $type_array)){
				$type = 2;
			}

            $workout_data = array(
				'date'			=> date("Y-m-d", strtotime($this->input->post('date_of_workout'))),
				'name'			=> $this->input->post('name_of_workout'),
				'description'	=> $this->input->post('desc_of_workout'),
				'type'			=> $type,
				'lastupdated'	=> time(),
				'updated'		=> date("Y-m-d H:i:s")
			);

			if(isset($_FILES['image_workout']['name']) && $_FILES['image_workout']['name']!=""){
				@$file = upload_to_bucket_server('image_workout', 'w_');
				if($file['status']){
					$workout_data['image'] = $file['filename'];
				}
			}else{
				$workout_data['image'] = $this->input->post('wrkoutimg');
			}

			$this->admin_model->update('workout', $workout_data, array('id' => $workout_id));
			$this->admin_model->delete('trainee_workout', array('workout_id'=>$workout_id));

			$trainee_arr = array();
            if(isset($_POST['trainee_id'])){
	            $trainee_arr = $_POST['trainee_id'];
            }

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

			/* EDIT WORKOUT MODULE */

			if($workout_structure){
				$index = 1;
				foreach($key_array as $temp){
					foreach($temp as $key => $field){
						if($key == 'exercise'){
							$exercise_data = array(
								'workout_id'			=> $workout_id,
								'name'					=> $this->input->post($field.'_name'),
								'default_exercise_id'	=> $this->input->post($field.'_default_id'),
								'description'			=> $this->input->post($field.'_desc'),
								'lastupdated'			=> time()
							);

							$image = $_FILES[$field.'_file']['name'];
							$image_temp = $_FILES[$field.'_file']['tmp_name'];
							$exerciseimg = $_POST[$field.'_galleryname'];

							$exercise_data['image'] = $exerciseimg;
							if(empty($image) && $exerciseimg != ""){					
								$exercise_data['image'] = $exerciseimg;
							}
							else{
								$file = upload_exercise_to_bucket_server($image, $image_temp, 'e_');
								if($file['status']){
									$exercise_data['image'] = $file['filename'];
								}
							}

							if(isset($workout_structure[$index]['exercise_id'])){
								$exercise_data['updated'] = date("Y-m-d H:i:s");
								$exercise_id = $workout_structure[$index]['exercise_id'];

								$this->admin_model->update('exercise', $exercise_data, array('id' => $exercise_id));
							}
							else{
								$exercise_data['token'] = get_token();
								$exercise_data['created'] = date("Y-m-d H:i:s");

								$exercise_id = $this->admin_model->insert('exercise', $exercise_data);
							}

							// echo "<pre> $index";
							// echo $this->db->last_query();
							// die();

							$set_index = 0;
							foreach($this->input->post($field.'_time') as $keyy => $varr){
								$set_data = array(
									'exercise_id'	=> $exercise_id,
									'time'			=> @$_POST[$field.'_time'][$keyy],
									'value'			=> @$_POST[$field.'_weight'][$keyy],
									'reps'			=> @$_POST[$field.'_reps'][$keyy],
									'lastupdated'	=> time(),
								);
								
								if(isset($workout_structure[$index]['sets'][$set_index]['exercise_set_id'])){
									$set_data['updated'] = date("Y-m-d H:i:s");
									$set_id = $workout_structure[$index]['sets'][$set_index]['exercise_set_id'];

									$this->admin_model->update('exercise_set', $set_data, array('id' => $set_id));
								}
								else{
									$set_data['token'] = get_token();
									$set_data['created'] = date("Y-m-d H:i:s");
									$this->admin_model->insert('exercise_set', $set_data);
								}								
								$set_index++;
							}
						}
						elseif($key == 'circuit'){
							$circuit_data = array(
								'workout_id'	=> $workout_id,
								'name'			=> $this->input->post($field.'_name'),
								'description'	=> $this->input->post($field.'_desc'),
								'lastupdated'	=> time(),
							);

							if(isset($workout_structure[$index]['circuit_id'])){
								$circuit_data['updated'] = date("Y-m-d H:i:s");
								$circuit_id = $workout_structure[$index]['circuit_id'];
								
								$this->admin_model->update('circuit', $circuit_data, array('id' => $circuit_id));
							}
							else{
								$circuit_data['token'] = get_token();
								$circuit_data['created'] = date("Y-m-d H:i:s");

								$circuit_id = $this->admin_model->insert('circuit', $circuit_data);
							}

							$exercise 		= $_POST[$field.'_exercise_name'];
							$description 	= $_POST[$field.'_exercise_desc'];
							$exercise_def_id= $_POST[$field.'_exercise_def_id'];
							$exerciseimg 	= $_POST[$field.'_exercise_galleryname'];
							$image 			= $_FILES[$field.'_exercise_file']['name'];
							$image_temp 	= $_FILES[$field.'_exercise_file']['tmp_name'];

							$tag = 0;
							for( $ind=1; $ind <= count($exercise); $ind++){
								$exercise_data = array(
									'workout_id'			=> $workout_id,
									'circuit_id'			=> $circuit_id,
									'name'					=> $exercise[$ind-1],
									'description'			=> $description[$ind-1],
									'default_exercise_id'	=> $exercise_def_id[$ind-1],
									'lastupdated'			=> time(),
								);

								if(empty($image[$ind-1]) && $exerciseimg[$ind-1] != ""){					
									$exercise_data['image'] = $exerciseimg[$ind-1];
								}
								else{
									$file = upload_exercise_to_bucket_server($image[$ind-1], $image_temp[$ind-1], 'e_');
									if($file['status']){
										$exercise_data['image'] = $file['filename'];
									}
								}

								if(isset($workout_structure[$index]['exercises'][$tag]['exercise_id'])){
									$exercise_data['updated'] = date("Y-m-d H:i:s");
									$exercise_id = $workout_structure[$index]['exercises'][$tag]['exercise_id'];

									$this->admin_model->update('exercise', $exercise_data, array('id' => $exercise_id));
								}
								else{
									$exercise_data['token'] = get_token();
									$exercise_data['created'] = date("Y-m-d H:i:s");

									$exercise_id = $this->admin_model->insert('exercise', $exercise_data);
								}

								$set_index = 0;
								foreach($this->input->post($field.'_exercise_time'.$ind) as $keyy => $varr){
									$set_data = array(
										'exercise_id'	=> $exercise_id,
										'time'			=> @$_POST[$field.'_exercise_time'.$ind][$keyy],
										'value'			=> @$_POST[$field.'_exercise_weight'.$ind][$keyy],
										'reps'			=> @$_POST[$field.'_exercise_reps'.$ind][$keyy],
										'lastupdated'	=> time(),
									);
									
									if(isset($workout_structure[$index]['exercises'][$tag]['sets'][$set_index]['exercise_set_id'])){
										$set_data['updated'] = date("Y-m-d H:i:s");
										$set_id = $workout_structure[$index]['exercises'][$tag]['sets'][$set_index]['exercise_set_id'];

										$this->admin_model->update('exercise_set', $set_data, array('id' => $set_id));
									}
									else{
										$set_data['token'] = get_token();
										$set_data['created'] = date("Y-m-d H:i:s");
										$this->admin_model->insert('exercise_set', $set_data);
									}								
									$set_index++;
								}
								$tag++;
							}
						}
						$index = $index + 1;
					}
				}
				// die();
			}
			else{
				foreach($key_array as $temp){
					foreach($temp as $key => $field){
						if($key == 'exercise'){
							$exercise_data = array(
								'workout_id'			=> $workout_id,
								'name'					=> $this->input->post($field.'_name'),
								'default_exercise_id'	=> $this->input->post($field.'_default_id'),
								'description'			=> $this->input->post($field.'_desc'),
								'token'					=> get_token(),
								'lastupdated'			=> time(),
								'created'				=> date("Y-m-d H:i:s")
							);

							$image = $_FILES[$field.'_file']['name'];
							$image_temp = $_FILES[$field.'_file']['tmp_name'];
							$exerciseimg = $_POST[$field.'_galleryname'];

							if(empty($image) && $exerciseimg != ""){					
								$exercise_data['image'] = $exerciseimg;
							}
							else{
								$file = upload_exercise_to_bucket_server($image, $image_temp, 'e_');
								if($file['status']){
									$exercise_data['image'] = $file['filename'];
								}
							}

							$exercise_id = $this->admin_model->insert('exercise', $exercise_data);
							
							foreach($this->input->post($field.'_time') as $keyy => $varr){
								$set_data = array(
									'exercise_id'	=> $exercise_id,
									'time'			=> @$_POST[$field.'_time'][$keyy],
									'value'			=> @$_POST[$field.'_weight'][$keyy],
									'reps'			=> @$_POST[$field.'_reps'][$keyy],
									'token'			=> get_token(),
									'lastupdated'	=> time(),
									'created'		=> date("Y-m-d H:i:s")
								);
								$this->admin_model->insert('exercise_set', $set_data);
							}
						}
						elseif($key == 'circuit'){

							$circuit_data = array(
								'workout_id'	=> $workout_id,
								'name'			=> $this->input->post($field.'_name'),
								'description'	=> $this->input->post($field.'_desc'),
								'token'			=> get_token(),
								'lastupdated'	=> time(),
								'image'			=> '',
								'created'		=> date("Y-m-d H:i:s")
							);

							$circuit_id = $this->admin_model->insert('circuit', $circuit_data);

							$exercise 		= $_POST[$field.'_exercise_name'];
							$description 	= $_POST[$field.'_exercise_desc'];
							$exercise_def_id= $_POST[$field.'_exercise_def_id'];
							$exerciseimg 	= $_POST[$field.'_exercise_galleryname'];
							$image 			= $_FILES[$field.'_exercise_file']['name'];
							$image_temp 	= $_FILES[$field.'_exercise_file']['tmp_name'];

							for( $ind=1; $ind <= count($exercise); $ind++){
								$exercise_data = array(
									'workout_id'			=> $workout_id,
									'circuit_id'			=> $circuit_id,
									'name'					=> $exercise[$ind-1],
									'description'			=> $description[$ind-1],
									'default_exercise_id'	=> $exercise_def_id[$ind-1],
									'token'					=> get_token(),
									'lastupdated'			=> time(),
									'created'				=> date("Y-m-d H:i:s")
								);

								if(empty($image[$ind-1]) && $exerciseimg[$ind-1] != ""){					
									$exercise_data['image'] = $exerciseimg[$ind-1];
								}
								else{
									$file = upload_exercise_to_bucket_server($image[$ind-1], $image_temp[$ind-1], 'e_');
									if($file['status']){
										$exercise_data['image'] = $file['filename'];
									}
								}

								$exercise_id = $this->admin_model->insert('exercise', $exercise_data);

								foreach($this->input->post($field.'_exercise_time'.$ind) as $keyy => $varr){
									$set_data = array(
										'exercise_id'	=> $exercise_id,
										'time'			=> @$_POST[$field.'_exercise_time'.$ind][$keyy],
										'value'			=> @$_POST[$field.'_exercise_weight'.$ind][$keyy],
										'reps'			=> @$_POST[$field.'_exercise_reps'.$ind][$keyy],
										'token'			=> get_token(),
										'lastupdated'	=> time(),
										'created'		=> date("Y-m-d H:i:s")
									);
									$this->admin_model->insert('exercise_set', $set_data);
								}
							}
						}
					}
				}
			}

			/* / EDIT WORKOUT MODULE */

			$this->session->set_flashdata('success_msg',"Workout has been updated successfully.");
			redirect(_INDEX.'workout/all');
		}

		$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>get_trainer_id()));
		if(!$data['trainee']){
			$this->session->set_flashdata('error_msg',"Please add trainee first then try to add workout.");
			redirect(_INDEX.'trainer/manage_trainee');
		}

		$data['group'] = $this->admin_model->get_result('group', array('trainer_id'=>$trainer_id,'parent_id'=>0));
			
		$data['select_groups'] = $this->admin_model->select_groups(0);
		$data['total_groups'] = $this->admin_model->select_groups(1);
		$data['group_members'] = $this->admin_model->select_groups_members(0);
		$data['total_members'] = $this->admin_model->select_groups_members(1);

		$this->db->order_by('name', 'asc');
		$data['default_exercises'] = $this->admin_model->get_result('default_exercises', array('trainer_id' => get_trainer_id()));

		$this->db->order_by('name', 'asc');
		$data['default_circuit'] = $this->admin_model->get_result('default_circuit', array('trainer_id' => get_trainer_id()));

		$data['template'] = 'workout/edit';
        $this->load->view('templates/trainer_template', $data);
	}

	public function delete($workout_id=''){
		$this->admin_model->delete_workout($workout_id);
		$this->session->set_flashdata('success_msg',"Workout has been deleted successfully.");
		redirect(_INDEX.'workout/all');
	}

	public function view($workout_id=''){
		if(trainee_login_in()===FALSE)
			redirect(_INDEX.'login/trainee_login');

		$this->form_validation->set_rules('status', 'status', 'required');
		if ($this->form_validation->run() == TRUE){	
			$workout_data = array(
				'status'		=> $this->input->post('status'),
				'updated'		=> date("Y-m-d H:i:s")
			);

			$workout_data['lastupdated'] = time();

			$this->admin_model->update('workout',$workout_data,array('id'=>$workout_id));
			$this->session->set_flashdata('success_msg',"Status has been updated successfully.");
			redirect(cms_current_url());
		}

		$data['workout'] = $this->admin_model->get_workout($workout_id);
		$data['template'] = 'workout/view';
        $this->load->view('templates/trainee_template', $data);
	}


	public function do_multi_upload($filename2='user_file' ,$temp_name, $upload_path='./assets/uploads/custom_uploads/', $path_of_thumb='')
	{
		$allowed =  array('gif','png','jpg','jpeg','mp4');
		// $filename = $_FILES[$filename2]['name'];
		$ext = pathinfo($filename2, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ){
			return FALSE;
		}
		else{
			
				 $name = uniqid().time();
				 if(move_uploaded_file($temp_name,$upload_path.$name.'.'.$ext))
				 return $name.'.'.$ext;
				 else
				 return FALSE;
			}
	}

	public function do_core_upload($filename2='user_file' , $upload_path='./assets/uploads/custom_uploads/', $path_of_thumb='')
	{
		$allowed =  array('gif','png','jpg','jpeg','mp4');
		$filename = $_FILES[$filename2]['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ){
			return FALSE;
		}
		else{
			
			if ($_FILES[$filename2]["error"] > 0){
	 			return FALSE; 
	 		}
			else{
			 $name = uniqid().time();
			 if(move_uploaded_file($_FILES[$filename2]['tmp_name'],$upload_path.$name.'.'.$ext))
			 return $name.'.'.$ext;
			 else
			 return FALSE;
			}
		} 
	}

	public function delete_set($set_id)
	{
		$this->admin_model->delete('exercise_set',array('id'=>$set_id));
		echo 1;
	}

	// 	public function edit($workout_id = '')
	// {
	// 	if(trainer_login_in()===FALSE)
	// 		redirect(_INDEX.'login/trainer_login');

	// 	$trainer_id = get_trainer_id();

	// 	// $this->form_validation->set_rules('trainee_id', 'trainee', 'required');							
	// 	$this->form_validation->set_rules('date_of_workout', 'date of workout', 'required');							
	// 	$this->form_validation->set_rules('time_of_workout', 'time of workout', 'required');							
	// 	$this->form_validation->set_rules('name_of_workout', 'name of workout', 'required');	
	// 	$this->form_validation->set_rules('desc_of_workout', 'desc of workout', 'required');
								
	// 	$data['workout'] = $this->admin_model->get_workout($workout_id);

 //        $work_info = $this->admin_model->get_row('workout', array('id' => $workout_id));
        
 //        $data['work_image_info'] = $work_info;

	// 	$trainee_workout = $this->admin_model->get_result('trainee_workout', array('workout_id'=>$workout_id));
	// 	$data['trainee_workout'] = array();
	// 	$data['group_workout'] = array();
	// 	if($trainee_workout){
	// 		foreach($trainee_workout as $row){
	// 			$data['trainee_workout'][]= $row->trainee_id;
	// 			if($row->is_group == 1 && $row->group_id != 0){
	// 				$data['group_workout'][] = $row->group_id;	
	// 			}
	// 		}
	// 	}

	// 	if ($this->form_validation->run() == TRUE)
	// 	{			
	// 		$old_workout_data = $data['workout'];

	// 		$this->admin_model->delete('exercise', array('workout_id' => $workout_id));

	// 		foreach($old_workout_data['exercises'] as $row)
	// 		{
	// 			$this->admin_model->delete('exercise_set', array('exercise_id' => $row['exercise_id']));
	// 		}

	// 		$workout_data = array(
	// 			// 'trainee_id'	=> $this->input->post('trainee_id'),
	// 			'trainer_id'	=> $trainer_id,
	// 			'date'			=> date("Y-m-d", strtotime($this->input->post('date_of_workout'))),
	// 			'time'			=> date("H:i:s", strtotime($this->input->post('time_of_workout'))),
	// 			'name'			=> $this->input->post('name_of_workout'),
	// 			'description'	=> $this->input->post('desc_of_workout'),
	// 			'updated'		=> date("Y-m-d H:i:s")
	// 		);

	// 		$trainee_arr =  $this->input->post('trainee_id');
	// 		//print_r($trainee_arr); die();
	// 		$group_arr = $this->input->post('group_id');
	// 		//echo "<pre>";			
	// 		$post_group = $this->admin_model->get_grouptrainee($trainer_id); 
	// 		$group_array = array();

	// 		if(!empty($group_arr)){
	// 			foreach ($group_arr as $key => $value) {					
	// 				foreach ($post_group as $row) {						
	// 					if($row->grp_id == $value){
	// 						$group_array[] = $row;	
	// 					}
	// 				}
	// 			}
	// 		}

	// 		//print_r($group_array); die();

	// 		$trainee_id = array();

	// 		if(!empty($trainee_arr)){
	// 		 	foreach ($trainee_arr as $var => $val) {			 			
	// 				$trainee_id[$val]['trainee'] = $val;
	// 				$trainee_id[$val]['is_group'] = '0';
	// 				$trainee_id[$val]['group_id'] = '0';
	// 	 		}	

	// 			foreach ($group_array as $key) {
	// 			 	$trainee_id[$key->trainee_id]['trainee'] = $key->trainee_id;
	// 				$trainee_id[$key->trainee_id]['is_group'] = '1';
	// 				$trainee_id[$key->trainee_id]['group_id'] = $key->grp_id;
	// 			 } 

	// 		}else{
	// 			foreach ($group_array as $key) {					
	// 			 	$trainee_id[$key->trainee_id]['trainee'] = $key->trainee_id;
	// 				$trainee_id[$key->trainee_id]['is_group'] = '1';
	// 				$trainee_id[$key->trainee_id]['group_id'] = $key->grp_id;
	// 			}
	// 		}

	// 	   if(isset($_FILES['image_workout']['name']) && $_FILES['image_workout']['name']!="")
	// 	   {
	//               $image_workout_name = $this->do_core_upload('image_workout','./assets/uploads/workout/');
	//              if(!empty($image_workout_name))
	//              {
	//              	$workout_data['image'] = $image_workout_name;
	//              	@unlink('./assets/uploads/workout/'.$work_info->image);
	//              }
 //  		   }


	// 		$this->admin_model->update('workout',$workout_data,array('id'=>$workout_id));
	// 		$this->admin_model->delete('trainee_workout', array('workout_id'=>$workout_id));

	// 		$batchdata = array();
	// 		$i = 0;
	// 		foreach ($trainee_id as $row){
	// 			$batchdata['trainee_id'] = $row['trainee'];
	// 			$batchdata['is_group'] = $row['is_group'];
	// 			$batchdata['group_id'] = $row['group_id'];
	// 			$batchdata['workout_id'] = $workout_id;
	// 			$this->admin_model->insert('trainee_workout', $batchdata);
	// 		}

          
 //            $exercise = 0;
 //            if(isset($_POST['exercise']) && isset($_POST['description']))
 //            {
	// 			$exercise = $_POST['exercise'];
	// 			$description = $_POST['description'];
	//     		$old_image_data = $_POST['old_image_data'];
	// 	    }

 //            if(isset($_FILES['image_exercise']['name']))
 //            {
	// 			$image = $_FILES['image_exercise']['name'];
	// 			$image_temp = $_FILES['image_exercise']['tmp_name'];
	// 	    }

	// 		// print_r($image);
	// 		// die();
      
	// 		for($i=1;$i<=count($exercise);$i++)
	// 		{

	// 			$exercise_data = array(
	// 								'workout_id'	=> $workout_id,
	// 								'name'			=> $exercise[$i-1],
	// 								'description'	=> $description[$i-1],
	// 								'created'		=> date("Y-m-d H:i:s")
 //                     				);

	// 			if($image[$i-1]=="")
	// 			{
 //                  $exercise_data['image'] = $old_image_data[$i-1];
	// 			}
	// 			else
	// 			{
 //                   $exercise_data['image'] = $this->do_multi_upload($image[$i-1],$image_temp[$i-1],'./assets/uploads/exercise/');
	// 			}
              

	// 			$exercise_id = $this->admin_model->insert('exercise',$exercise_data);
              
 //              // print_r($_POST);
 //              // die();

	// 			if($this->input->post('sets'.$i)){
	// 				foreach($this->input->post('sets'.$i) as $key => $var){
					
	// 					$set_data = array(
	// 						'exercise_id'	=> $exercise_id,
	// 						'value'			=> $var,
	// 						'reps'			=> @$_POST['reps'.$i][$key],
	// 						'created'		=> date("Y-m-d H:i:s")
	// 					);

	// 					$this->admin_model->insert('exercise_set', $set_data);
	// 				}
	// 			}
	// 		}

	// 		$this->session->set_flashdata('success_msg',"Workout has been updated successfully.");
	// 		redirect(_INDEX.'workout/all');
	// 	}

	// 	$data['trainee'] = $this->admin_model->get_result('trainee', array('trainer_id'=>get_trainer_id()));
	// 	if(!$data['trainee']){
	// 		$this->session->set_flashdata('error_msg',"Please add trainee first then try to add workout.");
	// 		redirect(_INDEX.'trainer/manage_trainee');
	// 	}

	// 	$data['group'] = $this->admin_model->get_result('group', array('trainer_id'=>$trainer_id));
	// 	$data['template'] = 'workout/edit';
 //        $this->load->view('templates/trainer_template', $data);
	// }

	public function get_default($id){
		if(strtolower($id) == 'undefined' || $id == ""){
			$res = array('status'=>FALSE);
			echo json_encode($res);
		}

		$result = $this->admin_model->get_row('default_exercises', array('id'=>$id));
		$res = array('status'=>TRUE,'response'=>$result);
		echo json_encode($res);
	}

	public function notes($workout_id="",$offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
        
        if(!$workout_id){
			redirect(_INDEX.'workout/all');
        }

		$where =  array('workout_id'=>$workout_id);

		$limit=10;
		$data['workout']=$this->admin_model->get_row('workout',array('id'=>$workout_id));
		$data['workout_notes']=$this->admin_model->get_workout_notes($limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'workout/notes/'.$workout_id;
		$config['total_rows'] = $this->admin_model->get_workout_notes(0, 0,$where);
		$config['per_page'] = $limit;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'workout/notes';
        $this->load->view('templates/trainer_template', $data);			
	}

	public function delete_notes($id="",$workout_id=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
        if(!$id){
			redirect(_INDEX.'workout/all');
        }
		$this->admin_model->delete('workout_notes',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"Deleted successfully.");
		redirect(_INDEX.'workout/notes/'.$workout_id);
	}


	public function exercise($workout_id="",$offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

        if(!$workout_id){
			redirect(_INDEX.'workout/all');
        }

		$where = array('workout_id'=>$workout_id);
        
		$limit=10;
		$data['workout']=$this->admin_model->get_row('workout',array('id'=>$workout_id));
		$data['exercise_notes']=$this->admin_model->get_pagination_result('exercise',$limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'workout/exercise/'.$workout_id;
		$config['total_rows'] = $this->admin_model->get_pagination_result('exercise',0, 0,$where);
		$config['per_page'] = $limit;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'workout/exercise';
        $this->load->view('templates/trainer_template', $data);			
	}

	public function exercise_notes($exercise_id="",$offset=0){		
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

        if(!$exercise_id){
			redirect(_INDEX.'workout/all');
        }

		$where =  array('exercise_id'=>$exercise_id);

		$limit=10;
		$data['exercise']=$this->admin_model->get_row('exercise',array('id'=>$exercise_id));
		$data['exercise_notes']=$this->admin_model->get_exercise_notes($limit,$offset,$where);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'workout/exercise_notes/'.$exercise_id;
		$config['total_rows'] = $this->admin_model->get_exercise_notes(0, 0,$where);
		$config['per_page'] = $limit;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		
        $data['template'] = 'workout/exercise_notes';
        $this->load->view('templates/trainer_template', $data);			
	}

	public function delete_exercise_notes($id="",$exercise_id=""){	
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');
        if(!$id){
			redirect(_INDEX.'workout/all');
        }
		$this->admin_model->delete('exercise_notes',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"Deleted successfully.");
		redirect(_INDEX.'workout/exercise_notes/'.$exercise_id);
	}

	public function check1(){
		print_r($_SERVER['HTTP_X_FORWARDED_FOR']);
	}

	public function add_exercise_box(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$id = $this->input->post('id');

		$data['exercise'] = $this->admin_model->get_row('default_exercises', array( 'id' => $id , 'trainer_id' => get_trainer_id()));
			
		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		echo $this->load->view('workout/add_exercise_box', $data, TRUE);
	}

	public function add_circuit_box(){
		if(trainer_login_in()===FALSE)
			redirect(_INDEX.'login/trainer_login');

		$id = $this->input->post('id');

		$slug = id_to_slug('default_circuit', $id);

		$data['circuit'] = $this->admin_model->get_circuit($slug);

		$this->db->where('trainer_id', get_trainer_id());
		$this->db->or_where('trainer_id', '0');
		$data['galleryimages'] = $this->admin_model->get_result('gallery');

		if($data['circuit'])
			echo $this->load->view('workout/add_circuit_box', $data, TRUE);
		else
			echo 0;
	}
}