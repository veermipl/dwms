<!--sidebar end-->
<!--main content start-->
<style>
    .error {
        color: red;
    }
</style>
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="col-md-7 row">
            <header class="panel-heading">
                <?php
                if (!empty($doctor->id))
                    echo lang('edit_doctor');
                else
                    echo lang('add_doctor');
                ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">

                        <div class="col-lg-12">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <?php echo validation_errors(); ?>
                                <?php echo $this->session->flashdata('feedback'); ?>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>

                        <form role="form" action="doctor/addNew" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required value='<?php
                                                                                                    if (!empty($setval)) {
                                                                                                        echo set_value('name');
                                                                                                    }
                                                                                                    if (!empty($doctor->name)) {
                                                                                                        echo $doctor->name;
                                                                                                    }
                                                                                                    ?>' placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" required value='<?php
                                                                                                        if (!empty($setval)) {
                                                                                                            echo set_value('email');
                                                                                                        }
                                                                                                        if (!empty($doctor->email)) {
                                                                                                            echo $doctor->email;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('password'); ?> <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required placeholder="********">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" required value='<?php
                                                                                                        if (!empty($setval)) {
                                                                                                            echo set_value('address');
                                                                                                        }
                                                                                                        if (!empty($doctor->address)) {
                                                                                                            echo $doctor->address;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" required value='<?php
                                                                                                        if (!empty($setval)) {
                                                                                                            echo set_value('phone');
                                                                                                        }
                                                                                                        if (!empty($doctor->phone)) {
                                                                                                            echo $doctor->phone;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('profile'); ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="profile" required value='<?php
                                                                                                        if (!empty($setval)) {
                                                                                                            echo set_value('profile');
                                                                                                        }
                                                                                                        if (!empty($doctor->profile)) {
                                                                                                            echo $doctor->profile;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                                <input type="file" name="img_url">
                            </div>
                            <input type="hidden" name="id" value='<?php
                                                                    if (!empty($doctor->id)) {
                                                                        echo $doctor->id;
                                                                    }
                                                                    ?>'>

                            <div class="text-right">
                                <button type="submit" name="submit" class="btn btn-info"><?php echo lang('submit'); ?></button>
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