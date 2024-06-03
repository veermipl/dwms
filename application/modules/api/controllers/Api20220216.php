<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));
		$this->load->model('Api_model');
        $this->load->helper("services");
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
		$this->load->model('appointment/appointment_model');
        $this->load->model('doctor/doctor_model');
        $this->load->model('patient/patient_model');
        $this->load->model('sms/sms_model');
        $this->load->module('sms');
        $this->load->helper('file_helper');



    }

        // API for location 
        function getLocation() {
            $query = $this->db->get('location');
            //header('Content-Type: application/json');
            $data = $query->result();
            echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
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
           echo json_encode(['data'=>$query->result(),'status'=>"true",'message'=>"success"]);

        }

    // API for login reworked 
    function login() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Login";
        //validate form input
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        // echo json_encode(['data'=>'hi','status'=>"true",'message'=>"success"]);
        // die();
        if ($this->form_validation->run() == true) {
            //check to see if the user is logging in
            //check for "remember me"
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'))) {
                //if the login is successful

              $deviceType=  $this->input->post('deviceType');
              $deviceTokken=  $this->input->post('deviceToken');
              $email=  $this->input->post('email');
              if(!empty($deviceTokken)){
                 $this->Api_model->device_update($deviceType,$deviceTokken,$email);
               }

                //redirect them back to the home page
              
               $userdata =  $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->result();
               $this->db->where('ion_user_id',$userdata[0]->id);
               $user_data=array();
                $doctor_query = $this->db->get('doctor');
                $doctor_data = $doctor_query->result();
                $this->db->where('ion_user_id',$userdata[0]->id);
                $patient_query = $this->db->get('patient');
                $patient_data = $patient_query->result();
                if (count($doctor_data)>0) {
                    $user_data = $doctor_data[0];
                }
                if (count($patient_data)>0) {
                    $user_data = $patient_data[0];
                }
				$user_data->deviceToken = $userdata[0]->deviceToken;
               $user_data->deviceType = $userdata[0]->deviceType;
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
                
                echo json_encode(['data'=>$user_data,'status'=>"true",'message'=>"success"]);

            } else {
                //if the login was un-successful
                //redirect them back to the login page
                //echo json_encode([300,$this->ion_auth->errors()]);
               $message=  preg_replace("/\r|\n/", "", strip_tags($this->ion_auth->errors()));

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

        ob_start();
        $logout = $this->ion_auth->logout();
        ob_clean();
        if ($logout) {
            $blank_object = new stdClass();
            echo json_encode(['data'=>$blank_object,'status'=>"true",'message'=>preg_replace("/\r|\n/", "", strip_tags($this->ion_auth->messages()))]);
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
		$query = $this->db->join('appointment_status','appointment_status.id =appointment.status' );
        $query = $this->db->get('appointment');
        $data=$query->result();
        $count=0;
        foreach ($data as $d) {
            if($d->date!='') {
                $data[$count]->date=date('Y-m-d H:i:s', $d->date);
            }
            if($d->registration_time!='') {
                $data[$count]->registration_time=date('Y-m-d H:i:s', $d->registration_time);
            }
            $count++;
        }

        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    }

    //change password
    function change_password() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

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
            $data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->_render_page('auth/change_password', $data);
        } else {
            $identity =$this->input->post('user_id');
            $old =$this->input->post('old');
            $new =$this->input->post('new');
            $old_password_matches = $this->ion_auth->hash_password_db($identity, $old);

            if ($old_password_matches === TRUE){
                $hashed_new_password  = $this->bcrypt->hash($new);
                $data = array(
                    'password' => $hashed_new_password,
                );
                $this->db->where('id', $identity);
                $result1 = $this->db->update('users', $data); 
                if ($result1==1)
                {
                    $return = new stdClass();
                    echo json_encode([ 'data'=>$return,'status'=>"true",'message'=> 'password change successful']);
                }
                else
                {
                    $return = new stdClass();
                    echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'password change unsuccessful']);
                }
            }else{
                $return = new stdClass();
                echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'Old password not match']);
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
    public function trigger_events($events)
	{
		if (is_array($events) && !empty($events))
		{
			foreach ($events as $event)
			{
				$this->trigger_events($event);
			}
		}
		else
		{
			if (isset($this->_ion_hooks->$events) && !empty($this->_ion_hooks->$events))
			{
				foreach ($this->_ion_hooks->$events as $name => $hook)
				{
					$this->_call_hook($events, $name);
				}
			}
		}
	}
    public function identity_check($identity = '')
	{
		$this->trigger_events('identity_check');

		if (empty($identity))
		{
			return FALSE;
		}

		return $this->db->where($this->identity_column, $identity)
		                ->count_all_results($this->tables['users']) > 0;
	}
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
    //reset password - final step for forgotten password
    public function resetPassword() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $identity =$this->input->post('id');
        $new =$this->input->post('new_password');
        $new = $this->bcrypt->hash($new);
        $data = array(
		    'password' => $new,
		);
        $this->db->where('id', $identity);
            $result1 = $this->db->update('users', $data); 
		if ($result1==1)
		{
            $return = new stdClass();
            echo json_encode([ 'data'=>$return,'status'=>"true",'message'=> 'password change successful']);
		}
		else
		{
            $return = new stdClass();
            echo json_encode([ 'data'=>$return,'status'=>"false",'message'=> 'password change unsuccessful']);
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
        $this->form_validation->set_rules('userName', 'Name is required', 'required');
        $this->form_validation->set_rules('email', 'Email is required', 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phoneno', 'Phone Number is required', 'required');
        $this->form_validation->set_rules('password', 'Password is required', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        
        $this->form_validation->set_rules('gender', 'gender field is required', 'required');
        

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
            date_default_timezone_set('Asia/Kolkata');
            $userid=$this->ion_auth->register($username, $password, $email,'', $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            $p_data = array();
                $p_data = array(
                    'ion_user_id' => $userdata[0]->id,
                    'name' => $this->input->post('userName'),
                    'email' => strtolower($this->input->post('email')),
                    'doctor' => 1,
                    'phone' => $this->input->post('phoneno'),
                    'sex' => $this->input->post('gender'),
                    'registration_time'=>date("U"),
                );
				
                $p_result=$this->db->insert('patient', $p_data);
                if ($p_result==true) {
                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();
                    if($result1[0]->registration_time!='') {
                        $result1[0]->registration_time=date('Y-m-d H:i:s', $result1[0]->registration_time);
                    }
                    _register_email($email);
                    echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'successfully Created']);
                }else{
                    $result1=new stdClass();
                    echo json_encode(['data'=>$result1,'status'=>'false','message'=>'unsuccessfull']);
                }
            
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $result1=new stdClass();
		 echo json_encode(['data'=>$result1,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
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
    date_default_timezone_set('Asia/Kolkata');
    $this->db->order_by('id', 'desc');
    $query = $this->db->get('patient');
    $data =  $query->result();
    $count=0;
    foreach ($data as $d) {
        if($d->img_url!='') {
            $data[$count]->img_url=base_url().$d->img_url;
        }
        if($d->registration_time!='') {
            $data[$count]->registration_time=date('Y-m-d H:i:s', $d->registration_time);
        }
        $count++;
    }
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

// API to get get available slots by location and date
    function getAvailableSlotByLocation() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $timestamp = strtotime($_POST['date']);
        //    $timestamp = strtotime('2022-01-24');
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
                    $count++;
                }else{
                    $datanew[$data[$i]['name']][$count]->booked="n";
                    $count++;
                }
            }
            

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
    if($doctor[0]->img_url!='') {
        $doctor[0]->img_url=base_url().$doctor[0]->img_url;
    }
    echo json_encode(['data'=>$doctor,'status'=>"true",'message'=>"success"]);
}

// API For Book Appointment
function book_appointment() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Book Appointment";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    //$this->form_validation->set_rules('patientId','PatientID', 'required');
    $this->form_validation->set_rules('mode_of_consultation','Mode Of Consultation is Required', 'required');
    //$this->form_validation->set_rules('type_of_consultation', 'Type Of Consultation is Required', 'required');
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
        $date = strtotime($this->input->post('date'));
        $time_slot = $this->input->post('time_slot');
        $patient_id = $this->input->post('patient_id');
        $time_slot1 = str_replace(["TO","To"],"to",$time_slot);
        $time_slot_explode= explode('to', $time_slot1);

        // print_r($time_slot_explode);
        // die();

        $s_time = trim($time_slot_explode[0]);

        $e_time = trim($time_slot_explode[1]);


		if (empty($patient_id)) {
            $patient_data = array(
                // 'patient' => $this->input->post('patientId'),
                'name' => $this->input->post('patient_name'),
                'email' => $this->input->post('patient_email'),
                'phone' => $this->input->post('patient_phone')
    
            );
            $this->db->insert('patient', $patient_data);
            $patient_id = $this->db->insert_id();
        }

   
        $additional_data = array(
            'patient' => $patient_id,
            'mode_of_consultation' => $this->input->post('mode_of_consultation'),
            'type_of_consultation' => $this->input->post('type_of_consultation'),
            'location_id' => $this->input->post('location_id'),
            'date' => strtotime($this->input->post('date')),
            'time_slot' => $this->input->post('time_slot'),
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 1,
            'patientname' => $this->input->post('patient_name'),
            'price' => $this->input->post('price')

        );
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
      //  $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
      //  $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
			$doctor_id = $this->input->post('doctor_id');
            $this->db->where('id', $doctor_id);
            $query1 = $this->db->get('doctor');

            $datas = $query1->result_array();
            $doc_user_id = $datas[0]['ion_user_id'];

            $this->db->where('id', $doc_user_id);
            $query2 = $this->db->get('users');
            $datas_doc = $query2->result_array();

            $deviceToken = $datas_doc[0]['deviceToken'];
            $deviceType = $datas_doc[0]['deviceToken'];
            $this->db->insert('appointment', $additional_data);
            $inserted_id = $this->db->insert_id();

            $this->db->where('id', $inserted_id);
            $query2 = $this->db->get('appointment');
            $result_data = $query2->result();
            if($result_data[0] ->date!='') {
                $result_data[0] ->date=date('Y-m-d', $result_data[0] ->date);
            }
            if($result_data[0] ->registration_time!='') {
                $result_data[0] ->registration_time=date('Y-m-d H:i:s', $result_data[0] ->registration_time);
            }
            $messsage = array
            (
                'title'   => 'Curewell Therapies',
                'body'     => "Your Booking Request #".$result_data[0]->id ." has been sent to the doctor. Soon you will get a notification of it's approval so that you can proceed to payment.",
            );
            $data_addedd = array
            (
                'type'   => 'BKNG',
                'id'     => $result_data[0]->id,
                'patient_id'     => $result_data[0]->patient,
                'msg'  => "Your Booking Request #".$result_data[0]->id ." has been sent to the doctor. Soon you will get a notification of it's approval so that you can proceed to payment.",
            );
            $n_data = array();
            date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $doc_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $datas[0]['img_url'],
                    'appointment_id' => $result_data[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
            _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);

        echo json_encode(['data'=>$result_data[0] ,'status'=>'true','message'=>'Appointment Booked Successfully']);
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

// API For Book send notification for jisti meet
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
        
   

    $this->db->update('appointment', $datas, array('id' => $appointment_id));
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

    $data_result=array();
      $data_result['patientid']=$appointment_details[0]->patient;
      $data_result['patientname']=$patient_data[0]->name;
      $data_result['BookingDate']=$appointment_details[0]->date;
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
      

    $return = $this->db->affected_rows() == 1;
		if ($return){
            if ($appointment_details[0]->status==2) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $data_addedd = array
                (
                    'type'   => 'approved',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
            if ($appointment_details[0]->status==3) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Because '.$description.'.',
                );
                $data_addedd = array
                (
                    'type'   => 'rejected',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Because '.$description.'.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
            if ($appointment_details[0]->status==5) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $data_addedd = array
                (
                    'type'   => 'No Show',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name,
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
            if ($appointment_details[0]->status==6) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $data_addedd = array
                (
                    'type'   => 'Postponed',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
                  
        }

   echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"success"]);
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
            $userdata =  $this->ion_auth->where('email',$this->input->post('email'))->users()->result();
            $message = $this->ion_auth->messages();
            echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);

        } else {
            $username = strtolower($this->input->post('userName'));
            $email = strtolower($this->input->post('email'));

            $additional_data = array(
                'first_name' => $this->input->post('userName'),
                'email' => $this->input->post('email'),
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );

            $data  = array(
                'deviceToken' => $this->input->post('deviceToken'),
                'deviceType' => $this->input->post('deviceType'),
            );

            $userid=$this->ion_auth->register($username, $password,'', $email, $additional_data);
            $this->db->update('users', $data, array('id' => $userid));

            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            $this->db->where('ion_user_id',$userdata->id);
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){

            } else {
                $p_data = array(
                    'name' => $this->input->post('userName'),
                    'email' => $this->input->post('email'),
                );
                $p_result=$this->db->insert('patient', $p_data);
            }
            $message = $this->ion_auth->messages();
            echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);

        }
         
    } else {
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}

    // get appointment list by Doctor Id
    function getAppointmentBydoctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $id    = $_POST['id'];
        $data['patients'] = $this->Api_model->getDoctorId($id);
        $count=0;
        foreach ($data['patients'] as $d) {
            if($d->date!='') {
                $data['patients'][$count]->date=date('Y-m-d', $d->date);
            }
            $count++;
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
    
       
    }

