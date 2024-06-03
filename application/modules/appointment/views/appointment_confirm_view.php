<?php
$current_user = $this->ion_auth->get_user_id();
if ($this->ion_auth->in_group('Doctor')) {
    $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
    $doctordetails = $this->db->get_where('doctor', array('id' => $doctor_id))->row();
}
?> 
<section id="main-content">
  <section class="wrapper site-min-height">
    <link href="common/extranal/css/prescription/add_new_prescription_view.css" rel="stylesheet">
    <section class="col-md-8">
      <header class="panel-heading"> <?php
               //print_r($prescription); 
                if (!empty($appointment->id))
                    echo 'View Appointment';
                ?> 
      </header>
            <div class="panel col-md-12">
                <div class="adv-table editable-table ">
                <div class="clearfix"> <?php echo validation_errors(); ?> 
                <form role="form" id="editAppointmentForms" action="appointment/appointment/markPostponeRequest" class="clearfix" method="post" enctype="multipart/form-data">
                    <div class="">
                        <div class="modal-body row">
                        <!-- <?php var_dump($appointment);?> -->
                        <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label> 
                        <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient); echo $patientdetails->name; ?>' placeholder="">

                        </div>

                        <div class="col-md-6 panel doctor_div1">
                            <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>
                             <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">

                        </div>

                        <div class="col-md-6 panel">
                            <label for="exampleInputEmail1"> Location </label>
                          <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php  $getLocation =  $this->doctor_model->getLocationById($appointment->location_id); echo $getLocation->name;?>' placeholder="">
                        </div>

                        <div class="col-md-6 panel">
                            <label for="exampleInputEmail1"> <?php echo 'Date - Time' ?> </label>
                            <input type="text" readonly="readonly" class="form-control default-date-picker" id="date1" readonly="" name="date" value='<?php echo $appointment->date?> <?php echo $appointment->s_time ?>-<?php echo $appointment->e_time ?>' placeholder="">
                        </div>

                        <div class="col-md-6 ">
                            <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?> </label>
                            <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php   echo $appointment->type_of_consultation;?>' placeholder="">
                        </div>

                        <div class="col-md-6">
                            <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?> </label>
                           <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php   echo $appointment->mode_of_consultation;?>' placeholder="">
                        </div>

                        <div class="col-md-6">
                            <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?> </label>
                            <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php  $getStatus =  $this->doctor_model->getStatusById($appointment->status); echo  $getStatus->status_name;?>' placeholder="">
                        </div>

                        <div class="col-md-6">
                            <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('type'); ?> </label>
                            <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php echo $appointment->patient_type;?>' placeholder="">
                        </div>
                      
                        <input type="hidden" name="id" id="appointment_id" value=''>
                        <div class="col-md-12 panel">
                         <br> <br>
                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                        <button type="button" class="btn btn-info btn-xs btn_width editedbutton" data-toggle="modal" data-id="<?php echo $appointment->id ?>" data-target="#myModal22"> Cancel</button>
                           &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;

                        <button type="button" class="btn btn-info btn-xs btn_width editedbutton" data-toggle="modal" data-id="<?php echo $appointment->id ?>" data-target="#myModal2"> Postpone</button>
                        
                        </div>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            
    </section>
    <!-- page end-->
  </section>
</section>
<!--main content end-->

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo lang('edit_appointment'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentForm" action="appointment/appointment/markPostponeRequest" class="clearfix" method="post" enctype="multipart/form-data">
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label> 
                        <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient); echo $patientdetails->name; ?>' placeholder="">

                    </div>
                   
                     <div class="col-md-6 panel doctor_div1">
                            <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>
                             <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">

                    </div>

                     <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date1" readonly="" name="date"  value='<?php echo $appointment->date?>' placeholder="">
                    </div>

                      <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?></label>
                        
                         <select type="text" class="form-control" name="mode_of_consultation" id="modes_of_consultation">
                        <option value="">Select Mode Of Consultation</option>
                        <?php

                        foreach($consultation as $row)
                        {
                        if($appointment->mode_of_consultation == $row->mode_of_consultation){
                              echo '<option value="'.$row->id.'" selected>'.$row->mode_of_consultation. '</option>';
                        }else{
                         echo '<option value="'.$row->id.'">'.$row->mode_of_consultation.'</option>';
                        }
                        }
                        ?>

                        </select>
                    </div>
                    
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>
                         <select type="text" class="form-control" name="types_of_consultation" id="type_of_consultation"  placeholder="">
                           
                        

                        </select>
                    </div>
                   


                    <div class="col-md-12 panel" id="location_id_div">
                    <label for="exampleInputEmail1"> Location </label> 
                    <select class="form-control m-bot15" name="location_id" id="location_id">   
                            <option value="">Please Select Location</option>
                            <?php 

                            foreach($location as $loc)
                            { 
                            if($appointment->location_id == $loc->id){
                              echo '<option value="'.$loc->id.'"selected>'.$loc->name. '</option>';
                            }else{
                                    echo '<option value="'.$loc->id.'">'.$loc->name. '</option>';
                            }
                            }
                            ?>

                        </select>
                    </div>
                   

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1">Available Slots</label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value='<?php echo $appointment->s_time ?>-<?php echo $appointment->e_time ?>'> 
                        </select>
                    </div>


                  

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label> 
                         <select class="form-control m-bot15" name="status" value=''> 
                            <option value="">Please select Status</option>
                            <?php 

                            $stat = $appointment->status; 

                            foreach($status as $row)
                            { 
                            if($stat == $row->id){
                              echo '<option value="'.$row->id.'"selected>'.$row->status_name. '</option>';
                            }else{
                                    echo '<option value="'.$row->id.'">'.$row->status_name. '</option>';
                            }
                            }
                            ?>
                           
                        </select>
                    </div>
                   
                    <input type="hidden" name="id" id="appointment_id" value=''>
                    <div class="col-md-12 panel">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModal22" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo 'Reason Cancel'; ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentForm" action="appointment/markCancellationRequest" class="clearfix" method="post" enctype="multipart/form-data">
                  
                 <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label> 
                        <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient); echo $patientdetails->name; ?>' placeholder="">

                    </div>
                   
                     <div class="col-md-6 panel doctor_div1">
                            <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>
                             <input type="text" readonly="readonly" class="form-control"  readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">

                    </div>

                    <div class="col-md-12 ">
                        <label for="exampleInputEmail1">Reason For Cancellation</label>
                        <input type="text" class="form-control" name="reasone" id="reasone" required="required">
                    </div>

                   
                   
                    <input type="hidden" name="id" id="appointment_id" value='<?php echo $appointment->id;?>'>
                    <div class="col-md-12 panel">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('cancel'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!--footer start-->
<script src="common/js/codearistos.min.js"></script>

<script type="text/javascript">
  var select_medicine = " < ? php echo lang('medicine'); ? > ";
</script>

<script type="text/javascript">
  var select_doctor = " < ? php echo lang('select_doctor'); ? > ";
</script>

<script type="text/javascript">
  var select_patient = " < ? php echo lang('select_patient'); ? > ";
</script>

<script type="text/javascript">
  var language = " < ? php echo $this - > language; ? > ";
</script>

<script src="common/extranal/js/appointment/appointment_view.js"></script>