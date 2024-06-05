<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Schedule_model extends CI_model {

        function __construct() {
            parent::__construct();
            $this->load->database();
        }

        function getSchedule($radio1) {
            // echo $radio1;
            if($radio1!=""){
            $query = $this->db->where('weekday',$radio1);
            }
            
            $query = $this->db->get('time_slot');
            
            // echo $this->db->last_query();
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

        function getAvailableDoctorByDate($date) {

            $weekday = strftime("%A", $date);
            $this->db->where('date', $date);
            $query1 = $this->db->get('holidays')->result();
            if (!empty($query1)) {
                $doctor = array();
                foreach ($query1 as $q1) {
                    $doctor[] = $q1->doctor;
                }
                $this->db->where_not_in('id', $staff);
            }

            $query = $this->db->get('doctor')->result();
            foreach ($query as $availableDoctor) {
                $this->db->where('doctor', $availableDoctor->id);
                $this->db->where('weekday', $weekday);
                $query_slot = $this->db->get('time_slot')->result();

                if (!empty($query_slot)) {
                    $doctor_avail[] = $availableDoctor->id;
                }
            }
            $this->db->where_in('id', $doctor_avail);
            $query_avail_doctor = $this->db->get('doctor');
            return $query_avail_doctor->result();
        }

        function getAvailableDoctorsByDateBySlot($date, $slot) {

            $weekday = strftime("%A", $date);
            $this->db->where('date', $date);
            $query1 = $this->db->get('holidays')->result();
            if (!empty($query1)) {
                $doctor = array();
                foreach ($query1 as $q1) {
                    $doctor[] = $q1->doctor;
                }
                $this->db->where_not_in('id', $doctor);
            }

            $query = $this->db->get('doctor')->result();
            foreach ($query as $availableDoctor) {
                $this->db->where('doctor', $availableDoctor->id);
                $this->db->where('weekday', $weekday);
                $query_slot = $this->db->get('time_slot')->result();

                if (!empty($query_slot)) {
                    $doctor_avail[] = $availableDoctor->id;
                }
            }

            foreach ($doctor_avail as $key => $value) {
                $this->db->where('doctor', $value);
                $this->db->where('date', $date);
                $this->db->where('time_slot', $slot);
                $query_appointment = $this->db->get('appointment')->result();

                if (empty($query_appointment)) {
                    $most_probable_avail_doctor[] = $value;
                }
            }
            $this->db->where_in('id', $most_probable_avail_doctor);
            $query_avail_doctor = $this->db->get('staff');
            return $query_avail_doctor->result();
        }

        function getAvailableSlotByDoctorByDate($date, $doctor) {
            //$newDate = date("m-d-Y", strtotime($date));
            $weekday = strftime("%A", $date);

            $this->db->where('date', $date);
            $this->db->where('doctor', $doctor);
            $holiday = $this->db->get('holidays')->result();

            if (empty($holiday)) {
                $this->db->where('date', $date);
                $this->db->where('doctor', $doctor);
                $query = $this->db->get('appointment')->result();


                $this->db->where('doctor', $doctor);
                $this->db->where('weekday', $weekday);
                $this->db->order_by('s_time_key', 'asc');
                $query1 = $this->db->get('time_slot')->result();

                $availabletimeSlot = array();
                $bookedTimeSlot = array();

                foreach ($query1 as $timeslot) {
                    $availabletimeSlot[] = $timeslot->s_time . ' To ' . $timeslot->e_time;
                }
                foreach ($query as $bookedTime) {
                    if ($bookedTime->status != 'Cancelled') {
                        $bookedTimeSlot[] = $bookedTime->time_slot;
                    }
                }

                $availableSlot = array_diff($availabletimeSlot, $bookedTimeSlot);
            } else {
                $availableSlot = array();
            }

            return $availableSlot;
        }

        function getAvailableSlotByDoctorByDateByAppointmentId($date, $doctor, $appointment_id) {
           
            $weekday = strftime("%A", $date);

            $this->db->where('date', $date);
            $this->db->where('doctor', $doctor);
            $holiday = $this->db->get('holidays')->result();

            if (empty($holiday)) {

                $this->db->where('date', $date);
                $this->db->where('doctor', $doctor);
                $query = $this->db->get('appointment')->result();


                $this->db->where('doctor', $doctor);
                $this->db->where('weekday', $weekday);
                $this->db->order_by('s_time_key', 'asc');
                $query1 = $this->db->get('time_slot')->result();

                $availabletimeSlot = array();
                $bookedTimeSlot = array();

                foreach ($query1 as $timeslot) {
                    $availabletimeSlot[] = $timeslot->s_time . ' To ' . $timeslot->e_time;
                }
                foreach ($query as $bookedTime) {
                    if ($bookedTime->status != 'Cancelled') {
                        if ($bookedTime->id != $appointment_id) {
                            $bookedTimeSlot[] = $bookedTime->time_slot;
                        }
                    }
                }

                $availableSlot = array_diff($availabletimeSlot, $bookedTimeSlot);
            } else {
                $availableSlot = array();
            }

            return $availableSlot;
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

        function getDoctorByIonUserId($id) {
            $this->db->where('ion_user_id', $id);
            $query = $this->db->get('doctor');
            return $query->row();
        }

        function insertTimeSlot($data) {
            $this->db->insert('time_slot', $data);
        }

        function getTimeSlot() {
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function getTimeSlotById($id) {
            $this->db->where('id', $id);
            $query = $this->db->get('time_slot');
            return $query->row();
        }

        function getTimeSlotByDoctor($id) {
            $this->db->order_by('s_time_key', 'asc');
            $this->db->where('doctor', $id);
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function updateTimeSlot($id, $data) {
            $this->db->where('id', $id);
            $this->db->update('time_slot', $data);
        }

        function deleteTimeSlot($id) {
            $this->db->where('id', $id);
            $this->db->delete('time_slot');
        }

        function insertSchedule($data) {
            $this->db->insert('time_slot', $data);
        }

        function insertLocation($data){
            // print_r($data); die;
            $this->db->insert('location', $data);
         }

        function updateLocation($id, $data) {
            $this->db->where('id', $id);
            $this->db->update('location', $data);
        }

        function getLocationById($id)
        {
             $this->db->where('id', $id);
            $query = $this->db->get('location');
            return $query->row();
        }

        
        function deleteLocation($id) {
            $this->db->where('id', $id);
            $this->db->delete('location');
        }

        function getScheduleByDoctor($doctor,$radio1) {
            $this->db->where('doctor', $doctor);
            if($radio1!=""){
            $this->db->where('weekday',$radio1);
            }
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function getScheduleById($id) {
            $this->db->where('id', $id);
            $query = $this->db->get('time_slot');
            return $query->row();
        }


        function getScheduleByDoctorByWeekday($doctor, $weekday, $location, $s_time, $e_time) {

            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $this->db->where('location_id', $location);
            $this->db->where('s_time', $s_time);
            $this->db->where('weekday', $e_time);
            $query = $this->db->get('time_slot');
            return $query->result();
        }
        

        function getScheduleByDoctorByWeekdayById($doctor, $weekday, $id) {
            $this->db->where_not_in('id', $id);
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function updateSchedule($id, $data) {
            $this->db->where('id', $id);
            $this->db->update('time_slot', $data);
        }

       

        function deleteSchedule($id) {
            $this->db->where('id', $id);
            $this->db->delete('time_slot');
        }

       
        function deleteTimeSlotByDoctorByWeekday($doctor, $weekday,$location,$s_time,$e_time) {
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $this->db->delete('time_slot');
        }

        function insertHoliday($data) {
            $this->db->insert('holidays', $data);
        }

        function getHolidays() {
            $query = $this->db->get('holidays');
            return $query->result();
        }

        function getHolidayById($id) {
            $this->db->where('id', $id);
            $query = $this->db->get('holidays');
            return $query->row();
        }

        function getHolidaysByDoctor($id) {
            $this->db->order_by('id', 'asc');
            $this->db->where('doctor', $id);
            $query = $this->db->get('holidays');
            return $query->result();
        }

        function getHolidayByDoctorByDate($doctor, $date) {
            $this->db->where('doctor', $doctor);
            $this->db->where('date', $date);
            $query = $this->db->get('holidays');
            return $query->row();
        }

        function getTimeSlotByDoctorByWeekday($doctor, $weekday) {
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function getTimeSlotByDoctorByWeekdayById($doctor, $weekday, $id) {
            $this->db->where_not_in('id', $id);
            $this->db->where('doctor', $doctor);
            $this->db->where('weekday', $weekday);
            $query = $this->db->get('time_slot');
            return $query->result();
        }

        function updateHoliday($id, $data) {
            $this->db->where('id', $id);
            $this->db->update('holidays', $data);
        }

        function deleteHoliday($id) {
            $this->db->where('id', $id);
            $this->db->delete('holidays');
        }



    }
