<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timeslot_model extends CI_model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

   

    function getTimeslot() {
        $query = $this->db->get('time_slot');
        return $query->result();
    }

}