// as per shubahm sir instructions
function getAppointmentByPatinetId() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $id    = $_POST['id'];
    $data['patients'] = $this->Api_model->getPatient($id);
    $count=0;
    foreach ($data['patients'] as $d) {
        $this->db->where('booking_id',$d->bookingId);
        $this->db->order_by('id', 'desc');
        $query1 = $this->db->get('payment');
        $txn= $query1->result();
        $data['patients'] [$count]->txnId=$txn[0]->txnID;

        $this->db->where('id',$d->bookingId);
        $query1 = $this->db->get('appointment');
        $appointment= $query1->result();
        $data['patients'] [$count]->remarks=$appointment[0]->remarks;

        $this->db->where('id',$appointment[0]->doctor);
        $query1 = $this->db->get('doctor');
        $doctor= $query1->result();
        $data['patients'] [$count]->doctorname=$doctor[0]->name;

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
            'order_status' => $order_status_text
           

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
        $this->db->where('id',$_POST['status_id']);
        $status_query = $this->db->get('appointment_status');
        $status_data =  $status_query->result();

        $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                );
                $data_addedd = array
                (
                    'type'   => 'Payment Confirmed',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'appointment_id' => $appointment_details[0]->id,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd); 
        echo json_encode(['data'=>$order_id,'status'=>"true",'message'=>"Order Successfully."]);
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
              'order_status' =>  $order_status
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
  
    }


