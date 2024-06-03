<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <section class="panel col-md-6 row">

            <header class="panel-heading">

                <?php

                if (!empty($appointment->id))

                    echo lang('edit_appointment');

                else

                    echo lang('add_appointment');

                ?>

            </header>

          <link href="common/extranal/css/appointment/add_new.css" rel="stylesheet">



            <div class="panel-body">

                <div class="adv-table editable-table ">

                    <?php echo validation_errors(); ?>

                    <?php echo $this->session->flashdata('feedback'); ?>

                </div>

                <form role="form" action="appointment/addNew" class="clearfix row" method="post" enctype="multipart/form-data">

                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                        </div>

                        <div class="col-md-9"> 

                            <select class="form-control m-bot15  pos_select" id="pos_select" name="patient" value=''> 

                                <?php if (!empty($appointment->id)) { ?>

                                    <option value="<?php echo $patients->id; ?>" selected="selected"><?php echo $patients->name; ?> - <?php echo $patients->id; ?></option>  

                                <?php } ?>

                            </select>

                        </div>

                    </div>

                    <div class="pos_client clearfix">

                        <div class="col-md-8 payment pad_bot pull-right">

                            <div class="col-md-3 payment_label"> 

                                <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('name'); ?></label>

                            </div>

                            <div class="col-md-9"> 

                                <input type="text" class="form-control pay_in" name="p_name" value='<?php

                                if (!empty($payment->p_name)) {

                                    echo $payment->p_name;

                                }

                                ?>' placeholder="">

                            </div>

                        </div>

                        <div class="col-md-8 payment pad_bot pull-right">

                            <div class="col-md-3 payment_label"> 

                                <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('email'); ?></label>

                            </div>

                            <div class="col-md-9"> 

                                <input type="text" class="form-control pay_in" name="p_email" value='<?php

                                if (!empty($payment->p_email)) {

                                    echo $payment->p_email;

                                }

                                ?>' placeholder="">

                            </div>

                        </div>

                        <div class="col-md-8 payment pad_bot pull-right">

                            <div class="col-md-3 payment_label"> 

                                <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('phone'); ?></label>

                            </div>

                            <div class="col-md-9"> 

                                <input type="text" class="form-control pay_in" name="p_phone" value='<?php

                                if (!empty($payment->p_phone)) {

                                    echo $payment->p_phone;

                                }

                                ?>' placeholder="">

                            </div>

                        </div>

                        <div class="col-md-8 payment pad_bot pull-right">

                            <div class="col-md-3 payment_label"> 

                                <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('age'); ?></label>

                            </div>

                            <div class="col-md-9"> 

                                <input type="text" class="form-control pay_in" name="p_age" value='<?php

                                if (!empty($payment->p_age)) {

                                    echo $payment->p_age;

                                }

                                ?>' placeholder="">

                            </div>

                        </div> 

                        <div class="col-md-8 payment pad_bot pull-right">

                            <div class="col-md-3 payment_label"> 

                                <label for="exampleInputEmail1"> <?php echo lang('patient'); ?> <?php echo lang('gender'); ?></label>

                            </div>

                            <div class="col-md-9"> 

                                <select class="form-control m-bot15" name="p_gender" value=''>



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

                    </div>



                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label>

                        </div>

                        <div class="col-md-9 doctor_div"> 

                            <select class="form-control m-bot15" id="adoctors" name="doctor" value=''>  

                                <?php if (!empty($appointment->id)) { ?>

                                    <option value="<?php echo $doctors->id; ?>" selected="selected"><?php echo $doctors->name; ?> - <?php echo $doctors->id; ?></option>  

                                <?php } ?>

                            </select>

                        </div>

                    </div>
                                    
                    <div class="col-md-12 panel">
                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1"> Location </label>

                        </div>  
                    <div class="col-md-9"> 
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


                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>

                        </div>

                        <div class="col-md-9"> 

                            <input type="text" class="form-control" id="date" readonly="" name="date"  value='<?php

                            if (!empty($appointment->date)) {

                                echo date('d-m-Y', $appointment->date);

                            }

                            ?>' placeholder="">

                        </div>

                    </div>



                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label class=""><?php echo lang('available_slots'); ?></label>

                        </div>

                        <div class="col-md-9"> 

                            <select class="form-control m-bot15" name="time_slot" id="aslots" value=''> 



                            </select>

                        </div>

                    </div>

                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?> <?php echo lang('status'); ?></label> 
                        <select type="text" class="form-control" name="mode_of_consultation" id="mode_of_consultation">
                        <option value="">Select Mode Of Consultation</option>
                        <?php
                        foreach($consultation as $row)
                        {
                         echo '<option value="'.$row->id.'">'.$row->mode_of_consultation.'</option>';
                        }
                        ?>

                        </select>
                    </div>
                    
                    <div class="col-md-12 panel">
                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>
                        <select type="text" class="form-control" name="type_of_consultation" id="type_of_consultation" value='' placeholder="">

                              <option value="">Select Type Of Consultation</option>

                        </select>
                    </div>





                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>

                        </div>

                        <div class="col-md-9"> 

                            <input type="text" class="form-control" name="remarks"  value='<?php

                            if (!empty($appointment->remarks)) {

                                echo $appointment->remarks;

                            }

                            ?>' placeholder="">

                        </div>

                    </div>





                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                            <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>

                        </div>

                        <div class="col-md-9"> 
                        <select class="form-control m-bot15" name="status" value=''> 
                            <option value="">Please Select Status</option>
                            <?php 
                            foreach($status as $row)
                            { 
                              echo '<option value="'.$row->id.'">'.$row->status_name.'</option>';
                            }
                            ?>
                           
                        </select>
                    

                        </div>

                    </div>





                    <input type="hidden" name="id" id="appointment_id" value='<?php

                    if (!empty($appointment->id)) {

                        echo $appointment->id;

                    }

                    ?>'>



                    <div class="col-md-12 panel">

                        <div class="col-md-3 payment_label"> 

                        </div>

                        <div class="col-md-9"> 

                            <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>

                        </div>

                    </div>





                </form>

            </div>



        </section>

        <!-- page end-->

    </section>

</section>

<!--main content end-->

<!--footer start-->





<script src="common/js/codearistos.min.js"></script>







<?php if (!empty($appointment->id)) { ?>



    <script src="common/extranal/js/appointment/edit_appointment.js"></script>



<?php } else { ?> 



    <script src="common/extranal/js/appointment/add_new.js"></script>



<?php } ?>

<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>

<script type="text/javascript">var select_patient = "<?php echo lang('select_patient'); ?>";</script>

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

 </script>







