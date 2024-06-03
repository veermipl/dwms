<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));
        $this->load->model('appointment_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('patient/patient_model');
        $this->load->model('sms/sms_model');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

        // API for location 
        function getLocation() {
            $query = $this->db->get('location');
            //header('Content-Type: application/json');
            $data = $query->result();
            echo json_encode(['data'=>'{}','status'=>"true",'message'=>"success"]);
        }

        



         // API for Mode of Consultation 
         function getConsultationMode() {
            $this->db->where('status', 1);
            $query = $this->db->get('consultation_mode');
            
            echo json_encode(['data'=>$query->result(),'status'=>"true",'message'=>"success"]);
        }



        
         // API for Mode of Consultation 
         function getConsultationType() {
           $this->db->where('consultation_mode_id',$_GET['mode']);
                $this->db->where('status', 1);
                $query = $this->db->get('consultation_type');
           // echo json_encode($query->result());
           echo json_encode(['data'=>json_encode($query->result()),'status'=>"true",'message'=>"success"]);

        }




    // API for login reworked 


    function login() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Login";

        //validate form input
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
      

        if ($this->form_validation->run() == true) {
            //check to see if the user is logging in
            //check for "remember me"
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'))) {
                //if the login is successful
                //redirect them back to the home page
               $userdata =  $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->result();
               // echo json_encode([200,$userdata]);
                echo json_encode(['data'=>$userdata[0],'status'=>"true",'message'=>"success"]);

            } else {
                //if the login was un-successful
                //redirect them back to the login page
                //echo json_encode([300,$this->ion_auth->errors()]);
                echo json_encode(['status'=>"false",'message'=>$this->ion_auth->errors()]);

            }
        } else {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


            //$this->_render_page('auth/login', $data);
            echo json_encode(['status'=>"false",'message'=>$data['message']]);

        }
    }






    //log the user out
    function logout() {
        ob_start();

        $logout = $this->ion_auth->logout();
        ob_clean();

        if ($logout) {
            echo json_encode([200, $this->ion_auth->messages()]);
            //redirect('auth/login', 'refresh');
        }
    }


   // check user 
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


    // API for Blood Group 
    function getBloodgroup() {

        $this->db->order_by("order asc");
        $query = $this->db->get('blood_group');

        $data = $query->result();
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }


    // API for Appointment List By Search 
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
        $query = $this->db->get('appointment');
        $query->result();
        echo json_encode(['data'=>$query->result_object,'status'=>"true",'message'=>"success"]);
    }








    //change password
    function change_password() {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            //display the form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
            );
            $data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
            );
            $data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->_render_page('auth/change_password', $data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //forgot password
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

    //reset password - final step for forgotten password
    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
                //display the form
                //set the flash data error message if there is one
                $data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                );
                $data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $data['min_password_length'] . '}.*$',
                );
                $data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $data['csrf'] = $this->_get_csrf_nonce();
                $data['code'] = $code;

                //render
                $this->_render_page('auth/reset_password', $data);
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("auth/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //activate the user
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

    //deactivate the user
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

  

    //edit a user
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

    // create a new group
    function create_userX() {
        $data['title'] = "Create User";
     
        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('userName', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phoneno', $this->lang->line('create_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('gender', 'gender field is required', 'required');
        // $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('userName'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('userName'),
                'phone' => $this->input->post('phoneno'),
                'gender' => $this->input->post('gender'),
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );
            
        }
        if ($this->form_validation->run() == true) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>$this->ion_auth->messages()]);
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            
            echo json_encode(['data'=>$userdata,'status'=>'false','message'=>$message]);
        }
    }
	
	
	
	// API For create user
	function create_user() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Create User";
     
        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('userName', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phoneno', $this->lang->line('create_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        //$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('gender', 'gender field is required', 'required');
        // $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('userName'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('userName'),
                'phone' => $this->input->post('phoneno'),
                'gender' => $this->input->post('gender'),
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );
            
        }
        if ($this->form_validation->run() == true) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>$this->ion_auth->messages()]);
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            
		 echo json_encode(['data'=>[],'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
        }
    }

// API to get Time slot
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


//Api to get  patinet List 

function getPatient() {
    $this->db->order_by('id', 'desc');
    $query = $this->db->get('patient');
    $data =  $query->result();
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}


//Api to get  Appointment Status  

function getappointmentStatus() {
    $this->db->order_by('order', 'ASC');
    $query = $this->db->get('appointment_status');
    $data =  $query->result();
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}





    // API to get Price Based on Membership code
  
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



// Location wise timeslots
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
            $this->db->where('loc_code',$location_id);
            $this->db->where('Weekday',$day);
            $query = $this->db->get('time_slot');
            $datanew[$data[$i]['name']] = $query->result();
            // echo json_encode(['data'=>$datanew,'status'=>"true",'message'=>"success"]);
                
        }
        echo json_encode(['data'=>$datanew,'status'=>"true",'message'=>"success"]);
    }



// API for Doctor Details
function getDoctorDetails(){
    $doctor_id  = 1;
    $this->db->where('id',$doctor_id);
    $query = $this->db->get('doctor');
    $doctor = $query->result();
    echo json_encode(['data'=>$doctor,'status'=>"true",'message'=>"success"]);
}






