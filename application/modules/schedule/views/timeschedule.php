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

                <?php echo lang('time_schedule'); ?> (<?php echo $this->db->get_where('doctor', array('id' => $doctorr))->row()->name; ?>)

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
                                <input type="hidden" name="doctor" value="<?php echo $doctorr; ?>">

                                <label class="mr-3" for="<?php echo $week->name; ?>"><?php echo $week->name; ?></label><br>
                            </form>
                        <?php } ?>

                    </div>
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">

                        <thead>

                            <tr>

                                <th> # </th>

                                <th> <?php echo lang('weekday'); ?></th>

                                <th> <?php echo lang('start_time'); ?></th>

                                <th> <?php echo lang('end_time'); ?></th>
                                <th> <?php echo lang('location'); ?></th>
                                <th> <?php echo lang('slot_type'); ?></th>


                                <th> <?php echo lang('options'); ?></th>



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

                                    <td> <?php echo $schedule->weekday; ?></td>

                                    <td><?php echo $schedule->s_time; ?></td>

                                    <td><?php echo $schedule->e_time; ?></td>
                                    <td><?php echo $this->schedule_model->getLocationById($schedule->location_id)->name ?? ''; ?></td>
                                    <td><?php echo ucwords($schedule->membership_code); ?></td>


                                    <td>



                                        <a class="btn btn-info btn-xs btn_width delete_button" href="schedule/deleteSchedule?id=<?php echo $schedule->id; ?>&doctor=<?php echo $doctorr; ?>&weekday=<?php echo $schedule->weekday; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash"> </i> <?php echo lang('delete'); ?></a>

                                    </td>

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

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title"> <?php echo lang('add'); ?> <?php echo lang('time_slots'); ?></h4>

            </div>

            <div class="modal-body">

                <form role="form" action="schedule/addSchedule" class="clearfix" method="post" enctype="multipart/form-data">

                    <div class="form-group bootstrap-timepicker col-md-6">

                        <label for="exampleInputEmail1"> <?php echo lang('weekday'); ?></label>

                        <div class="input-group bootstrap-timepicker w-100">

                            <select class="form-control m-bot15" id="weekday" name="weekday" value=''>
                                <option value="">Select .....</option>
                                <?php foreach ($weekday as $week) { ?>

                                    <option value="<?php echo $week->name; ?>" <?php if ($_SESSION['weekday'] == $week->name) { ?> selected <?php } ?>><?php echo $week->name; ?></option>

                                <?php } ?>
                            </select>

                        </div>

                    </div>

                    <div class="form-group bootstrap-timepicker col-md-6">

                        <label for="exampleInputEmail1"> <?php echo lang('location'); ?></label>

                        <div class="input-group bootstrap-timepicker w-100">

                            <select class="form-control m-bot15" id="location_id" name="location_id" value=''>
                                <option value="">Select .....</option>
                                <?php foreach ($location as $loc) { ?>
                                    <option value="<?php echo $loc->id; ?>"><?php echo $loc->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('start_time'); ?></label>
                        <div class="input-group bootstrap-timepicker">
                            <input type="text" class="form-control timepicker-default s_time" name="s_time" value=''>
                            <span class="input-group-btn">
                                <button class="btn btn-default custome-btn-dwms" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>

                    <div class="form-group col-md-6">
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

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo "Slot Type"; ?></label>
                        <div class="input-group w-100">
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

                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1"> <?php echo lang('end_time'); ?></label>
                        <div class="input-group bootstrap-timepicker w-100">
                            <input type="text" class="form-control timepicker-default e_time" name="e_time" value='' readonly="readonly">
                            <span class="input-group-btn">
                                <button class="btn btn-default custome-btn-dwms" type="button"><i class="fa fa-clock"></i></button>
                            </span>
                        </div>

                    </div>



                    <input type="hidden" name="doctor" value='<?php echo $doctorr; ?>'>

                    <input type="hidden" name="redirect" value='schedule/timeSchedule?doctor=<?php echo $doctorr; ?>'>

                    <input type="hidden" name="id" value=''>

                    <div class="form-group">

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

                    <div class="form-group">

                        <label for="exampleInputEmail1"> <?php echo lang('start_time'); ?></label>

                        <div class="input-group bootstrap-timepicker">

                            <input type="text" class="form-control timepicker-default" name="s_time" value=''>

                            <span class="input-group-btn">

                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>

                            </span>

                        </div>



                    </div>

                    <div class="form-group bootstrap-timepicker">

                        <label for="exampleInputEmail1"> <?php echo lang('end_time'); ?></label>

                        <div class="input-group bootstrap-timepicker">

                            <input type="text" class="form-control timepicker-default" name="e_time" value=''>

                            <span class="input-group-btn">

                                <button class="btn btn-default" type="button"><i class="fa fa-clock-o"></i></button>

                            </span>

                        </div>

                    </div>

                    <div class="form-group bootstrap-timepicker">

                        <label for="exampleInputEmail1"> <?php echo lang('weekday'); ?></label>

                        <div class="input-group bootstrap-timepicker">

                            <select class="form-control m-bot15" id="weekday" name="weekday" value=''>

                                <option value="Friday"><?php echo lang('friday') ?></option>

                                <option value="Saturday"><?php echo lang('saturday') ?></option>

                                <option value="Sunday"><?php echo lang('sunday') ?></option>

                                <option value="Monday"><?php echo lang('monday') ?></option>

                                <option value="Tuesday"><?php echo lang('tuesday') ?></option>

                                <option value="Wednesday"><?php echo lang('wednesday') ?></option>

                                <option value="Thursday"><?php echo lang('thursday') ?></option>

                            </select>



                        </div>

                    </div>



                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo lang('appointment') ?> <?php echo lang('duration') ?> </label>

                        <select class="form-control m-bot15" name="duration" value=''>



                            <option value="3" <?php

                                                if (!empty($settings->duration)) {

                                                    if ($settings->duration == '3') {

                                                        echo 'selected';
                                                    }
                                                }

                                                ?>> 15 Minitues </option>



                            <option value="4" <?php

                                                if (!empty($settings->duration)) {

                                                    if ($settings->duration == '4') {

                                                        echo 'selected';
                                                    }
                                                }

                                                ?>> 20 Minitues </option>



                            <option value="6" <?php

                                                if (!empty($settings->duration)) {

                                                    if ($settings->duration == '6') {

                                                        echo 'selected';
                                                    }
                                                }

                                                ?>> 30 Minitues </option>



                            <option value="9" <?php

                                                if (!empty($settings->duration)) {

                                                    if ($settings->duration == '9') {

                                                        echo 'selected';
                                                    }
                                                }

                                                ?>> 45 Minitues </option>



                            <option value="12" <?php

                                                if (!empty($settings->duration)) {

                                                    if ($settings->duration == '12') {

                                                        echo 'selected';
                                                    }
                                                }

                                                ?>> 60 Minitues </option>



                        </select>

                    </div>



                    <input type="hidden" name="doctor" value="<?php echo $doctorr; ?>">

                    <input type="hidden" name="redirect" value='schedule/timeSchedule'>

                    <input type="hidden" name="id" value=''>

                    <button type="submit" name="submit" class="btn btn-info"> <?php echo lang('submit'); ?></button>

                </form>



            </div>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog -->

</div>









<script src="common/js/codearistos.min.js"></script>

<script src="common/extranal/js/schedule/timeschedule.js"></script>


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