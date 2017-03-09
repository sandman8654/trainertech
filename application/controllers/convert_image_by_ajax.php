<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Convert_image_by_ajax extends CI_Controller {
	public function __construct(){
		parent::__construct();
		error_reporting(0);
		header('Content-Type: application/json'); 
		
		$this->load->model('admin_model');
		$this->load->helper('convert_image_to_base64');
	}

	public function index(){
		$image = $this->input->post('image');
		if($image === FALSE){
			$res = array('error' => '0');
			echo json_encode($res);
			exit();
		}else{
			$base64image = convertImageToBase64ByAjax(BUCKET_PATH.$image);
			$res = array(
				'base64image' => $base64image
			);
			echo json_encode($res);
			exit();
		}
	}
}