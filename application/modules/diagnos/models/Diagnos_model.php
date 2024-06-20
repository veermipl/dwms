<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Diagnos_model extends CI_model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insertDiagnos($data) {

        $this->db->insert('diagnos', $data);
    }

    function getDiagnos() {
      
        $query = $this->db->get('diagnos');
        return $query->result();
    }
    
    function getActiveDiagnos() {
    
        $this->db->where('status','Active');
        $query = $this->db->get('diagnos');
        return $query->result();
    }

    function getActiveDiagnosOrderByName() {
    
        $this->db->where('status','Active');
        $this->db->order_by('title','asc');
        $query = $this->db->get('diagnos');
        return $query->result();
    }

    function getDiagnosById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('diagnos');
        return $query->row();
    }

    function updateDiagnos($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('diagnos', $data);
    }

    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('diagnos');
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

    function getDiagnosByIonUserId($id) {
        $this->db->where('ion_user_id', $id);
        $query = $this->db->get('diagnos');
        return $query->row();
    }

}
