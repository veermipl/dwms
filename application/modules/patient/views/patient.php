<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <link href="common/extranal/css/patient/patient.css" rel="stylesheet">
        <section class="">

            <header class="panel-heading">
                <?php echo lang('patient'); ?> <?php echo lang('database'); ?>
                <div class="col-md-4 no-print pull-right"> 
                    <a data-toggle="modal" href="#myModal">
                        <div class="btn-group pull-right">
                            <button id="" class="btn green btn-xs">
                                <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                            </button>
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
                                <?php if ($this->ion_auth->in_group(array('admin', 'Accountant', 'Receptionist'))) { ?>
                                    <th><?php echo lang('due_balance'); ?></th>
                                <?php } ?>
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
                <h4 class="modal-title">  <?php echo lang('register_new_patient'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" action="patient/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="name"  value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="email"  value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>
                        <input type="password" class="form-control" name="password"  placeholder="">
                    </div>



                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <input type="text" class="form-control" name="address"  value='' placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <input type="text" class="form-control" name="phone"  value='' placeholder="">
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
                                ?> > <?php echo $group->blood_type; ?> </option>
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
                                        <input type="file" class="default" name="img_url"/>
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
                <h4 class="modal-title">  <?php echo lang('edit_patient'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="editPatientForm" action="patient/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="name"  value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="email"  value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('change'); ?><?php echo lang('password'); ?></label>
                        <input type="password" class="form-control" name="password"  placeholder="">
                    </div>



                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <input type="text" class="form-control" name="address"  value='' placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <input type="text" class="form-control" name="phone"  value='' placeholder="">
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
                                ?> > <?php echo $group->blood_type; ?> </option>
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
                                        <input type="file" class="default" name="img_url"/>
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
                <h4 class="modal-title">  <?php echo lang('patient'); ?>  <?php echo lang('info'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" id="infoPatientForm"  action="patient/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group last col-md-4">
                        <div class="">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail img_class">
                                <img src="" id="img1" alt="" />

                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail img_thumb"></div>
                            </div>
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><?php echo lang('patient_id'); ?>: <span class="patientIdClass"></span></label>
                            </div>
                        </div>

                    </div>
                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>
                        <div class="nameClass">
                        <input type="text" class="form-control" name="name"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>


                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <div class="emailClass">
                        <input type="text" class="form-control" name="email"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label><?php echo lang('age'); ?></label>
                        <div class="ageClass">
                        <input type="text" class="form-control" name="age"  value='' placeholder="" readonly="readonly">
                        </div>     
                    </div>

                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <div class="addressClass">
                        <input type="text" class="form-control" name="address"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('gender'); ?></label>
                        <div class="genderClass">
                        <input type="text" class="form-control" name="sex"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <div class="phoneClass">
                        <input type="text" class="form-control" name="phone"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>
                        <div class="bloodgroupClass">
                        <input type="text" class="form-control" name="bloodgroup"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label><?php echo lang('birth_date'); ?></label>
                        <div class="birthdateClass">
                        <input type="text" class="form-control" name="birthdate"  value='' placeholder="" readonly="readonly">
                        </div>     
                    </div>






                    <div class="form-group col-md-4">    
                    </div>
                    <div class="form-group col-md-4">    
                    </div>
                    <div class="form-group col-md-4">    
                        <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>
                        <div class="doctorClass">
                        <input type="text" class="form-control" name="doctor"  value='' placeholder="" readonly="readonly">
                        </div>
                    </div>







                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
</div>


<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>
<script type="text/javascript">var language = "<?php echo $this->language; ?>";</script>

<script src="common/extranal/js/patient/patient.js"></script>


