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

        <section class="col-md-12">

            <header class="panel-heading">View Appointment <?php

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

                                    <!-- <?php var_dump($appointment); ?> -->

                                    <div class="col-md-4  mb-3">

                                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient);

                                                                                                                                    echo $patientdetails->name; ?>' placeholder="">



                                    </div>



                                    <div class="col-md-4 mb-3  doctor_div1">

                                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">



                                    </div>



                                    <div class="col-md-4 mb-3 ">

                                        <label for="exampleInputEmail1"> Location </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php $getLocation =  $this->doctor_model->getLocationById($appointment->location_id);

                                                                                                                                    echo $getLocation->name; ?>' placeholder="">

                                    </div>



                                    <div class="col-md-4 mb-3 ">

                                        <label for="exampleInputEmail1"> <?php echo 'Date - Time' ?> </label>

                                        <input type="text" readonly="readonly" class="form-control default-date-picker" id="date1" readonly="" name="date" value='<?php echo $appointment->date ?> <?php echo $appointment->s_time ?>-<?php echo $appointment->e_time ?>' placeholder="">

                                    </div>



                                    <div class="col-md-4 mb-3 ">

                                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?> </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php echo $appointment->type_of_consultation; ?>' placeholder="">

                                    </div>



                                    <div class="col-md-4 mb-3">

                                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?> </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php echo $appointment->mode_of_consultation; ?>' placeholder="">

                                    </div>



                                    <div class="col-md-4 mb-3">

                                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?> </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php $getStatus =  $this->doctor_model->getStatusById($appointment->status);

                                                                                                                                    echo  $getStatus->status_name; ?>' placeholder="">

                                    </div>



                                    <div class="col-md-4 mb-3">

                                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('type'); ?> </label>

                                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php echo $appointment->patient_type; ?>' placeholder="">

                                    </div>



                                    <input type="hidden" name="id" id="appointment_id" value=''>

                                    <div class="col-md-12 panel mt-3">

                                        <?php

                                        // var_dump($settings);

                                        // echo $settings->mindays_for_booking; echo "<br>";

                                        // echo $appointment->s_time; echo "<br>";

                                        // echo $appointment->e_time; echo "<br>";

                                        // var_dump($appointment);



                                        $str = $appointment->s_time;

                                        $str = explode("-", $str);

                                        // echo $str[0];

                                        $testDateStr = strtotime($str[0]);

                                        $finalDate = date("Y-m-d H:i:s", strtotime(-$settings->mindays_for_booking . "hour", $testDateStr));

                                        // echo 'One hour ago, the date = ' . $finalDate;

                                        $str1 = explode(" ", $finalDate);

                                        // print_r($str1);

                                        // echo "The time is " . date("h:i:sa");



                                        if ($str1[0] > date("h:i:sa")) {

                                            // echo "Time is correct";

                                        ?>



                                            <button type="button" class="btn btn-info btn-xs btn_width editedbutton" data-toggle="modal" data-id="<?php echo $appointment->id ?>" data-target="#myModal22"> Cancel</button>

                                            &nbsp; &nbsp;



                                            <button type="button" class="btn btn-info btn-xs btn_width editedbutton" data-toggle="modal" data-id="<?php echo $appointment->id ?>" data-target="#myModal2"> Postpone</button>

                                        <?php

                                        } else {

                                            echo "The Time Schedule has been passed";

                                        }

                                        ?>

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



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog ">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('edit_appointment'); ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" id="editAppointmentForm" action="appointment/appointment/markPostponeRequest" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient);

                                                                                                                    echo $patientdetails->name; ?>' placeholder="">



                    </div>



                    <div class="col-md-6 panel doctor_div1">

                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>

                        <input type="text" readonly="readonly" id="#adoctors1" class="form-control" readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">



                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>

                        <input type="text" class="form-control default-date-picker" id="date1" readonly="" name="date" value='<?php echo $appointment->date ?>' placeholder="">

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?></label>



                        <select type="text" class="form-control" name="mode_of_consultation" id="modes_of_consultation">

                            <option value="">Select Mode Of Consultation</option>

                            <?php



                            foreach ($consultation as $row) {

                                if ($appointment->mode_of_consultation == $row->mode_of_consultation) {

                                    echo '<option value="' . $row->id . '" selected>' . $row->mode_of_consultation . '</option>';

                                } else {

                                    echo '<option value="' . $row->id . '">' . $row->mode_of_consultation . '</option>';

                                }

                            }

                            ?>



                        </select>

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>

                        <select type="text" class="form-control" name="types_of_consultation" id="type_of_consultation" placeholder="">







                        </select>

                    </div>







                    <div class="col-md-6 panel" id="location_id_div">

                        <label for="exampleInputEmail1"> Location </label>

                        <select class="form-control" name="location_id" id="location_id">

                            <option value="">Please Select Location</option>

                            <?php



                            foreach ($location as $loc) {

                                if ($appointment->location_id == $loc->id) {

                                    echo '<option value="' . $loc->id . '"selected>' . $loc->name . '</option>';

                                } else {

                                    echo '<option value="' . $loc->id . '">' . $loc->name . '</option>';

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









                    <input type="hidden" name="id" id="appointment_ids" value='<?php echo $appointment->id; ?>'>

                    <div class="col-md-12 panel bdr-top-btn">

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

                <h4 class="modal-title"> <?php echo 'Reason Cancel'; ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" id="editAppointmentForm" action="appointment/markCancellationRequest" class="clearfix" method="post" enctype="multipart/form-data">

                    <!-- <?php var_dump($appointment); ?> -->

                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>

                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php $patientdetails = $this->patient_model->getPatientById($appointment->patient);

                                                                                                                    echo $patientdetails->name; ?>' placeholder="">



                    </div>



                    <div class="col-md-6 panel doctor_div1">

                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?> </label>

                        <input type="text" readonly="readonly" class="form-control" readonly="" name="date" value='<?php echo $this->doctor_model->getDoctorById($appointment->doctor)->name; ?>' placeholder="">



                    </div>



                    <div class="col-md-12 ">

                        <label for="exampleInputEmail1">Reason For Cancellation</label>

                        <input type="text" class="form-control" name="reasone" id="reasone" required="required">

                    </div>





                    <input type="hidden" name="id" id="appointment_id" value='<?php echo $appointment->id; ?>'>

                    <input type="hidden" name="appointment_id" id="id" value='<?php echo $appointment->id; ?>'>

                    <div class="col-md-12 bdr-top-btn" style="margin-top: 15px;">

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

<script>

    $(document).ready(function() {

        $('#modes_of_consultation').change(function() {

            var consultation_id = $('#modes_of_consultation').val();





            if (consultation_id != '') {

                $.ajax({

                    url: "<?php echo base_url(); ?>appointment/fetch_type",

                    method: "POST",

                    data: {

                        consultation_id: consultation_id

                    },

                    success: function(data) {

                        $('#type_of_consultation').html(data);

                    }

                });

            } else {

                $('#type_of_consultation').html('<option value="">Select Type Of Consultation</option>');



            }

        });

    });



    $(document).ready(function() {

        $('#myTable').DataTable();

    });



    $(document).ready(function() {



        var date = $('#date1').val();

        var doctorr = 1;

        var a_id = $('#appointment_ids').val();



        $.ajax({



            url: 'schedule/getAvailableSlotByDoctorByDateByAppointmentIdByJason?date=' + date + '&doctor=' + doctorr + '&appointment_id=' + appointment_id,

            method: 'GET',

            data: '',

            dataType: 'json',

            success: function(response) {

                "use strict";

                $('#aslots1').find('option').remove();

                var slots = response.aslots;

                $.each(slots, function(key, value) {

                    "use strict";

                    $('#aslots1').append($('<option>').text(value).val(value)).end();

                });



                $("#aslots1").val(response.current_value).trigger('change');





                if ($('#aslots1').has('option').length == 0) { //if it is blank. 

                    $('#aslots1').append($('<option>').text('No Further Time Slots').val('Not Selected')).end();

                }

            }

        })



    });

</script>