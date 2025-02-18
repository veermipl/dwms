<!--sidebar end-->

<!--main content start-->





<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <link href="common/extranal/css/doctor/doctor.css" rel="stylesheet">



        <section class="panel">

            <header class="panel-heading">

                <?php echo lang('company'); ?>

                <div class="col-md-4 no-print pull-right">

                    <a href="doctor.php"></a>

                    <a data-toggle="modal" href="#myModal">

                        <div class="btn-group pull-right">

                            <button id="" class="btn green btn-xs">
                                <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                            </button>

                            <!-- <a href="doctor/addNew">
                                <button class="btn green btn-xs">
                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                                </button>
                            </a> -->

                        </div>

                    </a>

                </div>

            </header>

            <div class="panel-body">

                <div class="adv-table editable-table  ">

                    <div class="space15"></div>

                    <div class="table-responsive- " id="agt_rt">

                        <table class="table table-striped table-hover table-bordered " id="editable-sample">

                            <thead>

                                <tr>

                                    <th><?php echo lang('company'); ?> <?php echo lang('id'); ?></th>

                                    <th><?php echo lang('name'); ?></th>

                                    <th><?php echo lang('email'); ?></th>

                                    <th><?php echo lang('phone'); ?></th>

                                    <th><?php echo lang('registration_number'); ?></th>

                                    <th class="no-print"><?php echo lang('options'); ?></th>

                                </tr>

                            </thead>

                            <tbody>









                            </tbody>

                        </table>

                    </div>

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

                <h4 class="modal-title"> <?php echo lang('add_new_company'); ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" action="company/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('registration_number'); ?> </label>

                        <input type="text" class="form-control" name="registration_number" value='' placeholder="" >

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="email" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('password'); ?> <span class="text-danger">*</span></label>

                        <input type="password" class="form-control" name="password" placeholder="********" required>

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?> </label>

                        <input type="text" class="form-control" name="address" value='' placeholder="" >

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?> </label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="" >

                    </div>



                    

                    <div class="form-group last col-md-6">

                        <label class="control-label">Upload Logo </label>

                        <div class="">

                            <div class="fileupload fileupload-new" data-provides="fileupload">

                                <!-- <div class="fileupload-new thumbnail img_class">

                                    <img src="" alt="" />

                                </div> -->

                                <div class="fileupload-preview fileupload-exists thumbnail img_url"></div>

                                <div>

                                    <span class="btn btn-white btn-file1">

                                        <!-- <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>

                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span> -->

                                        <input type="file" class="default" name="img_url" />

                                    </span>

                                    <!-- <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a> -->

                                </div>

                            </div>



                        </div>

                    </div>

                    <div class="col-md-12  bdr-top-btn">

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

                <h4 class="modal-title"> <?php echo lang('edit_company'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" id="editDoctorForm" class="clearfix" action="company/addNew" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('registration_number'); ?> </label>

                        <input type="text" class="form-control" name="registration_number" value='' placeholder="" >

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="email" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>

                        <input type="password" class="form-control" name="password" placeholder="********">

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?> </label>

                        <input type="text" class="form-control" name="address" value='' placeholder="" >

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?> </label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="" >

                    </div>



                    



                    <!-- <div class="form-group last col-md-6">

                        <label class="control-label">Image Upload</label>

                        <div class="">

                            <div class="fileupload fileupload-new" data-provides="fileupload">

                                <div class="fileupload-new thumbnail img_class">

                                    <img src="" id="img" alt="" />

                                </div>

                                <div class="fileupload-preview fileupload-exists thumbnail img_url"></div>

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

                    </div> -->



                    <div class="form-group last col-md-12">

                        <label for="images" class="drop-container">

                            <span class="drop-title">

                                Upload Logo

                                <span class="text-danger">*</span>

                            </span>

                            <input class="py-2" type="file" id="img_url" name="img_url" value="" accept="image/*" onchange="loadDocFile(event,'img_url_output','img_url_error')">

                            <div class="file-note"></div>

                            <span id="img_url_error" style="font-size:12px; color:red"></span>

                            <img id="img_url_output" style="display:none;" width="100" height="100" />

                        </label>

                    </div>



                    <input type="hidden" name="id" value=''>

                    <div class="form-group col-md-12 bdr-top-btn">

                        <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>

                    </div>

                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>

<!-- Edit Event Modal-->







<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog modal_medium">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('company'); ?> <?php echo lang('info'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" id="editDoctorForm1" class="clearfix" action="company/addNew" method="post" enctype="multipart/form-data">



                    <div class="row">

                        <div class="col-lg-4">

                            <div class="row">

                                <div class="col-lg-12 imageWrapper">

                                    <div class=" last col-md-6">

                                        <div class="">

                                            <div class="fileupload fileupload-new" data-provides="fileupload">

                                                <div class="fileupload-new thumbnail img_class1">

                                                    <img src="" id="img1" alt="" />

                                                </div>

                                                <div class="fileupload-preview fileupload-exists thumbnail img_url"></div>

                                            </div>



                                        </div>

                                    </div>

                                </div>

                                <div class="col-lg-12">

                                    <div class="form-group col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('registration_number'); ?></label>

                                        <div class="profileClass"></div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-8">



                            <div class="row">

                                <div class="col-lg-12">

                                    <div class="form-group col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>

                                        <div class="nameClass"></div>

                                    </div>

                                    <div class="form-group col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>

                                        <div class="emailClass"></div>

                                    </div>

                                    <div class="form-group col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>

                                        <div class="addressClass"></div>

                                    </div>



                                    <div class="form-group col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>

                                        <div class="phoneClass"></div>

                                    </div>





                                </div>

                            </div>

                        </div>

                    </div>





                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>











<script src="common/js/codearistos.min.js"></script>



<script type="text/javascript">

    var language = "<?php echo $this->language; ?>";

</script>



<script src="common/extranal/js/company/company.js"></script>