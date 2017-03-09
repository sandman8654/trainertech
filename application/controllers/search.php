<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Search extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index(){
		$data['menuactive'] = 'search';
		$data['bodyclass'] = 'about';
		$data['pagetitle'] = 'Search results';

		$limit = 6;
		$offset = 0;

		$post['srchfield'] = $this->input->post('s');
		$post['pricerange'] = '';

		$data['results'] = $this->admin_model->filter_listings($post, $limit, $offset);

		$data['template'] = 'search/index';
		$this->load->view('templates/home_template', $data);
	}

	public function load_more(){
		$limit = 6;
		$offset = $this->input->post('offset');
		$post['srchfield'] = $this->input->post('s');
		$post['pricerange'] = '';
		$data['results'] = $this->admin_model->filter_listings($post, $limit, $offset);

		if($data['results']){
			echo json_encode($data['results']);
		}else{
			echo '0';
		}

	}
}