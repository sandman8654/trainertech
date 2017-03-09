<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stripe_pay extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$data['amount'] = '20.25';

        $this->form_validation->set_rules('stripeToken', 'stripeToken', 'required');
        if ($this->form_validation->run() == TRUE){
            $myCard = $this->input->post('stripeToken');
            $token = uniqid();
            $grand_total = 20;
            $amount = 20;             
            $description ="Payment form #$token"; 
            $stripe_responce=$this->stripe($amount, $myCard, $description);         
            $response = json_decode($stripe_responce);
            if(@$response->paid == 1){          
                $data = array(
                    'invoice'   => $response->id,
                    'text'      => $stripe_responce,
                    'created'   => date("Y-m-d H:i:s")
                );
                $this->db->insert('temp_table_delete_anytime', $data);
                echo "SUCCESS";
                die();
            }else{
                echo "your shopping payment failed. <h4>".$response->error->message."</h4> Please try again.";
                echo '<a href="'.base_url().'">HOME</a>';
                exit();
            }
        }
		$data['template'] = 'stripe_pay/form';
        $this->load->view('templates/admin_template', $data);	
	}

	public function fetch(){
		$result = $this->admin_model->get_result('temp_table_delete_anytime');
		echo "<pre>";
		if($result){
			foreach ($result as $key => $value) {
				print_r($value);
				print_r(json_decode($value->text));
				echo "---------------------------------";
			}
		}
		echo "---------------------------------";
	}

    private function stripe($amt, $token, $desc){
        $this->load->library('stripe');
        return $this->stripe->charge_card(($amt * 100), $token, $desc);
    }
}