// API For Book Appointment

// API For Book Appointment
// API For Book Appointment
function book_appointment() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Book Appointment";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    //$this->form_validation->set_rules('patientId','PatientID', 'required');
    $this->form_validation->set_rules('mode_of_consultation','Mode Of Consultation is Reequired', 'required');
    $this->form_validation->set_rules('type_of_consultation', 'Type Of Consultation is Reequired', 'required');
    $this->form_validation->set_rules('location', '', '');
    $this->form_validation->set_rules('date', 'Select Date', 'required');
    $this->form_validation->set_rules('time_slot', 'Select Time Slot', 'required');
    $this->form_validation->set_rules('patient_name', 'Patient Name', 'required');
    $this->form_validation->set_rules('patient_email', 'Patient email', 'required');
    $this->form_validation->set_rules('patient_phone', 'patient phone', 'required');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
    $this->form_validation->set_rules('price', 'Price', 'required');
    
    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $mode_of_consultation = $this->input->post('mode_of_consultation');
        $type_of_consultation = $this->input->post('type_of_consultation');
        $location_id = $this->input->post('location');
        $date = $this->input->post('date');
        $time_slot = $this->input->post('time_slot');
        
        $time_slot_explode = explode('To', $time_slot);



        $s_time = trim($time_slot_explode[0]);

        $e_time = trim($time_slot_explode[1]);


        $patient_data = array(
            //'patient' => $this->input->post('patientId'),
            'name' => $this->input->post('patient_name'),
            'email' => $this->input->post('patient_email'),
            'phone' => $this->input->post('patient_phone')

        );
        $this->db->insert('patient', $patient_data);
        $patient_id = $this->db->insert_id();

   
        $additional_data = array(
            'patient' => $patient_id,
            'mode_of_consultation' => $this->input->post('mode_of_consultation'),
            'type_of_consultation' => $this->input->post('type_of_consultation'),
            'location_id' => $this->input->post('location_id'),
            'date' => $this->input->post('date'),
            'time_slot' => $this->input->post('time_slot'),
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'time_slot' => $e_time,
            'status' => 1,
            'price' => $this->input->post('price')

        );
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
      //  $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
      //  $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
     

        $this->db->insert('appointment', $additional_data);
        echo json_encode(['data'=>$additional_data,'status'=>'true','message'=>'Appointment Booked Successfully']);
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        
        echo json_encode(['data'=>[],'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}





public function send()
	{
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
        $server_key = 'AAAAP8HTM2A:APA91bG6k5al31k58PPMD-WNx-h5vJS6qKJ7h5eISoedjmbldzGNi8VLlto7xYJQe7PgI13fSEcH3GtQOjITzKzkzdxLE2JEj-s0ikAgbh6teWLHG_kcuEr0RdEgWSwZJM0KiTSdiE_A';
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




// API for Approve or Reject


function  ApproveOrReject(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    //2009-10-22
   $status= $_POST['status_id'];
   $appointment_id= $_POST['appointment_id'];
    $datas = array(
        'status' => $status
        );
   

    $this->db->update('appointment', $datas, array('id' => $appointment_id));
    $return = $this->db->affected_rows() == 1;
		if ($return){
            $json_data = [
                "to" => $_POST['device_token'],
                "notification" => 'success',
                "data" => [],
                ];
        $data = json_encode($json_data);
        //FCM API end-point
         $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = 'AAAAP8HTM2A:APA91bG6k5al31k58PPMD-WNx-h5vJS6qKJ7h5eISoedjmbldzGNi8VLlto7xYJQe7PgI13fSEcH3GtQOjITzKzkzdxLE2JEj-s0ikAgbh6teWLHG_kcuEr0RdEgWSwZJM0KiTSdiE_A';
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
      
    }

   echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
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

    if ($this->form_validation->run() == true) {
        echo $username = strtolower($this->input->post('userName'));
        $email = strtolower($this->input->post('email'));

        $additional_data = array(
            'first_name' => $this->input->post('userName'),
            'email' => $this->input->post('email'),
        );
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
        $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
        $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
        echo json_encode(['data'=>$additional_data,'status'=>'true','message'=>$this->ion_auth->messages()]);
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        
     echo json_encode(['data'=>[],'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
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
    function getAppointmentByDoctorId() {
        $id = $this->input->get('id');
        $data['doctor_id'] = $id;
        $data['appointments'] = $this->appointment_model->getAppointment();
        $data['patients'] = $this->patient_model->getPatient();
        $data['mmrdoctor'] = $this->doctor_model->getDoctorById($id);
        $data['doctors'] = $this->doctor_model->getDoctor();
        $data['settings'] = $this->settings_model->getSettings();
        echo json_encode(['data'=>$data,'status'=>'false','message'=>'dasdasd']);
    }
    // function getAppointmentByDoctorId() {
    //     $id = $this->input->get('id');
    //     $data['doctor_id'] = $id;
    //     $this->db->where('doctor', $id);
    //     $query = $this->db->get('appointment');
    //     $data['appointments'] = $query->result();
    //     echo json_encode(['data'=>$data,'status'=>'false','message'=>'dasdasd']);
    // }

}
