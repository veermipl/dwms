<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->
        <section class="panel  row">

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

                <div class="col-lg-12">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <?php echo validation_errors(); ?>
                    </div>
                    <div class="col-lg-3"></div>
                </div>

                <form role="form" action="appointment/addNew" class="clearfix row" method="post" enctype="multipart/form-data">

                    <div class="col-md-6">
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

                                    <select class="form-control m-bot15" required name="p_gender" value=''>



                                        <option value="Male" <?php

                                                                if (!empty($patient->sex)) {

                                                                    if ($patient->sex == 'Male') {

                                                                        echo 'selected';
                                                                    }
                                                                }

                                                                ?>> Male </option>

                                        <option value="Female" <?php

                                                                if (!empty($patient->sex)) {

                                                                    if ($patient->sex == 'Female') {

                                                                        echo 'selected';
                                                                    }
                                                                }

                                                                ?>> Female </option>

                                        <option value="Others" <?php

                                                                if (!empty($patient->sex)) {

                                                                    if ($patient->sex == 'Others') {

                                                                        echo 'selected';
                                                                    }
                                                                }

                                                                ?>> Others </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-12 panel">

                            <div class="col-md-3 payment_label">

                                <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>

                            </div>

                            <div class="col-md-9 doctor_div">

                                <select class="form-control m-bot15" required id="adoctors" name="doctor" value=''>

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
                                <select class="form-control m-bot15" id="alocation" required name="location_id" value=''>
                                    <option value="">Please Select Location</option>

                                    <?php foreach ($location as $loc) : ?>
                                        <option value="<?= $loc->id ?>" <?= set_value('location_id', @$appointment->location_id) == $loc->id  ? 'selected' : '' ?>>
                                            <?= $loc->name ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>


                            <div class="col-md-12 panel">

                                <div class="col-md-3 payment_label">

                                    <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>

                                </div>

                                <div class="col-md-9">

                                    <input type="text" class="form-control" required id="date" readonly="" min="<?=date('y-m-d')?>" name="date" value='<?php

                                                                                                                        if (!empty($appointment->date)) {

                                                                                                                            echo date('d-m-Y', ($appointment->date));
                                                                                                                        }else{
                                                                                                                            '';
                                                                                                                        }

                                                                                                                        ?>' placeholder="">

                                </div>

                            </div>

                            <div class="col-md-12 panel">

                                <div class="col-md-3 payment_label">
                                    <label class=""><?php echo lang('available_slots'); ?></label>
                                </div>

                                <div class="col-md-9">
                                    <select class="form-control m-bot15" name="time_slot" id="aslots" value='' required>
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 panel">
                                <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?> <?php echo lang('status'); ?></label>
                                <select class="form-control" name="mode_of_consultation" required id="mode_of_consultation">
                                    <option value="">Select Mode Of Consultation</option>

                                    <?php foreach ($consultation as $consultationVal) : ?>
                                        <option value="<?= $consultationVal->id ?>" <?= set_value('mode_of_consultation', @$appointment->mode_of_consultation) == $consultationVal->id  ? 'selected' : '' ?>>
                                            <?= $consultationVal->mode_of_consultation ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-12 panel">
                                <label for="exampleInputEmail1"> <?php echo 'Type Of Consultation'; ?></label>
                                <select type="text" class="form-control"  name="type_of_consultation" id="type_of_consultation" value='' placeholder="">

                                    <option value="">Select Type Of Consultation</option>
                                    <?php  if($appointment->mode_of_consultation == 1): ?>
                                            <option value="1" <?= (@$appointment->type_of_consultation == 1) ? 'selected' : ''?> >Audio</option>
                                            <option value="2" <?= (@$appointment->type_of_consultation == 2) ? 'selected' : ''?> >Video</option>
                                    <?php endif ?>

                                </select>
                            </div>


                            <div class="col-md-12 panel">

                                <div class="col-md-3 payment_label">

                                    <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>

                                </div>

                                <div class="col-md-9">

                                    <input type="text" class="form-control" name="remarks" value='<?php

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

                                        <?php foreach ($status as $statusVal) : ?>
                                            <option value="<?= $statusVal->id ?>" <?= set_value('status', @$appointment->status) == $statusVal->id  ? 'selected' : '' ?>>
                                                <?= $statusVal->status_name ?>
                                            </option>
                                        <?php endforeach; ?>

                                    </select>


                                </div>

                                <div class="form-group">
                                    <label for="bp">Amount</label>
                                    <input type="number" step="0.001" class="form-control" id="amount" name="amount" value="<?= set_value('amount', @$appointment->amount ?? '700.00') ?>" />
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

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="temp">Temp (°C):</label>
                            <input type="text" class="form-control" id="temp" name="temp" value="<?= set_value('temp', @$appointment->temp) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="bp">BP (mmHg):</label>
                            <input type="text" class="form-control" id="bp" name="bp" value="<?= set_value('bp', @$appointment->bp) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="pulse">Pulse (b/min):</label>
                            <input type="text" class="form-control" id="pulse" name="pulse" value="<?= set_value('pulse', @$appointment->pulse) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="spo2">SpO2 (%):</label>
                            <input type="text" class="form-control" id="spo2" name="spo2" value="<?= set_value('spo2', @$appointment->spo2) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="rr">RR (b/min):</label>
                            <input type="text" class="form-control" id="rr" name="rr" value="<?= set_value('rr', @$appointment->rr) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="height">Height (cm):</label>
                            <input type="text" class="form-control" id="height" name="height" value="<?= set_value('height', @$appointment->height) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight (kg):</label>
                            <input type="text" class="form-control" id="weight" name="weight" value="<?= set_value('weight', @$appointment->weight) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="bmi">BMI:</label>
                            <input type="text" class="form-control" id="bmi" name="bmi" value="<?= set_value('bmi', @$appointment->bmi) ?>" />
                        </div>
                        <div class="form-group">
                            <label for="rbs">RBS (mg/dL):</label>
                            <input type="text" class="form-control" id="rbs" name="rbs" value="<?= set_value('rbs', @$appointment->rbs) ?>" />
                        </div>
                    </div>

                    <div class="col-md-12 text-center mt-2">
                        <button type="submit" name="submit" class="btn btn-info pull-right_"> <?php echo lang('submit'); ?></button>
                    </div>

                </form>

            </div>


        </section>
        <!-- page end-->

    </section>

</section>

<!--main content end-->

<!--footer start-->

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }
</style>



<script src="common/js/codearistos.min.js"></script>







<?php if (!empty($appointment->id)) { ?>



    <script src="common/extranal/js/appointment/edit_appointment.js"></script>



<?php } else { ?>



    <script src="common/extranal/js/appointment/add_new.js"></script>



<?php } ?>

<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>

<script type="text/javascript">
    var select_patient = "<?php echo lang('select_patient'); ?>";
</script>

<script src="common/extranal/js/appointment/appointment_select2.js"></script>



<script>
    $(document).ready(function() {
        $('#mode_of_consultation').change(function() {
            var consultation_id = $('#mode_of_consultation').val();
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
</script>