<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DWMS Prescription</title>

    <link href="<?= base_url() ?>/common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" />
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

        table#physical_info_table td{
            text-align: center;
        }
    </style>
</head>


<!-- <body onload="window.print()"> -->

<body>

    <div class="invoice-box" style="max-width: 890px; margin: auto; padding: 10px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 24px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555;">

        <table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;">

            <tr class="top_rw">
                <td style="width:100%;padding: 15px 15px 10px 15px; text-align: center;">
                    <img src="<?= base_url('uploads/invoice-pdf-logo.png'); ?>" style="width:200px">
                </td>
            </tr>

            <tr class="title" style="margin-top:10px">
                <td colspan="2" style="padding: 0 0 14px 14px;">
                    <b><u>Appointment Information</u></b>
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 14px 14px">
                                <b>Patient:</b> <?= $patientData ? $patientData->name : '-' ?> <br>
                                <b>Doctor:</b> <?= $doctorData ? $doctorData->name : '-' ?> <br>
                                <b>Time:</b> <?= $appointmentData ? $appointmentData->time_slot : '-' ?> <br>
                                <b>Location:</b> <?= $locationData ? $locationData->name : '-' ?> <br>
                                <b>Date:</b> <?= $appointmentData ? date('d-m-Y', ($appointmentData->date)) : '-' ?> <br>
                                <b>Status:</b> <?= $statusData ? $statusData->status_name : '-' ?> <br>
                                <b>Mode Of Consultation:</b> <?= $consultationModeData ? $consultationModeData->mode_of_consultation : '-' ?> <br>
                                <b>Type Of Consultation:</b> <?= $consultationTypeData ? $consultationTypeData->name : '-' ?> <br>
                                <b>Remarks:</b> <?= $appointmentData ? $appointmentData->remarks : '-' ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="title" style="margin-top:10px">
                <td colspan="2" style="padding: 0 0 14px 14px;">
                    <b><u>General Information</u></b>
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 14px 14px">
                                <b>Patient:</b> <?= $patientData ? $patientData->name : '-' ?> <br>
                                <b>Email:</b> <?= $patientData ? $patientData->email : '-' ?> <br>
                                <b>Phone:</b> <?= $patientData ? $patientData->phone : '' ?> <br>
                                <b>Blood Group:</b> <?= $patientData ? $patientData->bloodgroup : '' ?> <br>
                                <b>DOB:</b> <?= $patientData ? $patientData->birthdate : '' ?> <br>
                                <b>Age:</b> <?= $patientData ? $patientData->age : '' ?> <br>
                                <b>Gender:</b> <?= $patientData ? $patientData->sex : '' ?> <br>
                                <b>Passport Number:</b> <?= trim($patientData->idType_passport) ? $patientData->idType_passport : '-' ?> <br>
                                <b>Driver’s License Number:</b> <?= trim($patientData->idType_drivers) ? $patientData->idType_drivers : '-' ?> <br>
                                <b>Other:</b> <?= trim($patientData->idType_other) ? $patientData->idType_other : '-' ?> <br>
                                <b>Address:</b> <?= $patientData ? $patientData->address : '-' ?> <br>
                                <b>Chief Complaint:</b> <?= trim($patientData->chiefComplaint) ? $patientData->chiefComplaint : '-' ?> <br>
                                <b>History of Presenting Illness:</b> <?= trim($patientData->historyOfIllness) ? $patientData->historyOfIllness : '-' ?> <br>
                                <b>Past Medical History and Drug History:</b> <?= trim($patientData->pastMedicalHistory) ? $patientData->pastMedicalHistory : '-' ?> <br>
                                <b>Past Surgical History:</b> <?= trim($patientData->pastSurgicalHistory) ? $patientData->pastSurgicalHistory : '-' ?> <br>
                                <b>Allergies:</b> <?= $patientData->allergies == 'yes' ? $patientData->allergies_comment : 'No' ?> <br>
                                <b>Smoking:</b> <?= $patientData->smoking == 'yes' ? $patientData->smoking_comment : 'No' ?> <br>
                                <b>Alcohol use:</b> <?= $patientData->alcohol == 'yes' ? $patientData->alcohol_comment : 'No' ?> <br>
                                <b>Other (Sexual activity, LMP, etc.):</b> <?= $patientData->other_activity == 'yes' ? $patientData->other_activity_comment : 'No' ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="title" style="margin-top:10px">
                <td colspan="2" style="padding: 0 0 14px 14px;">
                    <b><u>Vital Information</u></b>
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 14px 14px">
                                <b>Temp (°C):</b> <?= trim($appointmentData->temp) ? $appointmentData->temp : '-' ?><br>
                                <b>Height (cm):</b> <?= trim($appointmentData->height) ? $appointmentData->height : '-' ?> <br>
                                <b>BP (mmHg):</b> <?= trim($appointmentData->bp) ? $appointmentData->bp : '-' ?> <br>
                                <b>Weight (kg):</b> <?= trim($appointmentData->weight) ? $appointmentData->weight : '-' ?> <br>
                                <b>Pulse (b/min):</b> <?= trim($appointmentData->pulse) ? $appointmentData->pulse : '-' ?> <br>
                                <b>BMI:</b> <?= trim($appointmentData->bmi) ? $appointmentData->bmi : '-' ?> <br>
                                <b>SpO2 (%):</b> <?= trim($appointmentData->spo2) ? $appointmentData->spo2 : '-' ?> <br>
                                <b>RBS (mg/dL):</b> <?= trim($appointmentData->rbs) ? $appointmentData->rbs : '-' ?> <br>
                                <b>RR (b/min):</b> <?= trim($appointmentData->rr) ? $appointmentData->rr : '-' ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="title" style="margin-top:10px">
                <td colspan="2" style="padding: 0 0 14px 14px;">
                    <b><u>Physical Information</u></b>
                </td>
            </tr>

            <tr class="information" style="margin-top:10px">
                <td style="width: 100%; color:#222222f7; padding: 15px 0px 10px 15px;" colspan="3">
                    <table style="width: 100%" border="1" style="text-align: center;" id="physical_info_table">
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
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 0 14px">
                                <b>Comments:</b> <?= trim($pysicalFormData->physical_comment_remark) ? $pysicalFormData->physical_comment_remark : '-' ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information" style="margin-top:10px">
                <td style="width: 100%; color:#222222f7; padding: 15px 0px 10px 15px;" colspan="3">
                    <table style="width: 100%" border="1" style="text-align: center;" id="physical_info_table">
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
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 14px 14px">
                                <b>Physician’s Signature:</b> <?= $pysicalFormData->physical_physician_signature ?? ucwords($doctor_ion_data->name) ?> <br>
                                <b>Physician’s Name:</b> <?= $pysicalFormData->physical_physician_name ?? ucwords($doctor_ion_data->name) ?> <br>
                                <b>Date of examination (DD/MM/YY):</b> <?= $pysicalFormData->physical_examination_date ?? date('d-m-Y') ?> <br>
                                <b>Examination Completed:</b> <?= ($pysicalFormData->exam_complete == '1') ? 'Yes' : 'No' ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="title" style="margin-top:10px">
                <td colspan="2" style="padding: 0 0 14px 14px;">
                    <b><u>Diagnosis & Prescription Information</u></b>
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 0 14px 14px 14px">
                                <b>Diagnosis:</b> <?= trim($appointmentData->diagnosis) ? $appointmentData->diagnosis : '-' ?> <br>
                                <b>Prescription:</b> <?= trim($appointmentData->prescription) ? $appointmentData->prescription : '-' ?> <br>
                                <b>Diagnosis List:</b> <?= $diagnosisListData ?> <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>


            <tr style="text-align:center;margin-top:100px;height: 60px;top:100px">

                <td style="padding:5px 13px 5px;color:  #fff;    font-size: 20px;  font-weight: 500;background: #b9202f;" colspan="2">

                    Dr. wilburg's Medical Services

                </td>

            </tr>

        </table>

    </div>

</body>

</html>