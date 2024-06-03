<?php
// NuEkd_l{tqev
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation','pdf'));
        $this->load->helper(array('url', 'language'));
		$this->load->model('Api_model');
        $this->load->helper("services");
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->model('appointment/appointment_model');
        $this->load->model('lab/lab_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('patient/patient_model');
        $this->load->model('sms/sms_model');
        $this->load->model('medicine/medicine_model');
        $this->load->model('settings/settings_model');
        $this->load->module('sms');
        $this->load->model('prescription/prescription_model');
        $this->load->helper('file_helper');
        $this->load->model('pharmacist/pharmacist_model');
        // $this->load->library('pdf');

        
    }
    // for download pdf of prescription by id getting data from prescription table
    function viewPrescription() {
        $id = $this->input->get('id');
        
        $data['prescription'] = $this->prescription_model->getPrescriptionById($id);
        // $data['settings'] = $this->settings_model->getSettings();
        $doctor = $this->doctor_model->getDoctorById($data['prescription'] ->doctor);
        $patient = $this->patient_model->getPatientById($data['prescription'] ->patient);
        // print_r($data['prescription'] );
        // print_r($data['prescription'] ->doctor );
        // die();
        if (!empty($patient)) {
            $patient_name= $patient->name; 

            $birthDate = strtotime($patient->birthdate);
            $birthDate = date("m-d-Y", $birthDate);
            // $birthDate = explode("/", $birthDate);
            // $age = date_diff(date_create($birthDate), date_create('today'))->y;
            $patient_age= $patient->age . " Year(s)";

            $patient_sex=$patient->sex;

            $prescription_date=date("d-m-Y", $data['prescription'] ->date);
        }else{
            $patient_name="";
            $patient_age="";
            $patient_sex="";
            $prescription_date="";
        }
        
        if (!empty($data['prescription'] ->medicine)) {
            $medicine = $data['prescription'] ->medicine;
            $medicine = explode("###", $medicine);
            $medicine_html='<table class="" style="width:100%;"> <thead> <th>Medicine</th> <th>Instruction</th> <th class="text-right">frequency</th> </thead> <tbody>';
            foreach ($medicine as $key => $value) {
                
                    $single_medicine = explode("***", $value);
                    $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name. " - " . $single_medicine[1];
                    $single_medicine_instruction=$single_medicine[3] . ' - ' . $single_medicine[4];
                    $single_medicine_frequency=$single_medicine[2];

                    $medicine_html.='<tr> <td class="">'.$single_medicine_name.' </td> <td class=""> '.$single_medicine_instruction.'</td> <td class="text-right">'.$single_medicine_frequency.'</td> </tr>';
            }
            $medicine_html.='</tbody> </table>';

        }else{
            $medicine_html="";
        }
        $url=base_url();
        $prescription_html='';
        if (!empty($data['prescription'] ->symptom)) {
            $prescription_html='
                <div>
                    <h6><strong>History: </strong> <br> <strong>'.$data['prescription'] ->symptom.'</strong></h6>
                </div>
            <hr>';
        }
        if (!empty($data['prescription'] ->note)) {
            $prescription_html.='
                <div>
                    <h6><strong>Note: </strong> <br> <strong>'.$data['prescription'] ->note.'</strong></h6>
            </div>
            <hr>';
        }
        if (!empty($data['prescription'] ->advice)) {
            $prescription_html.='
                <div>
                    <h6><strong>Advice: </strong> <br> <strong>'.$data['prescription'] ->advice.'</strong></h6>
                </div>
            <hr>';
        }
        $return ='<!DOCTYPE html>
                <html>
                <head>
                <title></title>
                        <link href="'.base_url().'common/extranal/css/prescription/prescription_view_1.css" rel="stylesheet">
                        <link rel="stylesheet" href="'.base_url().'common/css/bootstrap-reset.css">
                        <link rel="stylesheet" href="'.base_url().'common/extranal/css/prescription.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/prescription_view_1.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/bootstrap.min.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/style.css">
                        <style>

                        .panel-body {
                            background: #fff !important;
							padding:0px !important;
                        }
						td, th{font-size:12px;}
						.medicine_div {
                            padding-left: 0px !important;
                        }
                        body{
                            background: #fff !important;
                            padding:0px !important;
                        }
                        table{
                            background: #fff !important;
                            padding:0px !important;
                        }
					
                        </style>
                </head>
                <body style="background: #fff !important;padding: 0px;width: 100%;width: 640px;min-width:640px">
          
                <section class="">
                    
		            <div class="col-md-12 panel bg_container p-0" id="prescription">
                        <div class="bg_prescription">
                            <div class="panel-body">
				                <div class="col-md-12 text-center top_title mt-3" style="text-align: center;width:100%;">
                                    <img src="'.base_url().$this->settings_model->getSettings()->logo.'" height="60">
                                    <h5 class="mb-1"><i><b>A Step towards better health</b></i></h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 text-left top_logo " style="float: left;width: 60%;"> 
                                        <img src="'.base_url().'uploads/nama.png" height="100">
                                    </div>
                                    <div class="col-md-4  top_title" style="float: left;width: 40%;">
                                        <h4 class="mb-0"><i><b>'.$settings->title.'</b></i></h4>
                                        <p class="mb-0">'.$settings->address.'</p>
                                        <p class="mb-0">Tel : '.$settings->phone.'</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-new mt-1"></div>
				            <div class="row">
				                <div class="col-md-12 clearfix">
                                    <div class="col-md-3 left_panel" style="float: left;width: 23%;border-right:2px solid #666;">
                                        <div class="panel-body text-center">
                                            <h5 class="mt-4 mb-4"><strong>MEMBER</h5>
                                            <img src="'.base_url().'uploads/namacv.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/essm.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/ssm.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/aasect.jpg" style="width:100%;" class="mb-3">
                                            <img src="'.base_url().'uploads/asia.jpg" style="width:90%;" class="mb-3">
                                            <img src="'.base_url().'uploads/american.png" style="width:90%;"class="mb-3">                          
                                        </div>
                                    </div>
                                    <div class="col-md-9 w-7" style="float: left;width: 72%;">
                                        <div class="panel-body p-0">
                                            <div class="medicine_div mt-4">
                                                <table style="width:100%;margin-bottom:10px;">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Age</th> 
                                                        <th>Sex</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    <tr>
                                                        <td>'. $patient_name.' </td>
                                                        <td>'. $patient_age.' </td>
                                                        <td>'. $patient_sex.' </td>
                                                        <td>'. $prescription_date.' </td>
                                                    </tr>
                                                </table>
                                                '.$medicine_html.''.$prescription_html.'
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
				            </div>
				 
                            <div class="border-new"></div>
                                <div class="col-md-12 text-center" style="margin-top:20px;">
                                    <p>'.$this->settings_model->getSettings()->address.'</p>
                                    <p>Ph. '.$this->settings_model->getSettings()->phone.', '.$this->settings_model->getSettings()->phone.'</p>
                                    <p>Book your Appointment with us at Procto.com, E-mail: '.$this->settings_model->getSettings()->email.'</p>
                                </div>
                            </div>
                        </div>
                    </section>
               
                    </body>
                    </html>';
                   
                    $this->pdf->createPDF($return, 'prescription', true);
                    // echo $return;
                    // echo phpinfo();
    }
