<!--sidebar end-->

<!--main content start-->



<style>

    .fw-bold {

        font-weight: bold;

    }

</style>



<section id="main-content">

    <section class="wrapper site-min-height">

        <link href="common/extranal/css/patient/patient.css" rel="stylesheet">

        <section class="">



            <header class="panel-heading">

                <?php echo lang('patient'); ?> <?php echo lang('database'); ?>

                <div class="col-md-4 no-print pull-right">

                    <a data-toggle="modal" href="#myModal">

                        <div class="btn-group pull-right">

                            <!-- <button id="" class="btn green btn-xs">

                                <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>

                            </button> -->

                            <a href="patient/addNewView">

                                <button class="btn green btn-xs">

                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>

                                </button>

                            </a>

                        </div>

                    </a>

                </div>

            </header>

            <div class="panel-body">



                <div class="adv-table editable-table ">



                    <div class="space15"></div>

                    <table class="table table-striped table-hover table-bordered" id="editable-sample">

                        <thead>

                            <tr>

                                <th><?php echo lang('patient_id'); ?></th>

                                <th><?php echo lang('name'); ?></th>

                                <th><?php echo lang('phone'); ?></th>

                                <th><?php echo lang('gender'); ?></th>
                                <th><?php echo lang('company'); ?></th>

                                <!-- <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) { ?>

                                    <th><?php echo lang('due_balance'); ?></th>

                                <?php } ?> -->

                                <th class="no-print"><?php echo lang('options'); ?></th>

                            </tr>

                        </thead>

                        <tbody>



                        </tbody>

                    </table>

                </div>

            </div>

        </section>

        <!-- page end-->

    </section>

</section>

<!--main content end-->

<!--footer start-->







<!-- Add Patient Modal-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('register_new_patient'); ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" action="patient/addNew" class="clearfix" method="post" enctype="multipart/form-data">



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>

                        <input type="text" class="form-control" name="email" value='' placeholder="">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>

                        <input type="password" class="form-control" name="password" placeholder="">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>

                        <input type="text" class="form-control" name="address" value='' placeholder="">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('sex'); ?></label>

                        <select class="form-control m-bot15" name="sex" value=''>



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



                    <div class="form-group col-md-6">

                        <label><?php echo lang('birth_date'); ?></label>

                        <input class="form-control form-control-inline input-medium default-date-picker" type="text" name="birthdate" value="" placeholder="" required="" onkeypress="return false;">

                    </div>





                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>

                        <select class="form-control m-bot15" name="bloodgroup" value=''>



                            <?php foreach ($groups as $group) { ?>

                                <option value="<?php echo $group->blood_type; ?>" <?php

                                                                                    if (!empty($patient->bloodgroup)) {

                                                                                        if ($group->blood_type == $patient->bloodgroup) {

                                                                                            echo 'selected';

                                                                                        }

                                                                                    }

                                                                                    ?>> <?php echo $group->blood_type; ?> </option>

                            <?php } ?>

                        </select>

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>

                        <select class="form-control m-bot15" id="doctorchoose1" name="doctor" value=''>



                        </select>

                    </div>







                    <div class="form-group last col-md-6">

                        <label class="control-label">Image Upload</label>

                        <div class="">

                            <div class="fileupload fileupload-new" data-provides="fileupload">

                                <div class="fileupload-new thumbnail img_class">

                                    <img src="" id="#img1" alt="" />



                                </div>

                                <div class="fileupload-preview fileupload-exists thumbnail img_thumb"></div>

                                <div>

                                    <span class="btn btn-white btn-file">

                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>

                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>

                                        <input type="file" class="default" name="img_url" />

                                    </span>

                                    <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>

                                </div>

                            </div>



                        </div>

                    </div>

                    <div class="form-group col-md-6">

                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>

                    </div>





                    <section class="col-md-12">

                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>

                    </section>

                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>

<!-- Add Patient Modal-->





<!-- Edit Patient Modal-->

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('edit_patient'); ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" id="editPatientForm" action="patient/addNew" class="clearfix" method="post" enctype="multipart/form-data">



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>

                        <input type="text" class="form-control" name="email" value='' placeholder="">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('change'); ?><?php echo lang('password'); ?></label>

                        <input type="password" class="form-control" name="password" placeholder="">

                    </div>







                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>

                        <input type="text" class="form-control" name="address" value='' placeholder="">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="">

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('sex'); ?></label>

                        <select class="form-control m-bot15" name="sex" value=''>



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



                    <div class="form-group col-md-6">

                        <label><?php echo lang('birth_date'); ?></label>

                        <input class="form-control form-control-inline input-medium default-date-picker" type="text" name="birthdate" value="" placeholder="" required="" onkeypress="return false;">

                    </div>





                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>

                        <select class="form-control m-bot15" name="bloodgroup" value=''>

                            <?php foreach ($groups as $group) { ?>

                                <option value="<?php echo $group->blood_type; ?>" <?php

                                                                                    if (!empty($patient->bloodgroup)) {

                                                                                        if ($group->group == $patient->bloodgroup) {

                                                                                            echo 'selected';

                                                                                        }

                                                                                    }

                                                                                    ?>> <?php echo $group->blood_type; ?> </option>

                            <?php } ?>

                        </select>

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>

                        <select class="form-control m-bot15" id="doctorchoose" name="doctor" value=''>



                        </select>

                    </div>







                    <div class="form-group last col-md-6">

                        <label class="control-label">Image Upload</label>

                        <div class="">

                            <div class="fileupload fileupload-new" data-provides="fileupload">

                                <div class="fileupload-new thumbnail img_class">

                                    <img src="" id="img" alt="" />



                                </div>

                                <div class="fileupload-preview fileupload-exists thumbnail img_thumb"></div>

                                <div>

                                    <span class="btn btn-white btn-file">

                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>

                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>

                                        <input type="file" class="default" name="img_url" />

                                    </span>

                                    <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>

                                </div>

                            </div>



                        </div>

                    </div>



                    <div class="form-group col-md-6">

                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>

                    </div>



                    <input type="hidden" name="id" value=''>

                    <input type="hidden" name="p_id" value='<?php

                                                            if (!empty($patient->patient_id)) {

                                                                echo $patient->patient_id;

                                                            }

                                                            ?>'>











                    <section class="col-md-12">

                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>

                    </section>



                </form>



            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>

