<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Controller {
	protected	$order;
	protected	$now;

	public function __construct(){
		parent::__construct();
		$this->now = date("Y-m-d H:i:s");
		$this->load->helper('convert_image_to_base64');
	}

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

	public function index(){
		if($_POST){
			if(isset($_POST['method'])){
				$method = $this->input->post('method');
				if(method_exists($this, $method)){
		        	return call_user_func_array(array($this, $method), array());
		        	exit();
		    	}
		    	else{
		    		$res = array('error' => 'METHOD NOT FOUND !!!');
					echo json_encode($res);
					exit();
		    	}
			}
			else{
				$res = array('error' => 'METHOD PARAMETER IS MISSING !!!');
				echo json_encode($res);
				exit();
			}
		}
		else{
			$res = array('error' => 'NO REQUEST FOUND !!!');
			echo json_encode($res);
			exit();
		}
	}

	private function get_user_name($user_id='') {
    	$this->db->select('name');
    	$this->db->where('user_id', $user_id);
    	$this->db->from('users_profiles');
    	$query = $this->db->get();
    	if($query->num_rows() > 0){
    		return $query->row()->name;
    	}
    	return 'Unknown';
    }

	public function admin_login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$this->db->where('email', $email);
		$this->db->where('password', sha1($password));		
		$query=$this->db->from('users');			
		$query=$this->db->get();
		if ($query->num_rows()==1){	
			$row=array(
				'id'			=>	$query->row()->id,
				'name'			=>  $this->get_user_name($query->row()->id),
				'email'			=>	$query->row()->email,
			);						
			
			$res = array('success' => array( 'result' => $row ));
			echo json_encode($res);
			exit();
		}else{				
			$res = array('error' => 'Username/Password is incorrect. Please retry.');
			echo json_encode($res);
			exit();
		}
	}

	public function trainer_login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$this->db->where('email', $email);
		$this->db->where('password', sha1($password));		
		$query=$this->db->from('trainer');			
		$query=$this->db->get();
		if ($query->num_rows()==1){	
			if($query->row()->status != '0'){
				$row=array(
					'id'			=>	$query->row()->id,
					'name'			=>  $query->row()->fname.' '.$query->row()->lname,
					'email'			=>	$query->row()->email,
				);						
				
				$res = array('success' => array( 'result' => $row ));
				echo json_encode($res);
				exit();
			}else{
				$res = array('error' => 'Your account not activated yet.');
				echo json_encode($res);
				exit();
			}
		}else{				
			$res = array('error' => 'Username/Password is incorrect. Please retry.');
			echo json_encode($res);
			exit();
		}
	}

	public function trainee_login(){
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$this->db->where('email', $email);
		$this->db->where('password', sha1($password));		
		$query=$this->db->from('trainee');			
		$query=$this->db->get();
		if ($query->num_rows()==1){	
			$row=array(
				'id'			=>	$query->row()->id,
				'name'			=>  $query->row()->fname.' '.$query->row()->lname,
				'trainer_id'	=>	$query->row()->trainer_id,
				'email'			=>	$query->row()->email,
				'fname'			=>	$query->row()->fname,
				'image'			=>	$query->row()->image,
				'lname'			=>	$query->row()->lname,
				'height'		=>	$query->row()->height,
				'currentweight'	=>	$query->row()->currentweight,
				'goalweight'	=>	$query->row()->goalweight,
				'address'		=>	$query->row()->address,
				'city'			=>	$query->row()->city,
			);						
			
			$res = array('success' => array( 'result' => $row ));
			echo json_encode($res);
			exit();
		}else{				
			$res = array('error' => 'Username/Password is incorrect. Please retry.');
			echo json_encode($res);
			exit();
		}
	}



	public function trainee_signup(){
		$fname = $this->input->post('fname');
		$lname = $this->input->post('lname');
		$email = $this->input->post('email');
		$password = sha1($this->input->post('password'));		
		$exist = $this->db->get_where('trainee', array('email'=>$email));
		if($exist->num_rows > 0){
			$res = array('error' => 'Email Already Exists');
			echo json_encode($res);
			exit();
		}


		$data = array(
			'slug'=>create_slug('trainee', $this->input->post('fname').' '.$this->input->post('lname')),
			'fname' => $fname,
			'lname' => $lname,
			'email' => $email,
			'password' => $password,
			'trainer_id' => 4,
			'token' => get_token(),
			'lastupdated' => time(),
			'created' => date('Y-m-d H:i:s')
		);

		$q=$this->db->insert('trainee', $data);
		
		if($q)
		$id =$this->db->insert_id();		 
		
		$this->db->where('id', $id);
		$query=$this->db->get('trainee');
		if ($query->num_rows()==1){	
			$row=array(
				'id'			=>	$query->row()->id,
				'name'			=>  $query->row()->fname.' '.$query->row()->lname,
				'email'			=>	$query->row()->email,
				'fname'			=>	$query->row()->fname,
				'image'			=>	$query->row()->image,
				'lname'			=>	$query->row()->lname,
				'height'		=>	$query->row()->height,
				'currentweight'	=>	$query->row()->currentweight,
				'goalweight'	=>	$query->row()->goalweight,
				'address'		=>	$query->row()->address,
				'city'			=>	$query->row()->city,
			);						
			
			$res = array('success' => array( 'result' => $row ));
			echo json_encode($res);
			exit();
		}else{				
			$res = array('error' => 'Something went wrong. Please retry.');
			echo json_encode($res);
			exit();
		}
	}

// Trainer Module Start

	public function add_trainer(){
		$fname 		= $this->input->post('fname');
		$lname 		= $this->input->post('lname');
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');
		$cpassword 	= $this->input->post('password');
		$address 	= $this->input->post('address');
		$city 		= $this->input->post('city');
		
		if(($fname != '') && ($lname != '') && ($email != '') && ($password != '') && ($address != '') && ($city != '')){
			
			$row = $this->get_row('trainer', array('email' => $this->input->post('email')));

			if($row){
				$res = array('error' => 'This email is already exist.');
				echo json_encode($res);
				exit();
			}
			if ($password != $cpassword) {
				$res = array('error' => 'Password confirmation failed.');
				echo json_encode($res);
				exit();	
			}

			$data=array(
				'slug'=>create_slug('trainer', $this->input->post('fname').' '.$this->input->post('lname')),
				'fname'=>$this->input->post('fname'),
				'lname'=>$this->input->post('lname'),
				'email'=>$this->input->post('email'),
				'password'=>sha1($this->input->post('password')),	
				'address'=>$this->input->post('address'),	
				'city'=>$this->input->post('city'),
				'created' => date('Y-m-d H:i:s'),			
				'updated' => date('Y-m-d H:i:s')			
			);	
			
			$trainer_id = $this->insert('trainer',$data);
			$res = array('success' => 'Trainer added successfully.' , 'trainer_id' => $trainer_id);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'Please check parameters.');
			echo json_encode($res);
			exit();
		}
	}

	public function edit_trainer(){
		$trainer_id = $this->input->post('trainer_id');
		
		if($trainer_id != ''){
			
			$trainer = $this->get_row('trainer', array('id' => $trainer_id));

			if(empty($trainer)){
				$res = array('error' => 'Invalid trainer id.');
				echo json_encode($res);
				exit();
			}

			$newSlug='';

			if($this->input->post('fname') != ''){
				$data['fname'] = $this->input->post('fname');
				$newSlug .= $this->input->post('fname');
			}

			if($this->input->post('lname') != ''){
				$data['lname'] = $this->input->post('lname');
				if (!empty($newSlug)){
					$newSlug .= ' '.$this->input->post('lname');
				}
				else
					$newSlug .= $trainer->lname.' '.$this->input->post('lname');
			}else{
				if (!empty($newSlug)){
					$newSlug .= ' '.$trainer->lname;
				}
			}

			if (!empty($newSlug))
				$data['slug'] = create_slug_for_update('trainee', $newSlug);

			if($this->input->post('email') != ''){
				if ($this->input->post('email') != $trainer->email) {
					$row = $this->get_row('trainer', array('email' => $this->input->post('email')));
					if($row){
						$res = array('error' => 'This email is already exist.');
						echo json_encode($res);
						exit();
					}
				}
				$data['email'] = $this->input->post('email');
			}

			if($this->input->post('address') != ''){
				$data['address'] = $this->input->post('address');
			}

			if($this->input->post('city') != ''){
				$data['city'] = $this->input->post('city');
			}

			if(count($data) > 0){
				$this->db->where('id', $this->input->post('trainer_id'));
				$data['updated'] = date('Y-m-d H:i:s');
				$this->db->update('trainer', $data);
				$res = array('success' => 'Trainer updated successfully.' , 'trainer_id' => $this->input->post('trainer_id'));
				echo json_encode($res);
				exit();
			}else{
				$res = array('error' => 'Send some data for update trainer !!!');
				echo json_encode($res);
				exit();
			}
		}
		else{
			$res = array('error' => 'Please provide trainer id...!!!');
			echo json_encode($res);
			exit();
		}
	}

	public function delete_trainer()
	{
		$this->db->where('id', $this->input->post('trainer_id'));
		$query = $this->db->get('trainer');
		if($query->num_rows() == 0){
			$res = array('error' => "Invalid Trainer ID");
			echo json_encode($res);
			exit();
		}else{
			$this->db->delete('trainer', array('id' => $this->input->post('trainer_id')));
			$res = array('success' => 'Trainer has been deleted successfully...!!!');
			echo json_encode($res);
			exit();
		}
	}

	public function get_trainers(){
		$this->db->select('id');
		$this->db->from('trainer');
		$query = $this->db->get();
		$total_rows = $query->num_rows();

		if((isset($_POST['limit'])) && (isset($_POST['offset']))){
			$this->db->limit($this->input->post('limit'), $this->input->post('offset'));
		}
		elseif(isset($_POST['limit'])){
			$this->db->limit($this->input->post('limit'));
		}
		elseif(isset($_POST['offset'])){
			$this->db->limit($total_rows, $this->input->post('offset'));
		}

		$this->db->from('trainer');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			$res = array('results' => $query->result_array(), 'total_rows' => $total_rows);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'No trainer found.');
			echo json_encode($res);
			exit();
		}
	}

	public function get_trainer(){
		$row = $this->get_row('trainer', array('id'=>$this->input->post('trainer_id')));
		if($row){
			$res = array('result' => $row);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'Trainer not found.');
			echo json_encode($res);
			exit();
		}
	}

	public function trainer_change_password(){
		$trainer_id = $this->input->post('trainer_id');
		$old = sha1(trim($this->input->post('old')));
		$new = sha1(trim($this->input->post('new')));
		$confirm = sha1(trim($this->input->post('confirm')));
		$data = array('password' => $new );
		if ($new != $confirm) {
			$res = array('error' => 'Confirm password does not match with new password.');
			echo json_encode($res);
			exit();
		}
		$status = $this->get_result('trainer', array('id' => $trainer_id, 'password'=>$old) );
		if(!empty($status))
		{
			$this->update('trainer', $data, array('id' => $trainer_id) );
			$res = array('success' => 'Password has been changed successfully.' );
			echo json_encode($res);
			exit();
		}
		else
		{
			$res = array('error' => 'Invalid trainer id or old password.');
			echo json_encode($res);
			exit();
		}
	}

	public function update_trainer_profile(){
		$trainer_id = $this->input->post('trainer_id');
		
		if($trainer_id != ''){
			
			$trainer = $this->get_row('trainer', array('id' => $trainer_id));

			if(empty($trainer)){
				$res = array('error' => 'Invalid trainer id.');
				echo json_encode($res);
				exit();
			}

			$data = array();
			$newSlug='';

			if($this->input->post('fname') != ''){
				$data['fname'] = $this->input->post('fname');
				$newSlug .= $this->input->post('fname');
			}

			if($this->input->post('lname') != ''){
				$data['lname'] = $this->input->post('lname');
				if (!empty($newSlug)){
					$newSlug .= ' '.$this->input->post('lname');
				}
				else
					$newSlug .= $trainer->lname.' '.$this->input->post('lname');
			}else{
				if (!empty($newSlug)){
					$newSlug .= ' '.$trainer->lname;
				}
			}

			if (!empty($newSlug))
				$data['slug'] = create_slug_for_update('trainee', $newSlug);

			if($this->input->post('email') != ''){
				if ($this->input->post('email') != $trainer->email) {
					$row = $this->get_row('trainer', array('email' => $this->input->post('email')));
					if($row){
						$res = array('error' => 'This email is already exist.');
						echo json_encode($res);
						exit();
					}
				}
				$data['email'] = $this->input->post('email');
			}

			if($this->input->post('address') != ''){
				$data['address'] = $this->input->post('address');
			}

			if($this->input->post('city') != ''){
				$data['city'] = $this->input->post('city');
			}

			if(count($data) > 0){
				$this->db->where('id', $this->input->post('trainer_id'));
				$data['updated'] = date('Y-m-d H:i:s');
				$this->db->update('trainer', $data);
				$res = array('success' => 'Profile updated successfully.' , 'trainer_id' => $this->input->post('trainer_id'));
				echo json_encode($res);
				exit();
			}else{
				$res = array('error' => 'Send some data for update...!!!');
				echo json_encode($res);
				exit();
			}
		}
		else{
			$res = array('error' => 'Please provide trainer id...!!!');
			echo json_encode($res);
			exit();
		}
	}

