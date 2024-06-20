<!--sidebar end-->
<!--main content start-->

<style>
	input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
textarea.form-control{
	height: 100px !important;
}
ul.checkbox-design{
	display: flex; 
	flex-wrap: wrap; 
	padding: 0; 
	list-style: none;
	min-height: 150px;
    height: 150px;
    overflow-y: scroll;
    max-height: 300px;
}
ul.checkbox-design li {
    background: #8b0a0a;
    margin: 8px;
    padding: 2px 7px;
    border-radius: 10px;
}
ul.checkbox-design li label {
    margin-bottom: 0;
    color:#fff;
    font-weight: 500;
    font-size: 13px;
}
ul.checkbox-design li input{}
</style>
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/appointment/appointment.css" rel="stylesheet">

        <section class="panel">
            <header class="panel-heading">
            	<div class="row">
            		<div class="col-md-8">
            			<?php echo lang('prescription'); ?>
            		</div>
            		<div class="col-md-4">
            			<?php if($invData['prescription_generate'] == 1): ?>
            				<a href="appointment/downloadInvoicePrescription?appointment=<?=$invData['apt_id']?>" class="btn btn-primary" target="_blank" title="">Print</a>
            			<?php endif;?>
            		</div>
            	</div>
                
            </header>

           	<div class="card p-5">
           		
	           		<div class="row">
	           			
	           			<div class="col-md-12">
			           		

           					<table class="table table-bordered table-responsive table-striped">
           						<thead>
           							<tr>
           								<!-- <th>Doctor Name</th> -->
           								<th>Patient Name</th>
           								<th>Sex</th>
           								<th>Age </th>
           								<th>Date Of Birth</th>
           								<th>Date </th>
           								
           							</tr>
           							
           						</thead>
           						<tbody>
           							<tr>
           								<!-- <td><?=ucwords($doctorData->name) ?? ''?></td> -->
           								<td><?=ucwords($invData['patient_name']) ?? ''?></td>
           								<td><?=$patientData->sex ?? '-'?></td>
           								<td><?=$patientData->age ? $patientData->age : '-'?></td>
           								<td><?=$patientData->birthdate ? $patientData->birthdate : '-'?></td>
           								<td><?=date('d-m-Y')?></td>
           							</tr>
           						</tbody>
           					</table>
			           				

			           		
			           	</div>
			           	<div class="col-md-12">
			           		<form action="appointment/patientPrescriptionStore" method="post" accept-charset="utf-8">
			           			<input type="hidden" name="appointment_id" value="<?=$invData['apt_id'] ?? 0?>">
			           			<input type="hidden" name="patient_id" value="<?=$invData['patient'] ?? 0?>">
			           			<input type="hidden" name="doctor_id" value="<?=$invData['doctor'] ?? 0?>">
			           			<div class="row">

			           				<?php $diagnos_ids = explode(',', $aptData->diagnos_ids) ?? []; ?>
			           				<div class="col-md-12">
				           				<div class="form-group mr-3">
					           				<label><?=lang('diagnosis_list')?></label>
					           				<hr>
					           				<?php if($diagnos && count($diagnos) >0): ?>
					           					<!-- <input type="text" id="search-box" class="form--control" placeholder="Search diagnoses..."> -->
					           					<ul class="checkbox-design">
					           					<?php foreach($diagnos as $key => $row): ?>
					           							<li>
					           								<div class="chckbox">
					           									<label for="fro<?=$row->id?>"><?=ucwords($row->title) ?? ''?></label>
					           									<input type="checkbox" name="diagnos_ids[]" class="form--control" <?=(in_array($row->id, $diagnos_ids)) ? 'checked' : '' ?>  value="<?=$row->id?>" id="fro<?=$row->id?>">
					           								</div>
					           							</li>
					           					<?php endforeach; ?>
					           					</ul>
					           				<?php endif;?>
					           			</div>
				           			</div>
			           				
			           				<div class="col-md-12">
				           				<div class="form-group mr-3">
					           				<label><?=lang('diagnos')?></label>
					           				<textarea class="form-control" name="diagnosis" placeholder="Diagnosis"><?=$aptData->diagnosis ?? ''?></textarea>
					           			</div>
				           			</div>

				           			<div class="col-md-12">
				           				<div class="form-group mr-3">
					           				<label>Prescription</label>
					           				<textarea class="form-control" name="prescription" placeholder="Prescription"><?=$aptData->prescription ?? ''?></textarea>
					           			</div>
				           			</div>

				           			<div class="col-md-8">
				           				<div class="form-group">
					           				<button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
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
<!--footer start-->




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