<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Appointment extends MX_Controller

{



    function __construct()

    {

        parent::__construct();



        $this->load->model('appointment_model');
        $this->load->model('diagnos/diagnos_model');

        $this->load->model('doctor/doctor_model');

        $this->load->model('patient/patient_model');

        $this->load->model('company/Company_model');

        $this->load->model('sms/sms_model');

        $this->load->module('sms');

        $this->load->library('SendMail');



        if (!$this->ion_auth->in_group(array('admin', 'Nurse', 'Doctor', 'Patient', 'Receptionist'))) {

            redirect('home/permission');
        }
    }



    public function index()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }







        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $data['status'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] =         $this->doctor_model->getType();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('appointment', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function fetch_type()

    {

        if ($this->input->post('consultation_id')) {

            echo $this->doctor_model->fetch_type($this->input->post('consultation_id'), $this->input->post('selected_type_of_consultation'));
        }
    }



    public function request()

    {





        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('appointment_request', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function todays()

    {

        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }





        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('todays', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function upcoming()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }



        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('upcoming', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function myTodays()

    {

        if (!$this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }





        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('my_todays', $data);

        $this->load->view('home/footer'); // just the header file

    }



    function calendar()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            $data['appointments'] = $this->appointment_model->getAppointmentByDoctor($doctor);
        } else {

            $data['appointments'] = $this->appointment_model->getAppointment();
        }



        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('calendar', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function addNewView()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }

        $data = array();

        $data['settings'] = $this->settings_model->getSettings();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] = $this->doctor_model->getType();

        $data['status'] = $this->doctor_model->getAllStatus();



        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('add_new', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function addNew()

    {

        $id = $this->input->post('id');

        $patient = $this->input->post('patient');

        $doctor = $this->input->post('doctor');

        $date = $this->input->post('date');

        if (!empty($date)) {

            $date = strtotime($date);
        }



        $time_slot = $this->input->post('time_slot') ?? '';





        $time_slot_explode = explode('To', $time_slot);



        $s_time = trim($time_slot_explode[0]);

        $e_time = trim($time_slot_explode[1]);



        $app_time = strtotime(date('d-m-Y', $date) . ' ' . $s_time);

        $app_time_full_format = date('d-m-Y', $date) . ' ' . $s_time . '-' . $e_time;



        $remarks = $this->input->post('remarks');



        $sms = $this->input->post('sms');



        $status = $this->input->post('status');



        $redirect = $this->input->post('redirect');



        $request = $this->input->post('request');



        if (empty($request)) {

            $request = '';
        }



        $user = $this->ion_auth->get_user_id();



        if ($this->ion_auth->in_group(array('Patient'))) {

            $user = '';
        }



        if ((empty($id))) {

            $add_date = date('m/d/y');

            $registration_time = time();

            $patient_add_date = $add_date;

            $patient_registration_time = $registration_time;
        } else {

            $add_date = $this->appointment_model->getAppointmentById($id)->add_date;

            $registration_time = $this->appointment_model->getAppointmentById($id)->registration_time;
        }



        $s_time_key = $this->getArrayKey($s_time);



        $p_name = $this->input->post('p_name');

        $p_email = $this->input->post('p_email');

        if (empty($p_email)) {

            $p_email = $p_name . '-' . rand(1, 1000) . '-' . $p_name . '-' . rand(1, 1000) . '@example.com';
        }

        if (!empty($p_name)) {

            $password = $p_name . '-' . rand(1, 100000000);
        }

        $p_phone = $this->input->post('p_phone');

        $p_age = $this->input->post('p_age');

        $p_gender = $this->input->post('p_gender');

        $patient_id = rand(10000, 1000000);



        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');



        if ($patient == 'add_new') {

            $this->form_validation->set_rules('p_name', 'Patient Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');

            $this->form_validation->set_rules('p_phone', 'Patient Phone', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        }



        // Validating Name Field

        $this->form_validation->set_rules('patient', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');

        // Validating Password Field

        $this->form_validation->set_rules('doctor', 'Doctor', 'trim|required|min_length[1]|max_length[100]|xss_clean');



        // Validating Email Field

        $this->form_validation->set_rules('date', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');

        // Validating Email Field

        $this->form_validation->set_rules('s_time', 'Start Time', 'trim|min_length[1]|max_length[100]|xss_clean');

        // Validating Email Field

        $this->form_validation->set_rules('e_time', 'End Time', 'trim|min_length[1]|max_length[100]|xss_clean');

        // Validating Address Field   

        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|min_length[1]|max_length[1000]|xss_clean');



        if ($this->form_validation->run() == FALSE) {

            if (!empty($id)) {

                redirect("appointment/editAppointment?id=$id");
            } else {



                $data['patients'] = $this->patient_model->getPatient();

                $data['doctors'] = $this->doctor_model->getDoctor();

                $data['settings'] = $this->settings_model->getSettings();

                $data['location'] = $this->doctor_model->getLocation();

                $data['consultation'] = $this->doctor_model->getConsultation_Mode();

                $data['type'] = $this->doctor_model->getType();

                $data['status'] = $this->doctor_model->getAllStatus();



                $this->load->view('home/dashboard', $data); // just the header file

                $this->load->view('add_new', $data);

                $this->load->view('home/footer'); // just the header file

            }
        } else {





            if ($time_slot == '' || $time_slot == 'Not Selected') {

                $this->session->set_flashdata('feedback', lang('slot_required'));

                redirect('appointment/addNewView');
            }



            if ($patient == 'add_new') {



                $data_p = array(

                    'patient_id' => $patient_id,

                    'name' => $p_name,

                    'email' => $p_email,

                    'phone' => $p_phone,

                    'sex' => $p_gender,

                    'age' => $p_age,

                    'add_date' => $patient_add_date,

                    'registration_time' => $patient_registration_time,

                    'how_added' => 'from_appointment'

                );

                $username = $this->input->post('p_name');

                // Adding New Patient

                if ($this->ion_auth->email_check($p_email)) {

                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));

                    if (!empty($redirect)) {

                        redirect($redirect);
                    } else {

                        redirect('appointment');
                    }
                } else {

                    $dfg = 5;

                    $this->ion_auth->register($username, $password, $p_email, $dfg);

                    $ion_user_id = $this->db->get_where('users', array('email' => $p_email))->row()->id;

                    $this->patient_model->insertPatient($data_p);

                    $patient_user_id = $this->db->get_where('patient', array('email' => $p_email))->row()->id;

                    $id_info = array('ion_user_id' => $ion_user_id);

                    $this->patient_model->updatePatient($patient_user_id, $id_info);
                }



                $patient = $patient_user_id;
            }



            $patient_phone = $this->patient_model->getPatientById($patient)->phone;

            if (empty($id)) {

                $room_id = 'hms-meeting-' . $patient_phone . '-' . rand(10000, 1000000);

                $live_meeting_link = 'https://meet.jit.si/' . $room_id;
            } else {

                $appointment_details = $this->appointment_model->getAppointmentById($id);

                $room_id = $appointment_details->room_id;

                $live_meeting_link = $appointment_details->live_meeting_link;
            }





            $patientname = $this->patient_model->getPatientById($patient)->name;

            $patientemail = $this->patient_model->getPatientById($patient)->email;

            $doctorname = $this->doctor_model->getDoctorById($doctor)->name;

            // if($this->input->post('amount') <=0){

            //     $this->session->set_flashdata('feedback', lang('Please_enter_a_valid_amount'));

            //     redirect('appointment/addNewView');

            // }

            $data = array();

            $data = array(

                'patient' => $patient,

                'patientname' => $patientname,

                'doctor' => $doctor,

                'doctorname' => $doctorname,

                'date' => $date,

                's_time' => $s_time,

                'e_time' => $e_time,

                'time_slot' => $time_slot,

                'add_date' => $add_date,

                'registration_time' => $registration_time,

                'status' => $status,

                's_time_key' => $s_time_key,

                'user' => $user,

                'request' => $request,

                'room_id' => $room_id,

                'live_meeting_link' => $live_meeting_link,

                'app_time' => $app_time,

                'app_time_full_format' => $app_time_full_format,

                'location_id' => htmlentities($this->input->post('location_id')),

                'mode_of_consultation' => htmlentities($this->input->post('mode_of_consultation')),

                'type_of_consultation' => htmlentities($this->input->post('type_of_consultation')),

                'remarks' => $remarks,

                'temp' => htmlentities($this->input->post('temp')),

                'bp' => htmlentities($this->input->post('bp')),

                'pulse' => htmlentities($this->input->post('pulse')),

                'spo2' => htmlentities($this->input->post('spo2')),

                'rr' => htmlentities($this->input->post('rr')),

                'height' => htmlentities($this->input->post('height')),

                'weight' => htmlentities($this->input->post('weight')),

                'bmi' => htmlentities($this->input->post('bmi')),

                'rbs' => htmlentities($this->input->post('rbs')),

                'amount' => htmlentities($amount),

                'form_step' => 1,

            );

            $username = $this->input->post('name');

            if (empty($id)) {     // Adding New department

                $this->appointment_model->insertAppointment($data);



                $patient_doctor = $this->patient_model->getPatientById($patient)->doctor;



                $patient_doctors = explode(',', $patient_doctor);



                if (!in_array($doctor, $patient_doctors)) {

                    $patient_doctors[] = $doctor;

                    $doctorss = implode(',', $patient_doctors);

                    $data_d = array();

                    $data_d = array('doctor' => $doctorss);

                    $this->patient_model->updatePatient($patient, $data_d);
                }

                // $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status);



                $autoemail = $this->email_model->getAutoEmailByType('patient');

                if ($autoemail->status == 'Active') {

                    $mail_provider = $this->settings_model->getSettings()->emailtype;

                    $settngs_name = $this->settings_model->getSettings()->system_vendor;

                    $emailSettings = $this->email_model->getEmailSettingsByType($mail_provider);



                    $subject = 'Appointment Booking Details';

                    $message = 'Dear ' . $data['patientname'] . ', Thank you for booking an appoinment. <br> Here is your appointment details.<br> <br>  Doctor: ' . $data['doctorname'] . ' <br> Appointment Date: ' . date('d-m-y', $data['date']) . '<br> Appointment Slot: ' . $data['time_slot'] . '<br><br> Thank You, <br>' . $this->settings->title;



                    $this->sendmail->send($emailSettings->admin_email, $patientemail, $subject, $message, $settngs_name, $emailSettings);
                }



                $this->session->set_flashdata('feedback', lang('appointment_booked'));
            } else { // Updating department

                $previous_status = $this->appointment_model->getAppointmentById($id)->status;

                if ($previous_status != "Confirmed") {

                    if ($status == "Confirmed") {

                        $this->sendSmsDuringAppointment($id, $data, $patient, $doctor, $status);
                    }
                }

                $this->appointment_model->updateAppointment($id, $data);



                $this->session->set_flashdata('feedback', lang('updated'));
            }

            // Loading View



            if (!empty($redirect)) {

                redirect($redirect);
            } else {

                redirect('appointment');
            }
        }
    }



    function sendSmsDuringAppointment($id, $data, $patient, $doctor, $status)

    {

        //sms

        $set['settings'] = $this->settings_model->getSettings();

        $patientdetails = $this->patient_model->getPatientById($patient);

        $doctordetails = $this->doctor_model->getDoctorById($doctor);

        if (empty($id)) {

            if ($status != 'Confirmed') {

                $autosms = $this->sms_model->getAutoSmsByType('appoinment_creation');

                $autoemail = $this->email_model->getAutoEmailByType('appoinment_creation');
            } else {

                $autosms = $this->sms_model->getAutoSmsByType('appoinment_confirmation');

                $autoemail = $this->email_model->getAutoEmailByType('appoinment_confirmation');
            }
        } else {



            $autosms = $this->sms_model->getAutoSmsByType('appoinment_confirmation');

            $autoemail = $this->email_model->getAutoEmailByType('appoinment_confirmation');
        }

        $message = $autosms->message;

        $to = $patientdetails->phone;

        $name1 = explode(' ', $patientdetails->name);

        if (!isset($name1[1])) {

            $name1[1] = null;
        }

        $data1 = array(

            'firstname' => $name1[0],

            'lastname' => $name1[1],

            'name' => $patientdetails->name,

            'doctorname' => $doctordetails->name,

            'appoinmentdate' => date('d-m-Y', $data['date']),

            'time_slot' => $data['time_slot'],

            'hospital_name' => $set['settings']->system_vendor

        );



        if ($autosms->status == 'Active') {

            $messageprint = $this->parser->parse_string($message, $data1);



            $data2[] = array($to => $messageprint);

            $this->sms->sendSms($to, $message, $data2);
        }



        if ($autoemail->status == 'Active') {

            $mail_provider = $this->settings_model->getSettings()->emailtype;

            $settngs_name = $this->settings_model->getSettings()->system_vendor;

            $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);

            $message1 = $autoemail->message;

            $messageprint1 = $this->parser->parse_string($message1, $data1);

            if ($mail_provider == 'Domain Email') {

                $this->email->from($email_Settings->admin_email);
            }

            if ($mail_provider == 'Smtp') {

                $this->email->from($email_Settings->user, $settngs_name);
            }

            $this->email->to($patientdetails->email);

            $this->email->subject(lang('appointment'));

            $this->email->message($messageprint1);

            $this->email->send();
        }
    }



    function getArrayKey($s_time)

    {

        $all_slot = array(

            0 => '12:00 AM',

            1 => '12:05 AM',

            2 => '12:10 AM',

            3 => '12:15 AM',

            4 => '12:20 AM',

            5 => '12:25 AM',

            6 => '12:30 AM',

            7 => '12:35 AM',

            8 => '12:40 PM',

            9 => '12:45 AM',

            10 => '12:50 AM',

            11 => '12:55 AM',

            12 => '01:00 AM',

            13 => '01:05 AM',

            14 => '01:10 AM',

            15 => '01:15 AM',

            16 => '01:20 AM',

            17 => '01:25 AM',

            18 => '01:30 AM',

            19 => '01:35 AM',

            20 => '01:40 AM',

            21 => '01:45 AM',

            22 => '01:50 AM',

            23 => '01:55 AM',

            24 => '02:00 AM',

            25 => '02:05 AM',

            26 => '02:10 AM',

            27 => '02:15 AM',

            28 => '02:20 AM',

            29 => '02:25 AM',

            30 => '02:30 AM',

            31 => '02:35 AM',

            32 => '02:40 AM',

            33 => '02:45 AM',

            34 => '02:50 AM',

            35 => '02:55 AM',

            36 => '03:00 AM',

            37 => '03:05 AM',

            38 => '03:10 AM',

            39 => '03:15 AM',

            40 => '03:20 AM',

            41 => '03:25 AM',

            42 => '03:30 AM',

            43 => '03:35 AM',

            44 => '03:40 AM',

            45 => '03:45 AM',

            46 => '03:50 AM',

            47 => '03:55 AM',

            48 => '04:00 AM',

            49 => '04:05 AM',

            50 => '04:10 AM',

            51 => '04:15 AM',

            52 => '04:20 AM',

            53 => '04:25 AM',

            54 => '04:30 AM',

            55 => '04:35 AM',

            56 => '04:40 AM',

            57 => '04:45 AM',

            58 => '04:50 AM',

            59 => '04:55 AM',

            60 => '05:00 AM',

            61 => '05:05 AM',

            62 => '05:10 AM',

            63 => '05:15 AM',

            64 => '05:20 AM',

            65 => '05:25 AM',

            66 => '05:30 AM',

            67 => '05:35 AM',

            68 => '05:40 AM',

            69 => '05:45 AM',

            70 => '05:50 AM',

            71 => '05:55 AM',

            72 => '06:00 AM',

            73 => '06:05 AM',

            74 => '06:10 AM',

            75 => '06:15 AM',

            76 => '06:20 AM',

            77 => '06:25 AM',

            78 => '06:30 AM',

            79 => '06:35 AM',

            80 => '06:40 AM',

            81 => '06:45 AM',

            82 => '06:50 AM',

            83 => '06:55 AM',

            84 => '07:00 AM',

            85 => '07:05 AM',

            86 => '07:10 AM',

            87 => '07:15 AM',

            88 => '07:20 AM',

            89 => '07:25 AM',

            90 => '07:30 AM',

            91 => '07:35 AM',

            92 => '07:40 AM',

            93 => '07:45 AM',

            94 => '07:50 AM',

            95 => '07:55 AM',

            96 => '08:00 AM',

            97 => '08:05 AM',

            98 => '08:10 AM',

            99 => '08:15 AM',

            100 => '08:20 AM',

            101 => '08:25 AM',

            102 => '08:30 AM',

            103 => '08:35 AM',

            104 => '08:40 AM',

            105 => '08:45 AM',

            106 => '08:50 AM',

            107 => '08:55 AM',

            108 => '09:00 AM',

            109 => '09:05 AM',

            110 => '09:10 AM',

            111 => '09:15 AM',

            112 => '09:20 AM',

            113 => '09:25 AM',

            114 => '09:30 AM',

            115 => '09:35 AM',

            116 => '09:40 AM',

            117 => '09:45 AM',

            118 => '09:50 AM',

            119 => '09:55 AM',

            120 => '10:00 AM',

            121 => '10:05 AM',

            122 => '10:10 AM',

            123 => '10:15 AM',

            124 => '10:20 AM',

            125 => '10:25 AM',

            126 => '10:30 AM',

            127 => '10:35 AM',

            128 => '10:40 AM',

            129 => '10:45 AM',

            130 => '10:50 AM',

            131 => '10:55 AM',

            132 => '11:00 AM',

            133 => '11:05 AM',

            134 => '11:10 AM',

            135 => '11:15 AM',

            136 => '11:20 AM',

            137 => '11:25 AM',

            138 => '11:30 AM',

            139 => '11:35 AM',

            140 => '11:40 AM',

            141 => '11:45 AM',

            142 => '11:50 AM',

            143 => '11:55 AM',

            144 => '12:00 PM',

            145 => '12:05 PM',

            146 => '12:10 PM',

            147 => '12:15 PM',

            148 => '12:20 PM',

            149 => '12:25 PM',

            150 => '12:30 PM',

            151 => '12:35 PM',

            152 => '12:40 PM',

            153 => '12:45 PM',

            154 => '12:50 PM',

            155 => '12:55 PM',

            156 => '01:00 PM',

            157 => '01:05 PM',

            158 => '01:10 PM',

            159 => '01:15 PM',

            160 => '01:20 PM',

            161 => '01:25 PM',

            162 => '01:30 PM',

            163 => '01:35 PM',

            164 => '01:40 PM',

            165 => '01:45 PM',

            166 => '01:50 PM',

            167 => '01:55 PM',

            168 => '02:00 PM',

            169 => '02:05 PM',

            170 => '02:10 PM',

            171 => '02:15 PM',

            172 => '02:20 PM',

            173 => '02:25 PM',

            174 => '02:30 PM',

            175 => '02:35 PM',

            176 => '02:40 PM',

            177 => '02:45 PM',

            178 => '02:50 PM',

            179 => '02:55 PM',

            180 => '03:00 PM',

            181 => '03:05 PM',

            182 => '03:10 PM',

            183 => '03:15 PM',

            184 => '03:20 PM',

            185 => '03:25 PM',

            186 => '03:30 PM',

            187 => '03:35 PM',

            188 => '03:40 PM',

            189 => '03:45 PM',

            190 => '03:50 PM',

            191 => '03:55 PM',

            192 => '04:00 PM',

            193 => '04:05 PM',

            194 => '04:10 PM',

            195 => '04:15 PM',

            196 => '04:20 PM',

            197 => '04:25 PM',

            198 => '04:30 PM',

            199 => '04:35 PM',

            200 => '04:40 PM',

            201 => '04:45 PM',

            202 => '04:50 PM',

            203 => '04:55 PM',

            204 => '05:00 PM',

            205 => '05:05 PM',

            206 => '05:10 PM',

            207 => '05:15 PM',

            208 => '05:20 PM',

            209 => '05:25 PM',

            210 => '05:30 PM',

            211 => '05:35 PM',

            212 => '05:40 PM',

            213 => '05:45 PM',

            214 => '05:50 PM',

            215 => '05:55 PM',

            216 => '06:00 PM',

            217 => '06:05 PM',

            218 => '06:10 PM',

            219 => '06:15 PM',

            220 => '06:20 PM',

            221 => '06:25 PM',

            222 => '06:30 PM',

            223 => '06:35 PM',

            224 => '06:40 PM',

            225 => '06:45 PM',

            226 => '06:50 PM',

            227 => '06:55 PM',

            228 => '07:00 PM',

            229 => '07:05 PM',

            230 => '07:10 PM',

            231 => '07:15 PM',

            232 => '07:20 PM',

            233 => '07:25 PM',

            234 => '07:30 PM',

            235 => '07:35 PM',

            236 => '07:40 PM',

            237 => '07:45 PM',

            238 => '07:50 PM',

            239 => '07:55 PM',

            240 => '08:00 PM',

            241 => '08:05 PM',

            242 => '08:10 PM',

            243 => '08:15 PM',

            244 => '08:20 PM',

            245 => '08:25 PM',

            246 => '08:30 PM',

            247 => '08:35 PM',

            248 => '08:40 PM',

            249 => '08:45 PM',

            250 => '08:50 PM',

            251 => '08:55 PM',

            252 => '09:00 PM',

            253 => '09:05 PM',

            254 => '09:10 PM',

            255 => '09:15 PM',

            256 => '09:20 PM',

            257 => '09:25 PM',

            258 => '09:30 PM',

            259 => '09:35 PM',

            260 => '09:40 PM',

            261 => '09:45 PM',

            262 => '09:50 PM',

            263 => '09:55 PM',

            264 => '10:00 PM',

            265 => '10:05 PM',

            266 => '10:10 PM',

            267 => '10:15 PM',

            268 => '10:20 PM',

            269 => '10:25 PM',

            270 => '10:30 PM',

            271 => '10:35 PM',

            272 => '10:40 PM',

            273 => '10:45 PM',

            274 => '10:50 PM',

            275 => '10:55 PM',

            276 => '11:00 PM',

            277 => '11:05 PM',

            278 => '11:10 PM',

            279 => '11:15 PM',

            280 => '11:20 PM',

            281 => '11:25 PM',

            282 => '11:30 PM',

            283 => '11:35 PM',

            284 => '11:40 PM',

            285 => '11:45 PM',

            286 => '11:50 PM',

            287 => '11:55 PM',

        );



        $key = array_search($s_time, $all_slot);

        return $key;
    }



    function getAppointmentByJasonByDoctor()

    {





        $id = $this->input->get('id');



        $query = $this->appointment_model->getAppointmentByDoctor($id);



        $jsonevents = array();



        foreach ($query as $entry) {



            $doctor = $this->doctor_model->getDoctorById($entry->doctor);

            if (!empty($doctor)) {

                $doctor = $doctor->name;
            } else {

                $doctor = '';
            }

            $time_slot = $entry->time_slot;

            $time_slot_new = explode(' To ', $time_slot);

            $start_time = explode(' ', $time_slot_new[0]);

            $end_time = explode(' ', $time_slot_new[1]);



            if ($start_time[1] == 'AM') {

                $start_time_second = explode(':', $start_time[0]);

                if ($start_time_second[0] == 12) {

                    $day_start_time_second = $start_time_second[1] * 60;
                } else {

                    $day_start_time_second = $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            } else {

                $start_time_second = explode(':', $start_time[0]);

                if ($start_time_second[0] == 12) {

                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[1] * 60;
                } else {

                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            }



            if ($end_time[1] == 'AM') {

                $end_time_second = explode(':', $end_time[0]);

                if ($end_time_second[0] == 12) {

                    $day_end_time_second = $end_time_second[1] * 60;
                } else {

                    $day_end_time_second = $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            } else {

                $end_time_second = explode(':', $end_time[0]);

                if ($end_time_second[0] == 12) {

                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[1] * 60;
                } else {

                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            }



            $patient_details = $this->patient_model->getPatientById($entry->patient);



            if (!empty($patient_details)) {

                $patient_mobile = $patient_details->phone;

                $patient_name = $patient_details->name;
            } else {

                $patient_mobile = '';

                $patient_name = '';
            }



            if ($entry->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($entry->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($entry->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($entry->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($entry->status == 'Requested') {

                $appointment_status = lang('requested');
            }



            $info = '<br/>' . lang('status') . ': ' . $appointment_status . '<br>' . lang('patient') . ': ' . $patient_name . '<br/>' . lang('phone') . ': ' . $patient_mobile . '<br/> Doctor: ' . $doctor . '<br/>' . lang('remarks') . ': ' . $entry->remarks;

            if ($entry->status == 'Pending Confirmation') {

                //  $color = '#098098';

                $color = 'yellowgreen';
            }

            if ($entry->status == 'Confirmed') {

                $color = '#009988';
            }

            if ($entry->status == 'Treated') {

                $color = '#112233';
            }

            if ($entry->status == 'Cancelled') {

                $color = 'red';
            }

            if ($entry->status == 'Requested') {

                $color = '#6883a3';
            }



            $jsonevents[] = array(

                'id' => $entry->id,

                'title' => $info,

                'start' => date('Y-m-d H:i:s', $entry->date + $day_start_time_second),

                'end' => date('Y-m-d H:i:s', $entry->date + $day_end_time_second),

                'color' => $color,

            );
        }



        echo json_encode($jsonevents);
    }



    function getAppointmentByJason()

    {







        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            $query = $this->appointment_model->getAppointmentByDoctor($doctor);
        } elseif ($this->ion_auth->in_group(array('Patient'))) {

            $patient_ion_id = $this->ion_auth->get_user_id();

            $patient = $this->db->get_where('patient', array('ion_user_id' => $patient_ion_id))->row()->id;

            $query = $this->appointment_model->getAppointmentByPatient($patient);
        } else {

            $query = $this->appointment_model->getAppointmentForCalendar();
        }

        $jsonevents = array();



        foreach ($query as $entry) {



            $doctor = $this->doctor_model->getDoctorById($entry->doctor);

            if (!empty($doctor)) {

                $doctor = $doctor->name;
            } else {

                $doctor = '';
            }

            $time_slot = $entry->time_slot;

            $time_slot_new = explode(' To ', $time_slot);

            $start_time = explode(' ', $time_slot_new[0]);

            $end_time = explode(' ', $time_slot_new[1]);



            if ($start_time[1] == 'AM') {

                $start_time_second = explode(':', $start_time[0]);

                if ($start_time_second[0] == 12) {

                    $day_start_time_second = $start_time_second[1] * 60;
                } else {

                    $day_start_time_second = $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            } else {

                $start_time_second = explode(':', $start_time[0]);

                if ($start_time_second[0] == 12) {

                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[1] * 60;
                } else {

                    $day_start_time_second = 12 * 60 * 60 + $start_time_second[0] * 60 * 60 + $start_time_second[1] * 60;
                }
            }



            if ($end_time[1] == 'AM') {

                $end_time_second = explode(':', $end_time[0]);

                if ($end_time_second[0] == 12) {

                    $day_end_time_second = $end_time_second[1] * 60;
                } else {

                    $day_end_time_second = $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            } else {

                $end_time_second = explode(':', $end_time[0]);

                if ($end_time_second[0] == 12) {

                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[1] * 60;
                } else {

                    $day_end_time_second = 12 * 60 * 60 + $end_time_second[0] * 60 * 60 + $end_time_second[1] * 60;
                }
            }



            $patient_details = $this->patient_model->getPatientById($entry->patient);



            if (!empty($patient_details)) {

                $patient_mobile = $patient_details->phone;

                $patient_name = $patient_details->name;
            } else {

                $patient_mobile = '';

                $patient_name = '';
            }



            if ($entry->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($entry->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($entry->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($entry->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($entry->status == 'Requested') {

                $appointment_status = lang('requested');
            }



            $info = '<br/>' . lang('status') . ': ' . $appointment_status . '<br>' . lang('patient') . ': ' . $patient_name . '<br/>' . lang('phone') . ': ' . $patient_mobile . '<br/> Doctor: ' . $doctor . '<br/>' . lang('remarks') . ': ' . $entry->remarks;

            if ($entry->status == 'Pending Confirmation') {



                $color = 'yellowgreen';
            }

            if ($entry->status == 'Confirmed') {

                $color = '#009988';
            }

            if ($entry->status == 'Treated') {

                $color = '#112233';
            }

            if ($entry->status == 'Cancelled') {

                $color = 'red';
            }

            if ($entry->status == 'Requested') {

                $color = '#6883a3';
            }



            $jsonevents[] = array(

                'id' => $entry->id,

                'title' => $info,

                'description' => 'Click to see the patient history',

                'start' => date('Y-m-d H:i:s', $entry->date + $day_start_time_second),

                'end' => date('Y-m-d H:i:s', $entry->date + $day_end_time_second),

                'color' => $color,

            );
        }



        echo json_encode($jsonevents);
    }



    function getAppointmentByDoctorId()

    {

        $id = $this->input->get('id');

        $data['doctor_id'] = $id;

        $data['appointments'] = $this->appointment_model->getAppointment();

        $data['patients'] = $this->patient_model->getPatient();

        $data['mmrdoctor'] = $this->doctor_model->getDoctorById($id);

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('appointment_by_doctor', $data);

        $this->load->view('home/footer'); // just the header file

    }



    function editAppointment()

    {

        $data = array();

        $id = $this->input->get('id');

        $appointmentData = $this->appointment_model->getAppointmentById($id);

        if (!$id || !$appointmentData) {

            $this->session->set_flashdata('feedback', 'Invalid Appoinment ID');

            redirect('appointment');
        }



        $data['appointment'] = $appointmentData;

        $data['patients'] = $this->patient_model->getPatientById($data['appointment']->patient);

        $data['doctors'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);

        $data['settings'] = $this->settings_model->getSettings();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] = $this->doctor_model->getType();

        $data['status'] = $this->doctor_model->getAllStatus();



        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('add_new', $data);

        $this->load->view('home/footer'); // just the footer file 

    }



    function editAppointmentByJason()

    {

        $id = $this->input->get('id');

        $data['appointment'] = $this->appointment_model->getAppointmentById($id);

        $data['patient'] = $this->patient_model->getPatientById($data['appointment']->patient);

        $data['doctor'] = $this->doctor_model->getDoctorById($data['appointment']->doctor);

        echo json_encode($data);
    }



    function treatmentReport()

    {

        $data['settings'] = $this->settings_model->getSettings();

        $data['doctors'] = $this->doctor_model->getDoctor();



        $date_from = strtotime($this->input->post('date_from'));

        $date_to = strtotime($this->input->post('date_to'));

        if (!empty($date_to)) {

            $date_to = $date_to + 24 * 60 * 60;
        }



        if (empty($date_from) || empty($date_to)) {

            $data['appointments'] = $this->appointment_model->getAppointment();
        } else {

            $data['appointments'] = $this->appointment_model->getAppointmentByDate($date_from, $date_to);

            $data['from'] = $this->input->post('date_from');

            $data['to'] = $this->input->post('date_to');
        }



        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('treatment_history', $data);

        $this->load->view('home/footer'); // just the footer file

    }



    function myAppointments()

    {

        $data['appointments'] = $this->appointment_model->getAppointment();

        $data['settings'] = $this->settings_model->getSettings();

        $user_id = $this->ion_auth->user()->row()->id;

        $data['user_id'] = $this->db->get_where('patient', array('ion_user_id' => $user_id))->row()->id;

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('myappointments', $data);

        $this->load->view('home/footer'); // just the header file

    }



    function delete()

    {

        $data = array();

        $id = $this->input->get('id');

        $doctor_id = $this->input->get('doctor_id');

        $this->appointment_model->delete($id);

        $this->session->set_flashdata('feedback', lang('deleted'));

        if (!empty($doctor_id)) {

            redirect('appointment/getAppointmentByDoctorId?id=' . $doctor_id);
        } else {

            redirect('appointment');
        }
    }



    function getAppointment()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($limit == -1) {

            if (!empty($search)) {

                $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search, $order, $dir);
            } else {

                $data['appointments'] = $this->appointment_model->getAppointmentWithoutSearch($order, $dir);
            }
        } else {

            if (!empty($search)) {

                $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search);
            } else {

                $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start);
            }
        }





        foreach ($data['appointments'] as $appointment) {



            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                //   $options1 = '<a type="button" class="btn editbutton" title="Edit" data-toggle="modal" data-id="463"><i class="fa fa-edit"> </i> Edit</a>';

                $options1 = ' <a type="button" class="btn editbutton" title="' . lang('edit') . '" data-toggle = "modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';
            }



            $options2 = '<a class="btn detailsbutton" title="' . lang('info') . '" style="color: #fff;" href="appointment/appointmentDetails?id=' . $appointment->id . '"><i class="fa fa-info"></i> ' . lang('info') . '</a>';



            $options3 = '<a class="btn green" title="' . lang('history') . '" style="color: #fff;" href="appointment/medicalHistory?id=' . $appointment->id . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';



            $options4 = '<a class="btn invoicebutton" title="' . lang('payment') . '" style="color: #fff;" href="finance/appointmentPaymentHistory?appointment=' . $appointment->id . '"><i class="fa fa-money"></i> ' . lang('payment') . '</a>';



            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                $options5 = '<a class="btn delete_button" title="' . lang('delete') . '" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i> ' . lang('delete') . '</a>';
            }





            $info[] = array(

                $appointment->id,

                $appointment->name,

                $appointment->phone,

                $this->settings_model->getSettings()->currency . $this->appointment_model->getDueBalanceByAppointmentId($appointment->id),

                $options1 . ' ' . $options2 . ' ' . $options3 . ' ' . $options4 . ' ' . $options5,

            );
        }



        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $this->db->get('appointment')->num_rows(),

                "recordsFiltered" => $this->db->get('appointment')->num_rows(),

                "data" => $info

            );
        } else {

            $output = array(

                // "draw" => 1,

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    public function downloadInvoiceGenerate()

    {





        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }





        $apt_id = htmlentities($_GET['appointment']);



        $data = array();

        $data['settings'] = $this->settings_model->getSettings();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] = $this->doctor_model->getType();

        $data['status'] = $this->doctor_model->getAllStatus();



        $data['aptData'] = $this->appointment_model->getAppointmentById($apt_id);

        $aptData = $this->appointment_model->getAppointmentById($apt_id);

        $data['patientData'] = $this->patient_model->getPatientById($aptData->patient);

        $patientData = $this->patient_model->getPatientById($aptData->patient);

        $data['doctorData'] = $this->doctor_model->getDoctorById($aptData->doctor);

        $doctorData = $this->doctor_model->getDoctorById($aptData->doctor);

        $data['statusData'] = $this->doctor_model->getStatusById($aptData->status);

        $statusData = $this->doctor_model->getStatusById($aptData->status);

        $data['consultationModeData'] = $this->doctor_model->getStatusById($aptData->status);

        $consultationModeData = $this->doctor_model->getStatusById($aptData->status);



        $invNumber = mt_rand(0, 999999);



        $data['invData'] = [

            'appName' => 'DWMS',

            'appLogo' => '',

            // 'appLogo' => base_url() . $this->db->get('settings')->row()->logo,

            'appAddress' => 'A1 Peter’s Hall, East Bank Demerara,Guyana',

            'appNumber' => '+592-620-999',

            'appWeb' => 'www.gogole.com',

            'appMail' => 'info@awclinicguyana.com',

            'invDate' => date('d-m-Y'),

            'invNumber' => $aptData->id . '-' . $invNumber,

            'patient_id' => '#DWMS-' . $aptData->patient,

            'doctor_id' => '#DWMS-' . $aptData->doctor,

            'patient' => $aptData->patient,

            'doctor' => $aptData->doctor,

            'apt_id' => $aptData->id,

            'apt_date' => $aptData->add_date,

            'patient_name' => $patientData->name,

            'doctor_name' => $doctorData->name,

            'apt_status' => $statusData->status_name,

            'apt_mode_of_consultation' => ($aptData->mode_of_consultation == '1') ? 'Online' : 'In Clinic',

            'apt_type_of_consultation' => '',

            'apt_time_slot' => date('d-m-Y', $aptData->date) . ' - ' . $aptData->time_slot,

            'apt_amount' => $aptData->amount,

            'apt_payment_mode' => $aptData->payment_mode ?? '',

            'generate_invoice' => $aptData->generate_invoice ?? 0,

            'prescription_generate' => $aptData->prescription_generate ?? 0,

        ];

        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('appointment_invoice', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function UpdatePaymentByAppointmentID()

    {

        if ($this->input->post("appointment_id") > 0) {

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

            $this->form_validation->set_rules('amount', 'Amount', 'required|min_length[1]|max_length[100]|xss_clean');
            $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required|xss_clean');
            $this->form_validation->set_rules('generate_invoice', 'Generate Invoice', 'required|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                redirect($_SERVER['HTTP_REFERER']);
            }
            $apid = $this->input->post("appointment_id");

            $patient_id = $this->input->post("patient_id");

            $doctor_id = $this->input->post("doctor_id");

            $updateData = [

                'amount' => $this->input->post("amount"),

                'payment_mode' => $this->input->post("payment_mode"),

                'generate_invoice' => $this->input->post("generate_invoice"),

            ];



            $this->appointment_model->updateAppointmentByIdOrDoctorId($apid, $patient_id, $doctor_id, $updateData);

            $this->session->set_flashdata('feedback', lang('updated'));

            redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->session->set_flashdata('feedback', lang('updated'));

            redirect($_SERVER['HTTP_REFERER']);
        }
    }



    public function downloadInvoice()

    {

        if (count($_GET) > 0) {

            $apt_id = htmlentities($_GET['appointment']);



            $aptData = $this->appointment_model->getAppointmentById($apt_id);

            $patientData = $this->patient_model->getPatientById($aptData->patient);

            $doctorData = $this->doctor_model->getDoctorById($aptData->doctor);

            $statusData = $this->doctor_model->getStatusById($aptData->status);

            $consultationModeData = $this->doctor_model->getConsultationModeById($aptData->mode_of_consultation);

            $consultationTypeData = $this->doctor_model->getTypeById($aptData->type_of_consultation);

            $invNumber = mt_rand(0, 999999);

            $invData = [

                'appName' => 'DWMS',

                'appLogo' => '',

                // 'appLogo' => base_url() . $this->db->get('settings')->row()->logo,

                'appAddress' => 'A1 Peter’s Hall, East Bank Demerara,Guyana',

                'appNumber' => '+592-620-999',

                'appWeb' => 'www.gogole.com',

                'appMail' => 'info@awclinicguyana.com',

                'invDate' => date('d-m-Y'),

                // 'invNumber' => $aptData->id . '-' . $invNumber,

                'invNumber' => $aptData->id,

                'patient_id' => '#DWMS-' . $aptData->patient,

                'apt_id' => $aptData->id,

                'apt_date' => $aptData->add_date,

                'patient_name' => $patientData->name,

                'doctor_name' => $doctorData->name,

                'apt_status' => $statusData->status_name,

                'apt_mode_of_consultation' => $consultationModeData->mode_of_consultation,

                'apt_type_of_consultation' => $consultationTypeData->name,

                'apt_time_slot' => date('d-m-Y', $aptData->date) . ' - ' . $aptData->time_slot,

                'apt_amount' => $aptData->amount,

            ];



            $invoiceName = "Invoice-" . $aptData->id . '.pdf';



            $this->load->library('pdf');

            $html = $this->load->view('appointment/invoice', $invData, true);

            // $this->load->view('appointment/invoice', $invData);



            // Create PDF from the HTML

            $this->pdf->createPDF($html, $invoiceName);
        } else {

            redirect('appointment');
        }
    }



    public function patientPrescription()
    {

        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');
        }





        $apt_id = htmlentities($_GET['apid']);



        $data = array();

        $data['settings'] = $this->settings_model->getSettings();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] = $this->doctor_model->getType();

        $data['status'] = $this->doctor_model->getAllStatus();



        $data['aptData'] = $this->appointment_model->getAppointmentById($apt_id);

        $aptData = $this->appointment_model->getAppointmentById($apt_id);

        $data['patientData'] = $this->patient_model->getPatientById($aptData->patient);

        $patientData = $this->patient_model->getPatientById($aptData->patient);

        $data['doctorData'] = $this->doctor_model->getDoctorById($aptData->doctor);

        $doctorData = $this->doctor_model->getDoctorById($aptData->doctor);

        $data['statusData'] = $this->doctor_model->getStatusById($aptData->status);

        $statusData = $this->doctor_model->getStatusById($aptData->status);

        $data['consultationModeData'] = $this->doctor_model->getStatusById($aptData->status);

        $consultationModeData = $this->doctor_model->getStatusById($aptData->status);


        // $data['diagnos']    = $this->diagnos_model->getActiveDiagnos() ?? [];
        $data['diagnos']    = $this->diagnos_model->getActiveDiagnosOrderByName() ?? [];



        $invNumber = mt_rand(0, 999999);



        $data['invData'] = [

            'appName' => 'DWMS',

            'appLogo' => '',

            // 'appLogo' => base_url() . $this->db->get('settings')->row()->logo,

            'appAddress' => 'A1 Peter’s Hall, East Bank Demerara,Guyana',

            'appNumber' => '+592-620-999',

            'appWeb' => 'www.gogole.com',

            'appMail' => 'info@awclinicguyana.com',

            'invDate' => date('d-m-Y'),

            'invNumber' => $aptData->id . '-' . $invNumber,

            'patient_id' => '#DWMS-' . $aptData->patient,

            'doctor_id' => '#DWMS-' . $aptData->doctor,

            'patient' => $aptData->patient,

            'doctor' => $aptData->doctor,

            'apt_id' => $aptData->id,

            'apt_date' => $aptData->add_date,

            'patient_name' => $patientData->name,

            'doctor_name' => $doctorData->name,

            'apt_status' => $statusData->status_name,

            'apt_mode_of_consultation' => ($aptData->mode_of_consultation == '1') ? 'Online' : 'In Clinic',

            'apt_type_of_consultation' => '',

            'apt_time_slot' => date('d-m-Y', $aptData->date) . ' - ' . $aptData->time_slot,

            'apt_amount' => $aptData->amount,

            'apt_payment_mode' => $aptData->payment_mode ?? '',

            'generate_invoice' => $aptData->generate_invoice ?? 0,

            'prescription_generate' => $aptData->prescription_generate ?? 0,
            'diagnos_ids' => $aptData->diagnos_ids ?? 0,

        ];


        $this->load->view('home/dashboard', $data); // just the header file

        $this->load->view('patient_prescription', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function patientPrescriptionStore()
    {

        if ($this->input->post("appointment_id") > 0) {



            $apid = $this->input->post("appointment_id");

            $patient_id = $this->input->post("patient_id");

            $doctor_id = $this->input->post("doctor_id");



            $aptData = $this->appointment_model->getAppointmentById($apid);

            $patientData = $this->patient_model->getPatientById($aptData->patient);

            $doctorData = $this->doctor_model->getDoctorById($aptData->doctor);

            $statusData = $this->doctor_model->getStatusById($aptData->status);

            $consultationModeData = $this->doctor_model->getStatusById($aptData->status);





            $updateData = [

                'diagnosis' => $this->input->post("diagnosis"),
                'prescription' => $this->input->post("prescription"),
                'diagnos_ids' => implode(',', $this->input->post("diagnos_ids")),
                'prescription_generate' => 1,

            ];




            $this->appointment_model->updateAppointmentByIdOrDoctorId($apid, $patient_id, $doctor_id, $updateData);

            $this->session->set_flashdata('feedback', lang('updated'));

            redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->session->set_flashdata('feedback', lang('updated'));

            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function downloadInvoicePrescription()

    {

        if (count($_GET) > 0) {

            $apt_id = htmlentities($_GET['appointment']);



            $aptData = $this->appointment_model->getAppointmentById($apt_id);

            $patientData = $this->patient_model->getPatientById($aptData->patient);

            $doctorData = $this->doctor_model->getDoctorById($aptData->doctor);

            $statusData = $this->doctor_model->getStatusById($aptData->status);

            $consultationModeData = $this->doctor_model->getStatusById($aptData->status);



            $invNumber = mt_rand(0, 999999);



            $invData = [

                'appName' => 'DWMS',

                'appLogo' => '',

                // 'appLogo' => base_url() . $this->db->get('settings')->row()->logo,

                'appAddress' => 'A1 Peter’s Hall, East Bank Demerara,Guyana',

                'appNumber' => '+592-620-999',

                'appWeb' => 'www.gogole.com',

                'appMail' => 'info@awclinicguyana.com',

                'invDate' => date('d-m-Y'),

                // 'invNumber' => $aptData->id . '-' . $invNumber,

                'invNumber' => $aptData->id,

                'patient_id' => '#DWMS-' . $aptData->patient ?? '',

                'apt_id' => $aptData->id ?? '',

                'apt_date' => $aptData->add_date ?? '',

                'patient_name' => $patientData->name ?? '',

                'doctor_name' => $doctorData->name ?? '',

                'doctor_email' => $doctorData->email ?? '',

                'doctor_address' => $doctorData->address ?? '',

                'doctor_phone' => $doctorData->phone ?? '',

                'doctor_profile' => $doctorData->profile ?? '',

                'apt_status' => $statusData->status_name,

                'apt_mode_of_consultation' => ($aptData->mode_of_consultation == '1') ? 'Online' : 'In Clinic',

                'apt_type_of_consultation' => '',

                'apt_time_slot' => date('d-m-Y', $aptData->date) . ' - ' . $aptData->time_slot,

                'apt_amount' => $aptData->amount ?? '',

                'patient_sex' => $patientData->sex ?? '',

                'patient_age' => $patientData->age ?? '',

                'patient_dob' => $patientData->birthdate ?? '',

                'diagnosis' => $aptData->diagnosis ?? '',
                'diagnos_ids' => $aptData->diagnos_ids ?? '',

                'prescription' => $aptData->prescription ?? '',

            ];
            $diagnos_ids = explode(',', $aptData->diagnos_ids) ?? [];
            $saved_diagnos_ids = [];
            if ($diagnos_ids && count($diagnos_ids) > 0) {
                foreach ($diagnos_ids as $key => $row) {
                    if ($row !== 0) {
                        $saved_diagnos_ids[] = $this->diagnos_model->getDiagnosById($row)->title;
                    }
                }
            }

            $invData['saved_diagnos_ids'] = $saved_diagnos_ids;
            $invoiceName = "Prescription-" . $aptData->id . '.pdf';



            $this->load->library('pdf');

            $html = $this->load->view('appointment/invoice_prescription', $invData, true);

            // $this->load->view('appointment/invoice_prescription', $invData);



            // Create PDF from the HTML

            $this->pdf->createPDF($html, $invoiceName);
        } else {

            redirect('appointment');
        }
    }

    public function getSummaryData($appointment_id = null)
    {
        $data = [];

        if ($appointment_id) {

            $appointmentData = $this->appointment_model->getAppointmentById($appointment_id);

            if (!$appointmentData) {
                return false;
            }

            $patient_id = $appointmentData->patient ?? null;
            $patientData = $this->patient_model->getPatientById($patient_id);

            $doctor_id = $appointmentData->doctor ?? null;
            $doctorData = $this->doctor_model->getDoctorById($doctor_id);

            $pysicalFormData = $this->appointment_model->getAppointmentByIdOrDoctorId($appointment_id, $patient_id);

            if (!$patientData || !$pysicalFormData || $pysicalFormData->exam_complete == '0') {
                return false;
            }

            $diagnosisListIdArr = explode(',', $appointmentData->diagnos_ids);
            $diagnosisListData = [];
            foreach ($diagnosisListIdArr as $dKey => $dVal) {
                $dData = $this->diagnos_model->getDiagnosById($dVal);
                if ($dData) {
                    array_push($diagnosisListData, $dData->title);
                }
            }

            $locationData = $this->doctor_model->getLocationById($appointmentData->location_id);
            $consultationModeData = $this->doctor_model->getConsultationModeById($appointmentData->mode_of_consultation);
            $consultationTypeData = $this->doctor_model->getTypeById($appointmentData->type_of_consultation);
            $statusData = $this->doctor_model->getStatusById($appointmentData->status);
            $companyData = $this->Company_model->getCompanyById($patientData->company_id);
            // $bloodGroupData = $this->doctor_model->getBloodGroupById($appointmentData->bloodgroup);

            $data['appointmentData'] = $appointmentData;
            $data['patientData'] = $patientData;
            $data['doctorData'] = $doctorData;
            $data['pysicalFormData'] = $pysicalFormData;
            $data['locationData'] = $locationData;
            $data['consultationModeData'] = $consultationModeData;
            $data['consultationTypeData'] = $consultationTypeData;
            $data['statusData'] = $statusData;
            $data['companyData'] = $companyData;
            $data['diagnosisListData'] = (count($diagnosisListData) > 0) ? implode(', ', $diagnosisListData) : '-';

            if ($this->ion_auth->in_group(array('Doctor'))) {
                $doctor_ion_id = $this->ion_auth->get_user_id();
                $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row();

                $data['is_doctor'] = true;
                $data['doctor_ion_data'] = $doctor;
            } else {
                $data['is_doctor'] = false;
                $data['doctor_ion_data'] = [];
            }
        } else {
            return false;
        }

        return $data;
    }

    public function summary()
    {

        if (!$this->ion_auth->in_group(array('Doctor'))) {
            $this->session->set_flashdata('feedback', 'Access Denied');
            redirect('appointment');
        }

        $appointment_id = $_GET['apid'] ?? null;
        $data = $this->getSummaryData($appointment_id);

        if ($data === false) {
            $this->session->set_flashdata('feedback', 'Something went wrong!');
            redirect('appointment');
        }

        $this->load->view('home/dashboard');
        $this->load->view('appointment/summary', $data);
        $this->load->view('home/footer');
    }

    function downloadSummary()
    {
        $appointment_id = $_GET['apt'] ?? null;
        $invData = $this->getSummaryData($appointment_id);

        if ($invData === false) {
            $this->session->set_flashdata('feedback', 'Something went wrong!');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $invoiceName = "Invoice-" . $appointment_id . '.pdf';

        $this->load->library('pdf');

        $html = $this->load->view('appointment/summary_invoice', $invData, true);

        $this->pdf->createPDF($html, $invoiceName);
    }

    function shareSummary()
    {
        $share_to = $_GET['type'] ?? null;
        $appointment_id = $_GET['apt'] ?? null;
        $invData = $this->getSummaryData($appointment_id);

        if ($invData === false) {
            $this->session->set_flashdata('feedback', 'Something went wrong!');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $patientData = $invData['patientData'];
        $companyData = $invData['companyData'];
        $apptData = $invData['appointmentData'];
        $diagnosis = $invData['diagnosisListData'];
        $invoiceName = "Invoice-" . $appointment_id . '.pdf';

        if (!$companyData) {
            $this->session->set_flashdata('feedback', 'Company data not found');
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->load->library('pdf');

        $html = $this->load->view('appointment/summary_invoice', $invData, true);

        $savePath = FCPATH . 'invoicefile';

        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }

        $pdfPath = $savePath . '/' . $invoiceName;

        $this->pdf->createPDF($html, $pdfPath, false, 'A4', 'portrait', true);

        $autoemail = $this->email_model->getAutoEmailByType('patient');
        if ($autoemail->status == 'Active') {
            $mail_provider = $this->settings_model->getSettings()->emailtype;
            $settngs_name = $this->settings_model->getSettings()->system_vendor;
            $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);

            $email = $companyData->email; //default
            if ($share_to == 'company') {
                $email = $companyData->email;
            }
            if ($share_to == 'patient') {
                $email = $patientData->email;
            }
            $subject = 'Patient Diagnosis and Treatment Summary for ' . $patientData->name;
            $message = 'Dear ' . $companyData->name . ',<br>We are writing to provide you with the diagnosis and treatment summary for the patient [' . $patientData->name . '] who was referred to our hospital for a checkup. Please find the details below:<br><br><strong>Patient Information:</strong><br><br>Name: ' . $patientData->name . '<br>Patient ID: #' . $patientData->id . '<br>Appointment ID: #' . $apptData->id . '<br>Date of Birth: ' . $patientData->birthdate . '<br>Gender: ' . $patientData->sex . '<br>Date of Visit: ' . date('j M Y', ($apptData->date)) . '<br>Diagnosis: ' . $diagnosis . ' <br><br>Please find the attached document containing the summary of the examination<br>If you have any questions or require further information, please do not hesitate to contact us. <br><br> Thank You, <br>' . $this->settings->title;

            if ($mail_provider == 'Domain Email') {
                $this->email->from($email_Settings->admin_email);
            }
            if ($mail_provider == 'Smtp') {
                $this->email->from($email_Settings->user, $settngs_name);
            }
            $this->sendmail->send($email_Settings->admin_email, $email, $subject, $message, $settngs_name, $email_Settings, $pdfPath);
        }

        $this->session->set_flashdata('feedback', 'Shared successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }

    function getAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];

        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $this->db->get('appointment')->num_rows(),

                "recordsFiltered" => $this->db->get('appointment')->num_rows(),

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    // mohit code 04june2024

    function updateApppinmentPhyscialDetail()
    {

        $physical_examination_date = $_POST['physical_examination_date'];
        $exam_complete = (isset($_POST['exam_complete'])) ? '1' : '0';


        $apid = $_POST['apid'] ?? 0;

        $patient_id = $_POST['patient_id'] ?? 0;

        $doctor_id = $_POST['doctor_id'] ?? 0;



        unset($_POST['submit']);

        unset($_POST['apid']);

        unset($_POST['patient_id']);

        unset($_POST['doctor_id']);

        unset($_POST['submit']);

        unset($_POST['physical_examination_date']);

        unset($_POST['exam_complete']);

        $updateData = $_POST;

        $updateData["physical_examination_date"] = $physical_examination_date;
        $updateData["exam_complete"] = $exam_complete;


        if ($this->appointment_model->getAppointmentById($apid)) {

            $doctor  = $this->ion_auth->get_user_id() ?? 0;

            if ($doctor) {

                $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $doctor))->row()->id;

                $this->appointment_model->updateAppointmentByIdOrDoctorId($apid, $patient_id, $doctor_id, $updateData);

                $this->session->set_flashdata('feedback', lang('updated'));

                redirect($_SERVER['HTTP_REFERER']);
            } else {

                $this->session->set_flashdata('feedback', lang('something_went_wrong'));

                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {

            $this->session->set_flashdata('feedback', lang('something_went_wrong'));

            redirect($_SERVER['HTTP_REFERER']);
        }
    }



    function updateApppinmentVitalDetail()

    {

        $apid = $_POST['apid'] ?? 0;

        $patient_id = $_POST['patient_id'] ?? 0;

        $doctor_id = $_POST['doctor_id'] ?? 0;



        if ($this->appointment_model->getAppointmentById($apid)) {

            $data = array(

                'temp' => htmlentities($this->input->post('temp')),

                'bp' => htmlentities($this->input->post('bp')),

                'pulse' => htmlentities($this->input->post('pulse')),

                'spo2' => htmlentities($this->input->post('spo2')),

                'rr' => htmlentities($this->input->post('rr')),

                'height' => htmlentities($this->input->post('height')),

                'weight' => htmlentities($this->input->post('weight')),

                'bmi' => htmlentities($this->input->post('bmi')),

                'rbs' => htmlentities($this->input->post('rbs')),

                'form_step' => 2,

            );

            $this->appointment_model->updateAppointment($apid, $data);



            $this->session->set_flashdata('feedback', lang('updated'));

            redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->session->set_flashdata('feedback', lang('something_went_wrong'));

            redirect($_SERVER['HTTP_REFERER']);
        }
    }



    // mohit code 04june2024



    function getNoShowAppointmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getNoShowAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    function getPendingAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPendingAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                // "draw" => 1,

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }

        echo json_encode($output);
    }



    function getApprovedAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getApprovedAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    function getRejectedAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];


        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getRejectedAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    function getCompletedAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCompletedAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }

        echo json_encode($output);
    }


    function getPostponeAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];


        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getPostponeAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }

        echo json_encode($output);
    }



    function getConfirmedAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];

        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getConfirmAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }



    function getCancelledAppoinmentList()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];


        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getCancelledAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }

        echo json_encode($output);
    }


    function getTodaysAppoinmentList()
    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }



        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            //$i = $i + 1;



            $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';





            $option222 = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-notes-medical"></i> ' . lang('prescription') . '</a>';
            }



            if ($this->ion_auth->in_group(array('Nurse'))) {



                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }



            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);



            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status,);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }

            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }



            if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }

            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            if (date('Y-m-d', $appointment->date) == date('Y-m-d')) {

                //  if ($appointment->date == strtotime(date('Y-m-d'))) {

                $info[] = array(

                    $appointment->id,

                    $patientname,

                    $doctorname,

                    date('d-m-Y', $appointment->date) . '<br>' . $appointment->s_time . '-' . $appointment->e_time,

                    $consultation_mode_type,

                    $consultation_mode,

                    $location,

                    $status,

                    $option11 . ' ' . $option1 . ' ' . $option2 . ' ' . $options7 . ' ' . $option222

                );

                $i = $i + 1;
            } else {

                $info1[] = array(

                    $appointment->id,

                    $appointment->patientname,

                    $appointment->doctorname,

                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                    $appointment->remarks,

                    $appointment_status,

                    $option11 . ' ' . $option1 . ' ' . $option2 . ' ' . $options7 . ' ' . $option222

                );
            }
        }



        if ($i !== 0) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                // "draw" => 1,

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }


    function getUpcomingAppoinmentList()
    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }



        $i = 0;

        foreach ($data['appointments'] as $appointment) {



            $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';



            if ($this->ion_auth->in_group(array('Nurse'))) {



                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }



            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            if ($appointment->date > strtotime(date('Y-m-d'))) {

                $patientdetails = $this->patient_model->getPatientById($appointment->patient);

                if (!empty($patientdetails)) {

                    $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
                } else {

                    $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
                }

                $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

                if (!empty($doctordetails)) {

                    $doctorname = $doctordetails->name;
                } else {

                    $doctorname = $appointment->doctorname;
                }



                $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);



                if (!empty($getLocation)) {

                    $location = $getLocation->name;
                } else {

                    $location = "";
                }



                $getStatus =  $this->doctor_model->getStatusById($appointment->status,);



                if (!empty($getStatus)) {

                    $status = $getStatus->status_name;
                } else {

                    $status = "";
                }

                $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

                if (!empty($consultationMode)) {

                    $consultation_mode = $consultationMode->mode_of_consultation;
                } else {

                    $consultation_mode = '';
                }



                $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

                if (!empty($consultationModetype)) {

                    $consultation_mode_type = $consultationModetype->name;
                } else {

                    $consultation_mode_type = '';
                }


                if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }

                $info[] = array(

                    $appointment->id,

                    $patientname,

                    $doctorname,

                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                    $consultation_mode_type,

                    $consultation_mode,

                    $location,

                    $status,

                    $option11 . ' ' . $option1 . ' ' . $option2 . ' ' . $options7

                );

                $i = $i + 1;
            } else {

                if ($this->ion_auth->in_group(array('admin', 'Doctor'))) {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }

                $info1[] = array(

                    $appointment->id,

                    $appointment->patientname,

                    $appointment->doctorname,

                    date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                    // $consultation_mode_type,

                    // $consultation_mode,

                    // $location,

                    $appointment->status,

                    $option11 . ' ' . $option1 . ' ' . $option2 . ' ' . $options7

                );
            }
        }



        if ($i !== 0) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                // "draw" => 1,

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }


    function getMyTodaysAppoinmentList()
    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListBySearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentListByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }



        $i = 0;

        foreach ($data['appointments'] as $appointment) {



            $patient_ion_id = $this->ion_auth->get_user_id();

            $patient_details = $this->patient_model->getPatientByIonUserId($patient_ion_id);

            $patient_id = $patient_details->id;

            if ($patient_id == $appointment->patient) {

                $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';



                // $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';





                $option222 = '';

                if ($this->ion_auth->in_group(array('Doctor'))) {

                    $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-notes-medical"></i> ' . lang('prescription') . '</a>';
                }



                if ($this->ion_auth->in_group(array('Nurse'))) {



                    $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
                } else {

                    $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
                }



                $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

                $patientdetails = $this->patient_model->getPatientById($appointment->patient);

                if (!empty($patientdetails)) {

                    $patientname = ' <a type="button" class="" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
                } else {

                    $patientname = ' <a type="button" class="" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
                }

                $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

                if (!empty($doctordetails)) {

                    $doctorname = $doctordetails->name;
                } else {

                    $doctorname = $appointment->doctorname;
                }



                $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);



                if (!empty($getLocation)) {

                    $location = $getLocation->name;
                } else {

                    $location = "";
                }



                $getStatus =  $this->doctor_model->getStatusById($appointment->status,);



                if (!empty($getStatus)) {

                    $status = $getStatus->status_name;
                } else {

                    $status = "";
                }


                $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

                if (!empty($consultationMode)) {

                    $consultation_mode = $consultationMode->mode_of_consultation;
                } else {

                    $consultation_mode = '';
                }



                $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

                if (!empty($consultationModetype)) {

                    $consultation_mode_type = $consultationModetype->name;
                } else {

                    $consultation_mode_type = '';
                }




                if ($this->ion_auth->in_group(array('Patient'))) {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting?\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }

                if ($appointment->status == 'Pending Confirmation') {

                    $appointment_status = lang('pending_confirmation');
                } elseif ($appointment->status == 'Confirmed') {

                    $appointment_status = lang('confirmed');
                } elseif ($appointment->status == 'Treated') {

                    $appointment_status = lang('treated');
                } elseif ($appointment->status == 'Cancelled') {

                    $appointment_status = lang('cancelled');
                } elseif ($appointment->status == 'Requested') {

                    $appointment_status = lang('requested');
                }

                if (date('Y-m-d', $appointment->date) == date('Y-m-d')) {



                    $info[] = array(

                        $appointment->id,

                        $patientname,

                        $doctorname,

                        date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                        $consultation_mode_type,

                        $consultation_mode,

                        $location,

                        $status,

                        $option11 . ' ' . $options7 . ' ' . $option222

                    );

                    $i = $i + 1;
                } else {

                    $info1[] = array(

                        $appointment->id,

                        $appointment->patientname,

                        $appointment->doctorname,

                        date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                        $appointment->remarks,

                        $appointment_status,

                        $option11 . ' ' . $options7 . ' ' . $option222

                    );
                }
            }
        }



        if ($i !== 0) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                // "draw" => 1,

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }


    function getVipAppoinmentList()
    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentBysearchByDoctor($doctor, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentByDoctorWithoutSearch($doctor, $order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir);
                }
            }
        } else {

            if ($limit == -1) {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentBysearch($search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentWithoutSearch($order, $dir);
                }
            } else {

                if (!empty($search)) {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentByLimitBySearch($limit, $start, $search, $order, $dir);
                } else {

                    $data['appointments'] = $this->appointment_model->getVipAppointmentByLimit($limit, $start, $order, $dir);
                }
            }
        }

        $i = 0;

        foreach ($data['appointments'] as $appointment) {

            $i = $i + 1;

            // $option1 = '<button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="' . $appointment->id . '"><i class="fa fa-edit"> ' . lang('edit') . '</i></button>';

            $option1 = '<a class="btn btn-info btn-xs btn_width" title="' . lang('edit') . '" style="color: #fff;" href="appointment/editAppointment?id=' . $appointment->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . '</a>';

            $option222 = $summaryOpt = '';

            if ($this->ion_auth->in_group(array('Doctor'))) {

                $option222 = '<a class="btn btn-primary" title="' . lang('prescription') . '" style="color: #fff;" href="appointment/patientPrescription?apid=' . $appointment->id . '&id=' . $appointment->patient . '"><i class="fa fa-prescription"></i> ' . lang('prescription') . '</a>';

                if ($appointment->exam_complete == '1') {
                    $summaryOpt = '<a class="btn btn-primary" title="' . lang('summary') . '" style="color: #fff;" href="appointment/summary?apid=' . $appointment->id . '"><i class="fa fa-list"></i> ' . lang('summary') . '</a>';
                }
            }

            if ($this->ion_auth->in_group(array('Nurse'))) {

                $option11 = '<a class="btn btn-primary" title="' . lang('vital_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=vital"><i class="fa fa-notes-medical"></i> ' . lang('vital_info') . '</a>';
            } else {

                $option11 = '<a class="btn btn-primary" title="' . lang('medical_info') . '" style="color: #fff;" href="patient/medical?apid=' . $appointment->id . '&id=' . $appointment->patient . '&tab=physical"><i class="fa fa-notes-medical"></i> ' . lang('medical_info') . '</a>';
            }

            $option111 = '<a class="btn btn-primary" title="' . lang('history') . '" style="color: #fff;" href="patient/medicalHistory?id=' . $appointment->patient . '&did=' . $appointment->doctor . '"><i class="fa fa-stethoscope"></i> ' . lang('history') . '</a>';


            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/delete?id=' . $appointment->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i>' . lang('delete') . '</a>';

            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }



            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }





            if ($this->ion_auth->in_group(array('Doctor'))) {

                if ($appointment->status == 'Confirmed') {

                    if ($appointment->status == 'Confirmed') {

                        $options7 = '<a class="btn btn-info btn-xs btn_width detailsbutton" title="' . lang('start_live') . '" style="color: #fff;" href="meeting/instantLive?id=' . $appointment->id . '" target="_blank" onclick="return confirm(\'Are you sure you want to start a live meeting with this patient? SMS and Email will be sent to the Patient.\');"><i class="fa fa-headphones"></i> ' . lang('live') . '</a>';
                    } else {

                        $options7 = '';
                    }
                } else {

                    $options7 = '';
                }
            } else {

                $options7 = '';
            }



            $invOpt = '<a class="btn btn-success" title="' . lang('invoice') . '" style="color: #fff;" href="appointment/downloadInvoiceGenerate?appointment=' . $appointment->id . '"><i class="fa fa-file-pdf"></i> ' . lang('invoice') . '</a>';



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }

            $info[] = array(

                $appointment->id,

                $patientname,

                $doctorname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $status,

                $option11 . ' ' . $option222 . ' ' . $summaryOpt . ' ' . $invOpt . ' ' . $option111 . ' ' . $options7 . ' ' . $option2

            );
        }

        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }





    function viewAppointment()

    {



        $id = $this->input->get('id');



        $data['appointment'] = $this->appointment_model->getAppointmentById($id);

        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $data['status'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] =         $this->doctor_model->getType();



        $data['settings'] = $this->settings_model->getSettings();



        $this->load->view('home/dashboard', $data);



        $this->load->view('appointment_view', $data);



        $this->load->view('home/footer');
    }





    function viewConfirmedAppointment()

    {



        $id = $this->input->get('id');



        $data['appointment'] = $this->appointment_model->getAppointmentById($id);

        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $data['status'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] =         $this->doctor_model->getType();



        $data['settings'] = $this->settings_model->getSettings();



        $this->load->view('home/dashboard', $data);



        $this->load->view('appointment_confirm_view', $data);



        $this->load->view('home/footer');
    }





    function getCancelledAppointments()

    {



        $data['appointment'] = $this->appointment_model->getAllCancelledAppointmentsByHandled();

        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $data['status'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] =         $this->doctor_model->getType();

        $data['settings'] = $this->settings_model->getSettings();



        $this->load->view('home/dashboard', $data);



        $this->load->view('cancelled_view', $data);



        $this->load->view('home/footer');
    }





    function getPostponedAppointments()

    {



        $data['appointment'] = $this->appointment_model->getAllPostponedAppointments();

        $data['patients'] = $this->patient_model->getPatient();

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();

        $data['status'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] =         $this->doctor_model->getType();

        $data['settings'] = $this->settings_model->getSettings();



        $this->load->view('home/dashboard', $data);



        $this->load->view('postponed_view', $data);



        $this->load->view('home/footer');
    }







    function markCancellationRequest()

    {

        // var_dump($_POST);

        $id = $this->input->post('id');

        $aid = $this->input->post('appointment_id');

        $data['reasone'] = $this->input->post('reasone');

        $data['type'] = '8';



        $data['flag'] = '0';

        $current_timestamp = strtotime("now");

        $data['created_at'] = $current_timestamp;

        $status = $this->appointment_model->cancelConfirmApp($id, $data);

        $sdata['status'] = '8';

        $status = $this->appointment_model->cancelConfirmAppByPending($id, $sdata);



        redirect('appointment/getCancelledAppointments');
    }



    function markPostponeRequest()

    {



        var_dump($_POST);

        exit;

        $id = $this->input->post('id');

        $aid = $this->input->post('appointment_id');

        $data['reasone'] = $this->input->post('reasone');

        $data['type'] = '6';



        $data['flag'] = '0';

        $current_timestamp = strtotime("now");

        $data['created_at'] = $current_timestamp;

        $status = $this->appointment_model->postponeConfirmApp($id, $data);

        $sdata['status'] = '6';

        $status = $this->appointment_model->postponeConfirmAppByPending($id, $sdata);



        redirect('appointment/getPostponedAppointments');
    }



    function getAllCancelledAppointmentsByPending()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];









        $data['appointments'] = $this->appointment_model->getAllCancelledAppointmentsByPending();





        $i = 0;

        foreach ($data['appointments'] as $appointment) {



            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/markAsHandled?id=' . $appointment->c_id . '" onclick="return confirm(\'Are you sure you want to mark this item as handled?\');">Handled</a>';



            $option3 = '<a class="btn btn-info btn-xs btn_width" href="appointment/viewAppointment?id=' . $appointment->id . '">View Appointment</a>';



            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);



            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status,);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }

            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }



            $mil = $appointment->created_at * 1000;

            $seconds = $mil / 1000;

            $created_at = date("d/m/Y H:i:s", $seconds);



            $info[] = array(

                $appointment->id,

                $patientname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $appointment->reasone,

                $created_at,

                $option2 . '' . $option3

            );

            $i = $i + 1;
        }



        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }





    function getAllPostponedAppointmentsByPending()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "3" => "app_time",

            "5" => "status",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        $dir = $values[0];

        $order = $values[1];









        $data['appointments'] = $this->appointment_model->getAllPostponedAppointmentsByPending();





        $i = 0;

        foreach ($data['appointments'] as $appointment) {



            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="appointment/markAsHandled?id=' . $appointment->c_id . '" onclick="return confirm(\'Are you sure you want to mark this item as handled?\');">Handled</a>';



            $option3 = '<a class="btn btn-info btn-xs btn_width" href="appointment/viewAppointment?id=' . $appointment->id . '">View Appointment</a>';





            $patientdetails = $this->patient_model->getPatientById($appointment->patient);

            if (!empty($patientdetails)) {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $patientdetails->name . '</a>';
            } else {

                $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $appointment->patient . '"> ' . $appointment->patientname . '</a>';
            }

            $doctordetails = $this->doctor_model->getDoctorById($appointment->doctor);

            if (!empty($doctordetails)) {

                $doctorname = $doctordetails->name;
            } else {

                $doctorname = $appointment->doctorname;
            }



            $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);



            if (!empty($getLocation)) {

                $location = $getLocation->name;
            } else {

                $location = "";
            }



            $getStatus =  $this->doctor_model->getStatusById($appointment->status,);



            if (!empty($getStatus)) {

                $status = $getStatus->status_name;
            } else {

                $status = "";
            }

            $consultationMode = $this->doctor_model->getConsultationModeById($appointment->mode_of_consultation);

            if (!empty($consultationMode)) {

                $consultation_mode = $consultationMode->mode_of_consultation;
            } else {

                $consultation_mode = '';
            }



            $consultationModetype = $this->doctor_model->getTypeById($appointment->type_of_consultation);

            if (!empty($consultationModetype)) {

                $consultation_mode_type = $consultationModetype->name;
            } else {

                $consultation_mode_type = '';
            }



            if ($appointment->status == 'Pending Confirmation') {

                $appointment_status = lang('pending_confirmation');
            } elseif ($appointment->status == 'Confirmed') {

                $appointment_status = lang('confirmed');
            } elseif ($appointment->status == 'Treated') {

                $appointment_status = lang('treated');
            } elseif ($appointment->status == 'Cancelled') {

                $appointment_status = lang('cancelled');
            } elseif ($appointment->status == 'Requested') {

                $appointment_status = lang('requested');
            }



            $mil = $appointment->created_at * 1000;

            $seconds = $mil / 1000;

            $created_at = date("d/m/Y H:i:s", $seconds);



            $info[] = array(

                $appointment->id,

                $patientname,

                date('d-m-Y', $appointment->date) . ' <br> ' . $appointment->s_time . '-' . $appointment->e_time,

                $consultation_mode_type,

                $consultation_mode,

                $location,

                $appointment->reasone,

                $created_at,

                $option2 . '' . $option3

            );

            $i = $i + 1;
        }



        if (!empty($data['appointments'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $i,

                "recordsFiltered" => $i,

                "data" => $info

            );
        } else {

            $output = array(

                "recordsTotal" => 0,

                "recordsFiltered" => 0,

                "data" => []

            );
        }



        echo json_encode($output);
    }









    function markAsHandled()

    {



        $id = $this->input->get('id');

        $data['flag'] = '1';

        $status = $this->appointment_model->PendingHandleApp($id, $data);

        redirect('appointment/getCancelledAppointments');
    }
}



/* End of file appointment.php */

    /* Location: ./application/modules/appointment/controllers/appointment.php */
