<!--sidebar end-->
<!--main content start-->
<style>
    a.active {
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


            <?php $apid = $_GET['apid'] ?? 0; ?>
            <?php $patent_id = $_GET['id'] ?? 0; ?>


            <?php $data = $this->appointment_model->getAppointmentByIdOrDoctorId($apid, $patent_id); ?>
            <?php if ($data) : ?>
                <div class="panel-body">
                    <div class="mt-3">
                        <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                            <li class="nav-item <?= ($patient_form_tab == 'appointment') ? 'active' : '' ?>">
                                <a class="nav-link <?= ($patient_form_tab == 'appointment') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=appointment" role="tab">Appointment</a>
                            </li>

                            <li class="nav-item <?= ($patient_form_tab == 'general') ? 'active' : '' ?>">
                                <a class="nav-link <?= ($patient_form_tab == 'general') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=general" role="tab">General</a>
                            </li>

                            <li class="nav-item <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>">
                                <a class="nav-link <?= ($patient_form_tab == 'vital') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=vital" role="tab">Vital</a>
                            </li>
                            <li class="nav-item <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>">
                                <a class="nav-link <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>" data-bs-toggle="tab" href="patient/medical?apid=<?= $apid ?>&id=<?php echo $patient_id ?>&tab=physical" role="tab">Physical</a>
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


                        <!-- physical info form -->
                        <div class="tab-pane <?= ($patient_form_tab == 'physical') ? 'active' : '' ?>" id="tasks" role="tabpanel">
                            <div class="card">
                                <!-- <div class="card-body p-4">
                                    <h5>physical info form</h5>
                                </div> -->
                                <form role="form" action="appointment/updateApppinmentPhyscialDetail" method="post" enctype="multipart/form-data">

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
                                                    <td>Marked overweight, tremor, signs of alcoholism problem drinking or drug abuse</td>
                                                    <td><input type="radio" name="physical_general_appearance" <?php if ($data->physical_general_appearance == 'normal') echo 'checked'; ?> value="normal"></td>
                                                    <td><input type="radio" name="physical_general_appearance" <?php if ($data->physical_general_appearance == 'abnormal') echo 'checked'; ?> value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>1. Eyes/ Pupils</td>
                                                    <td>Pupillary equality, reaction to light, accommodation, ocular muscle movement, nystagmus, exophthalmos, retinopathy, cataract, glaucoma</td>
                                                    <td><input type="radio" name="physical_eyes_pupils" <?php if ($data->physical_eyes_pupils == 'normal') echo 'checked'; ?> value="normal"></td>
                                                    <td><input type="radio" name="physical_eyes_pupils" <?php if ($data->physical_eyes_pupils == 'normal') echo 'checked'; ?> value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>2. Ear, Nose and Throat</td>
                                                    <td>Tympanic membrane, occlusion of external canal, perforated eardrums, irregular deformities of the throat likely to interfere with swallowing</td>
                                                    <td><input type="radio" <?php if ($data->physical_ear_nose_throat == 'normal') echo "checked"; ?> name="physical_ear_nose_throat" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_ear_nose_throat == 'abnormal') echo "checked"; ?> name="physical_ear_nose_throat" value="abnormal"></td>
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
                                                    <td><input type="radio" <?php if ($data->physical_teeth == 'normal') echo "checked"; ?> name="physical_teeth" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_teeth == 'abnormal') echo "checked"; ?> name="physical_teeth" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>4. Lungs/ Chest</td>
                                                    <td>Abnormal chest wall expansion, abnormal respiratory rate, abnormal, wheezing, rales, crackles, cyanosis</td>
                                                    <td><input type="radio" <?php if ($data->physical_lungs_chest == 'normal') echo "checked"; ?> name="physical_lungs_chest" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_lungs_chest == 'abnormal') echo "checked"; ?> name="physical_lungs_chest" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>5. Cardiovascular</td>
                                                    <td>Irregular heart sounds, murmurs, pacemaker</td>
                                                    <td><input type="radio" <?php if ($data->physical_cardiovascular == 'normal') echo "checked"; ?> name="physical_cardiovascular" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_cardiovascular == 'abnormal') echo "checked"; ?> name="physical_cardiovascular" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>6. Abdomen</td>
                                                    <td>Enlarged liver, enlarged spleen, masses, bruits, hernia</td>
                                                    <td><input type="radio" <?php if ($data->physical_abdomen == 'normal') echo "checked"; ?> name="physical_abdomen" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_abdomen == 'abnormal') echo "checked"; ?> name="physical_abdomen" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>7. Genitourinary system</td>
                                                    <td>Hernia orifices, hydroceles, external genital lesions</td>
                                                    <td><input type="radio" <?php if ($data->physical_genitourinary_system == 'normal') echo "checked"; ?> name="physical_genitourinary_system" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_genitourinary_system == 'abnormal') echo "checked"; ?> name="physical_genitourinary_system" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>8. Musculoskeletal</td>
                                                    <td>Flaccidity</td>
                                                    <td><input type="radio" <?php if ($data->physical_musculoskeletal == 'normal') echo "checked"; ?> name="physical_musculoskeletal" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_musculoskeletal == 'abnormal') echo "checked"; ?> name="physical_musculoskeletal" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>9. Skin</td>
                                                    <td>Rashes</td>
                                                    <td><input type="radio" <?php if ($data->physical_skin == 'normal') echo "checked"; ?> name="physical_skin" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_skin == 'abnormal') echo "checked"; ?> name="physical_skin" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>10. Varicose Veins</td>
                                                    <td>Reticular veins, spider veins, varicose nodes, edema, trophic ulcer</td>
                                                    <td><input type="radio" <?php if ($data->physical_varicose_veins == 'normal') echo "checked"; ?> name="physical_varicose_veins" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_varicose_veins == 'abnormal') echo "checked"; ?> name="physical_varicose_veins" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>11. Neurological</td>
                                                    <td>Impaired equilibrium, decreased power, coordination of speech pattern, asymmetric deep tendon reflexes, sensory or positional abnormalities, abnormal reflexes</td>
                                                    <td><input type="radio" <?php if ($data->physical_neurological == 'normal') echo "checked"; ?> name="physical_neurological" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_neurological == 'abnormal') echo "checked"; ?> name="physical_neurological" value="abnormal"></td>
                                                </tr>
                                                <tr>
                                                    <td>12. Extremities</td>
                                                    <td>Loss or impairment of limbs, weakness, paralysis, clubbing, edema</td>
                                                    <td><input type="radio" <?php if ($data->physical_extremities == 'normal') echo "checked"; ?> name="physical_extremities" value="normal"></td>
                                                    <td><input type="radio" <?php if ($data->physical_extremities == 'abnormal') echo "checked"; ?> name="physical_extremities" value="abnormal"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="form-group">
                                            <label for="comments">COMMENTS</label>
                                            <textarea class="form-control" id="comments" name="physical_comment_remark" rows="4"><?= $data->physical_comment_remark ?? '' ?></textarea>
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
                                                    <td><input type="radio" name="physical_diagnosis" <?php if ($data->physical_diagnosis == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_diagnosis" <?php if ($data->physical_diagnosis == 'No') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_diagnosis" <?php if ($data->physical_diagnosis == 'NA') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" name="physical_diagnosis_comment" id="comments" rows="4"><?= $data->physical_diagnosis_comment ?></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td>INVESTIGATION</td>
                                                    <td><input type="radio" name="physical_investigation" <?php if ($data->physical_investigation == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_investigation" <?php if ($data->physical_investigation == 'yes') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_investigation" <?php if ($data->physical_investigation == 'yes') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" name="physical_investigation_comment" id="comments" rows="4"><?= $data->physical_investigation_comment ?></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td>TREATMENT /PROCEDURE</td>
                                                    <td><input type="radio" name="physical_treatment_procedure" <?php if ($data->physical_treatment_procedure == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_treatment_procedure" <?php if ($data->physical_treatment_procedure == 'No') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_treatment_procedure" <?php if ($data->physical_treatment_procedure == 'NA') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" id="comments" name="physical_treatment_procedure_comment" rows="4"><?= $data->physical_treatment_procedure_comment ?></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td>PATIENT EDUCATED ON</td>
                                                    <td><input type="radio" name="physical_patient_educated" <?php if ($data->physical_patient_educated == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_patient_educated" <?php if ($data->physical_patient_educated == 'No') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_patient_educated" <?php if ($data->physical_patient_educated == 'NA') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" id="comments" name="physical_patient_educated_comment" rows="4"><?= $data->physical_patient_educated_comment ?? '' ?></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td>SICK LEAVE </td>
                                                    <td><input type="radio" name="physical_sick_leave" <?php if ($data->physical_sick_leave == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_sick_leave" <?php if ($data->physical_sick_leave == 'No') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_sick_leave" <?php if ($data->physical_sick_leave == 'NA') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" name="physical_sick_leave_comment" id="comments" rows="4"><?= $data->physical_sick_leave_comment ?? '' ?></textarea></td>
                                                </tr>

                                                <tr>
                                                    <td>FOLLOW-UP</td>
                                                    <td><input type="radio" name="physical_follow_up" <?php if ($data->physical_follow_up == 'yes') echo "checked"; ?> value="yes"></td>
                                                    <td><input type="radio" name="physical_follow_up" <?php if ($data->physical_follow_up == 'No') echo "checked"; ?> value="No"></td>
                                                    <td><input type="radio" name="physical_follow_up" <?php if ($data->physical_follow_up == 'NA') echo "checked"; ?> value="NA"></td>
                                                    <td><textarea class="form-control" name="physical_follow_up_comment" id="comments" rows="4"><?= $data->physical_follow_up_comment ?? '' ?></textarea></td>
                                                </tr>

                                            </tbody>
                                        </table>

                                        <div class="form-group">
                                            <label for="comments">Physician’s Signature</label>
                                            <textarea class="form-control" id="comments" name="physical_physician_signature" rows="2"><?= $data->physical_physician_signature ?? '' ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="comments">Physician’s Name</label>
                                            <input type="text" class="form-control" name="physical_physician_name" value="DR. AYESHA WILBURG">
                                        </div>
                                        <div class="form-group">
                                            <label for="comments">Date of examination (DD/MM/YY)</label>
                                            <input type="date" class="form-control" name="physical_examination_date" value="<?= $data->physical_examination_date ?? date('d-m-Y') ?>">
                                        </div>



                                        <div class="form-group">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-primary" style="background:#097eb8 !important;border: unset;">
                                        </div>

                                    </div>
                                </form>


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