<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



class Api_model extends CI_Model {



  function __construct() {

        parent::__construct();

        $this->load->database();

    }



   

function key_exists($key)

{

    $this->db->where('patient',$key);

    $query = $this->db->get('appointment');



    if ($query->num_rows() > 0){

        return true;

    }

    else{

        return false;

    }

}



function device_update($deviceType,$deviceTokken,$email)

{

   

    $update_rows = array(

                'deviceType' => $deviceType,

                'deviceToken' => $deviceTokken

            );

            $this->db->where('email', $email);

            $result = $this->db->update('users', $update_rows); 



            return $result;

}



function getAppointment() {

    $this->db->order_by('id', 'desc');
    $query = $this->db->get('appointment');
    return $query->result();
}

function getPatient($id) {
    $this->db->select('appointment.id as bookingId,patient.id as patientId,patient.name as patientName,
    appointment.mode_of_consultation as mode_of_consultation,appointment.s_time as starttime,appointment.description as reason,  appointment.e_time as endtime,appointment.time_slot as time_slot,
    appointment.type_of_consultation as type_of_consultation,appointment_status.status_name as status,  appointment.price as price,appointment.date as date ,appointment.user as description, location.name as location,users.deviceToken as patient_device_token,appointment.patient_type as patient_type,appointment.registration_time as created_at');
    $this->db->where('patient.id', $id);
    $this->db->join('appointment','appointment.patient=patient.id');
    $this->db->join('appointment_status','appointment_status.id=appointment.status');
    $this->db->join('location','location.id=appointment.location_id','left');
    $this->db->join('users','users.id=patient.ion_user_id');
    $this->db->order_by('appointment.id', 'desc');
    $query = $this->db->get('patient');
   return $query->result();
  //echo  $this->db->last_query();   
}

function getDoctorId($id) {

    $this->db->select('appointment.id as bookingId,patient.id as patientId,patient.name as patientName,
    appointment.mode_of_consultation as mode_of_consultation,appointment.s_time as starttime, appointment.e_time as endtime,appointment.time_slot as time_slot, appointment.type_of_consultation as type_of_consultation,appointment_status.status_name as status, appointment.price as price, appointment.date as date , location.name as location');
    $this->db->where('doctor.ion_user_id', $id);
    $this->db->join('appointment','appointment.doctor=doctor.id');
    $this->db->join('patient','patient.id=appointment.patient');
    $this->db->join('appointment_status','appointment_status.id=appointment.status');
    $this->db->join('location','location.id=appointment.location_id');
    $query = $this->db->get('doctor');
   return $query->result();
  //echo  $this->db->last_query();
}

function getDoctorById($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('doctor');
    return $query->row();
}

function getDoctor() {
    $query = $this->db->get('doctor');
    return $query->result();
}

function insertLab($data) {
    $this->db->insert('lab', $data);
}

function insertPrescription($data) {
    $this->db->insert('prescription', $data);
}

function getPatientById($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('patient');
    return $query->row();
}

function insertHoliday($data) {
    return $this->db->insert('holidays', $data);
}

function getPatientMaterialByPatientId($id) {

    $this->db->where('patient', $id);

    $query = $this->db->get('patient_material');

    return $query->result();

}



// 26-01-2022 sunil

function otp_exists($userid)

{

    $userid=$userid;

    $query = $this->db->query("SELECT `id`,`otp`

                   FROM `users` 

                   WHERE  `id`='$userid'");

    return $query->result();

 

}



function getdocument($id) {

    $this->db->where('patient', $id);
    $this->db->order_by('id','desc');
    $query = $this->db->get('patient_material');

    $data=$query->result();

    $count=0;

    foreach ($data as $d) {

        $data[$count]->date=date('Y-m-d', $d->date);

        $data[$count]->time=date('H:i', $d->date);

        $count++;

    }

    return $data;

}



function getAllPatient() {

    $this->db->select('
    patient.id as patient_id,
    patient.ion_user_id as user_id,
    patient.name as patient_name,
    patient.img_url as patient_profile_photo, 
    patient.email as patient_email, 
    patient.phone as patient_phone, 
    patient.address as patient_address, 
    patient.age as patient_age, 
    patient.weight as patient_weight, 
    patient.merital_status as patient_merital_status, 
    patient.spouse_name as patient_spouse_name, 
    users.deviceToken as patient_device_token, 
    doctor.id as doctor_id,
    doctor.name as doctorname,
    appointment.id as appointment_id,
    appointment.s_time as starttime,
    appointment.e_time as endtime,
    appointment.time_slot as time_slot, 
    appointment.remarks as remarks ,
    appointment.registration_time as created_at , 
    appointment.patient_type as patient_type, 
    appointment.mode_of_consultation as mode_of_consultation, 
    appointment.type_of_consultation as type_of_consultation, 
    appointment.date as booking_date,
    appointment.status as status_id, 
    appointment_status.status_name as status,
    location.id as location_id,
    location.name as location_name,
    payment.id as payment_id,
    payment.txnID as payment_txnID,
    payment.amount as payment_amount,
    payment.order_status as payment_order_status');

   //$this->db->where('patient.id', $id);

    $this->db->join('appointment','appointment.patient=patient.id');
    $this->db->join('users','users.id=patient.ion_user_id');

    $this->db->join('appointment_status','appointment_status.id=appointment.status','left');

    $this->db->join('location','location.id=appointment.location_id' ,'left');

   // $this->db->join('appointment','appointment.doctor=doctor.id');

    $this->db->join('payment','payment.booking_id=appointment.id','left');
    $this->db->join('doctor','doctor.ion_user_id=709');
    $this->db->order_by('appointment.id', 'desc');
    $query = $this->db->get('patient');

   return $query->result();

  //echo  $this->db->last_query();   

}

function getMedicalHistoryByAllPatientId($id) {

    $this->db->where('patient_id', $id);

   // $this->db->join('payment','payment.patient=medical_history.patient_id');

    //$this->db->join('location','location.id=appointment.location_id');

    //$this->db->join('appointment','appointment.patient=medical_history.patient_id');

    $query = $this->db->get('medical_history');

    $data=$query->result();

    $count=0;

    foreach ($data as $d) {

        $data[$count]->date=date('d-m-Y', $d->date);
        $data[$count]->registration_time=date('d-m-Y H:i A', $d->registration_time);

        $count++;

    }

    return $data;

}



function getPrescriptionByAllPatientId($patient_id) {

    $this->db->order_by('id', 'desc');

    $this->db->where('patient', $patient_id);

    $query = $this->db->get('prescription');

    $data=$query->result();

    // $count=0;

    // foreach ($data as $d) {

    //     $data[$count]->date=date('d-m-Y', $d->date);

    //     $count++;

    // }

    return $data;

}



function getLabByAllId($id) {

    $this->db->where('patient', $id);
    $this->db->order_by('id', 'desc');
    $query = $this->db->get('lab');
    

    $data=$query->result();

    return $data;

}



public function updatePassword($new_password, $userid){

    $data = array(

        'password'=> md5($new_password)

        );

        return $this->db->where('id', $userid)

                        ->update('users', $data);





}



    public function check_email_exists($email){

        $query = $this->db->query("SELECT `id`,`email`

                    FROM `users` 

                    WHERE  `email`='$email'");

        return $query->result_array();

    }

    public function insertScedule($data){

        $query = $this->db->query("SELECT `id`

        FROM `time_slot`

        WHERE  `doctor` = '" . $data['doctor'] . "' and `s_time`='" . $data['s_time'] . "' and `e_time` = '" . $data['e_time'] . "' and `weekday` = '" . $data['weekday'] . "' and `location_id` = '" . $data['location_id'] . "' ");



        $val = $query->row();



        if (count($val) == 0) {

            return $this->db->insert('time_slot', $data);

        } else {

            return 0;

        }



    }





}



