<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manager_model extends CI_Model {
	private $table_name	= 'manager';
	function login($email=null, $password) {	
		$this->db->where('email', $email);
        $this->db->where('password',sha1($password));		
		$query=$this->db->get($this->table_name);
		if ($query->num_rows() > 0) {
			$row = array(
				'id'			=>$query->row()->id,						
				'email'			=>$query->row()->email,
				'fname'			=>$query->row()->fname,
				'lname'			=>$query->row()->lname,
				'logged_in'		=>TRUE
			);									
			$this->session->set_userdata('managerInfo',$row);
			return TRUE;				
		}else{
			$this->session->set_flashdata('error_msg','Invalid username or password.');		
			return FALSE;
		}
	}
}