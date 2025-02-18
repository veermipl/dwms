<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Doctor extends MX_Controller

{



    function __construct()

    {

        parent::__construct();



        $this->load->model('doctor_model');

        $this->load->model('appointment/appointment_model');

        $this->load->model('patient/patient_model');

        $this->load->model('prescription/prescription_model');

        $this->load->model('schedule/schedule_model');

        $this->load->module('patient');

        $this->load->module('sms');

        if (!$this->ion_auth->in_group(array('admin', 'Accountant', 'Doctor', 'Receptionist', 'Nurse', 'Laboratorist', 'Patient'))) {

            redirect('home/permission');

        }

    }



    public function index()

    {



        $data['doctors'] = $this->doctor_model->getDoctor();



        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('doctor', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function addNewView()

    {

        $data = array();

        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('add_new', $data);

        $this->load->view('home/footer'); // just the header file

    }



    public function addNew()

    {



        $id = $this->input->post('id');

        $name = $this->input->post('name');

        $password = $this->input->post('password');

        $email = $this->input->post('email');

        $address = $this->input->post('address');

        $phone = $this->input->post('phone');

        $profile = $this->input->post('profile');



        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // Validating Name Field
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        // Validating Password Field
        if (empty($id)) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        }
        // Validating Email Field
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[100]|xss_clean');
        // Validating Address Field   
        $this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|min_length[1]|max_length[50]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('profile', 'Profile', 'trim|required|min_length[1]|max_length[50]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            if (!empty($id)) {

                $data = array();
                $data['doctor'] = $this->doctor_model->getDoctorById($id);
                $this->load->view('home/dashboard'); // just the header file
                $this->load->view('add_new', $data);
                $this->load->view('home/footer'); // just the footer file
            } else {

                $data = array();
                $data['setval'] = 'setval';
                $this->load->view('home/dashboard'); // just the header file
                $this->load->view('add_new', $data);
                $this->load->view('home/footer'); // just the header file
            }

        } else {

            $file_name = $_FILES['img_url']['name'];
            $file_name_pieces = explode('_', $file_name);
            $new_file_name = '';

            $count = 1;

            foreach ($file_name_pieces as $piece) {

                if ($count !== 1) {

                    $piece = ucfirst($piece);

                }



                $new_file_name .= $piece;

                $count++;

            }

            $config = array(

                'file_name' => $new_file_name,

                'upload_path' => "./uploads/",

                'allowed_types' => "gif|jpg|png|jpeg|pdf",

                'overwrite' => False,

                // 'max_size' => "20480000", // Can be set to particular file size , here it is 2 MB(2048 Kb)

                // 'max_height' => "1768",

                // 'max_width' => "2024"

            );



            $this->load->library('Upload', $config);

            $this->upload->initialize($config);



            if ($this->upload->do_upload('img_url')) {

                $path = $this->upload->data();

                $img_url = "uploads/" . $path['file_name'];

                $data = array();

                $data = array(

                    'img_url' => $img_url,

                    'name' => $name,

                    'email' => $email,

                    'address' => $address,

                    'phone' => $phone,

                    'profile' => $profile

                );

            } else {



                $data = array();

                $data = array(

                    'name' => $name,

                    'email' => $email,

                    'address' => $address,

                    'phone' => $phone,

                    'profile' => $profile

                );

            }

            $username = $this->input->post('name');

            if (empty($id)) {     // Adding New Doctor

                if ($this->ion_auth->email_check($email)) {

                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));

                    redirect('doctor/addNewView');

                } else {

                    $dfg = 4;

                    $this->ion_auth->register($username, $password, $email, $dfg);

                    $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                    // print_r($data);
                    // die();
                    $this->doctor_model->insertDoctor($data);

                    $doctor_user_id = $this->db->get_where('doctor', array('email' => $email))->row()->id;

                    $id_info = array('ion_user_id' => $ion_user_id);

                    $this->doctor_model->updateDoctor($doctor_user_id, $id_info);



                    //sms

                    $set['settings'] = $this->settings_model->getSettings();

                    $autosms = $this->sms_model->getAutoSmsByType('doctor');

                    $message = $autosms->message;

                    $to = $phone;

                    $name1 = explode(' ', $name);

                    if (!isset($name1[1])) {

                        $name1[1] = null;

                    }

                    $data1 = array(

                        'firstname' => $name1[0],

                        'lastname' => $name1[1],

                        'name' => $name,

                        'company' => $set['settings']->system_vendor

                    );



                    if ($autosms->status == 'Active') {

                        $messageprint = $this->parser->parse_string($message, $data1);

                        $data2[] = array($to => $messageprint);

                        $this->sms->sendSms($to, $message, $data2);

                    }

                    //end

                    //email



                    $autoemail = $this->email_model->getAutoEmailByType('doctor');

                    if ($autoemail->status == 'Active') {

                        $mail_provider = $this->settings_model->getSettings()->emailtype;

                        $settngs_name = $this->settings_model->getSettings()->system_vendor;

                        $email_Settings = $this->email_model->getEmailSettingsByType($mail_provider);



                        $this->load->library('encryption');



                        $message1 = $autoemail->message;

                        $messageprint1 = $this->parser->parse_string($message1, $data1);

                        if ($mail_provider == 'Domain Email') {

                            $this->email->from($email_Settings->admin_email);

                        }

                        if ($mail_provider == 'Smtp') {

                            $this->email->from($email_Settings->user, $settngs_name);

                        }

                        $this->email->to($email);

                        $this->email->subject('Registration confirmation');

                        $this->email->message($messageprint1);

                        $this->email->send();

                    }



                    //end





                    $this->session->set_flashdata('feedback', lang('added'));

                }

            } else { // Updating Doctor

                $ion_user_id = $this->db->get_where('doctor', array('id' => $id))->row()->ion_user_id;

                if (empty($password)) {

                    $password = $this->db->get_where('users', array('id' => $ion_user_id))->row()->password;

                } else {

                    $password = $this->ion_auth_model->hash_password($password);

                }

                $this->doctor_model->updateIonUser($username, $email, $password, $ion_user_id);

                $this->doctor_model->updateDoctor($id, $data);

                $this->session->set_flashdata('feedback', lang('updated'));

            }

            // Loading View

            redirect('doctor');

        }

    }



    function editDoctor()

    {

        $data = array();

        $id = $this->input->get('id');

        $data['doctor'] = $this->doctor_model->getDoctorById($id);

        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('add_new', $data);

        $this->load->view('home/footer'); // just the footer file

    }



    function details()

    {



        $data = array();



        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $id = $this->doctor_model->getDoctorByIonUserId($doctor_ion_id)->id;

        } else {

            redirect('home');

        }





        $data['doctor'] = $this->doctor_model->getDoctorById($id);

        $data['todays_appointments'] = $this->appointment_model->getAppointmentByDoctorByToday($id);

        $data['appointments'] = $this->appointment_model->getAppointmentByDoctor($id);

        $data['patients'] = $this->patient_model->getPatient();

        $data['appointment_patients'] = $this->patient->getPatientByAppointmentByDctorId($id);

        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['prescriptions'] = $this->prescription_model->getPrescriptionByDoctorId($id);

        $data['holidays'] = $this->schedule_model->getHolidaysByDoctor($id);

        $data['schedules'] = $this->schedule_model->getScheduleByDoctor($id, $radio = "");

        $data['settings'] = $this->settings_model->getSettings();

        $data['statuses'] = $this->doctor_model->getAllStatus();

        $data['location'] = $this->doctor_model->getLocation();

        $data['consultation'] = $this->doctor_model->getConsultation_Mode();

        $data['type'] = $this->doctor_model->getType();







        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('details', $data);

        $this->load->view('home/footer'); // just the footer file

    }



    function editDoctorByJason()

    {

        $id = $this->input->get('id');

        $data['doctor'] = $this->doctor_model->getDoctorById($id);

        echo json_encode($data);

    }



    function delete()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');

        }



        $data = array();

        $id = $this->input->get('id');

        $user_data = $this->db->get_where('doctor', array('id' => $id))->row();

        $path = $user_data->img_url;



        if (!empty($path)) {

            unlink($path);

        }

        $ion_user_id = $user_data->ion_user_id;

        $this->db->where('id', $ion_user_id);

        $this->db->delete('users');

        $this->doctor_model->delete($id);

        $this->session->set_flashdata('feedback', lang('deleted'));

        redirect('doctor');

    }



    public function toggleDoctorStatus()

    {

        if (count($_POST) > 0) {

            $doctor_id = htmlentities($_POST['iid']);

            $user_id = htmlentities($_POST['user_ion_id']);

            $status = htmlentities($_POST['status_iid']);



            $statuss = ($status == 1) ? 0 : 1;



            $res = $this->doctor_model->changeUserStatus($user_id, $statuss);



            if ($res === true) {

                $data['error'] = false;

                $data['msg'] = 'Status updated';

            } else {

                $data['error'] = true;

                $data['msg'] = 'Error updating status';

            }

        } else {

            $data['error'] = true;

            $data['msg'] = 'Invalid Input';

        }



        echo json_encode($data);

        exit;

    }



    function getDoctor()

    {

        $requestData = $_REQUEST;

        $start = $requestData['start'];

        $limit = $requestData['length'];

        $search = $this->input->post('search')['value'];



        $order = $this->input->post("order");

        $columns_valid = array(

            "0" => "id",

            "1" => "name",

            "2" => "email",

            "3" => "phone",

        );

        $values = $this->settings_model->getColumnOrder($order, $columns_valid);

        // $dir = $values[0];

        $dir = 'desc';

        $order = $values[1];



        if ($limit == -1) {

            if (!empty($search)) {

                $data['doctors'] = $this->doctor_model->getDoctorBysearch($search, $order, $dir);

            } else {

                $data['doctors'] = $this->doctor_model->getDoctorWithoutSearch($order, $dir);

            }

        } else {

            if (!empty($search)) {

                $data['doctors'] = $this->doctor_model->getDoctorByLimitBySearch($limit, $start, $search, $order, $dir);

            } else {

                $data['doctors'] = $this->doctor_model->getDoctorByLimit($limit, $start, $order, $dir);

            }

        }





        foreach ($data['doctors'] as $doctor) {

            $doctor_user_id = $doctor->ion_user_id ?? '0';

            $userData = $this->doctor_model->getDoctorUser($doctor_user_id);



            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                $options1 = '<a type="button" class="btn btn-info btn-xs btn_width editbutton edit-bg-new" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';

            }

            $options2 = '<a class="btn btn-info btn-xs appointment-bg-new detailsbutton" title="' . lang('appointments') . '"  href="appointment/getAppointmentByDoctorId?id=' . $doctor->id . '"> <i class="fa fa-calendar"> </i> ' . lang('appointments') . '</a>';

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                // $options3 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="doctor/delete?id=' . $doctor->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';

                $statusOpt = '<a type="button" class="btn btn-xs status-bg-new btn_width changeStatus ' . ($userData->active == '0' ? 'btn-danger' : 'btn-success') . '" title="' . lang('status') . '" data-toggle="modal" data-id="' . $doctor->id . '" data-status="' . $userData->active . '" data-user_ion_id="' . $userData->id . '">' . ($userData->active == '0' ? lang('in_active') : lang('active')) . '</a>';

            } else {

                $options3 = '';

            }







            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                // $options4 = '<a href="schedule/holidays?doctor=' . $doctor->id . '" class="btn btn-info holiday-bg-new btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('holiday') . '</a>';
                $options4 = '';

                $options5 = '<a href="schedule/timeSchedule?doctor=' . $doctor->id . '" class="btn btn-info timeSchedule-bg-new btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('time_schedule') . '</a>';

                $options6 = '<a type="button" class="btn btn-info btn-xs btn_width info-bg-new detailsbutton inffo" title="' . lang('info') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-info"> </i> ' . lang('info') . '</a>';

            }



            $info[] = array(

                $doctor->id,

                $doctor->name,

                $doctor->email,

                $doctor->phone,

                $doctor->profile,



                $options6 . ' ' . $options1 . ' ' . $options2 . ' ' . $options4 . ' ' . $options5 . ' ' . $options3 . ' ' . $statusOpt,



            );

        }



        if (!empty($data['doctors'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $this->db->get('doctor')->num_rows(),

                "recordsFiltered" => $this->db->get('doctor')->num_rows(),

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



    public function getDoctorInfo()

    {

        // Search term

        $searchTerm = $this->input->post('searchTerm');



        // Get users

        $response = $this->doctor_model->getDoctorInfo($searchTerm);



        echo json_encode($response);

    }



    public function getDoctorWithAddNewOption()

    {

        // Search term

        $searchTerm = $this->input->post('searchTerm');



        // Get users

        $response = $this->doctor_model->getDoctorWithAddNewOption($searchTerm);



        echo json_encode($response);

    }

}



/* End of file doctor.php */

/* Location: ./application/modules/doctor/controllers/doctor.php */