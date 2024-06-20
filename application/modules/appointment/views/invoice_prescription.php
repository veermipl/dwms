<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DWMS Prescription</title>
</head>

<body onload="window.print()">
    <div class="invoice-box" style="max-width: 890px; margin: auto; padding: 10px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 24px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555;">
        <table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;">
            <tr class="top_rw">
                <td style="width:100%;padding: 15px 15px 10px 15px; text-align: center;">
                    <img src="<?= base_url('uploads/invoice-pdf-logo.png'); ?>" style="width:200px">
                </td>
            </tr>
            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 15px 0px 10px 15px;">
                                <b>Dr. <?=ucwords($doctor_name) ?? ''  ?></b> <br>
                                <b>Profile:</b> <?= ucwords($doctor_profile) ?> <br>
                                <b>Phone:</b> <?= $doctor_phone ?> <br>
                                <b>Email:</b> <?= $doctor_email ?> <br>
                            </td>
                             <td style="float:right;text-align:right;width: 50%; padding: 15px 15px 10px 0px; ">
                             	<b><u>Address</u></b><br>
                                <p><?=$doctor_address ?? ''?></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
             <tr class="information" style="margin-top:10px">
                <td colspan="2" style="padding: 15px 0px 10px 15px;">
                    <b><u>Patient & Appointment Details</u></b>
                </td>
             </tr>
            <tr class="information" style="margin-top:10px">
                <td style="width: 100%; color:#222222f7; padding: 15px 0px 10px 15px;" colspan="3">
                    <table style="width: 100%" border="1" style="text-align: center;">
                        <tr style="text-align: center;">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>DOB</th>
                            <th>Appointment</th>
                        </tr>
                        <tr style="text-align: center;">
                            <td><?=$invNumber ?? ''?></td>
                            <td><?=ucwords($patient_name) ?? ''?></td>
                            <td><?=$patient_age ?? ''?></td>
                            <td><?=$patient_sex ?? ''?></td>
                            <td><?=$patient_dob ?? ''?></td>
                            <td><?=$apt_time_slot ?? ''?></td>
                        </tr>
                    </table>
                </td>
            </tr>


            <tr class="total" style="margin-top:40px">
                <td style="width:100%;margin-top:40px" colspan="2">
                    <br>
                    <b style="padding: 15px 15px 5px;color: #2ea2d9; text-transform: uppercase;font-weight: 700;font-size: 16px;padding-top:40px"><u><?=lang('diagnosis_list')?></u></b><br>
                    <p style="font-size: 12px;text-align: justify;">
                        <?php if($saved_diagnos_ids && count($saved_diagnos_ids) > 0): ?>
                            <?php foreach($saved_diagnos_ids as $key => $row): ?>
                                    <?=ucwords($row).', ' ?? ''?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </p>
                </td>
            </tr>
           
            
            <tr class="total" style="margin-top:40px">
                <td style="width:100%;margin-top:40px" colspan="2">
                    <br>
                    <b style="padding: 15px 15px 5px;color: #2ea2d9; text-transform: uppercase;font-weight: 700;font-size: 16px;padding-top:40px"><u><?=lang('diagnos')?></u></b><br>
                    <p style="font-size: 12px;text-align: justify;"><?=$diagnosis ?? ''?></p>
                </td>
            </tr>
            <tr class="total">
            	<td style="width:100%;" colspan="2">
                    <b style="padding: 15px 15px 5px;color: #2ea2d9; text-transform: uppercase;font-weight: 700;font-size: 16px;"><u>Prescription</u></b><br>
                    <p style="font-size: 12px;text-align: justify;"><?=$prescription ?? ''?></p>
                </td>
            </tr>

            <tr style="width:100%; text-align:right" >
                

                <td style="width:50%;padding: 15px 15px 5px;color: #222222f7; font-weight: 600;line-height: 18px;" colspan="2">
                  <br>
                    <p>Doctor Signature</p>
                    <p style="font-weight: 500;;margin-bottom: 0;"><?=ucwords($doctor_name) ?? ''?></p>
                    <p style="font-weight: 500;margin-bottom: 0;margin-top: 0;padding:0"><?=ucwords($doctor_profile) ?? ''?></p>
                    <br>
                    <br>
                    <br>
                    <br>
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