//  get Appointment list  26-01-2022
public function getAppointmentlist() {

    //$content = trim(file_get_contents("php://input"));
    //$_POST = json_decode($content, true);
    //  $id    = $_POST['id'];
    $data['patients'] = $this->Api_model->getAllPatient();
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

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
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);
}

//  get Labreport list 
public function getLabreportlist() {

    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $patient_id    = $_POST['id'];
    $data['lablist'] = $this->Api_model->getLabByAllId($patient_id);
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

}
//  get Document list 
public function getDocumentlist() {

    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $patient_id    = $_POST['id'];
    $data['document'] = $this->Api_model->getdocument($patient_id);
    echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

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
            echo json_encode(['data'=>$blank_object,'status'=>"true",'message'=>"OTP Verify successfully"]);
             
            }
         
         else{
            echo json_encode(['data'=>$result[0]->otp,'status'=>"false",'message'=>"New OTP & Confirm OTP is not matching"]);  
            
 
     }
    }else {
        echo json_encode(['data'=>$result[0]->otp,'status'=>"false",'message'=>"New OTP & Confirm OTP is not matching"]);
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
            echo json_encode(['status'=>"False",'message'=>"Enter Correct Email ID."]);
        }
       
    } else {
      
        echo json_encode(['status'=>"False",'message'=>"Enter Correct Email ID."]);
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
    $this->form_validation->set_rules('patient_id','Patient Required', 'required');
    $this->form_validation->set_rules('date', 'Select Date', 'required');
    $this->form_validation->set_rules('time_slot', 'Select Time Slot', 'required');
    $this->form_validation->set_rules('location', '', '');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
    
    if ($this->form_validation->run() == true) {
        $patient_id = $this->input->post('patient_id');
        $location_id = $this->input->post('location');
        $date = $this->input->post('date');
         $time_slot = $this->input->post('time_slot');
        $time_slot_explode = explode('To', $time_slot);
        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

   
        $additional_data = array(
            'patient' => $patient_id,
            'location_id' => $location_id,
            'date' => $this->input->post('date'),
            'time_slot' => $time_slot,
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 3
        );
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
     
			$doctor_id = $this->input->post('doctor_id');
            $this->db->where('id', $doctor_id);
            $query1 = $this->db->get('doctor');

            $datas = $query1->result_array();
            $doc_user_id = $datas[0]['ion_user_id'];

            $this->db->where('id', $doc_user_id);
            $query2 = $this->db->get('users');
            $datas_doc = $query2->result_array();

            $deviceToken = $datas_doc[0]['deviceToken'];
            $deviceType = $datas_doc[0]['deviceToken'];
            // Send Notification 

        $this->db->insert('appointment', $additional_data);
        echo json_encode(['data'=>$additional_data,'status'=>'true','message'=>'Appointment Booked Successfully']);
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
    $this->form_validation->set_rules('date','Date is Required', 'required');
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
        } else {
            $date = time();
        }
        $date_string = date('d-m-y', $date);
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
    $this->form_validation->set_rules('case_id','Case Id is Required', 'required');
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
    $this->form_validation->set_rules('labreport_id','Labreport Id is Required', 'required');
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
    $this->form_validation->set_rules('date','Date is Required', 'required');
    $this->form_validation->set_rules('patient_id','Patient Required', 'required');
    $this->form_validation->set_rules('doctor_id','Doctor Required', 'required');
    $this->form_validation->set_rules('template','Template Required', 'required');
    $this->form_validation->set_rules('report','Report Required', 'required');

    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $date = $this->input->post('date');
        $labreport_id = $this->input->post('labreport_id');
        $patient_id = $this->input->post('patient_id');
        $doctor_id = $this->input->post('doctor_id');
        $template = $this->input->post('template');
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
        } else {
            $date = time();
        }
        $date_string = date('d-m-y', $date);
       
            $data = array(
                'category_name' => $template,
                'report' => $report,
                'patient' => $patient_id,
                'date' => $date,
                'doctor' => $doctor_id,
                'patient_name' => $patient_name,
                'patient_phone' => $patient_phone,
                'patient_address' => $patient_address,
                'doctor_name' => $doctor_name,
                'date_string' => $date_string
            );
         
            if (empty($labreport_id)) {
                $this->Api_model->insertLab($data);
                $inserted_id = $this->db->insert_id();
                $this->db->where('id', $inserted_id);
                $query = $this->db->get('lab');
                $data[] = $query->result();
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Lab Report Added Successfully']);
            }else{
                $this->db->where('id', $labreport_id);
                $result = $this->db->update('lab', $data); 
                $inserted_id = $labreport_id;
                $this->db->where('id', $inserted_id);
                $query = $this->db->get('lab');
                $data[] = $query->result();
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Lab Report Updated Successfully']);
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
    $this->form_validation->set_rules('date', 'Date', 'trim|required|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('doctor_id', 'Doctor', 'trim|min_length[1]|max_length[100]|xss_clean');
    $this->form_validation->set_rules('symptom', 'History', 'trim|min_length[1]|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('note', 'Note', 'trim|min_length[1]|max_length[1000]|xss_clean');
    $this->form_validation->set_rules('advice', 'Advice', 'trim|min_length[1]|max_length[1000]|xss_clean');
    //$this->form_validation->set_rules('validity', 'Validity', 'trim|min_length[1]|max_length[100]|xss_clean');

    if ($this->form_validation->run() == true) {

        $prescription_id = $this->input->post('prescription_id');
        // $tab = $this->input->post('tab');
        $date = $this->input->post('date');
        if (!empty($date)) {
            $date = strtotime($date);
        }
        $patient = $this->input->post('patient_id');
        $doctor = $this->input->post('doctor_id');
        $symptom = $this->input->post('symptom');
        $medicine = $this->input->post('medicine');
        $dosage = $this->input->post('dosage');
        $frequency = $this->input->post('frequency');
        $days = $this->input->post('days');
        $instruction = $this->input->post('instruction');
        $note = $this->input->post('note');
        $admin = $this->input->post('admin');
        $advice = $this->input->post('advice');

        $report = array();

        if (!empty($medicine)) {
            foreach ($medicine as $key => $value) {
                $report[$value] = array(
                    'dosage' => $dosage[$key],
                    'frequency' => $frequency[$key],
                    'days' => $days[$key],
                    'instruction' => $instruction[$key],
                );
            }
            foreach ($report as $key1 => $value1) {
                $final[] = $key1 . '***' . implode('***', $value1);
            }
            $final_report = implode('###', $final);
        } else {
            $final_report = '';
        }

        $data = array();
        $patientname = $this->Api_model->getPatientById($patient)->name;
        $doctorname = $this->Api_model->getDoctorById($doctor)->name;
        $data = array('date' => $date,
            'patient' => $patient,
            'doctor' => $doctor,
            'symptom' => $symptom,
            // 'medicine' => $final_report,
            'medicine' => $medicine,
            'note' => $note,
            'advice' => $advice,
            'patientname' => $patientname,
            'doctorname' => $doctorname
        );
        if (empty($prescription_id)) {
            $this->Api_model->insertPrescription($data);
            $inserted_id = $this->db->insert_id();
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('prescription');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Prescription is Added Successfully']);
        }else{
            $this->db->where('id', $prescription_id);
            $result = $this->db->update('prescription', $data); 
            $inserted_id = $prescription_id;
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('prescription');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Prescription Updated Successfully']);
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
    $this->form_validation->set_rules('prescription_id','Prescription Id is Required', 'required');
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
    $data['title'] = "Add Holiday";
    //validate form input
    $this->form_validation->set_rules('date','Date is Required', 'required');
    $this->form_validation->set_rules('doctor_id','Patient Required', 'required');

    if ($this->form_validation->run() == true) {
        //$patient = $this->input->post('patientId');
        $date = $this->input->post('date');
        $doctor_id = $this->input->post('doctor_id');
        $data = array();
    // $patient_user_id = $this->db->get_where('patient', array('id' => $patient_id))->row();
    // $doctor_user_id = $this->db->get_where('doctor', array('id' => $doctor_id))->row();
   
            $data = array(
                'date' => $date,
                'doctor' => $doctor_id
            );
         
    
        $this->Api_model->insertHoliday($data);
           $inserted_id = $this->db->insert_id();     
            $this->db->where('id', $insert_id);
            $query = $this->db->get('holidays');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Holiday Added Successfully']);
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
        // $this->form_validation->set_rules('profileimg', 'Profile image is required', 'required');
        $this->form_validation->set_rules('name', 'Name is required', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number is required', 'required');
        $this->form_validation->set_rules('gender', 'gender field is required', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth field is required', 'required');
        $this->form_validation->set_rules('blood_group', 'Blood Group field is required', 'required');
        $this->form_validation->set_rules('address', 'Address field is required', 'required');
        $this->form_validation->set_rules('pincode', 'Pincode field is required', 'required');
        if ($this->form_validation->run() == true) {
            $id = $_POST['id'];
            $this->db->where('id',$id);
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){
                if (empty($_POST ['profileimg'])) {
                    $prfile_data = array(
                                    'name'=>$_POST ['name'],
                                    'phone'=>$_POST ['phone'],
                                    'sex'=>$_POST ['gender'],
                                    'birthdate'=>$_POST ['dob'],
                                    'bloodgroup'=>$_POST ['blood_group'],
                                    'address'=>$_POST ['address'],
                                    'doctor'=>1,
                                    'pincode'=>$_POST ['pincode']
                                );
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }else {
                    $base64_image_string=$_POST ['profileimg'];
                    $output_file="./uploads/";
                        $toDay   = date("Y-m-d");
                        $rand    = rand(1000, 9999);
                        $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
                    file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );

                    $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,
                                        'name'=>$_POST ['name'],
                                        'phone'=>$_POST ['phone'],
                                        'sex'=>$_POST ['gender'],
                                        'birthdate'=>$_POST ['dob'],
                                        'bloodgroup'=>$_POST ['blood_group'],
                                        'address'=>$_POST ['address'],
                                        'doctor'=>1,
                                        'pincode'=>$_POST ['pincode']
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
        $id = $this->input->get('id');
        $data = array();
        $appointments = $this->appointment_model->getAppointmentByDoctor($id);
        foreach ($appointments as $appointment) {
            $patient_exists = $this->patient_model->getPatientById($appointment->patient);
            if (!empty($patient_exists)) {
                $patients['id'] = $appointment->patient;
                $patients['name'] = $patient_exists->name;
                $patients['booking_date'] = date('d/m/Y', $appointment->date);
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
        } else {
            $patients = '';
        }
        echo json_encode(['data'=>$patients,'status'=>'false','message'=>'dasdasd']);
    }

// Edited By Ajit


    function getPatientByDoctorIdbyjason() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);

        $id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $locationid = $_POST['locationid'];
        $data = array();
        $this->db->where('doctor', $id);
        $this->db->where('date', $date);
        $this->db->where('location_id', $locationid);
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

            if (!empty($patient_exists)) {
                $patient['patientid'] = $appointment->patient;
                $patient['patientname'] = $patient_exists->name;
                $patient['BookingDate'] = $appointment->date;
                $patient['BookingTime'] = $appointment->s_time;
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
                $patient['date'] = $date;
                $patient['description'] ='';
				$patient['status_id'] = $appointment->status;
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
                $patient['BookingDate'] = $appointment->date;
                $patient['BookingTime'] = $appointment->s_time;
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
                $patient['date'] = $date;
                $patient['description'] ='';
				$patient['status_id'] = $appointment->status;
                $patient['doctor_name'] = $doctor[0]->name;
                $patient['remarks'] = $appointment->remarks;
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
        $this->form_validation->set_rules('patient_id','Patient Id is Required', 'required');
        $this->form_validation->set_rules('base_data','file is Required', 'required');

        if ($this->form_validation->run() == true) {
            $document_id = $this->input->post('document_id');
            $patient_id = $this->input->post('patient_id');
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
                        'patient_name' => $patient_name,
                        'patient_address' => $patient_address,
                        'patient_phone' => $patient_phone,
                        'date_string' => date('d-m-y', $date),
                    );
                    if (empty($document_id)) {
                        $this->db->insert('patient_material', $data);
                        $patient_id2 = $this->db->insert_id();
                        $patient_details2 = $this->patient_model->getPatientById($patient_id2);
                        echo json_encode(['data'=>$patient_details2,'status'=>"true",'message'=>"Document Added Successfully"]);
                    }else{
                        $this->db->where('id', $document_id);
                        $result = $this->db->update('patient_material', $data); 
                        $inserted_id = $document_id;
                        $this->db->where('id', $inserted_id);
                        $query = $this->db->get('patient_material');
                        $data[] = $query->result();
                        echo json_encode(['data'=>$data,'status'=>'true','message'=>'Document Updated Successfully']);
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
        $this->form_validation->set_rules('document_id','Document Id is Required', 'required');
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
        $data['title'] = "Add Scedule";
        //validate form input
        $this->form_validation->set_rules('day','Day is Required', 'required');
        $this->form_validation->set_rules('doctor_id','Patient Required', 'required');
        $this->form_validation->set_rules('start_time','Start Time Required', 'required');
        $this->form_validation->set_rules('duration','Duration Required', 'required');
        $this->form_validation->set_rules('location_id','Location is Required', 'required');
        $this->form_validation->set_rules('consultation_type','Consultation Type is Required', 'required');

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

            $data = array();   
                $data = array(
                    'doctor' => $doctor_id,
                    's_time' => $this->input->post('start_time'),
                    'e_time' => $endTime,
                    'weekday' => $day,
                    'location_id' => $location_id,
                    'membership_code' =>$consultation_type
                );
            
        
        $data_ins =  $this->Api_model->insertScedule($data);
            
            if($data_ins == 1){
                echo json_encode(['data'=>$data,'status'=>'true','message'=>'Time Slot Added Successfully']);
            } else {
                echo json_encode(['data'=>$data,'status'=>'false','message'=>'Time Slot Alredy Exist']);
            }


            } else {
                //display the create user form
                $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                echo json_encode(['data'=>'{}','status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
            }

    }

    public function test_book_appointment() {
        $deviceToken = 'cxi0ydwbRxaX3XCeRAadR-:APA91bGg3MzBj3Pnrcv4pqzX2oygnFQCI15xhw7Ed493hfxFDy_a-uyWJ-BEhxIcp7WLPCGDwHm42XgmEHjO0HoonW2XW4ZuwJrkAShCUQy6B_ULNfB5humpwLP0uPj-W_plCinMY2W_';
        $deviceType = $datas_doc[0]['deviceToken'];
        $messsage = array
        (
            'title'   => 'Curewell Therapies',
            'body'     => 'Your appointment #378 has been successfully approved by Dr. Sudhir Bhola. Now you can proceed to payment.',
        );
        $data_addedd = array
        (
            'type'   => 'BKNG',
            'id'     => '378',
            'patient_id'  => '123',
            'msg'  => 'Your appointment #378 has been successfully approved by Dr. Sudhir Bhola. Now you can proceed to payment.'
        );
        
        _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);
        echo json_encode(['data'=>$result ,'status'=>'true','message'=>"appointment book successfully"]);

    }
    public function send_email(){
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $this->form_validation->set_rules('patientid','Patient Id is Required', 'required');
        $this->form_validation->set_rules('email','Email is Required', 'required');
        $this->form_validation->set_rules('templateid','Template Id is Required', 'required');
        $this->form_validation->set_rules('subject','Templateid is Required', 'required');
        $this->form_validation->set_rules('paragraph','Paragraph is Required', 'required');
        if ($this->form_validation->run() == true) {
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
            $mail->Username = 'business@mishainfotech.com';
            $mail->Password = 'Misha#@12$';
            $mail->SMTPSecure = 'tls';
            $mail->Port     = "587";
            $mail->setFrom('business@mishainfotech.com', 'Curewell Therapies');
            $mail->addAddress($_POST['email']);
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
                echo json_encode(['data'=>$blank_object,'status'=>'true','message'=>'Email send successfully']);
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
            $data[$days]=array();
            $this->db->where('doctor',$id);
            $this->db->where('weekday', $days);
            $this->db->order_by("s_time asc");
            $query = $this->db->get('time_slot');
            $time_slot_by_day= $query->result();
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
        $NewDate=Date('Y-m-d', strtotime('+30 days'));
        $array = array();
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($NewDate);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime(date("Y-m-d")), $interval, $realEnd);
                
        $this->db->where('membership_code','VIP');
        $time_slot_query = $this->db->get('time_slot');
        $time_slot_data = $time_slot_query->result();

        foreach($period as $date) {	
            foreach ($time_slot_data as $tsd) {
                if (date('l', strtotime($date->format('Y-m-d')))==$tsd->weekday) {
                    // if (in_array($date->format('Y-m-d'), $array)) {
                    // }else{
                        array_push($array,$date->format('Y-m-d'));
                    // }
                }
            }
            
        }

        if (count($array)>0) {
            echo json_encode(['data'=>$array,'status'=>'true','message'=>'List of VIP time slot dates']);
        }else{
            $blank_object=new stdClass();
            echo json_encode(['data'=>$time_slot_data,'status'=>'false','message'=>'No VIP time slot date found']);
        }
    }
    function create_user_by_doctor() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $data['title'] = "Create User By Doctor";
     
        $tables = $this->config->item('tables', 'ion_auth');

        //validate form input
        $this->form_validation->set_rules('name', 'Name is required', 'required');
        $this->form_validation->set_rules('email', 'Email is required', 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phone', 'Phone Number is required', 'required');
        $this->form_validation->set_rules('password', 'Password is required', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        
        $this->form_validation->set_rules('gender', 'gender field is required', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth field is required', 'required');
        $this->form_validation->set_rules('blood_group', 'Blood Group field is required', 'required');
        $this->form_validation->set_rules('address', 'Address field is required', 'required');
        

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('name'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $phone = $this->input->post('phone');
            $gender = $this->input->post('gender');
            $dob = $this->input->post('dob');
            $blood_group = $this->input->post('blood_group');
            $address = $this->input->post('address');

            $additional_data = array(
                'first_name' => $this->input->post('name'),
                'phone' => $this->input->post('phoneno'),
                'gender' => $this->input->post('gender'),
            );
            
        }
        if ($this->form_validation->run() == true) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $userid=$this->ion_auth->register($username, $password, $email,'', $additional_data);
            $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
            $base64_image_string=$_POST ['profile_img'];
            $output_file="./uploads/";
            // $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
            // $mime=$splited[0];
            // $data=$splited[1];
                
            // $mime_split_without_base64=explode(';', $mime,2);
            // $mime_split=explode('/', $mime_split_without_base64[0],2);
            // if(count($mime_split)==2)
            // {
                $toDay   = date("Y-m-d");
                $rand    = rand(1000, 9999);
                // $extension=$mime_split[1];
                // if($extension=='jpeg')$extension='jpg';
                $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
            // }
            file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );
            $p_data = array();
                $p_data = array(
                    'ion_user_id' => $userdata[0]->id,
                    'name' => $username,
                    'email' => $email,
                    'doctor' => 1,
                    'phone' => $phone,
                    'sex' => $gender,
                    'address' => $address,
                    'birthdate' => $dob,
                    'bloodgroup' => $blood_group,
                    'img_url' => "uploads/".$output_file_with_extension,
                );
				
                $p_result=$this->db->insert('patient', $p_data);
                if ($p_result==true) {
                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();
                    if($result1[0]->img_url!='') {
                        $result1[0]->img_url=base_url().$result1[0]->img_url;
                    }
                    _register_email($email);
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
        $this->form_validation->set_rules('id', 'Patient Id is required', 'required');
        $this->form_validation->set_rules('name', 'Name is required', 'required');
        $this->form_validation->set_rules('birthdate', 'Date of Birth field is required', 'required');
        // $this->form_validation->set_rules('profileimg', 'Profile Image is required', 'required');
        if ($this->form_validation->run() == true) {
            $id = $_POST['id'];
            $this->db->where('id',$id);
            $query = $this->db->get('patient');
            $num = $query->num_rows();  
            if($num >=1){
                if (empty($_POST ['profileimg'])) {
                    $prfile_data = array('name'=>$_POST ['name'],'birthdate'=>$_POST ['dob']);
                    $this->db->where('id', $id);
                    $result=$this->db->update('patient', $prfile_data);
                }else{
                    $base64_image_string=$_POST ['profileimg'];
                    $output_file="./uploads/";
                        $toDay   = date("Y-m-d");
                        $rand    = rand(1000, 9999);
                        $output_file_with_extension=$toDay.'_'.$rand.'.jpg';
                    file_put_contents( $output_file . $output_file_with_extension, base64_decode($base64_image_string) );
    
                    $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,'name'=>$_POST ['name'],'birthdate'=>$_POST ['dob']);
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
        $this->form_validation->set_rules('doctorid', 'Doctor Id is required', 'required');
        $this->form_validation->set_rules('doctorname', 'Name is required', 'required');
        $this->form_validation->set_rules('address', 'Address field is required', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile field is required', 'required');
        // $this->form_validation->set_rules('profileimg', 'Profile Image is required', 'required');
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
                        $toDay   = date("Y-m-d");
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
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();

        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->date=date('Y-m-d', strtotime($n->created_at));
            $notification_result[$count]->time=date('H:i:s', strtotime($n->created_at));

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
            $notification_result[$count]->date=$appointment_result[0]->date;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            // $notification_result[$count]->type_of_user='';
            
            
            $count++;
        }

        echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"success"]);

    
    }
    function getnotificationBypatient() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        
        $this->db->where('id',$_POST['patient_id']);
        $patient_query = $this->db->get('patient');
        $patient_result= $patient_query->result();
        
        $this->db->where('user_id',$patient_result[0]->ion_user_id);
        $notification_query = $this->db->get('notification');
        $notification_result= $notification_query->result();
        $count=0;
        foreach ($notification_result as $n) {
            date_default_timezone_set("Asia/Kolkata"); 
            $notification_result[$count]->date=date('Y-m-d', strtotime($n->created_at));
            $notification_result[$count]->time=date('H:i:s', strtotime($n->created_at));

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
            $notification_result[$count]->date=$appointment_result[0]->date;
            $notification_result[$count]->location_id=$appointment_result[0]->location_id;
            $notification_result[$count]->location_name=$location_result[0]->name;
            $notification_result[$count]->mode_of_consultation=$appointment_result[0]->mode_of_consultation;
            $notification_result[$count]->type_of_consultation=$appointment_result[0]->type_of_consultation;
            // $notification_result[$count]->type_of_user='';
            
            
            $count++;
        }

        echo json_encode(['data'=>$notification_result,'status'=>"true",'message'=>"success"]);

    
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
        foreach ($data as $d) {
            $data['Prescriptionlist'][$count]->date=date('Y-m-d', $d->date);
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
        foreach ($data as $d) {
            $data['lablist'][$count]->date=date('Y-m-d', $d->date);
            $count++;
        }
        echo json_encode(['data'=>$data,'status'=>"true",'message'=>"success"]);

    
    }
    function getrepportbypatientid() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id    = $_POST['patient_id'];
        $this->db->where('patient', $patient_id);
        $this->db->order_by('patient', 'asc');
        $query = $this->db->get('report');
        $data=$query->result();
        echo json_encode(['data'=>$data,'status'=>'true','message'=>'Report by doctor id']);
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
        $this->form_validation->set_rules('doctor_id', 'Doctor field is required', 'required');
        $this->form_validation->set_rules('patient_id', 'Patient field is required', 'required');
        $this->form_validation->set_rules('report_type', 'Report type is required', 'required');
        $this->form_validation->set_rules('description', 'Description is required', 'required');
        $this->form_validation->set_rules('date', 'Date is required', 'required');
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
                'date'=>$date,
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

}
