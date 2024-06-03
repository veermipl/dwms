<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Profile extends MX_Controller {



    function __construct() {

        parent::__construct();

        $this->load->model('profile_model');

        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');

        }

    }



    public function index() {

        $data = array();

        $id = $this->ion_auth->get_user_id();

        $data['profile'] = $this->profile_model->getProfileById($id);

        $this->load->view('home/dashboard'); 

        $this->load->view('profile', $data);

        $this->load->view('home/footer'); 

    }



    public function addNew() {

        $id = $this->input->post('id');

        $name = $this->input->post('name');

        $password = $this->input->post('password');

        $email = $this->input->post('email');

        $con_pass = $this->input->post('confirm_password');


        $data['profile'] = $this->profile_model->getProfileById($id);

        if ($data['profile']->email != $email) {

            if ($this->ion_auth->email_check($email)) {

                $this->session->set_flashdata('feedback', lang('this_email_address_is_already_registered'));

                redirect('profile');

            }

        }



        $this->load->library('form_validation');

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        

        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[5]|max_length[100]|xss_clean');

       

        if (!empty($password)) {

            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[100]|xss_clean');

        }

        if(!empty($con_pass)){
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        }
     

        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[100]|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $data = array();

            $id = $this->ion_auth->get_user_id();

            $data['profile'] = $this->profile_model->getProfileById($id);

            $this->load->view('home/dashboard'); 

            $this->load->view('profile', $data);

            $this->load->view('home/footer'); 

        } else {

            $data = array();

            $data = array(

                'name' => $name,

                'email' => $email,

            );



            $username = $this->input->post('name');

            $ion_user_id = $this->ion_auth->get_user_id();

            $group_id = $this->profile_model->getUsersGroups($ion_user_id)->row()->group_id;

            $group_name = $this->profile_model->getGroups($group_id)->row()->name;

            $group_name = strtolower($group_name);

            if (empty($password)) {

                $password = $this->db->get_where('users', array('id' => $ion_user_id))->row()->password;

            } else {

                $password = $this->ion_auth_model->hash_password($password);

            }

            $this->profile_model->updateIonUser($username, $email, $password, $ion_user_id);

            if (!$this->ion_auth->in_group('admin')) {

                $this->profile_model->updateProfile($ion_user_id, $data, $group_name);

            }

            $this->session->set_flashdata('feedback', lang('updated'));



          

            redirect('profile');

        }

    }



}



/* End of file profile.php */

/* Location: ./application/modules/profile/controllers/profile.php */

