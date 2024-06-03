<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('pharmacy_model');
        $this->load->model('medicine/medicine_model');
        $this->load->model('settings/settings_model');
        $data['settings'] = $this->settings_model->getSettings();
        // if (!$this->ion_auth->in_group(array('admin', 'Accountant', 'Pharmacist'))) {
        //     redirect('home/permission');
        // }
    }

    function invoice_by_appointment_id() {

        $id = $this->input->post('appointment_id');
        date_default_timezone_set("Asia/Kolkata"); 
        if (!empty($date)) {
            $date = strtotime($date);
        }
        $this->db->where('booking_id',$appointment_id);
        $payment_query = $this->db->get('payment');
        $payment_details= $payment_query->result();

        $data = array();
        $data['payments'] = $payment_details[0];
        print_r($data);

    }


}

/* End of file pharmacy.php */
/* Location: ./application/modules/pharmacy/controllers/pharmacy.php */