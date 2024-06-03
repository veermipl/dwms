<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
          <link href="common/extranal/css/doctor/doctor.css" rel="stylesheet">
         
        <section class="panel">
            <header class="panel-heading">
                <?php echo 'Tariff'; ?>    
                <div class="col-md-4 no-print pull-right"> 
                    <a href="tariff.php"></a>
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
                                <th><?php echo lang('doctor'); ?> <?php echo lang('name'); ?></th>
                             
                                <th><?php echo 'Membership' ; ?></th>
                               
                                <th><?php echo lang('amount'); ?></th>
                                <th ><?php echo lang('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($tariff as $key => $tar) {
                                    
                                ?>
                                <tr>
                                 <td> <?php echo $this->doctor_model->getDoctorById($tar->doctor_id)->name; ?></td>
                                
                                <td><?php echo $this->finance_model->getMembershipById($tar->membership_details_id)->name; ?></td></td>  
                           
                                 <td><?php echo $tar->amount;?></td>
                               
                                 <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="<?php echo $tar->id;?>"><i class="fa fa-edit"></i> <?php echo lang('edit'); ?></button>  

                                                <a class="btn btn-info btn-xs btn_width delete_button" href="finance/deleteTariff?id=<?php echo $tar->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"> </i> <?php echo lang('delete'); ?></a>
                                            </td>
                                        <?php } ?>
                                </tr>
                            <?php } ?>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">  <?php echo 'Add New Tariff'; ?> </h4>
            </div>
            <div class="modal-body row">
                <form role="form" action="finance/addNewTariff" class="clearfix" method="post" enctype="multipart/form-data">
                   
                   <div class="col-md-6 panel doctor_div">
                        
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label> 
                        
                        <select class="form-control m-bot15" id="adoctors" name="doctor_id" value=''>  
                        </select>

                    </div>

                     <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> Membership </label> 
                        <select class="form-control m-bot15" id="alocation" name="membership_details_id" value=''>   
                            <option value="">Please Select Membership</option>
                            <?php 

                            $locsa = $tar->membership_details_id;

                            foreach($membership as $loc)
                            { 
                            if($locsa == $loc->id){
                              echo '<option value="'.$loc->id.'" selected>'.$loc->name. '</option>';
                            }else{
                                    echo '<option value="'.$loc->id.'">'.$loc->name. '</option>';
                            }
                            }
                            ?>

                        </select>
                    </div>

                     <div class="col-md-6">
                       
                        <label for="exampleInputEmail1">  <?php echo 'Amount'; ?></label> 
                        
                        <input type="text" class="form-control" name="amount"  value='' placeholder="Amount">

                    </div>

                    <div class="col-md-6">
                       
                       <label for="exampleInputEmail1">  <?php echo 'Status'; ?></label> 
                       
                       <select name="status" class="form-control m-bot15">

                        <option value="">Select Status</option> 
                        <option value="active">Active</option> 
                        <option value="inactive">Inactive</option> 
                       
                      </select>

                   </div>


                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Accountant Modal-->







<!-- Edit Event Modal-->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo 'Edit Tariff'; ?> </h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editTimeSlotForm" class="clearfix" action="finance/addNewTariff" method="post" enctype="multipart/form-data">
                   
                  
                    <div class="col-md-6 panel doctor_div">
                        
                        <label for="exampleInputEmail1">  <?php echo lang('doctor'); ?></label> 
                        
                        <select class="form-control m-bot15 js-example-basic-single" name="doctor_id" value=''>  
                          <option value="">Select .....</option>
                            <?php foreach ($doctors as $doctor) { ?>
                                <option value="<?php echo $doctor->id; ?>"<?php
                                if (!empty($tar->doctor_id)) {
                                    if ($tar->doctor_id == $doctor->id) {
                                        echo 'selected';
                                    }
                                }
                                ?>>
                            <?php echo $doctor->name; ?> </option>
                            <?php } ?>
                                </select>
                    </div>  
                   

                     <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> Membership </label> 
                        <select class="form-control m-bot15" id="alocation" name="membership_details_id" value=''>   
                            <option value="">Please Select Membership</option>
                            <?php 

                             $locsa = $tar->membership_details_id;


                            foreach($membership as $loc)
                            { 
                            if($locsa == $loc->id){
                              echo '<option value="'.$loc->id.'" selected>'.$loc->name. '</option>';
                            }else{
                              echo '<option value="'.$loc->id.'">'.$loc->name. '</option>';
                            }
                            }
                            ?>

                        </select>
                    </div>

                     <div class="col-md-6">
                       
                        <label for="exampleInputEmail1">  <?php echo 'Amount'; ?></label> 
                        
                        <input type="text" class="form-control" name="amount"  value='' placeholder="Amount">

                    </div>

                    <div class="col-md-6">
                       
                       <label for="exampleInputEmail1">  <?php echo 'Status'; ?></label> 
                       
                       <select name="status" class="form-control m-bot15">

                        <option value="">Select Status</option> 
                        <option value="active">Active</option> 
                        <option value="inactive">Inactive</option> 
                       
                      </select>

                   </div>
                   

                    <input type="hidden" name="id" value='<?php echo $tar->id;?>'>
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit Event Modal-->



<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('doctor'); ?> <?php echo lang('info'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editDoctorForm1" class="clearfix" action="doctor/addNew" method="post" enctype="multipart/form-data">

                    <div class="form-group last col-md-6">
                        <div class="">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new thumbnail img_class">
                                    <img src="" id="img1" alt="" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail img_url"></div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>
                        <div class="nameClass"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>
                        <div class="emailClass"></div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                        <div class="addressClass"></div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                        <div class="phoneClass"></div>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"><?php echo lang('profile'); ?></label>
                        <div class="profileClass"></div>
                    </div>


                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>




<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">var select_doctor = "<?php echo lang('select_doctor'); ?>";</script>
<script type="text/javascript">var select_patient = "<?php echo lang('select_patient'); ?>";</script>
<script type="text/javascript">var language = "<?php echo $this->language; ?>";</script>

<script src="common/extranal/js/finance/tariff.js"></script>

