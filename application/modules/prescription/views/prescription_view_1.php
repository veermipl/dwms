<!--main content start-->

        <link href="common/extranal/css/prescription/prescription_view_1.css" rel="stylesheet">
	<style>
	@page {
  margin: 0%;

  @top-center {
    font-family: sans-serif;
    font-weight: bold;
    font-size: 2em;
    content: counter(page);
  }
}
	</style>
<section id="main-content">
    <section class="wrapper">

        <?php
        $doctor = $this->doctor_model->getDoctorById($prescription->doctor);
        $patient = $this->patient_model->getPatientById($prescription->patient);
        ?>

	  
	  
        <div class="col-md-8 panel bg_container margin_top p-0 col-xs-12 col-sm-12" id="prescription">
            <div class="bg_prescription">
                <div class="panel-body">
				       <div class="col-md-12 text-center top_title mt-3" style="text-align: center;width:100%;"><img src="uploads/Logo-c.png" height="60">
					   	<h5 class="mb-1"><i><b>A Step towards better health</b></i></h5>
					   </div>
				
				    <div class="row">
				 <div class="text-left top_logo  " style="float: left;width: 30%;padding-left: 10px;"> <img src="uploads/nama.png" width="200">

	</div>
							 <div class="text-left top_logo  " style="float: left;width: 30%;"> &nbsp

	</div>   
                    <div class=" top_title" style="float: left;width: 40%;">
					<h4 class="mb-0"><i><b>Dr. Sudhir Bhola</b></i></h4>
					<p class="mb-0">BAMS, M.D.(A.M.)NAMACP</p>
					<p class="mb-0">Regd No. 99400100058</p>
					<p class="mb-0"><b>NAMA Membership #:53848643</b></p>
					<p class="mb-0"><small>Sexologist, Skin & Hair Specialist</small></p>
					
					
                   
                    </div>
				
                 </div>
                </div>
                <div class="border-new mt-1"></div>
				<div class="row">
				<div class="col-md-12 clearfix">

                    <div class="col-md-3 left_panel" style="float: left;width: 23%;">

                        <div class="panel-body text-center">
						<h5 class="mt-4 mb-4"><strong>MEMBER</h5>
						<img src="uploads/namacv.jpg" height="80" class="mb-3">
						<img src="uploads/essm.jpg" height="80" class="mb-3">
						<img src="uploads/ssm.jpg" height="80" class="mb-3">
						<img src="uploads/aasect.jpg" height="40" class="mb-3">
						<img src="uploads/asia.jpg" height="70" class="mb-3">
						<img src="uploads/american.png" height="50" class="mb-3">                          
                        </div>
                    </div>

                    <div class="col-md-9 w-7" style="float: left;
    width: 77%;">

                        <div class="panel-body p-0">
                            <div class="medicine_div mt-4">
                             	<div class="row">
			<div class="col-md-5 col-xs-12 col-12 mb-3">
			 Name:  <?php
                            if (!empty($patient)) {
                                echo $patient->name;
                            }
                            ?>                                         
			</div>
			<div class="col-md-2 col-xs-12 col-12 mb-3">
			Age : <?php
                            if (!empty($patient)) {
                                $birthDate = strtotime($patient->birthdate);
                                $birthDate = date('m/d/Y', $birthDate);
                                $birthDate = explode("/", $birthDate);
                                $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
                                echo $age . ' Year(s)';
                            }
                            ?>              
			</div>
			<div class="col-md-2 col-xs-12 col-12 mb-3">
			Sex : <?php echo $patient->sex; ?>           
			</div>
			<div class="col-md-3 col-xs-12 col-12 mb-3">
			Date :   <?php echo date('d-m-Y', $prescription->date); ?>        
			</div>
			</div>
            <?php
                            if (!empty($prescription->medicine)) {
                                ?>
                                <table class="table table-striped table-hover">                      
                                    <thead>       
                                    <th><?php echo lang('medicine'); ?></th>
                                    <th><?php echo lang('instruction'); ?></th>
                                    <th class="text-right"><?php echo lang('frequency'); ?></th>    
                                    </thead>
                                    <tbody>
                                        <?php
                                        $medicine = $prescription->medicine;
                                        $medicine = explode('###', $medicine);
                                        foreach ($medicine as $key => $value) {
                                            ?>
                                            <tr>
                                                <?php $single_medicine = explode('***', $value); ?>

                                                <td class=""><?php echo $this->medicine_model->getMedicineById($single_medicine[0])->name . ' - ' . $single_medicine[1]; ?> </td>
                                                <td class=""><?php echo $single_medicine[3] . ' - ' . $single_medicine[4]; ?> </td>
                                                <td class="text-right"><?php echo $single_medicine[2] ?> </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                            <?php if (!empty($prescription->symptom)) { ?>
                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('history'); ?>: </strong>  <?php echo $prescription->symptom; ?></h5>
                            </div>
                        </div>

                        <hr>
                        <?php } ?>
                        <?php if (!empty($prescription->note)) { ?>
                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('note'); ?>:</strong> <?php echo $prescription->note; ?></h5>
                            </div>
                        </div>




                        <hr>
                        <?php } ?>
                        <?php if (!empty($prescription->advice)) { ?>
                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('advice'); ?>: </strong>  <?php echo $prescription->advice; ?></h5>
                            </div>
                        </div>
                        <?php } ?>

                            </div>
                                                    </div>

                    </div>
                </div>
				 </div>
				 
				 <div class="border-new"></div>
				 
				       <div class="col-md-12 text-center" style="margin-top:20px;">
					   <p>350, Sultanpur, Behind Sultanpur Metro Gate No. 1, New Delhi - 110030</p>
					   <p>ph. 9999118111, 9899118111</p>
					   <p>Book your Appointment with us at Procto.com, E-mail: curewelltherapies@gmail.com</p>
					    </div>
				</div>
            </div>
		
<!--                 <div class="panel-body">
                    <div class="">
                        <h5 class="col-md-4 prescription"><?php echo lang('date'); ?> : <?php echo date('d-m-Y', $prescription->date); ?></h5>
                        <h5 class="col-md-3 prescription"><?php echo lang('prescription'); ?> <?php echo lang('id'); ?> : <?php echo $prescription->id; ?></h5>
                    </div>
                </div> -->

           <!--      <hr> -->
               <!--  <div class="panel-body">
                    <div class="">
                        <h5 class="col-md-4 patient_name"><?php echo lang('patient'); ?>: <?php
                            if (!empty($patient)) {
                                echo $patient->name;
                            }
                            ?>
                        </h5>
                        <h5 class="col-md-3 patient"><?php echo lang('patient_id'); ?>: <?php
                            if (!empty($patient)) {
                                echo $patient->id;
                            }
                            ?></h5>
                        <h5 class="col-md-3 patient"><?php echo lang('age'); ?>: 
                            <?php
                            if (!empty($patient)) {
                                $birthDate = strtotime($patient->birthdate);
                                $birthDate = date('m/d/Y', $birthDate);
                                $birthDate = explode("/", $birthDate);
                                $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
                                echo $age . ' Year(s)';
                            }
                            ?>
                        </h5>
                        <h5 class="col-md-2 patient text-right"><?php echo lang('gender'); ?>: <?php echo $patient->sex; ?></h5>
                    </div>
                </div>

                <hr>
 -->
             <!--    <div class="col-md-12 clearfix description">



                    <div class="col-md-3 left_panel">

                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('history'); ?>: </strong> <br> <br> <?php echo $prescription->symptom; ?></h5>
                            </div>
                        </div>

                        <hr>

                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('note'); ?>:</strong> <br> <br> <?php echo $prescription->note; ?></h5>
                            </div>
                        </div>




                        <hr>

                        <div class="panel-body">
                            <div class="pull-left">
                                <h5><strong><?php echo lang('advice'); ?>: </strong> <br> <br> <?php echo $prescription->advice; ?></h5>
                            </div>
                        </div>




                    </div>

                    <div class="col-md-9">

                        <div class="panel-body">
                            <div class="medicine_div">
                                <strong class="medicine_div1"> Rx </strong>
                            </div>
                            <?php
                            if (!empty($prescription->medicine)) {
                                ?>
                                <table class="table table-striped table-hover">                      
                                    <thead>       
                                    <th><?php echo lang('medicine'); ?></th>
                                    <th><?php echo lang('instruction'); ?></th>
                                    <th class="text-right"><?php echo lang('frequency'); ?></th>    
                                    </thead>
                                    <tbody>
                                        <?php
                                        $medicine = $prescription->medicine;
                                        $medicine = explode('###', $medicine);
                                        foreach ($medicine as $key => $value) {
                                            ?>
                                            <tr>
                                                <?php $single_medicine = explode('***', $value); ?>

                                                <td class=""><?php echo $this->medicine_model->getMedicineById($single_medicine[0])->name . ' - ' . $single_medicine[1]; ?> </td>
                                                <td class=""><?php echo $single_medicine[3] . ' - ' . $single_medicine[4]; ?> </td>
                                                <td class="text-right"><?php echo $single_medicine[2] ?> </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>


                    </div>

                </div>
 -->

            <!-- </div> -->

         <!--    <div class="panel-body prescription_footer">
                <div class="col-md-4 pull-left prescription_footer1"> <hr> <?php echo lang('signature'); ?></div>
                <div class="col-md-8 pull-right text-right">
                    <h3 class='hospital'><?php echo $settings->title; ?></h3>
                    <h5><?php echo $settings->address; ?></h5>
                    <h5><?php echo $settings->phone; ?></h5>
                </div>
            </div>   -->


    <!--     </div> -->



        <!-- invoice start-->
        <section class="col-md-4 margin_top">
            <div class="panel-primary clearfix">

                <div class="panel_button clearfix">
                    <div class="text-center invoice-btn no-print pull-left">
                        <a class="btn btn-info btn-lg invoice_button" onclick="javascript:window.print();"><i class="fa fa-print"></i> <?php echo lang('print'); ?> </a>
                    </div>
                </div>

                <div class="panel_button clearfix">
                    <div class="text-center invoice-btn no-print pull-left download_button">
                        <a class="btn btn-info btn-sm detailsbutton pull-left download" id="download"><i class="fa fa-download"></i> <?php echo lang('download'); ?> </a>
                    </div>
                </div>
                <div class="panel_button clearfix">
                    <?php if ($this->ion_auth->in_group(array('admin'))) { ?>
                        <div class="text-center invoice-btn no-print pull-left">
                            <a class="btn btn-info btn-lg info" href='prescription/all'><i class="fa fa-medkit"></i> <?php echo lang('all'); ?> <?php echo lang('prescription'); ?> </a>
                        </div>
                    <?php } ?>
                    <?php if ($this->ion_auth->in_group(array('Doctor'))) { ?>
                        <div class="text-center invoice-btn no-print pull-left">
                            <a class="btn btn-info btn-lg info" href='prescription'><i class="fa fa-medkit"></i> <?php echo lang('all'); ?> <?php echo lang('prescriptions'); ?> </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="panel_button">
                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                        <div class="text-center invoice-btn no-print pull-left">
                            <a class="btn btn-info btn-lg green" href="prescription/addPrescriptionView"><i class="fa fa-plus-circle"></i> <?php echo lang('add_prescription'); ?> </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section> 
        <!-- invoice end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->


<script src="common/js/codearistos.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript">var id_pres = "<?php echo $prescription->id; ?>";</script>
<script src="common/extranal/js/prescription/prescription_view_1.js"></script>

