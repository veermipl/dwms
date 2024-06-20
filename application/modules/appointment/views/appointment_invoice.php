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
</style>
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/appointment/appointment.css" rel="stylesheet">

        <section class="panel">
            <header class="panel-heading">
            	<div class="row">
            		<div class="col-md-8">
            			<?php echo lang('invoice_generate'); ?>
            		</div>
            		<div class="col-md-4">
            			<?php if($invData['generate_invoice'] == 1): ?>
            				<a href="appointment/downloadInvoice?appointment=<?=$invData['apt_id']?>" class="btn btn-primary" target="_blank" title="">Print</a>
            			<?php endif;?>
            		</div>
            	</div>
                
            </header>

           	<div class="card">
           		
	           		<div class="row">
	           			
	           			<div class="col-md-6">
			           		
			           			
           					<table class="table table-bordered table-responsive table-striped">
           						<thead>
           							<tr>
           								<th>Appointment ID</th><td><?=$invData['apt_id'] ?? ''?></td>
           							</tr>
           							<tr>
           								<th>Patient Name</th><td><?=$invData['patient_name'] ?? ''?></td>
           							</tr>
           							<tr>
           								<th>Appointment Date </th><td><?=$invData['apt_time_slot'] ?? ''?></td>
           							</tr>
           							<tr>
           								<th>Payment Mode</th><td><?=$invData['apt_payment_mode'] ?? '-'?></td>
           							</tr>
           							<tr>
           								<th>Appointment Amount </th><td><?=($invData['apt_amount'] > 0) ? $invData['apt_amount'] : '-'?></td>
           							</tr>
           						</thead>
           					</table>
			           				

			           		
			           	</div>
			           	<div class="col-md-6">
			           		<form action="appointment/UpdatePaymentByAppointmentID" method="post" accept-charset="utf-8">
			           			<input type="hidden" name="appointment_id" value="<?=$invData['apt_id'] ?? 0?>">
			           			<input type="hidden" name="patient_id" value="<?=$invData['patient'] ?? 0?>">
			           			<input type="hidden" name="doctor_id" value="<?=$invData['doctor'] ?? 0?>">
			           			<div class="row">
			           				<div class="col-md-8">
				           				<div class="form-group">
					           				<label>Generate Invoice</label>
					           				<select name="generate_invoice" class="form-control">
					           					<option value="1">Yes</option>
					           					<option value="0">No</option>
					           				</select>
					           			</div>
				           			</div>

				           			<div class="col-md-8">
				           				<div class="form-group">
					           				<label>Amount Type</label>
					           				<select name="payment_mode" class="form-control">
					           					<option value="cash">Cash</option>
					           					<option value="online">Online</option>
					           				</select>
					           			</div>
				           			</div>

				           			<div class="col-md-8">
				           				<div class="form-group">
					           				<label>Amount</label>
					           				<input type="number"  required name="amount" class="form-control">
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