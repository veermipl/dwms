<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/patient/add_new.css" rel="stylesheet">
        <section class="panel">

            <header class="panel-heading">
                <?php
                if (empty(@$schedule->id))
                    echo lang('add') . ' ' . lang('schedule');
                else
                    echo lang('edit') . ' ' . lang('schedule');
                ?>
            </header>

            <section class="panel">
                <div class="panel-body">

                    <div class="col-lg-12">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-12 text-danger">
                            <?php echo validation_errors(); ?>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>

                    <form role="form" action="schedule/addSchedule" class="clearfix" method="post" enctype="multipart/form-data">

                        <input type="hidden" class="form-control" name="id" value="<?= @$schedule->id ?>" />

                        <div class="col-md-6 panel">
                            <label for="exampleInputEmail1"> <?php echo lang('doctor'); ?></label>
                            <select class="form-control m-bot15" id="doctorchoose" name="doctor" value=''>
                                <option value="">Select Doctor</option>
                                <?php foreach ($doctors as $doctorKey => $doctorVal) : ?>

                                    <option value="<?= $doctorVal->id; ?>" <?= ($doctorVal->id == set_value('doctor', @$schedule->doctor)) ? 'selected' : '' ?>>
                                        <?= $doctorVal->name . ' - ' . $doctorVal->id; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"> <?php echo lang('weekday'); ?></label>
                            <select class="form-control m-bot15" id="weekday" name="weekday" value=''>
                                <option value="">Select .....</option>
                                <?php foreach ($weekday as $week) { ?>
                                    <option value="<?php echo $week->name; ?>" <?= ($week->name == set_value('weekday', @$schedule->weekday)) ? 'selected' : '' ?>>
                                        <?php echo $week->name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"> <?php echo 'Location'; ?></label>
                            <select class="form-control m-bot15" id="location_id" name="location_id" value=''>
                                <option value="">Select .....</option>
                                <?php foreach ($location as $loc) { ?>
                                    <option value="<?php echo $loc->id; ?>" <?= ($loc->id == set_value('location_id', @$schedule->location_id)) ? 'selected' : '' ?>>
                                        <?php echo $loc->name; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"> <?php echo lang('start_time'); ?></label>
                            <div class="input-group bootstrap-timepicker">
                                <input type="text" class="form-control timepicker-default s_time" name="s_time" value='<?= set_value('s_time', @$schedule->s_time) ?>'>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                                </span>
                            </div>

                        </div>

                        <div class="form-group col-md-6" style="margin-left:1px !important;">
                            <label for="exampleInputEmail1"><?php echo lang('appointment') ?> <?php echo lang('duration') ?> </label>
                            <select class="form-control m-bot15 duration" name="duration" value=''>

                                <option value="3" <?= (set_value('duration', @$schedule->duration) == '3') ? 'selected' : '' ?>> 15 Minutes </option>

                                <option value="4" <?= (set_value('duration', @$schedule->duration) == '4') ? 'selected' : '' ?>> 20 Minutes </option>

                                <option value="6" <?= (set_value('duration', @$schedule->duration) == '6') ? 'selected' : '' ?>> 30 Minutes </option>

                                <option value="9" <?= (set_value('duration', @$schedule->duration) == '9') ? 'selected' : '' ?>> 45 Minutes </option>

                                <option value="12" <?= (set_value('duration', @$schedule->duration) == '12') ? 'selected' : '' ?>> 60 Minutes </option>

                            </select>
                        </div>

                        <div class="form-group col-md-6" style="margin-left:-1px !important;">
                            <label for="exampleInputEmail1"> <?php echo lang('end_time'); ?></label> <?= @$schedule->e_time ?>
                            <div class="input-group bootstrap-timepicker">
                                <input type="text" class="form-control timepicker-default e_time" name="e_time" value='<?= set_value('e_time', @$schedule->e_time) ?>' readonly="readonly">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-clock"></i></button>
                                </span>
                            </div>

                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1"> <?php echo "Slot Type"; ?></label>
                            <div class="input-group">
                                <select class="form-control m-bot15" name="membership_code" value=''>

                                    <option value="G" <?= (set_value('membership_code', @$schedule->membership_code) == "G") ? 'selected' : '' ?>> General </option>

                                    <option value="VIP" <?= (set_value('membership_code', @$schedule->membership_code) == "VIP") ? 'selected' : '' ?>> VIP </option>

                                </select>
                            </div>

                        </div>
                        
                        <!-- <input type="hidden" name="redirect" value='schedule/addScheduleView'> -->
                        <input type="hidden" name="redirect" value='<?= (@$schedule) ? "schedule/addScheduleView/".$schedule->id."" : "schedule/addScheduleView" ?>'>

                        <div class="form-group col-md-12">
                            <button type="submit" name="submit" class="btn btn-info pull-right"> <?php echo lang('submit'); ?></button>
                        </div>
                    </form>

                </div>
            </section>

        </section>
    </section>
</section>


<script>
    $('.duration').change(function() {
        calculateTime();
    });

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
