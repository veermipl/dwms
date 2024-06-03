<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Doctor_model extends CI_model {



    function __construct() {

        parent::__construct();

        $this->load->database();

    }



    function insertDoctor($data) {

        $this->db->insert('doctor', $data);

    }



    function getDoctor() {

        $query = $this->db->get('doctor');

        return $query->result();

    }
    function getLocation()
    {
        $query = $this->db->get('location');
        return $query->result();    
    }

    function getWeekday()
    {
        $query = $this->db->get('weekdays');
        return $query->result();    
    }

    
    function getConsultation_Mode(){
        $query = $this->db->where('status','1');
        $query = $this->db->get('consultation_mode');
        return $query->result();
    }

   function getType(){
        $query = $this->db->where('status','1');
        $query = $this->db->get('consultation_type');
        return $query->result();
    }

    function fetch_type($consultation_id)
     {
      $this->db->where('consultation_mode_id', $consultation_id);
      // $this->db->order_by('state_name', 'ASC');
      $query = $this->db->get('consultation_type');
      $output = '<option value="">Select Type Of Consultation</option>';
      foreach($query->result() as $row)
      {
       $output .= '<option value="'.$row->id.'">'.$row->name.'</option>';
      }
      return $output;
     }



    function getDoctorWithoutSearch($order, $dir) {

        if ($order != null) {

            $this->db->order_by($order, $dir);

        } else {

            $this->db->order_by('id', 'desc');

        }

        $query = $this->db->get('doctor');

        return $query->result();

    }



    function getDoctorBySearch($search, $order, $dir) {

        if ($order != null) {

            $this->db->order_by($order, $dir);

        } else {

            $this->db->order_by('id', 'desc');

        }

        $this->db->like('id', $search);

        $this->db->or_like('name', $search);

        $this->db->or_like('phone', $search);

        $this->db->or_like('address', $search);

        $this->db->or_like('email', $search);

        $query = $this->db->get('doctor');

        return $query->result();

    }

    public function getLocationById($id)
    {
        
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get('location');
        $result = $query->row();
     
        return $result;
    
    }

    public function getStatusById($id)
    {
        
        $this->db->select('status_name');
        $this->db->where('id', $id);
        $query = $this->db->get('appointment_status');
        $result = $query->row();
     
        return $result;
    
    }

    public function getAllStatus($value='')
    {
    
    $this->db->select('id,status_name');
    
    if($id!=''){
    
    $this->db->where('id', $id);
    
    }
    
    $query = $this->db->get('appointment_status');
    
    return $query->result();
    
    }



    function getDoctorByLimit($limit, $start, $order, $dir) {

        if ($order != null) {

            $this->db->order_by($order, $dir);

        } else {

            $this->db->order_by('id', 'desc');

        }

        $this->db->limit($limit, $start);

        $query = $this->db->get('doctor');

        return $query->result();

    }



    function getDoctorByLimitBySearch($limit, $start, $search, $order, $dir) {



        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);

        } else {

            $this->db->order_by('id', 'desc');

        }



        $this->db->or_like('name', $search);

        $this->db->or_like('phone', $search);

        $this->db->or_like('address', $search);

        $this->db->or_like('email', $search);

        $this->db->limit($limit, $start);

        $query = $this->db->get('doctor');

        return $query->result();

    }



    function getDoctorById($id) {

        $this->db->where('id', $id);

        $query = $this->db->get('doctor');

        return $query->row();

    }



    function getDoctorByIonUserId($id) {

        $this->db->where('ion_user_id', $id);

        $query = $this->db->get('doctor');

        return $query->row();

    }



    function updateDoctor($id, $data) {

        $this->db->where('id', $id);

        $this->db->update('doctor', $data);

    }



    function delete($id) {

        $this->db->where('id', $id);

        $this->db->delete('doctor');

    }



    function updateIonUser($username, $email, $password, $ion_user_id) {

        $uptade_ion_user = array(

            'username' => $username,

            'email' => $email,

            'password' => $password

        );

        $this->db->where('id', $ion_user_id);

        $this->db->update('users', $uptade_ion_user);

    }



    function getDoctorInfo($searchTerm) {

        if (!empty($searchTerm)) {

            $this->db->select('*');

            $this->db->where("name like '%" . $searchTerm . "%' ");

            $this->db->or_where("id like '%" . $searchTerm . "%' ");

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        } else {

            $this->db->select('*');

            

            $this->db->limit(10);

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        }





        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $this->db->select('*');

            $this->db->where('ion_user_id', $doctor_ion_id);

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        }





        // Initialize Array with fetched data

        $data = array();

        foreach ($users as $user) {

            $data[] = array("id" => $user['id'], "text" => $user['name'] . ' (' . lang('id') . ': ' . $user['id'] . ')');

        }

        return $data;

    }



    function getDoctorWithAddNewOption($searchTerm) {

        if (!empty($searchTerm)) {

            $this->db->select('*');

            $this->db->where("name like '%" . $searchTerm . "%' ");

            $this->db->or_where("id like '%" . $searchTerm . "%' ");

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        } else {

            $this->db->select('*');

           

            $this->db->limit(10);

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        }





        if ($this->ion_auth->in_group(array('Doctor'))) {

            $doctor_ion_id = $this->ion_auth->get_user_id();

            $this->db->select('*');

            $this->db->where('ion_user_id', $doctor_ion_id);

            $fetched_records = $this->db->get('doctor');

            $users = $fetched_records->result_array();

        }







        // Initialize Array with fetched data

        $data = array();

        $data[] = array("id" => 'add_new', "text" => lang('add_new'));

        foreach ($users as $user) {

            $data[] = array("id" => $user['id'], "text" => $user['name'] . ' (' . lang('id') . ': ' . $user['id'] . ')');

        }

        return $data;

    }



}