// for view of prescription by id getting data from prescription table
    function onlyviewPrescription() {
        $id = $this->input->get('id');
        
        $data['prescription'] = $this->prescription_model->getPrescriptionById($id);
        // $data['settings'] = $this->settings_model->getSettings();
        $doctor = $this->doctor_model->getDoctorById($data['prescription'] ->doctor);
        $patient = $this->patient_model->getPatientById($data['prescription'] ->patient);
        // print_r($data['prescription'] );
        // print_r($data['prescription'] ->doctor );
        // die();
        if (!empty($patient)) {
            $patient_name= $patient->name; 

            $birthDate = strtotime($patient->birthdate);
            $birthDate = date("m-d-Y", $birthDate);
            // $birthDate = explode("/", $birthDate);
            $age = date_diff(date_create($birthDate), date_create('today'))->y;
            $patient_age= $age . " Year(s)";

            $patient_sex=$patient->sex;

            $prescription_date=date("d-m-Y", $data['prescription'] ->date);
        }else{
            $patient_name="";
            $patient_age="";
            $patient_sex="";
            $prescription_date="";
        }
        
        if (!empty($data['prescription'] ->medicine)) {
            $medicine = $data['prescription'] ->medicine;
            $medicine = explode("###", $medicine);
            $medicine_html='<table class="" style="width:100%;"> <thead> <th>Medicine</th> <th>Instruction</th> <th class="text-right">frequency</th> </thead> <tbody>';
            foreach ($medicine as $key => $value) {
                
                    $single_medicine = explode("***", $value);
                    $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name. " - " . $single_medicine[1];
                    $single_medicine_instruction=$single_medicine[3] . ' - ' . $single_medicine[4];
                    $single_medicine_frequency=$single_medicine[2];

                    $medicine_html.='<tr> <td class="">'.$single_medicine_name.' </td> <td class=""> '.$single_medicine_instruction.'</td> <td class="text-right">'.$single_medicine_frequency.'</td> </tr>';
            }
            $medicine_html.='</tbody> </table>';

        }else{
            $medicine_html="";
        }
        $url=base_url();
        $prescription_html='';
        if (!empty($data['prescription'] ->symptom)) {
            $prescription_html='
                <div>
                    <h6><strong>History: </strong> <br> <strong>'.$data['prescription'] ->symptom.'</strong></h6>
                </div>
            <hr>';
        }
        if (!empty($data['prescription'] ->note)) {
            $prescription_html.='
                <div>
                    <h6><strong>Note: </strong> <br> <strong>'.$data['prescription'] ->note.'</strong></h6>
            </div>
            <hr>';
        }
        if (!empty($data['prescription'] ->advice)) {
            $prescription_html.='
                <div>
                    <h6><strong>Advice: </strong> <br> <strong>'.$data['prescription'] ->advice.'</strong></h6>
                </div>
            <hr>';
        }
        $return ='<!DOCTYPE html>
                <html>
                <head>
                <title></title>
                        <link href="'.base_url().'common/extranal/css/prescription/prescription_view_1.css" rel="stylesheet">
                        <link rel="stylesheet" href="'.base_url().'common/css/bootstrap-reset.css">
                        <link rel="stylesheet" href="'.base_url().'common/extranal/css/prescription.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/prescription_view_1.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/bootstrap.min.css">
                        <link rel="stylesheet" href="'.base_url().'common/css/style.css">
                        <style>
                        .panel-body {
                            background: #fff !important;
							padding:0px !important;
                        }
						td, th{font-size:12px !important;}
						.medicine_div {
                            padding-left: 0px !important;
                        }

                        body{
                            background: #fff !important;
                            padding:0px !important;
                        }
                        table{
                            background: #fff !important;
                            padding:0px !important;
                        }
					
                        </style>
                </head>
                <body style="background: #fff !important;padding: 0px;width: 640px;min-width:640px">
            
                <section class="">
                    
		            <div class="col-md-12 panel bg_container p-0" id="prescription">
                        <div class="bg_prescription">
                            <div class="panel-body">
				                <div class="col-md-12 text-center top_title mt-3" style="text-align: center;width:100%;">
                                    <img src="'.base_url().$this->settings_model->getSettings()->logo.'" height="60">
                                    <h5 class="mb-1"><i><b>A Step towards better health</b></i></h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 text-left top_logo  " style="float: left;width: 60%;"> 
                                        <img src="'.base_url().'uploads/nama.png" height="100">
                                    </div>
                                    <div class="col-md-4  top_title" style="float: left;width: 40%;">
                                        <h4 class="mb-0"><i><b>'.$this->settings_model->getSettings()->title.'</b></i></h4>
                                        <p class="mb-0">'.$this->settings_model->getSettings()->address.'</p>
                                        <p class="mb-0">Tel : '.$this->settings_model->getSettings()->phone.'</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-new mt-1"></div>
				            <div class="row">
				                <div class="col-md-12 clearfix">
                                    <div class="col-md-3 left_panel" style="float: left;width: 23%;">
                                        <div class="panel-body text-center">
                                            <h5 class="mt-4 mb-4"><strong>MEMBER</h5>
                                            <img src="'.base_url().'uploads/namacv.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/essm.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/ssm.jpg" height="80" class="mb-3">
                                            <img src="'.base_url().'uploads/aasect.jpg" style="width:100%;" class="mb-3">
                                            <img src="'.base_url().'uploads/asia.jpg" style="width:90%;" class="mb-3">
                                            <img src="'.base_url().'uploads/american.png" style="width:90%;" class="mb-3">                          
                                        </div>
                                    </div>
                                    <div class="col-md-9 w-7" style="float: left;width: 72%;">
                                        <div class="panel-body p-0">
                                            <div class="medicine_div mt-4">
                                                <table style="width:100%;margin-bottom:10px;">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Age</th> 
                                                        <th>Sex</th>
                                                        <th>Date</th>
                                                    </tr>
                                                    <tr>
                                                        <td>'. $patient_name.' </td>
                                                        <td>'. $patient_age.' </td>
                                                        <td>'. $patient_sex.' </td>
                                                        <td>'. $prescription_date.' </td>
                                                    </tr>
                                                </table>
                                                '.$medicine_html.''.$prescription_html.'
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
				            </div>
				 
                            <div class="border-new"></div>
                            
                                <div class="col-md-12 text-center" style="margin-top:20px;">
                                    <p>'.$this->settings_model->getSettings()->address.'</p>
                                    <p>Ph. '.$this->settings_model->getSettings()->phone.', '.$this->settings_model->getSettings()->phone.'</p>
                                    <p>Book your Appointment with us at Procto.com, E-mail: '.$this->settings_model->getSettings()->email.'</p>
                                </div>
                            </div>
                        </div>
                    </section>
                
                    </body>
                    </html>';
                   
                    // $this->pdf->createPDF($return, 'prescription', false);
                    echo $return;
                    // echo json_encode(['data'=>$return,'status'=>"true",'message'=>"Prescription view"]);
                    // echo phpinfo();
    }

        // API for location 
        function getLocation() {
            $query = $this->db->get('location');
            //header('Content-Type: application/json');
            $data = $query->result();
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
        }

         // API for Mode of Consultation table mode_of_consultance
         function getConsultationMode() {
            $this->db->where('status', 1);
            $query = $this->db->get('consultation_mode');            
            echo json_encode(['data'=>$query->result(),'status'=>"true",'message'=>"success"]);
        }

         // API for Type of Consultation table type_of_consultance
         function getConsultationType() {
           $this->db->where('consultation_mode_id',$_GET['mode']);
                $this->db->where('status', 1);
                $query = $this->db->get('consultation_type');
           // echo json_encode($query->result());
           echo json_encode(['data'=>$query->result(),'status'=>"true",'message'=>"success"]);

        }

    // API for login reworked 
    function login() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Login";
        //validate form input
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_message('min_length', 'Password must be at least 5 characters long');
        
        if ($this->form_validation->run() == true) {
            //check to see if the user is logging in
            //check for "remember me"
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'))) {
                //if the login is successful

              $deviceType=  $this->input->post('deviceType');
              $deviceTokken=  $this->input->post('deviceToken');
              $email=  $this->input->post('email');
              

                //redirect them back to the home page
              
               $userdata =  $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->result();
               $user_data=array();
                if(!empty($deviceTokken)){
                    $this->Api_model->device_update($deviceType,$deviceTokken,$email);

                    $this->db->where('user_id',$userdata[0]->id);
                    $this->db->where('device_type',$deviceType);
                    $this->db->where('device_token',$deviceTokken);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_result = $user_device_query->result();

                    if (count($user_device_result)>=1) {
                       
                    }else{
                        $user_device_data = array(
                            'user_id' => $userdata[0]->id,
                            'device_type' => $deviceType,
                            'device_token' => $deviceTokken,
                            'device_type' => $deviceType,
                            'status' => 1,
                        );
                        $user_device_insert_result=$this->db->insert('user_device', $user_device_data);
                    }
                }

               $this->db->where('ion_user_id',$userdata[0]->id);
                $doctor_query = $this->db->get('doctor');
                $doctor_data = $doctor_query->result();

                $this->db->where('user_id',$userdata[0]->id);
                $users_groups_query = $this->db->get('users_groups');
                $users_groups_data = $users_groups_query->result();

                $this->db->where('id',$users_groups_data[0]->group_id);
                $groups_query = $this->db->get('groups');
                $groups_data = $groups_query->result();

                $this->db->where('ion_user_id',$userdata[0]->id);
                $patient_query = $this->db->get('patient');
                $patient_data = $patient_query->result();

                $this->db->where('ion_user_id',$userdata[0]->id);
                $pharmacist_query = $this->db->get('pharmacist');
                $pharmacist_data = $pharmacist_query->result();
                
                if (count($doctor_data)>0) {
                    $user_data = $doctor_data[0];
                }
                if (count($patient_data)>0) {
                    $user_data = $patient_data[0];
                }
                if (count($pharmacist_data)>0) {
                    $user_data = $pharmacist_data[0];
                }
				$user_data->deviceToken = $deviceTokken;
               $user_data->deviceType = $deviceType;
               $key= $userdata[0]->id;
               $result= $this->Api_model->key_exists($key);
               if($result=='1') {
                $user_data->flag="Yes";
               }else {
                $user_data->flag="No";
                }
                if($user_data->img_url!='') {
                    $user_data->img_url=base_url().$user_data->img_url;
                }
                if($user_data->birthdate !='') {
                    $user_data->birthdate =date('d-m-Y',$user_data->birthdate);
                }
                $user_data->group_id =$groups_data[0]->id;
                $user_data->group_name =$groups_data[0]->name;
                
                echo json_encode(['data'=>$user_data,'status'=>"true",'message'=>"success"]);

            } else {
                //if the login was un-successful
                //redirect them back to the login page
                //echo json_encode([300,$this->ion_auth->errors()]);
                //    $message=  preg_replace("/\r|\n/", "", strip_tags($this->ion_auth->errors()));
                $message="Invalid email id or password";
                $user_data = new stdClass();
                echo json_encode([ 'data'=>$user_data,'status'=>"false",'message'=> $message]);

            }
        } else {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $empty_object = new stdClass();
            //$this->_render_page('auth/login', $data);
            echo json_encode(['data'=>$empty_object,'status'=>"false",'message'=>$data['message']]);

        }
    
    }
    //log the user out
    function logout() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $user_id=$_POST['user_id'];
        $device_token=$_POST['device_token'];

        $this->db->where('id',$user_id);
        $this->db->where('deviceToken',$device_token);
        $user_query = $this->db->get('users');
        $user_data = $user_query->result();

        if (count($user_data)>0) {
            $update_rows = array(
                'deviceToken' => ''
            );
            $this->db->where('id', $user_id);
            $result = $this->db->update('users', $update_rows); 
        }

        $this->db->where('user_id', $user_id);
        $this->db->where('device_token', $device_token);
        $user_device=$this->db->get('user_device');

        if (count($user_device)>0) {
            $this->db->where('user_id', $user_id);
            $this->db->where('device_token', $device_token);
            $this->db->delete('user_device');
        }

        ob_start();
        $logout = $this->ion_auth->logout();
        ob_clean();
        if ($logout) {
            $blank_object = new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>"true",'message'=>preg_replace("/\r|\n/", "", strip_tags($this->ion_auth->messages()))]);
        }
    }
   // check user is already exits or not from table user
    function check_user( ) {
        $this->db->where('email',$_GET['email']);
            $query = $this->db->get('users');
            $num = $query->num_rows();  
            if($num >=1){
                echo json_encode(['data'=>$num,'status'=>"true",'message'=>"User Found"]);

            } else {
                echo json_encode(['data'=>$num,'status'=>"false",'message'=>"No User Found"]);

            }
    }
    // API for Blood Group table blood_group
    function getBloodgroup() {

        $this->db->order_by("order asc");
        $query = $this->db->get('blood_group');

        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    // API for search Appointment List By Search input table appointment
    function getAppointmentListBySearch() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        // echo json_encode($_POST['search']);
        if ($_POST['search'] != null) {
            $search=$_POST['search'];
            $this->db->like('id', $search);
            $this->db->or_like('app_time_full_format', $search);
            $this->db->or_like('patientname', $search);
            $this->db->or_like('doctorname', $search);
        } 
		$query = $this->db->join('appointment_status','appointment_status.id =appointment.status' );
        $query = $this->db->get('appointment');
        $data=$query->result();
        $count=0;
        foreach ($data as $d) {
            if($d->date!='') {
                $data[$count]->date=date('d-m-Y h:i', $d->date);
            }
            if($d->add_date!='') {
                $data[$count]->add_date=date('d-m-Y h:i', $d->add_date);
            }
            if($d->registration_time!='') {
                $data[$count]->registration_time=date('d-m-Y h:i', $d->registration_time);
            }
            $count++;
        }
        if (count($data) >= 1) {
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"All Appointment List"]);
        }else{
            $blank_object=new stdClass();
            array_push($data, $blank_object);
            echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
        }
    }
    //change password table user
    function change_password() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('user_id',"Usier_id", 'required');
        $this->form_validation->set_rules('old',"Old password", 'required');
        $this->form_validation->set_rules('new',"New password",'required|min_length[5]');

        $this->form_validation->set_message('min_length', 'Password must be at least 5 characters long');

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            

            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        } else {
            $identity =$this->input->post('user_id');
            $old =$this->input->post('old');
            $new =$this->input->post('new');
            $old_password_matches = $this->ion_auth->hash_password_db($identity, $old);

            if ($old_password_matches === TRUE){
                if (strlen($new >=5)) {
                    $hashed_new_password  = $this->bcrypt->hash($new);
                    $data = array(
                        'password' => $hashed_new_password,
                    );
                    $this->db->where('id', $identity);
                    $result1 = $this->db->update('users', $data); 
                    if ($result1==1)
                    {
                        $return = new stdClass();
                        echo json_encode([ 'data'=>$return,'status'=>"true",'message'=> 'Password Reset Successful']);
                    }
                    else
                    {
                        $return = new stdClass();
                        echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Password Reset Unsuccessful']);
                    }
                }else
                {
                    $return = new stdClass();
                    echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Password must be at least 5 characters long']);
                }
                
            }else{
                $return = new stdClass();
                echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Old Password Not Match']);
            }
        }
    }
    //forgot password table user
    function forgot_password() {

        //setting validation rules by checking wheather identity is username or email
        if ($this->config->item('identity', 'ion_auth') == 'username') {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_username_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }


        if ($this->form_validation->run() == false) {
            //setup the input
            $data['email'] = array('name' => 'email',
                'id' => 'email',
            );

            if ($this->config->item('identity', 'ion_auth') == 'username') {
                $data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
            } else {
                $data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            //set any errors and display the form
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('auth/forgot_password', $data);
        } else {
            // get identity from username or email
            if ($this->config->item('identity', 'ion_auth') == 'username') {
                $identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
            } else {
                $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            }
            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') == 'username') {
                    $this->ion_auth->set_message('forgot_password_username_not_found');
                } else {
                    $this->ion_auth->set_message('forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/forgot_password", 'refresh');
            }

            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                //if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }
    // public function trigger_events($events)
	// {
	// 	if (is_array($events) && !empty($events))
	// 	{
	// 		foreach ($events as $event)
	// 		{
	// 			$this->trigger_events($event);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
	// 		{
	// 			foreach ($this->_ion_hooks->$events as $name => $hook)
	// 			{
	// 				$this->_call_hook($events, $name);
	// 			}
	// 		}
	// 	}
	// }
    // public function identity_check($identity = '')
	// {
	// 	$this->trigger_events('identity_check');

	// 	if (empty($identity))
	// 	{
	// 		return FALSE;
	// 	}

	// 	return $this->db->where($this->identity_column, $identity)
	// 	                ->count_all_results($this->tables['users']) > 0;
	// }
    // function hasshing password for using salt and sha1
    public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
           
		

		//bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
		      	
                    return $this->bcrypt->hash($password);
		}


		if ($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		}
		else
		{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}
    //reset password - final step for forgotten password table users
    public function resetPassword() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $identity =$this->input->post('id');
        $new =$this->input->post('new_password');
        if (strlen($new) >=5) {
            $new = $this->bcrypt->hash($new);
            $data = array(
                'password' => $new,
            );
            $this->db->where('id', $identity);
                $result1 = $this->db->update('users', $data); 
            if ($result1==1)
            {
                $return = new stdClass();
                echo json_encode([ 'data'=>$return,'status'=>"true",'message'=> 'Password Reset Successfully']);
            }
            else
            {
                $return = new stdClass();
                echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Password Reset Unsuccessful']);
            }
        }else{
            $return = new stdClass();
            echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Password must contain at least 5 digit']);
        }
        

		
	}

    //activate the user in table users
    function activate($id, $code = false) {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        } else {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user in table users
    function deactivate($id = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $data['csrf'] = $this->_get_csrf_nonce();
            $data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('auth/deactivate_user', $data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

  

    //api for edit a user profile using user table
    function edit_user($id) {
        $data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
            redirect('auth', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            //update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                );

                //update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }



                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin()) {
                    //Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }
                    }
                }

                //check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    //redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                } else {
                    //redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin()) {
                        redirect('auth', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        //display the edit user form
        $data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $data['user'] = $user;
        $data['groups'] = $groups;
        $data['currentGroups'] = $currentGroups;

        $data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );

        $this->_render_page('auth/edit_user', $data);
    }

	// API For create user in user table
	
	function create_user() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Create User";
     
        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('userName', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[' . $tables['users'] . '.email]',array('is_unique' => '%s address already exists'));
        
        $this->form_validation->set_rules('phoneno', 'Phone Number', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
       
        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('userName'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $age=date_diff(date_create($this->input->post('dob')), date_create('today'))->y;

            $additional_data = array(
                'first_name' => $this->input->post('userName'),
                'phone' => $this->input->post('phoneno'),
                'gender' => $this->input->post('gender'),
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );
            //check to see if we are creating the user
            //redirect them back to the admin page
            $userid=$this->ion_auth->register($username, $password, $email,5, $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            $p_data = array();
            date_default_timezone_set("Asia/Kolkata"); 
                $p_data = array(
                    'ion_user_id' => $userdata[0]->id,
                    'name' => $this->input->post('userName'),
                    'email' => strtolower($this->input->post('email')),
                    'doctor' => 1,
                    'birthdate' => null,
                    'phone' => $this->input->post('phoneno'),
                    'sex' => $this->input->post('gender'),
                    'age' => $age,
                    'weight' => null,
                    'merital_status' => null,
                    'spouse_name' => $this->input->post('spouse_name'),
                    'registration_time'=>time()
                );
				
                $p_result=$this->db->insert('patient', $p_data);

                $ud_data = array(
                    'user_id' => $userdata[0]->id,
                    'status' => 1,
                    'device_token' => $this->input->post('deviceToken'),
                    'device_type' => $this->input->post('deviceType'),
                );
                $ud_result=$this->db->insert('user_device', $ud_data);
                
                if ($p_result==true) {

                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();

                    $result1[0]->deviceToken = $this->input->post('deviceToken');
                    $result1[0]->deviceType = $this->input->post('deviceType');

                    $this->db->where('user_id',$userdata[0]->id);
                    $users_groups_query = $this->db->get('users_groups');
                    $users_groups_data = $users_groups_query->result();

                    $this->db->where('id',$users_groups_data[0]->group_id);
                    $groups_query = $this->db->get('groups');
                    $groups_data = $groups_query->result();

                    $result1[0]->group_id =$groups_data[0]->id;
                    $result1[0]->group_name =$groups_data[0]->name;
                    
                    // _register_email($email);
                    echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'Successfully Registered']);
                }else{
                    $result1=new stdClass();
                    echo json_encode(['data'=>$result1,'status'=>'true','message'=>'Please Check Your Details and Try Again']);
                }
            
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
	
	
// API to get Time slot from time slot table
    function getTimeslot() {
        //2009-10-22
        $timestamp = strtotime($_GET['date']);
         $day = date('l', $timestamp);
         // as requested by Shubham
        // $this->db->select('s_time');
        $this->db->where('Weekday',$day);
        $query = $this->db->get('time_slot');
        //return $query->result();
        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }


//Api to get  patinet List from patient table

function getPatient() {
    $this->db->order_by('id', 'desc');
    $query = $this->db->get('patient');
    $data =  $query->result();

    $count=0;
    foreach ($data as $d) {
        if($d->img_url!='') {

            $data[$count]->img_url=base_url().$d->img_url;            
        }
        $this->db->where('id',$d->ion_user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();
        if (empty($user_data[0]->created_on)) {
            $data[$count]->created_at=null;
        }else{
            $data[$count]->created_at=date('d-m-Y h:i A', $user_data[0]->created_on);
            // $data[$count]->birthdate=date('d-m-Y', $user_data[0]->birthdate);
        }
        if (empty($d->birthdate)) {
            $data[$count]->birthdate=null;
        }else{
            $data[$count]->birthdate=date('d-m-Y', $d->birthdate);
        }
        
        $count++;
    }
    
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}
// api for search patient by name for pateint dropdown from pateint table
function getPatientbyname() {
    $this->db->order_by('name', 'asc');
    $query = $this->db->get('patient');
    $data =  $query->result();

    $count=0;
    foreach ($data as $d) {
        if($d->img_url!='') {

            $data[$count]->img_url=base_url().$d->img_url;            
        }
        $this->db->where('id',$d->ion_user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();
        if (empty($user_data[0]->created_on)) {
            $data[$count]->created_at=null;
        }else{
            $data[$count]->created_at=date('d-m-Y h:i A', $user_data[0]->created_on);
            // $data[$count]->birthdate=date('d-m-Y', $user_data[0]->birthdate);
        }
        if (empty($d->birthdate)) {
            $data[$count]->birthdate=null;
        }else{
            $data[$count]->birthdate=date('d-m-Y', $d->birthdate);
        }
        $this->db->where('user_id', $d->ion_user_id);
                $this->db->where('status', 1);
                $user_device_query = $this->db->get('user_device');
                $user_device_data = $user_device_query->result();
                $data[$count]->device_tokens=$user_device_data;

        
        $count++;
    }
    
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}


//Api to get Appointment Status using appointment table

function getappointmentStatus() {
    $this->db->order_by('order', 'ASC');
    $query = $this->db->get('appointment_status');
    $data =  $query->result();
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}

    // API to get Price Based on Membership code from membership table
  
	function getPriceMembershipCode() {
       // $content = trim(file_get_contents("php://input"));
       // $_GET = json_decode($content, true);
        //2009-10-22
        $code= $_GET['membership_code'];
      
        //$this->db->where('code',$code);
        $this->db->where('code', $code);
        $query = $this->db->get('membership_details'); 
         
        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
//get available slot by location using time_slot, appointment and payment table  
    function getAvailableSlotByLocation() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $timestamp = strtotime($_POST['date']);
       // $timestamp = strtotime('2022-01-24');
       
        $day = date('l', $timestamp);
        $locationquery = $this->db->get('location');
        $data = $locationquery->result_array();
        $datanew=array();
        for($i=0;$i<count($data);$i++){
            $location_id= $data[$i]['id'];

            $this->db->where('location_id',$location_id);
            $this->db->where('Weekday',$day);
            $query = $this->db->get('time_slot');
            $all_time_slot = $query->result();
            $datanew[$data[$i]['name']] = $all_time_slot;
            $count=0;
            foreach ($datanew[$data[$i]['name']] as $ats) {
                
                
                date_default_timezone_set("Asia/Kolkata");
                if(strtotime($_POST['date']) == strtotime(date("d-m-Y"))){
                    if(strtotime($ats->s_time) <= strtotime(date("h:i A"))+ ($this->settings_model->getSettings()->mindays_for_booking * 60 * 60)){//+ (1 * 60 * 60)
                        // $datanew[$data[$i]['name']][$count]->booked_time="y";
                        // unset($datanew[$data[$i]['name']][$count]);
                        // array_splice($datanew[$data[$i]['name']],$count,1);
                        // array_shift($datanew[$data[$i]['name']]);
                        $datanew[$data[$i]['name']][$count]->booked="y";
                        $count++;
                        
                    }else{
                        $this->db->where('date',$_POST['date']);
                        $this->db->where('s_time',$ats->s_time);
                        $this->db->where('status',7);
                        $appointment = $this->db->get('appointment');
        
                        // $datanew[$data[$i]['name']] = $query->result();
        
                        $dataap[$i] =$appointment->result();
                        $num = $appointment->num_rows();  
                        if($num >=1){
                            $datanew[$data[$i]['name']][$count]->booked="y";
                            
                        }else{
                            $datanew[$data[$i]['name']][$count]->booked="n";
                        }
                        $count++;
                    }
                    
                }else{
                    $this->db->where('date',$_POST['date']);
                    // $this->db->where('doctor',$ats->doctor);
                    $this->db->where('s_time',$ats->s_time);
                    $this->db->where('status',7);
                    $appointment = $this->db->get('appointment');
    
                    // $datanew[$data[$i]['name']] = $query->result();
    
                    $dataap[$i] =$appointment->result();
                    $num = $appointment->num_rows();  
                    if($num >=1){
                        $datanew[$data[$i]['name']][$count]->booked="y";
                        
                    }else{
                        $datanew[$data[$i]['name']][$count]->booked="n";
                    }
                    $count++;
                }
                
            }

        }
        foreach ($datanew as $d) {
            foreach ($d as $da) {
                $da->s_time=strtoupper($da->s_time);
                $da->e_time=strtoupper($da->e_time);
            }
        }
        echo json_encode(['data'=>$datanew,'status'=>"true",'message'=>"All available time slot"]);
    }



// API for Doctor Details from users and doctor table
function getDoctorDetails(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $doctor_id = $this->input->post('doctor_id');
    if ($doctor_id==null) {
        $doctor_id  = 1;
    }
    // $doctor_id  = 1;
    $this->db->where('id',$doctor_id);
    $query = $this->db->get('doctor');
    $doctor = $query->result();
    if($doctor[0]->img_url!='') {
        $doctor[0]->img_url=base_url().$doctor[0]->img_url;
    }
    echo json_encode(['data'=>$doctor,'status'=>"true",'message'=>"success"]);
}

// API For Book Appointment by using appointment table
function book_appointment() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Book Appointment";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    //$this->form_validation->set_rules('patientId','PatientID', 'required');
    $this->form_validation->set_rules('mode_of_consultation','Mode Of Consultation', 'required');
    //$this->form_validation->set_rules('type_of_consultation', 'Type Of Consultation', 'required');
    // $this->form_validation->set_rules('location', 'Location', '');
    $this->form_validation->set_rules('date', 'Select Date', 'required');
    $this->form_validation->set_rules('time_slot', 'Select Time Slot', 'required');
    $this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
    $this->form_validation->set_rules('patient_email', 'Patient email', 'required');
    $this->form_validation->set_rules('patient_phone', 'patient phone', 'required');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
    $this->form_validation->set_rules('price', 'Price', 'required');
    $this->form_validation->set_rules('patient_type', 'Patient Type', 'required');
    
    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $mode_of_consultation = $this->input->post('mode_of_consultation');
        $type_of_consultation = $this->input->post('type_of_consultation');
        $location_id = $this->input->post('location');
        $date = $this->input->post('date');
        $time_slot = $this->input->post('time_slot');
        $patient_id = $this->input->post('patient_id');
        $time_slot1 = str_replace(["TO","To"],"to",$time_slot);
        $time_slot_explode= explode('to', $time_slot1);

        // print_r($time_slot_explode);
        // die();

        $s_time = trim($time_slot_explode[0]);

        $e_time = trim($time_slot_explode[1]);


		if (empty($patient_id)) {
            date_default_timezone_set("Asia/Kolkata"); 
            $patient_data = array(
                // 'patient' => $this->input->post('patientId'),
                'name' => $this->input->post('patient_name'),
                'email' => $this->input->post('patient_email'),
                'phone' => $this->input->post('patient_phone'),
                'registration_time'=>time()
    
            );
            $this->db->insert('patient', $patient_data);
            $patient_id = $this->db->insert_id();
        }

        date_default_timezone_set("Asia/Kolkata"); 
        $additional_data = array(
            'patient' => $patient_id,
            'mode_of_consultation' => $this->input->post('mode_of_consultation'),
            'type_of_consultation' => $this->input->post('type_of_consultation'),
            'location_id' => $this->input->post('location_id'),
            'date' => date('d-m-Y', strtotime($date)),
            'time_slot' => $this->input->post('time_slot'),
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 1,
            'price' => $this->input->post('price'),
            'registration_time' => time(),
            'patient_type' => $this->input->post('patient_type')

        );
            $doctor_id = $this->input->post('doctor_id');
            $this->db->where('id', $doctor_id);
            $query1 = $this->db->get('doctor');
            $datas = $query1->result_array();
            $doc_user_id = $datas[0]['ion_user_id'];

            $this->db->where('id', $doc_user_id);
            $query2 = $this->db->get('users');
            $datas_doc = $query2->result_array();
            
            $this->db->insert('appointment', $additional_data);
            $inserted_id = $this->db->insert_id();

            $this->db->where('id', $inserted_id);
            $query2 = $this->db->get('appointment');
            $result_data = $query2->result();

            $this->db->where('user_id', $doc_user_id);
                $this->db->where('status', 1);
                $user_device_query = $this->db->get('user_device');
                $user_device_data = $user_device_query->result();
               
                $data_addedd = array
                (
                    'type'   => 'Booking',
                    'title'   => 'New Appointment Booking Request',
                    'id'     => $result_data[0]->id,
                    'user_id' => $doc_user_id,
                    'patient_id'     => $result_data[0]->patient,
                    'msg'  => "New Booking Request #".$result_data[0]->id ." by ".$this->input->post('patient_name').".",
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                    $n_data = array(
                        'user_id' => $doc_user_id,
                        'type' => $data_addedd['type'],
                        'title' => $data_addedd['title'],
                        'message' => $data_addedd['msg'],
                        'profile_img' => $datas[0]['img_url'],
                        'appointment_id' => $result_data[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
                    );
                            
                $n_result=$this->db->insert('notification', $n_data);

                foreach ($user_device_data as $ud) {
                
                    $deviceToken = $ud->device_token;
                    $deviceType = $ud->device_type;

                    _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
                }

        echo json_encode(['data'=>$result_data[0] ,'status'=>'true','message'=>"Your Booking Request #".$result_data[0]->id ." has been sent to the doctor. Soon you will get a notification of it's approval so that you can proceed to payment."]);
        
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

    public function send(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $jistimeetinfo=$_POST['data'];
        $jistimeetinfo['registration_ids']=$_POST['registration_ids'][0];
            $json_data = [
                "to" => $_POST['registration_ids'][0],
                "notification" => $jistimeetinfo,
                "data" => $jistimeetinfo,
                ];
        $data = json_encode($json_data);
        //FCM API end-point
         $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = 'AAAA3PrYRQU:APA91bHamDe8dNAWgZx8f5cfPRncNY_UCmJ8ixrXZ00X804O6f8DafNrRksCufx5zqu4GzGTt6En52ISkboSrU4aUIXRhC6W_YNcF9L1ZNwS7FUPRREFR2zQ_GuE_yKt7z2SsajL3AED';
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        echo json_encode(['status'=>'true','message'=>'success']);
		
	}

function  ApproveOrReject(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    //2009-10-22
    $status= $_POST['status_id'];
    $appointment_id= $_POST['appointment_id'];
    $description= $_POST['description'];
    $datas = array(
        'status' => $status,'description' => $description
        );
    $this->db->where('id',$appointment_id);
    $check_query = $this->db->get('appointment');
    $check_appointment = $check_query->result();

    $this->db->where('date',$check_appointment[0]->date);
    $this->db->where('time_slot',$check_appointment[0]->time_slot);
    $this->db->where('status',2);
    $check_query2 = $this->db->get('appointment');
    $check_appointment2 = $check_query2->result();

    if (count($check_appointment2) > 0) {
        $data_result=new stdClass();
        echo json_encode(['data'=>$data_result,'status'=>"false",'message'=>"Booking found already"]);
    }else{
        $return=$this->db->update('appointment', $datas, array('id' => $appointment_id));

		if ($return==1){
            $this->db->where('id',$appointment_id);
            $query = $this->db->get('appointment');
            $appointment_details = $query->result();

            $this->db->where('id',$appointment_details[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$patient_data[0]->ion_user_id);
            $user_query = $this->db->get('users');
            $user_data =  $user_query->result();

            $this->db->where('id',$_POST['status_id']);
            $status_query = $this->db->get('appointment_status');
            $status_data =  $status_query->result();

            $this->db->where('id',$appointment_details[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();

            $this->db->where('booking_id',$appointment_id);
            $payment_query = $this->db->get('payment');
            $payment_details= $payment_query->result();

            $data_result=array();
            $data_result['patientid']=$appointment_details[0]->patient;
            $data_result['patientname']=$patient_data[0]->name;
            $data_result['BookingDate']=date_format($appointment_details[0]->date,"d-m-Y");
            $data_result['BookingTime']=$appointment_details[0]->time_slot;
            $data_result['LocationName']=$location_data[0]->name;
            $data_result['LocationId']=$appointment_details[0]->location_id;
            $data_result['statusId']=$appointment_details[0]->status;
            $data_result['StatusName']=$status_data[0]->status_name;
            $data_result['user_id']=$patient_data[0]->ion_user_id;
            if($patient_data[0]->img_url!='') {
                $patient_data[0]->img_url=base_url().$patient_data[0]->img_url;
            }
            $data_result['profile_img']=$patient_data[0]->img_url;
            if(count($payment_details)>=1) {
                $payment_status=$payment_details[0]->order_status;
            }else{
                $payment_status=null;
            }
            $data_result['payment_status']=$payment_status;
            $data_result['price']=$appointment_details[0]->price;
            


            $this->db->where('user_id', $patient_data[0]->ion_user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();

            if ($appointment_details[0]->status==2) {
               
                $data_addedd = array
                (
                    'type'   => 'approved',
                    'id'     => $appointment_details[0]->id,
                    'user_id' => $patient_data[0]->ion_user_id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'payment_status'     => $payment_status,
                    'price'     => $appointment_details[0]->price,
                    'title'=>'Appointment Approved',
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'title'=>'Appointment Approved',
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => time(),
                    'read_status' => 0
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"Appointment Approved"]);
            }
            if ($appointment_details[0]->status==3) {
                
                $data_addedd = array
                (
                    'type'   => 'rejected',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'user_id' => $patient_data[0]->ion_user_id,
                    'title'=>'Appointment Rejected',
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Because '.$description.'.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'title'=>"Appointment Rejected",
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => time(),
                    'read_status' => 0
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"Appointment Rejected"]);
            }

            foreach ($user_device_data as $ud) {
                
                $deviceToken = $ud->device_token;
                $deviceType = $ud->device_type;

                _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);  
            }         
        }else{
            $data_result=new stdClass();
            echo json_encode(['data'=>$data_result,'status'=>"false",'message'=>"Try again"]);
        }

    }
    

   
}

// API For create user Google Signing 
function googlesigningdetails() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Create User From Google Signing in";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    $this->form_validation->set_rules('userName', 'User Name', 'required');
    $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
    $this->form_validation->set_rules('deviceToken', '', '');
    $this->form_validation->set_rules('deviceType', '', '');
    if ($this->form_validation->run() == true) {
        $this->db->where('email',$this->input->post('email'));
        $query = $this->db->get('users');
        $num = $query->num_rows();  
        if($num >=1){
            $data  = array(
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );
            $this->db->update('users', $data, array('email' => $this->input->post('email')));
            $userdata =  $this->ion_auth->where('email',$this->input->post('email'))->users()->result();

                $this->db->where('user_id',$userdata[0]->id);
                $users_groups_query = $this->db->get('users_groups');
                $users_groups_data = $users_groups_query->result();

                $this->db->where('user_id',$userdata[0]->id);
                $this->db->where('device_type',$this->input->post('deviceType'));
                $this->db->where('device_token',$this->input->post('deviceToken'));
                $user_device_query = $this->db->get('user_device');
                $user_device_result = $user_device_query->result();

                if (count($user_device_result)>=1) {
                       
                }else{

                    $user_device_data = array(
                        'user_id' => $userdata[0]->id,
                        'device_type' => $this->input->post('deviceType'),
                        'device_token' => $this->input->post('deviceToken'),
                        'status' => 1,
                    );
                    $ud_result=$this->db->insert('user_device', $user_device_data);
                }

                if ($users_groups_data[0]->group_id==5) {
                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();

                    $result1[0]->deviceToken = $this->input->post('deviceToken');
                    $result1[0]->deviceType = $this->input->post('deviceType');

                    $this->db->where('id',$users_groups_data[0]->group_id);
                    $groups_query = $this->db->get('groups');
                    $groups_data = $groups_query->result();

                    if($result1[0]->img_url!='') {
                        $result1[0]->img_url=base_url().$result1[0]->img_url;
                    }
                    if($result1[0]->birthdate !='') {
                        $result1[0]->birthdate =date('d-m-Y',$result1[0]->birthdate);
                    }
                    if($result1[0]->registration_time !='') {
                        $result1[0]->registration_time =date('d-m-Y h:i A',$result1[0]->registration_time);
                    }

                    $result1[0]->group_id =$groups_data[0]->id;
                    $result1[0]->group_name =$groups_data[0]->name;
                    echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'User Already Register']);
                }else{
                    $result1=new stdClass();
                echo json_encode(['data'=>$result1,'status'=>'true','message'=>'You are not authorised user']);
                }
        } else {
            $username = strtolower($this->input->post('userName'));
            $email = strtolower($this->input->post('email'));
            $password='';

            $additional_data = array(
                'first_name' => $this->input->post('userName'),
                'email' => $this->input->post('email'),
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
                
            );

            // $userid=$this->ion_auth->register($username, $password,5, $email, $additional_data);
            $u_result=$this->db->insert('users', $additional_data);
            $inserted_id = $this->db->insert_id();
            $user_device_data = array(
                'user_id' => $inserted_id,
                'device_type' => $this->input->post('deviceType'),
                'device_token' => $this->input->post('deviceToken'),
                'status' => 1,
            );
            $ud_result=$this->db->insert('user_device', $user_device_data);

            $userdata =  $this->ion_auth->where('id',$inserted_id)->users()->result();
            $this->db->where('ion_user_id',$inserted_id );
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){

            } else {
                date_default_timezone_set("Asia/Kolkata"); 
                $p_data = array(
                    'name' => $this->input->post('userName'),
                    'email' => $this->input->post('email'),
                    'ion_user_id' => $inserted_id,
                    'registration_time'=>time()
                );
                $p_result=$this->db->insert('patient', $p_data);
            }
            $g_result=$this->db->insert('users_groups', ['user_id'=>$inserted_id ,'group_id'=>5]);
            if ($u_result==true) {

                $this->db->where('ion_user_id',$inserted_id);
                $query1 = $this->db->get('patient');
                $result1= $query1->result();

                $this->db->where('user_id',$inserted_id);
                $users_groups_query = $this->db->get('users_groups');
                $users_groups_data = $users_groups_query->result();

                $result1[0]->deviceToken = $this->input->post('deviceToken');
                $result1[0]->deviceType = $this->input->post('deviceType');

                $this->db->where('id',$users_groups_data[0]->group_id);
                $groups_query = $this->db->get('groups');
                $groups_data = $groups_query->result();

                if($result1[0]->img_url!='') {
                    $result1[0]->img_url=base_url().$result1[0]->img_url;
                }
                if($result1[0]->birthdate !='') {
                    $result1[0]->birthdate =date('d-m-Y',$result1[0]->birthdate);
                }
                if($result1[0]->registration_time !='') {
                    $result1[0]->registration_time =date('d-m-Y h:i A',$result1[0]->registration_time);
                }

                $result1[0]->group_id =$groups_data[0]->id;
                $result1[0]->group_name =$groups_data[0]->name;
                
                // _register_email($email);
                echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'Successfully Registered']);
            }else{
                $result1=new stdClass();
                echo json_encode(['data'=>$result1,'status'=>'true','message'=>'Please Check Your Details and Try Again']);
            }

            // $this->db->where('user_id',$inserted_id );
            // $users_groups_query = $this->db->get('users_groups');
            // $users_groups_data = $users_groups_query->result();

            // $this->db->where('id',$users_groups_data[0]->group_id);
            // $groups_query = $this->db->get('groups');
            // $groups_data = $groups_query->result();

            // $userdata[0]->group_id =$groups_data[0]->id;
            // $userdata[0]->group_name =$groups_data[0]->name;
            // $message = $this->ion_auth->messages();

            // echo json_encode(['data'=>$userdata,'status'=>'true','message'=>'Registration Successfully completed']);

        }
         
    } else {
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}



//-------------------------Sunil -------------------------------------------------

    // get appointment list by Doctor Id
    function getAppointmentBydoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $id    = $_POST['id'];
        $data['patients'] = $this->Api_model->getDoctorId($id);

        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    
       
    }

// as per shubahm sir instructions
function getAppointmentByPatinetId() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    date_default_timezone_set("Asia/Kolkata");
    $id    = $_POST['id'];
    $data['patients'] = $this->Api_model->getPatient($id);
    $count=0;
    foreach ($data['patients'] as $d) {
        $this->db->where('booking_id',$d->bookingId);
        $this->db->order_by('id', 'desc');
        $query1 = $this->db->get('payment');
        $txn= $query1->result();

        $data['patients'] [$count]->txnId=$txn[0]->txnID;
        $data['patients'] [$count]->txn_amount=$txn[0]->amount;
        if ($txn[0]->created_at!=null) {
            $data['patients'] [$count]->txn_date=date("d-m-Y h:i A",$txn[0]->created_at);
        }else{
            $data['patients'] [$count]->txn_date=null;
        }
            $data['patients'] [$count]->date=$d->date;
        
        if ($d->created_at!=null) {
            $data['patients'] [$count]->created_at=date("d-m-Y h:i A",$d->created_at);
        }else{
            $data['patients'] [$count]->created_at=null;

        }

        $this->db->where('id',$d->bookingId);
        $query1 = $this->db->get('appointment');
        $appointment= $query1->result();
        $data['patients'] [$count]->remarks=$appointment[0]->remarks;

        $this->db->where('id',$appointment[0]->doctor);
        $query1 = $this->db->get('doctor');
        $doctor= $query1->result();
        $data['patients'] [$count]->doctorname=$doctor[0]->name;

        $this->db->where('appointment_id',$appointment[0]->id);
        $this->db->where('type',8);
        $cancellation_requestquery1 = $this->db->get('cancellation_request');
        $cancellation_request= $cancellation_requestquery1->result();
        if (count($cancellation_request)>=1) {
            $data['patients'] [$count]->cancellation_request='y';
        }else{
            $data['patients'] [$count]->cancellation_request='n';
        }
        
        
        $this->db->where('appointment_id',$appointment[0]->id);
        $this->db->where('type',6);
        $postpone_requestquery1 = $this->db->get('cancellation_request');
        $postpone_request= $postpone_requestquery1->result();
        if (count($postpone_request)>=1) {
            $data['patients'] [$count]->postpone_request='y';
        }else{
            $data['patients'] [$count]->postpone_request='n';
        }
            if(strtotime($appointment[0]->date) <= strtotime(date("d-m-Y"))){
                $data['patients'] [$count]->postpone="n";
                $data['patients'] [$count]->cancel="n";
            }else{
                $data['patients'] [$count]->postpone="y";
                $data['patients'] [$count]->cancel="y";
            }
            if(strtotime($appointment[0]->date) == strtotime(date("d-m-Y"))){
                if(strtotime($appointment[0]->s_time) <= strtotime(date("h:i A"))+ (1 * 60 * 60)){
                    $data['patients'] [$count]->postpone="n";
                    $data['patients'] [$count]->cancel="n";
                    // unset($datanew[$data[$i]['name']][$count]);
                }
                else{
                    $data['patients'] [$count]->postpone="y";
                    $data['patients'] [$count]->cancel="y";
                }
            }

        $count++;
    }
    // $this->db->where('booking_id',$data['patients'][0]->bookingId);
    // $this->db->order_by('id', 'desc');
    // // $this->db->where('order_id',$data['patients'][0]->order_id);
    // $query1 = $this->db->get('payment');
    // $d['txn']= $query1->result();
    // $transection_id=$d['txn'][0]->txnID;

    // $data['patients'][0]->txnId=$d['txn'][0]->txnID;
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

   
}

    //   proceed order 
    public function proceed_order() {
        date_default_timezone_set("Asia/Kolkata"); 
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id =  $_POST['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $booking_id = $_POST['booking_id'];
        $amount = $_POST['amount'];
        $order_id = rand(111, 999) . substr(md5(time()), 5);
        $order_status = $_POST['order_status'];
        switch ($order_status) {
            case 1:
                $order_status_text = "pending";
                break;
            case 2:
                $order_status_text = "Approved";
                break;
                case 3:
                $order_status_text = "Reject";
                break;
                case 4:
                $order_status_text = "Completed";
                break;
                case 5:
                $order_status_text = "No Show";
                break;
                case 6:
                $order_status_text = "Postpone";
                break;
                case 7:
                $order_status_text = "Confirmed";
                break;
            default:
                $order_status_text = "pending";
                break;

        }
        $pay_via = $_POST['pay_via'];
        
        $array = array(
            'patient' => $patient_id,
            'doctor' => $doctor_id,
            'order_id' => $order_id,
            'amount' => $amount,
            'booking_Id' => $booking_id,
            'payment_type' => $pay_via,
            'order_status' => $order_status_text,
            'created_at'=>time()
           

        );
        $this->db->insert('payment',$array);
        $update_rows = array(
            'status' => $order_status

        );
        echo json_encode(['data'=>$order_id,'status'=>"true",'message'=>"Order Successfully."]);
        // $this->db->where('id', $booking_id);
        // $result = $this->db->update('appointment', $update_rows); 

              
        
    }
   
    // complete transaction
    public function complete_transaction(){
          $content = trim(file_get_contents("php://input"));
          $_POST = json_decode($content, true);
          $order_id =  $_POST['order_id'];
          $transaction_id = $_POST['transaction_id'];
        //   if ($_POST['order_status']=='Completed') {
        //     $order_status = 'confirmed';
        //   }else{
            $order_status = $_POST['order_status'];
        //   }
        //   $transaction_id = $this->input->post('transaction_id');
          $this->db->select("MAX(DISTINCT(invoice_no)) as invoice_no");
          $invoice_no = ++$this->db->get("payment")->row()->invoice_no;
  
          $data = array(
              'invoice_no' => $invoice_no,
              'txnID' => $transaction_id,
              'order_status' =>  $order_status,
              'created_at'=>time()
          );
          $this->db->where('order_id', $order_id);
          $this->db->update('payment', $data);
            

            $this->db->where('order_id',$order_id);
            $query1 = $this->db->get('payment');
            $payment= $query1->result();

            $update_rows = array(
                'status' => 7
            );

            $this->db->where('id', $payment[0]->booking_id);
            $result3 = $this->db->update('appointment', $update_rows); 

            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"Your Payment has been Successfully Processed.\n Your Appointment #".$payment[0]->booking_id." has been Confirmed"]);
            
            $booking_id=$payment[0]->booking_id;

            $this->db->where('id',$booking_id);
            $query = $this->db->get('appointment');
            $appointment_details = $query->result();
    
            $this->db->where('id',$appointment_details[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();
    
            $this->db->where('id',$patient_data[0]->ion_user_id);
            $user_query = $this->db->get('users');
            $user_data =  $user_query->result();
    
            $this->db->where('id',$appointment_details[0]->doctor);
            $doctor_query = $this->db->get('patient');
            $doctor_data =  $doctor_query->result();
    
            $this->db->where('id',$doctor_data[0]->ion_user_id);
            $doctor_user_query = $this->db->get('users');
            $doctor_user_data =  $doctor_user_query->result();
    
            $this->db->where('id',$_POST['status_id']);
            $status_query = $this->db->get('appointment_status');
            $status_data =  $status_query->result();
    
            $deviceToken = $user_data[0]->deviceToken;
            $deviceType = $user_data[0]->deviceType;
            $doctor_deviceToken = $doctor_user_data[0]->deviceToken;
            $doctor_deviceType = $doctor_user_data[0]->deviceType;
    
            $this->db->where('user_id', $patient_data[0]->ion_user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();
    
                    $data_addedd = array
                    (
                        'type'   => 'Payment Confirmed',
                        'id'     => $appointment_details[0]->id,
                        'patient_id'     => $appointment_details[0]->patient,
                        'user_id' => $patient_data[0]->ion_user_id,
                        'title'=>"Payment Confirmed",
                        'msg'  => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                    );
                    $n_data = array();
                    date_default_timezone_set("Asia/Kolkata"); 
                    $n_data = array(
                        'user_id' => $patient_data[0]->ion_user_id,
                        'type' => $data_addedd['type'],
                        'message' => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                        'title'=>"Payment Confirmed",
                        'profile_img' => $patient_data[0]->img_url,
                        'appointment_id' => $appointment_details[0]->id,
                        'created_at' => time(),
                        'read_status' => 0,
                        'msg' => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.'
                    );
                            
                    $n_result=$this->db->insert('notification', $n_data);
    
            foreach ($user_device_data as $ud) {
    
                $deviceToken = $ud->device_token;
                $deviceType = $ud->device_type;
                
                    _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd); 
            }
             // doctor notification
            $this->db->where('user_id', $doctor_data[0]->ion_user_id);
            $this->db->where('status', 1);
            $doctor_device_query = $this->db->get('user_device');
            $doctor_device_data = $doctor_device_query->result();
            
                    $doctor_data_addedd = array
                    (
                        'type'   => 'Payment Confirmed',
                        'title'=>"Payment Confirmed",
                        'id'     => $appointment_details[0]->id,
                        'patient_id'     => $appointment_details[0]->patient,
                        'user_id' => $doctor_data[0]->ion_user_id,
                        'msg'  => 'Payment of #'.$appointment_details[0]->id .' has been Successfully Confirmed.',
                    );
                    $doctor_n_data = array();
                    date_default_timezone_set("Asia/Kolkata"); 
                    $doctor_n_data = array(
                        'user_id' => $doctor_data[0]->ion_user_id,
                        'type' => $doctor_data_addedd['type'],
                        'message' => 'Payment of #'.$appointment_details[0]->id .' has been Successfully Confirmed.',
                        'title'=>"Payment Confirmed",
                        'profile_img' => $doctor_data[0]->img_url,
                        'appointment_id' => $appointment_details[0]->id,
                        'created_at' => time(),
                        'msg'=>'Payment of #'.$appointment_details[0]->id .' has been Successfully Confirmed.'
                    );
                            
                    $doctor_n_result=$this->db->insert('notification', $doctor_n_data);
                
    
            foreach ($doctor_device_data as $dd) {
    
                $doctor_deviceToken = $dd->device_token;
                $doctor_deviceType = $dd->device_type;
                
                    _send_fcm_notification($doctor_deviceToken,$doctor_deviceType,'',$doctor_data_addedd); 
            }
  
    }


//  get Appointment list  26-01-2022
public function getAppointmentlist() {

    //$content = trim(file_get_contents("php://input"));
    //$_POST = json_decode($content, true);
    //  $id    = $_POST['id'];
    date_default_timezone_set("Asia/Kolkata"); 
    $data['patients'] = $this->Api_model->getAllPatient();
    $count=0;
    foreach ($data['patients'] as $d) {
        if ($d->patient_profile_photo!=null) {
            $data['patients'] [$count]->patient_profile_photo=base_url().$d->patient_profile_photo;
        }else{
            $data['patients'] [$count]->patient_profile_photo=null;

        }
        if ($d->created_at!=null) {
            $data['patients'] [$count]->created_at=date("d-m-Y h:i A",$d->created_at);
        }else{
            $data['patients'] [$count]->created_at=null;

        }
        if ($d->patient_age==null) {
            $data['patients'] [$count]->patient_age="null";
        }
        if ($d->patient_weight==null) {
            $data['patients'] [$count]->patient_weight="null";
        }
        if ($d->patient_merital_status==null) {
            $data['patients'] [$count]->patient_merital_status="null";
        }
        if ($d->patient_spouse_name==null) {
            $data['patients'] [$count]->patient_spouse_name="null";
        }
        $count++;
    }
    if (count($data['patients']) >= 1) {
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"List of All Appointment"]);
    }else{
        $blank_object=new stdClass();
        array_push($data['patients'], $blank_object);
        echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
    }

}
//  get Casehistory list  
public function getCasehistorylist() {

    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $patient_id    = $_POST['id'];
    $data['medical_histories'] = $this->Api_model->getMedicalHistoryByAllPatientId($patient_id);
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);


}

//  get Prescription list 
public function getPrescriptionlist() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id    = $_POST['id'];
        $data['Prescriptionlist'] = $this->Api_model->getPrescriptionByAllPatientId($patient_id);
        $count=0;
        foreach ($data['Prescriptionlist'] as $d) {

            $this->db->where('id', $d->patient);
            $query = $this->db->get('patient');
            $patient=$query->result();
            
            if ($d->state==null) {
                $data['Prescriptionlist'][$count]->state="null";
            }
            if ($d->dd==null) {
                $data['Prescriptionlist'][$count]->dd="null";
            }
            if ($d->validity==null) {
                $data['Prescriptionlist'][$count]->validity="null";
            }
            if ($patient[0]->name==null) {
                $data['Prescriptionlist'][$count]->patientname="null";
            }else{
                $data['Prescriptionlist'][$count]->patientname=$patient[0]->name;
            }
            if ($patient[0]->email==null) {
                $data['Prescriptionlist'][$count]->patient_email="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_email=$patient[0]->email;
            }
            if ($patient[0]->address==null) {
                $data['Prescriptionlist'][$count]->patient_address="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_address=$patient[0]->address;
            }
            if ($patient[0]->phone==null) {
                $data['Prescriptionlist'][$count]->patient_phone="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_phone=$patient[0]->phone;
            }
            $data['Prescriptionlist'][$count]->date=date('d-m-Y', $d->date);
            
            
                $this->db->where('prescription_id',$d->id);
                $dispatches_query = $this->db->get('dispatches');
                $dispatches_data =  $dispatches_query->result();
                if (!empty($dispatches_data)) {
                    $this->db->where('id',$dispatches_data[0]->company_id);
                    $shipping_company = $this->db->get('shipping_companies')->result();

                    $data['Prescriptionlist'][$count]->company_id=$shipping_company[0]->id;
                    $data['Prescriptionlist'][$count]->company_name=$shipping_company[0]->name;
                    $data['Prescriptionlist'][$count]->traking_id = $dispatches_data[0]->tracking_id;
                    $data['Prescriptionlist'][$count]->traking_url=$shipping_company[0]->url."?id=".$dispatches_data[0]
                    ->tracking_id;
                }else{
                    $data['Prescriptionlist'][$count]->traking_id="null";
                    $data['Prescriptionlist'][$count]->traking_url="null";
                }
                
                $data['Prescriptionlist'][$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $data['Prescriptionlist'][$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $data['Prescriptionlist'][$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;
            $count++;
        }
        if (count($data['Prescriptionlist']) >= 1) {
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"List of All Prescription"]);
        }else{
            $blank_object=new stdClass();
            array_push($data['Prescriptionlist'], $blank_object);
            echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
        }
}

//  get Labreport list 
public function getLabreportlist() {

    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $patient_id    = $_POST['id'];
    $data['lablist'] = $this->Api_model->getLabByAllId($patient_id);
    $count=0;
    $count2=0;

    $this->db->where('id',$patient_id);
    $this->db->order_by('id','desc');
    $patient_query = $this->db->get('patient');
    $patient_data =  $patient_query->result();

    $this->db->where('user_id',$patient_data[0]->ion_user_id);
    $this->db->where('file_upload',1);
    $message_query = $this->db->get('message');
    $message_data =  $message_query->result();
    if (count($message_data) >= 1) {
        $today_date = date('d-m-Y');
        $end_date = date('d-m-Y', strtotime('-3 days', strtotime($today_date)));

        foreach ($message_data as $m) {
            $message_date=date('d-m-Y', $m->created_at);
            if (($message_date > $end_date) && ($message_date <= $today_date)){
                $count2++;
            }
        }
        if ($count2 >= 1) {
            $data['upload_status']="1";
        }else{
            $data['upload_status']="0";
        }
    }else{
        $data['upload_status']="0";
    }

    
    
    

    foreach ($data['lablist'] as $l) {
        $data['lablist'][$count]->date=date("d-m-Y",$l->date);
        if (empty($l->created_at)) {
            $data['lablist'][$count]->created_at=null;
        }else{
            $data['lablist'][$count]->created_at=date("d-m-Y h:i A",$l->created_at);
        }
        
        $data['lablist'][$count]->url=base_url()."api/viewlabreport?id=".$l->id;

        $this->db->where('id',$l->template_id);
        $template_query = $this->db->get('template');
        $template_data =  $template_query->result();
        $data['lablist'][$count]->template_name=$template_data[0]->name;
        $count++;
    }
    if (count($data['lablist']) >= 1) {
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"List of All Lablist"]);
    }else{
        $blank_object=new stdClass();
        array_push($data['lablist'], $blank_object);
        echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
    }
    // echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}