// Trainer Module End

// Trainee Module Start
	public function get_trainees(){
		$this->db->select('id');
		$this->db->from('trainee');
		
		if(isset($_POST['trainer_id'])){
			$this->db->where('trainer_id', $this->input->post('trainer_id'));
		}

		$query = $this->db->get();
		$total_rows = $query->num_rows();

		if((isset($_POST['limit'])) && (isset($_POST['offset']))){
			$this->db->limit($this->input->post('limit'), $this->input->post('offset'));
		}
		elseif(isset($_POST['limit'])){
			$this->db->limit($this->input->post('limit'));
		}
		elseif(isset($_POST['offset'])){
			$this->db->limit($total_rows, $this->input->post('offset'));
		}

		if(isset($_POST['trainer_id'])){
			$this->db->where('trainer_id', $this->input->post('trainer_id'));
		}

		$this->db->from('trainee');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			$res = array('results' => $query->result_array(), 'total_rows' => $total_rows);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'No trainee found.');
			echo json_encode($res);
			exit();
		}
	}

	public function add_trainee(){
		$fname 		= $this->input->post('fname');
		$lname 		= $this->input->post('lname');
		$email 		= $this->input->post('email');
		$password 	= $this->input->post('password');
		$cpassword 	= $this->input->post('confirm_password');
		$address 	= $this->input->post('address');
		$city 		= $this->input->post('city');
		$trainer_id = $this->input->post('trainer_id');
		
		if(($fname != '') && ($lname != '') && ($email != '') && ($password != '') && ($address != '') && ($city != '') && ($trainer_id != '')){
			
			$row = $this->get_row('trainee', array('email' => $this->input->post('email')));

			if($row){
				$res = array('error' => 'This email is already exist.');
				echo json_encode($res);
				exit();
			}

			if ($password != $cpassword) {
				$res = array('error' => 'Password confirmation failed.');
				echo json_encode($res);
				exit();	
			}

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
			
			$trainee_id = $this->insert('trainee',$data);
			$res = array('success' => 'Trainee added successfully.' , 'trainee_id' => $trainee_id);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'Please check parameters.');
			echo json_encode($res);
			exit();
		}
	}

	public function edit_trainee(){

		$trainee_id = $this->input->post('trainee_id');
		
		if($trainee_id != ''){
			
			$trainer = $this->get_row('trainee', array('id' => $trainee_id));

			if(empty($trainer)){
				$res = array('error' => 'Invalid trainee id.');
				echo json_encode($res);
				exit();
			}

			$newSlug='';

			if($this->input->post('fname') != ''){
				$data['fname'] = $this->input->post('fname');
				$newSlug .= $this->input->post('fname');
			}

			if($this->input->post('lname') != ''){
				$data['lname'] = $this->input->post('lname');
				if (!empty($newSlug)){
					$newSlug .= ' '.$this->input->post('lname');
				}
				else
					$newSlug .= $trainer->lname.' '.$this->input->post('lname');
			}else{
				if (!empty($newSlug)){
					$newSlug .= ' '.$trainer->lname;
				}
			}

			if (!empty($newSlug))
				$data['slug'] = create_slug_for_update('trainee', $newSlug);


			if($this->input->post('email') != ''){
				if ($this->input->post('email') != $trainer->email) {
					$row = $this->get_row('trainee', array('email' => $this->input->post('email')));
					if($row){
						$res = array('error' => 'This email is already exist.');
						echo json_encode($res);
						exit();
					}
				}
				$data['email'] = $this->input->post('email');
			}

			if($this->input->post('address') != ''){
				$data['address'] = $this->input->post('address');
			}

			if($this->input->post('city') != ''){
				$data['city'] = $this->input->post('city');
			}

			if(count($data) > 0){
				$this->db->where('id', $this->input->post('trainee_id'));
				$data['updated'] = date('Y-m-d H:i:s');
				$this->db->update('trainee', $data);
				$res = array('success' => 'Trainee updated successfully.' , 'trainee_id' => $this->input->post('trainee_id'));
				echo json_encode($res);
				exit();
			}else{
				$res = array('error' => 'Send some data for update trainee !!!');
				echo json_encode($res);
				exit();
			}
		}
		else{
			$res = array('error' => 'Please provide trainee id...!!!');
			echo json_encode($res);
			exit();
		}
	}

	public function delete_trainee()
	{
		$this->db->where('id', $this->input->post('trainee_id'));
		$query = $this->db->get('trainee');
		if($query->num_rows() == 0){
			$res = array('error' => "Invalid Trainee ID");
			echo json_encode($res);
			exit();
		}else{
			$this->db->delete('trainee', array('id' => $this->input->post('trainee_id')));
			$res = array('success' => 'Trainee has been deleted successfully...!!!');
			echo json_encode($res);
			exit();
		}
	}

	public function get_trainee(){
		$row = $this->get_row('trainee', array('id'=>$this->input->post('trainee_id')));
		if($row){
			$res = array('result' => $row);
			echo json_encode($res);
			exit();
		}
		else{
			$res = array('error' => 'Trainee not found.');
			echo json_encode($res);
			exit();
		}
	}

	public function trainee_change_password(){
		$trainee_id = $this->input->post('trainee_id');
		$old = sha1(trim($this->input->post('old')));
		$new = sha1(trim($this->input->post('new')));
		$confirm = sha1(trim($this->input->post('confirm')));
		$data = array('password' => $new );
		if ($new != $confirm) {
			$res = array('error' => 'Confirm password does not match with new password.');
			echo json_encode($res);
			exit();
		}
		$status = $this->get_result('trainee', array('id' => $trainee_id, 'password'=>$old) );
		if(!empty($status)) {
			$this->update('trainee', $data, array('id' => $trainee_id) );
			$res = array('success' => 'Password has been changed successfully.' );
			echo json_encode($res);
			exit();
		}
		else
		{
			$res = array('error' => 'Invalid trainee id or old password.');
			echo json_encode($res);
			exit();
		}
	}

	public function update_trainee_profile(){
		$trainee_id = $this->input->post('trainee_id');
		
		if($trainee_id != ''){
			
			$trainer = $this->get_row('trainee', array('id' => $trainee_id));

			if(empty($trainer)){
				$res = array('error' => 'Invalid trainee id.');
				echo json_encode($res);
				exit();
			}

			$image = $this->input->post('image');
			if($image !=""){
				// $uimage = createImage($image);
				$nimage = createImageOnAws($image);
				if($nimage){
					$uimage = $nimage;
				}
				else{
					$uimage="";
				}
			}else{
				$uimage="";
			}

			$newSlug='';

			if($this->input->post('fname') != ''){
				$data['fname'] = $this->input->post('fname');
				$newSlug .= $this->input->post('fname');
			}

			if($this->input->post('lname') != ''){
				$data['lname'] = $this->input->post('lname');
				if (!empty($newSlug)){
					$newSlug .= ' '.$this->input->post('lname');
				}
				else
					$newSlug .= $trainer->lname.' '.$this->input->post('lname');
			}else{
				if (!empty($newSlug)){
					$newSlug .= ' '.$trainer->lname;
				}
			}

			if (!empty($newSlug))
				$data['slug'] = create_slug_for_update('trainee', $newSlug);

			if($this->input->post('email') != ''){
				if ($this->input->post('email') != $trainer->email) {
					$row = $this->get_row('trainee', array('email' => $this->input->post('email')));
					if($row){
						$res = array('error' => 'This email is already exist.');
						echo json_encode($res);
						exit();
					}
				}
				$data['email'] = $this->input->post('email');
			}

			if($this->input->post('currentweight') != ''){
				$data['currentweight'] = $this->input->post('currentweight');
			}
			if($this->input->post('height') != ''){
				$data['height'] = $this->input->post('height');
			}
			if($this->input->post('goalweight') != ''){
				$data['goalweight'] = $this->input->post('goalweight');
			}

			if($this->input->post('address') != ''){
				$data['address'] = $this->input->post('address');
			}

			if($this->input->post('city') != ''){
				$data['city'] = $this->input->post('city');
			}

			if($image !=""){
				$data['image']=$uimage;
			}

			if(count($data) > 0){
				$this->db->where('id', $this->input->post('trainee_id'));
				$data['updated'] = date('Y-m-d H:i:s');
				$data['lastupdated'] = time();
				$this->db->update('trainee', $data);
				$res = array('success' => 'Profile updated successfully.' , 'trainee_id' => $this->input->post('trainee_id'));
				echo json_encode($res);
				exit();
			}else{
				$res = array('error' => 'Send some data for update...!!!');
				echo json_encode($res);
				exit();
			}
		}
		else{
			$res = array('error' => 'Please provide trainee id...!!!');
			echo json_encode($res);
			exit();
		}
	}
	// Trainee Module End

	 //Work Out Module Start
	 public function get_workout_dates(){
	 	$this->load->model('admin_model');

	 	$trainee_id = $this->input->post('trainee_id');

	 	$yearr = $this->input->post('year');
		$monthh = $this->input->post('month');

		if($monthh <= 9){
			$monthh = '0'.$monthh;
		}

	 	$year = date("Y");
		$week = date("W");
		$firstDayOfWeek = strtotime($year."W".str_pad($week,2,"0",STR_PAD_LEFT));
		$week_f_date = date("j",$firstDayOfWeek) - 1;
		$n = date('n') - 1 ;
		$week_f_date = date("Y/".$n."/".$week_f_date);
		$current_date = date("Y_".$n."_j");

	 	//$trainee_id = 1;
	 	$this->db->select('tw.*, w.name, w.date, w.status');
	 	$this->db->from('trainee_workout as tw');
	 	$this->db->join('workout as w', 'w.id = tw.workout_id', 'left');
	 	$this->db->where('tw.trainee_id', $trainee_id);
	 	$this->db->order_by('w.date', 'asc');
	 	// $this->db->like('date', date('Y-m-'), 'after');
	 	$this->db->like('date', $yearr.'-'.$monthh.'-', 'after');
	 	$query = $this->db->get();
	 	
	 	if($query->num_rows() > 0){
	 		$arr = array();
	 		$i = 0;
	 		foreach ($query->result() as $row) {
	 			$arr[$i]['name'] = $row->name;
	 			$arr[$i]['trainee_id'] = $row->trainee_id;
	 			$arr[$i]['workout_id'] = $row->workout_id;
	 			
	 			// $arr[$i]['workout_status'] = $row->status;
				// if($row->status != null )
	 		// 		$arr[$i]['workout_status'] = 1;
	 		// 	else
	 		// 		$arr[$i]['workout_status'] = 0;

	 			$rrr = $this->admin_model->get_row('workout_notes', array('trainee_id' => $trainee_id, 'workout_id' => $row->workout_id));

	 			if($rrr){
	 				$arr[$i]['workout_status'] = '1';
	 			}
	 			else{
	 				$arr[$i]['workout_status'] = '0';
	 			}

	 			
	 			$arr[$i]['date'] = date('l, F d',strtotime($row->date));
	 			
	 			$n = date('n',strtotime($row->date)) - 1 ;

	 			if($arr[$i]['workout_status'] == 1){
	 				$arr[$i]['div_class'] = date('Y_'.$n.'_j',strtotime($row->date))." calgrey";	 				
	 			}else{	 				
	 				$arr[$i]['div_class'] = date('Y_'.$n.'_j',strtotime($row->date));
	 			}

	 			// $arr[$i]['div_class'] = date('Y_'.$n.'_j',strtotime($row->date));
	 			$arr[$i]['week_f_date'] = $week_f_date;
				$arr[$i]['current_date'] = $current_date;
	 			
	 			//echo "<pre>";
	 			//print_r($arr);
	 			//die();
	 			$i++;
	 		}

	 		$res = array('results' => $arr);
	 		echo json_encode($res);
	 		exit();
	 	}
	 	else{
	 		$res = array('error' => 'No workout found.');
	 		echo json_encode($res);
	 		exit();
	 	}
	 }


	 public function get_workout(){
	 	$id = $this->input->post('trainee_id');
	 	if($id == ""){
	 		$res = array('error' => 'Please check parameters');
 			echo json_encode($res);
 			exit();
	 	}
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
	 				if(date('H:i:s') <= $key->time){
	 					$workout = $key; 	 				
	 					break;
	 				}
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
		 		$data['status'] = $workout->status;
		 		$data['description'] = $workout->description;
		 		$data['image'] = $workout->image;
		 		$exercise = $this->get_result('exercise', array('workout_id' => $workout->id));
		 		$data['count_of_exercise'] = count($exercise);

		 		foreach($exercise as $row){
		 			$arr = array();
		 			$arr['exercise'] = $row->name;
		 			$arr['exercise_id'] = $row->id;
		 			$arr['status'] = $row->status;
		 			$this->db->select('value');
		 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
		 			$arr['sets'] = $set;
		 			$data['exercises'][] = $arr;
		 		}

		 		//echo "<pre>";
		 		//echo date('H:i:s');
		 		//print_r($data);		 		
		 		$res = array('success' => $data);
	 			echo json_encode($res);
	 			exit();

	 		}else{
	 			$res = array('error' => 'No Workout Found');
	 			echo json_encode($res);
	 			exit();	
	 		}

 		}else{
	 		$res = array('error' => 'No Workout Found');
	 		echo json_encode($res);
	 		exit();	
	 	}
	 }

	 public function  getWorkout_byid(){
	 	$trainee_id = $this->input->post('trainee_id');
	 	$id = $this->input->post('id');
	 	$workout = $this->get_row('workout', array('id' => $id));
	 		if(!empty($workout)){
	 			$data = array();
		 		$data['date'] = date("M d,Y", strtotime($workout->date));
		 		$data['time'] = date("h:i", strtotime($workout->time));
		 		$data['trainee_id'] = $workout->trainee_id;
		 		$data['workout_id'] = $workout->id;
		 		$data['name'] = $workout->name;
		 		$data['status'] = $workout->status;
		 		$data['description'] = $workout->description;
		 		$data['image'] = $workout->image;
		 		$exercise = $this->get_result('exercise', array('workout_id' => $workout->id));
		 		$data['count_of_exercise'] = count($exercise);

		 		foreach($exercise as $row){
		 			$arr = array();
		 			$arr['exercise'] = $row->name;
		 			$arr['exercise_id'] = $row->id;
		 			
		 			// $arr['status'] = $row->status;
		 			$rrr = $this->get_row('exercise_notes', array('trainee_id' => $trainee_id, 'exercise_id' => $row->id));
		 			if($rrr){
		 				$arr['status'] = '1';
		 			}
		 			else{
		 				$arr['status'] = '0';
		 			}

		 			$this->db->select('value');
		 			$set = $this->get_result('exercise_set', array('exercise_id' => $row->id));
		 			$arr['sets'] = $set;
		 			$data['exercises'][] = $arr;
		 		}

		 		//echo "<pre>";
		 		//echo date('H:i:s');
		 		//print_r($data);		 		
		 		$res = array('success' => $data);
	 			echo json_encode($res);
	 			exit();

	 		}else{
	 			$res = array('error' => 'No Workout Found');
	 			echo json_encode($res);
	 			exit();	
	 		} 		
	 }

	 public function get_exercisedetails(){
	 	//$id = 42;
	 	$id = $this->input->post('id');
	 	$trainee_id = $this->input->post('trainee_id');

	 	if($id == ""){
	 		$res = array('error' => 'Please check parameters');
 			echo json_encode($res);
 			exit();
	 	}

	 	//$id = 1;

 		$exe = $this->get_row('exercise', array('id'=>$id));
 		if($exe){
 			$data = array();
 			
 			$ww = $this->get_row('workout', array('id' => $exe->workout_id));
 			if($ww){
 				$wdate = strtotime($ww->date);
 			}
 			else{
 				$wdate = strtotime("+1 day");
 			}

 			$curdate = strtotime( date('Y-m-d') );

 			if($curdate >= $wdate){
 				$data['notes_show'] = '1';
 			}
 			else{
 				$data['notes_show'] = '0';
 			}


 			$this->db->order_by('id', 'asc');
 			$res = $this->get_result('exercise_set', array('exercise_id'=>$id));
 			if($res){ 			
	 			$data['exercise'] = $exe->name;
	 			$data['workout_id'] = $exe->workout_id;
	 			$data['description'] = $exe->description;
	 			$data['resttime'] = $exe->resttime;
	 			$data['image'] = $exe->image;

	 			$rrr = $this->get_row('exercise_notes', array('exercise_id' => $id, 'trainee_id' => $trainee_id));
	 			if($rrr){
	 				$data['notes'] = $rrr->notes;
	 			}
	 			else{
	 				$data['notes'] = '';
	 			}

	 			$i = 0;
	 			foreach ($res as $row) {
	 				$data['set'][$i]['id'] = $row->id; 
	 				$data['set'][$i]['value'] = $row->value; 
	 				$data['set'][$i]['reps'] = $row->reps;

	 				$rrr = $this->get_row('exercise_set_results', array('exercise_set_id' => $row->id, 'trainee_id' => $trainee_id));
	 				if($rrr){
	 					$data['set'][$i]['resultreps'] = $rrr->resultreps;
	 					$data['set'][$i]['resultweight'] = $rrr->resultweight;
	 				}
	 				else{
	 					$data['set'][$i]['resultreps'] = '0';
	 					$data['set'][$i]['resultweight'] = '0';
	 				}

	 				$i++;
	 			}

	 			//print_r($data); die();
	 			$res = array('success' => $data);
	 			echo json_encode($res);
	 			exit(); 		

 			}else{
	 			$res = array('error' => 'Not Found');
	 			echo json_encode($res);
	 			exit();
	 		}
 			
 		}else{
 			$res = array('error' => 'Not Found');
 			echo json_encode($res);
 			exit();
 		}

		/*	
	 	$this->db->select('e.*, es.id as set_id, es.value');
 		$this->db->from('exercise as e'); 		
 		$this->db->where('e.id', $id);
 		$this->db->join('exercise_set as es', 'es.exercise_id = e.id','left'); 		
 		$query = $this->db->get();
 		if($query->num_rows() > 0){
 			$res = array('success' => $query->result_array());
 			echo json_encode($res);
 			exit();
 		}else{
 			$res = array('error' => 'Please check parameters');
 			echo json_encode($res);
 			exit();
 		}

 		 */

	 }

	// public function add_workout(){
	// 	$trainer_id = $this->input->post('trainer_id');
	// 	$trainee_id = $this->input->post('trainee_id');
	// 	$date 		= $this->input->post('date');
	// 	$time 		= $this->input->post('time');
	// 	$workoutname = $this->input->post('workoutname');
	// 	$description = $this->input->post('description');
	// 	$cpassword 	= $this->input->post('password');
	// 	$address 	= $this->input->post('address');
	// 	$city 		= $this->input->post('city');
		
	// 	if(($fname != '') && ($lname != '') && ($email != '') && ($password != '') && ($address != '') && ($city != '')){
			
	// 		$row = $this->get_row('trainer', array('email' => $this->input->post('email')));

	// 		if($row){
	// 			$res = array('error' => 'This email is already exist.');
	// 			echo json_encode($res);
	// 			exit();
	// 		}
	// 		if ($password != $cpassword) {
	// 			$res = array('error' => 'Password confirmation failed.');
	// 			echo json_encode($res);
	// 			exit();	
	// 		}

	// 		$data=array(
	// 			'slug'=>create_slug('trainer', $this->input->post('fname').' '.$this->input->post('lname')),
	// 			'fname'=>$this->input->post('fname'),
	// 			'lname'=>$this->input->post('lname'),
	// 			'email'=>$this->input->post('email'),
	// 			'password'=>sha1($this->input->post('password')),	
	// 			'address'=>$this->input->post('address'),	
	// 			'city'=>$this->input->post('city'),
	// 			'created' => date('Y-m-d H:i:s'),			
	// 			'updated' => date('Y-m-d H:i:s')			
	// 		);	
			
	// 		$trainer_id = $this->insert('trainer',$data);
	// 		$res = array('success' => 'Trainer added successfully.' , 'trainer_id' => $trainer_id);
	// 		echo json_encode($res);
	// 		exit();
	// 	}
	// 	else{
	// 		$res = array('error' => 'Please check parameters.');
	// 		echo json_encode($res);
	// 		exit();
	// 	}
	// }

	// Work Out Module End

	public function forgetpassword(){		
		$email = $this->input->post('email');
		$row = $this->get_row('trainee', array('email'=> $email));

		if($row){
			$newPassword = createRandomPassword(12);                
	    	$changePasswordKey = sha1(uniqid());
	    	$obj = array(
	    		// 'forgot_pwd'		=> sha1($newPassword),
				'forgot_pwd_key'	=> $changePasswordKey
	    	);
	    	$this->db->where('id', $row->id);
	    	$this->db->update('trainee', $obj);
			$subject = 'SportsApp : Forget Password';
			$to = array(
				$row->email
			);
			$data['name'] = $row->fname.' '.$row->lname;
			$data['new_password'] = $newPassword;
			$data['key'] = $changePasswordKey;			
			$html = $this->load->view('email/forget_traineepassword', $data, TRUE);
			$this->send_email($subject, $to, $html);
			
			$res = array('success' => 'A reset password link has been sent to your registered email-id.');
	 			echo json_encode($res);
	 			exit(); 		

 		}else{
	 		$res = array('error' => 'Email does not exists!');
	 		echo json_encode($res);
	 		exit();
	 	}
 			
		
	}


	public function send_email($subject, $to, $html, $from = array('no-reply@sportsApp.com' =>'SportsApp')){
		$this->load->library('smtp_lib/smtp_email');
		$this->smtp_email->sendEmail($from, $to, $subject, $html);
		return TRUE;
	}

	// public function update_exercise(){
	// 	$id = $_POST['id'];
	// 	if($id !=""){
	// 		$this->db->where('id', $id);
	// 		$this->db->update('exercise', array('status'=> 1));
	// 	}
	// }	

	public function update_exercise(){
		$id = $_POST['id'];
		$notes = $this->input->post('note');
		if($id !=""){
			$this->db->where('id', $id);
			$this->db->update('exercise', array('status'=> 1,'notes'=>$notes));
		}
	}

	public function db_update_exercise(){
		$id = $_POST['id'];
		$notes = $this->input->post('note');
		$lastupdated = $this->input->post('lastupdated');
		$token = $this->input->post('token');
		$trainee_id = $this->input->post('trainee_id');
		if($id !=""){
			$this->db->where('id', $id);
			$this->db->update('exercise', array('status'=> 1,'notes'=>$notes, 'lastupdated'=>$lastupdated));
			
			$this->db->where('exercise_id', $id);
			$this->db->where('trainee_id', $trainee_id);
			$query = $this->db->get('exercise_notes');
			if($query->num_rows() > 0){
				$rr = $query->row();
				$udata = array(
					'notes'=>$notes,
					'lastupdated'=>$lastupdated
				);
				$this->db->update('exercise_notes', $udata, array('id' => $rr->id));
			}
			else{
				$udata = array(
					'token'=>$token,
					'lastupdated'=>$lastupdated,
					'exercise_id'=> $id,
					'notes'=>$notes,
					'trainee_id'=>$trainee_id
				);
				$this->db->insert('exercise_notes', $udata);
			}

		}
	}

	public function update_exercise_set(){
		$postarr = $_POST['updatearr'];
		if(!empty($postarr)){
			foreach ($postarr as $row){				
				$udata = array(
					'resultweight'=>$row['weight'],
					'resultreps'=>$row['reps'],
					'set_status'=>1
					);
				$this->db->where('id', $row['id']);
				$this->db->update('exercise_set', $udata);
			}
		}
	}

	public function db_update_exercise_set(){
		$postarr = $_POST['updatearr'];
		$lastupdated = $_POST['lastupdated'];
		$trainee_id = $_POST['trainee_id'];
		if(!empty($postarr)){
			foreach ($postarr as $row){				
				$udata = array(
					'resultweight'=>$row['weight'],
					'resultreps'=>$row['reps'],
					'set_status'=>1,
					'lastupdated'=>$lastupdated
				);
				$this->db->where('id', $row['id']);
				$this->db->update('exercise_set', $udata);

				$this->db->where('exercise_set_id', $row['id']);
				$this->db->where('trainee_id', $trainee_id);
				$query = $this->db->get('exercise_set_results');
				if($query->num_rows() > 0){
					$rr = $query->row();
					$udata = array(
						'resultweight'=>$row['weight'],
						'resultreps'=>$row['reps'],
						'resulttime'=>$row['time'],
						'lastupdated'=>$lastupdated
					);
					$this->db->update('exercise_set_results', $udata, array('id' => $rr->id));
				}
				else{
					$udata = array(
						'token'=>$row['token'],
						'resultweight'=>$row['weight'],
						'resultreps'=>$row['reps'],
						'resulttime'=>$row['time'],
						'lastupdated'=>$lastupdated,
						'exercise_set_id'=>$row['id'],
						'trainee_id'=>$trainee_id
					);
					$this->db->insert('exercise_set_results', $udata);
				}
			}
		}
	}

	public function update_workoutnote(){
		$id = $this->input->post('id');
		$notes = $this->input->post('note');
		if($id !="" && $notes !=""){			
			$this->db->where('id', $id);
			$this->db->update('workout', array('notes'=>$notes));
		}
	}

	public function db_update_workoutnote(){
		$id = $this->input->post('id');
		$notes = $this->input->post('note');
		$lastupdated = $this->input->post('lastupdated');
		$token = $this->input->post('token');
		$trainee_id = $this->input->post('trainee_id');
		if($id !="" && $notes !=""){			
			$this->db->where('id', $id);
			$this->db->update('workout', array('notes'=>$notes , 'lastupdated' => $lastupdated));

			$this->db->where('workout_id', $id);
			$this->db->where('trainee_id', $trainee_id);
			$query = $this->db->get('workout_notes');
			if($query->num_rows() > 0){
				$rr = $query->row();
				$udata = array(
					'notes'=>$notes,
					'lastupdated'=>$lastupdated
				);
				$this->db->update('workout_notes', $udata, array('id' => $rr->id));
			}
			else{
				$udata = array(
					'token'=>$token,
					'lastupdated'=>$lastupdated,
					'workout_id'=> $id,
					'notes'=>$notes,
					'trainee_id'=>$trainee_id
				);
				$this->db->insert('workout_notes', $udata);
			}
			
		}
	}

	public function update_timetaken(){
		$timetaken = $this->input->post('timetake');
		$eid = $this->input->post('eid');
		$this->db->where('id', $eid);
		$this->db->update('exercise', array('timetaken'=>$timetaken));
	}

	public function db_update_timetaken(){
		$timetaken = $this->input->post('timetake');
		$eid = $this->input->post('eid');
		$lastupdated = $this->input->post('lastupdated');
		$this->db->where('id', $eid);
		$this->db->update('exercise', array('timetaken'=>$timetaken, 'lastupdated'=>$lastupdated));
	}

	public function get_queries(){
		$trainee_id = $this->input->post('trainee_id');

		if($trainee_id==""){
			$res = array('error' => 'Invalid Parameters.');
	 		echo json_encode($res);
	 		exit();
		}

		$this->db->order_by('id', 'desc'); 
		$this->db->where('by', 2);
		$this->db->where('status', 1);
		$this->db->where('trainee_id', $trainee_id);
		$query = $this->db->get('support');
		if($query->num_rows() > 0){
			$res = array('success' => array('result'=>$query->result_array()));
	 		echo json_encode($res);
	 		exit();
		}else{
			$res = array('error' => 'No Query Found');
	 		echo json_encode($res);
	 		exit();
		}
	}


	public function get_query(){

       //here token2 comes for fetch the support.
		$token2 = $this->input->post('token2');

		if($token2==""){
			$res = array('error' => 'Invalid Parameters.');
	 		echo json_encode($res);
	 		exit();
		}

		$this->db->where('token2', $token2);
		$query = $this->db->get('support');
		if($query->num_rows() > 0)
			$support = $query->row();
		else
			$support = "";

		$this->db->select('c.*, t.fname');
 		$this->db->from('conversation as c');
 		$this->db->where('c.support_token2', $support->token2);
 		$this->db->order_by('c.id', 'asc'); 		
 		$this->db->join('trainer as t', 'c.sender_id = t.id', 'left');
 		$query0 = $this->db->get();

 		if($query0->num_rows()>0){ 			
		  $conversation = $query0->result_array();
		}else{
			$conversation="";	
		}

		$res = array('success' => array('support'=>$support,'conversation'=>$conversation));
	 	echo json_encode($res);
	 	exit();		
	}

	public function addquery(){
		$sub= $this->input->post('sub');
		$msg= $this->input->post('msg');
		$trainee_id = $this->input->post('trainee_id');
		$this->db->where('id', $trainee_id);
		$query0 = $this->db->get('trainee');
		if($query0->num_rows() > 0){
			$traineeinfo = $query0->row();
			$token2 = get_token();
			$data=array(
				'subject'=>$sub,
				'message'=>$msg,
				'trainee_id'=>$traineeinfo->id,		
				'trainer_id'=>$traineeinfo->trainer_id,		
				'trainee_name'=>$traineeinfo->fname.' '.$traineeinfo->lname,		
				'trainee_email'=>$traineeinfo->email,
				'token2'=>$token2,				
				'by'=>2,				
				'status'=>1,				
				'is_read'=>0,
				'lastupdated'=>time(),			
				'created' => date('Y-m-d H:i:s')			
			);		
				
			$id = $this->insert('support',$data);		
			$token = time();
			$token = $token.$id;
			$this->update('support', array('token'=>$token), array('id'=>$id));
			$res = array('success' => 'Added successfully.');
		 	echo json_encode($res);
		 	exit();
		}else{
			$res = array('error' => 'Something Went Wrong.');
		 	echo json_encode($res);
		 	exit();
		}		
	}

	public function db_addquery(){
		$data=array(
			'subject'=>$this->input->post('sub'),
			'message'=>$this->input->post('msg'),
			'trainee_id'=>$this->input->post('trainee_id'),		
			'trainer_id'=>$this->input->post('trainer_id'),		
			'trainee_name'=>$this->input->post('trainee_name'),		
			'trainee_email'=>$this->input->post('trainee_email'),
			'token2'=>$this->input->post('token2'),	
			'by'=>2,	
			'status'=>1,				
			'is_read'=>0,
			'lastupdated'=>$this->input->post('lastupdated'),			
			'created' =>$this->input->post('created')			
		);		
			
		$id = $this->insert('support',$data);		
		$token = time();
		$token = $token.$id;
		$this->update('support', array('token'=>$token), array('id'=>$id));
	}

	public function update_setntime(){
		$weight = $this->input->post('weight');		
		$reps = $this->input->post('reps');		
		$id = $this->input->post('setid');		
		$timetake = $this->input->post('timetake');		

		$data = array(
			'resultreps'=>$reps,
			'resultweight'=>$weight,
			'timetaken'=>$timetake,
			'set_status'=>1
			);

		$this->db->where('id', $id);
		$this->db->update('exercise_set', $data);
	}

	public function db_update_setntime(){
		$weight = $this->input->post('weight');		
		$reps = $this->input->post('reps');		
		$id = $this->input->post('setid');		
		$timetake = $this->input->post('timetake');		
		$lastupdated = $this->input->post('lastupdated');		

		$data = array(
			'resultreps'=>$reps,
			'resultweight'=>$weight,
			'timetaken'=>$timetake,
			'set_status'=>1,
			'lastupdated' => $lastupdated
		);

		$this->db->where('id', $id);
		$this->db->update('exercise_set', $data);
	}

	public function trainee_reply(){
		$reply = $this->input->post('reply'); 

       //here token2 comes for fetch the support.
		$support_token2 = $this->input->post('token2'); 
		$trainee_id = $this->input->post('trainee_id'); 

		if( $reply == '' || $reply === FALSE || $support_token2 == '' || $support_token2 === FALSE || $trainee_id == '' || $trainee_id === FALSE ){
			$res = array('error' => 'Check parameters.');
		 	echo json_encode($res);
		 	exit();
		}

		$update=array(
			'message'=>$reply,				
			'support_token2'=>$support_token2,				
			'sender_id'=>$trainee_id,	
			'send_by'=>3,	
			'recieve_by'=>2,
			'token'=>get_token(),
			'lastupdated'=>time(),
			'created' => date('Y-m-d H:i:s')		
		);			
			
		$this->insert('conversation',$update);
		
		$this->db->where('token2', $support_token2);
		$query = $this->db->get('support');
		if($query->num_rows() > 0)
			$support = $query->row();
		else
			$support = "";

		$this->db->select('c.*, t.fname');
 		$this->db->from('conversation as c');
 		$this->db->where('c.support_token2',$support_token2);
 		$this->db->order_by('c.id', 'asc'); 		
 		$this->db->join('trainer as t', 'c.sender_id = t.id', 'left');
 		$query0 = $this->db->get();
 		if($query0->num_rows()>0){ 			
		  $conversation = $query0->result_array();
		}else{
			$conversation="";	
		}

		$res = array('success' => array('support'=>$support,'conversation'=>$conversation));
	 	echo json_encode($res);
	 	exit();	
	}

	public function db_trainee_reply(){
		$data=array(
			'message' => $this->input->post('message'),
			'support_token2' => $this->input->post('support_token2'),
			'sender_id' => $this->input->post('sender_id'),
			'send_by' => $this->input->post('send_by'),
			'recieve_by' => $this->input->post('recieve_by'),
			'created' => $this->input->post('created'),
			'token' => $this->input->post('token'),
			'lastupdated' => $this->input->post('lastupdated')			
		);	
			
		$this->insert('conversation',$data);
	}

	public function update_workoutstatus(){		
		$workoutid = $this->input->post('id');				
		if($workoutid != ""){						
			$exercise = $this->get_result('exercise', array('workout_id'=>$workoutid));			
			$flag = 1;
			
			if($exercise){
				foreach ($exercise as $row){				
					if($row->status == 0){
						$flag= 0;
						break;
					}
				}
			}

			if($flag == 1)
			$this->update('workout',array('status'=>1),array('id'=>$workoutid));
			// echo $flag;			
		}
	}

	public function db_update_workoutstatus(){		
		$workoutid = $this->input->post('id');				
		if($workoutid != ""){						
			$exercise = $this->get_result('exercise', array('workout_id'=>$workoutid));			
			$flag = 1;
			
			if($exercise){
				foreach ($exercise as $row){				
					if($row->status == 0){
						$flag= 0;
						break;
					}
				}
			}

			if($flag == 1){
				$this->update('workout',array('status'=>1, 'lastupdated' => $this->input->post('lastupdated')),array('id'=>$workoutid));
			}
		}
	}

	// public function insertquery(){
	// 	$this->db->query("ALTER TABLE  `trainee` ADD  `image` VARCHAR( 255 ) NOT NULL AFTER  `lname` ;");
	// 	echo "string";
	// }

	

	public function sync_conversation(){
		$this->load->model('admin_model');
		$conversation = $this->input->post('conversation');
		$trainee_id = $this->input->post('trainee_id');
		$trainer_id = $this->input->post('trainer_id');
		if($conversation === FALSE || $trainee_id === FALSE || $trainee_id == '' || $trainer_id === FALSE || $trainer_id == '' || $trainee_id == '0'){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($conversation != ''){
	    		$arr = explode('__----__', $conversation);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[10]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' => $r[0],
	    				'support_id' => $r[1],
	    				'support_token2' => $r[2],
	    				'message' => $r[3],
	    				'sender_id' => $r[4],
	    				'reciver_id' => $r[5],
	    				'send_by' => $r[6],
	    				'recieve_by' => $r[7],
	    				'created' => $r[8],
	    				'token' => $r[9],
	    				'lastupdated' => $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('conversation', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'support_id' 		=> $row->support_id,
	    					'support_token2'	=> $row->support_token2,
	    					'message' 			=> $row->message,
	    					'sender_id' 		=> $row->sender_id,
	    					'reciver_id' 		=> $row->reciver_id,
	    					'send_by' 			=> $row->send_by,
	    					'recieve_by' 		=> $row->recieve_by,
	    					'created' 			=> $row->created,
	    					'token' 			=> $row->token,
	    					'lastupdated' 		=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('conversation', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'support_id' 		=> $row->support_id,
								'support_token2' 	=> $row->support_token2,
		    					'message' 			=> $row->message,
		    					'sender_id' 		=> $row->sender_id,
		    					'reciver_id' 		=> $row->reciver_id,
		    					'send_by' 			=> $row->send_by,
		    					'recieve_by' 		=> $row->recieve_by,
		    					'created' 			=> $row->created,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('conversation', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		$this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');

    		$result = $this->admin_model->get_result('conversation');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_groups(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[7]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' => $r[0],
	    				'name' => $r[1],
	    				'slug' => $r[2],
	    				'trainer_id' => $r[3],
	    				'created' => $r[4],
	    				'updated' => $r[5],
	    				'token' => $r[6],
	    				'lastupdated' => $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('group', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'name' 			=> $row->name,
	    					'slug' 			=> $row->slug,
	    					'trainer_id' 	=> $row->trainer_id,
	    					'created' 		=> $row->created,
	    					'updated' 		=> $row->updated,
	    					'token' 		=> $row->token,
	    					'lastupdated' 	=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('group', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'name' 			=> $row->name,
		    					'slug' 			=> $row->slug,
		    					'trainer_id' 	=> $row->trainer_id,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('group', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		// $this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');
    		$this->db->where('trainer_id', $trainer_id);

    		$result = $this->admin_model->get_result('group');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_workout(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == '' || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();
			$delete_ids = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[13]);
	    			$lastupdated = $lastupdated[0];

	    			$anytime = explode('.', $r[14]);
	    			$anytime = $anytime[0];

	    			$type = explode('.', $r[15]);
	    			$type = $type[0];
	    			

	    			$row = array(
	    				'id' 					=> $r[0],
	    				'notes' 				=> $r[1],
	    				'trainer_id' 			=> $r[2],
	    				'trainee_id' 			=> $r[3],
	    				'date' 					=> $r[4],
	    				'time' 					=> $r[5],
	    				'name' 					=> $r[6],
	    				'description' 			=> $r[7],
	    				'image' 				=> $r[8],
	    				'status' 				=> $r[9],
	    				'created' 				=> $r[10],
	    				'updated' 				=> $r[11],
	    				'token' 				=> $r[12],
	    				'lastupdated' 			=> $lastupdated,
	    				'anytime'				=> $anytime,
	    				'type'					=> $type,
	    				'circuit_name'			=> $r[16],
	    				'circuit_description'	=> $r[17],
	    				'circuit_image'			=> $r[18]
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('workout', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'notes' 		=> $row->notes,
	    				// 	'trainer_id' 	=> $row->trainer_id,
	    				// 	'trainee_id' 	=> $row->trainee_id,
	    				// 	'date' 			=> $row->date,
	    				// 	'time' 			=> $row->time,
	    				// 	'name' 			=> $row->name,
	    				// 	'description' 	=> $row->description,
	    				// 	'image' 		=> $row->image,
	    				// 	'status' 		=> $row->status,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('workout', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'notes' 				=> $row->notes,
		    					'trainer_id' 			=> $row->trainer_id,
		    					'trainee_id' 			=> $row->trainee_id,
		    					'date' 					=> $row->date,
		    					'time' 					=> $row->time,
		    					'name' 					=> $row->name,
		    					'description' 			=> $row->description,
		    					'image' 				=> $row->image,
		    					'status' 				=> $row->status,
		    					'created' 				=> $row->created,
		    					'updated' 				=> $row->updated,
		    					'lastupdated' 			=> $row->lastupdated,
		    					'type' 					=> $row->type,
		    					'circuit_name' 			=> $row->circuit_name,
		    					'circuit_description' 	=> $row->circuit_description,
		    					'circuit_image' 		=> $row->circuit_image,
		    					'anytime'				=> $row->anytime
		    				);
		    				$this->admin_model->update('workout', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		$trainee_workout = $this->admin_model->get_result('trainee_workout', array('trainee_id' => $trainee_id));

    		if($trainee_workout){
    			$workout_id = array();
    			foreach($trainee_workout as $row)
    				$workout_id[] = $row->workout_id;

    			$this->db->where_in('id', $workout_id);
    		}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		// $this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');
    		$this->db->where('trainer_id', $trainer_id);

    		// $this->db->like('date', '-'.date('m').'-');
    		$fq = date("Y-m-%", time());
    		$sq = date("Y-m-%", strtotime("+1 month"));
    		$tq = date("Y-m-%", strtotime("-1 month"));
    		$sql_where = "((date LIKE '".$fq."' ) OR ( date LIKE '".$sq."' ) OR ( date LIKE '".$tq."' ) ) ";
    		$this->db->where($sql_where);

    		$result = $this->admin_model->get_result('workout');

    		if($result){
	    		$response = array();
	    		foreach($result as $row){
	    			$row = (array) $row;
	    			$path = './assets/uploads/workout/';
	    			$image = $row['image'];
	    			if($image != '' && $image != '0')
	    				$row['base64image'] = convertImageToBase64($path, $image);
	    			else
	    				$row['base64image'] = '0';
	    			$response[] = $row;
	    		}
	    	}else{
	    		$response = FALSE;
	    	}

	    	if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $response,
    				'delete_ids' => $delete_ids
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_exercise(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();
			$delete_ids = array();
			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			
	    			$lastupdated = explode('.', $r[12]);
	    			$lastupdated = $lastupdated[0];

	    			$circuit_id = explode('.', $r[13]);
	    			$circuit_id = $circuit_id[0];
	    			
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'notes' 		=> $r[1],
	    				'workout_id' 	=> $r[2],
	    				'name' 			=> $r[3],
	    				'description' 	=> $r[4],
	    				'timetaken' 	=> $r[5],
	    				'resttime' 		=> $r[6],
	    				'image' 		=> $r[7],
	    				'status' 		=> $r[8],
	    				'created' 		=> $r[9],
	    				'updated' 		=> $r[10],
	    				'token' 		=> $r[11],
	    				'circuit_id'	=> $circuit_id,
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('exercise', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'notes' 		=> $row->notes,
	    				// 	'workout_id' 	=> $row->workout_id,
	    				// 	'name' 			=> $row->name,
	    				// 	'description' 	=> $row->description,
	    				// 	'timetaken' 	=> $row->timetaken,
	    				// 	'resttime' 		=> $row->resttime,
	    				// 	'image' 		=> $row->image,
	    				// 	'status' 		=> $row->status,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('exercise', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'notes' 		=> $row->notes,
		    					'workout_id' 	=> $row->workout_id,
		    					'circuit_id' 	=> $row->circuit_id,
		    					'name' 			=> $row->name,
		    					'description' 	=> $row->description,
		    					'timetaken' 	=> $row->timetaken,
		    					'resttime' 		=> $row->resttime,
		    					'image' 		=> $row->image,
		    					'status' 		=> $row->status,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('exercise', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		// $workout = $this->admin_model->get_result('workout', array('trainer_id' => $trainer_id));

	    	$this->db->where('trainer_id', $trainer_id);
	    	// $this->db->like('date', '-'.date('m').'-');
	    	$fq = date("Y-m-%", time());
    		$sq = date("Y-m-%", strtotime("+1 month"));
    		$tq = date("Y-m-%", strtotime("-1 month"));
    		$sql_where = "((date LIKE '".$fq."' ) OR ( date LIKE '".$sq."' ) OR ( date LIKE '".$tq."' ) ) ";
    		$this->db->where($sql_where);
	    	
	    	$qry = $this->db->get('workout');
	    	if($qry->num_rows() > 0){
	    		$workout = $qry->result();
	    	}
	    	else{
	    		$workout = FALSE;
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		if($workout){
    			$workout_id = array();
    			foreach($workout as $row)
    				$workout_id[] = $row->id;

    			$this->db->where_in('workout_id', $workout_id);
    		}

    		$result = $this->admin_model->get_result('exercise');

    		if($result){
	    		$response = array();
	    		foreach($result as $row){
	    			$row = (array) $row;
	    			$path = './assets/uploads/exercise/';
	    			$image = $row['image'];
	    			
	    			if($image != '' && $image != '0')
	    				$row['base64image'] = convertImageToBase64($path, $image);
	    			else
	    				$row['base64image'] = '0';
	    			
	    			$response[] = $row;
	    			// $response[] = array(
	    			// 	'id' => $row['id'],
	    			// 	'image' => $row['image']
	    			// );
	    		}
	    	}else{
	    		$response = FALSE;
	    	}

	    	if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $response,
    				'delete_ids' => $delete_ids
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_circuit(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();
			$delete_ids = array();
			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			
	    			$lastupdated = explode('.', $r[8]);
	    			$lastupdated = $lastupdated[0];

	    			$row = array(
	    				'id' 			=> $r[0],
	    				'workout_id' 	=> $r[1],
	    				'name' 			=> $r[2],
	    				'description' 	=> $r[3],
	    				'image' 		=> $r[4],
	    				'created' 		=> $r[5],
	    				'updated' 		=> $r[6],
	    				'token' 		=> $r[7],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('circuit', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'notes' 		=> $row->notes,
	    				// 	'workout_id' 	=> $row->workout_id,
	    				// 	'name' 			=> $row->name,
	    				// 	'description' 	=> $row->description,
	    				// 	'timetaken' 	=> $row->timetaken,
	    				// 	'resttime' 		=> $row->resttime,
	    				// 	'image' 		=> $row->image,
	    				// 	'status' 		=> $row->status,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('exercise', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'workout_id' 	=> $row->workout_id,
		    					'name' 			=> $row->name,
		    					'description' 	=> $row->description,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('circuit', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		// $workout = $this->admin_model->get_result('workout', array('trainer_id' => $trainer_id));

	    	$this->db->where('trainer_id', $trainer_id);
	    	// $this->db->like('date', '-'.date('m').'-');
	    	$fq = date("Y-m-%", time());
    		$sq = date("Y-m-%", strtotime("+1 month"));
    		$tq = date("Y-m-%", strtotime("-1 month"));
    		$sql_where = "((date LIKE '".$fq."' ) OR ( date LIKE '".$sq."' ) OR ( date LIKE '".$tq."' ) ) ";
    		$this->db->where($sql_where);
	    	
	    	$qry = $this->db->get('workout');
	    	if($qry->num_rows() > 0){
	    		$workout = $qry->result();
	    	}
	    	else{
	    		$workout = FALSE;
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		if($workout){
    			$workout_id = array();
    			foreach($workout as $row)
    				$workout_id[] = $row->id;

    			$this->db->where_in('workout_id', $workout_id);
    		}

    		$result = $this->admin_model->get_result('circuit');

    		if($result){
	    		$response = array();
	    		foreach($result as $row){
	    			$row = (array) $row;
	    			$path = './assets/uploads/exercise/';
	    			$image = $row['image'];
	    			
	    			$row['base64image'] = '';
	    			
	    			$response[] = $row;
	    			// $response[] = array(
	    			// 	'id' => $row['id'],
	    			// 	'image' => $row['image']
	    			// );
	    		}
	    	}else{
	    		$response = FALSE;
	    	}

	    	if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $response,
    				'delete_ids' => $delete_ids
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_exercise_set(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();
			$delete_ids = array();
			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[12]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'exercise_id' 	=> $r[1],
	    				'value' 		=> $r[2],
	    				'reps' 			=> $r[3],
	    				'resultweight' 	=> $r[4],
	    				'timetaken' 	=> $r[5],
	    				'resultreps' 	=> $r[6],
	    				'set_status' 	=> $r[7],
	    				'created' 		=> $r[8],
	    				'updated' 		=> $r[9],
	    				'token' 		=> $r[10],
	    				'time' 			=> $r[11],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('exercise_set', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'exercise_id' 	=> $row->exercise_id,
	    				// 	'value' 		=> $row->value,
	    				// 	'reps' 			=> $row->reps,
	    				// 	'resultweight' 	=> $row->resultweight,
	    				// 	'timetaken' 	=> $row->timetaken,
	    				// 	'resultreps' 	=> $row->resultreps,
	    				// 	'set_status' 	=> $row->set_status,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('exercise_set', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'exercise_id' 	=> $row->exercise_id,
		    					'value' 		=> $row->value,
		    					'time' 			=> $row->time,
		    					'reps' 			=> $row->reps,
		    					'resultweight' 	=> $row->resultweight,
		    					'timetaken' 	=> $row->timetaken,
		    					'resultreps' 	=> $row->resultreps,
		    					'set_status' 	=> $row->set_status,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('exercise_set', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		// $workout = $this->admin_model->get_result('workout', array('trainer_id' => $trainer_id));

	    	$this->db->where('trainer_id', $trainer_id);
	    	// $this->db->like('date', '-'.date('m').'-');
	    	$fq = date("Y-m-%", time());
    		$sq = date("Y-m-%", strtotime("+1 month"));
    		$tq = date("Y-m-%", strtotime("-1 month"));
    		$sql_where = "((date LIKE '".$fq."' ) OR ( date LIKE '".$sq."' ) OR ( date LIKE '".$tq."' ) ) ";
    		$this->db->where($sql_where);
    		
	    	$qry = $this->db->get('workout');
	    	if($qry->num_rows() > 0){
	    		$workout = $qry->result();
	    	}
	    	else{
	    		$workout = FALSE;
	    	}

    		if($workout){
    			$workout_id = array();
    			foreach($workout as $row)
    				$workout_id[] = $row->id;

    			$this->db->where_in('workout_id', $workout_id);
    		}

    		$exercise = $this->admin_model->get_result('exercise');

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		if($exercise){
    			$exercise_id = array();
    			foreach($exercise as $row)
    				$exercise_id[] = $row->id;

    			$this->db->where_in('exercise_id', $exercise_id);
    		}

    		$result = $this->admin_model->get_result('exercise_set');



    		if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $result,
    				'delete_ids' => $delete_ids
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_trainee_workout(){
		$this->load->model('admin_model');
		$trainee_workout = $this->input->post('trainee_workout');
		$trainee_id = $this->input->post('trainee_id');
		if($trainee_workout === FALSE || $trainee_id === FALSE || $trainee_id == '' || $trainee_id == '0'){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();
			$delete_ids = array();

			if($trainee_workout != ''){
	    		$arr = explode('__----__', $trainee_workout);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[8]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' => $r[0],
	    				'trainee_id' => $r[1],
	    				'workout_id' => $r[2],
	    				'is_group' => $r[3],
	    				'group_id' => $r[4],
	    				'created' => $r[5],
	    				'updated' => $r[6],
	    				'token' => $r[7],
	    				'lastupdated' => $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('trainee_workout', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'trainee_id' 	=> $row->trainee_id,
	    				// 	'workout_id' 	=> $row->workout_id,
	    				// 	'is_group' 		=> $row->is_group,
	    				// 	'group_id' 		=> $row->group_id,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('trainee_workout', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'trainee_id' 	=> $row->trainee_id,
		    					'workout_id' 	=> $row->workout_id,
		    					'is_group' 		=> $row->is_group,
		    					'group_id' 		=> $row->group_id,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('trainee_workout', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		$this->db->where('trainee_id' , $trainee_id);

    		$result = $this->admin_model->get_result('trainee_workout');

    		if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $result,
    				'delete_ids' => $delete_ids
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_support(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[16]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'token' 		=> $r[1],
	    				'trainer_id' 	=> $r[2],
	    				'trainer_name' 	=> $r[3],
	    				'trainer_email' => $r[4],
	    				'trainee_id' 	=> $r[5],
	    				'trainee_name' 	=> $r[6],
	    				'trainee_email' => $r[7],
	    				'by' 			=> $r[8],
	    				'subject' 		=> $r[9],
	    				'message' 		=> $r[10],
	    				'status' 		=> $r[11],
	    				'is_read' 		=> $r[12],
	    				'created' 		=> $r[13],
	    				'updated' 		=> $r[14],
	    				'token2' 		=> $r[15],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('support', array('token2' => $row->token2));
	    			if(!($var)){
	    				$insert = array(
	    					'token' 		=> $row->token,
	    					'trainer_id' 	=> $row->trainer_id,
	    					'trainer_name' 	=> $row->trainer_name,
	    					'trainer_email' => $row->trainer_email,
	    					'trainee_id' 	=> $row->trainee_id,
	    					'trainee_name' 	=> $row->trainee_name,
	    					'trainee_email' => $row->trainee_email,
	    					'by' 			=> $row->by,
	    					'subject' 		=> $row->subject,
	    					'message' 		=> $row->message,
	    					'status' 		=> $row->status,
	    					'is_read' 		=> $row->is_read,
	    					'created' 		=> $row->created,
	    					'updated' 		=> $row->updated,
	    					'token2' 		=> $row->token2,
	    					'lastupdated' 	=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('support', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'token' 		=> $row->token,
		    					'trainer_id' 	=> $row->trainer_id,
		    					'trainer_name' 	=> $row->trainer_name,
		    					'trainer_email' => $row->trainer_email,
		    					'trainee_id' 	=> $row->trainee_id,
		    					'trainee_name' 	=> $row->trainee_name,
		    					'trainee_email' => $row->trainee_email,
		    					'by' 			=> $row->by,
		    					'subject' 		=> $row->subject,
		    					'message' 		=> $row->message,
		    					'status' 		=> $row->status,
		    					'is_read' 		=> $row->is_read,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('support', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		// $this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');
    		$this->db->where('trainee_id', $trainee_id);

    		$result = $this->admin_model->get_result('support');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_trainee(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[22]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'slug' 			=> $r[1],
	    				'trainer_id' 	=> $r[2],
	    				'fname' 		=> $r[3],
	    				'lname' 		=> $r[4],
	    				'email' 		=> $r[5],
	    				'image' 		=> $r[6],
	    				'currentweight' => $r[7],
	    				'height' 		=> $r[8],
	    				'goalweight' 	=> $r[9],
	    				'password' 		=> $r[10],
	    				'address' 		=> $r[11],
	    				'city' 			=> $r[12],
	    				'state' 		=> $r[13],
	    				'phone' 		=> $r[14],
	    				'zip' 			=> $r[15],
	    				'forgot_pwd' 	=> $r[16],
	    				'forgot_pwd_key'=> $r[17],
	    				'created' 		=> $r[18],
	    				'updated' 		=> $r[19],
	    				'token' 		=> $r[20],
	    				'newimage' 		=> $r[21],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('trainee', array('token' => $row->token));
	    			if(!($var)){
	    				// $insert = array(
	    				// 	'slug' 				=> $row->slug,
	    				// 	'trainer_id' 		=> $row->trainer_id,
	    				// 	'fname' 			=> $row->fname,
	    				// 	'lname' 			=> $row->lname,
	    				// 	'email' 			=> $row->email,
	    				// 	'image' 			=> $row->image,
	    				// 	'currentweight' 	=> $row->currentweight,
	    				// 	'height' 			=> $row->height,
	    				// 	'goalweight' 		=> $row->goalweight,
	    				// 	'password' 			=> $row->password,
	    				// 	'address' 			=> $row->address,
	    				// 	'city' 				=> $row->city,
	    				// 	'state' 			=> $row->state,
	    				// 	'phone' 			=> $row->phone,
	    				// 	'zip' 				=> $row->zip,
	    				// 	'forgot_pwd' 		=> $row->forgot_pwd,
	    				// 	'forgot_pwd_key' 	=> $row->forgot_pwd_key,
	    				// 	'created' 			=> $row->created,
	    				// 	'updated' 			=> $row->updated,
	    				// 	'token' 			=> $row->token,
	    				// 	'lastupdated' 		=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('trainee', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$image = $row->newimage;
							if($image != ""){
								$nimage = createImageOnAws($image);
								if($nimage){
									$uimage = $nimage;
								}
								else{
									$uimage = $var->image;
								}
							}
							else{
								$uimage = $var->image;
							}

							$update = array(
								'slug' 				=> $row->slug,
		    					'fname' 			=> $row->fname,
		    					'lname' 			=> $row->lname,
		    					'email' 			=> $row->email,
		    					'image' 			=> $uimage,
		    					'currentweight' 	=> $row->currentweight,
		    					'height' 			=> $row->height,
		    					'goalweight' 		=> $row->goalweight,
		    					'password' 			=> $row->password,
		    					'address' 			=> $row->address,
		    					'city' 				=> $row->city,
		    					'state' 			=> $row->state,
		    					'phone' 			=> $row->phone,
		    					'zip' 				=> $row->zip,
		    					'forgot_pwd' 		=> $row->forgot_pwd,
		    					'forgot_pwd_key' 	=> $row->forgot_pwd_key,
		    					'created' 			=> $row->created,
		    					'updated' 			=> $row->updated,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('trainee', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		// $this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');
    		$this->db->where('id', $trainee_id);

    		$result = $this->admin_model->get_result('trainee');

    		if($result){
	    		$response = array();
	    		foreach($result as $row){
	    			$row = (array) $row;
	    			$path = './assets/uploads/trainee/';
	    			$image = $row['image'];
	    			if($image != '' && $image != '0')
	    				$row['base64image'] = convertImageToBase64($path, $image);
	    			else
	    				$row['base64image'] = '0';
	    			$response[] = $row;
	    		}
	    	}else{
	    		$response = FALSE;
	    	}

    		$res = array(
    			'success' => array(
    				'data' => $response
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_trainer(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[17]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'slug' 			=> $r[1],
	    				'fname' 		=> $r[2],
	    				'lname' 		=> $r[3],
	    				'email' 		=> $r[4],
	    				'password' 		=> $r[5],
	    				'address' 		=> $r[6],
	    				'city' 			=> $r[7],
	    				'state' 		=> $r[8],
	    				'phone' 		=> $r[9],
	    				'zip' 			=> $r[10],
	    				'forgot_pwd' 	=> $r[11],
	    				'forgot_pwd_key'=> $r[12],
	    				'status' 		=> $r[13],
	    				'created' 		=> $r[14],
	    				'updated' 		=> $r[15],
	    				'token' 		=> $r[16],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('trainer', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'slug' 				=> $row->slug,
	    					'fname' 			=> $row->fname,
	    					'lname' 			=> $row->lname,
	    					'email' 			=> $row->email,
	    					'password' 			=> $row->password,
	    					'address' 			=> $row->address,
	    					'city' 				=> $row->city,
	    					'state' 			=> $row->state,
	    					'phone' 			=> $row->phone,
	    					'zip' 				=> $row->zip,
	    					'forgot_pwd' 		=> $row->forgot_pwd,
	    					'forgot_pwd_key' 	=> $row->forgot_pwd_key,
	    					'status' 			=> $row->status,
	    					'created' 			=> $row->created,
	    					'updated' 			=> $row->updated,
	    					'token' 			=> $row->token,
	    					'lastupdated' 		=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('trainer', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'slug' 				=> $row->slug,
		    					'fname' 			=> $row->fname,
		    					'lname' 			=> $row->lname,
		    					'email' 			=> $row->email,
		    					'password' 			=> $row->password,
		    					'address' 			=> $row->address,
		    					'city' 				=> $row->city,
		    					'state' 			=> $row->state,
		    					'phone' 			=> $row->phone,
		    					'zip' 				=> $row->zip,
		    					'forgot_pwd' 		=> $row->forgot_pwd,
		    					'forgot_pwd_key' 	=> $row->forgot_pwd_key,
		    					'status' 			=> $row->status,
		    					'created' 			=> $row->created,
		    					'updated' 			=> $row->updated,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('trainer', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		// $this->db->where('( ( sender_id = '.$trainee_id.' OR reciver_id = '.$trainee_id.' ) OR ( sender_id = '.$trainer_id.' OR reciver_id = '.$trainer_id.' ) )');
    		$this->db->where('id', $trainer_id);

    		$result = $this->admin_model->get_result('trainer');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_workout_notes(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			
	    			$lastupdated = explode('.', $r[7]);
	    			$lastupdated = $lastupdated[0];

	    			$w_id = explode('.', $r[1]);
	    			$w_id = $w_id[0];
	    			
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'workout_id' 	=> $w_id,
	    				'trainee_id' 	=> $r[2],
	    				'notes' 		=> $r[3],
	    				'created' 		=> $r[4],
	    				'updated' 		=> $r[5],
	    				'token' 		=> $r[6],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		// 
	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('workout_notes', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'workout_id' 		=> $row->workout_id,
	    					'trainee_id' 		=> $row->trainee_id,
	    					'notes' 			=> $row->notes,
	    					'created' 			=> $row->created,
	    					'updated' 			=> $row->updated,
	    					'token' 			=> $row->token,
	    					'lastupdated' 		=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('workout_notes', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'workout_id' 		=> $row->workout_id,
		    					'trainee_id' 		=> $row->trainee_id,
		    					'notes' 			=> $row->notes,
		    					'created' 			=> $row->created,
		    					'updated' 			=> $row->updated,
		    					'token' 			=> $row->token,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('workout_notes', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		$this->db->where('trainee_id', $trainee_id);

    		$result = $this->admin_model->get_result('workout_notes');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_exercise_notes(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[7]);
	    			$lastupdated = $lastupdated[0];

	    			$e_id = explode('.', $r[1]);
	    			$e_id = $e_id[0];

	    			$row = array(
	    				'id' 			=> $r[0],
	    				'exercise_id' 	=> $e_id,
	    				'trainee_id' 	=> $r[2],
	    				'notes' 		=> $r[3],
	    				'created' 		=> $r[4],
	    				'updated' 		=> $r[5],
	    				'token' 		=> $r[6],
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		// 
	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('exercise_notes', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'exercise_id' 		=> $row->exercise_id,
	    					'trainee_id' 		=> $row->trainee_id,
	    					'notes' 			=> $row->notes,
	    					'created' 			=> $row->created,
	    					'updated' 			=> $row->updated,
	    					'token' 			=> $row->token,
	    					'lastupdated' 		=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('exercise_notes', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'exercise_id' 		=> $row->exercise_id,
		    					'trainee_id' 		=> $row->trainee_id,
		    					'notes' 			=> $row->notes,
		    					'created' 			=> $row->created,
		    					'updated' 			=> $row->updated,
		    					'token' 			=> $row->token,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('exercise_notes', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		$this->db->where('trainee_id', $trainee_id);

    		$result = $this->admin_model->get_result('exercise_notes');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function sync_exercise_set_results(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainee_id = $this->input->post('trainee_id');
		if($syncData === FALSE || $trainee_id === FALSE || $trainee_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{
			$result = array();
			$id_where = array();

			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			$lastupdated = explode('.', $r[11]);
	    			$lastupdated = $lastupdated[0];
	    			$row = array(
	    				'id' 				=> $r[0],
	    				'exercise_set_id' 	=> $r[1],
	    				'resultweight' 		=> $r[2],
	    				'timetaken' 		=> $r[3],
	    				'resultreps' 		=> $r[4],
	    				'trainee_id' 		=> $r[5],
	    				'status' 			=> $r[6],
	    				'token' 			=> $r[7],
	    				'created' 			=> $r[8],
	    				'updated' 			=> $r[9],
	    				'resulttime'		=> $r[10],
	    				'lastupdated' 		=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		// 
	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('exercise_set_results', array('token' => $row->token));
	    			if(!($var)){
	    				$insert = array(
	    					'exercise_set_id' 	=> $row->exercise_set_id,
	    					'resultweight' 		=> $row->resultweight,
	    					'timetaken' 		=> $row->timetaken,
	    					'resultreps' 		=> $row->resultreps,
	    					'resulttime' 		=> $row->resulttime,
	    					'trainee_id' 		=> $row->trainee_id,
	    					'status' 			=> $row->status,
	    					'token' 			=> $row->token,
	    					'created' 			=> $row->created,
	    					'updated' 			=> $row->updated,
	    					'lastupdated' 		=> $row->lastupdated
	    				);
	    				$id_where[] = $this->admin_model->insert('exercise_set_results', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'exercise_set_id' 	=> $row->exercise_set_id,
		    					'resultweight' 		=> $row->resultweight,
		    					'timetaken' 		=> $row->timetaken,
		    					'resultreps' 		=> $row->resultreps,
		    					'resulttime' 		=> $row->resulttime,
		    					'trainee_id' 		=> $row->trainee_id,
		    					'status' 			=> $row->status,
		    					'token' 			=> $row->token,
		    					'created' 			=> $row->created,
		    					'updated' 			=> $row->updated,
		    					'lastupdated' 		=> $row->lastupdated
		    				);
		    				$this->admin_model->update('exercise_set_results', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		$this->db->where('trainee_id', $trainee_id);

    		$result = $this->admin_model->get_result('exercise_set_results');

    		$res = array(
    			'success' => array(
    				'data' => $result
    			)
    		);
    		echo json_encode($res);
			exit();
		}
	}

	public function get_time(){
		echo time();
	}

	public function db_check_email(){
		$trainee_id = $this->input->post('trainee_id');
		if($trainee_id != ''){
			$trainee = $this->get_row('trainee', array('id' => $trainee_id));
			if(empty($trainee)){
				$res = array('flag' => FALSE);
				echo json_encode($res);
				exit();
			}
			if ($this->input->post('email') != $trainee->email) {
				$row = $this->get_row('trainee', array('email' => $this->input->post('email')));
				if($row){
					$res = array('flag' => FALSE);
					echo json_encode($res);
					exit();
				}else{
					$res = array('flag' => TRUE);
					echo json_encode($res);
					exit();
				}
			}
		}
		else{
			$res = array('flag' => FALSE);
			echo json_encode($res);
			exit();
		}
	}

	public function db_update_trainee_profile(){
		$trainee_id = $this->input->post('trainee_id');
		$newimage = $this->input->post('newimage');
		if($trainee_id == '' || $trainee_id === FALSE){
			die();
		}
		
		if($newimage != ""){
			$nimage = createImageOnAws($newimage);
			if($nimage){
				$data['image'] = $nimage;
			}
		}
		
		$data['updated'] = $this->input->post('updated');
		$data['fname'] = $this->input->post('fname');
		$data['lname'] = $this->input->post('lname');
		$data['lastupdated'] = $this->input->post('lastupdated');
		$data['email'] = $this->input->post('email');
		$data['currentweight'] = $this->input->post('currentweight');
		$data['height'] = $this->input->post('height');
		$data['goalweight'] = $this->input->post('goalweight');
		
		$this->db->where('id', $this->input->post('trainee_id'));
		$this->db->update('trainee', $data);
		echo '1';
		die();
	}

	/*
	public function sync_exercise_for_android(){
		$this->load->model('admin_model');
		$syncData = $this->input->post('syncData');
		$trainer_id = $this->input->post('trainer_id');
		if($syncData === FALSE || $trainer_id === FALSE || $trainer_id == ''){
			$res = array('error' => 'METHOD NOT FOUND !!!');
			echo json_encode($res);
			exit();
		}else{

			$this->load->library('smtp_lib/smtp_email');
			
			$this->smtp_email->sendEmail('no-reply@trainertech.com', 'adil@acapellahq.com', 'Exercise SYNC Data', ' --> ' . $syncData . ' <-- ');

			$result = array();
			$id_where = array();
			$delete_ids = array();
			if($syncData != ''){
	    		$arr = explode('__----__', $syncData);
	    		foreach($arr as $r){
	    			$r = explode('_--_', $r);
	    			
	    			$lastupdated = explode('.', $r[12]);
	    			$lastupdated = $lastupdated[0];

	    			$circuit_id = explode('.', $r[13]);
	    			$circuit_id = $circuit_id[0];
	    			
	    			$row = array(
	    				'id' 			=> $r[0],
	    				'notes' 		=> $r[1],
	    				'workout_id' 	=> $r[2],
	    				'name' 			=> $r[3],
	    				'description' 	=> $r[4],
	    				'timetaken' 	=> $r[5],
	    				'resttime' 		=> $r[6],
	    				'image' 		=> $r[7],
	    				'status' 		=> $r[8],
	    				'created' 		=> $r[9],
	    				'updated' 		=> $r[10],
	    				'token' 		=> $r[11],
	    				'circuit_id'	=> $circuit_id,
	    				'lastupdated' 	=> $lastupdated
	    			);
	    			$result[] = $row;
	    		}

	    		
	    		foreach($result as $row){
	    			$row = (object)$row;
	    			$var = $this->admin_model->get_row('exercise', array('token' => $row->token));
	    			if(!($var)){
	    				$delete_ids[] = $row->id;
	    				// $insert = array(
	    				// 	'notes' 		=> $row->notes,
	    				// 	'workout_id' 	=> $row->workout_id,
	    				// 	'name' 			=> $row->name,
	    				// 	'description' 	=> $row->description,
	    				// 	'timetaken' 	=> $row->timetaken,
	    				// 	'resttime' 		=> $row->resttime,
	    				// 	'image' 		=> $row->image,
	    				// 	'status' 		=> $row->status,
	    				// 	'created' 		=> $row->created,
	    				// 	'updated' 		=> $row->updated,
	    				// 	'token' 		=> $row->token,
	    				// 	'lastupdated' 	=> $row->lastupdated
	    				// );
	    				// $id_where[] = $this->admin_model->insert('exercise', $insert);
	    			}
	    			else{
	    				if($var->lastupdated <= $row->lastupdated){
							$update = array(
								'notes' 		=> $row->notes,
		    					'workout_id' 	=> $row->workout_id,
		    					'circuit_id' 	=> $row->circuit_id,
		    					'name' 			=> $row->name,
		    					'description' 	=> $row->description,
		    					'timetaken' 	=> $row->timetaken,
		    					'resttime' 		=> $row->resttime,
		    					'image' 		=> $row->image,
		    					'status' 		=> $row->status,
		    					'created' 		=> $row->created,
		    					'updated' 		=> $row->updated,
		    					'lastupdated' 	=> $row->lastupdated
		    				);
		    				$this->admin_model->update('exercise', $update, array('id' => $var->id));
							$id_where[] = $var->id;
						}
	    			}
	    		}
	    	}

    		// $workout = $this->admin_model->get_result('workout', array('trainer_id' => $trainer_id));

	    	$this->db->where('trainer_id', $trainer_id);
	    	// $this->db->like('date', '-'.date('m').'-');
	    	$fq = date("Y-m-%", time());
    		$sq = date("Y-m-%", strtotime("+1 month"));
    		$tq = date("Y-m-%", strtotime("-1 month"));
    		$sql_where = "((date LIKE '".$fq."' ) OR ( date LIKE '".$sq."' ) OR ( date LIKE '".$tq."' ) ) ";
    		$this->db->where($sql_where);
	    	
	    	$qry = $this->db->get('workout');
	    	if($qry->num_rows() > 0){
	    		$workout = $qry->result();
	    	}
	    	else{
	    		$workout = FALSE;
	    	}

    		foreach($id_where as $id){
    			$this->db->where('id !=', $id);
    		}

    		if($workout){
    			$workout_id = array();
    			foreach($workout as $row)
    				$workout_id[] = $row->id;

    			$this->db->where_in('workout_id', $workout_id);
    		}

    		$result = $this->admin_model->get_result('exercise');

    		if($result){
	    		$response = array();
	    		foreach($result as $row){
	    			$row = (array) $row;
	    			$path = './assets/uploads/exercise/';
	    			$image = $row['image'];
	    			
	    			if($image != '' && $image != '0')
	    				$row['base64image'] = convertImageToBase64($path, $image);
	    			else
	    				$row['base64image'] = '0';
	    			
	    			$response[] = $row;
	    			// $response[] = array(
	    			// 	'id' => $row['id'],
	    			// 	'image' => $row['image']
	    			// );
	    		}
	    	}else{
	    		$response = FALSE;
	    	}

	    	if(!(count($delete_ids) > 0)){
    			$delete_ids = FALSE;
    		}

    		$res = array(
    			'success' => array(
    				'data' => $response,
    				'delete_ids' => $delete_ids
    			)
    		);

    		$this->smtp_email->sendEmail('no-reply@trainertech.com', 'adil@acapellahq.com', 'Return Exercise Data', ' --> ' . json_encode($res) . ' <-- ');

    		echo json_encode($res);
			exit();
		}
	}
	*/
}