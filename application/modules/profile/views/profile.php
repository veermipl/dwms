<!--sidebar end-->

<!--main content start-->

<section id="main-content">

    <section class="wrapper site-min-height">

        <!-- page start-->

        <div class="col-md-8 row">

            <section class="panel">

                <header class="panel-heading">

                    <?php echo lang('manage_profile'); ?>

                </header>

                <div class="panel-body">

                    <div class="adv-table editable-table ">

                        <div class="clearfix">

                            <?php echo validation_errors(); ?>

                            <form role="form" action="profile/addNew" class="clearfix" method="post" enctype="multipart/form-data">

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo lang('name'); ?></label>

                                    <input type="text" class="form-control" name="name"  value='<?php

                                    if (!empty($profile->username)) {

                                        echo $profile->username;

                                    }

                                    ?>' placeholder="">

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo lang('change_password'); ?></label>

                                    <input type="password" class="form-control" name="password"  placeholder="********">

                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo lang('change_password'); ?></label>
                                    <input type="password" class="form-control" name="confirm_password" placeholder="" autofill="no">
                                </div>


                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo lang('email'); ?></label>

                                    <input type="text" class="form-control" name="email"  value='<?php

                                    if (!empty($profile->email)) {

                                        echo $profile->email;

                                    }

                                    ?>' placeholder="" <?php

                                           if (!empty($profile->username)) {

                                               echo $profile->username;

                                           }

                                           ?>' placeholder="">

                                </div>

                                <input type="hidden" name="id" value='<?php

                                if (!empty($profile->id)) {

                                    echo $profile->id;

                                }

                                ?>'>

                                <div class="form-group">

                                    <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </section>

        </div>

        <!-- page end-->

    </section>

</section>

<!--main content end-->

<!--footer start-->



<script src="common/js/codearistos.min.js"></script>

<script src="common/extranal/js/ptofile.js"></script>