</div>

<!-- Edit Patient Modal-->





<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('patient'); ?> <?php echo lang('info'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" id="infoPatientForm" action="patient/addNew" class="p-0 m-0" method="post" enctype="multipart/form-data">

                    <div class="card py-4">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-4">

                                    <div class="card">

                                        <div class="card-body text-center">

                                            <div class="profileImageDiv mx-auto mb-4">

                                                <img src="" alt="" class="rounded-circle img-thumbnail w-50" id="img1">

                                            </div>

                                            <h5 class="mb-1" id="info_name"></h5>

                                        </div>

                                    </div>



                                    <div class="card mt-5">

                                        <div class="card-body">

                                            <h5 class="card-title mb-4 fw-bold"><i class="	far fa-user-circle"></i> Basic Information</h5>

                                            <div>

                                                <ul class="list-unstyled mb-0 text-muted">

                                                    <li>

                                                        <div class="d-flex align-items-center py-2">

                                                            <div class="flex-grow-1">

                                                                <i class="fa fa-envelope font-size-18"></i>

                                                                <span id="info_email"></span>

                                                            </div>

                                                        </div>

                                                    </li>

                                                    <li>

                                                        <div class="d-flex align-items-center py-2">

                                                            <div class="flex-grow-1">

                                                                <i class="fa fa-phone font-size-18"></i>

                                                                <span id="info_phone"></span>

                                                            </div>

                                                        </div>

                                                    </li>

                                                    <li>

                                                        <div class="d-flex align-items-center py-2">

                                                            <div class="flex-grow-1">

                                                                <i class="fa fa-user font-size-18"></i>

                                                                <span id="info_gender"></span>

                                                            </div>

                                                        </div>

                                                    </li>

                                                </ul>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="col-md-4">

                                    <div class="card mb-3">

                                        <div class="card-body">

                                            <h5 class="card-title mb-4 fw-bold">General Information</h5>



                                            <ul class="list-unstyled mb-0">

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Doctor</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_doctor"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Date Of Birth</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_dob"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Age</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_age"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Blood Group</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_bloodgroup"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Address</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_address"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                            </ul>



                                        </div>

                                    </div>



                                    <div class="card mb-3">

                                        <div class="card-body ">

                                            <h5 class="card-title mb-4 fw-bold">Lifestyle History</h5>



                                            <ul class="list-unstyled mb-0">

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Smoking <span id="info_smoking"></span></h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_smoking_comment"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Alcohol use <span id="info_alcohol"></span></h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_alcohol_comment"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Other (Sexual activity, LMP, etc.) <span id="info_other_activity"></span></h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_other_activity_comment"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                            </ul>

                                        </div>

                                    </div>







                                </div>

                                <div class="col-d-4">





                                    <div class="card mb-3">

                                        <div class="card-body">

                                            <h5 class="card-title mb-4 fw-bold">Medical Information</h5>



                                            <ul class="list-unstyled mb-0">

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Chief Complain</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_chiefComplaint"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">History of Presenting Illness</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_historyOfIllness"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Past Medical History and Drug History</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_pastMedicalHistory"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Past Surgical History</h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_pastSurgicalHistory"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                                <li class="pb-1">

                                                    <div class="d-flex align-items-center">

                                                        <div class="flex-grow-1">

                                                            <h5 class="mb-0 font-size-14">Allergies <span id="info_allergies"></span></h5>

                                                            <p class="text-muted mb-1 font-size-13" id="info_allergies_comment"></p>

                                                        </div>

                                                    </div>

                                                </li>

                                            </ul>

                                        </div>

                                    </div>

                                </div>



                            </div>

                        </div>



                </form>



            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div>

</div>





<script src="common/js/codearistos.min.js"></script>

<script type="text/javascript">

    var select_doctor = "<?php echo lang('select_doctor'); ?>";

</script>

<script type="text/javascript">

    var language = "<?php echo $this->language; ?>";

</script>



<script src="common/extranal/js/patient/patient.js"></script>