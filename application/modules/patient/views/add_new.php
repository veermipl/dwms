<!--sidebar end-->
<!--main content start-->
<style>
    #emailErr {
        /* font-weight: bold; */
        margin-top: 7px;
    }
</style>

<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <link href="common/extranal/css/patient/add_new.css" rel="stylesheet">
        <section class="panel">

            <header class="panel-heading">
                <?php
                if (!empty(@$patient->id))
                    echo lang('edit_patient');
                else
                    echo lang('add_new_patient');
                ?>
            </header>

            <section class="panel">
                <div class="panel-body">

                    <div class="col-lg-12">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-12">
                            <?php echo validation_errors(); ?>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>

                    <div class="col-lg-12">
                        <form role="form" action="patient/addNew" method="post" enctype="multipart/form-data">


                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label for="exampleInputEmail1"><?php echo lang('doctor'); ?></label>
                                    <select class="form-control m-bot15 js-example-basic-single" name="doctor" value=''>
                                        <?php foreach ($doctors as $doctor) { ?>
                                            <option value="<?php echo $doctor->id; ?>" <?php
                                                                                        if (!empty($patient->doctor)) {
                                                                                            if ($patient->doctor == $doctor->id) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        }
                                                                                        ?>><?php echo $doctor->name; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('name'); ?><span class="text-danger">*</span></label>
                                    <input type="text" required class="form-control" name="name" value='<?= set_value('name', @$patient->name) ?>' placeholder="">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('email'); ?><span class="text-danger">*</span></label>
                                    <input type="text" required id="patientEmail" class="form-control" name="email" value='<?= set_value('email', @$patient->email) ?>' patient_id="<?= @$patient->ion_user_id ?>" placeholder="">

                                    <span id="emailErr" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label><?php echo lang('birth_date'); ?></label>
                                    <input class="form-control form-control-inline input-medium default-date-picker" type="text" name="birthdate" value="<?= set_value('birthdate', @$patient->birthdate) ?>" placeholder="">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('age'); ?></label>
                                    <input type="text" class="form-control" name="age" value='<?= set_value('age', @$patient->age) ?>' placeholder="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('sex'); ?></label>
                                    <select class="form-control m-bot15" name="sex" value=''>
                                        <option value="Male" <?= set_value('sex', @$patient->sex) == 'Male' ? 'selected' : '' ?>> Male </option>
                                        <option value="Female" <?= set_value('sex', @$patient->sex) == 'Female' ? 'selected' : '' ?>> Female </option>
                                        <option value="Others" <?= set_value('sex', @$patient->sex) == 'Others' ? 'selected' : '' ?>> Others </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone" value='<?= set_value('phone', @$patient->phone) ?>' placeholder="" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>
                                    <select class="form-control m-bot15" name="bloodgroup" value=''>

                                        <?php foreach ($groups as $group) : ?>
                                            <option value="<?= $group->blood_type ?>" <?= set_value('bloodgroup', @$patient->bloodgroup) == $group->blood_type ? 'selected' : '' ?>>
                                                <?php echo $group->blood_type; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <?php if ($id && isset($id)) : ?>
                                        <label for="exampleInputEmail1"><?php echo lang('update') . ' ' . lang('image'); ?></label>
                                        <input type="file" class="form-control" name="img_url">
                                    <?php else : ?>
                                        <label for="exampleInputEmail1"><?php echo lang('image'); ?></label>
                                        <input type="file" class="form-control" name="img_url">
                                    <?php endif; ?>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <header class="panel-heading">
                            Photographic ID </header>

                        <div class="col-lg-12 mt-3">
                            <div class="form-group">

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">
                                            <label>
                                                <input type="radio" name="idType" value="passport" <?= (set_value('idType', $patient->idType) == 'passport') ? 'checked' : '' ?>> Passport number
                                            </label>
                                            <input type="text" class="form-control" name="idType_passport" value="<?= set_value('idType_passport', $patient->idType_passport) ?>">
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label>
                                                <input type="radio" name="idType" value="drivers" <?= (set_value('idType', $patient->idType) == 'drivers') ? 'checked' : '' ?>> Driver’s license number
                                            </label>
                                            <input type="text" class="form-control" name="idType_drivers" value="<?= set_value('idType_drivers', $patient->idType_drivers) ?>">
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label>
                                                <input type="radio" name="idType" value="other" <?= (set_value('idType', $patient->idType) == 'other') ? 'checked' : '' ?>> Other
                                            </label>
                                            <input type="text" class="form-control" name="idType_other" value="<?= set_value('idType_other', $patient->idType_other) ?>">
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?php echo lang('address'); ?><span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="address" value='<?= set_value('address', $patient->address) ?>' placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <div class="form-group">
                                                <?php if ($id && isset($id)) : ?>
                                                    <label for="exampleInputEmail1"><?php echo lang('update') . ' ' . lang('password'); ?></label>
                                                    <input type="password" class="form-control" name="password" placeholder="">
                                                <?php else : ?>
                                                    <label for="exampleInputEmail1"><?php echo lang('password'); ?></label>
                                                    <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>




                    </div>
                    <div class="row">
                        <header class="panel-heading">
                            Medical History </header>
                        <div class="col-lg-12 mt-3">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">
                                            <label for="chiefComplaint">Chief Complaint:</label>
                                            <textarea class="form-control" id="chiefComplaint" name="chiefComplaint"><?= set_value('chiefComplaint', $patient->chiefComplaint) ?></textarea>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label for="historyOfIllness">History of Presenting Illness:</label>
                                            <textarea class="form-control" id="historyOfIllness" name="historyOfIllness"><?= set_value('historyOfIllness', $patient->historyOfIllness) ?></textarea>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label for="pastMedicalHistory">Past Medical History and Drug History:</label>
                                            <textarea class="form-control" id="pastMedicalHistory" name="pastMedicalHistory"><?= set_value('pastMedicalHistory', $patient->pastMedicalHistory) ?></textarea>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label for="pastSurgicalHistory">Past Surgical History:</label>
                                            <textarea class="form-control" id="pastSurgicalHistory" name="pastSurgicalHistory"><?= set_value('pastSurgicalHistory', $patient->pastSurgicalHistory) ?></textarea>
                                        </div>
                                        <div class="col-lg-12 mt-3">
                                            <label>Allergies:</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="allergies" value="yes" <?= (set_value('allergies', $patient->allergies) == 'yes') ? 'checked' : '' ?>> Y
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="allergies" value="no" <?= (set_value('allergies', $patient->allergies) == 'no') ? 'checked' : '' ?>> N
                                                </label>
                                            </div>
                                            <textarea class="form-control mt-2" id="allergies_comment" name="allergies_comment" placeholder="Comment"><?= set_value('allergies_comment', $patient->allergies_comment) ?></textarea>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <header class="panel-heading">
                            Lifestyle History </header>
                        <div class="col-lg-12 mt-3">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-4 mt-3">
                                            <label>Smoking:</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="smoking" value="yes" <?= (set_value('smoking', $patient->smoking) == 'yes') ? 'checked' : '' ?>> Y
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="smoking" value="no" <?= (set_value('smoking', $patient->smoking) == 'no') ? 'checked' : '' ?>> N
                                                </label>
                                            </div>
                                            <textarea class="form-control mt-2" id="smoking_comment" name="smoking_comment" placeholder="Comment"><?= set_value('smoking_comment', $patient->smoking_comment) ?></textarea>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label>Alcohol use:</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="alcohol" value="yes" <?= (set_value('alcohol', $patient->alcohol) == 'yes') ? 'checked' : '' ?>> Y
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="alcohol" value="no" <?= (set_value('alcohol', $patient->alcohol) == 'no') ? 'checked' : '' ?>> N
                                                </label>
                                            </div>
                                            <textarea class="form-control mt-2" id="alcohol_comment" name="alcohol_comment" placeholder="Comment"><?= set_value('alcohol_comment', $patient->alcohol_comment) ?></textarea>
                                        </div>
                                        <div class="col-lg-4 mt-3">
                                            <label for="other">Other (Sexual activity, LMP, etc.):</label>
                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="other_activity" value="yes" <?= (set_value('other_activity', $patient->other_activity) == 'yes') ? 'checked' : '' ?>> Y
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="other_activity" value="no" <?= (set_value('other_activity', $patient->other_activity) == 'no') ? 'checked' : '' ?>> N
                                                </label>
                                            </div>
                                            <textarea class="form-control mt-2" id="other_activity_comment" name="other_activity_comment" placeholder="Comment"><?= set_value('other_activity_comment', $patient->other_activity_comment) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <?php if (empty($id)) { ?>

                        <!-- <div class="form-group sms_send">
                                    <div class="payment_label">
                                    </div>
                                    <div class="">
                                        <input type="checkbox" name="sms" value="sms"> <?php echo lang('send_sms') ?><br>
                                    </div>
                                </div> -->

                    <?php } ?>

                    <input type="hidden" name="id" value='<?php
                                                            if (!empty($patient->id)) {
                                                                echo $patient->id;
                                                            }
                                                            ?>'>
                    <input type="hidden" name="p_id" value='<?php
                                                            if (!empty($patient->patient_id)) {
                                                                echo $patient->patient_id;
                                                            }
                                                            ?>'>



                    </form>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 my-4">
                                <section class="">
                                    <button type="submit" name="submit" class="btn btn-info"><?php echo (empty($id)) ? lang('submit') : lang('update'); ?></button>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </section>

        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->

<script src="common/js/codearistos.min.js"></script>
<script type="text/javascript">
    var select_doctor = "<?php echo lang('select_doctor'); ?>";
</script>
<script type="text/javascript">
    var language = "<?php echo $this->language; ?>";
</script>
<script src="common/extranal/js/patient/patient.js"></script>