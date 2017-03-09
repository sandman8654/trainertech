<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ipn extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('admin_model');
	}
	
	public function index(){
		$data['invoice'] = uniqid();
		$data['user_email'] = 'test@test.com';
		$data['user_name'] = 'Test test';
		$data['amount'] = '20.25';

		$data['template'] = 'ipn/form';
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

	public function handler($invoice = ''){
		/**
         * Sandbox url : https://www.sandbox.paypal.com/cgi-bin/webscr 
         * Live url : https://www.paypal.com/cgi-bin/webscr
         */  

		$raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
        $keyval = explode ('=', $keyval);
        if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        
        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
        if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
        } else {
        $value = urlencode($value);
        }
        $req .= "&$key=$value";
        }
         
        // Step 2: POST IPN data back to PayPal to validate
         
        $ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
         
        if( !($res = curl_exec($ch)) ) {
        	curl_close($ch);
        }
        
        if (strcmp ($res, "VERIFIED") == 0)
        {
            $payment_status = $_POST['payment_status'];
            if($payment_status == 'Completed'){
                $text = json_encode($_POST);
            }
        } 
        else if (strcmp ($res, "INVALID") == 0) 
        {
            $text = json_encode($_POST);
        }

		$data = array(
			'invoice'	=> $invoice,
			'text'		=> $text,
			'created'	=> date("Y-m-d H:i:s")
		);
		$this->db->insert('temp_table_delete_anytime', $data);
	}
}