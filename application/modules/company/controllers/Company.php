<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Company extends MX_Controller

{



    function __construct()

    {

        parent::__construct();



        $this->load->model('company_model');

        $this->load->model('appointment/appointment_model');

        $this->load->model('patient/patient_model');

        $this->load->model('prescription/prescription_model');

        $this->load->model('schedule/schedule_model');

        $this->load->module('patient');

        $this->load->module('sms');

        if (!$this->ion_auth->in_group(array('admin', 'Accountant', 'Doctor', 'Receptionist', 'Nurse', 'Laboratorist', 'Patient','Company'))) {

            redirect('home/permission');

        }

    }



    public function index()

    {



        $data['company'] = $this->company_model->getCompany();

        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('company', $data);

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



        $id = $this->input->post( 'id');

        $name = $this->input->post('name');

        $password = $this->input->post('password');

        $email = $this->input->post('email');

        $address = $this->input->post('address');

        $phone = $this->input->post('phone');

        $profile = $this->input->post('profile');
        $registration_number = $this->input->post('registration_number');



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
        $this->form_validation->set_rules('address', 'Address', 'trim|min_length[1]|max_length[500]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('phone', 'Phone', 'trim|min_length[1]|max_length[50]|xss_clean');
        // Validating Phone Field           
        $this->form_validation->set_rules('registration_number', 'Registration Number', 'trim|min_length[1]|max_length[50]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            if (!empty($id)) {

                $data = array();
                $data['doctor'] = $this->company_model->getDoctorById($id);
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

                    // 'profile' => $profile,
                    'registration_number' => $registration_number

                );

            } else {



                $data = array();

                $data = array(

                    'name' => $name,

                    'email' => $email,

                    'address' => $address,

                    'phone' => $phone,

                    'registration_number' => $registration_number

                );

            }

            $username = $this->input->post('name');

            if (empty($id)) {     // Adding New Doctor    
                if ($this->ion_auth->email_check($email)) {

                    $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));

                    redirect('company/addNewView');

                } else {

                    $dfg = 11;

                    $this->ion_auth->register($username, $password, $email, $dfg);

                    $ion_user_id = $this->db->get_where('users', array('email' => $email))->row()->id;
                    // print_r($data);
                    // die();
                    $this->company_model->insertCompany($data);

                    $doctor_user_id = $this->db->get_where('company', array('email' => $email))->row()->id;

                    $id_info = array('ion_user_id' => $ion_user_id);

                    $this->company_model->updateCompany($doctor_user_id, $id_info);



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



                    $autoemail = $this->email_model->getAutoEmailByType('company');

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

                $ion_user_id = $this->db->get_where('company', array('id' => $id))->row()->ion_user_id;

                if (empty($password)) {

                    $password = $this->db->get_where('users', array('id' => $ion_user_id))->row()->password;

                } else {

                    $password = $this->ion_auth_model->hash_password($password);

                }

                $this->company_model->updateIonUser($username, $email, $password, $ion_user_id);

                $this->company_model->updateCompany($id, $data);

                $this->session->set_flashdata('feedback', lang('updated'));

            }

            // Loading View

            redirect('company');

        }

    }



    function editCompany()

    {

        $data = array();

        $id = $this->input->get('id');

        $data['doctor'] = $this->company_model->getCompanyById($id);

        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('add_new', $data);

        $this->load->view('home/footer'); // just the footer file

    }



    function details()

    {



        $data = array();



        if ($this->ion_auth->in_group(array('Company'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $id = $this->company_model->getCompanyByIonUserId($doctor_ion_id)->id;

        } else {

            redirect('home');

        }





        $data['doctor'] = $this->company_model->getCompanyById($id);

        $data['todays_appointments'] = $this->appointment_model->getAppointmentByCompanyByToday($id);

        $data['appointments'] = $this->appointment_model->getAppointmentByCompany($id);

        $data['patients'] = $this->patient_model->getPatient();

        $data['appointment_patients'] = $this->patient->getPatientByAppointmentByDctorId($id);

        $data['doctors'] = $this->company_model->getCompany();

        $data['prescriptions'] = $this->prescription_model->getPrescriptionByCompanyId($id);

        $data['holidays'] = $this->schedule_model->getHolidaysByCompany($id);

        $data['schedules'] = $this->schedule_model->getScheduleByCompany($id, $radio = "");

        $data['settings'] = $this->settings_model->getSettings();

        $data['statuses'] = $this->company_model->getAllStatus();

        $data['location'] = $this->company_model->getLocation();

        $data['consultation'] = $this->company_model->getConsultation_Mode();

        $data['type'] = $this->company_model->getType();







        $this->load->view('home/dashboard'); // just the header file

        $this->load->view('details', $data);

        $this->load->view('home/footer'); // just the footer file

    }



    function editCompanyByJason()

    {

        $id = $this->input->get('id');

        $data['doctor'] = $this->company_model->getCompanyById($id);

        echo json_encode($data);

    }



    function delete()

    {



        if ($this->ion_auth->in_group(array('Patient'))) {

            redirect('home/permission');

        }



        $data = array();

        $id = $this->input->get('id');

        $user_data = $this->db->get_where('company', array('id' => $id))->row();

        $path = $user_data->img_url;



        if (!empty($path)) {

            unlink($path);

        }

        $ion_user_id = $user_data->ion_user_id;

        $this->db->where('id', $ion_user_id);

        $this->db->delete('users');

        $this->company_model->delete($id);

        $this->session->set_flashdata('feedback', lang('deleted'));

        redirect('company');

    }



    public function toggleCompanyStatus()

    {

        if (count($_POST) > 0) {

            $doctor_id = htmlentities($_POST['iid']);

            $user_id = htmlentities($_POST['user_ion_id']);

            $status = htmlentities($_POST['status_iid']);



            $statuss = ($status == 1) ? 0 : 1;



            $res = $this->company_model->changeUserStatus($user_id, $statuss);



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



    function getCompany()

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

                $data['doctors'] = $this->company_model->getCompanyBysearch($search, $order, $dir);

            } else {

                $data['doctors'] = $this->company_model->getCompanyWithoutSearch($order, $dir);

            }

        } else {

            if (!empty($search)) {

                $data['doctors'] = $this->company_model->getCompanyByLimitBySearch($limit, $start, $search, $order, $dir);

            } else {

                $data['doctors'] = $this->company_model->getCompanyByLimit($limit, $start, $order, $dir);

            }

        }





        foreach ($data['doctors'] as $doctor) {

            $doctor_user_id = $doctor->ion_user_id ?? '0';

            $userData = $this->company_model->getCompanyUser($doctor_user_id);



            if ($this->ion_auth->in_group(array('admin'))) {

                $options1 = '<a type="button" class="btn btn-info btn-xs btn_width edit-bg-new editbutton" title="' . lang('edit') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-edit"> </i> ' . lang('edit') . '</a>';

            }

            $options2 = '<a class="btn btn-info btn-xs detailsbutton appointment-bg-new" title="' . lang('appointments') . '"  href="appointment/getAppointmentByDoctorId?id=' . $doctor->id . '"> <i class="fa fa-calendar"> </i> ' . lang('appointments') . '</a>';

            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                // $options3 = '<a class="btn btn-info btn-xs btn_width delete_button" title="' . lang('delete') . '" href="doctor/delete?id=' . $doctor->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i> ' . lang('delete') . '</a>';

                $statusOpt = '<a type="button" class="btn btn-xs btn_width status-bg-new changeStatus ' . ($userData->active == '0' ? 'btn-danger' : 'btn-success') . '" title="' . lang('status') . '" data-toggle="modal" data-id="' . $doctor->id . '" data-status="' . $userData->active . '" data-user_ion_id="' . $userData->id . '">' . ($userData->active == '0' ? lang('in_active') : lang('active')) . '</a>';
                $deleteBtn = '<a class="btn delete_button delete-bg-new" title="' . lang('delete') . '" href="company/delete?id=' . $doctor->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"></i> ' . lang('delete') . '</a>';

            } else {

                $options3 = '';

            }







            if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) {

                $options4 = '<a href="schedule/holidays?company=' . $doctor->id . '" class="btn btn-info holiday-bg-new btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('holiday') . '</a>';

                $options5 = '<a href="schedule/timeSchedule?company=' . $doctor->id . '" class="btn btn-info timeSchedule-bg-new btn-xs btn_width" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-book"></i> ' . lang('time_schedule') . '</a>';

                $options6 = '<a type="button" class="btn btn-info btn-xs info-bg-new btn_width detailsbutton inffo" title="' . lang('info') . '" data-toggle="modal" data-id="' . $doctor->id . '"><i class="fa fa-info"> </i> ' . lang('info') . '</a>';

            }



            $info[] = array(

                $doctor->id,

                $doctor->name,

                $doctor->email,

                $doctor->phone,

                $doctor->registration_number,



                $options6 . ' ' . $options1 . ' ' . $options3 . ' ' . $statusOpt . ' '.$deleteBtn, 



            );

        }



        if (!empty($data['doctors'])) {

            $output = array(

                "draw" => intval($requestData['draw']),

                "recordsTotal" => $this->db->get('company')->num_rows(),

                "recordsFiltered" => $this->db->get('company')->num_rows(),

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



    public function getCompanyInfo()

    {

        // Search term

        $searchTerm = $this->input->post('searchTerm');



        // Get users

        $response = $this->company_model->getCompanyInfo($searchTerm);



        echo json_encode($response);

    }



    public function getCompanyWithAddNewOption()

    {

        // Search term

        $searchTerm = $this->input->post('searchTerm');



        // Get users

        $response = $this->company_model->getDoctorWithAddNewOption($searchTerm);



        echo json_encode($response);

    }

}



/* End of file doctor.php */

/* Location: ./application/modules/doctor/controllers/doctor.php */