
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
          <link href="common/extranal/css/appointment/appointment.css" rel="stylesheet">
        
        <section class="panel">
            <header class="panel-heading">
                <?php echo 'Cancellation Requests'; ?>
               
            </header>

            <div class="col-md-12">
                <header class="panel-heading tab-bg-dark-navy-blueee row">
                    <ul class="nav nav-tabs col-md-9">
                        
                       <li class="active">
                            <a data-toggle="tab" href="#1">Pending</a>
                       </li>

                        <li class="">
                            <a data-toggle="tab" href="#all"><?php echo 'Handled'; ?></a>
                        </li>
                       

                    </ul>

                    <div class="pull-right col-md-3"><div class="pull-right custom_buttonss"></div></div>

                </header>
            </div>


            <div class="">
                <div class="tab-content">
                    
                    <div id="all" class="tab-pane">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table">

                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="myTable">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></td>
                                                <th> <?php echo lang('date-time'); ?></th>
                                            <!--     <th> <?php echo lang('remarks'); ?></th> -->
                                             <th><?php echo "Consultation Type"; ?></th>
                                                <th> <?php echo "Consultation Mode"; ?></th>
                                               <th> <?php echo "Location"; ?></th>
                                               <th> <?php echo "Reason"; ?></th>
                                               <th> <?php echo "Created At" ?> </th>
                                               <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // echo "<pre>";
                                            // var_dump($appointment);
                                            foreach ($appointment as $value) 
                                            {
                                               
                                            ?>
                                            <tr>
                                            <td><?php echo $value->id;?> </td>
                                            <td>
                                                <?php 
                                                
                                                $patientdetails = $this->patient_model->getPatientById($value->patient);
                                           
                                                if (!empty($patientdetails)) {
                                                    $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $value->patient . '">' . $patientdetails->name . '</a>';
                                                } else {
                                                    $patientname = ' <a type="button" class="history" data-toggle = "modal" data-id="' . $value->patient . '"> ' . $value->patientname . '</a>';
                                                }
                                                echo  $patientname;
                                                ?>

                                            </td>
                                            <td> <?php echo $value->date ?>  <br> <?php echo $value->s_time ?>-<?php echo $value->e_time ?></td>
                                            <td> <?php echo $value->type_of_consultation;?></td>
                                            <td> <?php echo $value->mode_of_consultation;?></td>
                                            <td> <?php $getLocation =  $this->doctor_model->getLocationById($value->location_id); echo $getLocation->name;?> </td>
                                            <td> <?php echo $value->reasone;?> </td>
                                            <td> <?php 
                                                $mil = $value->created_at * 1000;
                                                $seconds = $mil / 1000;
                                                echo date("d/m/Y H:i:s", $seconds); 
                                            
                                            ?> </td>
                                            <td> <a class="btn btn-info btn-xs btn_width" href="appointment/viewAppointment?id=<?php echo $value->id?>"><?php echo "View Appointment"?> </i></a></td>
                                            </tr>
                                            <?php 

                                             }
                                             
                                             ?>
                                                 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="1" class="tab-pane active">
                        <div class="">
                            <div class="panel-body">
                                <div class="adv-table editable-table">

                                    <div class="space15"></div>
                                    <table class="table table-striped table-hover table-bordered" id="editable-samplepending">
                                        <thead>
                                            <tr>
                                                <th> <?php echo lang('id'); ?></th>
                                                <th> <?php echo lang('patient'); ?></th>
                                                <th> <?php echo lang('date-time'); ?></th>
                                            <!--     <th> <?php echo lang('remarks'); ?></th> -->
                                             <th><?php echo "Consultation Type"; ?></th>
                                                <th> <?php echo "Consultation Mode"; ?></th>
                                               <th> <?php echo "Location"; ?></th>
                                               <th> <?php echo "Reason"; ?></th>
                                               <th> <?php echo "Created At" ?> </th>
                                                <th> <?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                                 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                </div>
            </div>


        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<!-- Add Appointment Modal-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo lang('add_appointment'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" action="appointment/addNew" method="post" class="clearfix" enctype="multipart/form-data">
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label> 
                        <select class="form-control m-bot15 pos_select" id="pos_select" name="patient" value=''> 

                        </select>
                    </div>
                    <div class="pos_client clearfix col-md-6">
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label> 
                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('age'); ?></label> 
                            <input type="text" class="form-control pay_in" name="p_age" value='' placeholder="">
                        </div> 
                        <div class="payment pad_bot"> 
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('gender'); ?></label>
                            <select class="form-control" name="p_gender" value=''>

                                <option value="Male" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Male') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Male </option>   
                                <option value="Female" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Female') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Female </option>
                                <option value="Others" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Others') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Others </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6 panel doctor_div">
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label> 
                        <select class="form-control m-bot15" id="adoctors" name="doctor" value=''>  

                        </select>
                    </div>

                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> Location </label> 
                        <select class="form-control m-bot15" id="alocation" name="location_id" value=''>   
                            <option value="">Please Select Location</option>
                            <?php 

                            $locsa = $patient->location; 

                            foreach($location as $loc)
                            { 
                            if($locsa == $loc->id){
                              echo '<option value="'.$loc->id.' selected">'.$loc->name. '</option>';
                            }else{
                                    echo '<option value="'.$loc->id.'">'.$loc->name. '</option>';
                            }
                            }
                            ?>

                        </select>
                    
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date" readonly="" name="date"  value='' placeholder="">
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1">Available Slots</label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots" value=''> 

                        </select>
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?> <?php echo lang('status'); ?></label> 
                        <select type="text" class="form-control" name="mode_of_consultation" id="mode_of_consultation">
                        <option value="">Select Mode Of Consultation</option>
                        <?php
                        foreach($consultation as $row)
                        {
                         echo '<option value="'.$row->name.'">'.$row->mode_of_consultation.'</option>';
                        }
                        ?>

                        </select>
                    </div>
                    
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>
                        <select type="text" class="form-control" name="type_of_consultation" id="type_of_consultation" value='' placeholder="">

                              <option value="">Select Type Of Consultation</option>

                        </select>
                    </div>
                   

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label> 
                        <select class="form-control m-bot15" name="status" value=''> 
                            <option value="">Please Select Status</option>
                            <?php 
                            foreach($status as $row)
                            { 
                              echo '<option value="'.$row->name.'">'.$row->status_name.'</option>';
                            }
                            ?>
                           
                        </select>
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks"  value='' placeholder="">
                    </div>

                   
                    <div class="col-md-12 panel">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>

                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Appointment Modal-->

<div class="modal fade" tabindex="-1" role="dialog" id="cmodal">
    <div class="modal-dialog modal-lg med_his" role="document" >
        <div class="modal-content">
           
            <div id='medical_history'>
                <div class="col-md-12">

                </div> 
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Edit Event Modal-->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo lang('edit_appointment'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editAppointmentForm" action="appointment/addNew" class="clearfix" method="post" enctype="multipart/form-data">
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label> 
                        <select class="form-control m-bot15  pos_select1 patient" id="pos_select1" name="patient" value=''> 

                        </select>
                    </div>
                    <div class="pos_client1 clearfix col-md-6">
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label> 
                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>
                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">
                        </div>
                        <div class="payment pad_bot pull-right">
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('age'); ?></label> 
                            <input type="text" class="form-control pay_in" name="p_age" value='' placeholder="">
                        </div> 
                        <div class="payment pad_bot"> 
                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('gender'); ?></label>
                            <select class="form-control" name="p_gender" value=''>

                                <option value="Male" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Male') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Male </option>   
                                <option value="Female" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Female') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Female </option>
                                <option value="Others" <?php
                                if (!empty($patient->sex)) {
                                    if ($patient->sex == 'Others') {
                                        echo 'selected';
                                    }
                                }
                                ?> > Others </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 panel doctor_div1">
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label> 
                        <select class="form-control m-bot15 doctor" id="adoctors1" name="doctor" value=''>  


                        </select>
                    </div>

                    <div class="col-md-12 panel">
                    <label for="exampleInputEmail1"> Location </label> 
                    <select class="form-control m-bot15" id="alocation" name="location_id" value=''>   
                            <option value="">Please Select Location</option>
                            <?php 

                            $locsa = $patient->location; 

                            foreach($location as $loc)
                            { 
                            if($locsa == $loc->id){
                              echo '<option value="'.$loc->id.' selected">'.$loc->name. '</option>';
                            }else{
                                    echo '<option value="'.$loc->id.'">'.$loc->name. '</option>';
                            }
                            }
                            ?>

                        </select>
                    </div>
                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date1" readonly="" name="date"  value='' placeholder="">
                    </div>

               


                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1">Available Slots</label>
                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value=''> 
                        </select>
                    </div>


                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>
                        
                         <select type="text" class="form-control" name="mode_of_consultation" id="modes_of_consultation">
                        <option value="">Select Mode Of Consultation</option>
                        <?php

                        foreach($consultation as $row)
                        {
                        if($mode == $row->status_name){
                              echo '<option value="'.$row->id.' selected">'.$row->status_name. '</option>';
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
                           
                        <option value="">Select Type Of Consultation</option>
                        <?php

                        foreach($type as $rows)
                        { 
                         echo '<option value="'.$rows->id.'">'.$rows->name.'</option>';
                        }
                        ?>

                        </select>
                    </div>
                   


                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label> 
                         <select class="form-control m-bot15" name="status" value=''> 
                            <option value="">Please select Status</option>
                            <?php 

                            $stat = $patient->status; 

                            foreach($status as $row)
                            { 
                            if($stat == $row->id){
                              echo '<option value="'.$row->id.' selected">'.$row->status_name. '</option>';
                            }else{
                                    echo '<option value="'.$row->id.'">'.$row->status_name. '</option>';
                            }
                            }
                            ?>
                           
                        </select>
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks"  value='' placeholder="">
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
<!-- Edit Event Modal-->

<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>
<script type="text/javascript">var select_patient = "<?php echo lang('select_patient'); ?>";</script>
<script type="text/javascript">var language = "<?php echo $this->language; ?>";</script>

<script src="common/extranal/js/appointment/appointment.js"></script>
<script src="common/extranal/js/appointment/appointment_select2.js"></script>

<script>
$(document).ready(function(){
 $('#mode_of_consultation').change(function(){
  var consultation_id = $('#mode_of_consultation').val();
  if(consultation_id != '')
  {
   $.ajax({
    url:"<?php echo base_url(); ?>appointment/fetch_type",
    method:"POST",
    data:{consultation_id:consultation_id},
    success:function(data)
    {
     $('#type_of_consultation').html(data);
    }
   });
  }
  else
  {
   $('#type_of_consultation').html('<option value="">Select Type Of Consultation</option>');
   
  }
 });
  });


$(document).ready( function () {
    $('#myTable').DataTable();
});
 </script>

