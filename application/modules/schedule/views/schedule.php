<!--sidebar end-->

<!--main content start-->
<style type="text/css">
    .center {
        display: flex;
        margin: 0 auto;
        width: 100%;
        padding: 10px;
        letter-spacing: 2px;
        text-indent: 5px;
        text-align: center;
    }

    .mr-3 {
        margin-right: 15px;
    }
</style>

<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <?php echo lang('time_schedule'); ?>
                <div class="col-md-4 clearfix pull-right">
                    <a data-toggle="modal" href="#myModal">
                        <div class="btn-group pull-right">
                            <!-- <button id="" class="btn green btn-xs">
                                <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                            </button> -->

                            <a href="schedule/addScheduleView">
                                <button class="btn green btn-xs">
                                    <i class="fa fa-plus-circle"></i> <?php echo lang('add_new'); ?>
                                </button>
                            </a>
                        </div>
                    </a>
                </div>
            </header>

            <div class="panel-body">

                <div class="adv-table editable-table">

                    <div class="center">

                        <form method="get">
                            <input type="radio" id="html" onclick="if(this.checked){this.form.submit()}" name="weekday" value="all" <?php if ($radio1 == 'all') {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                            <label class="mr-3" for="">All</label>
                        </form>
                        <?php foreach ($weekday as $week) { ?>
                            <form method="get">
                                <input type="radio" id="html" onclick="if(this.checked){this.form.submit()}" name="weekday" value="<?php echo $week->name; ?>" <?php if ($radio1 == $week->name) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?>>

                                <label class="mr-3" for="<?php echo $week->name; ?>"><?php echo $week->name; ?></label><br>
                            </form>
                        <?php } ?>

                    </div>


                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> <?php echo lang('doctor'); ?></th>
                                <th> <?php echo lang('weekday'); ?></th>
                                <th> <?php echo lang('start_time'); ?></th>
                                <th> <?php echo lang('end_time'); ?></th>
                                <th> <?php echo "Slot Type"; ?></th>
                                <th> <?php echo lang('location'); ?></th>
                                <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                    <th> <?php echo lang('options'); ?></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($schedules as $schedule) {
                                $i = $i + 1;
                            ?>
                                <tr class="">
                                    <td> <?php echo $i; ?></td>
                                    <td> <?php echo $this->doctor_model->getDoctorById($schedule->doctor)->name; ?></td>
                                    <td> <?php echo $schedule->weekday; ?></td>
                                    <td> <?php echo $schedule->s_time; ?></td>
                                    <td> <?php echo $schedule->e_time; ?></td>
                                    <td> <?php echo $schedule->membership_code; ?></td>
                                    <td> <?php echo  $this->doctor_model->getLocationId($schedule->location_id)->name; ?></td>
                                    <?php if ($this->ion_auth->in_group(array('admin', 'Doctor'))) { ?>
                                        <td>
                                            <!-- <button type="button" class="btn btn-info btn-xs btn_width editbutton" data-toggle="modal" data-id="<?php echo $schedule->id; ?>"><i class="fa fa-edit"></i> <?php echo lang('edit'); ?></button> -->

                                            <a href="schedule/editSchedule/<?= $schedule->id ?>">
                                                <button class="btn btn-info btn-xs btn_width">
                                                    <i class="fa fa-edit"></i> <?php echo lang('edit'); ?>
                                                </button>
                                            </a>

                                            <a class="btn btn-info btn-xs btn_width delete_button" href="schedule/deleteSchedule?id=<?php echo $schedule->id; ?>&doctor=<?php echo $schedule->doctor; ?>&weekday=<?php echo $schedule->weekday; ?>&all=all" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"> </i> <?php echo lang('delete'); ?></a>
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
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo lang('schedule'); ?></h4>
            </div>
            <div class="modal-body row">
                <form role="form" action="schedule/addSchedule" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="col-md-6 panel">
                        <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        <select class="form-control m-bot15" id="doctorchoose" name="doctor" value=''>
                            <?php if (!empty($prescription->doctor)) { ?>
                                <option value="<?php echo $doctors->id; ?>" selected="selected"><?php echo $doctors->name; ?> - <?php echo $doctors->id; ?></option>
                            <?php } ?>
                            <?php
                            if (!empty($setval)) {
                                $doctordetails1 = $this->db->get_where('doctor', array('id' => set_value('doctor')))->row();
                            ?>
                                <option value="<?php echo $doctordetails1->id; ?>" selected="selected"><?php echo $doctordetails1->name; ?> - <?php echo $doctordetails1->id; ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                    <?php if ($_SESSION['location']) {
                        echo "<pre>";
                        var_dump($_SESSION);
                    } ?>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('weekday'); ?></label>
                        <select class="form-control m-bot15" id="weekday" name="weekday" value=''>
                            <option value="">Select .....</option>
                            <?php foreach ($weekday as $week) { ?>

                                <option value="<?php echo $week->name; ?>" <?php if ($_SESSION['weekday'] == $week->name) { ?> selected <?php } ?>><?php echo $week->name; ?></option>

                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location'; ?></label>
                        <select class="form-control m-bot15" id="location_id" name="location_id" value=''>
                            <option value="">Select .....</option>
                            <?php foreach ($location as $loc) { ?>
                                <option value="<?php echo $loc->id; ?>"><?php echo $loc->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('start_time'); ?></label>
                        <div class="input-group bootstrap-timepicker">
                            <input type="text" class="form-control timepicker-default s_time" name="s_time" value=''>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>

                    <div class="form-group col-md-6" style="margin-left:1px !important;">
                        <label for="exampleInputEmail1"><?php echo lang('appointment') ?> <?php echo lang('duration') ?> </label>
                        <select class="form-control m-bot15 duration" name="duration" value=''>

                            <option value="3" <?php
                                                if (!empty($settings->duration)) {
                                                    if ($settings->duration == '3') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>> 15 Minutes </option>

                            <option value="4" <?php
                                                if (!empty($settings->duration)) {
                                                    if ($settings->duration == '4') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>> 20 Minutes </option>

                            <option value="6" <?php
                                                if (!empty($settings->duration)) {
                                                    if ($settings->duration == '6') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>> 30 Minutes </option>

                            <option value="9" <?php
                                                if (!empty($settings->duration)) {
                                                    if ($settings->duration == '9') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>> 45 Minutes </option>

                            <option value="12" <?php
                                                if (!empty($settings->duration)) {
                                                    if ($settings->duration == '12') {
                                                        echo 'selected';
                                                    }
                                                }
                                                ?>> 60 Minutes </option>

                        </select>
                    </div>

                    <div class="form-group col-md-6" style="margin-left:-1px !important;">
                        <label for="exampleInputEmail1"> <?php echo lang('end_time'); ?></label>
                        <div class="input-group bootstrap-timepicker">
                            <input type="text" class="form-control timepicker-default e_time" name="e_time" value='' readonly="readonly">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo "Slot Type"; ?></label>
                        <div class="input-group">
                            <select class="form-control m-bot15" name="membership_code" value=''>

                                <option value="G" <?php
                                                    if (!empty($settings->membership_code)) {
                                                        if ($settings->membership_code == 'G') {
                                                            echo 'selected';
                                                        }
                                                    }
                                                    ?>> General </option>

                                <option value="VIP" <?php
                                                    if (!empty($settings->membership_code)) {
                                                        if ($settings->membership_code == 'VIP') {
                                                            echo 'selected';
                                                        }
                                                    }
                                                    ?>> VIP </option>

                            </select>
                        </div>

                    </div>

                    <input type="hidden" name="redirect" value='schedule'>
                    <input type="hidden" name="id" value=''>

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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-plus-circle"></i> <?php echo lang('edit'); ?> <?php echo lang('time_slot'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form" id="editTimeSlotForm" action="schedule/addSchedule" method="post" enctype="multipart/form-data">

                    <div class="col-md-12 col-md-6">
                        <div class="col-md-3 payment_label">
                            <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                        </div>

                        <select class="form-control m-bot15 js-example-basic-single" name="doctor" value=''>
                            <option value="">Select .....</option>
                            <?php foreach ($doctors as $doctor) { ?>
                                <option value="<?php echo $doctor->id; ?>" <?php
                                                                            if (!empty($schedule->doctor)) {
                                                                                if ($schedule->doctor == $doctor->id) {
                                                                                    echo 'selected';
                                                                                }
                                                                            }
                                                                            ?>><?php echo $doctor->name; ?> </option>
                            <?php } ?>
                        </select>

                    </div>

                    <div class="col-md-12 col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('weekday'); ?></label>

                        <select class="form-control m-bot15" id="weekday1" name="weekday" value=''>
                            <option value="">Select .....</option>
                            <?php
                            $weekname = $schedule->weekday;

                            foreach ($weekday as $week) {
                                if ($weekname == $week->name) {
                                    echo '<option value="' . $week->name . '" selected>' . $week->name . '</option>';
                                } else {
                                    echo '<option value="' . $week->name . '">' . $week->name . '</option>';
                                }
                            } ?>
                        </select>

                    </div>


                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo 'Location'; ?></label>
                        <select class="form-control m-bot15" id="location_id" name="location_id" value=''>
                            <option value="">Select .....</option>
                            <?php
                            $locsa = $schedule->location_id;

                            foreach ($location as $loc) {
                                if ($locsa == $loc->id) {
                                    echo '<option value="' . $loc->id . '" selected>' . $loc->name . '</option>';
                                } else {

                                    echo '<option value="' . $loc->id . '">' . $loc->name . '</option>';
                                }
                            } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="exampleInputEmail1"> <?php echo lang('start_time'); ?></label>
                        <div class="input-group bootstrap-timepicker">
                            <input type="text" class="form-control timepicker-default s_time1" name="s_time" value=''>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>

                    <div class="form-group col-md-6" style="margin-left:-282px;">
                        <label for="exampleInputEmail1"><?php echo lang('appointment') ?> <?php echo lang('duration') ?> </label>
                        <select class="form-control m-bot15 duration1" name="duration" value=''>

                            <option value="3" <?php
                                                if ($settings->duration == '3') {
                                                    echo 'selected';
                                                }

                                                ?>> 15 Minutes </option>

                            <option value="4" <?php

                                                if ($settings->duration == '4') {
                                                    echo 'selected';
                                                }

                                                ?>> 20 Minutes </option>

                            <option value="6" <?php

                                                if ($settings->duration == '6') {
                                                    echo 'selected';
                                                }

                                                ?>> 30 Minutes </option>

                            <option value="9" <?php

                                                if ($settings->duration == '9') {
                                                    echo 'selected';
                                                }
                                                ?>> 45 Minutes </option>

                            <option value="12" <?php

                                                if ($settings->duration == '12') {
                                                    echo 'selected';
                                                }
                                                ?>> 60 Minutes </option>

                        </select>
                    </div>



                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('end_time'); ?></label>
                        <div class="input-group bootstrap-timepicker">
                            <input type="text" class="form-control timepicker-default e_time1" name="e_time" value='' readonly="readonly">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>

                    <div class="form-group col-md-6" style="margin-top:-13px !important">
                        <label for="exampleInputEmail1"> <?php echo "Slot Type"; ?></label>
                        <div class="input-group">
                            <select class="form-control m-bot15" name="membership_code" value=''>

                                <option value="G" <?php

                                                    if ($schedule->membership_code == 'G') { ?> selected <?php } ?>> General </option>

                                <option value="VIP" <?php

                                                    if ($schedule->membership_code == 'VIP') { ?> selected <?php } ?>> VIP </option>

                            </select>
                        </div>

                    </div>


                    <input type="hidden" name="redirect" value='schedule'>
                    <input type="hidden" name="id" value='<?php echo $schedule->id; ?>'>
                    <button type="submit" name="submit" class="btn btn-info" style="margin-top:27px !important"> <?php echo lang('submit'); ?></button>
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

<script src="common/extranal/js/schedule/schedule.js"></script>



<script>
    $('.duration').change(function() {
        calculateTime();
    });


    // $('.timepicker-default').timepicker({defaultTime: 'value'}).on('change', function (event) {
    //                 alert('!!!');
    //             });



    function calculateTime() {
        var tt = $(".s_time").val();

        var minn = parseInt($(".duration").val()) * 5;


        var datetime = '2010-10-18, ' + tt;



        var dd = Date.parse(datetime);
        dd += minn * 60000;

        var addedtime = new Date(dd);



        var hours = addedtime.getHours();
        var minutes = addedtime.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        hours = hours < 10 ? '0' + hours : hours;


        var strTime = hours + ':' + minutes + ' ' + ampm;

        $('.e_time').val(strTime);
    }
</script>


<script>
    $('.duration1').change(function() {

        calculateTime1();
    });

    function calculateTime1() {

        var tt1 = $(".s_time1").val();

        var minn1 = parseInt($(".duration1").val()) * 5;


        var datetime1 = '2010-10-18, ' + tt1;



        var dd1 = Date.parse(datetime1);
        dd1 += minn1 * 60000;

        var addedtime1 = new Date(dd1);



        var hours1 = addedtime1.getHours();
        var minutes1 = addedtime1.getMinutes();
        var ampm1 = hours1 >= 12 ? 'PM' : 'AM';
        hours1 = hours1 % 12;
        hours1 = hours1 ? hours1 : 12; // the hour '0' should be '12'
        minutes1 = minutes1 < 10 ? '0' + minutes1 : minutes1;
        hours1 = hours1 < 10 ? '0' + hours1 : hours1;


        var strTime1 = hours1 + ':' + minutes1 + ' ' + ampm1;

        $('.e_time1').val(strTime1);
    }
</script>


<script type="text/javascript">
    function MyAlert() {
        var radio1 = $('input[type="radio"]:checked').val();
        // alert(radio1);
        var pass_data = {
            'radio1': radio1,
        };
        //alert(pass_data);
        $.ajax({
            url: "<?php echo base_url(); ?>schedule",
            type: "POST",
            data: pass_data,
            success: function(data) {
                $('#editable-sample').html(data);

            }
        });
        return false;
    }
</script>