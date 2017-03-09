<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rename extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index(){
		var_dump($this->db->query('TRUNCATE ci_sessions'));
		die();
		$exercise = $this->admin_model->get_result('exercise');
		if($exercise){
			foreach($exercise as $row){

				if($row->image == ''){
					continue;
				}

				$old = './assets/uploads/exercise/'.$row->image;
				$new = './assets/uploads/exercise/e_'.$row->image;
				if(rename($old , $new)){
					$this->admin_model->update('exercise', array('image' => 'e_'.$row->image), array('id' => $row->id));
					echo '<p style="color:green;">E - '.$row->id.' - Success<p>';
				}
				else{
					echo '<p style="color:red;">E - '.$row->id.' - Error<p>';
				}
			}
		}

		$trainee = $this->admin_model->get_result('trainee');
		if($trainee){
			foreach($trainee as $row){

				if($row->image == ''){
					continue;
				}

				$old = './assets/uploads/trainee/'.$row->image;
				$new = './assets/uploads/trainee/t_'.$row->image;
				if(rename($old , $new)){
					$this->admin_model->update('trainee', array('image' => 't_'.$row->image), array('id' => $row->id));
					echo '<p style="color:green;">T - '.$row->id.' - Success<p>';
				}
				else{
					echo '<p style="color:red;">T - '.$row->id.' - Error<p>';
				}
			}
		}

		$workout = $this->admin_model->get_result('workout');
		if($workout){
			foreach($workout as $row){

				if($row->image == ''){
					continue;
				}

				$old = './assets/uploads/workout/'.$row->image;
				$new = './assets/uploads/workout/w_'.$row->image;
				if(rename($old , $new)){
					$this->admin_model->update('workout', array('image' => 'w_'.$row->image), array('id' => $row->id));
					echo '<p style="color:green;">W - '.$row->id.' - Success<p>';
				}
				else{
					echo '<p style="color:red;">W - '.$row->id.' - Error<p>';
				}
			}
		}
	}
}