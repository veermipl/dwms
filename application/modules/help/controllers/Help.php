<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Help extends CI_Controller {

  public function index()
  {
    $this->load->helper(array('url', 'language'));
    $this->load->view('help');
  }

}