//  get Document list 
public function getDocumentlist() {

    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $patient_id    = $_POST['id'];
    $data['document'] = $this->Api_model->getdocument($patient_id);

    $this->db->where('id',$patient_id);
    $patient_query = $this->db->get('patient');
    $patient_data =  $patient_query->result();
    
    $this->db->where('user_id',$patient_data[0]->ion_user_id);
    $this->db->where('file_upload',1);
    $message_query = $this->db->get('message');
    $message_data =  $message_query->result();
    if (count($message_data) >= 1) {
        $today_date = date('d-m-Y');
        $end_date = date('d-m-Y', strtotime('-3 days', strtotime($today_date)));

        foreach ($message_data as $m) {
            $message_date=date('d-m-Y', $m->created_at);
            if (($message_date > $end_date) && ($message_date <= $today_date)){
                $count2++;
            }
        }
        if ($count2 >= 1) {
            $data['upload_status']="1";
        }else{
            $data['upload_status']="0";
        }
    }else{
        $data['upload_status']="0";
    }
    $count=0;
    foreach ($data['document'] as $d) {
        if($d->url!='') {
            $data['document'][$count]->url=base_url().$d->url;
        }else{
            $data['document'][$count]->url=null;
        }
        $count++;
    }
    if (count($data['document']) >= 1) {
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"List of All Document"]);
    }else{
        $blank_object=new stdClass();
        array_push($data['document'], $blank_object);
        echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
    }
    // echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}

// get otp verify

public function otpverify() {
     $content = trim(file_get_contents("php://input"));
     $_POST = json_decode($content, true);
     
     // print_r($result); die;
      $get_otp = $_POST['otp'];
    if($get_otp!="" && $get_otp != null) {

         $userid = $_POST['id'];
         $result= $this->Api_model->otp_exists($userid);
        if($get_otp == $result[0]->otp) {
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'OTP Verify successfully']);
        }else{
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Invalid OTP!']);  
        }
    }else {
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'OTP ']);
    }
}
 // Forget Password 
 public function forget_password() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
     $email = $_POST['email']; 
    if (!empty($email) ) {
        $result= $this->Api_model->check_email_exists($email);
        if(!empty($result[0]['id'])){
            $otp = rand(1000, 9999);
            $msg = "Forgot Password: Your OTP is $otp";

            $update_rows = array(
                'otp' => $otp
    
            );
            $this->db->where('email', $email);
            $result1 = $this->db->update('users', $update_rows); 

            _send_email($email,$otp,$msg);
            $data['id']= $result[0]['id'];
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"We have sent an OTP on your registered email-id."]);
        } else {
            echo json_encode(['data'=>"",'status'=>"False",'message'=>"Enter Correct Email ID."]);
        }
       
    } else {
      
        echo json_encode(['data'=>"",'status'=>"False",'message'=>"Enter Correct Email ID."]);
    }
  
}


//-------------------------------------------------------------------------------

// API For Book Appointment Doctor 
function addAppointment() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Add Appointment";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    //$this->form_validation->set_rules('patientId','PatientID', 'required');
    $this->form_validation->set_rules('patient_id','Patient id', 'required');
    $this->form_validation->set_rules('date', 'Select Date', 'required');
    $this->form_validation->set_rules('time_slot', 'Select Time Slot', 'required');
    // $this->form_validation->set_rules('location', 'Location id', 'required');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
    $this->form_validation->set_rules('mode_of_consultation', 'Mode of Consultationis required', 'required');
    $this->form_validation->set_rules('type_of_consultation', 'Type of Consultationis required', 'required');
    $this->form_validation->set_rules('patient_type', 'Ptient type required', 'required');
    $this->form_validation->set_rules('price', 'Price', 'required');
    
    if ($this->form_validation->run() == true) {
        $patient_id = $this->input->post('patient_id');
        if (empty($this->input->post('location'))) {
            $location_id = 0;
        }else{
            $location_id = $this->input->post('location');
        }
        
        $date = $this->input->post('date');
        $time_slot = $this->input->post('time_slot');
        $remarks = $this->input->post('remarks');
        $mode_of_consultation = $this->input->post('mode_of_consultation');
        $type_of_consultation = $this->input->post('type_of_consultation');
        $time_slot_explode = explode('To', $time_slot);
        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);
        $patient_type = $this->input->post('patient_type');

        date_default_timezone_set("Asia/Kolkata"); 
        $additional_data = array(
            'patient' => $patient_id,
            'location_id' => $location_id,
            'date' => date('d-m-Y', strtotime($date)),
            'time_slot' => $time_slot,
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 1,
            'mode_of_consultation' => $mode_of_consultation,
            'type_of_consultation' => $type_of_consultation,
            'registration_time' => time(),
            "patient_type"=>$patient_type,
            'price' => $this->input->post('price'),
            'remarks' => $remarks
        );
            $doctor_id = $this->input->post('doctor_id');
            $this->db->where('id', $doctor_id);
            $query1 = $this->db->get('doctor');
            $datas = $query1->result_array();

            $this->db->where('id', $patient_id);
            $p_query = $this->db->get('patient');
            $p_datas = $p_query->result_array();

            $doc_user_id = $datas[0]['ion_user_id'];
            $this->db->where('id', $doc_user_id);
            $query2 = $this->db->get('users');
            $datas_doc = $query2->result_array();

            $this->db->insert('appointment', $additional_data);
            $inserted_id = $this->db->insert_id();

            $this->db->where('id', $inserted_id);
            $query2 = $this->db->get('appointment');
            $result_data = $query2->result();
        
            // Send Notification 
            $this->db->where('user_id', $doc_user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();

                $data_addedd = array
                (
                    'type'   => 'Add Appointment',
                    'title'   => 'New Appointment Booking Request',
                    'id'     => $result_data[0]->id,
                    'patient_id'     => $result_data[0]->patient,
                    'user_id' => $doc_user_id,
                    'msg'  => "New Booking Request #".$result_data[0]->id ." by ".$p_datas[0]->name.".",
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                    $n_data = array(
                        'user_id' => $doc_user_id,
                        'type' => $data_addedd['type'],
                        'title' => $data_addedd['title'],
                        'message' => $data_addedd['msg'],
                        'profile_img' => $datas[0]['img_url'],
                        'appointment_id' => $result_data[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
                    );
                            
                $n_result=$this->db->insert('notification', $n_data);

            foreach ($user_device_data as $ud) {
                
                $deviceToken = $ud->device_token;
                $deviceType = $ud->device_type;
                
                _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
            } 
            
            echo json_encode(['data'=>$result_data[0] ,'status'=>'true','message'=>"Appointment booked successfully"]);
        } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

// Vidya ------------------
// Add case details By doctor
function  addCase(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Add  Case";
    $tables = $this->config->item('tables', 'ion_auth');
    //validate form input
    $this->form_validation->set_rules('date','Date', 'required');
    $this->form_validation->set_rules('patient_id','Patient Required', 'required');
    $this->form_validation->set_rules('doctor_id','Doctor Required', 'required');
    $this->form_validation->set_rules('title','Title Required', 'required');
    $this->form_validation->set_rules('case_desc','Case Description Required', 'required');

    if ($this->form_validation->run() == true) {
        $case_id = $this->input->post('case_id');
        $date = $this->input->post('date');
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $title = $this->input->post('title');
        $case_desc = $this->input->post('case_desc');

        $patient_user_id = $this->db->get_where('patient', array('id' => $patient_id))->row();
   
   
        $patient_name  = $patient_user_id->name;
        $patient_address  = $patient_user_id->address;
        $patient_phone = $patient_user_id->phone;
        $registration_time = $patient_user_id->registration_time;

        if (!empty($date)) {
            $date = strtotime($date);
        }
        $registration_time =time();
        $case_data = array(
            'patient_id' => $patient_id,
           // 'doctot_id' => $doctor_id,
            'title' => $title,
            'description' => $case_desc,
            'patient_name' => $patient_name,
            'patient_address' => $patient_address,
            'patient_phone' => $patient_phone,
            'registration_time' => $registration_time,
            'date' =>$date,

        );
        
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
        if (empty($case_id)) {
            $this->db->insert('medical_history', $case_data);
            $insert_id = $this->db->insert_id();
            $this->db->where('id', $insert_id);
            $query = $this->db->get('medical_history');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Case  Booked Successfully']);
        }else{
            $this->db->where('id', $case_id);
            $result = $this->db->update('medical_history', $case_data); 
            $inserted_id = $case_id;
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('medical_history');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Case Updated Successfully']);
        }

       
    } else {
        //display the create user form
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}
public function deletecase() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $this->form_validation->set_rules('case_id','Case Id', 'required');
    if ($this->form_validation->run() == true) {

        $labreport_id = $this->input->post('case_id');
        $this->db->where('id', $labreport_id);
        $this->db->delete('medical_history');
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Case Deleted Successfully']);
    }
    else {
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}


public function deleteLabReport() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $this->form_validation->set_rules('labreport_id','Labreport Id', 'required');
    if ($this->form_validation->run() == true) {

        $labreport_id = $this->input->post('labreport_id');
        $this->db->where('id', $labreport_id);
        $this->db->delete('lab');
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Lab Report Deleted Successfully']);
    }
    else {
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

// Add LAb Report
public function addLabReport() {
    
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Add Lab Report";
    $tables = $this->config->item('tables', 'ion_auth');
    //validate form input
    $this->form_validation->set_rules('date','Date', 'required');
    $this->form_validation->set_rules('patient_id','Patient', 'required');
    $this->form_validation->set_rules('doctor_id','Doctor', 'required');
    $this->form_validation->set_rules('appointment_id','Appointment', 'required');
    $this->form_validation->set_rules('template_id','Template Id', 'required');
    $this->form_validation->set_rules('report','Report', 'required');

    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $date = $this->input->post('date');
        $labreport_id = $this->input->post('labreport_id');
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $appointment_id = $this->input->post('appointment_id');
        $template = $this->input->post('template_id');
        $report = $this->input->post('report');
        
        $data = array();
        $patient_user_id = $this->db->get_where('patient', array('id' => $patient_id))->row();
        $doctor_user_id = $this->db->get_where('doctor', array('id' => $doctor_id))->row();
        $patient_name  = $patient_user_id->name;
        $patient_address = $patient_user_id->address;
        $patient_phone = $patient_user_id->phone;
        $doctor_name = $doctor_user_id->name;
        if (!empty($date)) {
            $date = strtotime($date);
        }else{
            $date = null;
        }
        date_default_timezone_set("Asia/Kolkata"); 
        $created_at = time();
        $date_string = date('d-m-Y', $date);
       
            $data = array(
                'template_id' => $template,
                'report' => $report,
                'patient' => $patient_id,
                'date' => $date,
                'doctor' => $doctor_id,
                'appointment_id' => $appointment_id,
                'patient_name' => $patient_name,
                'patient_phone' => $patient_phone,
                'patient_address' => $patient_address,
                'doctor_name' => $doctor_name,
                'date_string' => $date_string,
                'created_at' => $created_at
            );
         
            if (empty($labreport_id)) {
                $this->Api_model->insertLab($data);
                $inserted_id = $this->db->insert_id();
                $this->db->where('id', $inserted_id);
                $query = $this->db->get('lab');
                $result_data = $query->result();
                echo json_encode(['data'=>$result_data,'status'=>'true','message'=>'Lab Report Added Successfully']);
            }else{
                $this->db->where('id', $labreport_id);
                $result = $this->db->update('lab', $data); 
                $inserted_id = $labreport_id;
                $this->db->where('id', $inserted_id);
                $query = $this->db->get('lab');
                $result_data = $query->result();
                echo json_encode(['data'=>$result_data,'status'=>'true','message'=>'Lab Report Updated Successfully']);
            }
        } else {
            //display the create user form
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }

// --- Add NewPrescription ----

public function addNewPrescription() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Add Lab Report";
    $tables = $this->config->item('tables', 'ion_auth');

    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    $this->form_validation->set_rules('selectedDate', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('patientId', 'Patient Id', 'trim|required|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'trim|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('History', 'History', 'trim|min_length[1]|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('Notes', 'Note', 'trim|min_length[1]|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('Advice', 'Advice', 'trim|min_length[1]|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
    //$this->form_validation->set_rules('validity', 'Validity', 'trim|min_length[1]|max_length[100]|xss_clean');

    if ($this->form_validation->run() == true) {

        $prescription_id = $this->input->post('prescription_id');
        // $tab = $this->input->post('tab');
        $date = $this->input->post('selectedDate');
        if (!empty($date)) {
            $date = strtotime($date);
        }
        $patientId = $this->input->post('patientId');
        $patientname = $this->input->post('patientName');
        $doctor = $this->input->post('doctorId');
        $doctorname = $this->input->post('doctorName');
        $symptom = $this->input->post('History');
        $note = $this->input->post('Notes');
        $advice = $this->input->post('Advice');
        $appointment_id = $this->input->post('appointment_id');

        $medicine = $this->input->post('medicine');
        if (!empty($medicine)) {
         
            foreach ($medicine as $key1 => $value1) {
                $final[] = '' . implode('***', $value1);
            }
            $final_report = implode('###', $final);
        } else {
            $final_report = '';
        }

        $data = array();
        $patientname = $this->Api_model->getPatientById($patient)->name;
        $doctorname = $this->Api_model->getDoctorById($doctor)->name;
        date_default_timezone_set("Asia/Kolkata"); 
        $created_at = time();
        $data = array('date' => $date,
            'patient' => $patientId,
            'doctor' => $doctor,
            'symptom' => $symptom,
            'medicine' => $final_report,
            // 'medicine' => $medicine,
            'note' => $note,
            'advice' => $advice,
            'patientname' => $patientname,
            'appointment_id' => $appointment_id,
            'doctorname' => $doctorname,
            'created_at' => $created_at,
            'visit_status'=> 0
        );
        // echo json_encode($data);
        if (empty($prescription_id)) {
            $r=$this->db->insert('prescription', $data);

            $inserted_id = $this->db->insert_id();

            $this->db->where('id', $inserted_id);
            $query = $this->db->get('prescription');
            $result_data = $query->result();

            $this->db->where('id', $patientId);
            $patient_query = $this->db->get('patient');
            $patient_data = $patient_query->result();

            $this->db->where('id', $patient_data[0]->ion_user_id);
            $user_query = $this->db->get('users');
            $user_data = $user_query->result();

            $this->db->where('user_id', $patient_data[0]->ion_user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();

                $data_addedd = array
                        (
                            'type'   => 'new_prescription',
                            'id'     => $inserted_id,
                            'user_id' => $user_data[0]->id,
                            'title'=>"New Prescription",
                            'patient_id'     => $result_data[0]->patient,
                            'msg'  => 'New Prescription #'.$inserted_id .' Added',
                        );
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $user_data[0]->id,
                    'type' => $data_addedd['type'],
                    'title'=>"New prescription",
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_id,
                    'created_at' => time(),
                    'read_status' => 0
                );
                                
                $n_result=$this->db->insert('notification', $n_data);

            foreach ($user_device_data as $ud) {
                
                $deviceToken = $ud->device_token;
                $deviceType = $ud->device_type;
                
               _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
            } 
            //notification to pharmacist 

            $pharmacist_query = $this->db->get('pharmacist');
            $pharmacist_data = $pharmacist_query->result();

            $this->db->where('id', 110);
            $pharmacist_user_query = $this->db->get('users');
            $pharmacist_user_data = $pharmacist_user_query->result();

            $this->db->where('user_id', 110);
            $this->db->where('status', 1);
            $pharmacist_user_device_query = $this->db->get('user_device');
            $pharmacist_user_device_data = $pharmacist_user_device_query->result();

                $pharmacist_data_addedd = array
                        (
                            'type'   => 'new_prescription',
                            'id'     => $inserted_id,
                            'title'=>"New Prescription",
                            'user_id' => 110,
                            'patient_id'     => $result_data[0]->patient,
                            'msg'  => 'New Prescription #'.$inserted_id .' Added',
                        );
                date_default_timezone_set("Asia/Kolkata"); 
                $pharmacist_n_data = array(
                    'user_id' => 110,
                    'type' => $data_addedd['type'],
                    'title'=>"New Prescription",
                    'message' => $data_addedd['msg'],
                    'profile_img' => $pharmacist_data[0]->img_url,
                    'appointment_id' => $appointment_id,
                    'created_at' => time(),
                );
                                
                $pharmacist_n_result=$this->db->insert('notification', $pharmacist_n_data);

            foreach ($pharmacist_user_device_data as $pud) {
                
                $pharmacist_deviceToken = $pud->device_token;
                $pharmacist_deviceType = $pud->device_type;
                
               _send_fcm_notification($pharmacist_deviceToken,$pharmacist_deviceType,'',$pharmacist_data_addedd);
            } 
            echo json_encode(['data'=>$result_data[0],'status'=>'true','message'=>'Prescription added successfully']);
        }else{
            $this->db->where('id', $prescription_id);
            $result = $this->db->update('prescription', $data); 
            $inserted_id = $prescription_id;
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('prescription');
            $data[] = $query->result();
            echo json_encode(['data'=>$inserted_id,'status'=>'true','message'=>'Prescription updated successfully']);
        }
        
    } else {
        //display the create user form
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}
public function deletePrescription() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $this->form_validation->set_rules('prescription_id','Prescription Id', 'required');
    if ($this->form_validation->run() == true) {

        $prescription_id = $this->input->post('prescription_id');
        $this->db->where('id', $prescription_id);
        $this->db->delete('prescription');
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Prescription Deleted Successfully']);
    }
    else {
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

// ---- Add Holiday ---- 

public function addHoliday(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    date_default_timezone_set("Asia/Kolkata"); 

    $data['title'] = "Add Holiday";
    //validate form input
    $this->form_validation->set_rules('date','Date', 'required');
    $this->form_validation->set_rules('doctor_id','Patient Required', 'required');

    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $date = $this->input->post('date');
        $doctor_id = $this->input->post('doctor_id');
        $reason = $this->input->post('reason');

        $this->db->where('date', date('d-m-Y', strtotime($date)));
        $query = $this->db->get('appointment');
        $appointment_data = $query->result();

        if (count($appointment_data)>=1) {
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>count($appointment_data).' Booking Already Found on '.$date]);
        }else{
            $this->db->where('date', strtotime($date));
            $holidays_query = $this->db->get('holidays');
            $holidays_data = $holidays_query->result();
            if (count($holidays_data)>=1) {
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>$date.' Already Exits']);
                
            }else{
                $data = array(
                    'date' => strtotime($date),
                    'doctor' => $doctor_id,
                    'reason'=>$reason,
                    'created_at'=>time()
                );
                $this->Api_model->insertHoliday($data);
                $inserted_id = $this->db->insert_id();     
                $this->db->where('id', $inserted_id);
                $new_query = $this->db->get('holidays');
                $new_data = $new_query->result();
                echo json_encode(['data'=>$new_data[0],'status'=>'true','message'=>'Holiday Added Successfully']);
            }
            
        }
    } else {
        //display the create user form
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }

}


function getPatientDetails() {
   // $this->db->order_by('id', 'desc');
    
    //$query = $this->db->get('patient');
    //$query = $this->db->join('appointment','appointment.patient =patient.id' );


    $this->db->select('patient.id,patient.img_url,patient.name,patient.email,patient.address,patient.phone,patient.sex,patient.age,patient.bloodgroup,appointment.id as appointmentid,appointment.date,appointment.time_slot,appointment.s_time,appointment.e_time,appointment.remarks,appointment.patientname,appointment.location_id');
    $query = $this->db->join('appointment','patient.id =appointment.patient' );
    $query = $this->db->get('patient');
    $query->result();


    $data =  $query->result();
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}


function myDocuments() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
        $patient_id = $_POST['patient_id'];
        $data['files'] = $this->Api_model->getPatientMaterialByPatientId($patient_id);
        // print_r($data['files'][0]); die;
        $count=0;
        foreach ($data['files'] as $d) {
            if($d->url != '') {
                $data['files'][$count]->url=base_url().$d->url;
            }
            $data['files'][$count]->date=date("d-m-Y", $d->date);
            $count++;
        }
        // $data['files'][0]->url=base_url().$data['files']->url;
        
        echo json_encode(['data'=>$data['files'],'status'=>"true",'message'=>"success"]);
    
}

    //edit a group
    function edit_group($id) {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("auth", 'refresh');
            }
        }

        //set the flash data error message if there is one
        $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $data['group'] = $group;

        $readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

        $data['group_name'] = array(
            'name' => 'group_name',
            'id' => 'group_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
            $readonly => $readonly,
        );
        $data['group_description'] = array(
            'name' => 'group_description',
            'id' => 'group_description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );

        $this->_render_page('auth/edit_group', $data);
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }



    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false) {

        $this->viewdata = (empty($data)) ? $data : $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }



    function edit_profile() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        // $this->form_validation->set_rules('profileimg', 'Profile image', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('gender', 'gender ', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth ', 'required');
        $this->form_validation->set_rules('city', 'City  ', 'required');
        $this->form_validation->set_rules('country', 'Country  ', 'required');
        $this->form_validation->set_rules('address', 'Address ', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode ', 'required');
        $this->form_validation->set_rules('weight', 'Weight ', 'required');
        $this->form_validation->set_rules('merital_status', 'Merital Status ', 'required');
        if ($this->form_validation->run() == true) {
            $id = $_POST['id'];
            $this->db->where('id',$id);
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){
                $age=date_diff(date_create($_POST ['dob']), date_create('today'))->y;
                if (empty($_POST ['profileimg'])) {
                    $prfile_data = array(
                                    'name'=>$_POST ['name'],
                                    'phone'=>$_POST ['phone'],
                                    'sex'=>$_POST ['gender'],
                                    'birthdate'=>strtotime($_POST ['dob']),
                                    'city'=>$_POST ['city'],
                                    'country'=>$_POST ['country'],
                                    'address'=>$_POST ['address'],
                                    'doctor'=>1,
                                    'pincode'=>$_POST ['pincode'],
                                    'age' => $age,
                                    'weight' => $_POST ['weight'],
                                    'merital_status' => $_POST ['merital_status'],
                                    'spouse_name' => $_POST ['spouse_name']
                                );
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }else {
                    $base64_image_string=$_POST ['profileimg'];
                    $output_file="./uploads/";
                        $toDay   = date("d-m-Y");
                        $rand    = rand(1000, 9999);
                        $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
                    file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );

                    $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,
                                        'name'=>$_POST ['name'],
                                        'phone'=>$_POST ['phone'],
                                        'sex'=>$_POST ['gender'],
                                        'birthdate'=>strtotime($_POST ['dob']),
                                        'city'=>$_POST ['city'],
                                        'country'=>$_POST ['country'],
                                        'address'=>$_POST ['address'],
                                        'doctor'=>1,
                                        'pincode'=>$_POST ['pincode'],
                                        'age' => $age,
                                        'weight' => $_POST ['weight'],
                                        'merital_status' => $_POST ['merital_status'],
                                        'spouse_name' => $_POST ['spouse_name']
                                    );
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }
                

                if ($result==true) {
                    $this->db->where('id',$id);
                    $patient_data_query = $this->db->get('patient');
                    $patient_data_result= $patient_data_query->result();
                    if($patient_data_result[0]->img_url!='') {
                        $patient_data_result[0]->img_url=base_url().$patient_data_result[0]->img_url;
                    }
                    $patient_data_result[0]->birthdate=date("d-m-Y",$patient_data_result[0]->birthdate);

                    echo json_encode(['data'=>$patient_data_result[0],'status'=>'true','message'=>'Profile update successfully']);
                }else {
                    $blank_object=new stdClass();
                    echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Profile update unsuccessfull']);
                }
            } else {
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Patient Found"]);

            }
            

        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function getAppointmentByDoctorIdbyjason() {
        date_default_timezone_set("Asia/Kolkata");
        $id = $this->input->get('id');
        $data = array();
        $appointments = $this->appointment_model->getAppointmentByDoctor($id);
        foreach ($appointments as $appointment) {
            $patient_exists = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patient_exists)) {
                $patients['id'] = $appointment->patient;
                $patients['name'] = $patient_exists->name;
                $patients['booking_date'] = $appointment->date;
                $patients['booking_time'] = $appointment->s_time;
                if($patient_exists->img_url!='') {
                    $patient_exists->img_url=base_url().$patient_exists->img_url;
                }
                $patients['ProfilePhoto'] = $patient_exists->img_url;
                $patients['mode_of_consultation'] = $appointment->mode_of_consultation;
                $patients['type_of_consultation'] = $appointment->type_of_consultation;
                $patients['membership_code'] = '';
                $patients['LocationId'] = $appointment->location_id;
            }
        }
        if (!empty($patients)) {
            $patients = array_unique($patients);
            echo json_encode(['data'=>$patients,'status'=>'true','message'=>'dasdasd']);
        } else {
            $patients = '';
            echo json_encode(['data'=>$patients,'status'=>'false','message'=>'dasdasd']);
        }
        
    }

