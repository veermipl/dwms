<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dispatches extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('dispatches_model');
        $this->load->model('medicine/medicine_model');
        $this->load->model('patient/patient_model');
        $this->load->model('doctor/doctor_model');
        if (!$this->ion_auth->in_group(array('admin', 'Pharmacist', 'Doctor', 'Patient', 'Nurse'))) {
            redirect('home/permission');
        }
    }

    public function index() {

        if ($this->ion_auth->in_group(array('Patient'))) {
            redirect('home/permission');
        }

        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();
        if ($this->ion_auth->in_group(array('Doctor'))) {
            $current_user = $this->ion_auth->get_user_id();
            $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
        }
        // $data['prescriptions'] = $this->dispatches_model->getPrescriptionByDoctorId($doctor_id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('prescription', $data);
        $this->load->view('home/footer'); 
    }

    function all() {

        if (!$this->ion_auth->in_group(array('admin', 'Doctor', 'Pharmacist'))) {
            redirect('home/permission');
        }

       //$data['medicines'] = $this->medicine_model->getMedicine();
       // $data['patients'] = $this->patient_model->getPatient();
       // $data['doctors'] = $this->doctor_model->getDoctor();
        $data['dispatches'] = $this->dispatches_model->getDispatches();
        //echo '<pre>'; print_r($data); die;
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('all_dispatches', $data);
        $this->load->view('home/footer'); 
    }


 function pending() {

        if (!$this->ion_auth->in_group(array('admin', 'Doctor', 'Pharmacist'))) {
            redirect('home/permission');
        }
        $data['dispatches'] = $this->dispatches_model->getDispatches();
        //echo '<pre>'; print_r($data); die;
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('all_pending_dispatches', $data);
        $this->load->view('home/footer'); 
    }
    public function addPrescriptionView() {

        if (!$this->ion_auth->in_group(array('admin', 'Doctor'))) {
            redirect('home/permission');
        }

        $data = array();
        $data['medicines'] = $this->medicine_model->getMedicine();
        $data['patients'] = $this->patient_model->getPatient();
        $data['doctors'] = $this->doctor_model->getDoctor();

        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('add_new_prescription_view', $data);
        $this->load->view('home/footer'); 
    }

    public function updateDispatches() {

        if (!$this->ion_auth->in_group(array('admin', 'Doctor'))) {
            redirect('home/permission');
        }

        $admin = $this->input->post('admin');
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $tracking_id = $this->input->post('tracking_id');
        $date = $this->input->post('date');
        $did = $this->input->post('did');
        $dispatches_id = $this->input->post('prescription_id');

        $data= array(
         'id'=> $did,
           'status'=> $status,
             'tracking_id'=> $tracking_id,
               'updated_by'=> $admin

            );

        $data2= array(
         'prescription_id'=> $dispatches_id,
           'status'=> $status

            );
       
//echo '<pre>';print_r($data); print_r($data2); die;
         $this->dispatches_model->updateDispatches($data);
         $this->dispatches_model->updateDispatchesByAdmin($data2);
 
         redirect('dispatches/all');

    }

    function viewPrescription() {
        $id = $this->input->get('id');
        $data['prescription'] = $this->dispatches_model->getDispatchesById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('prescription_view_1', $data);
        $this->load->view('home/footer'); 
    }

    function viewPrescriptionPrint() {
        $id = $this->input->get('id');
        $data['prescription'] = $this->dispatches_model->getDispatchesById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('prescription_view_print', $data);
        $this->load->view('home/footer'); 
    }

    function editDispatches() {
        $data = array();
        $id = $this->input->get('id');
    //echo  $id; die;
        $data['medicines'] = $this->medicine_model->getMedicine();
        $data['prescription'] = $this->dispatches_model->getDispatchesById($id);
      // print_r($data);
        $data['settings'] = $this->settings_model->getSettings();
        $data['patients'] = $this->patient_model->getPatientById($data['prescription']->patient);
        $data['doctors'] = $this->doctor_model->getDoctorById($data['prescription']->doctor);
        $this->load->view('home/dashboard', $data); 
        $this->load->view('add_new_prescription_view', $data);
        $this->load->view('home/footer'); 
    }

    function editDispatchesByJason() {
        $id = $this->input->get('id');
        $data['dispatches'] = $this->dispatches_model->getDispatchesById($id);
        echo json_encode($data);
    }

    function getPrescriptionByPatientIdByJason() {
        $id = $this->input->get('id');
        $prescriptions = $this->dispatches_model->getPrescriptionByPatientId($id);
        foreach ($prescriptions as $prescription) {
            $lists[] = ' <div class="pull-left prescription_box" style = "padding: 10px; background: #fff;"><div class="prescription_box_title">Prescription Date</div> <div>' . date('d-m-Y', $prescription->date) . '</div> <div class="prescription_box_title">Medicine</div> <div>' . $prescription->medicine . '</div> </div> ';
        }
        $data['prescription'] = $lists;
        $lists = NULL;
        echo json_encode($data);
    }

    function delete() {
        $id = $this->input->get('id');
        $admin = $this->input->get('admin');
        $patient = $this->input->get('patient');
        $this->dispatches_model->deletePrescription($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        if (!empty($patient)) {
            redirect('patient/caseHistory?patient_id=' . $patient);
        } elseif (!empty($admin)) {
            redirect('prescription/all');
        } else {
            redirect('prescription');
        }
    }

    public function prescriptionCategory() {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
        $data['categories'] = $this->dispatches_model->getPrescriptionCategory();
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('prescription_category', $data);
        $this->load->view('home/footer'); 
    }

    public function addCategoryView() {
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('add_new_category_view');
        $this->load->view('home/footer'); 
    }

    public function addNewCategory() {
        $id = $this->input->post('id');
        $category = $this->input->post('category');
        $description = $this->input->post('description');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
      
        $this->form_validation->set_rules('category', 'Category', 'trim|required|min_length[1]|max_length[100]|xss_clean');
      
        $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[1]|max_length[100]|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $data['settings'] = $this->settings_model->getSettings();
            $this->load->view('home/dashboard', $data); 
            $this->load->view('add_new_category_view');
            $this->load->view('home/footer'); 
        } else {
            $data = array();
            $data = array('category' => $category,
                'description' => $description
            );
            if (empty($id)) {
                $this->dispatches_model->insertPrescriptionCategory($data);
                $this->session->set_flashdata('feedback', lang('added'));
            } else {
                $this->dispatches_model->updatePrescriptionCategory($id, $data);
                $this->session->set_flashdata('feedback', lang('updated'));
            }
            redirect('prescription/prescriptionCategory');
        }
    }

    function edit_category() {
        $data = array();
        $id = $this->input->get('id');
        $data['prescription'] = $this->dispatches_model->getPrescriptionCategoryById($id);
        $data['settings'] = $this->settings_model->getSettings();
        $this->load->view('home/dashboard', $data); 
        $this->load->view('add_new_category_view', $data);
        $this->load->view('home/footer'); 
    }

    function editPrescriptionCategoryByJason() {
        $id = $this->input->get('id');
        $data['prescriptioncategory'] = $this->dispatches_model->getPrescriptionCategoryById($id);
        echo json_encode($data);
    }

    function deletePrescriptionCategory() {
        $id = $this->input->get('id');
        $this->dispatches_model->deletePrescriptionCategory($id);
        $this->session->set_flashdata('feedback', lang('deleted'));
        redirect('prescription/prescriptionCategory');
    }

    // function getPrescriptionListByDoctor() {
    //     $requestData = $_REQUEST;
    //     $start = $requestData['start'];
    //     $limit = $requestData['length'];
    //     $search = $this->input->post('search')['value'];
    //     $doctor_ion_id = $this->ion_auth->get_user_id();
    //     $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row()->id;

    //     $order = $this->input->post("order");
    //     $columns_valid = array(
    //         "0" => "id",
    //         "1" => "date",
    //     );
    //     $values = $this->settings_model->getColumnOrder($order, $columns_valid);
    //     $dir = $values[0];
    //     $order = $values[1];


    //     if ($limit == -1) {
    //         if (!empty($search)) {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionBysearchByDoctor($doctor, $search, $order, $dir);
    //         } else {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionByDoctorWithoutSearch($doctor, $order, $dir);
    //         }
    //     } else {
    //         if (!empty($search)) {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionByLimitBySearchByDoctor($doctor, $limit, $start, $search, $order, $dir);
    //         } else {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionByLimitByDoctor($doctor, $limit, $start, $order, $dir);
    //         }
    //     }


       
    //     $i = 0;
    //     $option1 = '';
    //     $option2 = '';
    //     $option3 = '';
    //     foreach ($data['prescriptions'] as $prescription) {
          
    //         $settings = $this->settings_model->getSettings();

    //         $option1 = '<a class="btn btn-info btn-xs btn_width" href="prescription/viewPrescription?id=' . $prescription->id . '"><i class="fa fa-eye">' . lang('view') . ' ' . lang('prescription') . ' </i></a>';
    //         $option3 = '<a class="btn btn-info btn-xs btn_width" href="prescription/editPrescription?id=' . $prescription->id . '" data-id="' . $prescription->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . ' ' . lang('prescription') . '</a>';
    //         $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="prescription/delete?id=' . $prescription->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
    //         $options4 = '<a class="btn btn-info btn-xs invoicebutton" title="' . lang('print') . '" style="color: #fff;" href="prescription/viewPrescriptionPrint?id=' . $prescription->id . '"target="_blank"> <i class="fa fa-print"></i> ' . lang('print') . '</a>';

    //         if (!empty($prescription->medicine)) {
    //             $medicine = explode('###', $prescription->medicine);
    //             $medicinelist = '';
    //             foreach ($medicine as $key => $value) {
    //                 $medicine_id = explode('***', $value);
    //                 $medicine_name_with_dosage = $this->medicine_model->getMedicineById($medicine_id[0])->name . ' -' . $medicine_id[1];
    //                 $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
    //                 rtrim($medicine_name_with_dosage, ',');
    //                 $medicinelist .= '<p>' . $medicine_name_with_dosage . '</p>';
    //             }
    //         } else {
    //             $medicinelist = '';
    //         }
    //         $patientdetails = $this->patient_model->getPatientById($prescription->patient);
    //         if (!empty($patientdetails)) {
    //             $patientname = $patientdetails->name;
    //         } else {
    //             $patientname = $prescription->patientname;
    //         }
    //         $info[] = array(
    //             $prescription->id,
    //             date('d-m-Y', $prescription->date),
    //             $patientname,
    //             $prescription->patient,
    //             $medicinelist,
    //             $option1 . ' ' . $option3 . ' ' . $option2 . ' ' . $options4
    //         );
    //         $i = $i + 1;
    //     }

    //     if ($data['prescriptions']) {
    //         $output = array(
    //             "draw" => intval($requestData['draw']),
    //             "recordsTotal" => $i,
    //             "recordsFiltered" => $i,
    //             "data" => $info
    //         );
    //     } else {
    //         $output = array(
              
    //             "recordsTotal" => 0,
    //             "recordsFiltered" => 0,
    //             "data" => []
    //         );
    //     }

    //     echo json_encode($output);
    // }

    // function getPrescriptionList() {
    //     $requestData = $_REQUEST;
    //     $start = $requestData['start'];
    //     $limit = $requestData['length'];
    //     $search = $this->input->post('search')['value'];

    //     $order = $this->input->post("order");
    //     $columns_valid = array(
    //         "0" => "id",
    //         "1" => "date",
    //     );
    //     $values = $this->settings_model->getColumnOrder($order, $columns_valid);
    //     $dir = $values[0];
    //     $order = $values[1];

    //     if ($limit == -1) {
    //         if (!empty($search)) {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionBysearch($search, $order, $dir);
    //         } else {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionWithoutSearch($order, $dir);
    //         }
    //     } else {
    //         if (!empty($search)) {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionByLimitBySearch($limit, $start, $search, $order, $dir);
    //         } else {
    //             $data['prescriptions'] = $this->dispatches_model->getPrescriptionByLimit($limit, $start, $order, $dir);
    //         }
    //     }

    //     $i = 0;
    //     $option1 = '';
    //     $option2 = '';
    //     $option3 = '';
    //     foreach ($data['prescriptions'] as $prescription) {
           
    //         $settings = $this->settings_model->getSettings();

    //         $option1 = '<a title="' . lang('view') . ' ' . lang('prescription') . '" class="btn btn-info btn-xs btn_width" href="prescription/viewPrescription?id=' . $prescription->id . '"><i class="fa fa-eye"> ' . lang('view') . ' ' . lang('prescription') . ' </i></a>';
    //         $option3 = '<a class="btn btn-info btn-xs btn_width" href="prescription/editPrescription?id=' . $prescription->id . '" data-id="' . $prescription->id . '"><i class="fa fa-edit"></i> ' . lang('edit') . ' ' . lang('prescription') . '</a>';
    //         $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="prescription/delete?id=' . $prescription->id . '&admin=' . $prescription->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
    //         $options4 = '<a class="btn btn-info btn-xs invoicebutton" title="' . lang('print') . '" style="color: #fff;" href="prescription/viewPrescriptionPrint?id=' . $prescription->id . '"target="_blank"> <i class="fa fa-print"></i> ' . lang('print') . '</a>';

    //         if (!empty($prescription->medicine)) {
    //             $medicine = explode('###', $prescription->medicine);
    //             $medicinelist = '';
    //             foreach ($medicine as $key => $value) {
    //                 $medicine_id = explode('***', $value);
    //                 $medicine_name_with_dosage = $this->medicine_model->getMedicineById($medicine_id[0])->name . ' -' . $medicine_id[1];
    //                 $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
    //                 rtrim($medicine_name_with_dosage, ',');
    //                 $medicinelist .= '<p>' . $medicine_name_with_dosage . '</p>';
    //             }
    //         } else {
    //             $medicinelist = '';
    //         }

    //         $patientdetails = $this->patient_model->getPatientById($prescription->patient);
    //         if (!empty($patientdetails)) {
    //             $patientname = $patientdetails->name;
    //         } else {
    //             $patientname = $prescription->patientname;
    //         }
    //         $doctordetails = $this->doctor_model->getDoctorById($prescription->doctor);
    //         if (!empty($doctordetails)) {
    //             $doctorname = $doctordetails->name;
    //         } else {
    //             $doctorname = $prescription->doctorname;
    //         }

    //         if ($this->ion_auth->in_group(array('Pharmacist', 'Receptionist'))) {
    //             $option2 = '';
    //             $option3 = '';
    //         }

    //         $info[] = array(
    //             $prescription->id,
    //             date('d-m-Y', $prescription->date),
    //             $doctorname,
    //             $patientname,
    //             $medicinelist,
    //             $option1 . ' ' . $option3 . ' ' . $options4 . ' ' . $option2
    //         );
    //         $i = $i + 1;
    //     }

    //     if ($data['prescriptions']) {
    //         $output = array(
    //             "draw" => intval($requestData['draw']),
    //             "recordsTotal" => $i,
    //             "recordsFiltered" => $i,
    //             "data" => $info
    //         );
    //     } else {
    //         $output = array(
    //             // "draw" => 1,
    //             "recordsTotal" => 0,
    //             "recordsFiltered" => 0,
    //             "data" => []
    //         );
    //     }

    //     echo json_encode($output);
    // }



     function getDispatchesList() {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "id",
            "1" => "date",
        );
        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order = $values[1];

        // if ($limit == -1) {
        //     if (!empty($search)) {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesBySearch($search, $order, $dir);
        //     } else {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesWithoutSearch($order, $dir);
        //     }
        // } else {
        //     if (!empty($search)) {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesByLimitBySearch($limit, $start, $search, $order, $dir);
        //     } else {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesByLimit($limit, $start, $order, $dir);
        //     }
        // }

        $i = 0;
        $option1 = '';
        $option2 = '';
        $option3 = '';
        $data['prescription'] = $this->dispatches_model->getDispatches();
      // echo '<pre>';   print_r($data['dispatches']); die;
        foreach ($data['prescription'] as $prescription) {
           
            $settings = $this->settings_model->getSettings();

            $option1 = '<a title="' . lang('view') . ' ' . lang('prescription') . '" class="btn btn-info btn-xs btn_width" href="dispatches/viewPrescription?id=' . $prescription->id . '"><i class="fa fa-eye"> ' . lang('view') . ' ' . 'Dispatched' . ' </i></a>';
            $option3 = '<a class="btn btn-info btn-xs btn_width" href="dispatches/editDispatches?id=' . $prescription->id . '&val=' . $prescription->dispatches_id . '" data-id="' . $prescription->dispatches_id.'"><i class="fa fa-edit"></i> ' . 'Update' . ' ' . 'Dispatched' . '</a>';
            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="dispatches/delete?id=' . $prescription->id . '&admin=' . $prescription->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $options4 = '<a class="btn btn-info btn-xs invoicebutton" title="' . lang('print') . '" style="color: #fff;" href="dispatches/viewPrescriptionPrint?id=' . $prescription->id . '"target="_blank"> <i class="fa fa-print"></i> ' . lang('print') . '</a>';
            $options5 = '<a class="btn btn-info btn-xs " title="' . 'Tracking' . '" style="color: #fff;" href="https://www.dtdct.in/tracking/tracking_results.asp?id=' . $prescription->tracking_id . '"target="_blank"> <i class="fa fa-truck"></i> ' . 'Tracking' . '</a>';
         
            // if (!empty($prescription->medicine)) {
            //     $medicine = explode('###', $prescription->medicine);
            //     $medicinelist = '';
            //     foreach ($medicine as $key => $value) {
            //         $medicine_id = explode('***', $value);
            //         $medicine_name_with_dosage = $this->medicine_model->getMedicineById($medicine_id[0])->name . ' -' . $medicine_id[1];
            //         $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
            //         rtrim($medicine_name_with_dosage, ',');
            //         $medicinelist .= '<p>' . $medicine_name_with_dosage . '</p>';
            //     }
            // } else {
            //     $medicinelist = '';
            // }

            $patientdetails = $this->patient_model->getPatientById($prescription->patient);
            if (!empty($patientdetails)) {
                $patientname = $patientdetails->name;
            } else {
                $patientname = $prescription->patientname;
            }
            $doctordetails = $this->doctor_model->getDoctorById($prescription->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $prescription->doctorname;
            }

            if ($this->ion_auth->in_group(array('Pharmacist', 'Receptionist'))) {
                $option2 = '';
                $option3 = '';
            }

            if($prescription->dispatches_status=='1'){

                $dispatches_status="Dispatched";

            }else {

               $dispatches_status="Not Dispatched";
            }

            $info[] = array(
                $prescription->id,
                $doctorname,
                $patientname,
                $dispatches_status,
                $prescription->tracking_id,
                date('d-m-Y', $prescription->date),
                $option1 . ' ' . $option3 . ' ' . $options4 . ' ' . $options5
            );
            $i = $i + 1;
        }

        if ($data['prescription']) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $i,
                "recordsFiltered" => $i,
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }


     function getDispatchesPendingList() {
        $requestData = $_REQUEST;
        $start = $requestData['start'];
        $limit = $requestData['length'];
        $search = $this->input->post('search')['value'];

        $order = $this->input->post("order");
        $columns_valid = array(
            "0" => "id",
            "1" => "date",
        );
        $values = $this->settings_model->getColumnOrder($order, $columns_valid);
        $dir = $values[0];
        $order = $values[1];

        // if ($limit == -1) {
        //     if (!empty($search)) {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesBySearch($search, $order, $dir);
        //     } else {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesWithoutSearch($order, $dir);
        //     }
        // } else {
        //     if (!empty($search)) {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesByLimitBySearch($limit, $start, $search, $order, $dir);
        //     } else {
        //         $data['dispatches'] = $this->dispatches_model->getDispatchesByLimit($limit, $start, $order, $dir);
        //     }
        // }

        $i = 0;
        $option1 = '';
        $option2 = '';
        $option3 = '';
        $data['prescription'] = $this->dispatches_model->getDispatchePending();
       echo '<pre>';   print_r($data['prescription']); die;
        foreach ($data['prescription'] as $prescription) {
           
            $settings = $this->settings_model->getSettings();

            $option1 = '<a title="' . lang('view') . ' ' . lang('prescription') . '" class="btn btn-info btn-xs btn_width" href="dispatches/viewPrescription?id=' . $prescription->id . '"><i class="fa fa-eye"> ' . lang('view') . ' ' . 'Dispatched' . ' </i></a>';
            $option3 = '<a class="btn btn-info btn-xs btn_width" href="dispatches/editDispatches?id=' . $prescription->id . '&val=' . $prescription->dispatches_id . '" data-id="' . $prescription->dispatches_id.'"><i class="fa fa-edit"></i> ' . 'Update' . ' ' . 'Dispatched' . '</a>';
            $option2 = '<a class="btn btn-info btn-xs btn_width delete_button" href="dispatches/delete?id=' . $prescription->id . '&admin=' . $prescription->id . '" onclick="return confirm(\'Are you sure you want to delete this item?\');"><i class="fa fa-trash"> </i></a>';
            $options4 = '<a class="btn btn-info btn-xs invoicebutton" title="' . lang('print') . '" style="color: #fff;" href="dispatches/viewPrescriptionPrint?id=' . $prescription->id . '"target="_blank"> <i class="fa fa-print"></i> ' . lang('print') . '</a>';
            $options5 = '<a class="btn btn-info btn-xs " title="' . 'Tracking' . '" style="color: #fff;" href="https://www.dtdct.in/tracking/tracking_results.asp?id=' . $prescription->tracking_id . '"target="_blank"> <i class="fa fa-truck"></i> ' . 'Tracking' . '</a>';
         
            // if (!empty($prescription->medicine)) {
            //     $medicine = explode('###', $prescription->medicine);
            //     $medicinelist = '';
            //     foreach ($medicine as $key => $value) {
            //         $medicine_id = explode('***', $value);
            //         $medicine_name_with_dosage = $this->medicine_model->getMedicineById($medicine_id[0])->name . ' -' . $medicine_id[1];
            //         $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
            //         rtrim($medicine_name_with_dosage, ',');
            //         $medicinelist .= '<p>' . $medicine_name_with_dosage . '</p>';
            //     }
            // } else {
            //     $medicinelist = '';
            // }

            $patientdetails = $this->patient_model->getPatientById($prescription->patient);
            if (!empty($patientdetails)) {
                $patientname = $patientdetails->name;
            } else {
                $patientname = $prescription->patientname;
            }
            $doctordetails = $this->doctor_model->getDoctorById($prescription->doctor);
            if (!empty($doctordetails)) {
                $doctorname = $doctordetails->name;
            } else {
                $doctorname = $prescription->doctorname;
            }

            if ($this->ion_auth->in_group(array('Pharmacist', 'Receptionist'))) {
                $option2 = '';
                $option3 = '';
            }

            if($prescription->dispatches_status=='1'){

                $dispatches_status="Dispatched";

            }else {

               $dispatches_status="Not Dispatched";
            }

            $info[] = array(
                $prescription->id,
                $doctorname,
                $patientname,
                $dispatches_status,
                $prescription->tracking_id,
                date('d-m-Y', $prescription->date),
                $option1 . ' ' . $option3 . ' ' . $options4 . ' ' . $options5
            );
            $i = $i + 1;
        }

        if ($data['prescription']) {
            $output = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => $i,
                "recordsFiltered" => $i,
                "data" => $info
            );
        } else {
            $output = array(
                // "draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            );
        }

        echo json_encode($output);
    }


}

/* End of file prescription.php */
/* Location: ./application/modules/prescription/controllers/prescription.php */
