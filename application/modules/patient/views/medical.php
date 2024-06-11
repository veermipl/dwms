<!--sidebar end-->
<!--main content start-->
<style>
a.active {
    border-bottom: 2px solid green !important;
}
</style>

<?php if (!$this->ion_auth->in_group(array('Doctor'))) : ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function() {

    // Disable all input type text
    var textInputs = document.querySelectorAll('input[type="text"]');
    for (var i = 0; i < textInputs.length; i++) {
        textInputs[i].disabled = true;
    }

    // Disable all textareas
    var textareas = document.querySelectorAll('textarea');
    for (var i = 0; i < textareas.length; i++) {
        textareas[i].disabled = true;
    }

    // Disable all checkboxes
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].disabled = true;
    }

    // Disable all radio buttons
    var radioButtons = document.querySelectorAll('input[type="radio"]');
    for (var i = 0; i < radioButtons.length; i++) {
        radioButtons[i].disabled = true;
    }

})
</script>
<?php endif; ?>

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


            <?php $apid = $_GET['apid'] ?? 0; ?>
            <?php $patent_id = $_GET['id'] ?? 0; ?>


            <?php
            $patientData = $this->patient_model->getPatientById($patent_id);
            $appointmentData = $this->appointment_model->getAppointmentById($apid);
            $data = $this->appointment_model->getAppointmentByIdOrDoctorId($apid, $patent_id);

            $patientList = $this->patient_model->getPatientById($appointmentData->patient);
            $doctorList = $this->doctor_model->getDoctorById($appointmentData->doctor);

            $consultationModeList = $this->doctor_model->getConsultation_Mode();
            $consultationTypeList = $this->doctor_model->getType();
            ?>
            <?php if ($data) : ?>
            <div class="panel-body">
                <div class="mt-3">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item <?= ($patient_form_tab == 'appointment') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'appointment') ? 'active' : '' ?>"
                                data-bs-toggle="tab"
                                href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=appointment"
                                role="tab">Appointment</a>
                        </li>
                        <li class="nav-item <?= ($patient_form_tab == 'general') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'general') ? 'active' : '' ?>"
                                data-bs-toggle="tab"
                                href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=general"
                                role="tab">General </a>
                        </li>
                        <li class="nav-item <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>"
                                data-bs-toggle="tab"
                                href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=vital"
                                role="tab">Vital </a>
                        </li>
                        <li class="nav-item <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>">
                            <a class="nav-link <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>"
                                data-bs-toggle="tab"
                                href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=physical"
                                role="tab">Physical</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    <div class="tab-pane <?= ($patient_form_tab == 'appointment') ? 'active' : '' ?>" id="overview"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <form role="form" action="" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="temp">Patient</label>
                                            <input readonly disabled type="text" class="form-control" id="temp"
                                                name="temp" value="<?= $patientList->name ?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="temp">Doctor</label>
                                            <input readonly disabled type="text" class="form-control" id="temp"
                                                name="temp" value="<?= $doctorList->name ?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="temp">Time Slot</label>
                                            <input readonly disabled type="text" class="form-control" id="temp"
                                                name="temp" value="<?= $appointmentData->time_slot ?>" />

                                        </div>
                                        <div class="form-group col-md-6">

                                            <div class="form-group">
                                                <label for="temp">Amount</label>
                                                <input readonly disabled type="text" class="form-control" id="amount"
                                                    name="amount" value="<?= $appointmentData->amount ?>" />
                                            </div>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="temp">Location</label>
                                            <select class="form-control m-bot15" name="status" value='' readonly
                                                disabled>
                                                <option value="">---</option>
                                                <?php foreach ($location as $locKey => $locVal) : ?>
                                                <option value="<?= $locVal->id ?>"
                                                    <?= $appointmentData->location_id == $locVal->id  ? 'selected' : '' ?>>
                                                    <?= $locVal->name ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="temp">Date</label>
                                            <input readonly disabled type="text" class="form-control" id="temp"
                                                name="temp" value="<?= date('d-m-Y', ($appointmentData->date)) ?>" />
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="temp">Mode Of Consultation</label>
                                            <select class="form-control m-bot15" name="status" value='' readonly
                                                disabled>
                                                <option value="">---</option>
                                                <?php foreach ($consultationModeList as $cmListKey => $cmListVal) : ?>
                                                <option value="<?= $cmListVal->id ?>"
                                                    <?= $appointmentData->mode_of_consultation == $cmListVal->id  ? 'selected' : '' ?>>
                                                    <?= $cmListVal->mode_of_consultation ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="temp">Type Of Consultation</label>
                                            <select class="form-control m-bot15" name="status" value='' readonly
                                                disabled>
                                                <option value="">---</option>
                                                <?php foreach ($consultationTypeList as $ctListKey => $ctListVal) : ?>
                                                <option value="<?= $ctListVal->id ?>"
                                                    <?= $appointmentData->type_of_consultation == $ctListVal->id  ? 'selected' : '' ?>>
                                                    <?= $ctListVal->name ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="temp">Remarks</label>
                                            <input readonly disabled type="text" class="form-control" id="temp"
                                                name="temp" value="<?= $appointmentData->remarks ?>" />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="temp">Status</label>
                                            <select class="form-control m-bot15" name="status" value='' readonly
                                                disabled>
                                                <?php foreach ($status as $statusVal) : ?>
                                                <option value="<?= $statusVal->id ?>"
                                                    <?= $appointmentData->status == $statusVal->id  ? 'selected' : '' ?>>
                                                    <?= $statusVal->status_name ?>
                                                </option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($patient_form_tab == 'general') ? 'active' : '' ?>" id="overview"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <form role="form" action="" method="post" enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('name'); ?><span
                                                    class="text-danger">*</span></label>
                                            <input type="text" readonly disabled class="form-control" name="name"
                                                value='<?= set_value('name', @$patientData->name) ?>' placeholder="">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('email'); ?><span
                                                    class="text-danger">*</span></label>
                                            <input type="text" readonly disabled class="form-control" name="email"
                                                value='<?= set_value('email', @$patientData->email) ?>' placeholder="">
                                        </div>


                                        <div class="form-group col-md-3">
                                            <label><?php echo lang('birth_date'); ?></label>
                                            <input readonly disabled
                                                class="form-control form-control-inline input-medium default-date-picker"
                                                type="text" name="birthdate"
                                                value="<?= set_value('birthdate', @$patientData->birthdate) ?>"
                                                placeholder="">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('age'); ?></label>
                                            <input readonly disabled type="text" class="form-control" name="age"
                                                value='<?= set_value('age', @$patientData->age) ?>' placeholder=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('sex'); ?></label>
                                            <select class="form-control m-bot15" name="sex" value='' readonly disabled>
                                                <option value="Male"
                                                    <?= set_value('sex', @$patientData->sex) == 'Male' ? 'selected' : '' ?>>
                                                    Male </option>
                                                <option value="Female"
                                                    <?= set_value('sex', @$patientData->sex) == 'Female' ? 'selected' : '' ?>>
                                                    Female </option>
                                                <option value="Others"
                                                    <?= set_value('sex', @$patientData->sex) == 'Others' ? 'selected' : '' ?>>
                                                    Others </option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('phone'); ?><span
                                                    class="text-danger">*</span></label>
                                            <input readonly disabled type="text" class="form-control" name="phone"
                                                value='<?= set_value('phone', @$patientData->phone) ?>' placeholder=""
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="exampleInputEmail1"><?php echo lang('blood_group'); ?></label>
                                            <select class="form-control m-bot15" name="bloodgroup" value='' readonly
                                                disabled>

                                                <?php foreach ($groups as $group) : ?>
                                                <option value="<?= $group->blood_type ?>"
                                                    <?= set_value('bloodgroup', @$patientData->bloodgroup) == $group->blood_type ? 'selected' : '' ?>>
                                                    <?php echo $group->blood_type; ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Photographic ID:</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="radio-inline pt-3 pb-2 col-md-6">
                                                            <input readonly disabled type="radio" name="idType"
                                                                value="passport"
                                                                <?= (set_value('idType', @$patientData->idType) == 'passport') ? 'checked' : '' ?>>
                                                            Passport number
                                                        </label>
                                                        <input readonly disabled type="text" class="form-control"
                                                            name="idType_passport"
                                                            value="<?= set_value('idType_passport', @$patientData->idType_passport) ?>">

                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="radio-inline  pt-3 pb-2 ">
                                                            <input readonly disabled type="radio" name="idType"
                                                                value="drivers"
                                                                <?= (set_value('idType', @$patientData->idType) == 'drivers') ? 'checked' : '' ?>>
                                                            Driver’s license number
                                                        </label>
                                                        <input readonly disabled type="text" class="form-control"
                                                            name="idType_drivers"
                                                            value="<?= set_value('idType_drivers', @$patientData->idType_drivers) ?>">

                                                    </div>
                                                    <div class="col-md-6">


                                                        <label class="radio-inline pt-3  pb-2 ">
                                                            <input readonly disabled type="radio" name="idType"
                                                                value="other"
                                                                <?= (set_value('idType', @$patientData->idType) == 'other') ? 'checked' : '' ?>>
                                                            Other
                                                        </label>
                                                        <input readonly disabled type="text" class="form-control"
                                                            name="idType_other"
                                                            value="<?= set_value('idType_other', @$patientData->idType_other) ?>">

                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="pt-3  pb-2"
                                                                for="exampleInputEmail1"><?php echo lang('address'); ?><span
                                                                    class="text-danger">*</span></label>
                                                            <input readonly disabled type="text" class="form-control"
                                                                name="address"
                                                                value='<?= set_value('address', @$patientData->address) ?>'
                                                                placeholder="">
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">

                                            <h3>Medical History</h3>
                                            <div class="form-group">
                                                <label for="chiefComplaint">Chief Complaint:</label>
                                                <textarea readonly disabled class="form-control" id="chiefComplaint"
                                                    name="chiefComplaint"><?= set_value('chiefComplaint', @$patientData->chiefComplaint) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="historyOfIllness">History of Presenting Illness:</label>
                                                <textarea readonly disabled class="form-control" id="historyOfIllness"
                                                    name="historyOfIllness"><?= set_value('historyOfIllness', @$patientData->historyOfIllness) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="pastMedicalHistory">Past Medical History and Drug
                                                    History:</label>
                                                <textarea readonly disabled class="form-control" id="pastMedicalHistory"
                                                    name="pastMedicalHistory"><?= set_value('pastMedicalHistory', @$patientData->pastMedicalHistory) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="pastSurgicalHistory">Past Surgical History:</label>
                                                <textarea readonly disabled class="form-control"
                                                    id="pastSurgicalHistory"
                                                    name="pastSurgicalHistory"><?= set_value('pastSurgicalHistory', @$patientData->pastSurgicalHistory) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Allergies:</label>
                                                <div>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="allergies"
                                                            value="yes"
                                                            <?= (set_value('allergies', @$patientData->allergies) == 'yes') ? 'checked' : '' ?>>
                                                        Y
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="allergies"
                                                            value="no"
                                                            <?= (set_value('allergies', @$patientData->allergies) == 'no') ? 'checked' : '' ?>>
                                                        N
                                                    </label>
                                                </div>
                                                <textarea readonly disabled class="form-control mt-2"
                                                    id="allergies_comment" name="allergies_comment"
                                                    placeholder="Comment"><?= set_value('allergies_comment', @$patientData->allergies_comment) ?></textarea>
                                            </div>


                                        </div>
                                        <div class="col-md-6">
                                            <h3>Lifestyle History</h3>
                                            <div class="form-group">
                                                <label>Smoking:</label>
                                                <div>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="smoking" value="yes"
                                                            <?= (set_value('smoking', @$patientData->smoking) == 'yes') ? 'checked' : '' ?>>
                                                        Y
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="smoking" value="no"
                                                            <?= (set_value('smoking', @$patientData->smoking) == 'no') ? 'checked' : '' ?>>
                                                        N
                                                    </label>
                                                </div>
                                                <textarea readonly disabled class="form-control mt-2"
                                                    id="smoking_comment" name="smoking_comment"
                                                    placeholder="Comment"><?= set_value('smoking_comment', @$patientData->smoking_comment) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Alcohol use:</label>
                                                <div>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="alcohol" value="yes"
                                                            <?= (set_value('alcohol', @$patientData->alcohol) == 'yes') ? 'checked' : '' ?>>
                                                        Y
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="alcohol" value="no"
                                                            <?= (set_value('alcohol', @$patientData->alcohol) == 'no') ? 'checked' : '' ?>>
                                                        N
                                                    </label>
                                                </div>
                                                <textarea readonly disabled class="form-control mt-2"
                                                    id="alcohol_comment" name="alcohol_comment"
                                                    placeholder="Comment"><?= set_value('alcohol_comment', @$patientData->alcohol_comment) ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="other">Other (Sexual activity, LMP, etc.):</label>
                                                <div>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="other_activity"
                                                            value="yes"
                                                            <?= (set_value('other_activity', @$patientData->other_activity) == 'yes') ? 'checked' : '' ?>>
                                                        Y
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input readonly disabled type="radio" name="other_activity"
                                                            value="no"
                                                            <?= (set_value('other_activity', @$patientData->other_activity) == 'no') ? 'checked' : '' ?>>
                                                        N
                                                    </label>
                                                </div>
                                                <textarea readonly disabled class="form-control mt-2"
                                                    id="other_activity_comment" name="other_activity_comment"
                                                    placeholder="Comment"><?= set_value('other_activity_comment', @$patientData->other_activity_comment) ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>" id="tasks"
                        role="tabpanel">
                        <div class="card">
                            <div class="card-body p-4">
                                <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                                <form role="form" action="appointment/updateApppinmentVitalDetail" method="post"
                                    enctype="multipart/form-data">
                                    <?php endif; ?>
                                    <input type="hidden" value="<?= $apid ?>" name="apid">
                                    <input type="hidden" value="<?= $patent_id ?>" name="patient_id">
                                    <input type="hidden" value="<?= $this->ion_auth->get_user_id() ?? 0 ?>"
                                        name="doctor_id">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="temp">Temp (°C):</label>
                                                <input type="text" class="form-control" id="temp" name="temp"
                                                    value="<?= set_value('temp', @$appointmentData->temp) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="bp">BP (mmHg):</label>
                                                <input type="text" class="form-control" id="bp" name="bp"
                                                    value="<?= set_value('bp', @$appointmentData->bp) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="pulse">Pulse (b/min):</label>
                                                <input type="text" class="form-control" id="pulse" name="pulse"
                                                    value="<?= set_value('pulse', @$appointmentData->pulse) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="spo2">SpO2 (%):</label>
                                                <input type="text" class="form-control" id="spo2" name="spo2"
                                                    value="<?= set_value('spo2', @$appointmentData->spo2) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="rr">RR (b/min):</label>
                                                <input type="text" class="form-control" id="rr" name="rr"
                                                    value="<?= set_value('rr', @$appointmentData->rr) ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="height">Height (cm):</label>
                                                <input type="text" class="form-control" id="height" name="height"
                                                    value="<?= set_value('height', @$appointmentData->height) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="weight">Weight (kg):</label>
                                                <input type="text" class="form-control" id="weight" name="weight"
                                                    value="<?= set_value('weight', @$appointmentData->weight) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="bmi">BMI:</label>
                                                <input type="text" class="form-control" id="bmi" name="bmi"
                                                    value="<?= set_value('bmi', @$appointmentData->bmi) ?>" />
                                            </div>
                                            <div class="form-group">
                                                <label for="rbs">RBS (mg/dL):</label>
                                                <input type="text" class="form-control" id="rbs" name="rbs"
                                                    value="<?= set_value('rbs', @$appointmentData->rbs) ?>" />
                                            </div>
                                        </div>


                                        <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                                        <div class="form-group">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-primary"
                                                style="background:#097eb8 !important;border: unset;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                            </div>
                            <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- physical info form -->
                <div class="tab-pane <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>" id="tasks"
                    role="tabpanel">
                    <div class="card">
                        <!-- <div class="card-body p-4">
                                    <h5>physical info form</h5>
                                </div> -->
                        <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                        <form role="form" action="appointment/updateApppinmentPhyscialDetail" method="post"
                            enctype="multipart/form-data">
                            <?php endif; ?>

                            <input type="hidden" value="<?= $apid ?>" name="apid">
                            <input type="hidden" value="<?= $patent_id ?>" name="patient_id">
                            <input type="hidden" value="<?= $this->ion_auth->get_user_id() ?? 0 ?>" name="doctor_id">
                            <div class="class">
                                <h2 class="text-center">PHYSICAL EXAMINATION</h2>
                                <p class="text-center"><i>To be completed by Examining Physician</i></p>
                                <table class="table table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Body Systems</th>
                                            <th>Check for</th>
                                            <th>Normal</th>
                                            <th>Abnormal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>General Appearance</td>
                                            <td>Marked overweight, tremor, signs of alcoholism problem drinking or
                                                drug abuse</td>
                                            <td><input type="radio" name="physical_general_appearance"
                                                    <?php if ($data->physical_general_appearance == 'normal') echo 'checked'; ?>
                                                    value="normal"></td>
                                            <td><input type="radio" name="physical_general_appearance"
                                                    <?php if ($data->physical_general_appearance == 'abnormal') echo 'checked'; ?>
                                                    value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>1. Eyes/ Pupils</td>
                                            <td>Pupillary equality, reaction to light, accommodation, ocular muscle
                                                movement, nystagmus, exophthalmos, retinopathy, cataract, glaucoma
                                            </td>
                                            <td><input type="radio" name="physical_eyes_pupils"
                                                    <?php if ($data->physical_eyes_pupils == 'normal') echo 'checked'; ?>
                                                    value="normal"></td>
                                            <td><input type="radio" name="physical_eyes_pupils"
                                                    <?php if ($data->physical_eyes_pupils == 'normal') echo 'checked'; ?>
                                                    value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>2. Ear, Nose and Throat</td>
                                            <td>Tympanic membrane, occlusion of external canal, perforated eardrums,
                                                irregular deformities of the throat likely to interfere with
                                                swallowing</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_ear_nose_throat == 'normal') echo "checked"; ?>
                                                    name="physical_ear_nose_throat" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_ear_nose_throat == 'abnormal') echo "checked"; ?>
                                                    name="physical_ear_nose_throat" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>3. Teeth</td>
                                            <td>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>U</td>
                                                        <td>1</td>
                                                        <td>3</td>
                                                        <td>4</td>
                                                        <td>5</td>
                                                        <td>6</td>
                                                        <td>7</td>
                                                        <td>8</td>
                                                        <td>9</td>
                                                        <td>10</td>
                                                        <td>11</td>
                                                        <td>12</td>
                                                        <td>14</td>
                                                    </tr>
                                                    <tr>
                                                        <td>L</td>
                                                        <td>30</td>
                                                        <td>28</td>
                                                        <td>26</td>
                                                        <td>25</td>
                                                        <td>24</td>
                                                        <td>23</td>
                                                        <td>22</td>
                                                        <td>21</td>
                                                        <td>20</td>
                                                        <td>19</td>
                                                        <td>18</td>
                                                        <td>16</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_teeth == 'normal') echo "checked"; ?>
                                                    name="physical_teeth" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_teeth == 'abnormal') echo "checked"; ?>
                                                    name="physical_teeth" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>4. Lungs/ Chest</td>
                                            <td>Abnormal chest wall expansion, abnormal respiratory rate, abnormal,
                                                wheezing, rales, crackles, cyanosis</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_lungs_chest == 'normal') echo "checked"; ?>
                                                    name="physical_lungs_chest" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_lungs_chest == 'abnormal') echo "checked"; ?>
                                                    name="physical_lungs_chest" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>5. Cardiovascular</td>
                                            <td>Irregular heart sounds, murmurs, pacemaker</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_cardiovascular == 'normal') echo "checked"; ?>
                                                    name="physical_cardiovascular" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_cardiovascular == 'abnormal') echo "checked"; ?>
                                                    name="physical_cardiovascular" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>6. Abdomen</td>
                                            <td>Enlarged liver, enlarged spleen, masses, bruits, hernia</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_abdomen == 'normal') echo "checked"; ?>
                                                    name="physical_abdomen" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_abdomen == 'abnormal') echo "checked"; ?>
                                                    name="physical_abdomen" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>7. Genitourinary system</td>
                                            <td>Hernia orifices, hydroceles, external genital lesions</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_genitourinary_system == 'normal') echo "checked"; ?>
                                                    name="physical_genitourinary_system" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_genitourinary_system == 'abnormal') echo "checked"; ?>
                                                    name="physical_genitourinary_system" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>8. Musculoskeletal</td>
                                            <td>Flaccidity</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_musculoskeletal == 'normal') echo "checked"; ?>
                                                    name="physical_musculoskeletal" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_musculoskeletal == 'abnormal') echo "checked"; ?>
                                                    name="physical_musculoskeletal" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>9. Skin</td>
                                            <td>Rashes</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_skin == 'normal') echo "checked"; ?>
                                                    name="physical_skin" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_skin == 'abnormal') echo "checked"; ?>
                                                    name="physical_skin" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>10. Varicose Veins</td>
                                            <td>Reticular veins, spider veins, varicose nodes, edema, trophic ulcer
                                            </td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_varicose_veins == 'normal') echo "checked"; ?>
                                                    name="physical_varicose_veins" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_varicose_veins == 'abnormal') echo "checked"; ?>
                                                    name="physical_varicose_veins" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>11. Neurological</td>
                                            <td>Impaired equilibrium, decreased power, coordination of speech
                                                pattern, asymmetric deep tendon reflexes, sensory or positional
                                                abnormalities, abnormal reflexes</td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_neurological == 'normal') echo "checked"; ?>
                                                    name="physical_neurological" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_neurological == 'abnormal') echo "checked"; ?>
                                                    name="physical_neurological" value="abnormal"></td>
                                        </tr>
                                        <tr>
                                            <td>12. Extremities</td>
                                            <td>Loss or impairment of limbs, weakness, paralysis, clubbing, edema
                                            </td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_extremities == 'normal') echo "checked"; ?>
                                                    name="physical_extremities" value="normal"></td>
                                            <td><input type="radio"
                                                    <?php if ($data->physical_extremities == 'abnormal') echo "checked"; ?>
                                                    name="physical_extremities" value="abnormal"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <label for="comments">COMMENTS</label>
                                    <textarea class="form-control" id="comments" name="physical_comment_remark"
                                        rows="4"><?= $data->physical_comment_remark ?? '' ?></textarea>
                                </div>
                            </div>
                            <div class="class">
                                <table class="table table-bordered" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Descritption</th>
                                            <th>Yes</th>
                                            <th>No</th>
                                            <th>N/A</th>
                                            <th>Comment/Reasons</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>DIAGNOSIS</td>
                                            <td><input type="radio" name="physical_diagnosis"
                                                    <?php if ($data->physical_diagnosis == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_diagnosis"
                                                    <?php if ($data->physical_diagnosis == 'No') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_diagnosis"
                                                    <?php if ($data->physical_diagnosis == 'NA') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" name="physical_diagnosis_comment"
                                                    id="comments"
                                                    rows="4"><?= $data->physical_diagnosis_comment ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>INVESTIGATION</td>
                                            <td><input type="radio" name="physical_investigation"
                                                    <?php if ($data->physical_investigation == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_investigation"
                                                    <?php if ($data->physical_investigation == 'yes') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_investigation"
                                                    <?php if ($data->physical_investigation == 'yes') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" name="physical_investigation_comment"
                                                    id="comments"
                                                    rows="4"><?= $data->physical_investigation_comment ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>TREATMENT /PROCEDURE</td>
                                            <td><input type="radio" name="physical_treatment_procedure"
                                                    <?php if ($data->physical_treatment_procedure == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_treatment_procedure"
                                                    <?php if ($data->physical_treatment_procedure == 'No') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_treatment_procedure"
                                                    <?php if ($data->physical_treatment_procedure == 'NA') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" id="comments"
                                                    name="physical_treatment_procedure_comment"
                                                    rows="4"><?= $data->physical_treatment_procedure_comment ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>PATIENT EDUCATED ON</td>
                                            <td><input type="radio" name="physical_patient_educated"
                                                    <?php if ($data->physical_patient_educated == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_patient_educated"
                                                    <?php if ($data->physical_patient_educated == 'No') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_patient_educated"
                                                    <?php if ($data->physical_patient_educated == 'NA') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" id="comments"
                                                    name="physical_patient_educated_comment"
                                                    rows="4"><?= $data->physical_patient_educated_comment ?? '' ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>SICK LEAVE </td>
                                            <td><input type="radio" name="physical_sick_leave"
                                                    <?php if ($data->physical_sick_leave == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_sick_leave"
                                                    <?php if ($data->physical_sick_leave == 'No') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_sick_leave"
                                                    <?php if ($data->physical_sick_leave == 'NA') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" name="physical_sick_leave_comment"
                                                    id="comments"
                                                    rows="4"><?= $data->physical_sick_leave_comment ?? '' ?></textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>FOLLOW-UP</td>
                                            <td><input type="radio" name="physical_follow_up"
                                                    <?php if ($data->physical_follow_up == 'yes') echo "checked"; ?>
                                                    value="yes"></td>
                                            <td><input type="radio" name="physical_follow_up"
                                                    <?php if ($data->physical_follow_up == 'No') echo "checked"; ?>
                                                    value="No"></td>
                                            <td><input type="radio" name="physical_follow_up"
                                                    <?php if ($data->physical_follow_up == 'NA') echo "checked"; ?>
                                                    value="NA"></td>
                                            <td><textarea class="form-control" name="physical_follow_up_comment"
                                                    id="comments"
                                                    rows="4"><?= $data->physical_follow_up_comment ?? '' ?></textarea>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                                <?php
                                            $doctor = [];
                                            if ($this->ion_auth->in_group(array('Doctor'))) {
                                                $doctor_ion_id = $this->ion_auth->get_user_id();
                                                $doctor = $this->db->get_where('doctor', array('ion_user_id' => $doctor_ion_id))->row();
                                            }
                                        ?>

                                <div class="form-group">
                                    <label for="comments">Physician’s Signature</label>
                                    <textarea class="form-control" id="comments" name="physical_physician_signature"
                                        rows="2"><?= $data->physical_physician_signature ?? ucwords($doctor->name) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="comments">Physician’s Name</label>
                                    <input type="text" class="form-control" name="physical_physician_name"
                                        value="<?=$data->name ?? ucwords($doctor->name)?>">
                                </div>
                                <div class="form-group">
                                    <label for="comments">Date of examination (DD/MM/YY)</label>
                                    <input type="date" class="form-control" name="physical_examination_date"
                                        value="<?= $data->physical_examination_date ?? date('d-m-Y') ?>">
                                </div>


                                <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                                <div class="form-group">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-primary"
                                        style="background:#097eb8 !important;border: unset;">
                                </div>
                                <?php endif; ?>

                            </div>
                            <?php if ($this->ion_auth->in_group(array('Doctor'))) : ?>
                        </form>
                        <?php endif; ?>


                    </div>
                </div>
                <!-- physical info form -->

            </div>
            </div>
            <?php else : ?>
            <div class="panel-body">
                <div class="mt-3">
                    <div class="text-center">
                        <h3>No Data Found!</h3>
                    </div>
                </div>
            </div>
            <?php endif ?>

        </section>
    </section>
</section>