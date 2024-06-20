<section id="main-content">
    <section class="wrapper site-min-height">
        <link href="common/extranal/css/patient/add_new.css" rel="stylesheet">

        <section class="panel">

            <header class="panel-heading">
                <?php
                if (empty(@$locationData->id))
                    echo lang('add') . ' Location';
                else
                    echo lang('edit') . ' Location';
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

                    <form role="form" action="schedule/storeLocation" class="clearfix" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="loc_id" value="<?= @$locationData->id ?>">

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" class="form-control" name="loc_name" value="<?= set_value('loc_name', @$locationData->name) ?>">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Code</label>
                            <input type="text" class="form-control" name="loc_code" value="<?= set_value('loc_code', @$locationData->loc_code) ?>">
                        </div>

                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">Address</label>
                            <textarea name="loc_address" class="form-control"><?= set_value('loc_address', @$locationData->location_address) ?></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" name="submit" class="btn btn-info pull-right">
                                <?= (@$locationData->id) ? lang('update') : lang('submit') ?>
                            </button>
                        </div>

                    </form>

                </div>
            </section>

        </section>

    </section>
</section>


<script>

</script>