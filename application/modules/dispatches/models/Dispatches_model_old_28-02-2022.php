<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatches_model extends CI_model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function insertPrescription($data) {
    $this->db->insert('prescription', $data);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
    }

   function insertDispatches($data) {
    $this->db->insert('dispatches', $data);
    $insert_id = $this->db->insert_id();
    return  $insert_id;
    }
    function getPrescription() {
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('prescription');
        return $query->result();
    }

  function getDispatches() {
        
        $query = $this->db->select("*");
        $query = $this->db->select("dispatches.id as dispatches_id");
        $query=  $this->db->join('prescription','prescription.id=dispatches.prescription_id');
        $query = $this->db->get('dispatches');
      // echo  $this->db->last_query();
        return $query->result();
    }

    function getDispatchePending() {
        
        $query = $this->db->select("*");
        $query = $this->db->select("dispatches.id as dispatches_id");
        $query=  $this->db->join('prescription','prescription.id=dispatches.prescription_id');
        $query = $this->db->where('prescription.dispatches_status','0');
        $query = $this->db->get('dispatches');
       //echo  $this->db->last_query(); die;
        return $query->result();
    }
    function getDispatchesWithoutSearch($order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $query = $this->db->get('dispatches');
        return $query->result();
    }

    function getDispatchesById($id) {
        $this->db->where('prescription_id', $id);
        $this->db->join('prescription', 'prescription.id=dispatches.prescription_id','left');
        $query = $this->db->get('dispatches');
        return $query->row();
    }

    function getPrescriptionByPatientId($patient_id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('patient', $patient_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByDoctorId($doctor_id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function updatePrescription($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('prescription', $data);
    }



function updateDispatches($data) {
    $this->db->where('id', $data['id']);
    $this->db->update('dispatches', $data);

   // echo $this->db->last_query();
    return;

    }
function updateDispatchesByAdmin($data) {
    $this->db->where('id', $data['prescription_id']);
    $this->db->set('dispatches_status',$data['status']);
    $this->db->update('prescription');
    return;

    }
    function deletePrescription($id) {
        $this->db->where('id', $id);
        $this->db->delete('prescription');
    }

    function getDispatchesBySearch($search, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->like('id', $search);
        //$this->db->or_like('patientname', $search);
        //$this->db->or_like('doctorname', $search);
        $query = $this->db->get('dispatches');
        return $query->result();
    }

    function getDispatchesByLimit($limit, $start, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get('dispatches');
        return $query->result();
    }

    function getDispatchesByLimitBySearch($limit, $start, $search, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->like('id', $search);
       // $this->db->or_like('patientname', $search);
       // $this->db->or_like('doctorname', $search);

        $this->db->limit($limit, $start);
        $query = $this->db->get('dispatches');
        return $query->result();
    }

    function getPrescriptionByDoctor($doctor_id) {
        $this->db->order_by('id', 'desc');
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByDoctorWithoutSearch($doctor_id, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        };
        $this->db->where('doctor', $doctor_id);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionBySearchByDoctor($doctor, $search, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->where('doctor', $doctor);
        $this->db->like('id', $search);
        $this->db->or_like('patientname', $search);
        $this->db->or_like('doctorname', $search);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByLimitByDoctor($doctor, $limit, $start, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->where('doctor', $doctor);
        $this->db->limit($limit, $start);
        $query = $this->db->get('prescription');
        return $query->result();
    }

    function getPrescriptionByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir) {
        if ($order != null) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('id', 'desc');
        }
        $this->db->like('id', $search);
        $this->db->where('doctor', $doctor);

        $this->db->or_like('patientname', $search);
        $this->db->or_like('doctorname', $search);

        $this->db->limit($limit, $start);
        $query = $this->db->get('prescription');
        return $query->result();
    }

}
