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
		$query = $this->db->join('appointment_status','appointment_status.id =appointment.status' );
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
        
    //     $query = $this->db->query("SELECT `id`,`password` ,'salt' FROM `users` WHERE  `id`='$identity'");
    // $result = $query->result_array();
 
		$new = $this->bcrypt->hash($new);


		//store the new password and reset the remember code so all remembered instances have to re-login
		//also clear the forgotten password code
		$data = array(
		    'password' => $new,
		);
        $this->db->where('id', $identity);
            $result1 = $this->db->update('users', $data); 
		// $r=$this->db->update($this->tables['users'], $data, array('id' => $identity));
        // print_r($result1);
        // die(); 
		// $return = $this->db->affected_rows() == 1;
		if ($result1==1)
		{
            $return = new stdClass();
            echo json_encode([ 'data'=>$return,'status'=>"true",'message'=> 'password change successful']);
		}
		else
		{
			// $this->trigger_events(array('post_change_password', 'post_change_password_unsuccessful'));
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
                );
				
                $p_result=$this->db->insert('patient', $p_data);
                if ($p_result==true) {
                    $this->db->where('ion_user_id',$userdata[0]->id);
                    $query1 = $this->db->get('patient');
                    $result1= $query1->result();
                    _register_email($email);
                    echo json_encode(['data'=>$result1[0],'status'=>'true','message'=>'successfully Created']);
                }else{
                    $result1=new stdClass();
                    echo json_encode(['data'=>$result1,'status'=>'true','message'=>'unsuccessfull']);
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
            $this->db->where('location_id',$location_id);
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
            'date' => $this->input->post('date'),
            'time_slot' => $this->input->post('time_slot'),
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 1,
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

            $deviceToken = 'cxi0ydwbRxaX3XCeRAadR-:APA91bHTrsZbseQ-AiPwT6IxFfDaLYLh0ssh3PEsbR6Ujc1lMz_uUZjX2TQAE949rJMbMXZOiueoO6RGHwraTFHrqqQTGkNngv_ln0X5j9JOs6j6gYfDqg6OaCOcd4fZFmj5NFTMgqzh';
            $deviceType = $datas_doc[0]['deviceToken'];
            $this->db->insert('appointment', $additional_data);
            $inserted_id = $this->db->insert_id();

            $this->db->where('id', $inserted_id);
            $query2 = $this->db->get('appointment');
            $result_data = $query2->result();



                $json_data = [
                    "to" => $deviceToken,
                    "notification" => ['msg'=>'An Appointment Booked' ],
                    "data" => ['msg'=>'sdsd'],
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
                if ($result === false) {
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
               // print_r($result);

        echo json_encode(['data'=>$result_data[0] ,'status'=>'true','message'=>'Appointment Booked Successfully']);
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
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




// API for Approve or Reject

/*

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
} */




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

   echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"success"]);
}



// API For create user Google Signing 
// API For create user Google Signing 
function googlesigningdetails() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Create User From Google Signing in";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    $this->form_validation->set_rules('userName', 'User Name', 'required');
    $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
    $this->form_validation->set_rules('deviceToken', '', '');
    $this->form_validation->set_rules('deviceType', '', '');
    if ($this->form_validation->run() == true) {
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
        
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
    $userid=$this->ion_auth->register($username, $password, $email, $additional_data);
       $this->db->update('users', $data, array('id' => $userid));
        //$this->db->update($this->tables['users'], $data, array('id' => $userid));

        $userdata =  $this->ion_auth->where('id',$userid)->users()->result();
		//echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>$this->ion_auth->messages()]);
		 $message = $this->ion_auth->messages();
        echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);

       // echo json_encode(['data'=>$userdata[0],'status'=>'true','message'=>'Success']);
    } else {
        //display the create user form
        //set the flash data error message if there is one
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


function getAppointmentByPatinetId() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $id    = $_POST['id'];
    $data['patients'] = $this->Api_model->getPatient($id);
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
            case 0:
                $array['order_status'] = "pending";
                break;
            case 1:
                $array['order_status'] = "active";
                break;
                case 2:
                $array['order_status'] = "rejected";
                break;
                case 3:
                $array['order_status'] = "expired";
                break;
            default:
                $array['order_status'] = "pending";
                break;
        }
        $pay_via = $_POST['pay_via'];
        switch ($pay_via) {
            case 1:
                $array['pay_via'] = "PAY_U_MONEY";
                break;
            case 2:
                $array['pay_via'] = "PAY_U_BIZ";
                break;
            default:
                $array['pay_via'] = "UNDEFINED";
                break;
        }
        $array = array(
            'patient' => $patient_id,
            'doctor' => $doctor_id,
            'order_id' => $order_id,
            'amount' => $amount,
            'booking_Id' => $booking_id,
            'payment_type' => $pay_via,
            'order_status' => $order_status
           
        );
        $this->db->insert('payment',$array);
        echo json_encode(['data'=>$order_id,'status'=>"true",'message'=>"Order Successfully."]);
       
    }
   
