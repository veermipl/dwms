<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
           <link href="common/extranal/css/patient/medical_history.css" rel="stylesheet">
        <section class="col-md-3">
            <header class="panel-heading clearfix">
                <div class="">
                    <?php echo lang('patient'); ?> <?php echo lang('info'); ?> 
                </div>

            </header> 

            <aside class="profile-nav">
                <section class="">
                    <div class="user-heading round edit_patient_div">
                        <?php if (!empty($patient->img_url)) { ?>
                            <a href="#">
                                <img src="<?php echo $patient->img_url; ?>" alt="">
                            </a>
                        <?php } ?>
                        <h1> <?php echo $patient->name; ?> </h1>
                        <p> <?php echo $patient->email; ?> </p>
                        <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                            <button type="button" class="btn btn-info btn-xs btn_width editPatient" title="<?php echo lang('edit'); ?>" data-toggle="modal" data-id="<?php echo $patient->id; ?>"><i class="fa fa-edit"> </i> <?php echo lang('edit'); ?></button>  
                        <?php } ?>
                    </div>

                    <ul class="nav nav-pills nav-stacked">
                      
                        <li>  <?php echo lang('patient_id'); ?> <span class="label pull-right r-activity"><?php echo $patient->id; ?></span></li>
                        <li>  <?php echo lang('gender'); ?><span class="label pull-right r-activity"><?php echo $patient->sex; ?></span></li>
                        <li>  <?php echo lang('birth_date'); ?><span class="label pull-right r-activity"><?php echo $patient->birthdate; ?></span></li>
                        <li>  <?php echo lang('phone'); ?><span class="label pull-right r-activity"><?php echo $patient->phone; ?></span></li>
                        <li>  <?php echo lang('email'); ?><span class="label pull-right r-activity"><?php echo $patient->email; ?></span></li>
                        <li class="address_bar">  <?php echo lang('address'); ?><span class="pull-right address_bar"><?php echo $patient->address; ?></span></li>

                    </ul>

                </section>
            </aside>


        </section>





        <section class="col-md-9">
            <header class="panel-heading clearfix">
                <div class="col-md-7">
                    <?php echo lang('history'); ?> | <?php echo $patient->name; ?>
                </div>               
            </header>

            <section class="panel-body">   
                <header class="panel-heading tab-bg-dark-navy-blueee">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a data-toggle="tab" href="#appointments"><?php echo lang('appointments'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#home"><?php echo lang('case_history'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#about"><?php echo lang('prescription'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#lab"><?php echo lang('lab'); ?></a>
                        </li>
                        <li class="">
                            <a data-toggle="tab" href="#profile"><?php echo lang('documents'); ?></a>
                        </li>
                        
                        <li class="">
                            <a data-toggle="tab" href="#timeline"><?php echo lang('timeline'); ?></a> 
                        </li>
                    </ul>
                </header>
                <div class="panel">
                    <div class="tab-content">
                        <div id="appointments" class="tab-pane active">
                            <div class="">
                                <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                                    <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#addAppointmentModal">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?> 
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#addAppointmentModal">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('request_a_appointment'); ?> 
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th><?php echo 'Appointment ID'; ?></th>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('time_slot'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th><?php echo lang('status'); ?></th>
                                                <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                                                    <th class="no-print"><?php echo lang('options'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($appointments as $appointment) { ?>
                                                <tr class="">
                                                    <td><?php echo $appointment->id;?></td>
                                                    <td><?php echo $appointment->date; ?></td>
                                                    <td><?php echo $appointment->time_slot; ?></td>
                                                    <td>
                                                        <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($appointment->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $appointment_doctor = $doctor_details->name;
                                                        } else {
                                                            $appointment_doctor = '';
                                                        }
                                                        echo $appointment_doctor;
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                     $getStatus =  $this->doctor_model->getStatusById($appointment->status);
            
                                                        
                                                        echo $getStatus->status_name;
                                                        ?></td>
                                                         
                                                    <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                                                        <td class="no-print edit_appointment_button">
                                                            <button type="button" class="btn btn-info btn-xs btn_width editAppointmentButton" title="<?php echo lang('edit'); ?>" data-toggle="modal" data-id="<?php echo $appointment->id; ?>"><i class="fa fa-edit"></i> </button>   
                                                            <a class="btn btn-info btn-xs btn_width delete_button" title="<?php echo lang('delete'); ?>" href="appointment/delete?id=<?php echo $appointment->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i> </a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="home" class="tab-pane">
                            <div class="">

                                <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                    <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#myModal">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?> 
                                        </a>
                                    </div>
                                <?php } ?>

                                <div class="adv-table editable-table ">


                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('title'); ?></th>
                                                <th><?php echo lang('description'); ?></th>
                                                <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                                    <th class="no-print"><?php echo lang('options'); ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($medical_histories as $medical_history) { ?>
                                                <tr class="">

                                                    <td><?php echo date('d-m-Y', $medical_history->date); ?></td>
                                                    <td><?php echo $medical_history->title; ?></td>
                                                    <td><?php echo $medical_history->description; ?></td>
                                                    <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                                        <td class="no-print medical_history_button">
                                                            <button type="button" class="btn btn-info btn-xs btn_width editbutton" title="<?php echo lang('edit'); ?>" data-toggle="modal" data-id="<?php echo $medical_history->id; ?>"><i class="fa fa-edit"></i> </button>   
                                                            <a class="btn btn-info btn-xs btn_width delete_button" title="<?php echo lang('delete'); ?>" href="patient/deleteCaseHistory?id=<?php echo $medical_history->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i> </a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div id="about" class="tab-pane"> <div class="">
                                <?php if ($this->ion_auth->in_group(array('Doctor'))) { ?>
                                    <div class=" no-print">
                                        <a class="btn btn-info btn_width btn-xs" href="prescription/addPrescriptionView">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_new'); ?> 
                                        </a>
                                    </div>
                                <?php } ?>
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>

                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th><?php echo lang('medicine'); ?></th>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($prescriptions as $prescription) { ?>
                                                <tr class="">
                                                    <td><?php echo date('m/d/Y', $prescription->date); ?></td>
                                                    <td>
                                                        <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($prescription->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $prescription_doctor = $doctor_details->name;
                                                        } else {
                                                            $prescription_doctor = '';
                                                        }
                                                        echo $prescription_doctor;
                                                        ?>

                                                    </td>
                                                    <td>

                                                        <?php
                                                        if (!empty($prescription->medicine)) {
                                                            $medicine = explode('###', $prescription->medicine);

                                                            foreach ($medicine as $key => $value) {
                                                                $medicine_id = explode('***', $value);
                                                                $medicine_details = $this->medicine_model->getMedicineById($medicine_id[0]);
                                                                if (!empty($medicine_details)) {
                                                                    $medicine_name_with_dosage = $medicine_details->name . ' -' . $medicine_id[1];
                                                                    $medicine_name_with_dosage = $medicine_name_with_dosage . ' | ' . $medicine_id[3] . '<br>';
                                                                    rtrim($medicine_name_with_dosage, ',');
                                                                    echo '<p>' . $medicine_name_with_dosage . '</p>';
                                                                }
                                                            }
                                                        }
                                                        ?>


                                                    </td>
                                                    <td class="no-print prescription_button">
                                                        <a class="btn-xs green" href="prescription/viewPrescription?id=<?php echo $prescription->id; ?>"><i class="fa fa-eye"> <?php echo lang('view'); ?> </i></a> 
                                                        <?php
                                                        if ($this->ion_auth->in_group('Doctor')) {
                                                            $current_user = $this->ion_auth->get_user_id();
                                                            $doctor_table_id = $this->doctor_model->getDoctorByIonUserId($current_user)->id;
                                                            if ($prescription->doctor == $doctor_table_id) {
                                                                ?>
                                                                <a type="button" class="btn-info btn-xs btn_width" data-toggle="modal" href="prescription/editPrescription?id=<?php echo $prescription->id; ?>"><i class="fa fa-edit"></i> <?php echo lang('edit'); ?></a>   
                                                                <a class="btn-info btn-xs btn_width delete_button" href="prescription/delete?id=<?php echo $prescription->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i> <?php echo lang('delete'); ?></a>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <a class="btn-xs invoicebutton" title="<?php echo lang('print'); ?>"  href="prescription/viewPrescriptionPrint?id=<?php echo $prescription->id; ?>"target="_blank"> <i class="fa fa-print"></i> <?php echo lang('print'); ?></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="lab" class="tab-pane"> <div class="">
                                <div class="adv-table editable-table ">
                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th><?php echo lang('id'); ?></th>
                                                <th><?php echo lang('date'); ?></th>
                                                <th><?php echo lang('doctor'); ?></th>
                                                <th class="no-print"><?php echo lang('options'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($labs as $lab) { ?>
                                                <tr class="">
                                                    <td><?php echo $lab->id; ?></td>
                                                    <td><?php echo date('m/d/Y', $lab->date); ?></td>
                                                    <td>
                                                        <?php
                                                        $doctor_details = $this->doctor_model->getDoctorById($lab->doctor);
                                                        if (!empty($doctor_details)) {
                                                            $lab_doctor = $doctor_details->name;
                                                        } else {
                                                            $lab_doctor = '';
                                                        }
                                                        echo $lab_doctor;
                                                        ?>
                                                    </td>
                                                    <td class="no-print">
                                                        <a class="btn btn-info btn-xs btn_width" href="lab/invoice?id=<?php echo $lab->id; ?>"><i class="fa fa-eye"> <?php echo lang('report'); ?> </i></a>   
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>



                        <div id="profile" class="tab-pane"> <div class="">
                              
                                <div class=" no-print">
                                    <div class="btn-group pull-left">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#myModal1">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_file'); ?> 
                                        </a>
                                    </div>
                                </div>
                                <div class=" no-print add_folder_css">
                                    <div class="btn-group pull-right">
                                        <a class="btn btn-info btn_width btn-xs" data-toggle="modal" href="#myModalf">
                                            <i class="fa fa-plus-circle"> </i> <?php echo lang('add_folder'); ?> 
                                        </a>
                                    </div> 
                                </div>
                                <div class="adv-table editable-table ">
                                    <div class="col-md-12">
                                        <?php
                                        foreach ($patient_materials as $patient_material) {
                                            if ($patient_material->folder == '') {
                                                ?>
                                                <div class="panel col-md-3 patient_material_class1">
                                                    <div class="col-md-5 pull-left">
                                                        <div class="post-info">
                                                            <a class="example-image-link" href="<?php echo $patient_material->url; ?>" data-lightbox="example-1">
                                                                <img class="example-image" src="<?php echo $patient_material->url; ?>" alt="image-1" height="90" width="90"/></a>
                                      
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-md-7 pull-right patient_material_url">
                                                        <div class="post-info patient_material_title">
                                                            <?php
                                                            if (!empty($patient_material->title)) {
                                                                echo $patient_material->title;
                                                            }
                                                            ?>

                                                        </div>
                                                        <div class="post-info">
                                                            <a class="btn btn-blue btn-xs btn_width" href="<?php echo $patient_material->url; ?>" download><i class="fa fa-download"></i> </a>
                                                            <?php if (!$this->ion_auth->in_group(array('Patient'))) { ?>
                                                                <a class="btn btn-info btn-xs btn_width delete_button " title="<?php echo lang('delete'); ?>" href="patient/deletePatientMaterial?id=<?php echo $patient_material->id; ?>"
                                                                   onclick="return confirm('Are you sure you want to delete this item?');"> <i class="fa fa-trash"></i> </a>
                                                               <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?> 

                                        <?php foreach ($folders as $folder) {
                                            ?>  
                                            <div class="panel col-md-3 patient_material_class1">
                                                <div class="col-md-6 pull-left">


                                                    <a href="patient/medicalHistoryByFolder?id=<?php echo $folder->id; ?>">
                                                        <div class="post-info">

                                                            <img class="example-image" src="uploads/folder1.png" alt="image-1" height="100" width="100"/>

                                                        </div></a>
                                                    </div>
                                                <div class="col-md-6 pull-right">
                                                    <div class="post-info patient_material_title">
                                                        <?php
                                                        if (!empty($folder->folder_name)) {
                                                            echo $folder->folder_name;
                                                        }
                                                        ?>

                                                    </div>
                                                
                                               


                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                            <span class="">Action</span>
                                                            
                                                        </button>
                                                        <ul class="dropdown-menu folder_div" role="menu">
                                                            <li> <a type="" class="edittbutton" data-toggle="modal" data-id="<?php echo $folder->id; ?>"><?php echo lang('edit'); ?></a></li>
                                                            <li><a class="" href="patient/deleteFolder?id=<?php echo $folder->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><?php echo lang('delete'); ?></a></li>
                                                            <li><a class="uploadbutton"  data-toggle="modal" data-id="<?php echo $folder->id; ?>"> <?php echo lang('upload'); ?> </a></li>
                                                            <li><a class=""  href="patient/medicalHistoryByFolder?id=<?php echo $folder->id; ?>"><?php echo lang('view_files'); ?></a></li>
                                                        </ul>
                                                    </div>
                                              
                                                </div>

                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                   
                    <div id="timeline" class="tab-pane"> 
                        <div class="">
                            <div class="">
                                <section class="panel ">
                                    <header class="panel-heading">
                                        Timeline
                                    </header>
                                 

                                    <?php
                                    if (!empty($timeline)) {
                                        krsort($timeline);
                                        foreach ($timeline as $key => $value) {
                                            echo $value;
                                        }
                                    }
                                    ?>

                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </section>

        </section>



    </section>
    <!-- page end-->
</section>
</section>
<!--main content end-->
<!--footer start-->




<!-- Add Patient Material Modal-->
<div class="modal fade" id="myModalf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">   <?php echo lang('add'); ?> <?php echo lang('folder'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="patient/addNewFolder" class="clearfix" method="post" enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="folder_name" id="exampleInputEmail1" placeholder="">
                    </div>
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="myModalfe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">   <?php echo lang('edit'); ?> <?php echo lang('folder'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editFolderForm" action="patient/addNewFolder" class="clearfix" method="post" enctype="multipart/form-data">


                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('name'); ?></label>
                        <input type="text" class="form-control" name="folder_name" id="exampleInputEmail1" placeholder="">
                    </div>
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value='<?php echo $folder->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- Add Patient Material Modal-->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">   <?php echo lang('add'); ?> <?php echo lang('files'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="patient/addPatientMaterial" class="clearfix row" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('title'); ?></label>
                        <input type="text" class="form-control" name="title" id="exampleInputEmail1" placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('file'); ?></label>
                        <input type="file" name="img_url">
                    </div>

                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>

                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModalff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">    <?php echo lang('add'); ?> <?php echo lang('files'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="uploadFileForm" action="patient/addPatientMaterial" class="clearfix" method="post" enctype="multipart/form-data">


                     <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('title'); ?></label>
                        <input type="text" class="form-control" name="title" id="exampleInputEmail1" placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo lang('file'); ?></label>
                        <input type="file" name="img_url">
                    </div>
                    <input type="hidden" name="hidden_folder_name" value="<?php echo $folder->folder_name; ?>" />
                    <input type="hidden" name="patient" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="folder" value='<?php echo $folder->id; ?>'>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Patient Modal-->


<!-- Add Medical History Modal-->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo lang('add_case'); ?></h4>
            </div> 
            <div class="modal-body">
                <form role="form" action="patient/addMedicalHistory" class="clearfix row" method="post" enctype="multipart/form-data">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium default-date-picker" name="date" id="exampleInputEmail1" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('title'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium" name="title" id="exampleInputEmail1" value='' placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label class=""><?php echo lang('description'); ?></label>
                        <div class="">
                            <textarea class="ckeditor form-control" name="description" value="" rows="10"></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="patient_id" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value=''>
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info submit_button pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="myModal2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo lang('edit_case'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="medical_historyEditForm" class="clearfix row" action="patient/addMedicalHistory" method="post" enctype="multipart/form-data">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('date'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium default-date-picker" name="date" id="exampleInputEmail1" value='' placeholder="" readonly="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"><?php echo lang('title'); ?></label>
                        <input type="text" class="form-control form-control-inline input-medium" name="title" id="exampleInputEmail1" value='' placeholder="">
                    </div>
                    <div class="form-group col-md-12">
                        <label class=""><?php echo lang('description'); ?></label>
                        <div class="">
                            <textarea class="ckeditor form-control editor" id="editor" name="description" value="" rows="10"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="patient_id" value='<?php echo $patient->id; ?>'>
                    <input type="hidden" name="id" value=''>
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info submit_button pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<?php
$current_user = $this->ion_auth->get_user_id();
if ($this->ion_auth->in_group('Doctor')) {
    $doctor_id = $this->db->get_where('doctor', array('ion_user_id' => $current_user))->row()->id;
}
?>


<!-- Add Appointment Modal-->
<div class="modal fade" id="addAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">   <?php echo lang('add_appointment'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" action="appointment/addNew" class="clearfix row" method="post" enctype="multipart/form-data">
                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1">  <?php echo lang('patient'); ?></label>
                        <select class="form-control m-bot15 js-example-basic-single pos_select" id="pos_select" name="patient" value=''> 
                            <option value="">Select .....</option>
                            <option value="<?php echo $patient->id; ?>"><?php echo $patient->name; ?> </option>
                        </select>
                    </div>
                    <div class="col-md-4 panel doctor_div">
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label>
                        <select class="form-control m-bot15" id="adoctors" name="doctor" value=''>  

                        </select>
                    </div>


                    <div class="col-md-4 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" id="date" readonly="" name="date" id="exampleInputEmail1" value='' placeholder="">
                    </div>

                    <div class="col-md-6 panel">
                        <label class=""><?php echo lang('available_slots'); ?></label> 
                        <select class="form-control m-bot15" name="time_slot" id="aslots" value=''> 

                        </select>
                    </div>

                    <div class="col-md-6 panel"> 
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>

                            <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                                <option value="Pending Confirmation" <?php ?> > <?php echo lang('pending_confirmation'); ?> </option>
                                <option value="Confirmed" <?php ?> > <?php echo lang('confirmed'); ?> </option>
                                <option value="Treated" <?php
                                ?> > <?php echo lang('treated'); ?> </option>
                                <option value="Cancelled" <?php ?> > <?php echo lang('cancelled'); ?> </option>
                            <?php } else { ?>
                                <option value="Requested" <?php ?> > <?php echo lang('requested'); ?> </option> 
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-8 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" id="exampleInputEmail1" value='' placeholder="">
                    </div>




                    <div class="col-md-5 panel">
                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>
                    </div>

                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>

                    <input type="hidden" name="request" value='<?php
                    if ($this->ion_auth->in_group(array('Patient'))) {
                        echo 'Yes';
                    }
                    ?>'>

                    <div class="col-md-12 panel">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="editAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">   <?php echo lang('edit_appointment'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editAppointmentForm" class="clearfix row" action="appointment/addNew" method="post" enctype="multipart/form-data">
                    <div class="col-md-4 panel"> 
                        <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>
                        <select class="form-control m-bot15 js-example-basic-single pos_select patient" id="pos_select" name="patient" value=''> 
                            <option value="">Select .....</option>
                            <option value="<?php echo $patient->id; ?>"><?php echo $patient->name; ?> </option>
                        </select>
                    </div>

                    <div class="col-md-4 panel doctor_div1">
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label> 
                        <select class="form-control m-bot15 doctor" id="adoctors1" name="doctor" value=''>  

                        </select>
                    </div>


                    <div class="col-md-4 panel"> 
                        <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                        <input type="text" class="form-control default-date-picker" readonly="" id="date1" name="date" id="exampleInputEmail1" value='' placeholder="">
                    </div>

                    <div class="col-md-6 panel">
                        <label class=""><?php echo lang('available_slots'); ?></label> 
                        <select class="form-control m-bot15" name="time_slot" id="aslots1" value=''> 

                        </select>
                    </div>

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('appointment'); ?> <?php echo lang('status'); ?></label>
                        <select class="form-control m-bot15" name="status" value=''>
                            <?php if (!$this->ion_auth->in_group('Patient')) { ?>
                                <option value="Pending Confirmation" <?php ?> > <?php echo lang('pending_confirmation'); ?> </option>
                                <option value="Confirmed" <?php
                                ?> > <?php echo lang('confirmed'); ?> </option>
                                <option value="Treated" <?php
                                ?> > <?php echo lang('treated'); ?> </option>
                                <option value="Cancelled" <?php ?> > <?php echo lang('cancelled'); ?> </option>
                            <?php } else { ?>
                                <option value="Requested" <?php ?> > <?php echo lang('requested'); ?> </option> 
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-8 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('remarks'); ?></label>
                        <input type="text" class="form-control" name="remarks" id="exampleInputEmail1" value='' placeholder="">
                    </div>




                    <div class="col-md-6 panel">
                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>
                    </div>



                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>>
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




<!-- Edit Patient Modal-->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                        <input type="text" class="form-control" name="name" id="exampleInputEmail1" value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <input type="text" class="form-control" name="email" id="exampleInputEmail1" value='' placeholder="">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('change'); ?><?php echo lang('password'); ?></label>
                        <input type="password" class="form-control" name="password" id="exampleInputEmail1" placeholder="">
                    </div>



                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <input type="text" class="form-control" name="address" id="exampleInputEmail1" value='' placeholder="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <input type="text" class="form-control" name="phone" id="exampleInputEmail1" value='' placeholder="">
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
                        <input class="form-control form-control-inline input-medium default-date-picker" type="text" name="birthdate" value="" placeholder="" readonly="">      
                    </div>


                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>
                        <select class="form-control m-bot15" name="bloodgroup" value=''>
                            <?php foreach ($groups as $group) { ?>
                                <option value="<?php echo $group->group; ?>" <?php
                                if (!empty($patient->bloodgroup)) {
                                    if ($group->group == $patient->bloodgroup) {
                                        echo 'selected';
                                    }
                                }
                                ?> > <?php echo $group->group; ?> </option>
                                    <?php } ?> 
                        </select>
                    </div>

                    <div class="form-group col-md-6">    
                        <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>
                        <select class="form-control js-example-basic-single doctor"  name="doctor" value=''> 
                            <option value=""> </option>
                            <?php foreach ($doctors as $doctor) { ?>                                        
                                <option value="<?php echo $doctor->id; ?>"><?php echo $doctor->name; ?> </option>
                            <?php } ?> 
                        </select>
                    </div>



                    <div class="form-group last col-md-6">
                        <label class="control-label">Image Upload</label>
                        <div class="">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail img_class">
                                    <img src="//www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" id="img" alt="" />
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

                    <input type="hidden" name="redirect" value='patient/medicalHistory?id=<?php echo $patient->id; ?>'>>

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




<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>
<script type="text/javascript">var select_patient = "<?php echo lang('select_patient'); ?>";</script>
<script type="text/javascript">var language = "<?php echo $this->language; ?>";</script>

<script src="common/extranal/js/patient/medical_history.js"></script>
