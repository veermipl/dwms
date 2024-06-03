<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- invoice start-->
         <link href="common/extranal/css/lab/invoice.css" rel="stylesheet">
		 <style>
		 .d-flex{display: flex;
}

		 </style>
        <section class="col-md-6">

            <div class="panel panel-primary" id="lab">
              
                <div class="panel-body invoice_info">
                    <div class="row invoice-list pt-5">
					
								 <div class="text-left top_logo  " style="float: left;width: 30%;padding-left: 10px;"> <img alt="" src="<?php echo $this->settings_model->getSettings()->logo; ?>" width="200">

	</div>
							 <div class="text-left top_logo  " style="float: left;width: 30%;"> &nbsp

	</div>   
                    <div class=" top_title" style="float: left;width: 40%;">

                            <h5>
                                <?php echo $settings->title ?>
                            </h5>
                            <h5>
                                <?php echo $settings->address ?>
                            </h5>
                            <h5>
                                Tel: <?php echo $settings->phone ?>
                            </h5>
                            
                        
                   
                    </div>

                        <div class="text-center corporate-id">
    <h4 class="lang_lab">
                                <?php echo lang('lab_report') ?>
                                
                            </h4>
							 <hr class="lang_lab_hr" style="width: 100%;margin: 20px 0px;">
                        </div>





                        <div class="col-md-12">
                            <div class="col-md-6 pull-left row patient_info">
                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <?php $patient_info = $this->db->get_where('patient', array('id' => $lab->patient))->row(); ?>
                                        <label class="control-label"><?php echo lang('patient'); ?> <?php echo lang('name'); ?> :</label>
                                        <span class="patient_name">  
                                            <?php
                                            if (!empty($patient_info)) {
                                                echo $patient_info->name . ' <br>';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"><?php echo lang('patient_id'); ?>  :</label>
                                        <span class="patient_name">  
                                            <?php
                                            if (!empty($patient_info)) {
                                                echo $patient_info->id . ' <br>';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"> <?php echo lang('address'); ?> :</label>
                                        <span class="patient_name"> 
                                            <?php
                                            if (!empty($patient_info)) {
                                                echo $patient_info->address . ' <br>';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"><?php echo lang('phone'); ?>  :</label>
                                        <span class="patient_name">  
                                            <?php
                                            if (!empty($patient_info)) {
                                                echo $patient_info->phone . ' <br>';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>


                            </div>

                            <div class="col-md-6 pull-right patient_info">

                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"> <?php echo lang('lab'); ?> <?php echo lang('report'); ?> <?php echo lang('id'); ?>  :</label>
                                        <span class="patient_name">  
                                            <?php
                                            if (!empty($lab->id)) {
                                                echo $lab->id;
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>


                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"><?php echo lang('date'); ?>  :</label>
                                        <span class="patient_name"> 
                                            <?php
                                            if (!empty($lab->date)) {
                                                echo date('d-m-Y', $lab->date) . ' <br>';
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>

                                <div class="col-md-12 row details">
                                    <p class="d-flex">
                                        <label class="control-label"><?php echo lang('doctor'); ?>  :</label>
                                        <span class="patient_name"> 
                                            <?php
                                            if (!empty($lab->doctor)) {
                                                $doctor_details = $this->doctor_model->getDoctorById($lab->doctor);
                                                if (!empty($doctor_details)) {
                                                    echo $doctor_details->name. '<br>';
                                                }
                                            }
                                            ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <br>

                    </div> 


                    <div class="col-md-12 panel-body">
                        <?php
                        if (!empty($lab->report)) {
                            echo $lab->report;
                        }
                        ?>
                    </div>


                </div>
            </div>



        </section>


        <section class="col-md-6">

            <div class="col-md-5 no-print option">
                <div class="text-center col-md-12 row">
                    <?php if ($this->ion_auth->in_group(array('admin', 'Laboratorist'))) { ?>
                    <a href="lab" class="btn btn-info btn-sm info pull-left"><i class="fa fa-arrow-circle-left"></i>  <?php echo lang('back_to_lab_module'); ?> </a>
                    <?php }?>
                    <?php if ($this->ion_auth->in_group(array('Patient'))) { ?>
                    <a href="lab/myLab" class="btn btn-info btn-sm info pull-left"><i class="fa fa-arrow-circle-left"></i>  <?php echo lang('back_to_lab_module'); ?> </a>
                    <?php }?>
                    <a class="btn btn-info btn-sm invoice_button pull-left" onclick="javascript:window.print();"><i class="fa fa-print"></i> <?php echo lang('print'); ?> </a>

                    <a class="btn btn-info btn-sm detailsbutton pull-left download" id="download"><i class="fa fa-download"></i> <?php echo lang('download'); ?> </a>

                    <?php if ($this->ion_auth->in_group(array('admin', 'Laboratorist'))) { ?>
                        <a href="lab?id=<?php echo $lab->id; ?>" class="btn btn-info btn-sm blue pull-left"><i class="fa fa-edit"></i> <?php echo lang('edit_report'); ?> </a>
                    <?php } ?>


                </div>

                <?php if ($this->ion_auth->in_group(array('admin', 'Laboratorist'))) { ?>
                    <div class="no-print">


                        <a href="lab" class="pull-left">
                            <div class="btn-group">
                                <button id="" class="btn green btn-sm">
                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_a_new_report'); ?>
                                </button>
                            </div>
                        </a>
                    </div>
                <?php } ?>

            </div>











        </section>
        <!-- invoice end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<script src="common/js/codearistos.min.js"></script>

<script>
                        $(document).ready(function () {
                            $(".flashmessage").delay(3000).fadeOut(100);
                        });
</script>





<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>

<script>


                        $('#download').click(function () {
                            var pdf = new jsPDF('p', 'pt', 'letter');
                            pdf.addHTML($('#lab'), function () {
                                pdf.save('lab_id_<?php echo $lab->id; ?>.pdf');
                            });
                        });

                        // This code is collected but useful, click below to jsfiddle link.
</script>
