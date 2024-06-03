<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Help extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }
  public function index()
  {

    $this->load->view('help');
  }

}