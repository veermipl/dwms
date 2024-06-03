<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sslcommerzpayment extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('finance/finance_model');
    }

    public function index() {
        $this->load->view('home');
    }

    public function hosted_view() {
        $this->load->view('hostedcheckout');
    }

    public function easycheckout_view() {
        $this->load->view('easycheckout');
    }

    public function request_api_hosted($amount_received, $patient, $inserted_id, $user, $redirectlink) {
        $patientdetails = $this->db->get_where('patient', array('id =' => $patient))->row();
        $setingsdetails = $this->db->get('settings')->row();
        $SSLCOMMERZ = $this->db->get_where('paymentGateway', array('name =' => 'SSLCOMMERZ'))->row();

        $post_data = array();
        $post_data['total_amount'] = $amount_received;
        if ($setingsdetails->currency == '$' || strtolower($setingsdetails->currency) == 'usd') {
            $post_data['currency'] = "USD";
        }
        if (strtolower($setingsdetails->currency) == 'taka' || strtolower($setingsdetails->currency) == 'tk' || strtolower($setingsdetails->currency) == 'bdt' || $setingsdetails->currency == 'ট') {
            $post_data['currency'] = "BDT";
        }
        if (strtolower($setingsdetails->currency) == 'euro') {
            $post_data['currency'] = "EURO";
        }
        $post_data['store_id'] = $SSLCOMMERZ->store_id;
        $post_data['store_passwd'] = $SSLCOMMERZ->store_password;
        $post_data['tran_id'] = "SSLC" . uniqid();
        $post_data['success_url'] = base_url() . "sslcommerzpayment/success";
        $post_data['fail_url'] = base_url() . "sslcommerzpayment/fail_payment";
        $post_data['cancel_url'] = base_url() . "sslcommerzpayment/cancel_payment";
      
        $post_data['cus_name'] = $patientdetails->name;
        $post_data['cus_email'] = $patientdetails->email;
        $post_data['cus_add1'] = $patientdetails->address;
        
        $post_data['cus_phone'] = $patientdetails->phone;

        
        $post_data['ship_name'] = $setingsdetails->system_vendor;
        $post_data['ship_add1'] = $setingsdetails->address;
       
        $post_data['value_a'] = $inserted_id;
        $post_data['value_b'] = $patient;
        $post_data['value_c'] = $user;
        $post_data['value_d'] = $redirectlink;
       
        if ($SSLCOMMERZ->status == 'test') {
            $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";
        } else {
            $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v3/api.php";
        }
        $session = array(
            'tran_id' => $post_data['tran_id'],
            'amount' => $post_data['total_amount'],
            'currency' => $post_data['currency']
        );
        $this->session->set_userdata('tarndata', $session);



        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); 


        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !( curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }


        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {
          
            echo "<meta http-equiv='refresh' content='0;url=" . $sslcz['GatewayPageURL'] . "'>";
            
            exit;
        } else {
            echo "JSON Data parsing error!";
        }
    }

    public function success() {
        $sesdata = $this->session->userdata('tarndata');
        $SSLCOMMERZ = $this->db->get_where('paymentGateway', array('name =' => 'SSLCOMMERZ'))->row();
        $store_id = $SSLCOMMERZ->store_id;
        $store_password = $SSLCOMMERZ->store_password;
        $val_id = urlencode($_POST['val_id']);
        $store_id = urlencode($store_id);
        $store_passwd = urlencode($store_password);
        if ($SSLCOMMERZ->status == 'test') {
            $sesdata = $this->session->userdata('tarndata');
            $requested_url = ("https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=" . $val_id . "&store_id=" . $store_id . "&store_passwd=" . $store_passwd . "&v=1&format=json");
        } else {
            $sesdata = $this->session->userdata('tarndata');
            $requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=" . $val_id . "&store_id=" . $store_id . "&store_passwd=" . $store_passwd . "&v=1&format=json");
        }


        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); 

        $result = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !( curl_errno($handle))) {
            $sesdata = $this->session->userdata('tarndata');
            
            $result = json_decode($result);

            $this->deposit($result);
            
        } else {

            echo "Failed to connect with SSLCOMMERZ";
        }
    }

    public function deposit($post_data) {
        $sesdata = $this->session->userdata('tarndata');
        $date = time();
        if ($post_data->value_d == '1') {

            $data1 = array(
                'date' => $date,
                'patient' => $post_data->value_b,
                'deposited_amount' => $post_data->amount,
                'payment_id' => $post_data->value_a,
                'amount_received_id' => $post_data->value_a . '.' . 'gp',
                'deposit_type' => 'Card',
                'gateway' => 'SSLCOMMERZ',
                'user' => $post_data->value_c
            );

            $this->finance_model->insertDeposit($data1);

            $data_payment = array('amount_received' => $post_data->amount, 'deposit_type' => 'Card');
            $this->finance_model->updatePayment($post_data->value_a, $data_payment);
            redirect("finance/invoice?id=" . $post_data->value_a);
          
        } if ($post_data->value_d == '0') {
            $sesdata = $this->session->userdata('tarndata');
            $data1 = array(
                'date' => $date,
                'patient' => $post_data->value_b,
                'deposited_amount' => $post_data->amount,
                'payment_id' => $post_data->value_a,
                'gateway' => 'SSLCOMMERZ',
                'deposit_type' => 'Card',
                'user' => $post_data->value_c
            );

            $this->finance_model->insertDeposit($data1);
           

            $this->redirectlink($post_data->value_b);
        }
    }

    public function redirectlink($patient) {
        $sesdata = $this->session->userdata('tarndata');
        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect("patient/myPaymentHistory");
        } else {
            $sesdata = $this->session->userdata('tarndata');
            redirect('finance/patientPaymentHistory?patient=' . $patient);
        }
    }

    public function fail_payment() {
       
        $sesdata = $this->session->userdata('tarndata');

        $this->session->set_flashdata('feedback', '"Transaction Failed"');

        if ($_POST['value_d'] == '0') {
            $sesdata = $this->session->userdata('tarndata');
            $this->redirectlink($_POST['value_b']);
        } else {
            $sesdata = $this->session->userdata('tarndata');
            redirect("finance/invoice?id=" . $_POST['value_a']);
        }
     
    }

    public function cancel_payment() {
        $sesdata = $this->session->userdata('tarndata');

        $this->session->set_flashdata('feedback', '"Transaction Failed"');

        if ($_POST['value_d'] == '0') {
            $sesdata = $this->session->userdata('tarndata');
            $this->redirectlink($_POST['value_b']);
        } else {
            $sesdata = $this->session->userdata('tarndata');
            redirect("finance/invoice?id=" . $_POST['value_a']);
        }
    }

    public function ipn_listener() {
        $database_order_status = 'Pending'; // Check this from your database here Pending is dummy data,
        $store_passwd = SSLCZ_STORE_PASSWD;
        if ($ipn = $this->sslcommerz->ipn_request($store_passwd, $_POST)) {
            if (($ipn['gateway_return']['status'] == 'VALIDATED' || $ipn['gateway_return']['status'] == 'VALID') && $ipn['ipn_result']['hash_validation_status'] == 'SUCCESS') {
                if ($database_order_status == 'Pending') {
                    echo $ipn['gateway_return']['status'] . "<br>";
                    echo $ipn['ipn_result']['hash_validation_status'] . "<br>";
                   
                }
            } elseif ($ipn['gateway_return']['status'] == 'FAILED' && $ipn['ipn_result']['hash_validation_status'] == 'SUCCESS') {
                if ($database_order_status == 'Pending') {
                    echo $ipn['gateway_return']['status'] . "<br>";
                    echo $ipn['ipn_result']['hash_validation_status'] . "<br>";
                   
                }
            } elseif ($ipn['gateway_return']['status'] == 'CANCELLED' && $ipn['ipn_result']['hash_validation_status'] == 'SUCCESS') {
                if ($database_order_status == 'Pending') {
                    echo $ipn['gateway_return']['status'] . "<br>";
                    echo $ipn['ipn_result']['hash_validation_status'] . "<br>";
                   
                }
            } else {
                if ($database_order_status == 'Pending') {
                    echo "Order status not " . $ipn['gateway_return']['status'];
                   
                }
            }
            echo "<pre>";
            print_r($ipn);
        }
    }

}
