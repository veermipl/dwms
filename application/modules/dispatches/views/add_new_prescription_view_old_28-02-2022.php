
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
        <section class="col-md-8">
            <header class="panel-heading">
                <?php
              //  print_r($prescription); 
                if (!empty($prescription->id))
                    echo 'Update dispatched';
                ?>
            </header>
            <div class="panel col-md-12">
                <div class="adv-table editable-table ">
                    <div class="clearfix">
                        <?php echo validation_errors(); ?>
                        <form role="form" action="dispatches/updateDispatches" class="clearfix" method="post" enctype="multipart/form-data">
                            <div class="">
                                
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"> <?php echo lang('patient'); ?></label>
                                    <select class="form-control m-bot15" id="patientchoose" name="patient" value=''>
                                        <?php if (!empty($prescription->patient)) { ?>
                                            <option value="<?php echo $patients->id; ?>" selected="selected"><?php echo $patients->name; ?> - (<?php echo lang('id'); ?> : <?php echo $patients->id; ?>)</option>  
                                        <?php } ?>
                                        <?php
                                        if (!empty($setval)) {
                                            $patientdetails = $this->db->get_where('patient', array('id' => set_value('patient')))->row();
                                            ?>
                                            <option value="<?php echo $patientdetails->id; ?>" selected="selected"><?php echo $patientdetails->name; ?> - (<?php echo lang('id'); ?> : <?php echo $patientdetails->id; ?>)</option>
                                        <?php }
                                        ?>
                                    </select>
                                </div> 
                                <?php if (!$this->ion_auth->in_group('Doctor')) { ?>
                                    <div class="form-group col-md-4"> 
                                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                                        <select class="form-control m-bot15" id="doctorchoose" name="doctor" value=''>
                                            <?php if (!empty($prescription->doctor)) { ?>
                                                <option value="<?php echo $doctors->id; ?>" selected="selected"><?php echo $doctors->name; ?> - (<?php echo lang('id'); ?> : <?php echo $doctors->id; ?>)</option>  
                                            <?php } ?>
                                            <?php
                                            if (!empty($setval)) {
                                                $doctordetails1 = $this->db->get_where('doctor', array('id' => set_value('doctor')))->row();
                                                ?>
                                                <option value="<?php echo $doctordetails1->id; ?>" selected="selected"><?php echo $doctordetails1->name; ?> -(<?php echo lang('id'); ?> : <?php echo $doctordetails1->id; ?>)</option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group col-md-4"> 
                                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                                        <?php if (!empty($prescription->doctor)) { ?>
                                            <select class="form-control m-bot15" name="doctor" value=''>
                                                <option value="<?php echo $doctors->id; ?>" selected="selected"><?php echo $doctors->name; ?> - (<?php echo lang('id'); ?> : <?php echo $doctors->id; ?>)</option>  
                                            </select>
                                        <?php } else { ?>
                                            <select class="form-control m-bot15" id="doctorchoose1" name="doctor" value=''>
                                                <?php if (!empty($prescription->doctor)) { ?>
                                                    <option value="<?php echo $doctors->id; ?>" selected="selected"><?php echo $doctors->name; ?> - (<?php echo lang('id'); ?> : <?php echo $doctors->id; ?>)</option>  
                                                <?php } ?>
                                                <?php if (!empty($doctordetails)) { ?>
                                                    <option value="<?php echo $doctordetails->id; ?>" selected="selected"><?php echo $doctordetails->name; ?> - (<?php echo lang('id'); ?> : <?php echo $doctordetails->id; ?>)</option>  
                                                <?php } ?>
                                                <?php
                                                if (!empty($setval)) {
                                                    $doctordetails1 = $this->db->get_where('doctor', array('id' => set_value('doctor')))->row();
                                                    ?>
                                                    <option value="<?php echo $doctordetails1->id; ?>" selected="selected"><?php echo $doctordetails1->name; ?> - (<?php echo lang('id'); ?> : <?php echo $doctordetails->id; ?>)</option>
                                                <?php }
                                                ?>
                                            </select>
                                        <?php } ?>



                                    </div>
                                <?php } ?> 

                                <div class="form-group col-md-6">
                                    <label class="control-label"><?php echo lang('history'); ?></label>
                                    <textarea class="form-control ckeditor" id="editor1" name="symptom" value="" rows="50" cols="20"><?php
                                        if (!empty($setval)) {
                                            echo set_value('symptom');
                                        }
                                        if (!empty($prescription->symptom)) {
                                            echo $prescription->symptom;
                                        }
                                        ?></textarea>
                                </div>
 


                              <div class="form-group col-md-6">
                                    <label class="control-label"><?php echo lang('note'); ?></label>
                                    <textarea class="form-control ckeditor" id="editor3" name="note" value="" rows="30" cols="20"><?php
                                        if (!empty($setval)) {
                                            echo set_value('note');
                                        }
                                        if (!empty($prescription->note)) {
                                            echo $prescription->note;
                                        }
                                        ?></textarea>
                                </div>

                                <div class="form-group col-md-12 medicine_block">
                                    <label class="control-label col-md-3"> <?php echo lang('medicine'); ?></label>
                                    <div class="col-md-9 medicine_div">
                                        <?php if (empty($prescription->medicine)) { ?>
                                            <select class="form-control m-bot15 medicinee"  id="my_select1_disabled" name="category" value=''>

                                            </select>
                                        <?php } else { ?>
                                            <select name="category"  class="form-control m-bot15 medicinee"  multiple="multiple" id="my_select1_disabled" >
                                                <?php
                                                if (!empty($prescription->medicine)) {

                                                  
                                                    $prescription_medicine = explode('###', $prescription->medicine);
                                                    foreach ($prescription_medicine as $key => $value) {
                                                        $prescription_medicine_extended = explode('***', $value);
                                                        $medicine = $this->medicine_model->getMedicineById($prescription_medicine_extended[0]);
                                                        ?>
                                                        <option value="<?php echo $medicine->id . '*' . $medicine->name; ?>"  <?php echo 'data-dosage="' . $prescription_medicine_extended[1] . '"' . 'data-frequency="' . $prescription_medicine_extended[2] . '"data-days="' . $prescription_medicine_extended[3] . '"data-instruction="' . $prescription_medicine_extended[4] . '"'; ?> selected="selected">
                                                            <?php echo $medicine->name; ?>
                                                        </option>                

                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div> 

                          <div class="form-group col-md-12 panel-body medicine_block">
                                    <label class="control-label col-md-3"><?php echo lang('medicine'); ?></label>
                                    <div class="col-md-9 medicine pull-right">

                                    </div>

                                </div>



                              <div class="form-group col-md-12">
                                    <label class="control-label"><?php echo lang('advice'); ?></label>
                                    <textarea class="form-control ckeditor" id="editor3" name="advice" value="" rows="30" cols="20"><?php
                                        if (!empty($setval)) {
                                            echo set_value('advice');
                                        }
                                        if (!empty($prescription->advice)) {
                                            echo $prescription->advice;
                                        }
                                        ?>
                                    </textarea>
                                </div> 


                                  
                             <?php 
                             $query=     $this->db->where('id',$_GET['val']);
                             $query=     $this->db->get('dispatches');
                             $result= $query->row_array();

                          //  print_r($result); 

                             ?>
                                <input type="hidden" name="id" value='<?php echo $_GET['id'];?>'>
                                <input type="hidden" name="did" value='<?php echo $_GET['val'];?>'>

                            

                                    <div class="form-group col-md-4"> 
                                        <label for="exampleInputEmail1"> <?php echo 'IS Dispatched'; ?></label>
                                          <input type="checkbox" name="status" value='1' class="form-control" <?php if($result['status']='1'){ echo 'checked';}?>>
                                    </div>

                                    <div class="form-group col-md-4"> 
                                        <label for="exampleInputEmail1"> <?php echo 'Tracking ID'; ?></label>
                                          <input type="text" name="tracking_id" value='<?php 
                                         if(!empty($result['tracking_id'])){
                                          echo $result['tracking_id'];
                                         }

                                           ?>' class="form-control">
                                           <input type="hidden" name="admin" value='admin'>
                                           <input type="hidden" name="prescription_id" value='<?php echo $prescription->id;?>'>
                                    </div>

                                    
                                    
      
                                <div class="form-group col-md-4"> 
                                    <label for="exampleInputEmail1"> <?php echo lang('date'); ?></label>
                                    <input type="text" class="form-control default-date-picker" name="date"  value='<?php
                                    if (!empty($setval)) {
                                        echo set_value('date');
                                    }
                                    if (!empty($prescription->date)) {
                                        echo date('d-m-Y', $prescription->date);
                                    }
                                    ?>' placeholder="" readonly="">
                                </div> 
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                                </div>
                            </div>

                            <div class="col-md-5">

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
<script type="text/javascript">var select_medicine = "<?php echo lang('medicine'); ?>";</script>
<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>
<script type="text/javascript">var select_patient = "<?php echo lang('select_patient'); ?>";</script>
<script type="text/javascript">var language = "<?php echo $this->language; ?>";</script>

<script src="common/extranal/js/prescription/add_new_prescription_view.js"></script>


