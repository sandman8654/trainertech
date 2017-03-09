<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}

	// public function index($slug=""){
	// 	if($slug == ""){
	// 		redirect(_INDEX.'home');
	// 	}
	// 	$data['menuactive'] = 'gallery';
	// 	$data['gallerytitle'] = 'gallery';		
	// 	$data['gallery'] = $this->admin_model->get_row('gallery', array("slug" => $slug));
	// 	$data['template'] = 'gallery/index';
	// 	$this->load->view('templates/home_template', $data);
	// }	

	public function all($offset=0){		
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');

		$limit=10;
		$data['gallery']=$this->admin_model->get_pagination_result('gallery', $limit,$offset);
		// print_r($data);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'gallery/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('gallery', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'gallery/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function add(){
		 if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');
		$this->form_validation->set_rules('submit', 'submit', 'required');		
		if ($this->form_validation->run() == TRUE){
			

			/* if($_FILES['gall']['name'][0]!='')
		  {

			$this->load->library('upload');
              $resp = array();
              $cpt = count($_FILES['gall']['name']);              
              $files = $_FILES;                 

              for($i=0; $i<$cpt; $i++){
              	
              	$file = upload_to_bucket_server('gallery_image', 'g_');


                $_FILES['userfile']['name']= $files['gall']['name'][$i];
                $_FILES['userfile']['type']= $files['gall']['type'][$i];
                $_FILES['userfile']['tmp_name']= $files['gall']['tmp_name'][$i];
                $_FILES['userfile']['error']= $files['gall']['error'][$i];
                $_FILES['userfile']['size']= $files['gall']['size'][$i];    
                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload();
                $name = $this->upload->data();
                create_thumb($name['file_name'], './assets/uploads/blogs/');
                $blogGallery['image'] = $name['file_name'];
                $this->admin_model->insert('blogs_gallery', $blogGallery);
              }
          } */


          	$data = array('image'=>'');

			if(isset($_FILES['gallery_image']['name']) && $_FILES['gallery_image']['name']!=""){				
				$file = upload_to_bucket_server('gallery_image', 'g_');				
				if($file['status']){
					$data['image'] = $file['filename'];
				}
				// }else{
				// 	$this->session->set_flashdata('error_msg', 'File not uploaded.');			
				// 	redirect(_INDEX.'gallery/add');
				// }
				$this->admin_model->insert('gallery',$data);		
				$this->session->set_flashdata('success_msg',"Image has been added successfully.");
				redirect(_INDEX.'gallery/add');
			}else{
				$this->session->set_flashdata('error_msg',"Please select Image");
				redirect(_INDEX.'gallery/add');
			}
		}

		$data['template'] = 'gallery/add';
        $this->load->view('templates/admin_template', $data);		
	}	

	public function delete($id=""){	
		if(admin_login_in()===FALSE)
			redirect(_INDEX.'login');	
		$this->admin_model->delete('gallery',array('id'=> $id));		
		$this->session->set_flashdata('success_msg',"gallery has been deleted successfully.");
		redirect(_INDEX.'gallery/all');
	}
}