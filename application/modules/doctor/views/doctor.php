<!--sidebar end-->

<!--main content start-->





<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <link href="common/extranal/css/doctor/doctor.css" rel="stylesheet">



        <section class="panel">

            <header class="panel-heading">

                <?php echo lang('doctor'); ?>

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

                <div class="adv-table editable-table">

                    <div class="space15"></div>

                    <table class="table table-striped table-hover table-bordered" id="editable-sample">

                        <thead>

                            <tr>

                                <th><?php echo lang('id'); ?></th>

                                <th><?php echo lang('name'); ?></th>

                                <th><?php echo lang('email'); ?></th>

                                <th><?php echo lang('phone'); ?></th>

                                <th><?php echo lang('profile'); ?></th>

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



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('add_new_doctor'); ?></h4>

            </div>

            <div class="modal-body row">

                <form role="form" action="doctor/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="" required>

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

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="address" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="" required>

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('profile'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="profile" value='' placeholder="" required>

                    </div>

                    <div class="form-group last col-md-6">

                        <label class="control-label">Image Upload </label>

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

                <h4 class="modal-title"> <?php echo lang('edit_doctor'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" id="editDoctorForm" class="clearfix" action="doctor/addNew" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="name" value='' placeholder="" required>

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

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="address" value='' placeholder="" required>

                    </div>

                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="" required>

                    </div>



                    <div class="form-group col-md-6">

                        <label for="exampleInputEmail1"><?php echo lang('profile'); ?> <span class="text-danger">*</span></label>

                        <input type="text" class="form-control" name="profile" value='' placeholder="" required>

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

                                Image Upload

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

                <h4 class="modal-title"> <?php echo lang('doctor'); ?> <?php echo lang('info'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" id="editDoctorForm1" class="clearfix" action="doctor/addNew" method="post" enctype="multipart/form-data">



                    <div class="row">

                        <div class="col-lg-4">

                            <div class="">

                                <div class="imageWrapper">

                                    <div class=" last ">

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

                                <div class="">

                                    <div class="">

                                        <label for="exampleInputEmail1" class="mb-0"><?php echo lang('profile'); ?></label>

                                        <div class="profileClass"></div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-8 pdl-0">



                            <div class="row">

                                <div class="col-lg-12">

                                    <div class="col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('name'); ?> : </label>

                                        <span class="nameClass"></span>

                                    </div>

                                    <div class="col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('email'); ?> : </label>

                                        <span class="emailClass"></span>

                                    </div>

                                    <div class="adres-info_doc col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('address'); ?> : </label>

                                        <span class="addressClass"></span>

                                    </div>



                                    <div class="col-md-12">

                                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?> : </label>

                                        <span class="phoneClass"></span>

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



<script src="common/extranal/js/doctor/doctor.js"></script>