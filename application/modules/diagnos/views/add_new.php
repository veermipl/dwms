<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <?php
                if (!empty($slide->id))
                    echo '<i class="fa fa-edit"></i> ' . lang('edit_diagnos');
                else
                    echo '<i class="fa fa-plus-circle"></i> ' . lang('add_diagnos');
                ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix">

                        <div class="col-lg-12">
                            <section class="panel">
                                <div class="panel-body">
                                    <div class="col-lg-12">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <?php echo validation_errors(); ?>
                                            <?php echo $this->session->flashdata('feedback'); ?>
                                        </div>
                                        <div class="col-lg-3"></div>
                                    </div>
                                    <form role="form" action="diagnos/addNew" method="post" enctype="multipart/form-data">
                                        <div class="form-group col-md-6">    
                                            <label for="exampleInputEmail1"><?php echo lang('title'); ?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="title"  required value='<?php
                                            if (!empty($setval)) {
                                                echo set_value('title');
                                            }
                                            if (!empty($slide->title)) {
                                                echo $slide->title;
                                            }
                                            ?>' placeholder="">   
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1"><?php echo lang('description'); ?></label>
                                            <input type="text" class="form-control" name="description"  value='<?php
                                            if (!empty($setval)) {
                                                echo set_value('description');
                                            }
                                            if (!empty($slide->description)) {
                                                echo $slide->description;
                                            }?>' placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1"><?php echo lang('status'); ?></label>
                                            <select class="form-control m-bot15" name="status" value=''>
                                                <option value="Active" <?php
                                                if (!empty($setval)) {
                                                    if ($slide->status == set_value('status')) {
                                                        echo 'selected';
                                                    }
                                                }
                                                if (!empty($slide->status)) {
                                                    if ($slide->status == 'Active') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?> > <?php echo lang('active'); ?> 
                                                </option>
                                                 <option value="Inactive" <?php
                                                if (!empty($setval)) {
                                                    if ($slide->status == set_value('status')) {
                                                        echo 'selected';
                                                    }
                                                }
                                                if (!empty($slide->status)) {
                                                    if ($slide->status == 'Inactive') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?> > <?php echo lang('in_active'); ?> 
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="exampleInputEmail1">Image</label>
                                            <input type="file" name="img_url">
                                        </div>
                                        <input type="hidden" name="id" value='<?php 
                                        if (!empty($slide->id)) {
                                            echo $slide->id;
                                        }
                                        ?>'>
                                      <div class="col-lg-12">
                                            <button type="submit" name="submit" class="btn btn-info">Submit</button>
                                      </div>
                                    </form>
                                </div>
                            </section>
                        </div>  
                    </div> 
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
</section>

