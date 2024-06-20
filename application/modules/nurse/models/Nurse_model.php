<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Nurse_model extends CI_model
{
    function __construct()
    {
        parent::__construct();

        $this->load->database();
    }


    function insertNurse($data)
    {
        $this->db->insert('nurse', $data);
    }


    function getNurse()
    {
        $query = $this->db->order_by('id', 'desc')->get('nurse');
        return $query->result();

        // $query = $this->db->select('nurse.*')->from('users')->join('nurse', 'users.id = nurse.ion_user_id', 'right')->where('users.active', 1);
        // return $query->result();
    }


    function getNurseById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('nurse');

        return $query->row();
    }


    function updateNurse($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('nurse', $data);
    }


    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('nurse');
    }


    function updateIonUser($username, $email, $password, $ion_user_id)
    {
        $uptade_ion_user = array(
            'username' => $username,
            'email' => $email,
            'password' => $password
        );

        $this->db->where('id', $ion_user_id);
        $this->db->update('users', $uptade_ion_user);
    }


    function getNurseUser($id)
    {
        if ($id) {
            $this->db->where('id', $id);
            $query = $this->db->get('users');

            return $query->row();
        } else {
            return false;
        }
    }


    function changeUserStatus($id, $status)
    {
        $data = [
            'active' => $status
        ];

        $this->db->where('id', $id);
        $this->db->update('users', $data);

        return true;
    }
}
