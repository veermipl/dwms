<!--sidebar end-->
<!--main content start-->
<style>
    a.active{
        border-bottom: 2px solid green !important;
    }
</style>


<section id="main-content">
    <section class="wrapper site-min-height">
        <link href="common/extranal/css/patient/patient.css" rel="stylesheet">
        <section class="">

            <header class="panel-heading">
                <?php echo lang('patient'); ?> <?php echo lang('medical_info'); ?>
                <div class="col-md-4 no-print pull-right">
                    <a data-toggle="modal" href="#myModal">
                        <div class="btn-group pull-right">
                        </div>
                    </a>
                </div>
            </header>

            <div class="panel-body">
                <div class="mt-3">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item <?= ($patient_form_tab == 'general') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'general') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?id=<?php echo $patient_id ?>&tab=general" role="tab">General</a>
                        </li>
                        <li class="nav-item <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?id=<?php echo $patient_id ?>&tab=vital" role="tab">Vital</a>
                        </li>
                        <li class="nav-item <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?id=<?php echo $patient_id ?>&tab=physical" role="tab">Physical</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    <div class="tab-pane <?= ($patient_form_tab == 'general') ? 'active' : '' ?>" id="overview" role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5>general info form</h5>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>" id="tasks" role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5>vital info form</h5>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>" id="tasks" role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5>physical info form</h5>
                            </div>
                        </div>
                    </div>

                </div>

        </section>
    </section>
</section>