<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payu extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('patient/patient_model');
        $this->load->model('donor/donor_model');
        $this->load->model('finance/finance_model');
        $this->load->model('finance/pharmacy_model');
        $this->load->model('prescription/prescription_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('pgateway/pgateway_model');
        if (!$this->ion_auth->in_group(array('admin', 'Nurse', 'Patient', 'Doctor', 'Laboratorist', 'Accountant', 'Receptionist', 'Pharmacist'))) {
            redirect('home/permission');
        }
    }

    public function index() {
        redirect('home');
    }

    public function check() {

        $amount = $this->input->get('deposited_amount');
        $invoice_id = $this->input->get('payment_id');


        $payment = $this->finance_model->getPaymentById($invoice_id);

        $client_info = $this->patient_model->getPatientById($payment->patient);

        $product_info = $invoice_id;
        $customer_name = $client_info->name;
        $customer_emial = $client_info->email;
        $customer_mobile = $client_info->phone;
        $customer_address = $client_info->address;


        $payumoney = $this->pgateway_model->getPaymentGatewaySettingsByName('Pay U Money');

        if ($payumoney->status == 'live') {
            $action = "https://secure.payu.in";
        } else {
            $action = "https://sandboxsecure.payu.in";
        }

        $MERCHANT_KEY = $payumoney->merchant_key; 
        $SALT = $payumoney->salt; 

        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
        $hash = strtolower(hash('sha512', $hashstring));

        $success = base_url() . 'payu/Status';
        $fail = base_url() . 'payu/Status';
        $cancel = base_url() . 'payu/Status';


        $data = array(
            'mkey' => $MERCHANT_KEY,
            'tid' => $txnid,
            'hash' => $hash,
            'amount' => $amount,
            'name' => $customer_name,
            'productinfo' => $product_info,
            'mailid' => $customer_emial,
            'phoneno' => $customer_mobile,
            'address' => $customer_address,
            'action' => $action, 
            'sucess' => $success,
            'failure' => $fail,
            'cancel' => $cancel
        );



        $this->load->view('home/dashboard'); 
        $this->load->view('confirmation', $data);
        $this->load->view('home/footer'); 
    }

    public function check1() {

        $amount = $this->input->get('deposited_amount');
        $invoice_id = $this->input->get('payment_id');


        $payment = $this->finance_model->getPaymentById($invoice_id);

        $client_info = $this->patient_model->getPatientById($payment->patient);

        $product_info = $invoice_id;
        $customer_name = $client_info->name;
        $customer_emial = $client_info->email;
        $customer_mobile = $client_info->phone;
        $customer_address = $client_info->address;

        


        $payumoney = $this->pgateway_model->getPaymentGatewaySettingsByName('Pay U Money');

        if ($payumoney->status == 'live') {
            $action = "https://secure.payu.in";
        } else {
            $action = "https://sandboxsecure.payu.in";
        }


        $MERCHANT_KEY = $payumoney->merchant_key; 
        $SALT = $payumoney->salt;   

        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
        $hash = strtolower(hash('sha512', $hashstring));

        $success = base_url() . 'payu/Status/index1';
        $fail = base_url() . 'payu/Status/index1';
        $cancel = base_url() . 'payu/Status/index1';


        $data = array(
            'mkey' => $MERCHANT_KEY,
            'tid' => $txnid,
            'hash' => $hash,
            'amount' => $amount,
            'name' => $customer_name,
            'productinfo' => $product_info,
            'mailid' => $customer_emial,
            'phoneno' => $customer_mobile,
            'address' => $customer_address,
            'action' => $action, 
            'sucess' => $success,
            'failure' => $fail,
            'cancel' => $cancel
        );



        $this->load->view('home/dashboard'); 
        $this->load->view('confirmation', $data);
        $this->load->view('home/footer'); 
    }

    public function check2() {

        $amount = $this->input->post('amount_received');
        $invoice_id = $this->input->post('id');


        $payment = $this->pharmacy_model->getPaymentById($invoice_id);

        $client_info = $this->patient_model->getPatientById($payment->patient);

        $product_info = $invoice_id;
        $customer_name = $client_info->name;
        $customer_emial = $client_info->email;
        $customer_mobile = $client_info->phone;
        $customer_address = $client_info->address;

       


        $payumoney = $this->pgateway_model->getPaymentGatewaySettingsByName('Pay U Money');

        if ($payumoney->status == 'live') {
            $action = "https://secure.payu.in";
        } else {
            $action = "https://sandboxsecure.payu.in";
        }


        $MERCHANT_KEY = $payumoney->merchant_key; 
        $SALT = $payumoney->salt; 

        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
        $hash = strtolower(hash('sha512', $hashstring));

        $success = base_url() . 'payu/Status/index2';
        $fail = base_url() . 'payu/Status/index2';
        $cancel = base_url() . 'payu/Status/index2';


        $data = array(
            'mkey' => $MERCHANT_KEY,
            'tid' => $txnid,
            'hash' => $hash,
            'amount' => $amount,
            'name' => $customer_name,
            'productinfo' => $product_info,
            'mailid' => $customer_emial,
            'phoneno' => $customer_mobile,
            'address' => $customer_address,
            'action' => $action,  
            'sucess' => $success,
            'failure' => $fail,
            'cancel' => $cancel
        );



        $this->load->view('home/dashboard');
        $this->load->view('confirmation', $data);
        $this->load->view('home/footer'); 
    }

    public function check3() {

        $amount = $this->input->get('amount_received');
        $invoice_id = $this->input->get('id');


        $payment = $this->pharmacy_model->getPaymentById($invoice_id);

        $client_info = $this->patient_model->getPatientById($payment->patient);

        $product_info = $invoice_id;
        $customer_name = $client_info->name;
        $customer_emial = $client_info->email;
        $customer_mobile = $client_info->phone;
        $customer_address = $client_info->address;

        


        $payumoney = $this->pgateway_model->getPaymentGatewaySettingsByName('Pay U Money');

        if ($payumoney->status == 'live') {
            $action = "https://secure.payu.in";
        } else {
            $action = "https://sandboxsecure.payu.in";
        }


        $MERCHANT_KEY = $payumoney->merchant_key; 
        $SALT = $payumoney->salt;  

        $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
        
        $udf1 = '';
        $udf2 = '';
        $udf3 = '';
        $udf4 = '';
        $udf5 = '';

        $hashstring = $MERCHANT_KEY . '|' . $txnid . '|' . $amount . '|' . $product_info . '|' . $customer_name . '|' . $customer_emial . '|' . $udf1 . '|' . $udf2 . '|' . $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' . $SALT;
        $hash = strtolower(hash('sha512', $hashstring));

        $success = base_url() . 'payu/Status/index2';
        $fail = base_url() . 'payu/Status/index2';
        $cancel = base_url() . 'payu/Status/index2';


        $data = array(
            'mkey' => $MERCHANT_KEY,
            'tid' => $txnid,
            'hash' => $hash,
            'amount' => $amount,
            'name' => $customer_name,
            'productinfo' => $product_info,
            'mailid' => $customer_emial,
            'phoneno' => $customer_mobile,
            'address' => $customer_address,
            'action' => $action, 
            'sucess' => $success,
            'failure' => $fail,
            'cancel' => $cancel
        );



        $this->load->view('home/dashboard'); 
        $this->load->view('confirmation', $data);
        $this->load->view('home/footer');
    }

    public function help() {
        $this->load->view('help');
    }

}
