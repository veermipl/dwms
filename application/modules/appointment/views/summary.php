<section id="main-content">
    <section class="wrapper site-min-height">
        <link href="<?= base_url() ?>/common/extranal/css/patient/patient.css" rel="stylesheet">
        <section class="">

            <header class="panel-heading">
                <?php echo lang('summary'); ?>
                <div class="pull-right">
                    <a href="appointment/downloadSummary?apt=<?= $appointmentData->id ?>" class="btn btn-dark mr-2">
                        <i class="fa fa-print"></i> PDF
                    </a>
                    <a href="appointment/shareSummary?apt=<?= $appointmentData->id ?>&type=company" class="btn btn-dark mr-2">
                        <i class="fa fa-share"></i> Share to company
                    </a>
                    <a href="appointment/shareSummary?apt=<?= $appointmentData->id ?>&type=patient" class="btn btn-dark mr-2">
                        <i class="fa fa-share"></i> Share to patient
                    </a>
                </div>
            </header>

            <?php if ($appointmentData) : ?>

                <div class="panel-body">
                    <div class="tab-content">

                        <div class="card">
                            <div class="card-body">

                                <div>
                                    <h5 class="card-title fw-bold">Appointment Information</h5>

                                    <div class="row my-4">
                                        <ul class="list-unstyled m-0">
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Patient</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Doctor</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $doctorData ? $doctorData->name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Time Slot</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $appointmentData ? $appointmentData->time_slot : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Location</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $locationData ? $locationData->name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Date</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $appointmentData ? date('d-m-Y', ($appointmentData->date)) : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Status</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $statusData ? $statusData->status_name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Mode Of Consultation</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $consultationModeData ? $consultationModeData->mode_of_consultation : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Type Of Consultation</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $consultationTypeData ? $consultationTypeData->name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Remarks</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $appointmentData ? $appointmentData->remarks : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="card-title fw-bold">General Information</h5>

                                    <div class="row my-4">
                                        <ul class="list-unstyled m-0">
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Patient</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->name : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Email</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->email : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Phone</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->phone : '' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Blood Group</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->bloodgroup : '' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">DOB</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->birthdate : '' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Age</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->age : '' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Gender</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->sex : '' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Passport Number</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->idType_passport) ? $patientData->idType_passport : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Driver’s License Number</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->idType_drivers) ? $patientData->idType_drivers : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Other</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->idType_other) ? $patientData->idType_other : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Address</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData ? $patientData->address : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Chief Complaint</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->chiefComplaint) ? $patientData->chiefComplaint : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">History of Presenting Illness</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->historyOfIllness) ? $patientData->historyOfIllness : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Past Medical History and Drug History</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->pastMedicalHistory) ? $patientData->pastMedicalHistory : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Past Surgical History</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($patientData->pastSurgicalHistory) ? $patientData->pastSurgicalHistory : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Allergies</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData->allergies == 'yes' ? $patientData->allergies_comment : 'No' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Smoking</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData->smoking == 'yes' ? $patientData->smoking_comment : 'No' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Alcohol use</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData->alcohol == 'yes' ? $patientData->alcohol_comment : 'No' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Other (Sexual activity, LMP, etc.)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $patientData->other_activity == 'yes' ? $patientData->other_activity_comment : 'No' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="card-title fw-bold">Vital Information</h5>

                                    <div class="row my-4">
                                        <ul class="list-unstyled m-0">
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Temp (°C)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->temp) ? $appointmentData->temp : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Height (cm)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->height) ? $appointmentData->height : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">BP (mmHg)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->bp) ? $appointmentData->bp : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Weight (kg)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->weight) ? $appointmentData->weight : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Pulse (b/min)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->pulse) ? $appointmentData->pulse : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">BMI</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->bmi) ? $appointmentData->bmi : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">SpO2 (%)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->spo2) ? $appointmentData->spo2 : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">RBS (mg/dL)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->rbs) ? $appointmentData->rbs : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">RR (b/min)</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->rr) ? $appointmentData->rr : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="card-title fw-bold">Physical Information</h5>

                                    <div class="row my-4">
                                        <div class="class">
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
                                                        <td>
                                                            <?= ($pysicalFormData->physical_general_appearance == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_general_appearance == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>1. Eyes/ Pupils</td>
                                                        <td>Pupillary equality, reaction to light, accommodation, ocular muscle
                                                            movement, nystagmus, exophthalmos, retinopathy, cataract, glaucoma
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_eyes_pupils == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_eyes_pupils == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2. Ear, Nose and Throat</td>
                                                        <td>Tympanic membrane, occlusion of external canal, perforated eardrums,
                                                            irregular deformities of the throat likely to interfere with
                                                            swallowing</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_ear_nose_throat == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_ear_nose_throat == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
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
                                                        <td>
                                                            <?= ($pysicalFormData->physical_teeth == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_teeth == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4. Lungs/ Chest</td>
                                                        <td>Abnormal chest wall expansion, abnormal respiratory rate, abnormal,
                                                            wheezing, rales, crackles, cyanosis</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_lungs_chest == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_lungs_chest == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5. Cardiovascular</td>
                                                        <td>Irregular heart sounds, murmurs, pacemaker</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_cardiovascular == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_cardiovascular == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>6. Abdomen</td>
                                                        <td>Enlarged liver, enlarged spleen, masses, bruits, hernia</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_abdomen == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_abdomen == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>7. Genitourinary system</td>
                                                        <td>Hernia orifices, hydroceles, external genital lesions</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_genitourinary_system == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_genitourinary_system == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>8. Musculoskeletal</td>
                                                        <td>Flaccidity</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_musculoskeletal == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_musculoskeletal == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>9. Skin</td>
                                                        <td>Rashes</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_skin == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_skin == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>10. Varicose Veins</td>
                                                        <td>Reticular veins, spider veins, varicose nodes, edema, trophic ulcer</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_varicose_veins == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_varicose_veins == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>11. Neurological</td>
                                                        <td>Impaired equilibrium, decreased power, coordination of speech
                                                            pattern, asymmetric deep tendon reflexes, sensory or positional
                                                            abnormalities, abnormal reflexes</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_neurological == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_neurological == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>12. Extremities</td>
                                                        <td>Loss or impairment of limbs, weakness, paralysis, clubbing, edema
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_extremities == 'normal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_extremities == 'abnormal') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="d-flex align-items-center col-md-12 pb-3">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 font-size-14">COMMENTS</h5>
                                                    <p class="text-muted mb-1 font-size-13" id="">
                                                        <?= trim($pysicalFormData->physical_comment_remark) ? $pysicalFormData->physical_comment_remark : '-' ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="class mt-3">
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
                                                        <td>
                                                            <?= ($pysicalFormData->physical_diagnosis == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_diagnosis == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_diagnosis == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_diagnosis_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>INVESTIGATION</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_investigation == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_investigation == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_investigation == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_investigation_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>TREATMENT /PROCEDURE</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_treatment_procedure == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_treatment_procedure == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_treatment_procedure == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_treatment_procedure_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>PATIENT EDUCATED ON</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_patient_educated == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_patient_educated == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_patient_educated == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_patient_educated_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>SICK LEAVE </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_sick_leave == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_sick_leave == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_sick_leave == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_sick_leave_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>FOLLOW-UP</td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_follow_up == 'yes') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_follow_up == 'No') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <?= ($pysicalFormData->physical_follow_up == 'NA') ? '<i class="fa fa-check"></i>' : '' ?>
                                                        </td>
                                                        <td>
                                                            <p class="text-muted mb-1 font-size-13" id="">
                                                                <?= $pysicalFormData->physical_follow_up_comment  ?>
                                                            </p>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>

                                            <div class="d-flex align-items-center col-md-6">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 font-size-14">Physician’s Signature</h5>
                                                    <p class="text-muted mb-1 font-size-13" id="">
                                                        <?= $pysicalFormData->physical_physician_signature ?? ucwords($doctor_ion_data->name) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center col-md-6">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 font-size-14">Physician’s Name</h5>
                                                    <p class="text-muted mb-1 font-size-13" id="">
                                                        <?= $pysicalFormData->physical_physician_name ?? ucwords($doctor_ion_data->name) ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center col-md-6">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 font-size-14">Date of examination (DD/MM/YY)</h5>
                                                    <p class="text-muted mb-1 font-size-13" id="">
                                                        <?= $pysicalFormData->physical_examination_date ?? date('d-m-Y') ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center col-md-6">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-0 font-size-14">Examination Completed</h5>
                                                    <p class="text-muted mb-1 font-size-13" id="">
                                                        <?= ($pysicalFormData->exam_complete == '1') ? 'Yes' : 'No' ?>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="card-title fw-bold">Diagnosis & Prescription Information</h5>

                                    <div class="row my-4">
                                        <ul class="list-unstyled m-0">
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Diagnosis</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->diagnosis) ? $appointmentData->diagnosis : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Prescription</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= trim($appointmentData->prescription) ? $appointmentData->prescription : '-' ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="pb-1 col-md-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-0 font-size-14">Diagnosis List</h5>
                                                        <p class="text-muted mb-1 font-size-13" id="">
                                                            <?= $diagnosisListData ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>

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