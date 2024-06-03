<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <?php echo 'Location'; ?>
                <div class="col-md-4 clearfix pull-right">
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
                <div class="adv-table editable-table">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> <?php echo 'Name'; ?></th>
                                <th> <?php echo 'Code' ?></th>
                                <th> <?php echo 'Address' ?></th>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                    <th> <?php echo lang('options'); ?></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($location as $schedule) {
                                $i = $i + 1;
                            ?>
                                <tr class="">
                                    <td> <?php echo $i; ?></td>

                                    <td> <?php echo $schedule->name; ?></td>
                                    <td><?php echo $schedule->loc_code; ?></td>
                                    <td><?php echo $schedule->location_address; ?></td>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                        <td>
                                            <button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="<?php echo $schedule->id; ?>"><i class="fa fa-edit"></i> <?php echo lang('edit'); ?></button>
                                            <a class="btn btn-info btn-xs btn_width delete_button" href="schedule/deleteLocation?id=<?php echo $schedule->id; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"> </i> <?php echo lang('delete'); ?></a>
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




<!-- Add Time Slot Modal-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo 'Location'; ?> </h4>
            </div>
            <div class="modal-body row">
                <form role="form" action="schedule/addLocation" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location Name'; ?></label>
                        <input type="text" class="form-control" name="name" value=''>

                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location Code'; ?></label>
                        <input type="text" class="form-control" name="loc_code" value=''>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo 'Location Address'; ?></label>
                        <input type="text" class="form-control" name="location_address" value=''>
                    </div>

                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Add Time Slot Modal-->


<!-- Edit Time Slot Modal-->
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> <?php echo 'Location'; ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editLocationForm" action="schedule/addLocation" method="post" enctype="multipart/form-data">

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location Name'; ?> </label>
                        <input type="text" class="form-control" name="name" value=''>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location Code'; ?> </label>
                        <input type="text" class="form-control" name="loc_code" value=''>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1"> <?php echo 'Location Address'; ?></label>
                        <input type="text" class="form-control" name="location_address" value=''>
                    </div>

                    <input type="hidden" name="id" value='<?php echo $schedule->id; ?>'>
                    <button type="submit" name="submit" class="btn btn-info"> Update </button>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- Edit Time Slot Modal-->



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

<script src="common/extranal/js/schedule/location.js"></script>