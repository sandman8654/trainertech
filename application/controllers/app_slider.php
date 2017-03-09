<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_slider extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$this->all();
	}
	
	public function all($offset=0)
	{		
		if(admin_login_in()===FALSE)
			redirect('login');

		$limit=10;
		$data['app_slider']=$this->admin_model->get_pagination_result('app_slider', $limit,$offset);
		$config= get_theme_pagination();	
		$config['base_url'] = base_url().'app_slider/all/';
		$config['total_rows'] = $this->admin_model->get_pagination_result('app_slider', 0, 0);
		$config['per_page'] = $limit;
		// $config['num_links'] = 5;		
		$this->pagination->initialize($config); 		
		$data['pagination'] = $this->pagination->create_links();		

        $data['template'] = 'app_slider/all';
        $this->load->view('templates/admin_template', $data);			
	}	

	public function add()
	{
		if(admin_login_in()===FALSE)
			redirect('login');

		$this->form_validation->set_rules('content', 'name', 'required');							
		$this->form_validation->set_rules('order', 'Order', 'required|is_unique[app_slider.order]|numeric');							
								
		if ($this->form_validation->run() == TRUE){			
			$data=array(
						'slug'=>create_slug('app_slider', $this->input->post('content')),
						'content'=>$this->input->post('content'),
						'order'=>$this->input->post('order'),
						'created' => date('Y-m-d H:i:s')			
		    			);

		    $data['token'] = get_token();
			$data['lastupdated'] = time();	
			
			$this->admin_model->insert('app_slider',$data);		
			$this->session->set_flashdata('success_msg',"Slider has been added successfully.");
			redirect('app_slider/all');
		}

		$data['template'] = 'app_slider/add';
        $this->load->view('templates/admin_template', $data);		
	}

	public function edit($slug = ""){
		if(admin_login_in()===FALSE)
			redirect('login');

		if($slug == "")
			redirect('group/all');

		$data['app_slider'] = $this->admin_model->get_row('app_slider', array('slug'=>$slug));
		
		$this->form_validation->set_rules('content', 'Content', 'required');							
		
		if ($this->form_validation->run() == TRUE){			
			
		$order_old = $this->admin_model->get_row('app_slider', array('order'=>$_POST['order']));
        
        if(is_numeric($_POST['order'])==FALSE)
        {
			$this->session->set_flashdata('error_msg',"Order Id should contain a numeric value.");
			redirect('app_slider/edit/'.$slug);
        }

        if(!empty($order_old) && $order_old->id != $data['app_slider']->id)
        {
			$this->session->set_flashdata('error_msg',"Order Id should contain a unique value.");
			redirect('app_slider/edit/'.$slug);
        }
        else
        {
			$update=array(
						'slug'=>create_slug('app_slider', $this->input->post('content')),
						'content'=>$this->input->post('content'),
						'order'=>$this->input->post('order'),
						'updated' => date('Y-m-d H:i:s')			
		    			);		
        }

        	$update['lastupdated'] = time();
				
			$this->admin_model->update('app_slider',$update, array('slug'=>$slug));
			$this->session->set_flashdata('success_msg',"app_slider has been updated successfully.");
			redirect('app_slider/all');
		}

		$data['template'] = 'app_slider/edit';
        $this->load->view('templates/admin_template', $data);		
	}

	public function delete($slug=""){	
		if(admin_login_in()===FALSE)
			redirect('login');

		$this->admin_model->delete('app_slider',array('slug'=> $slug));		
		$this->session->set_flashdata('success_msg',"Slider has been deleted successfully.");
		redirect('app_slider/all');
	}



	
}