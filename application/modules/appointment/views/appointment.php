<!--sidebar end-->

<!--main content start-->

<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <link href="common/extranal/css/appointment/appointment.css" rel="stylesheet">



        <section class="panel">

            <header class="panel-heading">

                <?php echo lang('appointments'); ?>

                <div class="clearfix no-print col-md-8 pull-right">

                    <a data-toggle="modal" href="#myModal">

                        <div class="btn-group pull-right">

                            <!-- <button id="" class="btn green btn-xs">

                                <i class="fa fa-plus-circle"></i>  <?php echo lang('add_appointment'); ?> 

                            </button> -->

                            <?php if ($this->ion_auth->in_group(array('Nurse'))) : ?>

                                <a href="appointment/addNewView">

                                    <button class="btn green btn-xs">

                                        <i class="fa fa-plus-circle"></i> <?php echo lang('add_appointment'); ?>

                                    </button>

                                </a>

                            <?php endif; ?>

                        </div>

                    </a>

                </div>

            </header>



            <div class="col-md-12">

                <header class="panel-heading tab-bg-dark-navy-blueee row">

                    <ul class="nav nav-tabs col-md-12">

                        <li class="active">

                            <a data-toggle="tab" href="#all"><?php echo lang('all'); ?></a>

                        </li>



                        <li class="">

                            <a data-toggle="tab" href="#1">Pending</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#2">Approved</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#9">VIP</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#3">Rejected</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#7">Confirmed</a>

                        </li>



                        <li class="">

                            <a data-toggle="tab" href="#4">Completed</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#5">No-Show</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#6">Postponed</a>

                        </li>

                        <li class="">

                            <a data-toggle="tab" href="#8">Cancelled</a>

                        </li>







                    </ul>



                    <div class="pull-right col-md-3">

                        <div class="pull-right custom_buttonss"></div>

                    </div>



                </header>

            </div>





            <div class="">

                <div class="tab-content">

                    <div id="1" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample1">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">







                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div id="2" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample2">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--  <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">









                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div id="3" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample3">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">











                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div id="4" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample4">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--    <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">







                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div id="all" class="tab-pane active">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">



                                    <div class="space15"></div>







                                    <table class="table table-striped table-hover table-bordered" id="editable-sample5">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th class="testClass"> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">







                                        </tbody>

                                    </table>









                                </div>

                            </div>

                        </div>

                    </div>





                    <div id="5" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample6">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="6" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample7">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="7" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample8">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div id="8" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample9">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

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



                    <div id="9" class="tab-pane">

                        <div class="">

                            <div class="panel-body">

                                <div class="adv-table editable-table ">

                                    <div class="space15"></div>

                                    <table class="table table-striped table-hover table-bordered" id="editable-sample10">

                                        <thead>

                                            <tr>

                                                <th> <?php echo lang('id'); ?></th>

                                                <th> <?php echo lang('patient'); ?></th>

                                                <th> <?php echo lang('doctor'); ?></th>

                                                <th> <?php echo lang('date-time'); ?></th>

                                                <!--     <th> <?php echo lang('remarks'); ?></th> -->

                                                <th><?php echo "Consultation Type"; ?></th>

                                                <th> <?php echo "Consultation Mode"; ?></th>

                                                <th> <?php echo "Location"; ?></th>

                                                <th> <?php echo lang('status'); ?></th>

                                                <th> <?php echo lang('options'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody class="appointments_body">

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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog ">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('add_appointment'); ?></h4>

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

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('name'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('email'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('phone'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('age'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_age" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('gender'); ?></label>

                            <select class="form-control" name="p_gender" value=''>



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



                    <div class="col-md-6 panel doctor_div">

                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>

                        <select class="form-control m-bot15" id="adoctors" name="doctor" value=''>



                        </select>

                    </div>



                    <div class="col-md-12 panel">

                        <label for="exampleInputEmail1"> Location </label>

                        <select class="form-control m-bot15" id="alocation" name="location_id" value=''>

                            <option value="">Please Select Location</option>

                            <?php



                            $locsa = $patient->location;



                            foreach ($location as $loc) {

                                if ($locsa == $loc->id) {

                                    echo '<option value="' . $loc->id . ' selected">' . $loc->name . '</option>';
                                } else {

                                    echo '<option value="' . $loc->id . '">' . $loc->name . '</option>';
                                }
                            }

                            ?>



                        </select>



                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>

                        <input type="text" class="form-control default-date-picker" id="date" readonly="" name="date" value='' placeholder="">

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1">Available Slots</label>

                        <select class="form-control m-bot15" name="time_slot" id="aslots" value=''>



                        </select>

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo 'Mode Of Consultation'; ?>

                            <?php echo lang('status'); ?></label>

                        <select type="text" class="form-control" name="mode_of_consultation" id="mode_of_consultation">

                            <option value="">Select Mode Of Consultation</option>

                            <?php

                            foreach ($consultation as $row) {

                                echo '<option value="' . $row->name . '">' . $row->mode_of_consultation . '</option>';
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

                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>

                            <?php echo lang('status'); ?></label>

                        <select class="form-control m-bot15" name="status" value=''>

                            <option value="">Please Select Status</option>

                            <?php

                            foreach ($status as $row) {

                                echo '<option value="' . $row->name . '">' . $row->status_name . '</option>';
                            }

                            ?>



                        </select>

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>

                        <input type="text" class="form-control" name="remarks" value='' placeholder="">

                    </div>





                    <div class="col-md-12 panel">

                        <button type="submit" name="submit" class="btn btn-info pull-right">

                            <?php echo lang('submit'); ?></button>

                    </div>



                </form>

            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>

<!-- Add Appointment Modal-->



<div class="modal fade" tabindex="-1" role="dialog" id="cmodal">

    <div class="modal-dialog modal-lg med_his" role="document">

        <div class="modal-content">



            <div id=' medical_history'>

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

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog ">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('edit_appointment'); ?></h4>

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

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('name'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_name" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('email'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_email" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('phone'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_phone" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot pull-right">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('age'); ?></label>

                            <input type="text" class="form-control pay_in" name="p_age" value='' placeholder="">

                        </div>

                        <div class="payment pad_bot">

                            <label for="exampleInputEmail1"> <?php echo lang('patient'); ?>

                                <?php echo lang('gender'); ?></label>

                            <select class="form-control" name="p_gender" value=''>



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



                    <div class="col-md-6 panel doctor_div1">

                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>

                        <select class="form-control m-bot15 doctor" id="adoctors1" name="doctor" value=''>





                        </select>

                    </div>



                    <div class="col-md-12 panel">

                        <label for="exampleInputEmail1"> Location </label>

                        <select class="form-control m-bot15" id="alocation" name="location_id" value=''>

                            <option value="">Please Select Location</option>

                            <?php



                            $locsa = $patient->location;



                            foreach ($location as $loc) {

                                if ($locsa == $loc->id) {

                                    echo '<option value="' . $loc->id . ' selected">' . $loc->name . '</option>';
                                } else {

                                    echo '<option value="' . $loc->id . '">' . $loc->name . '</option>';
                                }
                            }

                            ?>



                        </select>

                    </div>

                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>

                        <input type="text" class="form-control default-date-picker" id="date1" readonly="" name="date" value='' placeholder="">

                    </div>









                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1">Available Slots</label>

                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value=''>

                        </select>

                    </div>





                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1">

                            <?php echo 'Type Of Consultation'; ?></label>



                        <select type="text" class="form-control" name="mode_of_consultation" id="modes_of_consultation">

                            <option value="">Select Mode Of Consultation</option>

                            <?php



                            foreach ($consultation as $row) {

                                if ($mode == $row->status_name) {

                                    echo '<option value="' . $row->id . '"selected>' . $row->status_name . '</option>';
                                } else {

                                    echo '<option value="' . $row->id . '">' . $row->mode_of_consultation . '</option>';
                                }
                            }

                            ?>



                        </select>

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1">

                            <?php echo 'Type Of Consultation'; ?></label>

                        <select type="text" class="form-control" name="types_of_consultation" id="type_of_consultation" placeholder="">



                            <option value="">Select Type Of Consultation</option>

                            <?php



                            foreach ($type as $rows) {

                                echo '<option value="' . $rows->id . '">' . $rows->name . '</option>';
                            }

                            ?>



                        </select>

                    </div>







                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?>

                            <?php echo lang('status'); ?></label>

                        <select class="form-control m-bot15" name="status" value=''>

                            <option value="">Please select Status</option>

                            <?php



                            $stat = $patient->status;



                            foreach ($status as $row) {

                                if ($stat == $row->id) {

                                    echo '<option value="' . $row->id . ' selected">' . $row->status_name . '</option>';
                                } else {

                                    echo '<option value="' . $row->id . '">' . $row->status_name . '</option>';
                                }
                            }

                            ?>



                        </select>

                    </div>



                    <div class="col-md-6 panel">

                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>

                        <input type="text" class="form-control" name="remarks" value='' placeholder="">

                    </div>



                    <input type="hidden" name="id" id="appointment_id" value=''>

                    <div class="col-md-12 panel">

                        <button type="submit" name="submit" class="btn btn-info pull-right">

                            <?php echo lang('submit'); ?></button>

                    </div>

                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>

<!-- Edit Event Modal-->



<script src="common/js/codearistos.min.js"></script>

<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>

<script type="text/javascript">
    var select_patient = "<?php echo lang('select_patient'); ?>";
</script>

<script type="text/javascript">
    var language = "<?php echo $this->language; ?>";
</script>



<script src="common/extranal/js/appointment/appointment.js"></script>

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

                $('#type_of_consultation').html(

                    '<option value="">Select Type Of Consultation</option>');



            }

        });

    });
</script>