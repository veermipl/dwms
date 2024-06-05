<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Appointment_model extends CI_model
{



    function __construct()
    {

        parent::__construct();

        $this->load->database();
    }



    function insertAppointment($data)
    {



        $this->db->insert('appointment', $data);
    }



    function getAppointment()
    {

        $this->db->order_by('id', 'desc');

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getAppointmentByyToday()
    {

        $today = strtotime(date('Y-m-d'));



        $this->db->where('date', $today);

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {



        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentForCalendar()
    {

        $this->db->order_by('id', 'asc');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentRequest()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('request', 'Yes');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentRequestByDoctor($doctor)
    {

        $this->db->where('request', 'Yes');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByPatient($patient)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('patient', $patient);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByStatus($status)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', $status);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentByStatusByDoctor($status, $doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', $status);

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentById($id)
    {

        $this->db->where('id', $id);

        $query = $this->db->get('appointment');

        return $query->row();
    }

    // mohit code 04June2024
    function getAppointmentByIdOrDoctorId($id, $patient_id)
    {

        if ($this->ion_auth->in_group(array('admin'))) {
            $doctor  = $this->ion_auth->get_user_id() ?? 0;
            if ($doctor) {
                $this->db->where(['id' => $id, 'patient' => $patient_id]);
                $query = $this->db->get('appointment');
                return $query->row();
            } else {
                return false;
            }
        }
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $doctor  = $this->ion_auth->get_user_id() ?? 0;

            if ($doctor) {
                $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $doctor))->row()->id;
                $this->db->where(['id' => $id, 'patient' => $patient_id, 'doctor' => $doctor_id]);
                $query = $this->db->get('appointment');
                return $query->row();
            } else {
                return false;
            }
        }
    }

    function updateAppointmentByIdOrDoctorId($id, $patient_id, $doctor_id, $data)
    {
        $this->db->where(['id' => $id, 'patient' => $patient_id, 'doctor' => $doctor_id]);
        $this->db->update('appointment', $data);
        return true;
    }

    // mohit code 04June2024



    function getAppointmentByDate($date_from, $date_to)
    {

        $this->db->select('*');

        $this->db->from('appointment');

        $this->db->where('date >=', $date_from);

        $this->db->where('date <=', $date_to);

        $query = $this->db->get();

        return $query->result();
    }



    function getAppointmentByDoctorByToday($doctor_id)
    {

        $today = date('d-m-Y');

        $this->db->where('doctor', $doctor_id);

        $this->db->where('date', $today);

        $query = $this->db->get('appointment');

        // echo $this->db->last_query();
        return $query->result();
    }



    function updateAppointment($id, $data)
    {

        $this->db->where('id', $id);

        $this->db->update('appointment', $data);
    }



    function delete($id)
    {

        $this->db->where('id', $id);

        $this->db->delete('appointment');
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



    function getRequestAppointment()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Requested');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '5');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '5');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '5');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '5');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointment()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', '1');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '1');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '1');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '1');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '1');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getConfirmedAppointment()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', '7');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '2');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '2');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '2');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '2');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getTreatedAppointment()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Treated');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '3');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '3');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '3');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '3');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCancelledAppointment()
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Cancelled');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '4');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '4');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '4');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '4');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentListByDoctor($doctor)
    {

        $this->db->where('doctor', $doctor);

        $this->db->order_by('id', 'desc');

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentListByDoctorWithoutSearch($doctor, $order, $dir)
    {

        $this->db->where('doctor', $doctor);

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentListBySearchByDoctor($doctor, $search, $order, $dir)
    {

        $this->db->where('doctor', $doctor);

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentListByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        $this->db->where('doctor', $doctor);

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getAppointmentListByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {

        $this->db->where('doctor', $doctor);



        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRequestAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Requested');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '5');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '5')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getNoShowAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '5');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getNoShowAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '5')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getCancelledAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Cancelled');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '4');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '4')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getCompletedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '4');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCompletedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '4')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getPendingAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', '1');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '1');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '1')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getPendingAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '1');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPendingAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '1')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getTreatedAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', 'Treated');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '3');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '3')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getRejectedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '3');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getRejectedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '3')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getConfirmedAppointmentByDoctor($doctor)
    {

        $this->db->order_by('id', 'desc');

        $this->db->where('status', '7');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '2');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '2')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getApprovedAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '2');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getApprovedAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '2')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getPostponeAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '6');

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getPostponeAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '6');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPostponeAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '6');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPostponeAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '6');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPostponeAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '6');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getPostponeAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '6')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getPostponeAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '6');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getPostponeAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '6')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }




    function getConfirmAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '7');

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getConfirmAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '7');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getConfirmAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '7');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getConfirmAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '7');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getConfirmAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '7');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getConfirmAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '7')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getConfirmAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '7');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getConfirmAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '7')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }

    function getCancelledAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '8');

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getCancelledAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '8');

        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCancelledAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '8');

        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCancelledAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '8');

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCancelledAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('status', '8');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getCancelledAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '8')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getCancelledAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('status', '8');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getCancelledsAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '8')

            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getVipAppointmentWithoutSearch($order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('type_of_consultation', '6');
        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');


        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getVipAppointmentByDoctorWithoutSearch($doctor, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');


        $this->db->where('doctor', $doctor);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getVipAppointmentBySearch($search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');


        $this->db->like('id', $search);

        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getVipAppointmentByLimit($limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');


        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getVipAppointmentByLimitBySearch($limit, $start, $search, $order, $dir)
    {

        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');

        $this->db->like('id', $search);



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }



        $this->db->or_like('app_time_full_format', $search);

        $this->db->or_like('patientname', $search);

        $this->db->or_like('doctorname', $search);



        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }

    function getVipAppointmentBySearchByDoctor($doctor, $search, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '6')
            ->where('type_of_consultation', 'VIP In Clinic')
            ->or_where('type_of_consultation', 'VIP Online Consultation')


            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }



    function getVipAppointmentByLimitByDoctor($doctor, $limit, $start, $order, $dir)
    {

        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->where('type_of_consultation', 'VIP In Clinic');
        $this->db->or_where('type_of_consultation', 'VIP Online Consultation');

        $this->db->where('doctor', $doctor);

        $this->db->limit($limit, $start);

        $query = $this->db->get('appointment');

        return $query->result();
    }



    function getVipAppointmentByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir)
    {



        if ($order != null) {

            $this->db->order_by($order, $dir);
        } else {

            $this->db->order_by('id', 'desc');
        }

        $this->db->limit($limit, $start);

        $query = $this->db->select('*')

            ->from('appointment')

            ->where('status', '6')
            ->where('type_of_consultation', 'VIP In Clinic')
            ->or_where('type_of_consultation', 'VIP Online Consultation')


            ->where('doctor', $doctor)

            ->where("(id LIKE '%" . $search . "%' OR app_time_full_format LIKE '%" . $search . "%' OR patientname LIKE '%" . $search . "%' OR doctorname LIKE '%" . $search . "%')", NULL, FALSE)

            ->get();

        return $query->result();
    }

    function getAllPostponedAppointments()
    {

        $this->db->select('*');
        $this->db->from('cancellation_request as t1');
        $this->db->join('appointment as t2', 't1.appointment_id = t2.id', 'LEFT');
        $this->db->where('t1.flag', '0');
        $this->db->where('t1.type', '6');
        // $this->db->where('t2.status','7');

        $query = $this->db->get();
        return $query->result();
    }

    function getAllCancelledAppointments()
    {

        $this->db->select('*');
        $this->db->from('cancellation_request as t1');
        $this->db->join('appointment as t2', 't1.appointment_id = t2.id', 'LEFT');
        $this->db->where('t1.flag', '0');
        $this->db->where('t1.type', '8');
        // $this->db->where('t2.status','7');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCancelledAppointmentsByPending()
    {

        $this->db->select('*');
        $this->db->select('t1.id as c_id');
        $this->db->from('cancellation_request as t1');
        $this->db->join('appointment as t2', 't1.appointment_id = t2.id', 'LEFT');
        $this->db->where('t1.flag', '0');
        $this->db->where('t1.type', '8');
        $this->db->order_by('c_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllPostponedAppointmentsByPending()
    {

        $this->db->select('*');
        $this->db->select('t1.id as c_id');
        $this->db->from('cancellation_request as t1');
        $this->db->join('appointment as t2', 't1.appointment_id = t2.id', 'LEFT');
        $this->db->where('t1.flag', '0');
        $this->db->where('t1.type', '6');
        $this->db->order_by('c_id', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    function getAllCancelledAppointmentsByHandled()
    {

        $this->db->select('*');
        $this->db->select('t1.id as c_id');
        $this->db->from('cancellation_request as t1');
        $this->db->join('appointment as t2', 't1.appointment_id = t2.id', 'LEFT');
        $this->db->where('t1.flag', '1');
        $this->db->where('t1.type', '8');
        // $this->db->where('t2.status','7');
        $query = $this->db->get();
        return $query->result();
    }

    function cancelConfirmApp($id, $data)
    {

        $this->db->where('id', $id);

        $this->db->insert('cancellation_request', $data);

        // echo $this->db->last_query(); exit;

    }

    function cancelConfirmAppByPending($aid, $sdata)
    {

        $this->db->where('id', $aid);

        $this->db->update('appointment', $sdata);
    }


    function PendingHandleApp($id, $data)
    {

        $this->db->where('id', $id);

        $this->db->update('cancellation_request', $data);
        // echo $this->db->last_query(); exit;

    }
}