// Edited By Ajit 


    function getPatientByDoctorIdbyjason() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata");
        
        $id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $locationid = $_POST['locationid'];
        $data = array();
        $this->db->where('doctor', $id);
        $this->db->where('date', date('d-m-Y', strtotime($date)));
        if (!empty($locationid)) {
            $this->db->where('location_id', $locationid);
        }
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('appointment');
        $appointments =$query->result();

        // $appointments = $this->appointment_model->getAppointmentByDoctor($id);
        $patient_array=array();
        $i=0;
        foreach ($appointments as $appointment) {
            $patient_exists = $this->patient_model->getPatientById($appointment->patient);
            $location= $this->db->where('id',$appointment->location_id)->get('location')->result();
            $user_data= $this->db->where('id',$patient_exists->ion_user_id)->get('users')->result();
            $status= $this->db->where('id',$appointment->status)->get('appointment_status')->result();
            $cancellation_request = $this->db->where('appointment_id', $appointment->id)->get('cancellation_request')->result();
            
            if (!empty($patient_exists)) {
                $patient['patientid'] = $appointment->patient;
                $patient['ion_user_id'] = $patient_exists->ion_user_id;
                $patient['patientemail'] = $patient_exists->email;
                $patient['patientphone'] = $patient_exists->phone;
                $patient['patientaddress'] = $patient_exists->address;
                $patient['patientname'] = $patient_exists->name;
                $patient['patientage'] = $patient_exists->age;
                $patient['patientweight'] = $patient_exists->weight;
                $patient['patientmeritalstatus'] = $patient_exists->merital_status;
                $patient['BookingDate'] = $appointment->date;
                $patient['BookingTime'] = $appointment->s_time;
                $patient['patientweight'] = $patient_exists->weight; 
                if($patient_exists->img_url!='') {
                    $patient_exists->img_url=base_url().$patient_exists->img_url;
                }
                $patient['ProfilePhoto'] = $patient_exists->img_url;
                $patient['mode_of_consultation'] = $appointment->mode_of_consultation;
                $patient['type_of_consultation'] = $appointment->type_of_consultation;
                $patient['membership_code'] = '';
                $patient['LocationId'] = $appointment->location_id;
                $patient['location'] = $location[0]->name;
                $patient['device_token'] = $user_data[0]->deviceToken;
                $patient['booking_id'] = $appointment->id;
                $patient['starttime'] = $appointment->s_time;
                $patient['endtime'] = $appointment->e_time;
                $patient['time_slot'] = $appointment->time_slot;
                $patient['status'] = $status[0]->status_name;
                $patient['price'] = $appointment->price;
                if (!empty($appointment->registration_time)) {
                    $patient['date'] = date('d-m-Y h:i A',$appointment->registration_time);
                }else{
                    $patient['date'] ='';
                }
                if ($appointment->status==3) {
                    $patient['description'] =$appointment->description;
                }else{
                    $patient['description'] ='';
                }
                $patient['description'] =$appointment->description;
				$patient['status_id'] = $appointment->status;
                $patient['patient_type'] = $appointment->patient_type;
                
                // $patient['request_type']=$cancellation_request;
                if (count($cancellation_request) > 0) {
                    if ($cancellation_request[0]->type == 8) {
                        $patient['request_type'] ="Cancellation request pending";
                    }else if ($cancellation_request[0]->type == 6) {
                        $patient['request_type'] ="Postpone request pending";
                    }else{
                        $patient['request_type'] ="";
                    }
                }else{
                    $patient['request_type'] ="";
                }
            }
            if ($patient != null) {
                $patient_array['patients'][$i]=$patient;
                $i++;
            }
            
        }
        if (count($appointments)>0) {
            echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
        } else {
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'No data found']);
        }
        // echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
    }
    function getPatientByDoctorId() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $locationid = $_POST['locationid'];
        $data = array();
        $this->db->where('doctor', $id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('appointment');
        $appointments =$query->result();

        // $appointments = $this->appointment_model->getAppointmentByDoctor($id);
        $patient_array=array();
        $i=0;
        foreach ($appointments as $appointment) {
            $patient_exists = $this->patient_model->getPatientById($appointment->patient);
            $location= $this->db->where('id',$appointment->location_id)->get('location')->result();
            $user_data= $this->db->where('id',$patient_exists->ion_user_id)->get('users')->result();
            $doctor= $this->db->where('id',$appointment->patient)->get('doctor')->result();
            $status= $this->db->where('id',$appointment->status)->get('appointment_status')->result();

            if (!empty($patient_exists)) {
                $patient['patientid'] = $appointment->patient;
                $patient['patientname'] = $patient_exists->name;
                $patient['patientemail'] = $patient_exists->email;
                $patient['patientaddress'] = $patient_exists->address;
                $patient['patientphone'] = $patient_exists->phone;   
                $patient['patientweight'] = $patient_exists->weight; 
                if($patient_exists->img_url!='') {
                    $patient_exists->img_url=base_url().$patient_exists->img_url;
                }
                $patient['ProfilePhoto'] = $patient_exists->img_url;             
                $patient['BookingDate'] = $appointment->date;
                $patient['BookingTime'] = $appointment->s_time;
                $patient['mode_of_consultation'] = $appointment->mode_of_consultation;
                $patient['type_of_consultation'] = $appointment->type_of_consultation;
                $patient['membership_code'] = '';
                $patient['LocationId'] = $appointment->location_id;
                $patient['location'] = $location[0]->name;
                $patient['device_token'] = $user_data[0]->deviceToken;
                $patient['booking_id'] = $appointment->id;
                $patient['starttime'] = $appointment->s_time;
                $patient['endtime'] = $appointment->e_time;
                $patient['time_slot'] = $appointment->time_slot;
                $patient['status'] = $status[0]->status_name;
                $patient['price'] = $appointment->price;
                $patient['date'] = date('d-m-Y h:i A',$appointment->registration_time);
                $patient['description'] ='';
				$patient['status_id'] = $appointment->status;
                $patient['doctor_name'] = $doctor[0]->name;
                $patient['remarks'] = $appointment->remarks;
                $patient['patient_type'] = ucfirst($appointment->patient_type);
            }
            if ($patient != null) {
                $patient_array['patients'][$i]=$patient;
                $i++;
            }
            
        }
        if (count($appointments)>0) {
            echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
        } else {
            $blank_object=new stdClass();
            echo json_encode(['data'=>"{}",'status'=>'false','message'=>'No data found']);
        }
        // echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
    }

    public function getallAppointmentlist() {   

        $this->db->order_by('id', 'desc');
        $query = $this->db->get('appointment');
        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    
    }

    public function adddocuments() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        //validate form input
        $this->form_validation->set_rules('patient_id','Patient Id', 'required');
        $this->form_validation->set_rules('appointment_id','Appointment Id', 'required');
        $this->form_validation->set_rules('base_data','file', 'required');

        if ($this->form_validation->run() == true) {
            $document_id = $this->input->post('document_id');
            $patient_id = $this->input->post('patient_id');
            $appointment_id = $this->input->post('appointment_id');
            $title = $this->input->post('title');
            $base_data = $this->input->post('base_data');
            $type = $this->input->post('type');

            if (!empty($patient_id)) {
                $patient_details = $this->patient_model->getPatientById($patient_id);
                if ($patient_details==null) {
                    $blank_object=new stdClass();
		            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'patient is not found']);
                }else{
                    $data = base64_decode($base_data);
                    $file = "uploads/documents/". uniqid() .'.'.$type;
                    $success = file_put_contents($file, $data);
                    // print $success ? $file : 'Unable to save the file.';
                    $patient_name = $patient_details->name;
                    $patient_phone = $patient_details->phone;
                    $patient_address = $patient_details->address;
                    date_default_timezone_set("Asia/Kolkata"); 
                    $date = time();
                    // $type=".doc";
                    $folder="documents";
                    $img_url = $file;
                    $data = array();
                    $data = array(
                        'date' => $date,
                        'type' => $type,
                        'folder' => $folder,
                        'title' => $title,
                        'url' => $img_url,
                        'patient' => $patient_id,
                        'appointment_id' => $appointment_id,
                        'patient_name' => $patient_name,
                        'patient_address' => $patient_address,
                        'patient_phone' => $patient_phone,
                        'date_string' => date('d-m-Y', $date),
                    );
                    if (empty($document_id)) {
                        $this->db->insert('patient_material', $data);
                        $patient_id2 = $this->db->insert_id();
                        // $patient_details2 = $this->patient_model->getPatientById($patient_id2);
                        $this->db->where('id', $patient_id2);
                        $query = $this->db->get('patient_material');
                        $data = $query->result();
                        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"Document uplaoded successfully"]);
                    }else{
                        $this->db->where('id', $document_id);
                        $result = $this->db->update('patient_material', $data); 
                        $inserted_id = $document_id;
                        $this->db->where('id', $inserted_id);
                        $query = $this->db->get('patient_material');
                        $data = $query->result();
                        echo json_encode(['data'=>$data,'status'=>'true','message'=>'Document updated successfully']);
                    }
                }
            }else {
                $blank_object=new stdClass();
		            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Patient Id is Empty']);
            }
            
        }else{
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
		 echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    
    }
    public function deleteDocuments() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('document_id','Document Id', 'required');
        if ($this->form_validation->run() == true) {
    
            $document_id = $this->input->post('document_id');
            $this->db->where('id', $document_id);
            $this->db->delete('patient_material');
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Document Deleted Successfully']);
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }

    // -- Add Schedule ------------------

    public function addSchedule(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata"); 

        $data['title'] = "Add Scedule";
        //validate form input
        $this->form_validation->set_rules('day','Day', 'required');
        $this->form_validation->set_rules('doctor_id','Patient', 'required');
        $this->form_validation->set_rules('start_time','Start Time', 'required');
        $this->form_validation->set_rules('duration','Duration', 'required');
        $this->form_validation->set_rules('location_id','Location', 'required');
        $this->form_validation->set_rules('consultation_type','Consultation type', 'required');

        if ($this->form_validation->run() == true) {

            $day = $this->input->post('day');
            $doctor_id = $this->input->post('doctor_id');
            $start_time = $this->input->post('start_time');
            $location_id = $this->input->post('location_id');
            $consultation_type = $this->input->post('consultation_type');
            $durations = $this->input->post('duration');
            $time = strtotime($this->input->post('start_time'));
            //echo $startTime = date("H:i", strtotime('-30 minutes', $time));
            $duration = '+'.$durations;
            $endTime = date("h:i A", strtotime($duration, $time));

            $time_slot_check= $this->db->where('doctor',$doctor_id)->where('s_time',$start_time)->where('weekday',$day)->get('time_slot')->result();
            if (count($time_slot_check) > 0) {
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Time slot already exist']);
            }else{
                $data = array();   
                $data = array(
                    'doctor' => $doctor_id,
                    's_time' => strtoupper($this->input->post('start_time')),
                    'e_time' => strtoupper($endTime),
                    'weekday' => $day,
                    'location_id' => $location_id,
                    'membership_code' =>$consultation_type
                );        
                $data_ins =  $this->Api_model->insertScedule($data);
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Time Slot Added Successfully']);
            }
            // if($data_ins == 1){
            //     echo json_encode(['data'=>$data,'status'=>'true','message'=>'Time Slot Added Successfully']);
            // } else {
            //     echo json_encode(['data'=>$data,'status'=>'false','message'=>'Time slot already exist']);
            // }

        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                echo json_encode(['data'=>'{}','status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }

    }

    public function test_book_appointment() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $user_id=$this->input->post('user_id');

        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $user_device_query = $this->db->get('user_device');
        $user_device_data = $user_device_query->result();

 
            $data_addedd = array
            (
                'type'   => 'Booking',
                'id'     => '378',
                'patient_id'  => '123',
                'title'=>"New prescription",
                'msg'  => 'Your appointment #378 has been successfully approved by Dr. Sudhir Bhola. Now you can proceed to payment.'
            );
        foreach ($user_device_data as $ud) {
            $deviceToken =$ud->device_token;
            $deviceType = $ud->device_type;
            
            
            _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
        }
        
        echo json_encode(['data'=>$user_device_data ,'status'=>'true','message'=>"notification send successfully"]);

    }
    public function send_email(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('patientid','Patient Id', 'required');
        $this->form_validation->set_rules('email','Email', 'required');
        $this->form_validation->set_rules('templateid','Template Id', 'required');
        $this->form_validation->set_rules('subject','Templateid', 'required');
        $this->form_validation->set_rules('paragraph','Paragraph', 'required');
        if ($this->form_validation->run() == true) {
            $to = $_POST['email'];
            $sub = $_POST['subject'];
            $this->db->where('id', $_POST['templateid']);
            $template_query = $this->db->get('template');
            $templates =$template_query->result();
            $content =$templates[0]->template."<p>".$_POST['paragraph']."</p>";

            $ci =& get_instance();
            $ci->load->library('phpmailer_lib');
            $mail = $ci->phpmailer_lib->load();
            $mail->isSMTP();
            $mail->Host     = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'doctorappmipl2@gmail.com';
            $mail->Password = 'ttzwqpmrnyptknsx';
            $mail->SMTPSecure = 'tls';
            $mail->Port     = "587";
            $mail->setFrom('doctorappmipl2@gmail.com', 'Curewell Therapies');
            $mail->addAddress($to);
            $mail->Subject  = $sub;
            $mail->isHTML(true);
            $mail->Body = $content;
            if(!$mail->send()){
                // echo 'Message could not be sent.';
                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Email could not be sent']);
            }else{
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Email sent successfully']);
            }
            
        }else{
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
		    echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
        
        

    }
    
    function getAllSchedule() {
        //2009-10-22
        $content = trim(file_get_contents("php://input"));
        $_GET = json_decode($content, true);
        $id = $this->input->get('id');
        $mydays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');

        foreach ($mydays as $days){
            //echo $days;
            // $empty_odject = new stdClass();
            $data[$days]=array();
            $this->db->where('doctor',$id);
            $this->db->where('weekday', $days);
            $this->db->order_by("s_time asc");
            $query = $this->db->get('time_slot');
            $time_slot_by_day= $query->result();
            if (count($time_slot_by_day) ==0) {
                $empty_odject = new stdClass();
                $data[$days]=$empty_odject;
            }
            foreach ($time_slot_by_day as $tsbd ){
                $this->db->where('id', $tsbd->location_id);
                $query = $this->db->get('location');
                $location= $query->result();
                
                foreach ($location as $l) {
                    // $data2[$l->name]=array();
                    
                    $this->db->where('doctor',$id);
                    $this->db->where('weekday', $tsbd->weekday);
                    $this->db->where('location_id', $l->id);
                    $this->db->order_by("s_time asc");
                    $query = $this->db->get('time_slot');
                    $time_slot_by_location= $query->result();
                    foreach ($time_slot_by_location as $tsbl) {
                        $tsbl->s_time=strtoupper($tsbl->s_time);
                        $tsbl->e_time=strtoupper($tsbl->e_time);
                        $tsbl->location_name=$l->name;
                    }
                    // array_push($data2[$l->name],$time_slot_by_location);
                    // array_push($data[$days],$data2[$l->name]);
                    $data[$days][$l->name] = $time_slot_by_location;
                }
                
            }
            // $data[$days] = $time_slot_by_day;
          
        }
    
    
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    // get days 
    function getAlldays() {  
        $this->db->where('status',1);
        $this->db->order_by("order asc");
        $query = $this->db->get('weekdays');
        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    // get all duration days 
    function getAllduration() {  
        $this->db->where('status',1);
        $this->db->order_by("order asc");
        $query = $this->db->get('duration');
        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    public function vip_slot_date(){
        $NewDate=Date('d-m-Y', strtotime('+30 days'));
        $array = array();
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($NewDate);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime(date("d-m-Y")), $interval, $realEnd);
                
        $this->db->where('membership_code','VIP');
        $time_slot_query = $this->db->get('time_slot');
        $time_slot_data = $time_slot_query->result();

        foreach($period as $date) {	
            foreach ($time_slot_data as $tsd) {
                if (date('l', strtotime($date->format('d-m-Y')))==$tsd->weekday) {
                    if (in_array($date->format('d-m-Y'), $array)) {
                    }else{
                        array_push($array,$date->format('d-m-Y'));
                    }
                }
            }
            
        }

        $query2 = $this->db->get('holidays');
        $holidays= $query2->result();
        $array2=[];
        foreach ($holidays as $h) {
            if (in_array($date->format('d-m-Y'), $array2)) {
            }else{
                array_push($array2,date('d-m-Y',$h->date));
            }
            
        }
        $final_result=[];
        $final_result['vip_time_slot']=$array;
        $final_result['holidays']=$array2;

        if (count($array)>0) {
            echo json_encode(['data'=>$final_result,'status'=>'true','message'=>'List of VIP time slot dates']);
        }else{
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'No VIP time slot date found']);
        }
    }
    function create_user_by_doctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Create User By Doctor";
     
        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        
        $this->form_validation->set_rules('gender', 'gender', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
        $this->form_validation->set_rules('blood_group', 'Blood Group', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('weight', 'Weight', 'required');
        $this->form_validation->set_rules('merital_status', 'Merital Status', 'required');
        

       
        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('name'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $gender = $this->input->post('gender');
            $dob = $this->input->post('dob');
            $blood_group = $this->input->post('blood_group');
            $address = $this->input->post('address');
            $pincode = $this->input->post('pincode');
            $age=date_diff(date_create($dob), date_create('today'))->y;

            $additional_data = array(
                'first_name' => $this->input->post('name'),
                'phone' => $this->input->post('phoneno'),
                'gender' => $this->input->post('gender'),
                'pincode'=> $pincode,
            );
            $userid=$this->ion_auth->register($username, $password, $email,5, $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            $base64_image_string=$_POST ['profile_img'];
            $output_file="./uploads/";
                $toDay   = date("d-m-Y");
                $rand    = rand(1000, 9999);
                $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
            file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );
            date_default_timezone_set("Asia/Kolkata"); 
            $p_data = array();
                $p_data = array(
                    'ion_user_id' => $userdata[0]->id,
                    'name' => $username,
                    'email' => $email,
                    'doctor' => 1,
                    'phone' => $phone,
                    'sex' => $gender,
                    'address' => $address,
                    'birthdate' => strtotime($dob),
                    'bloodgroup' => $blood_group,
                    'pincode'=> $pincode,
                    'age' => $age,
                    'weight' => $this->input->post('weight'),
                    'merital_status' => $this->input->post('merital_status'),
                    'spouse_name' => $this->input->post('spouse_name'),
                    'img_url' => "uploads/".$output_file_with_extension,
                    'registration_time'=>time()
                );
				
                $p_result=$this->db->insert('patient', $p_data);

                if ($p_result==true) {
                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();
                    if($result1[0]->img_url!='') {
                        $result1[0]->img_url=base_url().$result1[0]->img_url;
                    }
                    if ($this->input->post('send_sms_check')==1) {
                        _register_email($email);
                    }
                    
                    echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'successfully Created']);
                }else{
                    $result1=new stdClass();
                    echo json_encode(['data'=>$result1,'status'=>'true','message'=>'unsuccessfull']);
                }
        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function edit_profile_by_doctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('id', 'Patient Id', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('birthdate', 'Date of Birth ', 'required');
        $this->form_validation->set_rules('weight', 'Weight ', 'required');
        $this->form_validation->set_rules('merital_status', 'Merital Status ', 'required');
        // $this->form_validation->set_rules('profileimg', 'Profile Image', 'required');
        if ($this->form_validation->run() == true) {
            $id = $_POST['id'];
            $this->db->where('id',$id);
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){
                $age=date_diff(date_create($_POST ['birthdate']), date_create('today'))->y;
                if (empty($_POST ['profileimg'])) {
                    $prfile_data = array('name'=>$_POST ['name'],'birthdate'=>strtotime($_POST ['birthdate']),'age' => $age,'weight' => $_POST ['weight'],'merital_status' => $_POST ['merital_status'],'spouse_name' => $_POST ['spouse_name']);
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }else{
                    $base64_image_string=$_POST ['profileimg'];
                    $output_file="./uploads/";
                        $toDay   = date("d-m-Y");
                        $rand    = rand(1000, 9999);
                        $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
                    file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );
    
                    $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,'name'=>$_POST ['name'],'birthdate'=>strtotime($_POST ['birthdate']),'age' => $age,'weight' => $_POST ['weight'],'merital_status' => $_POST ['merital_status'],'spouse_name' => $_POST ['spouse_name']);
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }
                

                if ($result==true) {
                    $this->db->where('id',$id);
                    $patient_data_query = $this->db->get('patient');
                    $patient_data_result= $patient_data_query->result();
                    if($patient_data_result[0]->img_url!='') {
                        $patient_data_result[0]->img_url=base_url().$patient_data_result[0]->img_url;
                    }

                    echo json_encode(['data'=>$patient_data_result[0],'status'=>'true','message'=>'Profile update successfully']);
                }else {
                    $blank_object=new stdClass();
                    echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Profile update unsuccessfull']);
                }
            } else {
                echo json_encode(['data'=>$num,'status'=>"false",'message'=>"No Patient Found"]);

            }
            

        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function edit_doctor_profile() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('doctorid', 'Doctor Id', 'required');
        $this->form_validation->set_rules('doctorname', 'Name', 'required');
        $this->form_validation->set_rules('address', 'Address ', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile ', 'required');
        // $this->form_validation->set_rules('profileimg', 'Profile Image', 'required');
        if ($this->form_validation->run() == true) {
            $id = $_POST['doctorid'];
            $doctorname = $_POST['doctorname'];
            $address = $_POST['address'];
            $mobile = $_POST['mobile'];

            $this->db->where('id',$id);
            $query = $this->db->get('doctor');
            $num = $query->num_rows();  
            if($num >=1){
                if (empty($_POST ['profileimg'])) {
                    $prfile_data = array('name'=>$doctorname,'address'=>$address,'phone'=>$mobile);
                    $this->db->where('id', $id);
                    $result=$this->db->update('doctor', $prfile_data);
                }else{
                    $base64_image_string=$_POST ['profileimg'];
                    $output_file="./uploads/";
                        $toDay   = date("d-m-Y");
                        $rand    = rand(1000, 9999);
                        $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
                    file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );
    
                    $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,'name'=>$doctorname,'address'=>$address,'phone'=>$mobile);
                    $this->db->where('id', $id);
                    $result=$this->db->update('doctor', $prfile_data);
                }
                

                if ($result==true) {
                    $this->db->where('id',$id);
                    $patient_data_query = $this->db->get('doctor');
                    $patient_data_result= $patient_data_query->result();

                    $this->db->where('id', $patient_data_result[0]->ion_user_id);
                    $result1=$this->db->update('users', array('phone'=>$mobile,'username'=>$doctorname,));

                    if($patient_data_result[0]->img_url!='') {
                        $patient_data_result[0]->img_url=base_url().$patient_data_result[0]->img_url;
                    }

                    echo json_encode(['data'=>$patient_data_result[0],'status'=>'true','message'=>'Profile update successfully']);
                }else {
                    $blank_object=new stdClass();
                    echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>'Profile update unsuccessfull']);
                }
            } else {
                echo json_encode(['data'=>$num,'status'=>"false",'message'=>"No Doctor Found"]);

            }
            

        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    // get appointment list by Doctor Id
    function getnotificationBydoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->db->where('id',$_POST['doctor_id']);
        $doctor_query = $this->db->get('doctor');
        $doctor_result= $doctor_query->result();
        
        $this->db->where('user_id',$doctor_result[0]->ion_user_id);
        $this->db->order_by('id', 'desc');
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();

        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->notification_date=date('d-m-Y', $n->created_at);
            $notification_result[$count]->notification_time=date('h:i A', $n->created_at);
            if ($n->read_status==null) {
                $notification_result[$count]->read_status=0;
            }
            $this->db->where('id', $n->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_result = $appointment_query->result();

            $this->db->where('id', $appointment_result[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_result = $patient_query->result();

            $this->db->where('id', $appointment_result[0]->location_id);
            $location_query = $this->db->get('location');
            $location_result = $location_query->result();

            // $notification_result[$count]->bookID=$appointment_result[0]->id;
            $notification_result[$count]->username=$patient_result[0]->name;
            if($n->profile_img!='') {
                $notification_result[$count]->profile_img=base_url().$n->profile_img;
            }
            $notification_result[$count]->time_slot=$appointment_result[0]->s_time;
            $notification_result[$count]->location_id=$appointment_result[0]->location_id;
            $notification_result[$count]->booking_date=$appointment_result[0]->date;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            $notification_result[$count]->patient_type=$appointment_result[0]->patient_type;
            if ($n->title==null) {
                if($n->type=='Booking') {
                    $notification_result[$count]->title="New Appointment Booking Request";
                }else if($n->type=='approved') {
                    $notification_result[$count]->title="Appointment Approved";
                }else if($n->type=='Cancled') {
                    $notification_result[$count]->title="Booking Cancelled";
                }else if($n->type=='Cancel') {
                    $notification_result[$count]->title="Booking Cancellation Request";
                }else if($n->type=='Postpone') {
                    $notification_result[$count]->title="Booking Postpone request";
                }else if($n->type=='Add Appointment') {
                    $notification_result[$count]->title="New Appointment Booking Request";
                }else if($n->type=='doctor_dispatched_notification') {
                    $notification_result[$count]->title="Dispatched";
                }else if($n->type=='message') {
                    $notification_result[$count]->title="New Message";
                }else if($n->type=='new_prescription') {
                    $notification_result[$count]->title="New Prescription";
                }else if($n->type=='Payment Confirmed') {
                    $notification_result[$count]->title="Payment Confirmed";
                }else if($n->type=='Postponed') {
                    $notification_result[$count]->title="Booking Postponed";
                }else if($n->type=='rejected') {
                    $notification_result[$count]->title="Appointment Rejected";
                }else if($n->type=='reminder') {
                    $notification_result[$count]->title="Reminder";
                }else if($n->type=='patient_dispatched_notification') {
                    $notification_result[$count]->title="Dispatched";
                }else{
                    $notification_result[$count]->title="null";
                }
            }else{
                $notification_result[$count]->title=$n->title;
            }
            
            
            // $notification_result[$count]->type_of_user='';
            
            $count++;
        }
        if (count($notification_result) >= 1) {
            echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"All Notification of Doctor"]);
        }else{
            $blank_object = new stdClass();
            array_push($notification_result, $blank_object);
            echo json_encode(['data'=>$notification_result,'status'=>"false",'message'=>"No Data Found"]);
        }
        
    }

    function getnotificationBypatient() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $this->db->where('id',$_POST['patient_id']);
        $patient_query = $this->db->get('patient');
        $patient_result= $patient_query->result();
        
        $this->db->where('user_id',$patient_result[0]->ion_user_id);
        $this->db->order_by('id', 'desc');
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();
        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->notification_date=date('d-m-Y', $n->created_at);
            $notification_result[$count]->notification_time=date('h:i A', $n->created_at);
            if ($n->read_status==null) {
                $notification_result[$count]->read_status=0;
            }
            $this->db->where('id', $n->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_result = $appointment_query->result();

            $this->db->where('id', $appointment_result[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_result = $patient_query->result();

            $this->db->where('id', $appointment_result[0]->location_id);
            $location_query = $this->db->get('location');
            $location_result = $location_query->result();

            $this->db->where('booking_id',$n->appointment_id);
            $payment_query = $this->db->get('payment');
            $payment_details= $payment_query->result();

            // $notification_result[$count]->bookID=$appointment_result[0]->id;
            $notification_result[$count]->username=$patient_result[0]->name;
            if($n->profile_img!='') {
                $notification_result[$count]->profile_img=base_url().$n->profile_img;
            }
            $notification_result[$count]->type=ucfirst($n->type);
            $notification_result[$count]->time_slot=$appointment_result[0]->s_time;
            $notification_result[$count]->booking_date=$appointment_result[0]->date;
            $notification_result[$count]->location_id=$appointment_result[0]->location_id;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            $notification_result[$count]->patient_type=$appointment_result[0]->patient_type;
            if(count($payment_details)>=1) {
                $payment_status=$payment_details[0]->order_status;
            }else{
                $payment_status=null;
            }
            $notification_result[$count]->payment_status=$payment_status;
            $notification_result[$count]->price=$appointment_result[0]->price;
            if($n->type=='Booking') {
                $notification_result[$count]->title="New Appointment Booking Request";
            }else if($n->type=='approved') {
                $notification_result[$count]->title="Appointment Approved";
            }else if($n->type=='Cancled') {
                $notification_result[$count]->title="Booking Cancelled";
            }else if($n->type=='Cancel') {
                $notification_result[$count]->title="Booking Cancellation Request";
            }else if($n->type=='Add Appointment') {
                $notification_result[$count]->title="New Appointment Booking Request";
            }else if($n->type=='Postpone') {
                $notification_result[$count]->title="Postpone";
            }else if($n->type=='doctor_dispatched_notification') {
                $notification_result[$count]->title="Dispatched";
            }else if($n->type=='message') {
                $notification_result[$count]->title="New Message";
            }else if($n->type=='new_prescription') {
                $notification_result[$count]->title="New Prescription";
            }else if($n->type=='Payment Confirmed') {
                $notification_result[$count]->title="Payment Confirmed";
            }else if($n->type=='Postpone' || $n->type=='Postponed') {
                $notification_result[$count]->title="Booking Postponed";
            }else if($n->type=='rejected') {
                $notification_result[$count]->title="Appointment Rejected";
            }else if($n->type=='reminder') {
                $notification_result[$count]->title="Reminder";
            }else if($n->type=='patient_dispatched_notification') {
                $notification_result[$count]->title="Dispatched";
            }else{
                $notification_result[$count]->title="null";
            }
            // $notification_result[$count]->type_of_user='';
            
            
            $count++;
        }
        if (count($notification_result) >= 1) {
            echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"success"]);
        }else{
            $blank_object=new stdClass();
            array_push($notification_result, $blank_object);
            echo json_encode(['data'=>$notification_result,'status'=>"false",'message'=>"No Data Found"]);
        }

    
    }
    function getpatientdetailsBypatient() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id    = $_POST['id'];
        $data['Prescriptionlist'] = $this->Api_model->getPrescriptionByAllPatientId($patient_id);
        $data['medical_histories'] = $this->Api_model->getMedicalHistoryByAllPatientId($patient_id);
        $data['lablist'] = $this->Api_model->getLabByAllId($patient_id);
        $data['document'] = $this->Api_model->getdocument($patient_id);

        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    
    }
    function allPrescriptionbydoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $doctor_id    = $_POST['doctor_id'];
        $this->db->order_by('id', 'desc');
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        $data['Prescriptionlist']=$query->result();
        $count=0;
        foreach ($data['Prescriptionlist'] as $d) {

            $this->db->where('id', $d->patient);
            $query = $this->db->get('patient');
            $patient=$query->result();
            $data['Prescriptionlist'][$count]->patientname=$patient[0]->name;
            $data['Prescriptionlist'][$count]->date=date('d-m-Y', $d->date);
            $data['Prescriptionlist'][$count]->patient_email=$patient[0]->email;
            $data['Prescriptionlist'][$count]->patient_address=$patient[0]->address;
            $data['Prescriptionlist'][$count]->patient_phone=$patient[0]->phone;
            $data['Prescriptionlist'][$count]->url=base_url()."api/viewPrescription?id=".$d->id;
            $data['Prescriptionlist'][$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;
            if($patient[0]->img_url!='') {
                $data['Prescriptionlist'][$count]->patient_photo=base_url().$patient[0]->img_url;
            }else{
                $data['Prescriptionlist'][$count]->patient_photo=null;
            }
            $medicine = $data['Prescriptionlist'][$count]->medicine;
            $medicine = explode("###", $medicine);
            $medicine_array=array();
            foreach ($medicine as $key => $value) {
                
                $single_medicine = explode("***", $value);
                $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name;
                $single_medicine_details['medicine_name']=$single_medicine_name;
                $single_medicine_details['mg']=$single_medicine[1];
                $single_medicine_details['frequency']=$single_medicine[2];
                $single_medicine_details['days']=$single_medicine[3];
                $single_medicine_details['instruction']=$single_medicine[4];
                // $single_medicine_instruction=$single_medicine[3] . ' - ' . $single_medicine[4];
                // $single_medicine_frequency=$single_medicine[2];

                array_push($medicine_array,$single_medicine_details);
            }
            $data['Prescriptionlist'][$count]->medicine=$medicine_array;
            $count++;
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    function allLabbydoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $doctor_id    = $_POST['doctor_id'];
        $this->db->order_by('id', 'desc');
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('lab');
        $data['lablist']=$query->result();
        $count=0;
        foreach ($data['lablist'] as $d) {

            $this->db->where('id', $d->patient);
            $query = $this->db->get('patient');
            $patient=$query->result();

            $data['lablist'][$count]->date=date('d-m-Y', $d->date);
            $data['lablist'][$count]->patient_email=$patient[0]->email;
            if($patient[0]->img_url!='') {
                $data['lablist'][$count]->patient_photo=base_url().$patient[0]->img_url;
            }else{
                $data['lablist'][$count]->patient_photo=null;
            }
            $data['lablist'][$count]->url=base_url()."api/viewlabreport?id=".$d->id;

            $this->db->where('id',$d->template_id);
            $template_query = $this->db->get('template');
            $template_data =  $template_query->result();
            $data['lablist'][$count]->template_name=$template_data[0]->name;
            $count++;
        }
        if (count($data) >= 1) {
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"All Lablist by Doctor"]);
        }else{
            $blank_object=new stdClass();
            array_push($data, $blank_object);
            echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
        }

    
    }
    function getrepportbypatientid() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id    = $_POST['patient_id'];
        $this->db->where('patient', $patient_id);
        $this->db->order_by('patient', 'asc');
        $query = $this->db->get('report');
        $data=$query->result();
        if (count($data) >= 1) {
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"All Report by Doctor"]);
        }else{
            $blank_object=new stdClass();
            array_push($data, $blank_object);
            echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No Data Found"]);
        }
    }
    function getrepportbydoctorid() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $doctor_id    = $_POST['doctor_id'];
        $this->db->where('doctor', $doctor_id);
        $this->db->order_by('doctor', 'asc');
        $query = $this->db->get('report');
        $data=$query->result();
        echo json_encode(['data'=>$data,'status'=>'true','message'=>'Report by doctor id']);
    }
    function addrepport() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata"); 
        $this->form_validation->set_rules('doctor_id', 'Doctor ', 'required');
        $this->form_validation->set_rules('patient_id', 'Patient ', 'required');
        $this->form_validation->set_rules('report_type', 'Report type', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        if ($this->form_validation->run() == true) {
            $doctor_id    = $_POST['doctor_id'];
            $patient_id    = $_POST['patient_id'];
            $report_type    = $_POST['report_type'];
            $description    = $_POST['description'];
            $date    = $_POST['date'];
            $p_data=array(
                'doctor'=>$doctor_id,
                'patient'=>$patient_id,
                'report_type'=>$report_type,
                'description'=>$description,
                'date'=>strtotime($date),
                'add_date'=>time()
            );
            $p_result=$this->db->insert('report', $p_data);
            if ($p_result==true) {
                $result1=new stdClass();
                echo json_encode(['data'=>$result1,'status'=>'true','message'=>'successfully Created']);
            }else{
                $result1=new stdClass();
                echo json_encode(['data'=>$result1,'status'=>'true','message'=>'unsuccessfull']);
            }
        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }   
    function appointment_by_id() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata");
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        if ($this->form_validation->run() == true) {
            $appointment_id    = $_POST['appointment_id'];

            $this->db->where('id', $appointment_id);
            $query = $this->db->get('appointment');
            $appointment=$query->result();

            $this->db->where('id', $appointment[0]->patient);
            $query = $this->db->get('patient');
            $patient=$query->result();

            $this->db->where('id', $patient[0]->ion_user_id);
            $query = $this->db->get('users');
            $users=$query->result();

            $this->db->where('id', $appointment[0]->location_id);
            $query = $this->db->get('location');
            $location=$query->result();

            $this->db->where('id', $appointment[0]->doctor);
            $query = $this->db->get('doctor');
            $doctor=$query->result();

            $this->db->where('id',$appointment[0]->status);
            $status_query = $this->db->get('appointment_status');
            $status_data =  $status_query->result();

            $data=[
                'appointment_id'=>$appointment[0]->id,
                'appointment_date'=>$appointment[0]->date,
                'appointment_time_slot'=>$appointment[0]->time_slot,
                'appointment_start_time'=>$appointment[0]->s_time,
                'appointment_end_time'=>$appointment[0]->e_time,
                'appointment_remarks'=>$appointment[0]->remarks,
                'appointment_registration_time'=>date('d-m-Y h:i A',$appointment[0]->registration_time),
                'appointment_room_id'=>$appointment[0]->room_id,
                'appointment_live_meeting_link'=>$appointment[0]->live_meeting_link,
                'appointment_app_time'=>$appointment[0]->app_time,
                'appointment_app_time_full_format'=>$appointment[0]->app_time_full_format,
                'appointment_price'=>$appointment[0]->price,
                'appointment_description'=>$appointment[0]->description,
                'mode_of_consultation'=>$appointment[0]->mode_of_consultation,
                'type_of_consultation'=>$appointment[0]->type_of_consultation,
                'status'=>$appointment[0]->status,
                'status_name'=>$status_data[0]->status_name,
                'patient_type'=>$appointment[0]->patient_type,
                'doctor_id'=>$appointment[0]->doctor,
                'doctor_name'=>$doctor[0]->name,
                'patient_name'=>$patient[0]->name,
                'patient_email'=>$patient[0]->email,
                'location_name'=>$location[0]->name,
                'reasone'=>$appointment[0]->description,
                'patient_register_date'=>date("d-m-Y h:i A",$users[0]->created_on),
            ];
                $this->db->where('booking_id', $appointment_id);
                $this->db->where('order_status', 'Confirmed');
                $query = $this->db->get('payment');
                $payment=$query->result();
            if (count($payment)>=1) {
                

                $data['txnID']=$payment[0]->txnID;
                $data['order_id']=$payment[0]->order_id;
                $data['txn_amount']=$payment[0]->amount;
                $data['txn_date']=date("d-m-Y h:i A",$payment[0]->created_at);
            }else{
                $data['txnID']=null;
                $data['order_id']=null;
                $data['txn_amount']=null;
                $data['txn_date']=null;
            }
            $this->db->where('appointment_id', $appointment_id);
                $query = $this->db->get('cancellation_request');
                $cancellation_request=$query->result();
            if (count($cancellation_request)>=1) {
                $data['reasone']=$cancellation_request[0]->reasone;
            }
            
            if(strtotime($appointment[0]->date) <= strtotime(date("d-m-Y"))){
                
                $data['postpone']="n";
                $data['cancel']="n";
            }else{
                $data['postpone']="y";
                $data['cancel']="y";
            }
            if(strtotime($appointment[0]->date) == strtotime(date("d-m-Y"))){
                if(strtotime($appointment[0]->s_time) <= strtotime(date("h:i A"))- (1 * 60 * 60)){
                    $data['postpone']="n";
                    $data['cancel']="n";
                    // unset($datanew[$data[$i]['name']][$count]);
                }
                else{
                    $data['postpone']="y";
                    $data['cancel']="y";
                }
            }
            

            if (count($appointment)>=1) {
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Appointment by id']);
            }else{
                $result1=new stdClass();
                echo json_encode(['data'=>$result1,'status'=>'true','message'=>'No Data Found']);
            }
        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    } 
    function getMedicine() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('medicine');
        $medicine= $query->result();
        if (count($medicine)>=1) {
            echo json_encode(['data'=>$medicine,'status'=>'true','message'=>'All Medicine']);
        }else{
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>'No Medicine Found']);
        }
    }
    function postpone(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata");

        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('time_slot', 'Time Slot', 'required');
        
        if ($this->form_validation->run() == true) {

            //2009-10-22
            $appointment_id= $_POST['appointment_id'];
            $date= $_POST['date'];
            if (empty($_POST['location_id'])) {
                $location_id= 0;
            }else{
                $location_id= $_POST['location_id'];
            }
            
            $time_slot= $_POST['time_slot'];
            // $doctor_id= $_POST['doctor_id'];
            $time_slot1 = str_replace(["TO","To"],"to",$time_slot);
            $time_slot_explode= explode('to', $time_slot1);
            $s_time = trim($time_slot_explode[0]);
            $e_time = trim($time_slot_explode[1]);
            $reason= $_POST['reason'];
            $send_email= $_POST['send_email'];

            //get appointment data
            $this->db->where('id',$appointment_id);
            $query = $this->db->get('appointment');
            $appointment_details = $query->result();

            // Insert data 
            $additional_data = array(
                'patient' => $appointment_details[0]->patient,
                'doctor' => $appointment_details[0]->doctor,
                'date' => $appointment_details[0]->date,
                'time_slot' => $appointment_details[0]->time_slot,
                'registration_time' =>$appointment_details[0]->registration_time,
                'status' => $appointment_details[0]->status,
                'mode_of_consultation' => $appointment_details[0]->mode_of_consultation,
                'type_of_consultation' => $appointment_details[0]->type_of_consultation,
                'location_id' => $appointment_details[0]->location_id,
                'price' => $appointment_details[0]->price,
                'description' => $reason,
                'patient_type' => $appointment_details[0]->patient_type,
                'appointment_id' => $appointment_details[0]->id,
                'created_at' =>time()

            );

            $this->db->insert('appointment_history', $additional_data);
            // $check =$this->db->last_query();

            // Update old data 
            $datas = array(
                'date' => date('d-m-Y', strtotime($date)),
                'time_slot' => $time_slot,
                's_time' => $s_time,
                'e_time' => $e_time,
                'registration_time' => time()
                );
                
            $this->db->update('appointment', $datas, array('id' => $appointment_id));
            $this->db->update('cancellation_request', array('flag' => 1), array('appointment_id' => $appointment_id));

            $this->db->where('id',$appointment_id);
            $query = $this->db->get('appointment');
            $new_appointment_details = $query->result();

            $this->db->where('id',$new_appointment_details[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$patient_data[0]->ion_user_id);
            $user_query = $this->db->get('users');
            $user_data =  $user_query->result();

            $this->db->where('id',$new_appointment_details[0]->status_id);
            $status_query = $this->db->get('appointment_status');
            $status_data =  $status_query->result();
        
            $this->db->where('id',$new_appointment_details[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();
        
            $data_result=array();
            $data_result['appointment_id']=$new_appointment_details[0]->id;
            $data_result['patientid']=$new_appointment_details[0]->patient;
            $data_result['patientname']=$patient_data[0]->name;
            $data_result['BookingDate']=$new_appointment_details[0]->date;
            $data_result['BookingTime']=$new_appointment_details[0]->time_slot;
            $data_result['LocationName']=$location_data[0]->name;
            $data_result['LocationId']=$new_appointment_details[0]->location_id;
            $data_result['statusId']=$new_appointment_details[0]->status;
            $data_result['StatusName']=$status_data[0]->status_name;
            $data_result['user_id']=$patient_data[0]->ion_user_id;
            if($patient_data[0]->img_url!='') {
                $patient_data[0]->img_url=base_url().$patient_data[0]->img_url;
            }
            $data_result['profile_img']=$patient_data[0]->img_url;

            $this->db->where('user_id', $patient_data[0]->ion_user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();

            $data_addedd = array
                    (
                        'type'   => 'Postponed',
                        'id'     => $new_appointment_details[0]->id,
                        'patient_id'     => $new_appointment_details[0]->patient,
                        'user_id' => $patient_data[0]->ion_user_id,
                        'title'=> 'Booking Postponed',
                        'msg'  => 'Your appointment #'.$appointment_id .' has been postpond successfully by Dr. Sudhir Bhola. Now your new appointment booking date is '.$new_appointment_details[0]->date.' at '.$new_appointment_details[0]->time_slot.'.',
            );
                    // $n_data = array();
            $n_data = array(
                        'user_id' => $patient_data[0]->ion_user_id,
                        'type' => $data_addedd['type'],
                        'message' => $data_addedd['msg'],
                        'title'=> 'Booking Postponed',
                        'profile_img' => $patient_data[0]->img_url,
                        'appointment_id' => $new_appointment_details[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
            );
                                    
            $n_result=$this->db->insert('notification', $n_data);
            foreach ($user_device_data as $ud) {
                $deviceToken =$ud->device_token;
                $deviceType = $ud->device_type;
                _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
            }
            
            echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"Booking successfully postponed"]);
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function getHolidaysByDoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
        if ($this->form_validation->run() == true) {

            $id= $_POST['doctor_id'];

            $this->db->order_by('id', 'asc');
            $this->db->where('doctor', $id);
            $query = $this->db->get('holidays');
            $return = $query->result();
            // $array=[];
            $count=0;
            foreach ($return as $r) {
                $return[$count]->date=date('d-m-Y',$r->date);
                $return[$count]->created_at=date('d-m-Y h:i A',$r->created_at);
                $count++;
            }
            echo json_encode(['data'=>$return,'status'=>"true",'message'=>"All Holidays By Doctor"]);
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  cancle_appointment(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');
        if ($this->form_validation->run() == true) {
            $appointment_id= $_POST['appointment_id'];
            $reason= $_POST['reason'];

            //get appointment data
            $this->db->where('id',$appointment_id);
            $query = $this->db->get('appointment');
            $appointment_details = $query->result();

            // Update old data 
             $datas = array(
                'status' => 8,
                "description"=> $reason              
            );
                
            $this->db->update('appointment', $datas, array('id' => $appointment_id));
            $this->db->update('cancellation_request', array('flag' => 1), array('appointment_id' => $appointment_id));
            
            $this->db->where('id',$appointment_details[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$patient_data[0]->ion_user_id);
            $user_query = $this->db->get('users');
            $user_data =  $user_query->result();
        
            $return = $this->db->affected_rows() == 1;
            if ($return){
                $this->db->where('user_id', $patient_data[0]->ion_user_id);
                $this->db->where('status', 1);
                $user_device_query = $this->db->get('user_device');
                $user_device_data = $user_device_query->result();

                    $data_addedd = array
                            (
                                'type'   => 'Cancled',
                                'title'   => 'Booking Cancelled',
                                'id'     => $appointment_details[0]->id,
                                'patient_id'     => $appointment_details[0]->patient,
                                'user_id' => $patient_data[0]->ion_user_id,
                                'msg'  => 'Your appointment #'.$appointment_id .' has been Cancelled by Dr. Sudhir Bhola.',
                            );
                    date_default_timezone_set("Asia/Kolkata"); 
                    $n_data = array(
                        'user_id' => $patient_data[0]->ion_user_id,
                        'title' => $data_addedd['title'],
                        'type' => $data_addedd['type'],
                        'message' => $data_addedd['msg'],
                        'profile_img' => $patient_data[0]->img_url,
                        'appointment_id' => $appointment_details[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
                    );
                                    
                    $n_result=$this->db->insert('notification', $n_data);

                foreach ($user_device_data as $ud) {
                    $deviceToken =$ud->device_token;
                    $deviceType = $ud->device_type;
                    
                    _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
                }
                
            }  
            echo json_encode(['data'=>$appointment_details,'status'=>"true",'message'=>"Booking Cancelled"]);
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function getTemplate() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('template');
        $return= $query->result();
        echo json_encode(['data'=>$return,'status'=>"true",'message'=>"All Lab Templates"]);
    }

    function getTemplateById() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $id= $_POST['template_id'];

        $this->db->where('id', $id);
        $query = $this->db->get('template');
        $return= $query->row();
        echo json_encode(['data'=>$return,'status'=>"true",'message'=>"Lab Template by Id"]);
    }
    function getAutoEmailTemplate() {
        $this->db->order_by('id', 'asc');
        $query = $this->db->get('autoemailtemplate');
        $return= $query->result();
        echo json_encode(['data'=>$return,'status'=>"true",'message'=>"All Email Templates"]);
    }

    function prescription_autofill() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('input_name', 'Input name', 'required');
        $this->form_validation->set_rules('value', 'Value Id', 'required');
        if ($this->form_validation->run() == true) {
            $input_name= $_POST['input_name'];
            $value= $_POST['value'];
            
            $this->db->where('name', $input_name);
            $this->db->like('value', $value);
            $query = $this->db->get('prescription_autofill_data');
            $return= $query->result();
            if (count($return)>0) {
                echo json_encode(['data'=>$return,'status'=>"false",'message'=>"Already exits"]);
            }else{
                date_default_timezone_set("Asia/Kolkata"); 
                $p_data = array(
                    'name' => $input_name,
                    'value' => $value,
                    'created_at' => time(),
                );
                $result=$this->db->insert('prescription_autofill_data', $p_data);
                echo json_encode(['data'=>$result,'status'=>"true",'message'=>"Successfully Save"]);
            }
        }else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function get_prescription_autofill() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->form_validation->set_rules('input_name', 'Input name', 'required');
        $this->form_validation->set_rules('value', 'Value Id', 'required');
        if ($this->form_validation->run() == true) {

            $input_name= $_POST['input_name'];
            $value= $_POST['value'];

            $this->db->where('name', $input_name);
            $this->db->like('value', $value);
            $query = $this->db->get('prescription_autofill_data');
            $return= $query->result();

            if (count($return)>0) {
                foreach ($return as $r) {
                    $data[]=$r->value;
                }
                echo json_encode(['data'=>$data,'status'=>"true",'message'=>"All data"]);
                
            }else{
                echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
            }

            
        }else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		    echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }

    function viewlabreport() {
        $data = array();
        $id = $this->input->get('id');
        
        $data['settings'] = $this->settings_model->getSettings();
        $data['lab'] = $this->lab_model->getLabById($id);
        $logo=base_url().$data['settings']->logo; 
        $patient_info = $this->db->get_where('patient', array('id' => $data['lab']->patient))->row();
        $lab_date=date('d-m-Y', $data['lab']->date);
        if (!empty($data['lab']->doctor)) {
            $doctor_details = $this->doctor_model->getDoctorById($data['lab']->doctor);
            if (!empty($doctor_details)) {
                $doctor_name= $doctor_details->name;
            }
        }
        $return ='<!DOCTYPE html>
                <html>
                <head>
                <title></title>
                        <link href="'.base_url().'common/extranal/css/lab/invoice.css" rel="stylesheet">
                        <link rel="stylesheet" href="'.base_url().'common/css/bootstrap.min.css">
                        <style>

                        .panel-body {
                            background: #fff !important;
							padding:0px !important;
                        }
						td, th{font-size:14px;}
						.medicine_div {
                            padding-left: 0px !important;
                        }
                        body{
                            background: #fff !important;
                            padding:0px !important;
                        }
                        table{
                            background: #fff !important;
                            padding:0px !important;
                        }
												.control-label {
							width: 80px;
						}
						
						.invoice_info {
							font-size: 12px;
						}
					
                        </style>
                </head>
                    <body style="background: #fff !important;padding: 0px;width: 100%;width: 640px;min-width:640px;margin: 0 auto;">
                        <section class="">
                            <div class="panel-body invoice_info">
                                <div class="row invoice-list">
                                    <div class="text-center corporate-id">
                                        <img alt="" src="'. $logo .'" width="200">
                                        <h3><strong>
                                            '. $data['settings']->title .'</strong>
                                        </h3>
                                        <h4><strong>
                                            '. $data['settings']->address .'</strong>
                                        </h4>
                                        <h4><strong>
                                            Tel: '. $data['settings']->phone .'</strong>
                                        </h4>
                                        
                                        <h4 class="lang_lab">
                                            Lab Report 
                                        
                                        </h4>
										    <hr class="lang_lab_hr" style="width: 100%;margin-top: 20px;">
                                    </div>
                                    <div class="col-md-12 mt-4" style="margin-top: 40px;">
                                        <div class="col-md-5 row patient_info" style="width: 50%;float: left;">
                                            <div class="">
                                                <p>
                                                <label class="control-label"><strong>Patient Name</strong> </label>
                                                <span class="patient_name"> : 
                                                '.@$patient_info->name .'
                                                </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                <label class="control-label"><strong>Patient Id  </strong></label>
                                                <span class="patient_name"> : 
                                                '. @$patient_info->id .'
                                                </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                <label class="control-label"> <strong>Address</strong> </label>
                                                <span class="patient_name"> : 
                                                '.@$patient_info->address .'
                                                </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                <label class="control-label"><strong>Phone</strong> </label>
                                                <span class="patient_name"> : 
                                                '.@$patient_info->phone.'
                                                </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 patient_info row " style="width: 40%;float: left;">
                                            <div class="">
                                                <p>
                                                <label class="control-label"> <strong>Lab Report Id </strong> </label>
                                                <span class="patient_name"> : 
                                                '.@$data['lab']->id.'
                                                </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                <label class="control-label"><strong>Date  </strong></label>
                                                <span class="patient_name"> : 
                                                '.@$lab_date.'
                                                </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                <label class="control-label"><strong>Doctor  </strong></label>
                                                <span class="patient_name"> : 
                                                '.@$doctor_name.'
                                                </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    </div>
                                    <div class="col-md-12 panel-body">
                                    '.$data['lab']->report.'
                                    </div>
                                </div>
                        </section>
                    </body>
                </html>';
                   
                    $this->pdf->createPDF($return, 'labreport', true);
                    // echo $return;
                    // echo phpinfo();
    }
    
    function getDispatches() {
        
        $query = $this->db->select("*");
        // $query = $this->db->where('prescription.dispatches_status','0');
        $query = $this->db->get('prescription');
        $result= $query->result();
        if (count($result)>=1) {
            $count=0;
            foreach ($result as $d) {

                $this->db->where('id', $d->patient);
                $patient_query = $this->db->get('patient');
                $patient_result= $patient_query->result();

                $result[$count]->patientname=$patient_result[0]->name;
                $result[$count]->patient_email=$patient_result[0]->email;
                $result[$count]->patient_phone=$patient_result[0]->phone;
                $result[$count]->patient_address=$patient_result[0]->address;
                $result[$count]->img_url=base_url().$patient_result[0]->img_url;

                $result[$count]->date=date('d-m-Y',$d->date);
                $result[$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $result[$count]->tracking_url=base_url()."api/viewPrescription?id=".$d->id;
                $count++;
            }
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Dispatched List"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        } 
    }

    ///////////////// Pharmachist //////////////////////////////////////////////////////

    function getPharmacist() {
        $query = $this->db->get('pharmacist');
        $result= $query->result();

        if (count($result)>=1) {
            $count=0;
            foreach ($result as $d) {
                $query = $this->db->where('id',$d->ion_user_id);
                $user_query = $this->db->get('users');
                $user_result= $user_query->result();
                    // $result[$count]->date=date('d-m-Y',$d->date);
                    $result[$count]->created_on=date('d-m-Y h:i A',$user_result[0]->created_on);
                    // $result[$count]->updated_at=date('d-m-Y h:i A',strtotime($d->updated_at));
                    $result[$count]->img_url=base_url().$d->img_url;
                $count++;
            }
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Pharmacist List"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        }
    }
    function getDispatchedlist() {
        
        $query = $this->db->select("*");
        $query = $this->db->where('prescription.dispatches_status','1');
        $query = $this->db->get('prescription');
        $result= $query->result();
        if (count($result)>=1) {
            $count=0;
            foreach ($result as $d) {

                $this->db->where('id', $d->patient);
                $patient_query = $this->db->get('patient');
                $patient_result= $patient_query->result();

                $result[$count]->patientname=$patient_result[0]->name;
                $result[$count]->patient_email=$patient_result[0]->email;
                $result[$count]->patient_phone=$patient_result[0]->phone;
                $result[$count]->patient_address=$patient_result[0]->address;
                $result[$count]->img_url=base_url().$patient_result[0]->img_url;
                
                $result[$count]->date=date('d-m-Y',$d->date);
                $result[$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $result[$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $result[$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;
                $count++;
            }
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Dispatched List"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        } 
        
    }
    function getPendingDispatches() {
        
        $query = $this->db->select("p.*");
        $query = $this->db->from('prescription p');
        $query = $this->db->where('p.dispatches_status','0');
        $query = $this->db->where('i.due','0');
        $query = $this->db->join('invoice i','p.appointment_id = i.appointment_id' , 'left');
        $query = $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        $result= $query->result();
        if (count($result)>=1) {
            $count=0;
            foreach ($result as $d) {

                $this->db->where('id', $d->patient);
                $patient_query = $this->db->get('patient');
                $patient_result= $patient_query->result();

                $result[$count]->patientname=$patient_result[0]->name;
                $result[$count]->patient_email=$patient_result[0]->email;
                $result[$count]->patient_phone=$patient_result[0]->phone;
                $result[$count]->patient_address=$patient_result[0]->address;
                $result[$count]->img_url=base_url().$patient_result[0]->img_url;
                
                $result[$count]->date=date('d-m-Y',$d->date);
                $result[$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $result[$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $result[$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;
                $count++;
            }
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Dispatched List"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        } 
    }
    function getExpense() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('pharmacy_expense');
        $result= $query->result();
        if (count($result)>=1) {
            $count=0;
            foreach ($result as $d) {
                $result[$count]->date=date('d-m-Y h:i A',$d->date);
                // $result[$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                // $result[$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $count++;
            }
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Expense List"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        }
    }
    function getExpenseById() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->form_validation->set_rules('expense_id', 'Expense Id', 'required');
        if ($this->form_validation->run() == true) {

            $id= $_POST['expense_id'];
            $this->db->where('id', $id);
            $query = $this->db->get('pharmacy_expense');
            $result= $query->result();
            if (!empty($result[0])) {

                $result[0]->date=date('d-m-Y h:i A',$result[0]->date);

                echo json_encode(['data'=>$result[0],'status'=>"true",'message'=>"Expense By Id"]);
            }else{
                $return = new stdClass();
                echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
            }
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }        
    }
    function getDispatchesbypatientid() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->form_validation->set_rules('patient_id', 'Patient Id', 'required');
        if ($this->form_validation->run() == true) {

            $patient_id= $_POST['patient_id'];
            $query = $this->db->where('patient',$patient_id);
            $query = $this->db->get('prescription');
            $result= $query->result();
            if (count($result)>=1) {
                $count=0;
                foreach ($result as $d) {

                    $this->db->where('id', $d->patient);
                    $patient_query = $this->db->get('patient');
                    $patient_result= $patient_query->result();

                    $result[$count]->patientname=$patient_result[0]->name;
                    $result[$count]->patient_email=$patient_result[0]->email;
                    $result[$count]->patient_phone=$patient_result[0]->phone;
                    $result[$count]->patient_address=$patient_result[0]->address;
                    $result[$count]->img_url=base_url().$patient_result[0]->img_url;

                    $result[$count]->date=date('d-m-Y',$d->date);
                    $result[$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                    $result[$count]->tracking_url=base_url()."api/viewPrescription?id=".$d->id;
                    $count++;
                }
                echo json_encode(['data'=>$result,'status'=>"true",'message'=>"All Dispatched List"]);
            }else{
                $return=array();
                echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
            } 
        }else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function getDispatchescounts() {
        
        $query = $this->db->select("*");
        // $query = $this->db->where('prescription.dispatches_status','0');
        $query = $this->db->get('prescription');
        $result= $query->result();

        $query = $this->db->select("*");
        $query = $this->db->where('dispatches_status','0');
        $query = $this->db->get('prescription');
        $pending= $query->result();

        $query = $this->db->select("*");
        $query = $this->db->where('dispatches_status','1');
        $query = $this->db->get('prescription');
        $dispatched= $query->result();

        $data=array();
        if (count($result)>=1) {
            $data['total']=count($result);
            $data['pending']=count($pending);
            $data['dispatched']=count($dispatched);
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"All Dispatched Count"]);
        }else{
            $return=array();
            echo json_encode(['data'=>$return,'status'=>"false",'message'=>"No data found"]);
        } 
    }
    function getnotificationBypharmacistid() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $this->db->where('id',$_POST['pharmacist_id']);
        $pharmacist_query = $this->db->get('pharmacist');
        $pharmacist_result= $pharmacist_query->result();
        
        $this->db->where('user_id',$pharmacist_result[0]->ion_user_id);
        $this->db->order_by('id', 'desc');
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();
        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->notification_date=date('d-m-Y', $n->created_at);
            $notification_result[$count]->notification_time=date('h:i A', $n->created_at);

            $this->db->where('id', $n->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_result = $appointment_query->result();

            $this->db->where('id', $appointment_result[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_result = $patient_query->result();

            $this->db->where('id', $appointment_result[0]->location_id);
            $location_query = $this->db->get('location');
            $location_result = $location_query->result();

            // $notification_result[$count]->bookID=$appointment_result[0]->id;
            $notification_result[$count]->username=$patient_result[0]->name;
            if($n->profile_img!='') {
                $notification_result[$count]->profile_img=base_url().$n->profile_img;
            }
            $notification_result[$count]->type=ucfirst($n->type);
            $notification_result[$count]->time_slot=$appointment_result[0]->s_time;
            $notification_result[$count]->booking_date=$appointment_result[0]->date;
            $notification_result[$count]->location_id=$appointment_result[0]->location_id;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            $notification_result[$count]->patient_type=$appointment_result[0]->patient_type;
            // $notification_result[$count]->type_of_user='';
            
            
            $count++;
        }

        echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"success"]);

    
    }
    public function getalldispatches() {
        
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('prescription');
        date_default_timezone_set("Asia/Kolkata"); 
        
        $data['Prescriptionlist'] = $query->result();
        $count=0;
        foreach ($data['Prescriptionlist'] as $d) {

                $this->db->where('prescription_id',$d->id);
                $dispatches_query = $this->db->get('dispatches');
                $dispatches_data =  $dispatches_query->result();
                if (!empty($dispatches_data)) {
                    $this->db->where('id',$dispatches_data[0]->company_id);
                    $shipping_company = $this->db->get('shipping_companies')->result();

                    $data['Prescriptionlist'][$count]->company_id=$shipping_company[0]->id;
                    $data['Prescriptionlist'][$count]->company_name=$shipping_company[0]->name;
                    $data['Prescriptionlist'][$count]->traking_id=$dispatches_data[0]->tracking_id;
                    $data['Prescriptionlist'][$count]->traking_url=$shipping_company[0]->url."?id=".$dispatches_data[0]->tracking_id;
                }else{
                    $data['Prescriptionlist'][$count]->traking_id=null;
                    $data['Prescriptionlist'][$count]->traking_url=null;
                }
                $data['Prescriptionlist'][$count]->date=date('d-m-Y', $d->date);
                $data['Prescriptionlist'][$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $data['Prescriptionlist'][$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $data['Prescriptionlist'][$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;

                // $this->db->where('id',$d->appointment_id);
                // $query2 = $this->db->get('appointment');
                // $appointment_details = $query2->result();

                $this->db->where('id',$d->patient);
                $patient_query = $this->db->get('patient');
                $patient_data =  $patient_query->result();

                $data['Prescriptionlist'][$count]->patientname=$patient_data[0]->name;
                $data['Prescriptionlist'][$count]->patientemail=$patient_data[0]->email;
                $data['Prescriptionlist'][$count]->patientaddress=$patient_data[0]->address;
                $data['Prescriptionlist'][$count]->patientphone=$patient_data[0]->phone;
                $data['Prescriptionlist'][$count]->patient_img_url=base_url().$patient_data[0]->img_url;

            $count++;
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    function update_dispatches_status() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata"); 

        $this->form_validation->set_rules('prescription_id', 'Prescription Id', 'required');
        $this->form_validation->set_rules('traking_id', 'Tracking Id', 'required');
        if ($this->form_validation->run() == true) {

            $prescription_id= $_POST['prescription_id'];
            $company_id= $_POST['company_id'];
            $traking_id= $_POST['traking_id'];
            $next_date= $_POST['date'];

            $query = $this->db->where('prescription_id',$prescription_id);
            $query = $this->db->get('dispatches');
            $result= $query->result();

            if (count($result)>=1) {
                echo json_encode(['data'=>$result[0],'status'=>"false",'message'=>"Selected Product Already Dispatched!"]);
            }else{
                
                $prescription_data=['dispatches_status'=>1];
                $dispatches_data=[
                                    'prescription_id'=>$prescription_id,
                                    'tracking_id'=>$traking_id,
                                    'company_id'=>$company_id,
                                    'status'=>1,
                                    'updated_by'=>"mobile_app",
                                    'date'=>strtotime($next_date),
                                    'created_at'=>time()
                                ];

                $this->db->where('id', $prescription_id);
                $p_result = $this->db->update('prescription', $prescription_data); 

                $d_result=$this->db->insert('dispatches', $dispatches_data);

                $this->db->where('id',$prescription_id);
                $prescription_query = $this->db->get('prescription');
                $prescription_data =  $prescription_query->result();

                $this->db->where('prescription_id',$prescription_id);
                $dispatches_query = $this->db->get('dispatches');
                $dispatches_data =  $dispatches_query->result();
                
                if (!empty($dispatches_data)) {

                    $this->db->where('id',$dispatches_data[0]->company_id);
                    $shipping_company = $this->db->get('shipping_companies')->result();

                    $prescription_data[0]->company_id=$shipping_company[0]->id;
                    $prescription_data[0]->company_name=$shipping_company[0]->name;
                    $prescription_data[0]->traking_id=$dispatches_data[0]->tracking_id;
                    $prescription_data[0]->traking_url=$shipping_company[0]->url."?id=".$dispatches_data[0]->tracking_id;
                }else{
                    $prescription_data[0]->traking_id=null;
                    $prescription_data[0]->traking_url=null;
                }
                $prescription_data[0]->next_date=date('d-m-Y', $prescription_data[0]->date);
                $prescription_data[0]->created_at=date('d-m-Y h:i A',$prescription_data[0]->created_at);
                $prescription_data[0]->url=base_url()."api/viewPrescription?id=".$prescription_data[0]->id;
                $prescription_data[0]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$prescription_data[0]->id;

                $this->db->where('id',$prescription_data[0]->appointment_id);
                $query2 = $this->db->get('appointment');
                $appointment_details = $query2->result();

                $this->db->where('id',$appointment_details[0]->patient);
                $patient_query = $this->db->get('patient');
                $patient_data =  $patient_query->result();

                $this->db->where('id',$patient_data[0]->ion_user_id);
                $p_user_query = $this->db->get('users');
                $p_user_data =  $p_user_query->result();

                $this->db->where('user_id', $patient_data[0]->ion_user_id);
                $this->db->where('status', 1);
                $user_device_query = $this->db->get('user_device');
                $user_device_data = $user_device_query->result();

                    $data_addedd = array
                    (
                        'type'   => 'patient_dispatched_notification',
                        'id'     => $prescription_data[0]->appointment_id,
                        'patient_id'     => $appointment_details[0]->patient,
                        'user_id' => $patient_data[0]->ion_user_id,
                        'title'=>"Dispatched",
                        'msg'  => 'Your prescription #'.$prescription_data[0]->id .' has been dispatched.',
                    );
                    $n_data = array();
                    date_default_timezone_set("Asia/Kolkata"); 
                    $n_data = array(
                        'user_id' => $patient_data[0]->ion_user_id,
                        'type' => $data_addedd['type'],
                        'message' => $data_addedd['msg'],
                        'title'=>"Dispatched",
                        'profile_img' => $patient_data[0]->img_url,
                        'appointment_id' => $appointment_details[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
                    );
                            
                    $n_result=$this->db->insert('notification', $n_data);

                foreach ($user_device_data as $ud) {
                    $deviceToken =$ud->device_token;
                    $deviceType = $ud->device_type;
                    
                    _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd); 
                }
                
                    
                    //doctor
                    $this->db->where('id',$appointment_details[0]->doctor);
                    $doctor_query = $this->db->get('doctor');
                    $doctor_data =  $doctor_query->result();

                    $this->db->where('id',$doctor_data[0]->ion_user_id);
                    $user_query = $this->db->get('users');
                    $user_data =  $user_query->result();

                    $this->db->where('user_id', $doctor_data[0]->ion_user_id);
                    $this->db->where('status', 1);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_data = $user_device_query->result();

                   
                    $data_addedd2 = array
                    (
                        'type'   => 'doctor_dispatched_notification',
                        'id'     => $prescription_data[0]->appointment_id,
                        'patient_id'     => $appointment_details[0]->patient,
                        'title'=>"Dispatched",
                        'user_id' => $doctor_data[0]->ion_user_id,
                        'msg'  => 'Prescription #'.$prescription_data[0]->id .' has been dispatched.',
                    );
                    $n_data2 = array();
                    date_default_timezone_set("Asia/Kolkata"); 
                    $n_data2 = array(
                        'user_id' => $doctor_data[0]->ion_user_id,
                        'type' => $data_addedd2['type'],
                        'title'=>"Dispatched",
                        'message' => $data_addedd2['msg'],
                        'profile_img' => $doctor_data[0]->img_url,
                        'appointment_id' => $appointment_details[0]->id,
                        'created_at' => time(),
                        'read_status' => 0
                    );
                            
                    $d_result=$this->db->insert('notification', $n_data2);

                    foreach ($user_device_data as $ud) {
                        $deviceToken2 =$ud->device_token;
                        $deviceType2 = $ud->device_type;
                        
                    _send_fcm_notification($deviceToken2,$deviceType2,'',$data_addedd2); 
                    }
                
                    

                echo json_encode(['data'=>$prescription_data[0],'status'=>"true",'message'=>"Selected Product Dispatched!"]);
            } 
        }else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  cancle_and_postpone_request(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        $this->form_validation->set_rules('reasone', 'Reasone', 'required');
        if ($this->form_validation->run() == true) {

            $type= $_POST['type'];
            $appointment_id= $_POST['appointment_id'];
            $reasone= $_POST['reasone'];

            $this->db->where('appointment_id',$appointment_id);
            $this->db->where('type',$type);
            $query = $this->db->get('cancellation_request');
            $request_details = $query->result();

            if (count($request_details)>=1) {
                    $result1=new stdClass();
                    echo json_encode(['data'=>$result1,'status'=>'false','message'=>'Already send']);
            }else{
                date_default_timezone_set("Asia/Kolkata"); 
                $this->db->insert('cancellation_request', array('appointment_id' => $appointment_id,'type' => $type,'reasone' => $reasone,'created_at'=>time(),'flag'=>0));
                $request_id = $this->db->insert_id();

                $this->db->where('id',$request_id);
                $query1 = $this->db->get('cancellation_request');
                $inserted_request_details = $query1->result();

                $this->db->where('id',$appointment_id);
                $query2 = $this->db->get('appointment');
                $appointment_details = $query2->result();

                $this->db->where('id',$appointment_details[0]->doctor);
                $patient_query = $this->db->get('doctor');
                $patient_data =  $patient_query->result();

                $this->db->where('id',$patient_data[0]->ion_user_id);
                $user_query = $this->db->get('users');
                $user_data =  $user_query->result();
                $type_msg='';
                if ($type==8) {
                    $type_msg="cancel";
                    $type_msg1="Cancel";
                    $title_candp="Booking Cancellation Request";
                    $resposne_msg="Your cancellation request has been successfully submitted . Our support staff will contact you soon.";
                }
                if ($type==6) {
                    $type_msg="postpone";
                    $type_msg1="Postpone";
                    $title_candp="Booking Postpone request";
                    $resposne_msg="Your postpone request has been successfully submitted . Our support staff will contact you soon.";
                }
                // if ($type==8) {
                //     $type_msg1="Cancel";
                // }
                // if ($type==6) {
                //     $type_msg1="Postpone";
                // }
                if ($reasone==null) {
                    $reasone="NA";
                }

                $this->db->where('user_id', $patient_data[0]->ion_user_id);
                    $this->db->where('status', 1);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_data = $user_device_query->result();

                        $data_addedd = array
                        (
                            'type'   => $type_msg,
                            'id'     => $appointment_id,
                            'patient_id'     => $appointment_details[0]->patient,
                            'user_id' => $patient_data[0]->ion_user_id,
                            'title'=>$title_candp,
                            'msg'  => 'You received a '.$type_msg.' request for booking #'.$appointment_id.' with reason '.$reasone.'.',
                        );
                        $n_data = array();
                        date_default_timezone_set("Asia/Kolkata"); 
                        $n_data = array(
                            'user_id' => $patient_data[0]->ion_user_id,
                            'type' => $type_msg1,
                            'title'=>$title_candp,
                            'message' => $data_addedd['msg'],
                            'profile_img' => $patient_data[0]->img_url,
                            'appointment_id' => $appointment_details[0]->id,
                            'created_at' => time(),
                            'read_status' => 0
                        );
                                
                        $n_result=$this->db->insert('notification', $n_data);

                    foreach ($user_device_data as $ud) {
                        $deviceToken =$ud->device_token;
                        $deviceType = $ud->device_type;
                        
                        _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd); 
                    }
                
                    
                    
                    $inserted_request_details[0]->created_at=date('d-m-Y h:i A', $inserted_request_details[0]->created_at);
                
                    echo json_encode(['data'=>$inserted_request_details[0],'status'=>"true",'message'=>$resposne_msg]);
            }
            
        }else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
            echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }

    }
    function  all_cancle(){
        $this->db->order_by('id', 'desc');
        $this->db->where('type',8);
        $this->db->where('flag',0);
        $query = $this->db->get('cancellation_request');
        $request_details = $query->result();

        $count=0;
        foreach ($request_details as $r) {
            $this->db->where('id',8);
            $appointment_status_query = $this->db->get('appointment_status');
            $appointment_status_data =  $appointment_status_query->result();

            $this->db->where('id',$r->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_data =  $appointment_query->result();

            $this->db->where('id',$appointment_data[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$appointment_data[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();

            $request_details[$count]->status_name=$appointment_status_data[0]->status_name;
            $request_details[$count]->booking_date=$appointment_data[0]->date;
            $request_details[$count]->booking_time=$appointment_data[0]->s_time;
            $request_details[$count]->location_id=$appointment_data[0]->location_id;
            $request_details[$count]->location_name=$location_data[0]->name;
            $request_details[$count]->patient_name=$patient_data[0]->name;
            $request_details[$count]->created_at=date('d-m-Y h:i A',$r->created_at);
            $count++;
        }
        if (count($request_details)>=1) {
            echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Cancle Request"]);
        }else{
            $blank_object=new stdClass();
            array_push($request_details, $blank_object);
            echo json_encode(['data'=>$request_details,'status'=>"false",'message'=>"No Cancle Request Found"]);
        }

        
        
        
    }
    function  all_postpone(){
        $this->db->order_by('id', 'desc');
        $this->db->where('type',6);
        $this->db->where('flag',0);
        $query = $this->db->get('cancellation_request');
        $request_details = $query->result();

        $count=0;
        foreach ($request_details as $r) {
            $this->db->where('id',6);
            $appointment_status_query = $this->db->get('appointment_status');
            $appointment_status_data =  $appointment_status_query->result();

            $this->db->where('id',$r->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_data =  $appointment_query->result();

            $this->db->where('id',$appointment_data[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$appointment_data[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();

            $request_details[$count]->status_name=$appointment_status_data[0]->status_name;
            $request_details[$count]->booking_date=$appointment_data[0]->date;
            $request_details[$count]->booking_time=$appointment_data[0]->s_time;
            $request_details[$count]->location_id=$appointment_data[0]->location_id;
            $request_details[$count]->location_name=$location_data[0]->name;
            $request_details[$count]->patient_name=$patient_data[0]->name;
            $request_details[$count]->created_at=date('d-m-Y h:i A',$r->created_at);
            $count++;
        }
        if (count($request_details)>=1) {
            echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Postpond Request"]);
        }else{
            $blank_object=new stdClass();
            array_push($request_details, $blank_object);
            echo json_encode(['data'=>$request_details,'status'=>"false",'message'=>"No Postpond Request Found"]);
        }

        
    }
    function  all_cancle_and_postpone_by_appointment_id(){
        
        $this->db->where('appointment_id',$appointment_id);
        $query = $this->db->get('cancellation_request');
        $request_details = $query->result();
        echo json_encode(['data'=>$request_details[0],'status'=>"true",'message'=>"All Cancle and Postpond Request By Appointment ID"]);
    }
    function  setting_details(){
        
        $settings_query = $this->db->get('settings');
        $settings_details = $settings_query->result();

        $contact_details=[];
        $contact_details['logo']=base_url().$settings_details[0]->logo;
        $contact_details['title']=$settings_details[0]->title;
        $contact_details['address']=$settings_details[0]->address;
        $contact_details['phone']=$settings_details[0]->phone;
        $contact_details['email']=$settings_details[0]->email;
        echo json_encode(['data'=>$contact_details,'status'=>"true",'message'=>"All Setting details"]);
    }
    function getPatientByDoctorIdpending() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $locationid = $_POST['locationid'];
        $data = array();
        $this->db->where('doctor', $id);
        $this->db->where('status', 1);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('appointment');
        $appointments =$query->result();

        // $appointments = $this->appointment_model->getAppointmentByDoctor($id);
        $patient_array=array();
        $i=0;
        foreach ($appointments as $appointment) {
            $patient_exists = $this->patient_model->getPatientById($appointment->patient);
            $location= $this->db->where('id',$appointment->location_id)->get('location')->result();
            $user_data= $this->db->where('id',$patient_exists->ion_user_id)->get('users')->result();
            $doctor= $this->db->where('id',$appointment->patient)->get('doctor')->result();
            $status= $this->db->where('id',$appointment->status)->get('appointment_status')->result();

            if (!empty($patient_exists)) {
                $patient['patientid'] = $appointment->patient;
                $patient['patientname'] = $patient_exists->name;
                $patient['patientemail'] = $patient_exists->email;
                $patient['patientaddress'] = $patient_exists->address;
                $patient['patientphone'] = $patient_exists->phone;   
                if($patient_exists->img_url!='') {
                    $patient_exists->img_url=base_url().$patient_exists->img_url;
                }
                $patient['ProfilePhoto'] = $patient_exists->img_url;             
                $patient['BookingDate'] = $appointment->date;
                $patient['BookingTime'] = $appointment->s_time;
                $patient['mode_of_consultation'] = $appointment->mode_of_consultation;
                $patient['type_of_consultation'] = $appointment->type_of_consultation;
                $patient['membership_code'] = '';
                $patient['LocationId'] = $appointment->location_id;
                $patient['location'] = $location[0]->name;
                $patient['device_token'] = $user_data[0]->deviceToken;
                $patient['booking_id'] = $appointment->id;
                $patient['starttime'] = $appointment->s_time;
                $patient['endtime'] = $appointment->e_time;
                $patient['time_slot'] = $appointment->time_slot;
                $patient['status'] = $status[0]->status_name;
                $patient['price'] = $appointment->price;
                $patient['date'] = date('d-m-Y h:i A',$appointment->registration_time);
                $patient['description'] ='';
				$patient['status_id'] = $appointment->status;
                $patient['doctor_name'] = $doctor[0]->name;
                $patient['remarks'] = $appointment->remarks;
                $patient['patient_type'] = ucfirst($appointment->patient_type);
            }
            if ($patient != null) {
                $patient_array['patients'][$i]=$patient;
                $i++;
            }
            
        }
        if (count($appointments)>0) {
            echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
        } else {
            $blank_object=new stdClass();
            echo json_encode(['data'=>"{}",'status'=>'false','message'=>'No data found']);
        }
        // echo json_encode(['data'=>$patient_array,'status'=>'true','message'=>'success']);
    }
    function getdispatchnotification() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $this->db->where('id',$_POST['doctor_id']);
        $doctor_query = $this->db->get('doctor');
        $doctor_result= $doctor_query->result();
        
        $this->db->where('type','doctor_dispatched_notification');
        $this->db->order_by('id', 'desc');
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();

        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->notification_date=date('d-m-Y', $n->created_at);
            $notification_result[$count]->notification_time=date('h:i A', $n->created_at);

            $this->db->where('id', $n->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_result = $appointment_query->result();

            $this->db->where('id', $appointment_result[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_result = $patient_query->result();

            $this->db->where('id', $appointment_result[0]->location_id);
            $location_query = $this->db->get('location');
            $location_result = $location_query->result();

            // $notification_result[$count]->bookID=$appointment_result[0]->id;
            $notification_result[$count]->username=$patient_result[0]->name;
            if($n->profile_img!='') {
                $notification_result[$count]->profile_img=base_url().$n->profile_img;
            }
            $notification_result[$count]->time_slot=$appointment_result[0]->s_time;
            $notification_result[$count]->location_id=$appointment_result[0]->location_id;
            $notification_result[$count]->booking_date=$appointment_result[0]->date;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            $notification_result[$count]->patient_type=$appointment_result[0]->patient_type;
            // $notification_result[$count]->type_of_user='';
            
            $count++;
        }
        echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"success"]);
    }
    function  get_postpone_history_by_appointment_id(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        if ($this->form_validation->run() == true) {

            $appointment_id= $_POST['appointment_id'];


            //get appointment data
            $this->db->where('appointment_id',$appointment_id);
            $appointment_history_query = $this->db->get('appointment_history');
            $appointment_history_details = $appointment_history_query->result();

            if (count($appointment_history_details) >=1) {
                $data_result=array();
                $count=0;
                foreach ($appointment_history_details as $ahd) {
                    $this->db->where('id',$ahd->patient);
                    $patient_query = $this->db->get('patient');
                    $patient_data =  $patient_query->result();
        
                    $this->db->where('id',$patient_data[0]->ion_user_id);
                    $user_query = $this->db->get('users');
                    $user_data =  $user_query->result();
        
                    $this->db->where('id',$ahd->status);
                    $status_query = $this->db->get('appointment_status');
                    $status_data =  $status_query->result();
                
                    $this->db->where('id',$ahd->location_id);
                    $location_query = $this->db->get('location');
                    $location_data =  $location_query->result();
                
                    $data_result[$count]['id']=$ahd->id;
                    $data_result[$count]['appointment_id']=$ahd->appointment_id;
                    $data_result[$count]['patientid']=$ahd->patient;
                    $data_result[$count]['patientname']=$patient_data[0]->name;
                    $data_result[$count]['BookingDate']=$ahd->date;
                    $data_result[$count]['BookingTime']=$ahd->time_slot;
                    $data_result[$count]['LocationName']=$location_data[0]->name;
                    $data_result[$count]['LocationId']=$ahd->location_id;
                    $data_result[$count]['statusId']=$ahd->status;
                    $data_result[$count]['reason']=$ahd->description;
                    $data_result[$count]['StatusName']=$status_data[0]->status_name;
                    $data_result[$count]['user_id']=$patient_data[0]->ion_user_id;
                    $data_result[$count]['mode_of_consultation']=$ahd->mode_of_consultation;
                    $data_result[$count]['type_of_consultation']=$ahd->type_of_consultation;
                    $data_result[$count]['patient_type']=$ahd->patient_type;
                    if($patient_data[0]->img_url!='') {
                        $patient_data[0]->img_url=base_url().$patient_data[0]->img_url;
                    }else{
                        $patient_data[0]->img_url=null;
                    }
                    $data_result[$count]['img_url']=$patient_data[0]->img_url;
                    $data_result[$count]['registration_time']=date('d-m-Y h:i A',$ahd->registration_time);
                    $data_result[$count]['created_at']=date('d-m-Y h:i A',$ahd->created_at);
                    $count++;
                }
                
            
                echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"All appointment postponed history by appointment id"]);
            }
            else {
                echo json_encode(['data'=>'[]','status'=>'false','message'=>'No data found']);
            }
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		    echo json_encode(['data'=>'[]','status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  get_postpone_history(){
  
        //get appointment data
        $appointment_history_query = $this->db->get('appointment_history');
        $appointment_history_details = $appointment_history_query->result();
        if (count($appointment_history_details) >=1) {
            $data_result=array();
            $count=0;
            foreach ($appointment_history_details as $ahd) {
                $this->db->where('id',$ahd->patient);
                $patient_query = $this->db->get('patient');
                $patient_data =  $patient_query->result();
    
                $this->db->where('id',$patient_data[0]->ion_user_id);
                $user_query = $this->db->get('users');
                $user_data =  $user_query->result();
    
                $this->db->where('id',$ahd->status);
                $status_query = $this->db->get('appointment_status');
                $status_data =  $status_query->result();
            
                $this->db->where('id',$ahd->location_id);
                $location_query = $this->db->get('location');
                $location_data =  $location_query->result();
            
                $data_result[$count]['id']=$ahd->id;
                $data_result[$count]['appointment_id']=$ahd->appointment_id;
                $data_result[$count]['patientid']=$ahd->patient;
                $data_result[$count]['patientname']=$patient_data[0]->name;
                $data_result[$count]['BookingDate']=$ahd->date;
                $data_result[$count]['BookingTime']=$ahd->time_slot;
                $data_result[$count]['LocationName']=$location_data[0]->name;
                $data_result[$count]['LocationId']=$ahd->location_id;
                $data_result[$count]['statusId']=$ahd->status;
                $data_result[$count]['reason']=$ahd->description;
                $data_result[$count]['StatusName']=$status_data[0]->status_name;
                $data_result[$count]['user_id']=$patient_data[0]->ion_user_id;
                $data_result[$count]['mode_of_consultation']=$ahd->mode_of_consultation;
                $data_result[$count]['patient_type']=$ahd->patient_type;
                    $data_result[$count]['type_of_consultation']=$ahd->type_of_consultation;
                if($patient_data[0]->img_url!='') {
                    $patient_data[0]->img_url=base_url().$patient_data[0]->img_url;
                }else{
                    $patient_data[0]->img_url=null;
                }
                $data_result[$count]['img_url']=$patient_data[0]->img_url;
                $data_result[$count]['registration_time']=date('d-m-Y h:i A',$ahd->registration_time);
                $data_result[$count]['created_at']=date('d-m-Y h:i A',$ahd->created_at);
                $count++;
            }
            
        
            echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"All appointment postponed history"]);
        }
        else {
		    echo json_encode(['data'=>'[]','status'=>'false','message'=>'No data found']);
        }
    }
    function  complete_status(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        if ($this->form_validation->run() == true) {

            $appointment_id= $_POST['appointment_id'];

            $update_rows = array(
                'status' => 4
            );
            $this->db->where('id', $appointment_id);
            $result = $this->db->update('appointment', $update_rows); 

            //get appointment data
            $this->db->where('id', $appointment_id);
            $query = $this->db->get('appointment');
            $appointment=$query->result();

            if (count($appointment) >=1) {
    
                $this->db->where('id', $appointment[0]->patient);
                $query = $this->db->get('patient');
                $patient=$query->result();
    
                $this->db->where('id', $patient[0]->ion_user_id);
                $query = $this->db->get('users');
                $users=$query->result();
    
                $this->db->where('id', $appointment[0]->location_id);
                $query = $this->db->get('location');
                $location=$query->result();
    
                $this->db->where('id', $appointment[0]->doctor);
                $query = $this->db->get('doctor');
                $doctor=$query->result();
    
                $this->db->where('id',$appointment[0]->status);
                $status_query = $this->db->get('appointment_status');
                $status_data =  $status_query->result();
    
                $data=[
                    'appointment_id'=>$appointment[0]->id,
                    'appointment_date'=>$appointment[0]->date,
                    'appointment_time_slot'=>$appointment[0]->time_slot,
                    'appointment_start_time'=>$appointment[0]->s_time,
                    'appointment_end_time'=>$appointment[0]->e_time,
                    'appointment_remarks'=>$appointment[0]->remarks,
                    'appointment_registration_time'=>date('d-m-Y h:i A',$appointment[0]->registration_time),
                    'appointment_room_id'=>$appointment[0]->room_id,
                    'appointment_live_meeting_link'=>$appointment[0]->live_meeting_link,
                    'appointment_app_time'=>$appointment[0]->app_time,
                    'appointment_app_time_full_format'=>$appointment[0]->app_time_full_format,
                    'appointment_price'=>$appointment[0]->price,
                    'appointment_description'=>$appointment[0]->description,
                    'mode_of_consultation'=>$appointment[0]->mode_of_consultation,
                    'type_of_consultation'=>$appointment[0]->type_of_consultation,
                    'status'=>$appointment[0]->status,
                    'status_name'=>$status_data[0]->status_name,
                    'patient_type'=>$appointment[0]->patient_type,
                    'doctor_id'=>$appointment[0]->doctor,
                    'doctor_name'=>$doctor[0]->name,
                    'patient_name'=>$patient[0]->name,
                    'patient_email'=>$patient[0]->email,
                    'location_name'=>$location[0]->name,
                    'patient_register_date'=>date("d-m-Y h:i A",$users[0]->created_on),
                ];
                    $this->db->where('booking_id', $appointment_id);
                    $this->db->where('order_status', 'Confirmed');
                    $query = $this->db->get('payment');
                    $payment=$query->result();
                if (count($payment)>=1) {
                    
    
                    $data['txnID']=$payment[0]->txnID;
                    $data['order_id']=$payment[0]->order_id;
                    $data['txn_amount']=$payment[0]->amount;
                    $data['txn_date']=date("d-m-Y h:i A",$payment[0]->created_at);
                }else{
                    $data['txnID']=null;
                    $data['order_id']=null;
                    $data['txn_amount']=null;
                    $data['txn_date']=null;
                }
            
                    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"Appointment completed"]);
            }
            else {
                echo json_encode(['data'=>'[]','status'=>'false','message'=>'No data found']);
            }
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		    echo json_encode(['data'=>'[]','status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  no_show_status(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('appointment_id', 'Appointment Id', 'required');
        if ($this->form_validation->run() == true) {

            $appointment_id= $_POST['appointment_id'];

            $update_rows = array(
                'status' => 5
            );
            $this->db->where('id', $appointment_id);
            $result = $this->db->update('appointment', $update_rows); 

            //get appointment data
            $this->db->where('id', $appointment_id);
            $query = $this->db->get('appointment');
            $appointment=$query->result();

            if (count($appointment) >=1) {
    
                $this->db->where('id', $appointment[0]->patient);
                $query = $this->db->get('patient');
                $patient=$query->result();
    
                $this->db->where('id', $patient[0]->ion_user_id);
                $query = $this->db->get('users');
                $users=$query->result();
    
                $this->db->where('id', $appointment[0]->location_id);
                $query = $this->db->get('location');
                $location=$query->result();
    
                $this->db->where('id', $appointment[0]->doctor);
                $query = $this->db->get('doctor');
                $doctor=$query->result();
    
                $this->db->where('id',$appointment[0]->status);
                $status_query = $this->db->get('appointment_status');
                $status_data =  $status_query->result();
    
                $data=[
                    'appointment_id'=>$appointment[0]->id,
                    'appointment_date'=>$appointment[0]->date,
                    'appointment_time_slot'=>$appointment[0]->time_slot,
                    'appointment_start_time'=>$appointment[0]->s_time,
                    'appointment_end_time'=>$appointment[0]->e_time,
                    'appointment_remarks'=>$appointment[0]->remarks,
                    'appointment_registration_time'=>date('d-m-Y h:i A',$appointment[0]->registration_time),
                    'appointment_room_id'=>$appointment[0]->room_id,
                    'appointment_live_meeting_link'=>$appointment[0]->live_meeting_link,
                    'appointment_app_time'=>$appointment[0]->app_time,
                    'appointment_app_time_full_format'=>$appointment[0]->app_time_full_format,
                    'appointment_price'=>$appointment[0]->price,
                    'appointment_description'=>$appointment[0]->description,
                    'mode_of_consultation'=>$appointment[0]->mode_of_consultation,
                    'type_of_consultation'=>$appointment[0]->type_of_consultation,
                    'status'=>$appointment[0]->status,
                    'status_name'=>$status_data[0]->status_name,
                    'patient_type'=>$appointment[0]->patient_type,
                    'doctor_id'=>$appointment[0]->doctor,
                    'doctor_name'=>$doctor[0]->name,
                    'patient_name'=>$patient[0]->name,
                    'patient_email'=>$patient[0]->email,
                    'location_name'=>$location[0]->name,
                    'patient_register_date'=>date("d-m-Y h:i A",$users[0]->created_on),
                ];
                    $this->db->where('booking_id', $appointment_id);
                    $this->db->where('order_status', 'Confirmed');
                    $query = $this->db->get('payment');
                    $payment=$query->result();
                if (count($payment)>=1) {
                    
    
                    $data['txnID']=$payment[0]->txnID;
                    $data['order_id']=$payment[0]->order_id;
                    $data['txn_amount']=$payment[0]->amount;
                    $data['txn_date']=date("d-m-Y h:i A",$payment[0]->created_at);
                }else{
                    $data['txnID']=null;
                    $data['order_id']=null;
                    $data['txn_amount']=null;
                    $data['txn_date']=null;
                }
            
                    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"Appointment marked as no show successfully"]);
            }
            else {
                echo json_encode(['data'=>'[]','status'=>'false','message'=>'No data found']);
            }
        }
        else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
		    echo json_encode(['data'=>'[]','status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  add_hint_data(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        //validate form input
        $this->form_validation->set_rules('input_name','Input Name', 'required');
        $this->form_validation->set_rules('text','Text', 'required');
    
        if ($this->form_validation->run() == true) {
            $input_name = $this->input->post('input_name');
            $text = $this->input->post('text');

            $this->db->where('input_name', $input_name);
            $this->db->like('text', $text);
            $query = $this->db->get('hint_data');
            $hint_found_data = $query->result();

            if (count($hint_found_data) >=1) {
                echo json_encode(['data'=>$hint_found_data[0],'status'=>'false','message'=>'Hint Already Inserted']);
            }else{
                date_default_timezone_set("Asia/Kolkata"); 
    
                $hint_data = array(
                    'input_name' => $input_name,
                    'text' => $text,
                    'created_at' => time()
        
                );
                $this->db->insert('hint_data', $hint_data);
                $insert_id = $this->db->insert_id();
                $this->db->where('id', $insert_id);
                $query = $this->db->get('hint_data');
                $data = $query->result();
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Hint Successfully Added']); 
            }

                      
        } else {
            //display the create user form
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function  show_hint_data(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        //validate form input
        $this->form_validation->set_rules('input_name','Input Name', 'required');
        $this->form_validation->set_rules('hint','Hint', 'required');
    
        if ($this->form_validation->run() == true) {
            $input_name = $this->input->post('input_name');
            $hint = $this->input->post('hint');

            $this->db->where('input_name', $input_name);
            $this->db->like('text', $hint);
            $query = $this->db->get('hint_data');
            $data = $query->result();
            if (count($data) >=1) {
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'All hint']); 
            }else{
                $data=array();
                echo json_encode(['data'=>$data,'status'=>'false','message'=>'No Hint Found']); 
            }
                      
        } else {
            //display the create user form
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function get_Available_Slot_for_Online() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata");
        
        $timestamp = strtotime($_POST['date']);
       // $timestamp = strtotime('2022-01-24');
        $day = date('l', $timestamp);

        $this->db->where('Weekday',$day);
        $query = $this->db->get('time_slot');
        $all_time_slot = $query->result();
        $count=0;
        foreach ($all_time_slot as $ats) {

            if(strtotime($_POST['date']) == strtotime(date("d-m-Y"))){
                if(strtotime($ats->s_time) <= strtotime(date("h:i A"))+ (1 * 60 * 60)){//+ (1 * 60 * 60)
                    $all_time_slot[$count]->booked="y";
                    $count++;
                }else{
                    $this->db->where('date',$_POST['date']);
                    $this->db->where('s_time',$ats->s_time);
                    $this->db->where('status',7);
                    $appointment = $this->db->get('appointment');
                    $dataap[$i] =$appointment->result();
                    $num = $appointment->num_rows();  
                    if($num >=1){
                        $all_time_slot[$count]->booked="y";
                    }else{
                        $all_time_slot[$count]->booked="n";
                    }
                    $count++;   
                }                 
            }else{
                $this->db->where('date',$_POST['date']);
                $this->db->where('s_time',$ats->s_time);
                $this->db->where('status',7);
                $appointment = $this->db->get('appointment');
                $dataap[$i] =$appointment->result();
                $num = $appointment->num_rows();  
                if($num >=1){
                    $all_time_slot[$count]->booked="y";
                }else{
                    $all_time_slot[$count]->booked="n";
                }
                $count++;
            }
        }
        echo json_encode(['data'=>$all_time_slot,'status'=>"true",'message'=>"All available time slot for online"]);
    }
    function patient_details_by_id() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $this->form_validation->set_rules('patient_id','Patient Id', 'required');
        
        if ($this->form_validation->run() == true) {

            $this->db->where('id',$_POST['patient_id']);
            $query = $this->db->get('patient');
            $patient= $query->result();
            
            if (count($patient)>=1) {
                if($patient[0]->img_url!='') {
                    $patient[0]->img_url=base_url().$patient[0]->img_url;            
                }

                $this->db->where('id',$patient[0]->ion_user_id);
                $user_query = $this->db->get('users');
                $user_data =  $user_query->result();

                if (empty($user_data[0]->created_on)) {
                    $patient[0]->created_at=null;
                }else{
                    $patient[0]->created_at=date('d-m-Y h:i A', $user_data[0]->created_on);
                }
                if (empty($patient[0]->birthdate)) {
                    $patient[0]->birthdate=null;
                }else{
                    $patient[0]->birthdate=date('d-m-Y', $patient[0]->birthdate);
                }
                echo json_encode(['data'=>$patient[0],'status'=>"ture",'message'=>"Patient Details"]);
            }else{
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>"flase",'message'=>"No Data Found"]);
            }
        } else {
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }
    function get_medicine_by_prescription_id() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $prescription_id    = $_POST['prescription_id'];

        $this->db->order_by('id', 'desc');
        $this->db->where('id', $prescription_id);
        $query = $this->db->get('prescription');
        $Prescriptionlist=$query->result();

        $medicine = $Prescriptionlist[0]->medicine;
        $medicine = explode("###", $medicine);
        $medicine_array=array();
        foreach ($medicine as $key => $value) {
                
            $single_medicine = explode("***", $value);
            $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name;
            $single_medicine_details['medicine_name']=$single_medicine_name;
            $single_medicine_details['mg']=$single_medicine[1];
            $single_medicine_details['frequency']=$single_medicine[2];
            $single_medicine_details['days']=$single_medicine[3];
            $single_medicine_details['instruction']=$single_medicine[4];
            array_push($medicine_array,$single_medicine_details);
        }
        $medicine=$medicine_array;
        echo json_encode(['data'=>$medicine,'status'=>"true",'message'=>"All Medicines By Prescription Id"]);
    }
    function  all_cancle_done_request(){
        $this->db->order_by('id', 'desc');
        $this->db->where('type',8);
        $this->db->where('flag',1);
        $query = $this->db->get('cancellation_request');
        $request_details = $query->result();

        $count=0;
        foreach ($request_details as $r) {
            $this->db->where('id',8);
            $appointment_status_query = $this->db->get('appointment_status');
            $appointment_status_data =  $appointment_status_query->result();

            $this->db->where('id',$r->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_data =  $appointment_query->result();

            $this->db->where('id',$appointment_data[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$appointment_data[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();

            $request_details[$count]->status_name=$appointment_status_data[0]->status_name;
            $request_details[$count]->booking_date=$appointment_data[0]->date;
            $request_details[$count]->booking_time=$appointment_data[0]->s_time;
            $request_details[$count]->location_id=$appointment_data[0]->location_id;
            $request_details[$count]->location_name=$location_data[0]->name;
            $request_details[$count]->patient_name=$patient_data[0]->name;
            $request_details[$count]->created_at=date('d-m-Y h:i A',$r->created_at);
            $count++;
        }
        if (count($request_details)>=1) {
            echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Cancle Done Request"]);
        }else{
            $blank_object=new stdClass();
            array_push($request_details, $blank_object);
            echo json_encode(['data'=>$request_details,'status'=>"false",'message'=>"No Cancle Done Request Found"]);
        }
        
        
        // echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Cancle Done Request"]);
    }
    function  all_postpone_done_request(){
        $this->db->order_by('id', 'desc');
        $this->db->where('type',6);
        $this->db->where('flag',1);
        $query = $this->db->get('cancellation_request');
        $request_details = $query->result();

        $count=0;
        foreach ($request_details as $r) {
            $this->db->where('id',6);
            $appointment_status_query = $this->db->get('appointment_status');
            $appointment_status_data =  $appointment_status_query->result();

            $this->db->where('id',$r->appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_data =  $appointment_query->result();

            $this->db->where('id',$appointment_data[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();

            $this->db->where('id',$appointment_data[0]->location_id);
            $location_query = $this->db->get('location');
            $location_data =  $location_query->result();

            $request_details[$count]->status_name=$appointment_status_data[0]->status_name;
            $request_details[$count]->booking_date=$appointment_data[0]->date;
            $request_details[$count]->booking_time=$appointment_data[0]->s_time;
            $request_details[$count]->location_id=$appointment_data[0]->location_id;
            $request_details[$count]->location_name=$location_data[0]->name;
            $request_details[$count]->patient_name=$patient_data[0]->name;
            $request_details[$count]->created_at=date('d-m-Y h:i A',$r->created_at);
            $count++;
        }
        if (count($request_details)>=1) {
            echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Postpond Done Request"]);
        }else{
            $blank_object=new stdClass();
            array_push($request_details, $blank_object);
            echo json_encode(['data'=>$request_details,'status'=>"false",'message'=>"No Postpond Done Request Found"]);
        }
        // echo json_encode(['data'=>$request_details,'status'=>"true",'message'=>"All Postpond Done Request"]);
    }
    function invoice_generate_by_appointment_id() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $appointment_id = $_POST['appointment_id'];
        date_default_timezone_set("Asia/Kolkata"); 
        if (!empty($date)) {
            $date = strtotime($date);
        }
        //payment
        $this->db->where('booking_id',$appointment_id);
        $payment_query = $this->db->get('payment');
        $payment_details= $payment_query->result();
        $paid=0;
        foreach ($payment_details as $p) {
            $paid+=$p->amount;
        }
        //appointment
        $this->db->where('id',$appointment_id);
        $appointment_query = $this->db->get('appointment');
        $appointment_details= $appointment_query->result();
        // medicine expense
        $this->db->order_by('id', 'desc');
        $this->db->where('appointment_id', $appointment_id);
        $query = $this->db->get('prescription');
        $Prescriptionlist=$query->result();
        // echo json_encode($Prescriptionlist);
        if (count($Prescriptionlist)>=1) {
            $pcount=0;
            $medicine_details=array();
            $medicine_price=array();
            foreach ($Prescriptionlist as $p) {
                $medicine = $Prescriptionlist[$pcount]->medicine;
                $medicine = explode("###", $medicine);
                
                foreach ($medicine as $key => $value) {
                        
                    $single_medicine = explode("***", $value);
                    $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name;
                    $single_medicine_price = $this->medicine_model->getMedicineById($single_medicine[0])->price;
                    array_push($medicine_details,$single_medicine_name);
                    array_push($medicine_price,$single_medicine_price);
                }
                $pcount++;
                // echo json_encode($medicine_price);
                
            }
            // echo json_encode($medicine_price);
        }else{
            $medicine_details='';
            $medicine_price=0;
        }

        $data = array();

        $data['appointment_id'] = $appointment_id;
        $data['patient_id'] = $appointment_details[0]->patient;
        $data['consultation_fee'] = $appointment_details[0]->price;
        $data['medicine_details'] = json_encode($medicine_details);
        $data['medicine_fee'] = json_encode($medicine_price);
        $data['total'] = array_sum($medicine_price)+$appointment_details[0]->price;
        $data['paid'] = $paid;
        $data['due']=$data['total']-$data['paid'];
        $data['created_at']=time();

        $this->db->where('appointment_id',$appointment_id);
        $check_invoice_query = $this->db->get('invoice');
        $check_invoice_details= $check_invoice_query->result();

        if (count($check_invoice_details) >=1) {

            $this->db->where('id', $check_invoice_details[0]->id);
            $result = $this->db->update('invoice', $data); 

            $this->db->where('id',$check_invoice_details[0]->id);
            $invoice_query = $this->db->get('invoice');
            $invoice_details= $invoice_query->result();
            date_default_timezone_set("Asia/Kolkata"); 
            $invoice_details[0]->created_at=date('d-m-Y h:i A',$invoice_details[0]->created_at);

            echo json_encode(['data'=>$invoice_details[0],'status'=>"true",'message'=>"Invoice successfully Updated"]);
        }else{
            $result=$this->db->insert('invoice', $data);
            if ($result==1) {
                $inserted_id = $this->db->insert_id();
                $this->db->where('id',$inserted_id);
                $invoice_query = $this->db->get('invoice');
                $invoice_details= $invoice_query->result();
                date_default_timezone_set("Asia/Kolkata"); 
                $invoice_details[0]->created_at=date('d-m-Y h:i A',$invoice_details[0]->created_at);
                echo json_encode(['data'=>$invoice_details[0],'status'=>"true",'message'=>"Invoice successfully generated"]);
            }else{
                // echo $this->db->last_query();
                $blank_object=new stdClass();
                echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"Invoice not generated please try again"]);
            }
        }
        
    }
    function invoice_by_invoice_id() {

        $invoice_id = $_GET['invoice_id'];
        date_default_timezone_set("Asia/Kolkata"); 

        $this->db->where('id',$invoice_id);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        $this->db->where('id',$invoice_details[0]->appointment_id);
        $appointment_query = $this->db->get('appointment');
        $appointment_details= $appointment_query->result();

        $this->db->where('id',$appointment_details[0]->patient);
        $patient_query = $this->db->get('patient');
        $patient_details= $patient_query->result();

        $this->db->where('id',$appointment_details[0]->doctor);
        $doctor_query = $this->db->get('doctor');
        $doctor_details= $doctor_query->result();

        if (count($invoice_details)>=1) {
            $medicine_names=json_decode($invoice_details[0]->medicine_details);
            $medicine_fees=json_decode($invoice_details[0]->medicine_fee);
            $medicine_detail="";
            if (count($medicine_names)>=1) {
                for ($index=0; $index < count($medicine_names); $index++) { 
                    $medicine_detail.='<tr>
                                            <td class="col-md-9">1 X '.$medicine_names[$index].'</td>
                                            <td class="col-md-3"> Rs. '.$medicine_fees[$index].' </td>
                                        </tr>';
                }
            }
            $html='<!DOCTYPE html>
                    <html>
                        <head>
                            <title></title>
                            <link href="'.base_url().'common/extranal/css/prescription/prescription_view_1.css" rel="stylesheet">
                            <link rel="stylesheet" href="'.base_url().'common/css/bootstrap-reset.css">
                            <link rel="stylesheet" href="'.base_url().'common/extranal/css/prescription.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/prescription_view_1.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/bootstrap.min.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/style.css">
                            <style>
                                .invoice-list h4 {
                                    font-weight: 500;
                                    font-size: 16px;
                                }
								.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
                                    text-align: right;
                                }
                                .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>th, .table>caption+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>td, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>td, {
                                    text-align: left;
                                }	

                                td.col-md-9 {
                                    text-align: left !important;
                                }

                                h3.text-center.invoice_lang {
                                    font-size: 24px;
                                }
                            </style>
                        </head>
                        <body style="padding: 0px;width: 100%;min-width:640px;margin: 0px auto;">
                        <section class="col-md-6" style="margin: 0px auto;">
                            <div class="panel panel-primary" id="invoice" style="border-color: #d3d3d4;>
                                <div class="invoice-all">
                                    <div class="row invoice-list2">
                                        <div class="col-md-12 invoice_head clearfix logotitle">
                                        <div class="col-md-6 text-left invoice_head_left">
                                            <h3>
                                                '.$this->settings_model->getSettings()->title.'                                        
                                            </h3>
                                            <h5>
                                            '.$this->settings_model->getSettings()->address.'                                         
                                            </h5>
                                            <h5> Tel: '.$this->settings_model->getSettings()->phone.'                                         
                                            </h5>
                                        </div>
                                        <div class="col-md-6 text-right invoice_head_right" style="padding: 15px 10px;">
                                            <img alt="" src="'.base_url().$this->settings_model->getSettings()->logo.'" width="200">
                                        </div>
                                        </div>
                                        <div class="col-md-12 hr_border" >
                                            <div style="border-top: 1px solid #eee;margin-bottom:10px;margin-top:10px;"> </div>
                                        </div>
                                        <div class="col-md-12 title">
                                        <h3 class="invoice_lang" style="    font-weight: 500;padding-left: 15px;">
                                            Payment Invoice #'.$invoice_details[0]->id.'
                                        </h3>
                                        </div>
                                         <div class="col-md-12 hr_border" >
                                       <div style="border-bottom: 1px solid #eee;margin-top:10px;margin-bottom:20px;"> </div>
                                        </div>
                                        <div class="col-md-12">
                                        <div class="col-md-6 pull-left info_position">
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Patient Name </label>
                                                    <span class="info_text"> : '.$patient_details[0]->name.' <br></span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Patient ID  </label>
                                                    <span class="info_text"> : '.$patient_details[0]->id.' <br></span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label"> Address </label>
                                                    <span class="info_text"> : '.$patient_details[0]->address.' <br></span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Phone  </label>
                                                    <span class="info_text"> : '.$patient_details[0]->phone.' <br></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pull-right info_position">
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Appointment Id  </label>
                                                    <span class="info_text"> : '.$invoice_details[0]->appointment_id.'</span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Invoice Date  </label>
                                                    <span class="info_text"> : '.date('d-m-Y',$invoice_details[0]->created_at).'
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-12 row details">
                                                <p>
                                                    <label class="control-label">Doctor  </label>
                                                    <span class="info_text"> : '.$doctor_details[0]->name.' <br></span>
                                                </p>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="" style="padding: 15px;">
                                        
										
										<div class="row">
                                            <div class="col-md-12">
                                                <table class="table">
                                                    
                                                    <tbody>
                                                        <tr>
                                                            <td class="col-md-9" style="text-align: left;"><strong>Consultation Fee</strong></td>
                                                            <td class="col-md-3">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-9" style="text-align: left;">
                                                                <p>1 X '.$invoice_details[0]->consultation_fee.'</p>
                                                            </td>
                                                            <td class="col-md-3" style="text-align: right;">
                                                                <p> Rs. '.$invoice_details[0]->consultation_fee.'</p>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-9"><strong>Medicine Fee</strong></td>
                                                            <td class="col-md-3">&nbsp;</td>
                                                        </tr>'.$medicine_detail.'
                                                        <tr>
                                                            <td class="text-right">
                                                                <p> <strong>Total : </strong> </p>
                                                                <p> <strong>Paid : </strong> </p>
                                                            </td>
                                                            <td>
                                                                <p> <strong> Rs. '.$invoice_details[0]->total.' </strong> </p>
                                                                <p> <strong> Rs. '.$invoice_details[0]->paid.'</strong> </p>
                                                            </td>
                                                        </tr>
                                                        <tr style="color: #F81D2D;">
                                                            <td class="text-right">
                                                                <h4><strong>Balance Due : </strong></h4>
                                                            </td>
                                                            <td class="text-left">
                                                                <h4><strong> Rs. '.$invoice_details[0]->due.' </strong></h4>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </section>
                        </body>
                    </html>';
            echo $html;
            
            // echo json_encode(['data'=>$html,'status'=>"true",'message'=>"Invoice Details"]);
        }else{
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function download_invoice_by_invoice_id() {

        $invoice_id = $_GET['invoice_id'];
        date_default_timezone_set("Asia/Kolkata"); 

        $this->db->where('id',$invoice_id);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();
        
        $this->db->where('id',$invoice_details[0]->appointment_id);
        $appointment_query = $this->db->get('appointment');
        $appointment_details= $appointment_query->result();

        $this->db->where('id',$appointment_details[0]->patient);
        $patient_query = $this->db->get('patient');
        $patient_details= $patient_query->result();

        $this->db->where('id',$appointment_details[0]->doctor);
        $doctor_query = $this->db->get('doctor');
        $doctor_details= $doctor_query->result();

        if (count($invoice_details)>=1) {

            $medicine_names=json_decode($invoice_details[0]->medicine_details);
            $medicine_fees=json_decode($invoice_details[0]->medicine_fee);
            $medicine_detail="";
            if (count($medicine_names)>=1) {
                for ($index=0; $index < count($medicine_names); $index++) { 
                    $medicine_detail.='<tr>
                                            <td class="col-md-9">1 X '.$medicine_names[$index].'</td>
                                            <td class="col-md-3"> Rs. '.$medicine_fees[$index].' </td>
                                        </tr>';
                }
            }
            $html='<!DOCTYPE html>
                    <html>
                        <head>
                            <title></title>
                            <link href="'.base_url().'common/extranal/css/prescription/prescription_view_1.css" rel="stylesheet">
                            <link rel="stylesheet" href="'.base_url().'common/css/bootstrap-reset.css">
                            <link rel="stylesheet" href="'.base_url().'common/extranal/css/prescription.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/prescription_view_1.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/bootstrap.min.css">
                            <link rel="stylesheet" href="'.base_url().'common/css/style.css">
                            <style>
                                .invoice-list h4 {
                                    font-weight: 500;
                                    font-size: 16px;
                                }
								.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
                                    text-align: right;
                                }
                                .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>th, .table>caption+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>td, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>td, {
                                    text-align: left;
                                }	

                                td.col-md-9 {
                                    text-align: left !important;
                                }

                                h3.text-center.invoice_lang {
                                    font-size: 24px;
                                }
                            </style>
                        </head>
                        <body style="padding: 0px;min-width:600px;margin: 0px auto;">
                        <section class="col-md-6" style="">
                            <div class="panel panel-primary" id="invoice" style="border-color: #d3d3d4;>
                                <div class="invoice-all">
                                    <div class="row invoice-list2">
                                        <div class="col-md-12 invoice_head clearfix logotitle">
                                        <div class="col-md-6 text-left invoice_head_left" style="float: left;width: 55%;">
                                            <h3>
                                                <strong>'.$this->settings_model->getSettings()->title.'   </strong>                                     
                                            </h3>
                                            <h5>
                                          <strong>  '.$this->settings_model->getSettings()->address.'     </strong>                                    
                                            </h5>
                                            <h5> <strong>Tel: '.$this->settings_model->getSettings()->phone.'   </strong>                                      
                                            </h5>
                                        </div>
                                        <div class="col-md-6  invoice_head_right" style="padding: 15px 0px;float: left;width: 45%;text-align: center;">
                                            <img alt="" src="'.base_url().$this->settings_model->getSettings()->logo.'" width="180">
                                        </div>
                                        </div>
                                        <div class="col-md-12 hr_border" >
                                            <div style="border-top: 1px solid #eee;margin-bottom:10px;margin-top:10px;"> </div>
                                        </div>
                                        <div class="col-md-12 title">
                                        <h3 class="invoice_lang" style="    font-weight: 500;padding-left: 15px;">
                                            <strong>Payment Invoice #'.$invoice_details[0]->id.'</strong>                                       
                                        </h3>
                                        </div>
                                         <div class="col-md-12 hr_border" >
                                       <div style="border-bottom: 1px solid #eee;margin-top:10px;margin-bottom:20px;"> </div>
                                        </div>
                                        <div class="col-md-12">
                                        <div class="col-md-6 pull-left info_position" style="float: left;width: 50%;">
                                            <div class="">
                                                <p>
                                                    <span class="control-label"><strong>Patient Name </strong></span>
                                                    <span class="patient_name"> : '.$patient_details[0]->name.' <br></span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                    <span class=""><strong>Patient ID  </strong></span>
                                                    <span class="patient_name"> : '.$patient_details[0]->id.' <br></span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                    <span class=""> <strong>Address </strong></span>
                                                    <span class="patient_name"> : '.$patient_details[0]->address.' <br></span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                    <span class=""><strong>Phone  </strong></span>
                                                    <span class="patient_name"> : '.$patient_details[0]->phone.' <br></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 pull-right info_position" style="float: left;width: 50%;">
                                            <div class="">
                                                <p>
                                                    <span class="control-label"><strong>Appointment Id</strong> </span>
                                                    <span class="patient_name"> : '.$invoice_details[0]->appointment_id.'</span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                    <span class="control-label"><strong>Invoice Date  </strong></span>
                                                    <span class="patient_name"> : '.date('d-m-Y',$invoice_details[0]->created_at).'
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="">
                                                <p>
                                                    <span class="control-label"><strong>Doctor  </strong></span>
                                                    <span class="patient_name"> : '.$doctor_details[0]->name.' <br></span>
                                                </p>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                   
                                    <div class="" style="padding: 15px;">
                                       <div class="row">
                                            <div class="col-md-12">
                                                <table class="table">
                                                    
                                                    <tbody>
                                                        <tr>
                                                            <td class="col-md-9" style="text-align: left;"><strong>Consultation Fee</strong></td>
                                                            <td class="col-md-3">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-9" style="text-align: left;">
                                                                <p>1 X '.$invoice_details[0]->consultation_fee.'</p>
                                                            </td>
                                                            <td class="col-md-3" style="text-align: right;">
                                                                <p> Rs. '.$invoice_details[0]->consultation_fee.'</p>

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="col-md-9"><strong>Medicine Fee</strong></td>
                                                            <td class="col-md-3">&nbsp;</td>
                                                        </tr>'.$medicine_detail.'
                                                        <tr>
                                                            <td class="text-right">
                                                                <p> <strong>Total : </strong> </p>
                                                                <p> <strong>Paid : </strong> </p>
                                                            </td>
                                                            <td>
                                                                <p> <strong> Rs. '.$invoice_details[0]->total.' </strong> </p>
                                                                <p> <strong> Rs. '.$invoice_details[0]->paid.'</strong> </p>
                                                            </td>
                                                        </tr>
                                                        <tr style="color: #F81D2D;">
                                                            <td class="text-right">
                                                                <h4><strong>Balance Due : </strong></h4>
                                                            </td>
                                                            <td class="text-left">
                                                                <h4><strong> Rs. '.$invoice_details[0]->due.' </strong></h4>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </section>
                        </body>
                    </html>';
            $this->pdf->createPDF($html, 'invoice', true);
            
            // echo json_encode(['data'=>$html,'status'=>"true",'message'=>"Invoice Details"]);
        }else{
            $blank_object=new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function all_paid_invoice() {
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due', 0);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            foreach ($invoice_details as $i) {
                $this->db->where('id', $i->appointment_id);
                $appointment_query = $this->db->get('appointment');
                $appointment_details= $appointment_query->result();

                $this->db->where('id', $appointment_details[0]->patient);
                $patient_query = $this->db->get('patient');
                $patient_details= $patient_query->result();

                $medicine_details=json_decode($i->medicine_details);
                $medicine_fee=json_decode($i->medicine_fee);

                $medicines=[];
                for ($in=0; $in < count($medicine_details) ; $in++) { 
                    $temp=['name'=>$medicine_details[$in],'price'=>$medicine_fee[$in]];
                    array_push($medicines,$temp);
                }

                $invoice_details[$count]->medicine_fee_total=array_sum(json_decode($i->medicine_fee));
                if ($invoice_details[$count]->medicine_fee_total==null) {
                    $invoice_details[$count]->medicine_fee_total=0;
                }
                $invoice_details[$count]->medicine_details=$medicines;
                $invoice_details[$count]->patient_id=$appointment_details[0]->patient;
                $invoice_details[$count]->patient_name=$patient_details[0]->name;
                $invoice_details[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                $invoice_details[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                $invoice_details[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                $count++;
            }

            echo json_encode(['data'=>$invoice_details,'status'=>"true",'message'=>"All Paid Invoice"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function all_due_invoice() {
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due >=', 1);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            foreach ($invoice_details as $i) {
                $this->db->where('id', $i->appointment_id);
                $appointment_query = $this->db->get('appointment');
                $appointment_details= $appointment_query->result();

                $this->db->where('id', $appointment_details[0]->patient);
                $patient_query = $this->db->get('patient');
                $patient_details= $patient_query->result();

                $medicine_details=json_decode($i->medicine_details);
                $medicine_fee=json_decode($i->medicine_fee);

                $medicines=[];
                for ($in=0; $in < count($medicine_details) ; $in++) { 
                    $temp=['name'=>$medicine_details[$in],'price'=>$medicine_fee[$in]];
                    array_push($medicines,$temp);
                }
                
                $invoice_details[$count]->medicine_fee_total=array_sum(json_decode($i->medicine_fee));
                if ($invoice_details[$count]->medicine_fee_total==null) {
                    $invoice_details[$count]->medicine_fee_total=0;
                }
                $invoice_details[$count]->medicine_details=$medicines;
                $invoice_details[$count]->patient_id=$appointment_details[0]->patient;
                $invoice_details[$count]->patient_name=$patient_details[0]->name;
                $invoice_details[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                $invoice_details[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                $invoice_details[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                $count++;
            }
            

            echo json_encode(['data'=>$invoice_details,'status'=>"true",'message'=>"All Due Invoice"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function due_invoice_by_patient_id() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $patient_id = $_POST['patient_id'];
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due >=', 1);
        $this->db->where('patient_id',$patient_id);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            foreach ($invoice_details as $i) {
                $this->db->where('id', $i->appointment_id);
                $appointment_query = $this->db->get('appointment');
                $appointment_details= $appointment_query->result();

                $this->db->where('id', $appointment_details[0]->patient);
                $patient_query = $this->db->get('patient');
                $patient_details= $patient_query->result();

                $invoice_details[$count]->medicine_details=json_decode($invoice_details[$count]->medicine_details);
                $invoice_details[$count]->medicine_fee=array_sum(json_decode($invoice_details[$count]->medicine_fee));
                // $invoice_details[$count]->medicine_details=json_decode($i->medicine_details);
                // $invoice_details[$count]->medicine_fee=json_decode($i->medicine_fee);
                $invoice_details[$count]->patient_id=$appointment_details[0]->patient;
                $invoice_details[$count]->patient_name=$patient_details[0]->name;
                $invoice_details[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                $invoice_details[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                $invoice_details[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                $count++;
            }

            echo json_encode(['data'=>$invoice_details,'status'=>"true",'message'=>"Due invoice by appointment id"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function paid_invoice_by_patient_id() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $patient_id = $_POST['patient_id'];
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due', 0);
        $this->db->where('patient_id',$patient_id);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            foreach ($invoice_details as $i) {
                $this->db->where('id', $i->appointment_id);
                $appointment_query = $this->db->get('appointment');
                $appointment_details= $appointment_query->result();

                $this->db->where('id', $appointment_details[0]->patient);
                $patient_query = $this->db->get('patient');
                $patient_details= $patient_query->result();

                $invoice_details[$count]->medicine_details=json_decode($invoice_details[$count]->medicine_details);
                $invoice_details[$count]->medicine_fee=array_sum(json_decode($invoice_details[$count]->medicine_fee));
                // $invoice_details[$count]->medicine_details=json_decode($i->medicine_details);
                // $invoice_details[$count]->medicine_fee=json_decode($i->medicine_fee);
                $invoice_details[$count]->patient_id=$appointment_details[0]->patient;
                $invoice_details[$count]->patient_name=$patient_details[0]->name;
                $invoice_details[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                $invoice_details[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                $invoice_details[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                $count++;
            }
            

            echo json_encode(['data'=>$invoice_details,'status'=>"true",'message'=>"Due invoice by appointment id"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    public function proceed_order2() {
        date_default_timezone_set("Asia/Kolkata"); 
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id =  $_POST['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $booking_id = $_POST['booking_id'];
        $amount = $_POST['amount'];
        $order_id = rand(111, 999) . substr(md5(time()), 5);
        $order_status = $_POST['order_status'];
        switch ($order_status) {
            case 1:
                $order_status_text = "pending";
                break;
            case 2:
                $order_status_text = "Approved";
                break;
            case 3:
                $order_status_text = "Reject";
                break;
            case 4:
                $order_status_text = "Completed";
                break;
            case 5:
                $order_status_text = "No Show";
                break;
            case 6:
                $order_status_text = "Postpone";
                break;
            case 7:
                $order_status_text = "Confirmed";
                break;
            default:
                $order_status_text = "pending";
                break;

        }
        $pay_via = $_POST['pay_via'];
        $array = array(
            'patient' => $patient_id,
            'doctor' => $doctor_id,
            'order_id' => $order_id,
            'amount' => $amount,
            'booking_Id' => $booking_id,
            'payment_type' => $pay_via,
            'order_status' => $order_status_text,
            'created_at'=>time()
           

        );
        $this->db->insert('payment',$array);
        $update_rows = array(
            'status' => $order_status

        );
        // $this->db->where('id', $booking_id);
        // $result = $this->db->update('appointment', $update_rows); 

        $this->db->where('id',$booking_id);
        $query = $this->db->get('appointment');
        $appointment_details = $query->result();

        $this->db->where('id',$appointment_details[0]->patient);
        $patient_query = $this->db->get('patient');
        $patient_data =  $patient_query->result();

        $this->db->where('id',$patient_data[0]->ion_user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();

        $this->db->where('id',$appointment_details[0]->doctor);
        $doctor_query = $this->db->get('patient');
        $doctor_data =  $doctor_query->result();

        $this->db->where('id',$doctor_data[0]->ion_user_id);
        $doctor_user_query = $this->db->get('users');
        $doctor_user_data =  $doctor_user_query->result();

        $this->db->where('id',$_POST['status_id']);
        $status_query = $this->db->get('appointment_status');
        $status_data =  $status_query->result();

        $this->db->where('appointment_id',$appointment_details[0]->id);
        $invoice_query = $this->db->get('invoice');
        $invoice_data =  $invoice_query->result();
                    // notification to patient
                    $this->db->where('user_id', $patient_data[0]->ion_user_id);
                    $this->db->where('status', 1);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_data = $user_device_query->result();

                   
                        $data_addedd = array
                        (
                            'type'   => 'Payment Confirmed',
                            'title'=>"Payment Confirmed",
                            'id'     => $appointment_details[0]->id,
                            'patient_id'     => $appointment_details[0]->patient,
                            'user_id' => $patient_data[0]->ion_user_id,
                            'msg'  => 'Your due amount of Rs. '.$invoice_data[0]->due.' for invoice #'.$invoice_data[0]->id .' has been Successfully done.',
                        );
                        $n_data = array();
                        date_default_timezone_set("Asia/Kolkata"); 
                        $n_data = array(
                            'user_id' => $patient_data[0]->ion_user_id,
                            'type' => $data_addedd['type'],
                            'title'=>"Payment Confirmed",
                            'message' => $data_addedd['msg'],
                            'profile_img' => $patient_data[0]->img_url,
                            'appointment_id' => $appointment_details[0]->id,
                            'created_at' => time(),
                            'read_status' => 0
                        );
                                
                        $n_result=$this->db->insert('notification', $n_data);

                    foreach ($user_device_data as $ud) {
                        $deviceToken =$ud->device_token;
                        $deviceType = $ud->device_type;
                        
                        _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd); 
                    }
                    // notification to doctor
                    $this->db->where('user_id', $doctor_data[0]->ion_user_id);
                    $this->db->where('status', 1);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_data = $user_device_query->result();

                        $doctor_data_addedd = array
                        (
                            'type'   => 'Payment Confirmed',
                            'title'=>"Payment Confirmed",
                            'id'     => $appointment_details[0]->id,
                            'patient_id'     => $appointment_details[0]->patient,
                            'user_id' => $doctor_data[0]->ion_user_id,
                            'msg'  => 'Payment of #'.$appointment_details[0]->id .' has been Successfully Confirmed.',
                        );
                        $doctor_n_data = array();
                        date_default_timezone_set("Asia/Kolkata"); 
                        $doctor_n_data = array(
                            'user_id' => $doctor_data[0]->ion_user_id,
                            'type' => $doctor_data_addedd['type'],
                            'title'=>"Payment Confirmed",
                            'message' => $doctor_data_addedd['msg'],
                            'profile_img' => $doctor_data[0]->img_url,
                            'appointment_id' => $appointment_details[0]->id,
                            'created_at' => time(),
                        );
                                
                        $doctor_n_result=$this->db->insert('notification', $doctor_n_data);

                    foreach ($user_device_data as $ud) {
                        $doctor_deviceToken =$ud->device_token;
                        $doctor_deviceType = $ud->device_type;
                        
                        _send_fcm_notification($doctor_deviceToken,$doctor_deviceType,'',$doctor_data_addedd);  
                    }                
        echo json_encode(['data'=>$order_id,'status'=>"true",'message'=>"Order Successfully."]);
    }
    function summary() {
        
        $summary=[];
        $all_patient= $this->db->get("patient")->result();
        $summary['all_patient_count'] = count($all_patient);

        $old_patient_result = $this->db->select("p.id")->from('patient p')->join('appointment A','p.id = A.patient' , 'left')->where('A.status',4)->group_by('p.id')->get()->result();
        $summary['old_patient_count']= count($old_patient_result);

        $summary['new_patient_count']=$summary['all_patient_count']-$summary['old_patient_count'];

        $visit = $this->db->select("p.id")->from('patient p')->join('appointment A','p.id = A.patient' , 'left')->where('A.status',4)->where('A.mode_of_consultation','Clinic')->group_by('p.id')->get()->result();
        $summary['visit_count']= count($visit);

        if ($summary['all_patient_count'] >= 1) {
           echo json_encode(['data'=>$summary,'status'=>"true",'message'=>"All Summary"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Patient Found"]);
        }
    }
    function payments_received_by_date() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $todaystart=date("Y-m-d",strtotime($_POST['date']));
        $payment= $this->db->get("payment")->result();
        $today_payment=0;
        foreach ($payment as $p) {
            if (date("Y-m-d",strtotime($p->created_at)) == $todaystart) {
                // array_push($today_payment,date("Y-m-d", strtotime($p->created_at)));
                $today_payment += $p->amount;
            }
        }

        if ($payment >= 1) {
           echo json_encode(['data'=>$today_payment,'status'=>"true",'message'=>"Total Amount"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Payment Found"]);
        }
    }
    function shipping_companies() {
        
        $shipping_companies= $this->db->get("shipping_companies")->result();
        
        if ($shipping_companies >= 1) {
           echo json_encode(['data'=>$shipping_companies,'status'=>"true",'message'=>"List of Shipping Companies"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Shipping Companies Found"]);
        }
    }
    function due_invoice_by_date() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $todaystart = date("d-m-Y",strtotime($_POST['date']));
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due >=', 1);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            $invoice=[];
            foreach ($invoice_details as $i) {
                if (date('d-m-Y',$i->created_at) == $todaystart) {
                    $invoice[$count]->id=$i->id;
                    $invoice[$count]->appointment_id=$i->appointment_id;
                    $invoice[$count]->consultation_fee=$i->consultation_fee;
                    
                    $this->db->where('id', $i->appointment_id);
                    $appointment_query = $this->db->get('appointment');
                    $appointment_details= $appointment_query->result();

                    $this->db->where('id', $appointment_details[0]->patient);
                    $patient_query = $this->db->get('patient');
                    $patient_details= $patient_query->result();

                    $invoice[$count]->medicine_details=json_decode($invoice_details[$count]->medicine_details);
                    $invoice[$count]->medicine_fee=array_sum(json_decode($invoice_details[$count]->medicine_fee));
                    // $invoice_details[$count]->medicine_details=json_decode($i->medicine_details);
                    // $invoice_details[$count]->medicine_fee=json_decode($i->medicine_fee);
                    $invoice[$count]->medicine_fee_total=array_sum(json_decode($i->medicine_fee));
                    if ($invoice[$count]->medicine_fee_total==null) {
                        $invoice[$count]->medicine_fee_total=0;
                    }
                    $invoice[$count]->total=$i->total;
                    $invoice[$count]->paid=$i->paid;
                    $invoice[$count]->due=$i->due;
                    $invoice[$count]->patient_id=$appointment_details[0]->patient;
                    $invoice[$count]->patient_name=$patient_details[0]->name;
                    $invoice[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                    $invoice[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                    $invoice[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                    $count++;
                }
                
            }
            if (count($invoice)>=1) {
                echo json_encode(['data'=>$invoice,'status'=>"true",'message'=>"Due invoice by date"]);
            }else{
                $blank_object=array();
                echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
            }
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    function paid_invoice_by_date() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $todaystart = date("d-m-Y",strtotime($_POST['date']));
        
        $this->db->order_by('id', 'desc');
        $this->db->where('due', 0);
        $invoice_query = $this->db->get('invoice');
        $invoice_details= $invoice_query->result();

        if (count($invoice_details)>=1) {
            date_default_timezone_set("Asia/Kolkata");
            $count=0;
            $invoice=[];
            foreach ($invoice_details as $i) {
                if (date('d-m-Y',$i->created_at) == $todaystart) {
                    $invoice[$count]->id=$i->id;
                    $invoice[$count]->appointment_id=$i->appointment_id;
                    $invoice[$count]->consultation_fee=$i->consultation_fee;
                    
                    $this->db->where('id', $i->appointment_id);
                    $appointment_query = $this->db->get('appointment');
                    $appointment_details= $appointment_query->result();

                    $this->db->where('id', $appointment_details[0]->patient);
                    $patient_query = $this->db->get('patient');
                    $patient_details= $patient_query->result();

                    $invoice[$count]->medicine_details=json_decode($invoice_details[$count]->medicine_details);
                    $invoice[$count]->medicine_fee=array_sum(json_decode($invoice_details[$count]->medicine_fee));
                    // $invoice_details[$count]->medicine_details=json_decode($i->medicine_details);
                    // $invoice_details[$count]->medicine_fee=json_decode($i->medicine_fee);
                    $invoice[$count]->medicine_fee_total=array_sum(json_decode($i->medicine_fee));
                    if ($invoice[$count]->medicine_fee_total==null) {
                        $invoice[$count]->medicine_fee_total=0;
                    }
                    $invoice[$count]->total=$i->total;
                    $invoice[$count]->paid=$i->paid;
                    $invoice[$count]->due=$i->due;
                    $invoice[$count]->patient_id=$appointment_details[0]->patient;
                    $invoice[$count]->patient_name=$patient_details[0]->name;
                    $invoice[$count]->created_at=date('d-m-Y h:i A',$i->created_at);
                    $invoice[$count]->view_invoice=base_url()."api/invoice_by_invoice_id?invoice_id=".$i->id;
                    $invoice[$count]->download_invoice=base_url()."api/download_invoice_by_invoice_id?invoice_id=".$i->id;
                    $count++;
                }
                
            }
            if (count($invoice)>=1) {
                echo json_encode(['data'=>$invoice,'status'=>"true",'message'=>"Paid invoice by date"]);
            }else{
                $blank_object=array();
                echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
            }
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No Invoice Found"]);
        }
    }
    public function send_message() {
        date_default_timezone_set("Asia/Kolkata"); 
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $user_id =  $_POST['user_id'];
        $appointment_id =  $_POST['appointment_id'];
        $message = $_POST['message'];
        $check = $_POST['check'];

        $this->db->where('id',$user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();

        $this->db->where('ion_user_id',$user_id);
        $patient_query = $this->db->get('patient');
        $patient_data =  $patient_query->result();

        $message_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $message_data = array(
                    'user_id' => $user_id,
                    'appointment_id' => $appointment_id,
                    'message' => $message,
                    'read_status' => 0,
                    "file_upload"=>$check,
                    'created_at' => time(),
                );

        $message_result=$this->db->insert('message', $message_data);

        if ($check == 1) {
            if ($check==1) {
                
                $data_addedd = array
                (
                    'type'   => 'message',
                    'id'     => $user_id,
                    'user_id'     => $user_id,
                    'title'=>'New Message',
                    'msg'  => 'You got a new message from Doctor with document uploading permission.',
                );
            }else{
                
                $data_addedd = array
                (
                    'type'   => 'message',
                    'id'     => $user_id,
                    'user_id' => $user_id,
                    'title'=>'New Message',
                    'message'     => $message,
                    'msg'  => 'You got a new message from Doctor.',
                );
            }
            $this->db->where('user_id', $user_id);
            $this->db->where('status', 1);
            $user_device_query = $this->db->get('user_device');
            $user_device_data = $user_device_query->result();

            $n_data = array(
                'user_id' => $user_id,
                'type' => $data_addedd['type'],
                'message' => $data_addedd['msg'],
                'title'=>'New Message',
                'profile_img' => $patient_data[0]->img_url,
                'appointment_id' => $appointment_id,
                'created_at' => time(),
                'read_status' => 0
            );
                    
            $n_result=$this->db->insert('notification', $n_data); 

            foreach ($user_device_data as $ud) {
            
                $deviceToken = $ud->device_token;
                $deviceType = $ud->device_type;
                
                _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd); 
            }      
        }
        echo json_encode(['data'=>$n_data,'status'=>"true",'message'=>"Message sent successfully."]);
    }
    public function count_message() {
        date_default_timezone_set("Asia/Kolkata"); 
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $user_id =  $_POST['user_id'];

        $this->db->where('id',$user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();

        $this->db->where('user_id',$user_id);
        $this->db->where('read_status',0);
        $message_query = $this->db->get('message');
        $message_data =  $message_query->result();

        if (count($message_data) >=1) {
            $message_data_count=count($message_data);
            echo json_encode(['data'=>"$message_data_count",'status'=>"true",'message'=>"All unread message count."]);
        }else{
            echo json_encode(['data'=>"0",'status'=>"true",'message'=>"No message found."]);
        } 
    }
    public function all_message_by_user_id() {
        date_default_timezone_set("Asia/Kolkata"); 
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $user_id =  $_POST['user_id'];

        $this->db->where('id',$user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();

        $this->db->where('user_id',$user_id);
        $this->db->order_by("created_at desc");
        $message_query = $this->db->get('message');
        $message_data =  $message_query->result();

        if (count($message_data) >=1) {
            $count=0;
            foreach ($message_data as $m) {
                $message_data[$count]->created_at=date('d-m-Y h:i A',$m->created_at);
                $count++;
            }
            echo json_encode(['data'=>$message_data,'status'=>"true",'message'=>"All message by user id."]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No message found."]);
        } 
    }
    function visit_inclinic_list() {

        $visit = $this->db->select("p.id as patient_id,
        p.ion_user_id as user_id,
        p.name as patient_name,
        p.img_url as img_url, 
        p.birthdate as birthdate, 
        p.email as patient_email, 
        p.phone as patient_phone, 
        p.address as patient_address, 
        users.deviceToken as patient_device_token, 
        A.id as appointment_id,
        A.s_time as starttime,
        A.e_time as endtime,
        A.time_slot as time_slot, 
        A.remarks as remarks ,
        A.registration_time as registration_time , 
        A.patient_type as patient_type, 
        A.mode_of_consultation as mode_of_consultation, 
        A.type_of_consultation as type_of_consultation, 
        A.date as booking_date,
        A.status as status_id, 
        appointment_status.status_name as status,
        location.id as location_id,
        location.name as location_name")->from('patient p')->join('appointment A','p.id = A.patient' , 'left')->join('location','location.id=A.location_id' ,'left')->join('users','users.id=p.ion_user_id','left')->join('appointment_status','appointment_status.id=A.status','left')->where('A.status',4)->where('A.mode_of_consultation','Clinic')->group_by('p.id')->order_by("date asc")->order_by("s_time asc")->get()->result();

        if (count($visit) >= 1) {
            $count=0;
            foreach ($visit as $v) {
                if (!empty($v->birthdate)) {
                    $visit[$count]->birthdate=date('d-m-Y',$v->birthdate);
                }else{
                    $visit[$count]->birthdate=null;
                }
                if (!empty($v->img_url)) {
                    $visit[$count]->img_url=base_url().$v->img_url;
                }else{
                    $visit[$count]->img_url=null;
                }
                $visit[$count]->registration_time=date('d-m-Y h:i A',$v->registration_time);
                // $visit[$count]->created_at=date('d-m-Y h:i A',$v->created_at);
                $count++;
            }
           echo json_encode(['data'=>$visit,'status'=>"true",'message'=>"All visitor list in clinic"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No visitor found in clinic"]);
        }
    }
    function visit_inclinic_list_by_date() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $date =  date("m-d-Y", strtotime($_POST['date']));

        $visit = $this->db->select("p.id as patient_id,
        p.ion_user_id as user_id,
        p.name as patient_name,
        p.img_url as img_url, 
        p.birthdate as birthdate, 
        p.email as patient_email, 
        p.phone as patient_phone, 
        p.address as patient_address, 
        users.deviceToken as patient_device_token, 
        A.id as appointment_id,
        A.s_time as starttime,
        A.e_time as endtime,
        A.time_slot as time_slot, 
        A.remarks as remarks ,
        A.registration_time as registration_time , 
        A.patient_type as patient_type, 
        A.mode_of_consultation as mode_of_consultation, 
        A.type_of_consultation as type_of_consultation, 
        A.date as booking_date,
        A.status as status_id, 
        appointment_status.status_name as status,
        location.id as location_id,
        location.name as location_name")->from('patient p')->join('appointment A','p.id = A.patient' , 'left')->join('location','location.id=A.location_id' ,'left')->join('users','users.id=p.ion_user_id','left')->join('appointment_status','appointment_status.id=A.status','left')->where('A.status',4)->where('A.date',$_POST['date'])->where('A.mode_of_consultation','Clinic')->order_by("A.date asc")->order_by("A.s_time asc")->get()->result();
        // echo json_encode(['data'=>$visit,'status'=>"true",'message'=>"All visitor list in clinic"]);
        if (count($visit) >= 1) {
            $count=0;
            foreach ($visit as $v) {
                if (!empty($v->birthdate)) {
                    $visit[$count]->birthdate=date('d-m-Y',$v->birthdate);
                }else{
                    $visit[$count]->birthdate=null;
                }
                if (!empty($v->img_url)) {
                    $visit[$count]->img_url=base_url().$v->img_url;
                }else{
                    $visit[$count]->img_url=null;
                }
                $visit[$count]->registration_time=date('d-m-Y h:i A',$v->registration_time);
                // $visit[$count]->created_at=date('d-m-Y h:i A',$v->created_at);
                $count++;
            }
           echo json_encode(['data'=>$visit,'status'=>"true",'message'=>"All visitor list in clinic"]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No visitor found in clinic"]);
        }
    }
    public function update_read_message() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $user_id =  $_POST['user_id'];

        $this->db->where('read_status',0);
        $this->db->where('user_id',$user_id);
        $message_query = $this->db->get('message');
        $message_data =  $message_query->result();

        if (count($message_data) >=1) {
            $update_rows = array(
                'read_status' => 1,
            );
            foreach ($message_data as $m) {
                $this->db->where('id', $m->id);
            $result = $this->db->update('message', $update_rows); 
            }
            
            

            $this->db->where('user_id',$user_id);
            $message_query2 = $this->db->get('message');
            $message_data2 =  $message_query2->result();

            echo json_encode(['data'=>$message_data2,'status'=>"true",'message'=>"Message read status updated."]);
        }else{
            $blank_object=array();
            echo json_encode(['data'=>$blank_object,'status'=>"false",'message'=>"No message found by this given id"]);
        }
        
    }
    function prescription_by_appointment_id() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $appointment_id    = $_POST['appointment_id'];

        $this->db->order_by('id', 'desc');
        $this->db->where('id', $appointment_id);
        $appointment_query = $this->db->get('appointment');
        $appointment=$appointment_query->result();

        $this->db->where('id', $appointment[0]->location_id);
        $location_query = $this->db->get('location');
        $location=$location_query->result();

        $data['appointment_id']=$appointment_id;
        $data['appointment_date']=$appointment[0]->date;
        $data['appointment_time_slot']=$appointment[0]->time_slot;
        $data['appointment_start_time']=$appointment[0]->s_time;
        $data['appointment_status_id']=$appointment[0]->status;
        $data['appointment_mode_of_consultation']=$appointment[0]->mode_of_consultation;
        $data['appointment_type_of_consultation']=$appointment[0]->type_of_consultation;
        $data['appointment_type_of_location_id']=$appointment[0]->location_id;
        $data['appointment_created_at']=date('d-m-Y', $appointment[0]->registration_time);
        $data['location_name']=$location[0]->name;


        $this->db->order_by('id', 'desc');
        $this->db->where('id', $appointment[0]->patient);
        $patient_query = $this->db->get('patient');
        $patient=$patient_query->result();

        $data['patient_id']=$patient[0]->id;
        $data['patient_name']=$patient[0]->name;
        $data['patient_email']=$patient[0]->email;
        $data['patient_address']=$patient[0]->address;
        $data['patient_phone']=$patient[0]->phone;
        $data['patient_weight']=$patient[0]->weight;
        $data['patient_merital_status']=$patient[0]->merital_status;
        $data['patient_spouse_name']=$patient[0]->spouse_name;
        if($patient[0]->img_url!='') {
            $data['patient_photo']=base_url().$patient[0]->img_url;
        }else{
            $data['patient_photo']=null;
        }

        $this->db->order_by('id', 'desc');
        $this->db->where('appointment_id', $appointment_id);
        $prescription_query = $this->db->get('prescription');
        $Prescriptionlist=$prescription_query->result();

        $data['Prescriptionlist_id']=$Prescriptionlist[0]->id;
        $data['Prescriptionlist_date']=date('d-m-Y', $Prescriptionlist[0]->date);
        $data['Prescriptionlist_url']=base_url()."api/viewPrescription?id=".$Prescriptionlist[0]->id;
        $data['Prescriptionlist_pdf_view_url']=base_url()."api/onlyviewPrescription?id=".$Prescriptionlist[0]->id;
        $medicine = $Prescriptionlist[0]->medicine;
        foreach ($medicine as $key => $value) {
                    $single_medicine = explode("***", $value);
                    $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name;
                    $single_medicine_details['medicine_name']=$single_medicine_name;
                    $single_medicine_details['mg']=$single_medicine[1];
                    $single_medicine_details['frequency']=$single_medicine[2];
                    $single_medicine_details['days']=$single_medicine[3];
                    $single_medicine_details['instruction']=$single_medicine[4];
                    array_push($medicine_array,$single_medicine_details);
                }
        $data['Prescriptionlist_medicine']=$medicine_array;
        $data['Prescriptionlist_history']=$Prescriptionlist[0]->symptom;
        $data['Prescriptionlist_advice']=$Prescriptionlist[0]->advice;
        $data['Prescriptionlist_note']=$Prescriptionlist[0]->note;
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    function automatic_send_notification_old() {

        $this->db->order_by('id', 'desc');
        $prescription_query = $this->db->get('prescription');
        $Prescriptionlist=$prescription_query->result();
        $data=[];
        $medicine_array=[];
        $count=0;
        foreach ($Prescriptionlist as $p) {
            $medicine = $p->medicine;
            $medicine = explode("###", $medicine);
            foreach ($medicine as $key => $value) {
                $single_medicine = explode("***", $value);
                $single_medicine_name = $this->medicine_model->getMedicineById($single_medicine[0])->name;
                // $single_medicine_details['date']=date('d-m-Y', $p->date);
                $next_date=date('d-m-Y', strtotime('+ '.$single_medicine[3], $p->date));
                // $single_medicine_details['next_date']=$next_date;
                // $single_medicine_details['send_date']=date('d-m-Y', strtotime('- 1 days', strtotime($next_date)));
                // $single_medicine_details['today']=date('d-m-Y');
                if (date('d-m-Y')==date('d-m-Y', strtotime('- 1 days', strtotime($next_date)))) {
                    array_push($medicine_array,$single_medicine_name);
                }
                
            }
            if (count($medicine_array)>=1) {
                $medicine_names=implode(", ",$medicine_array);
                $data[$count]->medicine_name = $medicine_names;
                $data[$count]->patient_id = $p->patient;
                $count++;

                // send notification
                $this->db->where('id', $p->patient);
                $patient_query = $this->db->get('patient');
                $patient_data = $patient_query->result_array();

                $this->db->where('id', $patient_data[0]['ion_user_id']);
                $users_query = $this->db->get('users');
                $users_data = $users_query->result_array();

                $this->db->where('user_id', $patient_data[0]['ion_user_id']);
                $this->db->where('status', 1);
                $user_device_query = $this->db->get('user_device');
                $user_device_data = $user_device_query->result();
                $user_id=$users_data[0]['id'];

                    $data_addedd = array
                    (
                        'type'   => 'reminder',
                        'user_id'     => $user_id,
                        'patient_id'     => $p->patient,
                        'title'=>"Reminder",
                        'msg'  => "Your medicines ".$medicine_names." will be going to finished tomorrow. Please contact to doctor as soon as possible.",
                    );
                    $n_data = array();
                    date_default_timezone_set("Asia/Kolkata"); 
                        $n_data = array(
                            'user_id' => $user_id,
                            'type' => $data_addedd['type'],
                            'message' => $data_addedd['msg'],
                            'title'=>"Reminder",
                            'profile_img' => base_url().$patient_data[0]->img_url,
                            'appointment_id' => $p->appointment_id,
                            'created_at' => time(),
                            'read_status' => 0
                        );
                                
                        $n_result=$this->db->insert('notification', $n_data);
                
                foreach ($user_device_data as $ud) {
                
                    $deviceToken = $ud->device_token;
                    $deviceType = $ud->device_type;

                    _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
                } 
            }
            
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    } 
    function automatic_send_notification() {

        $this->db->order_by('id', 'desc');
        $dispatches_query = $this->db->get('dispatches');
        $dispatches_list=$dispatches_query->result();
        $n_data = array();
        $count=0;
        foreach ($dispatches_list as $d) {
            if (!empty($d->date) || $d->date!=0) {
                if (date('d-m-Y',$d->date)==date('d-m-Y')) {

                    $this->db->where('id', $d->prescription_id);
                    $prescription_query = $this->db->get('prescription');
                    $Prescriptionlist=$prescription_query->result();

                    $this->db->where('id', $Prescriptionlist[0]->patient);
                    $patient_query = $this->db->get('patient');
                    $patient_list=$patient_query->result();
                    $user_id=$patient_list[0]->ion_user_id;

                    $this->db->where('user_id', $user_id);
                    $this->db->where('status', 1);
                    $user_device_query = $this->db->get('user_device');
                    $user_device_data = $user_device_query->result();

                        $data_addedd = array
                        (
                            'type'   => 'reminder',
                            'user_id'     => $user_id,
                            'patient_id'     => $patient_list[0]->ion_user_id,
                            'title'=>"Reminder",
                            'msg'  => "Your medicines will be going to finished tomorrow. Please contact to doctor as soon as possible.",
                        );
                        
                        date_default_timezone_set("Asia/Kolkata"); 
                            $n_data = array(
                                'user_id' => $user_id,
                                'type' => $data_addedd['type'],
                                'message' => $data_addedd['msg'],
                                'title'=>"Reminder",
                                'profile_img' => $patient_list[0]->img_url,
                                'appointment_id' => $Prescriptionlist[0]->appointment_id,
                                'created_at' => time(),
                                'read_status' => 0
                            );
                                    
                            $n_result=$this->db->insert('notification', $n_data);
                    
                    
                    foreach ($user_device_data as $ud) {
                    
                        $deviceToken = $ud->device_token;
                        $deviceType = $ud->device_type;

                        _send_fcm_notification($deviceToken,$deviceType,'',$data_addedd);
                        $count++;
                    } 
                }
                
            }
        }   
        echo json_encode(['data'=>$n_data,'status'=>"true",'message'=>"success"]);
    }  
    function test(){

        $this->db->where('id',$appointment_details[0]->doctor);
        $doctor_query = $this->db->get('doctor');
        $doctor_data =  $doctor_query->result();

        $this->db->where('id',$doctor_data[0]->ion_user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();

        $this->db->where('user_id', $doctor_data[0]->ion_user_id);
        $this->db->where('status', 1);
        $user_device_query = $this->db->get('user_device');
        $user_device_data = $user_device_query->result();

       
        $data_addedd2 = array
        (
            'type'   => 'doctor_dispatched_notification',
            'id'     => $appointment_id,
            'title'=>"Dispatched",
            'user_id' => $doctor_data[0]->ion_user_id,
            'patient_id'     => $appointment_details[0]->patient,
            'msg'  => 'Prescription #'.$prescription_data[0]->id .' has been dispatched.',
        );
        $n_data2 = array();
        date_default_timezone_set("Asia/Kolkata"); 
        $n_data2 = array(
            'user_id' => $doctor_data[0]->ion_user_id,
            'type' => $data_addedd2['type'],
            'title'=>"Dispatched",
            'message' => $data_addedd2['msg'],
            'profile_img' => $doctor_data[0]->img_url,
            'appointment_id' => $appointment_details[0]->id,
            'created_at' => time(),
        );
                
        // $d_result=$this->db->insert('notification', $n_data2);

        foreach ($user_device_data as $ud) {
            $deviceToken =$ud->device_token;
            $deviceType = $ud->device_type;
            
        _send_fcm_notification($deviceToken2,$deviceType2,'',$data_addedd2); 
        }
    }  
    public function getPrescriptionlist_by_appointment() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $appointment_id    = $_POST['appointment_id'];

        $this->db->order_by('id', 'desc');
        $this->db->where('appointment_id', $appointment_id);
        $query = $this->db->get('prescription');

        $data['Prescriptionlist'] = $query->result();

        $count=0;
        foreach ($data['Prescriptionlist'] as $d) {

            $this->db->where('id', $d->patient);
            $query = $this->db->get('patient');
            $patient=$query->result();
            
            if ($d->state==null) {
                $data['Prescriptionlist'][$count]->state="null";
            }
            if ($d->dd==null) {
                $data['Prescriptionlist'][$count]->dd="null";
            }
            if ($d->validity==null) {
                $data['Prescriptionlist'][$count]->validity="null";
            }
            if ($patient[0]->name==null) {
                $data['Prescriptionlist'][$count]->patientname="null";
            }else{
                $data['Prescriptionlist'][$count]->patientname=$patient[0]->name;
            }
            if ($patient[0]->email==null) {
                $data['Prescriptionlist'][$count]->patient_email="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_email=$patient[0]->email;
            }
            if ($patient[0]->address==null) {
                $data['Prescriptionlist'][$count]->patient_address="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_address=$patient[0]->address;
            }
            if ($patient[0]->phone==null) {
                $data['Prescriptionlist'][$count]->patient_phone="null";
            }else{
                $data['Prescriptionlist'][$count]->patient_phone=$patient[0]->phone;
            }
            $data['Prescriptionlist'][$count]->date=date('d-m-Y', $d->date);
            
            
                $this->db->where('prescription_id',$d->id);
                $dispatches_query = $this->db->get('dispatches');
                $dispatches_data =  $dispatches_query->result();
                if (!empty($dispatches_data)) {
                    $this->db->where('id',$dispatches_data[0]->company_id);
                    $shipping_company = $this->db->get('shipping_companies')->result();

                    $data['Prescriptionlist'][$count]->company_id=$shipping_company[0]->id;
                    $data['Prescriptionlist'][$count]->company_name=$shipping_company[0]->name;
                    $data['Prescriptionlist'][$count]->traking_id = $dispatches_data[0]->tracking_id;
                    $data['Prescriptionlist'][$count]->traking_url=$shipping_company[0]->url."?id=".$dispatches_data[0]
                    ->tracking_id;
                }else{
                    $data['Prescriptionlist'][$count]->traking_id="null";
                    $data['Prescriptionlist'][$count]->traking_url="null";
                }
                
                $data['Prescriptionlist'][$count]->created_at=date('d-m-Y h:i A',$d->created_at);
                $data['Prescriptionlist'][$count]->url=base_url()."api/viewPrescription?id=".$d->id;
                $data['Prescriptionlist'][$count]->pdf_view_url=base_url()."api/onlyviewPrescription?id=".$d->id;
            $count++;
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }
    public function getLabreportlist_by_appointment() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $appointment_id    = $_POST['appointment_id'];

        $this->db->where('appointment_id', $appointment_id);   
        $query = $this->db->get('lab');
        $data['lablist'] = $query->result();

        $count=0;
        $count2=0;
    
        $this->db->where('id',$patient_id);
        $patient_query = $this->db->get('patient');
        $patient_data =  $patient_query->result();
    
        $this->db->where('user_id',$patient_data[0]->ion_user_id);
        $this->db->where('file_upload',1);
        $message_query = $this->db->get('message');
        $message_data =  $message_query->result();
        if (count($message_data) >= 1) {
            $today_date = date('d-m-Y');
            $end_date = date('d-m-Y', strtotime('-3 days', strtotime($today_date)));
    
            foreach ($message_data as $m) {
                $message_date=date('d-m-Y', $m->created_at);
                if (($message_date > $end_date) && ($message_date <= $today_date)){
                    $count2++;
                }
            }
            if ($count2 >= 1) {
                $data['upload_status']="1";
            }else{
                $data['upload_status']="0";
            }
        }else{
            $data['upload_status']="0";
        }
        foreach ($data['lablist'] as $l) {
            $data['lablist'][$count]->date=date("d-m-Y",$l->date);
            if (empty($l->created_at)) {
                $data['lablist'][$count]->created_at=null;
            }else{
                $data['lablist'][$count]->created_at=date("d-m-Y h:i A",$l->created_at);
            }
            
            $data['lablist'][$count]->url=base_url()."api/viewlabreport?id=".$l->id;
    
            $this->db->where('id',$l->template_id);
            $template_query = $this->db->get('template');
            $template_data =  $template_query->result();
            $data['lablist'][$count]->template_name=$template_data[0]->name;
            $count++;
        }
        
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    
    }
    public function getDocumentlist_by_appointment() {

        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $appointment_id    = $_POST['appointment_id'];
        if (!empty($appointment_id)) {
            $this->db->where('appointment_id', $appointment_id);
            $this->db->order_by('id','desc');
            $query = $this->db->get('patient_material');    
            $data['document'] = $query->result();
            // echo $this->db->last_query();
            $this->db->where('id',$appointment_id);
            $appointment_query = $this->db->get('appointment');
            $appointment_data =  $appointment_query->result();
            
            $this->db->where('id',$appointment_data[0]->patient);
            $patient_query = $this->db->get('patient');
            $patient_data =  $patient_query->result();
            
            $this->db->where('user_id',$patient_data[0]->ion_user_id);
            $this->db->where('file_upload',1);
            $this->db->where('appointment_id',$appointment_id);
            $message_query = $this->db->get('message');
            $message_data =  $message_query->result();
            // echo count($message_data);
            if (count($message_data) >= 1) {
                $today_date = date('d-m-Y');
                $end_date = date('d-m-Y', strtotime('-3 days', strtotime($today_date)));
        
                foreach ($message_data as $m) {
                   $message_date=date('d-m-Y', $m->created_at);
                    if (($message_date > $end_date) && ($message_date <= $today_date)){
                        $count2++;
                    }
                }
                // echo $count2;
                if ($count2 >= 1) {
                    $data['upload_status']="1";
                }else{
                    $data['upload_status']="0";
                }
            }else{
                $data['upload_status']="0";
            }
            $count=0;
            foreach ($data['document'] as $d) {
                if($d->url!='') {
                    $data['document'][$count]->url=base_url().$d->url;
                }else{
                    $data['document'][$count]->url=null;
                }
                $data['document'][$count]->date=date('d-m-Y',$d->date);
                $count++;
            }
            
            // changes by shifali mam
            
            if (count($data['document'] ) >0) {
                echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
            }else{
                $data['document']= array(new stdClass());
                echo json_encode(['data'=>$data,'status'=>"true",'message'=>"No data found"]);
            }
        }else {
            echo json_encode(['data'=>$data,'status'=>"false",'message'=>"No appointment id found"]);
        }
        
    
    }
    public function update_notification_read_staus(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $notification_id=$_POST['notification_id'];

        $this->db->where('id',$notification_id);
        $notification_query = $this->db->get('notification');
        $notification_data = $notification_query->result();

        if (count($notification_data)>0) {
            $update_rows = array(
                'read_status' => 1
            );
            $this->db->where('id', $notification_id);
            $result = $this->db->update('notification', $update_rows); 
            echo json_encode(['data'=>$result,'status'=>"true",'message'=>"Notification read status updated"]);
        }else{
            echo json_encode(['data'=>'','status'=>"false",'message'=>"Not found"]);
        }
    }
    
    function send_notification() {
        $data_addedd2 = array
        (
            'type'   => 'doctor_dispatched_notification',
            'id'     => 11,
            'title'=>"Dispatched",
            'patient_id'     => 1,
            'msg'  => 'Prescription #1 has been dispatched.',
        );
        $device_token="dsQsHyP2QBmMXKVzlqZysa:APA91bETjAq2d5jzCyJ1ZF21n_Zz4G_R0uFYY1Aodl6mx-zvk68h0GVNFPDfLXhN4XYgY_XZ8uhaQUHJ3FcH7gmw_pCaFRgn-6hTH4pgKnxr6i2SmzID15Fd3-T8YqMNPrgjyP10OPHK";
        $message = array
        (
            'title'   => 'Curewell Therapies',
            'body'     => 'Prescription #1 has been dispatched.',
        );
        $json_data = [
            "to" => $device_token,
            "notification" => $message,
            "data" => $data_addedd2
        ];
        
        $notification_data = json_encode($json_data);
        //FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = 'AAAA3PrYRQU:APA91bHamDe8dNAWgZx8f5cfPRncNY_UCmJ8ixrXZ00X804O6f8DafNrRksCufx5zqu4GzGTt6En52ISkboSrU4aUIXRhC6W_YNcF9L1ZNwS7FUPRREFR2zQ_GuE_yKt7z2SsajL3AED';
        
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key,
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $notification_data);
        $result = curl_exec($ch);
        echo json_encode($result);
        if ($result === false) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        echo json_encode($json_data);
    //    return true;


    }  
    function get_slot_by_date() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        date_default_timezone_set("Asia/Kolkata");
        
        $this->form_validation->set_rules('doctor_user_id', 'doctor id', 'required');
        $this->form_validation->set_rules('membership_code', 'boking type', 'required');
        $this->form_validation->set_rules('mode_of_consultation', 'consultation type', 'required');
        $this->form_validation->set_rules('date', 'date', 'required');
        
        if ($this->form_validation->run() == true) {
            $doctor_user_id=$_POST['doctor_user_id'];       //709
            $membership_code=$_POST['membership_code'];     //General or vip
            $location_id=$_POST['location_id'];
            $mode_of_consultation=$_POST['mode_of_consultation'];   //online or clinic
            $timestamp = strtotime($_POST['date']);     // $timestamp = strtotime('2022-01-24');
            $weekday = date('l', $timestamp);

            $this->db->where('doctor',$doctor_user_id);
            if ($mode_of_consultation == "clinic") {
                $this->db->where('location_id',$location_id);
            }
            $this->db->where('membership_code',$membership_code);
            $this->db->where('Weekday',$weekday);
            $query = $this->db->get('time_slot');
            $all_time_slot = $query->result();

            $count=0;
            foreach ($all_time_slot as $ats) {
                $ats->slot= $ats->s_time.' - '.$ats->e_time;
                if($timestamp == strtotime(date("d-m-Y"))){
                    if(strtotime($ats->s_time) <= strtotime(date("h:i A")) + ($this->settings_model->getSettings()->mindays_for_booking * 60 * 60)){
                        $all_time_slot[$count]->booked="y";
                        $count++;
                        
                    }else{
                        $this->db->where('date',$_POST['date']);
                        $this->db->where('s_time',$ats->s_time);
                        $this->db->where('status',7);
                        $appointment = $this->db->get('appointment');        
                        $num = $appointment->num_rows();  
                        if($num >=1){
                            $all_time_slot[$count]->booked="y";
                            
                        }else{
                            $all_time_slot[$count]->booked="n";
                        }
                        $count++;
                    }
                    
                }else{
                    $this->db->where('date',$_POST['date']);
                    // $this->db->where('doctor',$ats->doctor);
                    $this->db->where('s_time',$ats->s_time);
                    $this->db->where('status',7);
                    $appointment = $this->db->get('appointment');
                    $num = $appointment->num_rows();  
                    if($num >=1){
                        $all_time_slot[$count]->booked="y";
                        
                    }else{
                        $all_time_slot[$count]->booked="n";
                    }
                    $count++;
                }

            }
            if (count($all_time_slot) >0) {
                echo json_encode(['data'=>$all_time_slot,'status'=>"true",'message'=>"All available time slot"]);
            }else{
                $empty_object = new stdClass();
                echo json_encode(['data'=>[$empty_object],'status'=>"true",'message'=>"No slot found"]);

            }

        } else {
            $empty_object = new stdClass();
            $message = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            echo json_encode(['data'=>[$empty_object],'status'=>"false",'message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);

        }
    } 
}
