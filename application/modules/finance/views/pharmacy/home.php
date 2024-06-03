<!--sidebar end-->
<!--main content start-->
<script type="text/javascript" src="common/js/google-loader.js"></script>
<section id="main-content"> 
      <link href="common/extranal/css/pharmacy/home.css" rel="stylesheet">
    <section class="wrapper site-min-height">           
              <!--state overview start-->
        <div class="col-md-12">
            <header class="panel-heading">
                <i class="fa fa-home"></i>  <?php echo lang('pharmacy'); ?> <?php echo lang('dashboard'); ?>
            </header>
            <div class="row state-overview">
               
             <!--   <div class="col-lg-3 col-sm-6">
                    <?php if ($this->ion_auth->in_group('admin')) { ?>
                        <a href="finance/pharmacy/todaySales">
                        <?php } ?>
                        <section class="panel panel-moree">
                            <div class="symbol terques">
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="value">
                                <p> <?php echo lang('today_sales'); ?> </p>
                                <h1 class="">
                                    <?php echo $settings->currency; ?> <?php echo number_format($today_sales_amount, 2, '.', ','); ?>
                                </h1>
                            </div>
                        </section>
                        <?php if ($this->ion_auth->in_group('admin')) { ?>
                        </a>
                    <?php } ?>
                </div>
 -->
<!-- 
                <div class="col-lg-3 col-sm-6">
                    <?php if ($this->ion_auth->in_group('admin')) { ?>
                        <a href="finance/pharmacy/todayExpense">
                        <?php } ?>
                        <section class="panel panel-moree">
                            <div class="symbol blue">
                                <i class="fa fa-minus"></i>
                            </div>
                            <div class="value">
                                <p> <?php echo lang('today_expense'); ?> </p>
                                <h1 class="">
                                    <?php echo $settings->currency; ?> <?php echo number_format($today_expenses_amount, 2, '.', ','); ?>
                                </h1>

                            </div>
                        </section>
                        <?php if ($this->ion_auth->in_group('admin')) { ?>
                        </a>
                    <?php } ?>
                </div> -->

                <div class="col-lg-3 col-sm-6">
                    <?php if ($this->ion_auth->in_group('admin')) { ?>
                        <a href="medicine">
                        <?php } ?>
                        <section class="panel panel-moree">
                            <div class="symbol blue">
                                <i class="fa fa-medkit"></i>
                            </div>
                            <div class="value">
                                <p> <?php echo lang('medicine'); ?> </p>
                                <h1 class="">
                                    <?php echo $this->db->count_all('medicine'); ?>
                                </h1>

                            </div>
                        </section>
                        <?php if ($this->ion_auth->in_group('admin')) { ?>
                        </a>
                    <?php } ?>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <?php if ($this->ion_auth->in_group('admin')) { ?>
                        <a href="accountant">
                        <?php } ?>
                        <section class="panel panel-moree">
                            <div class="symbol blue">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="value">
                                <p> <?php echo 'Dispatches'; ?> </p>
                                <h1 class="">
                                   <?php
                                            $query_n_o_s = $this->db->get('pharmacy_payment')->result();
                                            $i = 0;
                                            foreach ($query_n_o_s as $q_n_o_s) {
                                                if (date('m/y', time()) == date('m/y', $q_n_o_s->date)) {
                                                    $i = $i + 1;
                                                }
                                            }
                                            echo $i;
                                            ?>
                                </h1>
                            </div>
                        </section>
                        <?php if ($this->ion_auth->in_group('admin')) { ?>
                        </a>
                    <?php } ?>
                </div>





                <?php if ($this->ion_auth->in_group(array('admin'))) { ?>

                    <div class="col-lg-6 col-sm-12">
                        <div id="chart_div" class="panel"></div>
                        <div class="panel">         
                            <div class="panel-heading"> <?php echo lang('latest_sales'); ?></div>
                            <table class="table table-striped table-hover table-bordered" id="">
                                <thead>
                                    <tr>
                                        <th> <?php echo lang('date'); ?> </th>
                                        <th> <?php echo lang('grand_total'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                               
                                <?php
                                $i = 0;
                                foreach ($payments as $payment) {
                                    $i = $i + 1;
                                    ?>
                                    <?php $patient_info = $this->db->get_where('patient', array('id' => $payment->patient))->row(); ?>
                                    <tr class="">
                                        <td><?php echo date('d/m/y', $payment->date); ?></td>
                                        <td><?php echo $settings->currency; ?> <?php echo number_format($payment->gross_total, 2, '.', ','); ?></td>
                                    </tr>
                                    <?php
                                    if ($i == 10)
                                        break;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="panel">         
                            <div class="panel-heading"> <?php echo lang('latest_expense'); ?></div>
                            <table class="table table-striped table-hover table-bordered" id="">
                                <thead>
                                    <tr>
                                        <th> <?php echo lang('category'); ?> </th>
                                        <th> <?php echo lang('date'); ?> </th>
                                        <th> <?php echo lang('amount'); ?> </th>
                                    </tr>
                                </thead>
                                <tbody>
                               
                                <?php
                                $i = 0;
                                foreach ($expenses as $expense) {
                                    $i = $i + 1;
                                    ?>
                                    <tr class="">
                                        <td><?php echo $expense->category; ?></td>
                                        <td> <?php echo date('d/m/y', $expense->date); ?></td>
                                        <td><?php echo $settings->currency; ?> <?php echo number_format($expense->amount, 2, '.', ','); ?></td>             
                                    </tr>
                                    <?php
                                    if ($i == 10)
                                        break;
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>


                    </div>

                <?php } ?>




                <div class="col-md-6">
                    


                    <div class="panel">  
                        <div class="panel-heading"> <?php echo lang('latest_medicines'); ?></div>
                        <table class="table table-striped table-hover table-bordered" id="">
                            <thead>
                                <tr>
                                    <th> <?php echo lang('name'); ?></th>
                                    <th> <?php echo lang('category'); ?></th>
                                    <th> <?php echo lang('price'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                           
                            <?php
                            $i = 0;
                            foreach ($latest_medicines as $latest_medicine) {
                                $i = $i + 1;
                                ?>
                                <tr class="">
                                    <td><?php echo $latest_medicine->name; ?></td>
                                    <td> <?php echo $latest_medicine->category; ?></td>
                                    <td><?php echo $settings->currency; ?> <?php echo number_format($latest_medicine->s_price, 2, '.', ','); ?></td>
                                </tr>
                                <?php
                                if ($i == 10)
                                    break;
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>

                </div>


            </div>


        </div>
        <!--state overview end-->
    </section>
</section>
<!--main content end-->

<script type="text/javascript">var per_month_income_expense = "<?php echo lang('per_month_income_expense') ?>";</script>
<script type="text/javascript">var currency = "<?php echo $settings->currency ?>";</script>
<script type="text/javascript">var months_lang = "<?php echo lang('months') ?>";</script>
<script type="text/javascript">var this_year = <?php echo json_encode($this_year['payment_per_month']); ?>;</script>
<script type="text/javascript">var this_year_expenses = <?php echo json_encode($this_year['expense_per_month']); ?>;</script>
<script src="common/extranal/js/pharmacy/home.js"></script>


