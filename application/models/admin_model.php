<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {	

	public function insert($table_name='',  $data=''){
		$query=$this->db->insert($table_name, $data);
		if($query)
			return  $this->db->insert_id();
		else
			return FALSE;
	}
	
	public function get_result($table_name='', $id_array='',$id_array2=''){
		if(!empty($id_array)):		
			foreach ($id_array as $key => $value){
				$this->db->where($key, $value);
			}
		endif;
		if(!empty($id_array2)):		
			foreach ($id_array2 as $key => $value){
				$this->db->or_where($key, $value);
			}
		endif;
		$query=$this->db->get($table_name);
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}
	public function subscribe($limit='',$offset=''){	
		$this->db->order_by('id','desc');			
		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get('subscribes');
			if($query->num_rows()>0)
				return $query->result();
			else
				return FALSE;
		}else{
			$query=$this->db->get('subscribes');
			return $query->num_rows();
		}
	}
	public function get_row($table_name='', $id_array=''){
		if(!empty($id_array)):		
			foreach ($id_array as $key => $value){
				$this->db->where($key, $value);
			}
		endif;

		$query=$this->db->get($table_name);
		
		if($query->num_rows()>0)
			return $query->row();
		else
			return FALSE;
	}

	public function update($table_name='', $data='', $id_array=''){
		
		if(!empty($id_array)):
			foreach ($id_array as $key => $value){
				$this->db->where($key, $value);
			}
		endif;		
		return $this->db->update($table_name, $data);

	}

	public function delete($table_name='', $id_array){

		return $this->db->delete($table_name, $id_array);
	}

	public function get_pagination_result($table_name='', $limit='',$offset='', $id_array=''){
		if(!empty($id_array)):		
			foreach ($id_array as $key => $value){
				$this->db->where($key, $value);
			}
		endif;

		$this->db->order_by('id','desc');			
		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get($table_name);
			if($query->num_rows()>0)
				return $query->result();
			else
				return FALSE;
		}else{
			$query=$this->db->get($table_name);
			return $query->num_rows();
		}
	}

	public function get_limited_results($table_name='', $limit=''){		
		$this->db->order_by('id', 'desc');
		$query=$this->db->get($table_name, $limit);
		if($query->num_rows()>0)
			return $query->result();
		else
			return FALSE;
	}

	public function get_relatednews($category_id){
		$this->db->where('category_id', $category_id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get('posts',3);
		if($query->num_rows()>0)
				return $query->result();
			else
				return FALSE;
	}

	public function get_news($category, $limit, $offset){		
		if($category !="ALL"){
		$this->db->where('slug', $category);
		$cat = $this->db->get('categories');		
		$this->db->where('category_id',$cat->row()->id);
		$this->db->where('status', 1);
		$query = $this->db->get('posts', $limit, $offset);
		if($query->num_rows()>0)
				return $query->result();
			else
				return FALSE;
		}else{
			$this->db->where('status', 1);
			$query = $this->db->get('posts', $limit, $offset);
			if($query->num_rows()>0)
					return $query->result();
				else
					return FALSE;
		}	
	}

	public function filter_listings($post, $limit, $offset, $id_array=''){
		if(!empty($id_array)):		
			foreach ($id_array as $key => $value){
				$this->db->where($key, $value);
			}
		endif;

		if(!empty($post['srchfield'])){
			$this->db->like('city', $post['srchfield']);
			$this->db->or_like('state',$post['srchfield']);
			$this->db->or_like('zip',$post['srchfield']);
		}

		if(!empty($post['sort'])){
			if($post['sort'] == 'newest'){
				$this->db->order_by('id', 'desc');
			}

			if($post['sort'] == 'oldest'){
				$this->db->order_by('id', 'asc');
			}

			if($post['sort'] == 'h2l'){
				$this->db->order_by('price', 'desc');
			}

			if($post['sort'] == 'l2h'){
				$this->db->order_by('price', 'asc');
			}
		}

		if(!empty($post['cat']) && $post['cat'] !="all"){
			$category_id = $post['cat'];
		}else{
			$category_id="";
		}

		if($post['pricerange'] !=""){
			$val = explode('-', $post['pricerange']);
			$this->db->where("price BETWEEN ".$val[0]." AND ".$val[1]);
		}

		// $this->db->limit($limit, $offset);
		$query = $this->db->get('properties');
			$array = array();
			if($query->num_rows()>0){
				if($category_id !=""){
					foreach ($query->result() as $key){
						if($key->category_id == $category_id){
						$array[] = $key;
						}
					}
				}else{
					$array = $query->result();
				}
				$result['count']    = count($array);
				$result['listings'] = array_slice($array, $offset,$limit);
				return $result;				
			}
				else
					return FALSE;
	}

	// public function get_listing(){
	// 	$query = $this->db->get('posts', $limit, $offset);
	// 		if($query->num_rows()>0)
	// 				return $query->result();
	// 			else
	// 				return FALSE;
	// }



	public function get_pagination_where($table_name='', $limit='', $offset='', $condition='')
 	{ 
		if(!empty($condition)):  
			foreach ($condition as $key => $value){
			 	$this->db->where($key, $value);
			}
		endif;
		$this->db->order_by('id','desc');   
		if($limit > 0 && $offset>=0){
		$this->db->limit($limit, $offset);
		$query=$this->db->get($table_name);
		if($query->num_rows()>0)
		  return $query->result();
		else
		  return FALSE;
		}
		else{
		   $query=$this->db->get($table_name);
		   return $query->num_rows();
           }
 	}


 	public function get_subgroupmember($sg_id, $limit, $offset){
 		$this->db->select('gm.*, t.fname, t.lname, t.email');
 		$this->db->where('gm.subgroup_id', $sg_id);
 		$this->db->from('group_members as gm');
 		$this->db->join('trainee as t', 't.id = gm.trainee_id', 'left');
 		if($limit > 0 && $offset>=0){
		$this->db->limit($limit, $offset);
		$query=$this->db->get();
		if($query->num_rows()>0)
		  return $query->result();
		else
		  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
           }
 	}

 	public function get_trainee($trainer_id=''){
 		$this->db->select('t.*, grp.name as grp_name, sgrp.name as sgrp_name');
 		$this->db->where('t.trainer_id', $trainer_id);
 		$this->db->from('trainee as t');
 		$this->db->join('group_members as gm', 't.id = gm.trainee_id', 'left');
 		$this->db->join('sub_group as sgrp', 'gm.subgroup_id = sgrp.id', 'left');
 		$this->db->join('group as grp', 'grp.id = sgrp.group_id', 'left');
 		$this->db->group_by('gm.trainee_id');
 		$query = $this->db->get();
 		if($query->num_rows()>0)
		  return $query->result();
		else
		  return FALSE;
 	}


 	

 	public function get_workout($workout_id)
 	{
 		$data = array();
 		$workout = $this->get_row('workout', array('id' => $workout_id));

 		$data['date'] = date("m/d/Y", strtotime($workout->date));
 		$data['time'] = date("h:i A", strtotime($workout->time));
 		$data['trainee_id'] = $workout->trainee_id;
 		$data['name'] = $workout->name;
 		$data['anytime'] = $workout->anytime;
 		$data['image'] = $workout->image;
 		$data['status'] = $workout->status;
 		$data['description'] = $workout->description;
 		$data['type'] = $workout->type;
 		$data['circuit_name'] = $workout->circuit_name;
 		$data['circuit_description'] = $workout->circuit_description;
 		$data['circuit_image'] = $workout->circuit_image;
 		$exercise = $this->get_result('exercise', array('workout_id' => $workout_id));

 		$data['count_of_exercise'] = count($exercise);

	     if(!empty($exercise))
	     {
	 		foreach($exercise as $row)
	 		{
	 			$arr = array();
	 			$arr['exercise'] = $row->name;
	 			$arr['description'] = $row->description;
	 			$arr['image'] = $row->image;
	 			$arr['resttime'] = $row->resttime;
	 			$arr['exercise'] = $row->name;
	 			$arr['exercise_id'] = $row->id;
	 			$arr['exercise_status'] = $row->status;
	 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
	 			$arr['sets'] = $set;
	 			$data['exercises'][] = $arr;
	 		}
	     }

 		return $data;
 	}

 	public function get_circuit_workout($workout_id)
 	{
 		$data = array();
 		$workout = $this->get_row('workout', array('id' => $workout_id));

 		$data['date'] = date("m/d/Y", strtotime($workout->date));
 		$data['time'] = date("h:i A", strtotime($workout->time));
 		$data['trainee_id'] = $workout->trainee_id;
 		$data['name'] = $workout->name;
 		$data['anytime'] = $workout->anytime;
 		$data['image'] = $workout->image;
 		$data['status'] = $workout->status;
 		$data['description'] = $workout->description;
 		$data['type'] = $workout->type;
 		$data['circuit_name'] = $workout->circuit_name;
 		$data['circuit_description'] = $workout->circuit_description;
 		$data['circuit_image'] = $workout->circuit_image;
 		
 		$circuits = $this->get_result('circuit', array('workout_id' => $workout_id));

 		$exercise = $this->get_result('exercise', array('workout_id' => $workout_id));

 		$data['count_of_exercise'] = count($exercise);

	   	$data['circuits_data'] = array();

 		if(!empty($circuits))
	     {	

	 		foreach($circuits as $row2)
	 		{

	     		$circuits_data = array();

	     		$circuits_data['name'] = $row2->name;
	     		$circuits_data['id'] = $row2->id;
	     		$circuits_data['description'] = $row2->description;
	     		$circuits_data['image'] = $row2->image;
	     		$circuits_data['workout_id'] = $row2->workout_id;
	 			
	 			$exercise = $this->get_result('exercise', array('circuit_id' => $row2->id));

	     		$circuits_data['count'] = 0;
	 			
	 			if(!empty($exercise))
			     {
	     			$circuits_data['count'] = count($exercise);
			 		foreach($exercise as $row)
			 		{
			 			$arr = array();
			 			$arr['exercise'] = $row->name;
			 			$arr['circuit_id'] = $row->circuit_id;
			 			$arr['description'] = $row->description;
			 			$arr['image'] = $row->image;
			 			$arr['resttime'] = $row->resttime;
			 			$arr['exercise'] = $row->name;
			 			$arr['exercise_id'] = $row->id;
			 			$arr['exercise_status'] = $row->status;
			 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
			 			$arr['sets'] = $set;
			 			// $data['exercises'][] = $arr;
			 			$circuits_data['exercises'][] = $arr;
			 		}
			     }

			    $data['circuits_data'][] = $circuits_data;
	 			
	 		}
	     }



 		return $data;
 	}

 	public function delete_workout($workout_id)
 	{
 		$exercise = $this->get_result('exercise', array('workout_id' => $workout_id));
 		$workout = $this->get_row('workout', array('id' => $workout_id));
 		delete_from_bucket_server($workout->image);
        if(!empty($exercise))
        {
	 		foreach($exercise as $row){
	 			delete_from_bucket_server($row->image);
	 			$this->delete('exercise_set', array('exercise_id' => $row->id));
	 		}
        }
 		$this->delete('workout', array('id' => $workout_id));
 		$this->delete('exercise', array('workout_id' => $workout_id));
 		$this->delete('circuit', array('workout_id' => $workout_id));
 		$this->delete('trainee_workout', array('workout_id' => $workout_id));
 	}

 	public function delete_circuit_workout($workout_id)
 	{
 		$exercise = $this->get_result('exercise', array('workout_id' => $workout_id));
 		$workout = $this->get_row('workout', array('id' => $workout_id));
 		delete_from_bucket_server($workout->image);
        if(!empty($exercise))
        {
	 		foreach($exercise as $row){
	 			delete_from_bucket_server($row->image);
	 			$this->delete('exercise_set', array('exercise_id' => $row->id));
	 		}
        }
 		$this->delete('workout', array('id' => $workout_id));
 		$this->delete('exercise', array('workout_id' => $workout_id));
 		$this->delete('circuit', array('workout_id' => $workout_id));
 		$this->delete('trainee_workout', array('workout_id' => $workout_id));
 	}

 	public function filter_trainee($group,$search){

 		$this->db->select('t.*, grp.name as grp_name, sgrp.name as sgrp_name');
 		$this->db->where('t.trainer_id', get_trainer_id());
 		if($search !="")
 		$this->db->where("( `t`.`fname` LIKE '%$search%' OR `t`.`lname` like '%$search%'  )");

 		if(!empty($group) &&  $group != "ALL"){ 			
 			$grp = explode('_', $group); 			
 			if($grp[0] == 'grp'){
 				$this->db->where('grp.id', $grp[1]);
 			}elseif($grp[0] == 'subgrp'){
 				$this->db->where('sgrp.id', $grp[1]); 				
 			}
 		}

 		$this->db->from('trainee as t');
 		$this->db->join('group_members as gm', 't.id = gm.trainee_id', 'left');
 		$this->db->join('sub_group as sgrp', 'gm.subgroup_id = sgrp.id', 'left');
 		$this->db->join('group as grp', 'grp.id = sgrp.group_id', 'left');
 		$this->db->group_by('gm.trainee_id');
 		$query = $this->db->get();
 		if($query->num_rows()>0)
		  return $query->result();
		else
		  return FALSE;
 		
 	}

 	public function get_groups($trainer_id){
 		$this->db->select('grp.*, sgrp.name as grp_name, sgrp.id as sgrp_id, sgrp.id as group_id');
 		$this->db->where('grp.trainer_id', get_trainer_id()); 		
 		$this->db->from('group as grp'); 		
 		$this->db->join('sub_group as sgrp', 'grp.id = sgrp.group_id', 'left'); 		 		
 		$query = $this->db->get();
 		if($query->num_rows()>0){
 			$arr = array();
		  $result = $query->result();
		  foreach ($result as $row) {
		  	$arr[$row->id.'|-N-|'.$row->name][] = $row;
		  }
		  return $arr;		  
 		}
		else
		  return FALSE;
 	}


 	public function get_conversation($support_token2){ 		
 		$this->db->select('c.*, t.fname');
 		$this->db->from('conversation as c');
 		$this->db->where('c.support_token2',$support_token2);
 		$this->db->order_by('c.id', 'asc'); 		
 		$this->db->join('trainer as t', 'c.sender_id = t.id', 'left');
 		$query = $this->db->get();
 		if($query->num_rows()>0){ 			
		  return $query->result();
		}
 	}


 	// public function get_conversation($support_id){ 		
 	// 	$this->db->select('c.*, t.fname');
 	// 	$this->db->from('conversation as c');
 	// 	$this->db->where('c.support_id', $support_id);
 	// 	$this->db->order_by('c.id', 'asc'); 		
 	// 	$this->db->join('trainer as t', 'c.sender_id = t.id', 'left');
 	// 	$query = $this->db->get();
 	// 	if($query->num_rows()>0){ 			
		//   return $query->result();
		// }
 	// }

 	public function get_grouptrainee($trainer_id){
 		$this->db->select('g.id as grp_id, gm.trainee_id');
 		$this->db->from('group as g');
 		$this->db->join('sub_group as sg', 'g.id = sg.group_id');
 		$this->db->join('group_members as gm', 'sg.id = gm.subgroup_id');
 		$this->db->where('g.trainer_id', $trainer_id);
 		$query = $this->db->get();
 		if($query->num_rows()>0){ 			
		  return $query->result();
		}else{
			return FALSE;
		}
 	}


 	public function get_trainee_workout($id){
 		$this->db->select('w.*, tw.trainee_id');
 		$this->db->from('workout as w');
 		$this->db->where('tw.trainee_id', $id);
 		$this->db->join('trainee_workout as tw', 'tw.workout_id = w.id');
 		$query = $this->db->get();
 		if($query->num_rows()>0){ 			
		  return $query->result();
		  // return $query->result();
		}else{
			return FALSE;
		}
 	}

 	public function get_trainee_workouts($trainee_id, $limit,$offset){
 		$this->db->select('w.*, tw.trainee_id');
 		$this->db->from('workout as w');
 		$this->db->where('tw.trainee_id', $trainee_id);
 		$this->db->join('trainee_workout as tw', 'tw.workout_id = w.id');
 		$this->db->order_by('w.id','desc');			
		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
				return $query->result();
			else
				return FALSE;
		}else{
			$query=$this->db->get();
			return $query->num_rows();
		}
 	}

 	public function getWorkoutsOfTheDay($date='', $trainee_id=0){
 		// $id_array = array($id, ($id+1), ($id-1));
 		$this->db->select('w.id, w.name');
 		$this->db->from('workout as w');
 		$this->db->join('trainee_workout as tw', 'tw.workout_id = w.id');
 		$this->db->where('w.date', $date);
 		// $this->db->where("DATE_FORMAT(w.date,'%Y-%m-%d') = $date",NULL,FALSE);
 		$this->db->where('tw.trainee_id', $trainee_id);
 		// $this->db->group_by('w.id');
 		$this->db->order_by('w.time', 'asc');
 		$query = $this->db->get();
 		if($query->num_rows()>0){
		  return $query->result();
		  // return $query->result();
		}else{
			return FALSE;
		}
 	}

 	public function getWorkoutDetails($id){

// faraz work 
	// add code : e.circuit_id 		
// faraz work 

 		$this->db->select('e.workout_id,e.circuit_id,e.name,e.status,e.description,e.image,e.resttime ,e.id as eId, es.id, es.value, es.reps, es.time');
 		$this->db->from('exercise as e');
 		$this->db->join('exercise_set as es', 'es.exercise_id = e.id');
 		// $this->db->join('exercise_set_results as esr', 'esr.exercise_set_id = es.id','left');
 		// $this->db->join('exercise_notes as en', 'en.exercise_id = e.id', 'left');
 		$this->db->where('e.workout_id', $id);
 		$query = $this->db->get();
 		if($query->num_rows()>0){ 			
		  return $query->result();
		  // return $query->result();
		}else{
			return FALSE;
		}
 	}


 	public function get_todays_workout($id){	 	
	 	//$id = 1;
	 	$this->db->select('w.*, tw.trainee_id');
 		$this->db->from('workout as w');
 		$this->db->order_by('w.date', 'asc');
 		$this->db->where('tw.trainee_id', $id);
 		$this->db->join('trainee_workout as tw', 'tw.workout_id = w.id');
 		$query = $this->db->get();
 		$workout = '';
 		if($query->num_rows() > 0){ 			
	 		foreach ($query->result() as $key) {
	 			if(date('Y-m-d') == $key->date){
	 				// if(date('H:i:s') <= $key->time){
	 					$workout = $key; 	 				
	 					break;
	 				// }
	 			}elseif(date('Y-m-d') < $key->date){	 					 				
		 			// $workout = $key; 
		 			//print_r($key); die();
		 			// break;		 			
	 			}
	 		} 					

	 		//$workout = $this->get_row('workout', array('id' => $workout_id));
	 		if(!empty($workout)){
	 			$data = array();
		 		$data['date'] = date("M d,Y", strtotime($workout->date));
		 		$data['time'] = date("h:i", strtotime($workout->time));
		 		$data['trainee_id'] = $workout->trainee_id;
		 		$data['workout_id'] = $workout->id;
		 		$data['name'] = $workout->name;
		 		$data['notes'] = $workout->notes;
		 		$data['status'] = $workout->status;
		 		$data['description'] = $workout->description;
		 		$data['image'] = $workout->image;
		 		$exercise = $this->get_result('exercise', array('workout_id' => $workout->id));
		 		$data['count_of_exercise'] = count($exercise);

		 		foreach($exercise as $row){
		 			$arr = array();
		 			$arr['exercise'] = $row->name;
		 			$arr['exercise_id'] = $row->id;
		 			$arr['description'] = $row->description;
		 			$arr['resttime'] = $row->resttime;
		 			$arr['image'] = $row->image;
		 			$arr['status'] = $row->status;
		 			$this->db->select('value,reps,resultweight,resultreps,id as set_id');
		 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
		 			$arr['sets'] = $set;
		 			$data['exercises'][] = $arr;
		 		}

		 		return $data;

	 		}else{ 		
	 			return FALSE;
	 		}

 		}else{
	 		return FALSE;
	 	}
	 }

	 public function get_todays_workout_t($id){	 	
	 	//$id = 1;
	 	$this->db->select('w.*, tw.trainee_id, wn.notes');
 		$this->db->from('workout as w');
 		$this->db->order_by('w.date', 'asc');
 		$this->db->where('tw.trainee_id', $id);
 		$this->db->join('trainee_workout as tw', 'tw.workout_id = w.id');
 		$this->db->join('workout_notes as wn', 'wn.workout_id = w.id' , 'LEFT');
 		$query = $this->db->get();
 		
 		$workout = '';
 		if($query->num_rows() > 0){ 			
	 		foreach ($query->result() as $key) {
	 			if(date('Y-m-d') == $key->date){
	 				// if(date('H:i:s') <= $key->time){
	 					$workout = $key; 	 				
	 					break;
	 				// }
	 			}elseif(date('Y-m-d') < $key->date){	 					 				
		 			// $workout = $key; 
		 			//print_r($key); die();
		 			// break;		 			
	 			}
	 		} 					

	 		//$workout = $this->get_row('workout', array('id' => $workout_id));
	 		if(!empty($workout)){
	 			$data = array();
		 		$data['date'] = date("M d,Y", strtotime($workout->date));
		 		$data['time'] = date("h:i", strtotime($workout->time));
		 		$data['trainee_id'] = $workout->trainee_id;
		 		$data['workout_id'] = $workout->id;
		 		$data['name'] = $workout->name;
		 		$data['notes'] = $workout->notes;
		 		
		 		$data['status'] = $workout->status;
		 		
		 		if($workout->notes == null)
		 			$data['status'] = 0;
		 		else
		 			$data['status'] = 1;

		 		$data['description'] = $workout->description;
		 		$data['image'] = $workout->image;
		 		$exercise = $this->get_result('exercise', array('workout_id' => $workout->id));
		 		$data['count_of_exercise'] = count($exercise);

		 		foreach($exercise as $row){
		 			$arr = array();
		 			$arr['exercise'] = $row->name;
		 			$arr['exercise_id'] = $row->id;
		 			$arr['description'] = $row->description;
		 			$arr['resttime'] = $row->resttime;
		 			$arr['image'] = $row->image;
		 			$arr['status'] = $row->status;
		 			$this->db->select('value,reps,resultweight,resultreps,id as set_id');
		 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
		 			$arr['sets'] = $set;
		 			$data['exercises'][] = $arr;
		 		}

		 		return $data;

	 		}else{ 		
	 			return FALSE;
	 		}

 		}else{
	 		return FALSE;
	 	}
	 }


	 public function get_groupmember($group_id, $limit, $offset){
    $this->db->select('gm.*, t.id as trainee_idd , t.fname, t.lname, t.email');
    $this->db->from('group_members as gm');
    $this->db->join('trainee as t', 't.id = gm.trainee_id', 'left');
    $this->db->where('gm.group_id', $group_id);
    
    if($limit > 0 && $offset>=0){
      $this->db->limit($limit, $offset);
      $query=$this->db->get();
      if($query->num_rows()>0)
        return $query->result();
      else
        return FALSE;
    }
    else{
       $query=$this->db->get();
       return $query->num_rows();
    }
  }


    public function get_trainer_trainee($trainer_id=""){
    $this->db->select('gm.*');
    $this->db->from('group as g');
    $this->db->join('group_members as gm', 'gm.group_id = g.id');
    $this->db->where('g.trainer_id',$trainer_id);
    $query=$this->db->get();
    if($query->num_rows()>0){
      return $query->result();
    }
    else
      return FALSE;
  }

  	public function get_trainee_all($trainer_id="",$where,$limit,$offset){
 		$this->db->select('trainee.slug,trainee.fname,trainee.lname,trainee.id as trnid,g.name as groupname,g.id as groupid,g.parent_id');
 		$this->db->from('trainee');
 		$this->db->join('group_members as gm', 'gm.trainee_id = trainee.id','left');
 		$this->db->join('group as g', 'g.id = gm.group_id','left');
 		$this->db->where('trainee.trainer_id',$trainer_id);
		
        if(!empty($where['t_name'])){
	 		$this->db->like('trainee.fname',$where['t_name']);
	 		$this->db->or_like('trainee.lname',$where['t_name']);
        }

        if(!empty($where['t_group'])){
	 		$this->db->where('g.id',$where['t_group']);
        }

        if(!empty($where['sort_by'])){
        	$sort = explode('-',$where['sort_by']);
            $sort_by = $sort[0];
            if($sort_by=="created"){
		 		$this->db->order_by('trainee.created',$sort[1]);
            	$this->db->order_by('trainee.lname','asc');
            } 
            if($sort_by=="name"){
		 		$this->db->order_by('trainee.lname',$sort[1]);
            } 
            if($sort_by=="group"){
		 		$this->db->order_by('g.name',$sort[1]);
            	$this->db->order_by('trainee.lname','asc');
            } 
        }else{
	 		// $this->db->order_by('g.name','asc');
	 		$this->db->order_by('trainee.lname','asc');
	 	}



 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();

			// echo $this->db->last_query();
			// die();

			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}

 	public function select_groups($flag=0){
 		$trainer_id = get_trainer_id();
 		$this->db->select('g.*');
 		$this->db->from('group as g');
 		$this->db->group_by('g.id');
 		$this->db->order_by('g.name', 'asc');
 		$this->db->where('trainer_id', $trainer_id);
 		$query = $this->db->get();
 		if($flag == 0){
			if($query->num_rows()>0){
				return $query->result();
			}else{
				return FALSE;
			}
 		}else{
 			return $query->num_rows();
 		}
 	}

 	public function select_groups_members($flag=0){
 		$trainer_id = get_trainer_id();
 		$this->db->select('t.id, t.fname, t.lname, gm.group_id');
 		$this->db->from('trainee as t');
 		$this->db->join('group_members as gm', 'gm.trainee_id = t.id', 'left');
 		$this->db->group_by('t.id');
 		$this->db->order_by('t.lname', 'asc');
 		$this->db->order_by('gm.group_id', 'asc');
 		$this->db->where('t.trainer_id', $trainer_id);
 		$query = $this->db->get();
 		if($flag == 0){
			if($query->num_rows()>0){
				return $query->result();
			}else{
				return FALSE;
			}
 		}else{
 			return $query->num_rows();
 		}
 	}

 	public function get_common_exercise($selected_members){
 		$this->db->where_in('trainee_id', $selected_members);
 		$query = $this->db->get('trainee_workout');
 		if($query->num_rows()>0){
			$workout = array();
			foreach($query->result() as $row){
				if(isset($workout[$row->workout_id])){
					$workout[$row->workout_id] = $workout[$row->workout_id] + 1;
				}
				else{
					$workout[$row->workout_id] = 1;
				}
			}
			$workout_ids = array();
			foreach($workout as $key => $val){
				if($val == count($selected_members)){
					$workout_ids[] = $key;
				}
			}
			if(count($workout_ids) == 0){
				return FALSE;
			}
			else{
				$this->db->where_in('id', $workout_ids);
		 		$query = $this->db->get('workout');
		 		if($query->num_rows()>0){
					foreach($query->result() as $row){
						$this->db->or_where('e.workout_id', $row->id);
					}
					$this->db->select('e.id, e.name, e.workout_id, e.default_exercise_id, w.name as wname, w.date');
					$this->db->order_by('e.name', 'asc');
					$this->db->join('workout as w', 'w.id = e.workout_id');
					// $this->db->group_by('e.id');
					// $this->db->group_by('e.default_exercise_id');

					$this->db->from('exercise as e');
					$query = $this->db->get();
					if($query->num_rows() > 0){
						
						$result = $query->result();

						$arr = array();
						foreach($result as $row){
							if(strtotime($row->date) <= time()){
								$arr[$row->default_exercise_id] = $row;
							}
						}

						if(count($arr) > 0)
							$result = $arr;
						else
							return FALSE;


						return $result;
					}
					else{
						return FALSE;
					}
				}
				else{
					return FALSE;
				}
			}
		}
		else
			return FALSE;
 	}

 	public function manage_trainee($limit,$offset,$where){
 		$this->db->select('*');
 		$this->db->from('trainee');
 		$this->db->where('trainer_id',get_trainer_id());

        if(!empty($where['sort_by'])){
        	$sort = explode('-',$where['sort_by']);
            $sort_by = $sort[0];
            if($sort_by=="created"){
		 		$this->db->order_by('created',$sort[1]);
            } 
            if($sort_by=="name"){
		 		$this->db->order_by('lname',$sort[1]);
            } 
        }
        else{
        	$this->db->order_by('lname','asc');
        }



 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}


 	public function manage_groups($limit,$offset,$where){
 		$this->db->select('*');
 		$this->db->from('group');
 		$this->db->where('trainer_id',get_trainer_id());
        if(!empty($where['sort_by'])){
        	$sort = explode('-',$where['sort_by']);
            $sort_by = $sort[0];
            if($sort_by=="created"){
		 		$this->db->order_by('created',$sort[1]);
            } 
            if($sort_by=="group"){
		 		$this->db->order_by('name',$sort[1]);
            } 
        }

 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}


	public function get_trainee_queries($limit,$offset){
 		$this->db->select('s.*,tr.fname as trfname,tr.lname as trlname,');
 		$this->db->order_by('s.id', 'desc');
 		$this->db->from('support as s');
 		$this->db->join('trainee as tr', 'tr.id = s.trainee_id','left');
 		$this->db->where(array('s.trainer_id'=>get_trainer_id() , 's.by'=>'2'));

 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}

 	public function get_workout_notes($limit,$offset,$where){
 		$this->db->select('wkt.*,tr.fname,tr.lname');
 		$this->db->from('workout_notes as wkt');
 		$this->db->join('trainee as tr','tr.id = wkt.trainee_id','left');
 		$this->db->where($where);
 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}

 	public function get_exercise_notes($limit,$offset,$where){
 		$this->db->select('ex_notes.*,tr.fname,tr.lname');
 		$this->db->from('exercise_notes as ex_notes');
 		$this->db->join('trainee as tr','tr.id = ex_notes.trainee_id','left');
 		$this->db->where($where);
 		if($limit > 0 && $offset>=0){
			$this->db->limit($limit, $offset);
			$query=$this->db->get();
			if($query->num_rows()>0)
			  return $query->result();
			else
			  return FALSE;
		}
		else{
		   $query=$this->db->get();
		   return $query->num_rows();
		}
 	}

 	public function get_workout_with_notes($workout_id){
 		$this->db->select('w.*, wn.notes');
 		$this->db->where('w.id', $workout_id);
 		$this->db->join('workout_notes as wn', 'wn.workout_id = w.id', 'left');
 		$this->db->from('workout as w');
 		$query = $this->db->get();
 		if($query->num_rows() > 0)
 			return $query->row();
 		else
 			return FALSE;
 	}

 	public function trainee_workout_notes($workout_id,$trainee_id=0){
 		$this->db->select('w.*, wn.notes');
 		$this->db->where('w.id', $workout_id);
 		$this->db->join('workout_notes as wn', 'wn.workout_id = w.id', 'left');
 		$this->db->from('workout as w');
 		$this->db->where('wn.trainee_id',$trainee_id);
 		$query = $this->db->get();
 		if($query->num_rows() > 0)
 			return $query->row();
 		else
 			return FALSE;
 	}

 	public function get_exercise_pre_name($trainer_id = 0){
 		$this->db->select('id');
 		$this->db->where('trainer_id', $trainer_id);
 		$query = $this->db->get('workout');

 		if($query->num_rows() > 0){
 			$where_in = array();
 			foreach($query->result() as $row)
 				$where_in[] = $row->id;

 			$this->db->select('id, name');
 			$this->db->where_in('workout_id', $where_in);
 			$this->db->group_by('name');
 			$this->db->order_by('name', 'asc');
 			$query = $this->db->get('exercise');

 			if($query->num_rows() > 0)
	 			return $query->result();
	 		else
	 			return FALSE;
 		}
 		else{
 			return FALSE;
 		}
 	}

	public function delete_circuit($slug=''){
 		$c_row = $this->get_row('default_circuit', array('slug' => $slug , 'trainer_id' => get_trainer_id()));
 		if($c_row){
 			$this->delete('default_circuit', array('id' => $c_row->id));
	 		$exercise = $this->get_result('default_circuit_exercise', array('circuit_id' => $c_row->id));
	 		if(!empty($exercise)){
		 		foreach($exercise as $row){
		 			delete_from_bucket_server($row->image);
		 			$this->delete('default_circuit_exercise_set', array('circuit_exercise_id' => $row->id));
		 		}
	        }
	 		$this->delete('default_circuit_exercise', array('circuit_id' => $c_row->id));
	 	}
 	}

 	public function get_circuit($slug=''){
 		$data = array();
 		$default_circuit = $this->get_row('default_circuit', array('slug' => $slug , 'trainer_id' => get_trainer_id()));

 		if(!$default_circuit){
 			return FALSE;
 		}

 		$data['name'] = $default_circuit->name;
 		$data['description'] = $default_circuit->description;
 		$data['circuit_id'] = $default_circuit->id;
 		$data['circuit_slug'] = $default_circuit->slug;
 		
 		$exercise = $this->get_result('default_circuit_exercise', array('circuit_id' => $default_circuit->id));

 		if(!empty($exercise)){
	 		foreach($exercise as $row){
	 			$arr = array();
	 			$arr['exercise'] = $row->name;
	 			$arr['default_exercise_id'] = $row->default_exercise_id;
	 			$arr['description'] = $row->description;
	 			$arr['image'] = $row->image;
	 			$arr['exercise_id'] = $row->id;
	 			$set = $this->get_result('default_circuit_exercise_set', array('circuit_exercise_id' => $row->id));
	 			$arr['sets'] = $set;
	 			$data['exercises'][] = $arr;
	 		}
		}
 		return $data;
 	}

 	public function get_new_workout($workout_id = 0, $trainer_id = 0){
 		$data = array();
 		$workout = $this->get_row('workout', array('id' => $workout_id , 'trainer_id' => $trainer_id));

 		if(!$workout){
 			return FALSE;
 		}

 		$data['workout_id'] = $workout->id;
 		$data['date'] = date("m/d/Y", strtotime($workout->date));
 		$data['name'] = $workout->name;
 		$data['image'] = $workout->image;
 		$data['description'] = $workout->description;
 		$data['type'] = $workout->type;
 		
 		$exercises = $this->get_result('exercise', array('workout_id' => $workout_id));

 		if(!$exercises){
 			return FALSE;
 		}

 		$arr = array();
 		foreach($exercises as $row){
 			if($row->circuit_id == '0'){
 				$arr[] = array(
 					'exercise' => $row->id
 				);
 			}
 			else{
 				$arr[$row->circuit_id]['circuit'][] = $row->id;
 			}
 		}

 		$index = 1;
 		foreach($arr as $temp_circuit_id => $rav){
 			foreach($rav as $key => $var ){
 				if($key == 'exercise'){
 					$temp = array();
 					$exercise = $this->get_row('exercise', array('id' => $var));
 					if($exercise){
 						$temp['exercise_id'] = $exercise->id;
 						$temp['exercise'] = $exercise->name;
 						$temp['default_exercise_id'] = $exercise->default_exercise_id;
 						$temp['description'] = $exercise->description;
 						$temp['image'] = $exercise->image;
 						$sets = $this->get_result('exercise_set', array('exercise_id' => $exercise->id));
	 					$temp['sets'] = $sets;
	 					$data['exercises']['ex_'.$index] = $temp;
 					}
 				}
 				elseif($key == 'circuit'){
 					// $data['exercises']['cr_'.$index] = $temp;
 					$circuit_temp = array();
 					$circuit = $this->get_row('circuit', array('id' => $temp_circuit_id));
 					if($circuit){
 						$circuit_temp['circuit_id'] = $circuit->id;
 						$circuit_temp['circuit'] = $circuit->name;
 						$circuit_temp['description'] = $circuit->description;
 						$exercises = $this->get_result('exercise', array('circuit_id' => $circuit->id));
 						if($exercises){
 							foreach($exercises as $exercise){
 								$temp = array();
		 						$temp['exercise_id'] = $exercise->id;
 								$temp['exercise'] = $exercise->name;
 								$temp['default_exercise_id'] = $exercise->default_exercise_id;
		 						$temp['description'] = $exercise->description;
		 						$temp['image'] = $exercise->image;
		 						$sets = $this->get_result('exercise_set', array('exercise_id' => $exercise->id));
			 					$temp['sets'] = $sets;
			 					$circuit_temp['exercises'][] = $temp;
							}
							$data['exercises']['cr_'.$index] = $circuit_temp;
 						}
 					}
 				}
 				$index++;
 			}
 		}
 		return $data;
 	}

 	public function delete_new_circuit($circuit_id = 0){
 		$this->delete('circuit', array('id' => $circuit_id));
 		$exercise = $this->get_result('exercise', array('circuit_id' => $circuit_id));
 		if($exercise){
 			foreach($exercise as $row){
 				$this->delete_new_exercise($row->id);
 			}
 		}
 		return TRUE;
 	}

 	public function delete_new_exercise($exercise_id = 0){
 		$this->delete('exercise', array('id' => $exercise_id));
 		$exercise_set = $this->get_result('exercise_set', array('exercise_id' => $exercise_id));
 		if($exercise_set){
 			foreach($exercise_set as $row){
 				$this->delete_new_exercise_set($row->id);
 			}
 		}
 		return TRUE;
 	}

 	public function delete_new_exercise_set($exercise_set_id = 0){
 		$this->delete('exercise_set', array('id' => $exercise_set_id));
 		return TRUE;
 	}

 	public function get_new_workout_structure_for_edit($workout_id = 0){
 		$data = array();
 		$exercises = $this->get_result('exercise', array('workout_id' => $workout_id));
 		$arr = array();
 		if($exercises){
	 		foreach($exercises as $row){
	 			if($row->circuit_id == '0'){
	 				$arr[] = array(
	 					'exercise' => $row->id
	 				);
	 			}
	 			else{
	 				$arr[$row->circuit_id]['circuit'][] = $row->id;
	 			}
	 		}
	 	}

 		$index = 1;
 		foreach($arr as $temp_circuit_id => $rav){
 			foreach($rav as $key => $var ){
 				if($key == 'exercise'){
 					$temp = array();
 					$exercise = $this->get_row('exercise', array('id' => $var));
 					if($exercise){
 						$temp['exercise_id'] = $exercise->id;
 						$this->db->select('id as exercise_set_id');
 						$this->db->where('exercise_id', $exercise->id);
 						$this->db->from('exercise_set');
 						$query = $this->db->get();
 						$sets = $query->result_array();
	 					$temp['sets'] = $sets;
	 					$data[$index] = $temp;
 					}
 				}
 				elseif($key == 'circuit'){
 					// $data['exercises']['cr_'.$index] = $temp;
 					$circuit_temp = array();
 					$circuit = $this->get_row('circuit', array('id' => $temp_circuit_id));
 					if($circuit){
 						$circuit_temp['circuit_id'] = $circuit->id;
 						$this->db->select('id as exercise_id');
 						$exercises = $this->get_result('exercise', array('circuit_id' => $circuit->id));
 						if($exercises){
 							foreach($exercises as $exercise){
 								$temp = array();
		 						$temp['exercise_id'] = $exercise->exercise_id;
 								$this->db->select('id as exercise_set_id');
		 						$this->db->where('exercise_id', $exercise->exercise_id);
		 						$this->db->from('exercise_set');
		 						$query = $this->db->get();
		 						$sets = $query->result_array();

			 					$temp['sets'] = $sets;
			 					$circuit_temp['exercises'][] = $temp;
							}
							$data[$index] = $circuit_temp;
 						}
 					}
 				}
 				$index++;
 			}
 		}
 		
 		if(count($data) > 0)
 			return $data;
 		else
 			return FALSE;
 	}

 	public function get_default_exercise_id($exercise_id=0){
 		$row = $this->get_row('exercise', array('id' => $exercise_id));
 		if($row){
 			return $row->default_exercise_id;
 		}
 		return FALSE;
 	}
}