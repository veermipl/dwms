<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <section class="panel">

            <header class="panel-heading">

                <?php

                if (!empty($nurse->id))

                    echo lang('edit_nurse');

                else

                    echo lang('add_nurse');

                ?>

            </header>

            <div class="panel-body col-md-12">

                <div class="adv-table editable-table ">

                    <div class="clearfix">



                        <div class="col-lg-12">

                            <div class="panel">

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-10">

                                            <?php echo validation_errors(); ?>

                                            <?php echo $this->session->flashdata('feedback'); ?>

                                        </div>

                                    </div>

                                    <form role="form" action="nurse/addNew" method="post" enctype="multipart/form-data">

                                        <div class="row">

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('name'); ?> <span class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" name="name"  value='<?php

                                                    if (!empty($setval)) {

                                                        echo set_value('name');

                                                    }

                                                    if (!empty($nurse->name)) {

                                                        echo $nurse->name;

                                                    }

                                                    ?>'>

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('email'); ?> <span class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" name="email"  value='<?php

                                                    if (!empty($setval)) {

                                                        echo set_value('email');

                                                    }

                                                    if (!empty($nurse->email)) {

                                                        echo $nurse->email;

                                                    }

                                                    ?>' placeholder="">

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('password'); ?> <span class="text-danger">*</span></label>

                                                    <input type="password" class="form-control" name="password"  placeholder="********">

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('address'); ?> <span class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" name="address"  value='<?php

                                                    if (!empty($setval)) {

                                                        echo set_value('address');

                                                    }

                                                    if (!empty($nurse->address)) {

                                                        echo $nurse->address;

                                                    }

                                                    ?>' placeholder="">

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('phone'); ?> <span class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" name="phone"  value='<?php

                                                    if (!empty($setval)) {

                                                        echo set_value('phone');

                                                    }

                                                    if (!empty($nurse->phone)) {

                                                        echo $nurse->phone;

                                                    }

                                                    ?>' placeholder="">

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>

                                                    <input type="file" name="img_url">

                                                </div>

                                            </div>

                                        </div>

                                        

                                        

                                        

                                        

                                        



                                        <input type="hidden" name="id" value='<?php

                                        if (!empty($nurse->id)) {

                                            echo $nurse->id;

                                        }

                                        ?>'>

                                        <button type="submit" name="submit" class="btn btn-info"><?php echo lang('submit'); ?></button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- page end-->

    </section>

</section>

<!--main content end-->