// complete transaction
    public function complete_transaction()
    {
          $content = trim(file_get_contents("php://input"));
          $_POST = json_decode($content, true);
          $order_id =  $_POST['order_id'];
          $transaction_id = $_POST['transaction_id'];
          $order_status = $_POST['order_status'];
          $transaction_id = $this->input->post('transaction_id');
          $this->db->select("MAX(DISTINCT(invoice_no)) as invoice_no");
          $invoice_no = ++$this->db->get("payment")->row()->invoice_no;
  
          $data = array(
              'invoice_no' => $invoice_no,
              'txnID' => $transaction_id,
              'order_status' =>  $order_status
          );
          $this->db->where('order_id', $order_id);
          $this->db->update('payment', $data);
          echo json_encode(['data'=>$data,'status'=>"true",'message'=>"Payment Successfully."]);
  
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
//  public function forget_password() {
//     $content = trim(file_get_contents("php://input"));
//     $_POST = json_decode($content, true);
//      $email = $_POST['email']; 
//     if (!empty($email) ) {
//         $result= $this->Api_model->check_email_exists($email);
//         if(!empty($result[0]['id'])){
//             $otp = rand(1000, 9999);
//             $msg = "Forgot Password: Your OTP is $otp";
            
//             $update_rows = array(
//                 'otp' => $otp
    
//             );
//             $this->db->where('email', $email);
//             $result = $this->db->update('users', $update_rows); 

//             _send_email($email,$otp,$msg);
//             $data['id']= $result[0]['id'];
//             echo json_encode(['data'=>$data,'status'=>"true",'message'=>"We have sent an OTP on your registered email-id."]);
//         } else {
//             echo json_encode(['status'=>"False",'message'=>"Enter Correct Email ID."]);
//         }
       
//     } else {
      
//         echo json_encode(['status'=>"False",'message'=>"Enter Correct Email ID."]);
//     }
  
// }
// reset Password 

// public function resetPassword() {
//         $content = trim(file_get_contents("php://input"));
//         $_POST = json_decode($content, true);
//         $new_password =  $_POST['new_password'];
//         $conf_password = $_POST['confirm_password'];
//         $userid = $_POST['id'];
//         // if($new_password == $conf_password) {
//                 if($this->Api_model->updatePassword($new_password, $userid)){
//                      echo json_encode(['data'=>$blank_object,'status'=>"true",'message'=>"Password updated successfully."]);
//                 }
//                 else{
                   
//                      echo json_encode(['data'=>$blank_object,'status'=>"False",'message'=>"Failed to update password."]);
//                 }
//         //     }
        
//         // else{
         
//         //      echo json_encode(['data'=>$blank_object,'status'=>"False",'message'=>"New password & Confirm password is not matching."]);
//         // }
    
// }


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

                // $json_data = [
                //     "to" => $deviceToken,
                //     "notification" => 'An Appointment Booked',
                //     "data" => [],
                // ];
                // $data = json_encode($json_data);
                // //FCM API end-point
                // $url = 'https://fcm.googleapis.com/fcm/send';
                // $server_key = 'AAAAP8HTM2A:APA91bG6k5al31k58PPMD-WNx-h5vJS6qKJ7h5eISoedjmbldzGNi8VLlto7xYJQe7PgI13fSEcH3GtQOjITzKzkzdxLE2JEj-s0ikAgbh6teWLHG_kcuEr0RdEgWSwZJM0KiTSdiE_A';
                // //header with content_type api key
                // $headers = array(
                //     'Content-Type:application/json',
                //     'Authorization:key=' . $server_key,
                // );
                // //CURL request to route notification to FCM connection server (provided by Google)
                // $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                // $result = curl_exec($ch);
                // if ($result === false) {
                //     die('Oops! FCM Send Error: ' . curl_error($ch));
                // }
                // curl_close($ch);

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
        //$patient = $this->input->post('patientId');
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


        $this->db->insert('medical_history', $case_data);
       
         $insert_id = $this->db->insert_id();

        $this->db->where('id', $insert_id);
        $query = $this->db->get('medical_history');
        //header('Content-Type: application/json');
        $data[] = $query->result();
        echo json_encode(['data'=>$data,'status'=>'true','message'=>'Case  Booked Successfully']);
    } else {
        //display the create user form
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
         
    
            $this->Api_model->insertLab($data);
           $inserted_id = $this->db->insert_id();
 
        
            $this->db->where('id', $insert_id);
            $query = $this->db->get('lab');
            $data[] = $query->result();
            echo json_encode(['data'=>$data,'status'=>'true','message'=>'Lab Report Added Successfully']);
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

    $id = $this->input->post('id');
    $tab = $this->input->post('tab');
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

            // }
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
   

    $this->Api_model->insertPrescription($data);

    $inserted_id = $this->db->insert_id();
 
        
    $this->db->where('id', $insert_id);
    $query = $this->db->get('prescription');
    $data[] = $query->result();
    echo json_encode(['data'=>$data,'status'=>'true','message'=>'Prescription is Added Successfully']);
} else {
    //display the create user form
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
        $id = $_POST['id'];
        $base64_image_string=$_POST ['profileimg'];
        $output_file="./uploads/";
        $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
        $mime=$splited[0];
        $data=$splited[1];
    
        $mime_split_without_base64=explode(';', $mime,2);
        $mime_split=explode('/', $mime_split_without_base64[0],2);
        if(count($mime_split)==2)
        {
            $toDay   = date("Y-m-d");
			$rand    = rand(1000, 9999);
            $extension=$mime_split[1];
            if($extension=='jpeg')$extension='jpg';
            //if($extension=='javascript')$extension='js';
            //if($extension=='text')$extension='txt';
            $output_file_with_extension=$toDay.'_'.$rand.'.'.$extension;
        }
        file_put_contents( $output_file . $output_file_with_extension, base64_decode($data) );

        $prfile_data = array('img_url' => "uploads/".$output_file_with_extension,'name'=>$_POST ['name'],'birthdate'=>$_POST ['dob']);
        $this->db->where('id', $id);
        $result=$this->db->update('patient', $prfile_data);

        
        echo json_encode(['data'=>$result,'status'=>'false','message'=>'dasdasd']);
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
            $patient_array['patients'][$i]=$patient;
            $i++;
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
                    $img_url = $success;
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
                    $this->db->insert('patient_material', $data);
                    $patient_id = $this->db->insert_id();
                    echo json_encode(['data'=>$patient_details,'status'=>"true",'message'=>"success"]);
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

        if ($this->form_validation->run() == true) {

            $day = $this->input->post('day');
            $doctor_id = $this->input->post('doctor_id');
            $start_time = $this->input->post('start_time');
            $location_id = $this->input->post('location_id');
        
            $time = strtotime($this->input->post('start_time'));
            //echo $startTime = date("H:i", strtotime('-30 minutes', $time));
            echo $endTime = date("h:i A", strtotime('+30 minutes', $time));


            $data = array();   
                $data = array(
                    'doctor' => $doctor_id,
                    's_time' => $this->input->post('start_time'),
                    'e_time' => $endTime,
                    'weekday' => $day,
                    'location_id' => $location_id,
                    'membership_code' =>'G'
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

}
