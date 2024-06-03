<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Location_model extends CI_model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    
    function getLocation() {
        $query = $this->db->get('location');
        return $query->result();
    }

    

}
