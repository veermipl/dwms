<section id="main-content">

    <section class="wrapper site-min-height">

        <link href="common/extranal/css/nurse.css" rel="stylesheet">

        <section class="panel">

            <header class="panel-heading">

                <?php echo lang('nurse'); ?>

                <div class="col-md-4 no-print pull-right">

                    <!-- <a data-toggle="modal" href="#myModal"> -->

                    <a href="nurse/addNew">

                        <div class="btn-group pull-right">

                            <button id="" class="btn green btn-xs">

                                <i class="fa fa-plus-circle"></i> <?php echo lang('add_nurse'); ?>

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

                                <th><?php echo lang('image'); ?></th>

                                <th><?php echo lang('name'); ?></th>

                                <th><?php echo lang('email'); ?></th>

                                <th><?php echo lang('address'); ?></th>

                                <th><?php echo lang('phone'); ?></th>

                                <th class="no-print"><?php echo lang('options'); ?></th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($nurses as $nurse) : ?>

                                <?php

                                    $nurse_user_id = $nurse->ion_user_id ?? '0';

                                    $userData = $this->nurse_model->getNurseUser($nurse_user_id);

                                    $nurse_profile_img = (trim($nurse->img_url)) ? $nurse->img_url : 'https://dwms.digitalnoticeboard.biz/uploads/image-removebg-preview.png';

                                ?>

                                <tr class="">

                                    <td class="img_class"><img class="img" src="<?= $nurse_profile_img; ?>"></td>

                                    <td> <?php echo $nurse->name; ?></td>

                                    <td><?php echo $nurse->email; ?></td>

                                    <td class="center"><?php echo $nurse->address; ?></td>

                                    <td><?php echo $nurse->phone; ?></td>

                                    <td class="no-print">

                                        <button type="button" class="btn btn-info btn-xs btn_width edit-btn-new editbutton" title="<?php echo lang('edit'); ?>" data-toggle="modal" data-id="<?php echo $nurse->id; ?>"><i class="fa fa-edit"> </i><?php echo lang('edit'); ?></button>

                                        <a type="button" class="btn btn-xs btn_width changeStatus <?= ($userData->active == '0' ? 'btn-danger' : 'btn-success') ?>" title="<?= lang('status') ?>" data-toggle="modal" data-id="<?= $nurse->id ?>" data-status="<?= $userData->active ?>" data-user_ion_id="<?= $userData->id ?>">

                                            <?= ($userData->active == '0' ? lang('in_active') : lang('active')) ?>

                                        </a>

                                        <a class="btn btn-info btn-xs btn_width delete_button delete-btn-new" title="<?php echo lang('delete'); ?>" href="nurse/delete?id=<?php echo $nurse->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"></i> <?php echo lang('delete'); ?></a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>



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













<!-- Add Nurse Modal-->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('add_nurse'); ?> </h4>

            </div>

            <div class="modal-body">

                <form role="form" action="nurse/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>

                        <input type="text" class="form-control" name="name" value=''>

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>

                        <input type="text" class="form-control" name="email" value='' placeholder="">

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>

                        <input type="password" class="form-control" name="password" placeholder="">

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>

                        <input type="text" class="form-control" name="address" value='' placeholder="">

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>

                        <input type="text" class="form-control" name="phone" value='' placeholder="">

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>

                        <input type="file" name="img_url">

                    </div>



                    <div class="form-group col-md-12">

                        <button type="submit" name="submit" class="btn btn-info pull-right row"><?php echo lang('submit'); ?></button>

                    </div>



                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>



<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('edit_nurse'); ?> </h4>

            </div>

            <div class="modal-body">

                <form role="form" id="editNurseForm" class="clearfix" action="nurse/addNew" method="post" enctype="multipart/form-data">

                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="name" value='' required placeholder="">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="email" value='' required  placeholder="">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>

                                <input type="password" class="form-control" name="password" placeholder="********">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="address" value='' required  placeholder="">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="phone" value='' required  placeholder="">

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>

                                <input type="file" name="img_url">

                                <img id="img_url_output" width="100" height="100" src="">

                            </div>

                        </div>

                    </div>



                    <input type="hidden" name="id" value=''>

                    <hr class="line-hr">

                    <div class="form-group col-md-12">

                        <button type="submit" name="submit" class="btn btn-info pull-right- row"><?php echo lang('submit'); ?></button>

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

<script src="common/extranal/js/nurse.js"></script>