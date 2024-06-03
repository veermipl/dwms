09-02-2022
////////////////////////////////
shubham 

allPrescriptionbydoctor
allLabbydoctor
changepassword ->   currentpassword
                    newpasword

                    doctor profile update api
{
profileimage
doctorname
mobile
address
}
Response:
{
name
mobile
address 
profileimage
}
msg:Profile Updated Successfully!



/////////////////////////////////
chirag

logout
report

////////////////////////////
imran 

image url in login
function addAppointment() {
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    $data['title'] = "Add Appointment";
 
    $tables = $this->config->item('tables', 'ion_auth');

    //validate form input
    //$this->form_validation->set_rules('patientId','PatientID', 'required');
    $this->form_validation->set_rules('patient_id','Patient Required', 'required');
    $this->form_validation->set_rules('date', 'Select Date', 'required');
    $this->form_validation->set_rules('time_slot', 'Select Time Slot', 'required');
    $this->form_validation->set_rules('location', '', '');
    $this->form_validation->set_rules('doctor_id', 'Doctor Id', 'required');
    
    if ($this->form_validation->run() == true) {
        $patient_id = $this->input->post('patient_id');
        $location_id = $this->input->post('location');
        $date = $this->input->post('date');
         $time_slot = $this->input->post('time_slot');
        $time_slot_explode = explode('To', $time_slot);
        $s_time = trim($time_slot_explode[0]);
        $e_time = trim($time_slot_explode[1]);

   
        $additional_data = array(
            'patient' => $patient_id,
            'location_id' => $location_id,
            'date' => $this->input->post('date'),
            'time_slot' => $time_slot,
            'doctor' => $this->input->post('doctor_id'),
            's_time' => $s_time,
            'e_time' => $e_time,
            'status' => 3
        );
        
    }
    if ($this->form_validation->run() == true) {
        //check to see if we are creating the user
        //redirect them back to the admin page
     
			$doctor_id = $this->input->post('doctor_id');
            $this->db->where('id', $doctor_id);
            $query1 = $this->db->get('doctor');

            $datas = $query1->result_array();
            $doc_user_id = $datas[0]['ion_user_id'];

            $this->db->where('id', $doc_user_id);
            $query2 = $this->db->get('users');
            $datas_doc = $query2->result_array();

            $deviceToken = $datas_doc[0]['deviceToken'];
            $deviceType = $datas_doc[0]['deviceToken'];
            // Send Notification 

        $this->db->insert('appointment', $additional_data);
        echo json_encode(['data'=>$additional_data,'status'=>'true','message'=>'Appointment Booked Successfully']);
    } else {
        //display the create user form
        //set the flash data error message if there is one
        $message= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $blank_object=new stdClass();
        echo json_encode(['data'=>$blank_object,'status'=>'false','message'=>preg_replace("/\r|\n/", "", strip_tags($message))]);
    }
}
function  ApproveOrReject(){
    $content = trim(file_get_contents("php://input"));
    $_POST = json_decode($content, true);
    //2009-10-22
   $status= $_POST['status_id'];
   $appointment_id= $_POST['appointment_id'];
   $description= $_POST['description'];
    $datas = array(
        'status' => $status,'description' => $description
        );
        
   

    $this->db->update('appointment', $datas, array('id' => $appointment_id));
    $this->db->where('id',$appointment_id);
    $query = $this->db->get('appointment');
    $appointment_details = $query->result();

    $this->db->where('id',$appointment_details[0]->patient);
    $patient_query = $this->db->get('patient');
    $patient_data =  $patient_query->result();

    $this->db->where('id',$patient_data[0]->ion_user_id);
    $user_query = $this->db->get('users');
    $user_data =  $user_query->result();

    $this->db->where('id',$_POST['status_id']);
    $status_query = $this->db->get('appointment_status');
    $status_data =  $status_query->result();

    $this->db->where('id',$appointment_details[0]->location_id);
    $location_query = $this->db->get('location');
    $location_data =  $location_query->result();

    $data_result=array();
      $data_result['patientid']=$appointment_details[0]->patient;
      $data_result['patientname']=$patient_data[0]->name;
      $data_result['BookingDate']=$appointment_details[0]->date;
      $data_result['BookingTime']=$appointment_details[0]->time_slot;
      $data_result['LocationName']=$location_data[0]->name;
      $data_result['LocationId']=$appointment_details[0]->location_id;
      $data_result['statusId']=$appointment_details[0]->status;
      $data_result['StatusName']=$status_data[0]->status_name;
      $data_result['user_id']=$patient_data[0]->ion_user_id;
      $data_result['profile_img']=$patient_data[0]->img_url;
      

    $return = $this->db->affected_rows() == 1;
		if ($return){
            if ($appointment_details[0]->status==2) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $data_addedd = array
                (
                    'type'   => 'approve',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been successfully '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Now you can proceed to payment.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
            if ($appointment_details[0]->status==3) {
                $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Because '.$description.'.',
                );
                $data_addedd = array
                (
                    'type'   => 'reject',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your appointment #'.$appointment_details[0]->id .' has been '.$status_data[0]->status_name.' by Dr. Sudhir Bhola. Because '.$description.'.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd);  
            }
                  
        }

   echo json_encode(['data'=>$data_result,'status'=>"true",'message'=>"success"]);
}
public function proceed_order() {
        $content = trim(file_get_contents("php://input"));
        $_POST = json_decode($content, true);
        $patient_id =  $_POST['patient_id'];
        $doctor_id = $_POST['doctor_id'];
        $booking_id = $_POST['booking_id'];
        $amount = $_POST['amount'];
        $order_id = rand(111, 999) . substr(md5(time()), 5);
        $order_status = $_POST['order_status'];
        switch ($order_status) {
            case 1:
                $order_status_text = "pending";
                break;
            case 2:
                $order_status_text = "Approved";
                break;
                case 3:
                $order_status_text = "Reject";
                break;
                case 4:
                $order_status_text = "Completed";
                break;
                case 5:
                $order_status_text = "No Show";
                break;
                case 6:
                $order_status_text = "Postpone";
                break;
                case 7:
                $order_status_text = "Confirmed";
                break;
            default:
                $order_status_text = "pending";
                break;

        }
        $pay_via = $_POST['pay_via'];
        
        $array = array(
            'patient' => $patient_id,
            'doctor' => $doctor_id,
            'order_id' => $order_id,
            'amount' => $amount,
            'booking_Id' => $booking_id,
            'payment_type' => $pay_via,
            'order_status' => $order_status_text
           

        );
        $this->db->insert('payment',$array);
        $update_rows = array(
            'status' => $order_status

        );
        $this->db->where('id', $booking_id);
        $result = $this->db->update('appointment', $update_rows); 

        $this->db->where('id',$booking_id);
        $query = $this->db->get('appointment');
        $appointment_details = $query->result();

        $this->db->where('id',$appointment_details[0]->patient);
        $patient_query = $this->db->get('patient');
        $patient_data =  $patient_query->result();

        $this->db->where('id',$patient_data[0]->ion_user_id);
        $user_query = $this->db->get('users');
        $user_data =  $user_query->result();
        $this->db->where('id',$_POST['status_id']);
        $status_query = $this->db->get('appointment_status');
        $status_data =  $status_query->result();

        $deviceToken = $user_data[0]->deviceToken;
                $deviceType = $user_data[0]->deviceType;
            
                $messsage = array
                (
                    'title'   => 'Curewell Therapies',
                    'body'     => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                );
                $data_addedd = array
                (
                    'type'   => 'payment_success',
                    'id'     => $appointment_details[0]->id,
                    'patient_id'     => $appointment_details[0]->patient,
                    'msg'  => 'Your payment has been Successfully Processed Your Appointment #'.$appointment_details[0]->id .' has been Confirmed.',
                );
                $n_data = array();
                date_default_timezone_set("Asia/Kolkata"); 
                $n_data = array(
                    'user_id' => $patient_data[0]->ion_user_id,
                    'type' => $data_addedd['type'],
                    'message' => $data_addedd['msg'],
                    'profile_img' => $patient_data[0]->img_url,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                        
                $n_result=$this->db->insert('notification', $n_data);
                _send_fcm_notification($deviceToken,$deviceType,$messsage,$data_addedd); 
        echo json_encode(['data'=>$order_id,'status'=>"true",'message'=>"Order Successfully."]);
       
    }