<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="col-md-8 row">
            <section class="col-md-10 row">
                <header class="panel-heading">
                    <?php
                    if (!empty($settings->name)) {
                        echo $settings->name;
                    }
                    ?> <?php echo lang('settings'); ?>
                </header>
                <div class="panel-body">
                    <div class="adv-table editable-table ">
                        <div class="clearfix">
                            <?php echo validation_errors(); ?>
                            <form role="form" action="pgateway/addNewSettings" class="clearfix" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"> <?php echo lang('payment_gateway'); ?> <?php echo lang('name'); ?></label>
                                    <input type="text" class="form-control" name="name"  value='<?php
                                    if (!empty($settings->name)) {
                                        echo $settings->name;
                                    }
                                    ?>' placeholder="" readonly>   
                                </div>
                                <?php if ($settings->name == "Pay U Money") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('merchant_key'); ?> </label>
                                        <input type="text" class="form-control" name="merchant_key"  value="<?php
                                        if (!empty($settings->merchant_key)) {
                                            echo $settings->merchant_key;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('salt'); ?> </label>
                                        <input type="text" class="form-control" name="salt"  value='<?php
                                        if (!empty($settings->salt)) {
                                            echo $settings->salt;
                                        }
                                        ?>'>
                                    </div
                                <?php } ?>
                                <?php if ($settings->name == "Authorize.Net") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('apiloginid'); ?> </label>
                                        <input type="text" class="form-control" name="apiloginid"  value="<?php
                                        if (!empty($settings->apiloginid)) {
                                            echo $settings->apiloginid;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('transactionkey'); ?> </label>
                                        <input type="text" class="form-control" name="transactionkey"  value="<?php
                                        if (!empty($settings->transactionkey)) {
                                            echo $settings->transactionkey;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    
                                <?php } ?>
                                <?php if ($settings->name == "Paytm") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('merchant_key'); ?> </label>
                                        <input type="text" class="form-control" name="merchant_key"  value="<?php
                                        if (!empty($settings->merchant_key)) {
                                            echo $settings->merchant_key;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('merchant_mid'); ?> </label>
                                        <input type="text" class="form-control" name="merchant_mid"  value="<?php
                                        if (!empty($settings->merchant_mid)) {
                                            echo $settings->merchant_mid;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('merchant_website'); ?> </label>
                                        <input type="text" class="form-control" name="merchant_website"  value="<?php
                                        if (!empty($settings->merchant_website)) {
                                            echo $settings->merchant_website;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                <?php } ?>
                                <?php if ($settings->name == "Paystack") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('secretkey'); ?> </label>
                                        <input type="text" class="form-control" name="secret"  value="<?php
                                        if (!empty($settings->secret)) {
                                            echo $settings->secret;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('public_key'); ?> </label>
                                        <input type="text" class="form-control" name="public_key"  value='<?php
                                        if (!empty($settings->public_key)) {
                                            echo $settings->public_key;
                                        }
                                        ?>'>
                                    </div
                                <?php } ?>
                                <?php if ($settings->name == "PayPal") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('api_username'); ?> </label>
                                        <input type="text" class="form-control" name="APIUsername"  value="<?php
                                        if (!empty($settings->APIUsername)) {
                                            echo $settings->APIUsername;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('api_password'); ?> </label>
                                        <input type="text" class="form-control" name="APIPassword"  value='<?php
                                        if (!empty($settings->APIPassword)) {
                                            echo $settings->APIPassword;
                                        }
                                        ?>'>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('api_signature'); ?> </label>
                                        <input type="text" class="form-control" name="APISignature"  value='<?php
                                        if (!empty($settings->APISignature)) {
                                            echo $settings->APISignature;
                                        }
                                        ?>'>
                                    </div>
                                <?php } ?>
                                <?php if ($settings->name == "2Checkout") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('merchantcode'); ?> </label>
                                        <input type="text" class="form-control" name="merchantcode"  value='<?php
                                        if (!empty($settings->merchantcode)) {
                                            echo $settings->merchantcode;
                                        }
                                        ?>'>
                                    </div
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('privatekey'); ?> </label>
                                        <input type="text" class="form-control" name="privatekey"  value="<?php
                                        if (!empty($settings->privatekey)) {
                                            echo $settings->privatekey;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('publishablekey'); ?> </label>
                                        <input type="text" class="form-control" name="publishablekey"  value="<?php
                                        if (!empty($settings->publishablekey)) {
                                            echo $settings->publishablekey;
                                        }
                                        ?>" placeholder="">
                                    </div>

                                <?php } ?>
                                <?php if ($settings->name == "SSLCOMMERZ") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('store_id'); ?> </label>
                                        <input type="text" class="form-control" name="store_id"  value="<?php
                                        if (!empty($settings->store_id)) {
                                            echo $settings->store_id;
                                        }
                                        ?>" placeholder="">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo lang('store_password'); ?> </label>
                                        <input type="text" class="form-control" name="store_password"  value='<?php
                                        if (!empty($settings->store_password)) {
                                            echo $settings->store_password;
                                        }
                                        ?>'>
                                    </div>

                                <?php } ?>
                                <?php if ($settings->name == "Stripe") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('secretkey'); ?></label>
                                        <input type="text" class="form-control" name="secret"  value='<?php
                                        if (!empty($settings->secret)) {
                                            echo $settings->secret;
                                        }
                                        ?>' placeholder="" <?php
                                               if (!$this->ion_auth->in_group('admin')) {
                                                   echo 'disabled';
                                               }
                                               ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('publishkey'); ?></label>
                                        <input type="text" class="form-control" name="publish"  value='<?php
                                        if (!empty($settings->publish)) {
                                            echo $settings->publish;
                                        }
                                        ?>'>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo lang('status'); ?></label>
                                    <select class="form-control m-bot15" name="status" value=''>
                                        <option value="live" <?php
                                        if (!empty($settings->status)) {
                                            if ($settings->status == 'live') {
                                                echo 'selected';
                                            }
                                        }
                                        ?>><?php echo lang('live'); ?> </option>
                                        <option value="test" <?php
                                        if (!empty($settings->status)) {
                                            if ($settings->status == 'test') {
                                                echo 'selected';
                                            }
                                        }
                                        ?>><?php echo lang('test'); ?></option>
                                    </select>
                                </div>
                                    <?php if ($settings->name == "2Checkout") { ?>
                                    
                                    <ul>
                                        <li>
                                            <code>   Available only Live mood .</code>
                                        </li>
                                    </ul>
                                    <?php } ?>
                                    <?php if ($settings->name == "SSLCOMMERZ") { ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"> <?php echo lang('ipnsettings'); ?></label>
                                        <input type="text" class="form-control" name=""  value=' <?php echo base_url(); ?>sslcommerzpayment/success' readonly="">                                 

                                    </div>
                                    <code>
                                        <?php echo "Copy  Ipn_settings to your merchant sslcommerz account. Follow steps below:" ?>
                                        <br><br>
                                        <ul>
                                            <li>>>Login at https://merchant.sslcommerz.com/ (LIVE) and <br>     https://sandbox.sslcommerz.com/manage/(TEST)</li>
                                            <li>>>Click on Menu My Stores > IPN Settings</li>
                                            <li>>>Tick mark Enable HTTP Listener, input above URL in the Box and save settings.</li>
                                        </ul>



                                    </code>
                                <?php } ?>
                                <input type="hidden" name="id" value='<?php
                                if (!empty($settings->id)) {
                                    echo $settings->id;
                                }
                                ?>'>
                                <div class="form-group clearfix">
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


<script src="common/js/codearistos.min.js"></script>
<script src="common/extranal/js/pgateway.js"></script>