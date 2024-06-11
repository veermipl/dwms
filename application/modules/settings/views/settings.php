<!--sidebar end-->
<!--main content start-->


<style>
    input.form-control.width {
        width: 50px;
    }
</style>
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading">
                <?php echo lang('settings'); ?>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table ">
                    <div class="clearfix row">
                        <?php echo validation_errors(); ?>
                        <form role="form" action="settings/update" method="post" enctype="multipart/form-data">
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('system_name'); ?></label>
                                <input type="text" class="form-control" name="name" value='<?php
                                                                                            if (!empty($settings->system_vendor)) {
                                                                                                echo $settings->system_vendor;
                                                                                            }
                                                                                            ?>' placeholder="system name">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('title'); ?></label>
                                <input type="text" class="form-control" name="title" value='<?php
                                                                                            if (!empty($settings->title)) {
                                                                                                echo $settings->title;
                                                                                            }
                                                                                            ?>' placeholder="title">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('address'); ?></label>
                                <input type="text" class="form-control" name="address" value='<?php
                                                                                                if (!empty($settings->address)) {
                                                                                                    echo $settings->address;
                                                                                                }
                                                                                                ?>' placeholder="address">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('phone'); ?></label>
                                <input type="text" class="form-control" name="phone" value='<?php
                                                                                            if (!empty($settings->phone)) {
                                                                                                echo $settings->phone;
                                                                                            }
                                                                                            ?>' placeholder="phone">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('hospital_email'); ?></label>
                                <input type="text" class="form-control" name="email" value='<?php
                                                                                            if (!empty($settings->email)) {
                                                                                                echo $settings->email;
                                                                                            }
                                                                                            ?>' placeholder="email">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('currency'); ?></label>
                                <input type="text" class="form-control" name="currency" value='<?php
                                                                                                if (!empty($settings->currency)) {
                                                                                                    echo $settings->currency;
                                                                                                }
                                                                                                ?>' placeholder="currency">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('timezone'); ?></label>
                                <select class="form-control m-bot15 js-example-basic-single" name="timezone" value=''>
                                    <?php
                                    foreach ($timezones as $key => $timezone) {
                                    ?>
                                        <option value="<?php echo $key ?>" <?php
                                                                            if ($key == $settings->timezone) {
                                                                                echo 'selected';
                                                                            }
                                                                            ?>><?php echo $timezone; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo lang('invoice_logo'); ?></label>
                                <input type="file" class="form-control" name="img_url" value='<?php
                                                                                                if (!empty($settings->invoice_logo)) {
                                                                                                    echo $settings->invoice_logo;
                                                                                                }
                                                                                                ?>' placeholder="">
                                <span class="help-block"><?php echo lang('recommended_size'); ?> : 200x100</span>
                            </div>
                            <div class="form-group hidden col-md-3">
                                <label for="exampleInputEmail1">Buyer</label>
                                <input type="hidden" class="form-control" name="buyer" value='<?php
                                                                                                if (!empty($settings->codec_username)) {
                                                                                                    echo $settings->buyer;
                                                                                                }
                                                                                                ?>' placeholder="codec_username">
                            </div>
                            <div class="form-group hidden col-md-3">
                                <label for="exampleInputEmail1">Purchase Code</label>
                                <input type="hidden" class="form-control" name="p_code" value='<?php
                                                                                                if (!empty($settings->codec_purchase_code)) {
                                                                                                    echo $settings->phone;
                                                                                                }
                                                                                                ?>' placeholder="codec_purchase_code">
                            </div>
                            <input type="hidden" name="id" value='<?php
                                                                    if (!empty($settings->id)) {
                                                                        echo $settings->id;
                                                                    }
                                                                    ?>'>

                            <div class="form-group col-md-12">

                                <header class="panel-heading pl-0 mt-0">
                                    Booking Setting

                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Minimum time for booking'; ?></label>
                                <input type="text" class="form-control" name="mindays_for_booking" value='<?php
                                                                                                            if (!empty($settings->mindays_for_booking)) {
                                                                                                                echo $settings->mindays_for_booking;
                                                                                                            }
                                                                                                            ?>' placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Maximum days for booking'; ?></label>
                                <input type="text" class="form-control" name="maxdays_for_booking" value='<?php
                                                                                                            if (!empty($settings->maxdays_for_booking)) {
                                                                                                                echo $settings->maxdays_for_booking;
                                                                                                            }
                                                                                                            ?>' placeholder="">
                            </div>

                            <!-- <div class="form-group col-md-12">
                             <span><b>Default Service Time:</span></b></div> -->

                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Default Service Time(Time-slot)'; ?></label>
                                <select class="form-control m-bot15 duration" name="default_service_time" value=''>
                                    <option value="">Select Default Service Time</option>
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
                            <!-- <div class="form-group col-md-12"> 
                            <span><b>Block Calender For Unpaid Appointment:</b></span>
                        </div> -->
                            <div class="form-group col-md-4">
                                <input type="checkbox" class="width" name="unpaid_appointment" value='1' <?php
                                                                                                            if ($settings->unpaid_appointment == '1') { ?> checked <?php } ?>>
                                <label for="exampleInputEmail1"><?php echo 'Block Calender For Unpaid Appointment'; ?></label>

                            </div>
                            <div class="form-group col-md-4">
                                <!-- <span><b>Allow Client to Canceled Appointment:</b></span> -->
                                <input type="checkbox" class="width" name="allow_client" value='1' <?php
                                                                                                    if ($settings->allow_client == '1') { ?> checked <?php } ?>>
                                <label for="exampleInputEmail1"><?php echo 'Allow Client to Canceled Appointment'; ?></label>

                            </div>

                            <!--      <div class="form-group col-md-4"> -->
                            <!-- <span><b>Send SMS instead of Push Notification :</b></span> -->
                            <div class="form-group col-md-4">
                                <input type="checkbox" class="width" name="send_sms_push" value='1' <?php
                                                                                                    if ($settings->send_sms_push == '1') { ?> checked <?php } ?>>
                                <label for="exampleInputEmail1"><?php echo ' Send SMS instead of Push Notification '; ?></label>

                            </div>
                            <!--    </div> -->

                            <div class="form-group col-md-12">

                                <header class="panel-heading pl-0 mt-0">
                                    Invoice Setting
                                </header>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'First Tax Name'; ?></label>
                                <input type="text" class="form-control" name="first_tax_name" value='<?php
                                                                                                        if (!empty($settings->first_tax_name)) {
                                                                                                            echo $settings->first_tax_name;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'First Tax Rate'; ?></label>
                                <input type="text" class="form-control" name="first_tax_gst_rate" value='<?php
                                                                                                            if (!empty($settings->first_tax_gst_rate)) {
                                                                                                                echo $settings->first_tax_gst_rate;
                                                                                                            }
                                                                                                            ?>' placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Second Tax Name'; ?></label>
                                <input type="text" class="form-control" name="second_tax_name" value='<?php
                                                                                                        if (!empty($settings->second_tax_name)) {
                                                                                                            echo $settings->second_tax_name;
                                                                                                        }
                                                                                                        ?>' placeholder="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="exampleInputEmail1"><?php echo 'Second Tax Rate'; ?></label>
                                <input type="text" class="form-control" name="second_tax_gst_rate" value='<?php
                                                                                                            if (!empty($settings->second_tax_gst_rate)) {
                                                                                                                echo $settings->second_tax_gst_rate;
                                                                                                            }
                                                                                                            ?>' placeholder="">
                            </div>
                            <div class="form-group col-md-12">
                                <span><b>Invoice Footer Note:</b></span>
                            </div>
                            <div class="form-group col-md-12">

                                <textarea class="form-control" rows="5" cols="50" name="invoice_footer_note"><?php
                                                                                                                if (!empty($settings->invoice_footer_note)) {
                                                                                                                    echo $settings->invoice_footer_note;
                                                                                                                }
                                                                                                                ?></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" name="submit" class="btn btn-info pull-right"><?php echo lang('submit'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
</section>



<script src="common/js/codearistos.min.js"></script>
<script src="common/extranal/js/settings/language.js"></script>
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('invoice_footer_note');
</script>