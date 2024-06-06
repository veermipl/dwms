<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DWMS Invoice</title>
</head>

<body onload="window.print()">
    <div class="invoice-box"
        style="max-width: 890px; margin: auto; padding: 10px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 24px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #555;">
        <table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;">
            <tr class="top_rw">
                <td colspan="2" style="width:30%;padding: 15px 15px 10px 15px;">
                    <img src="<?= base_url('uploads/invoice-pdf-logo.png'); ?>" style="width:200px">
                </td>
                <td style="padding: 15px 15px 10px 15px; text-align: right;">
                    <h2
                        style="margin-bottom: 0px; margin-top: 0; color: #273477;
                     font-size: 24px;">
                        Invoice </h2>
                </td>
            </tr>
            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 50%; padding: 15px 0px 10px 15px;">
                                <b><?= $appAddress ?></b> <br>
                                <b>Phone:</b> <?= $appNumber ?> <br>
                                <b>Website:</b> <?= $appWeb ?> <br>
                                <b>Email:</b> <?= $appMail ?>
                            </td>
                            <td style="float:right;text-align:right;width: 50%; padding: 15px 15px 10px 0px; ">
                                <b>Date:</b> <?= $invDate ?> <br>
                                <b>Invoice:</b> <?= $invNumber ?> <br>
                                <b>Customer ID:</b> <?= $patient_id ?></span> </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 15px 15px 10px;">
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td style="color: #273477;font-size: 20px;font-weight: 700;">BILL TO :</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td style="width: 100%; color:#222222f7" colspan="3">
                    <table style="width: 100%">
                        <tr style="max-width: 100%">
                            <td style="width: 70%; padding: 0px 0px 10px 15px;">
                                <b>Patient Name:</b> <?= $patient_name ?>
                                <b>Booking No:</b> <?= $apt_id ?> <br>
                                <b>Created at:</b> <?= $apt_date ?><br>
                                
                            </td>
                            <td style="float:right;text-align:right;width: 100%; padding: 0px 15px 10px 0px; ">
                                <b>Doctor Name:</b> <?= $doctor_name ?> <br>
                                <b>Mode Of Consultation:</b> <?= $apt_mode_of_consultation ?><br>
                                <!-- <b>Type Of Consultation:</b> <?= $apt_type_of_consultation ?><br> -->
                                <b>Status:</b> <?= $apt_status ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="details" style="width: 100%;">
                <td colspan="3" style="padding: 15px 15px 5px; width: 100%;">
                    <table cellspacing="0px" cellpadding="2px" style="width: 100%;">
                        <tr class="heading" bgcolor="#273477"
                            style="background-color: #273477!important;color: #fff; font-weight: bold; font-size: 12px;width: 100%;">
                            <td
                                style="width:50%; text-align:center;padding:5px 10px;border-left: 1px solid #000;border-bottom: 1px solid #000;border-top: 1px solid #000;">
                                Time Slot
                            </td>
                            <td
                                style="width:15%;padding: 5px 10px; text-align:center;border-left: 1px solid #000;border-top: 1px solid #000;border-bottom: 1px solid #000;">
                                Unit Price
                            </td>
                            <td
                                style="width:10%;padding: 5px 10px; text-align:center;border-top: 1px solid #000;border-left: 1px solid #000;border-bottom: 1px solid #000;">
                                QTY
                            </td>
                            <td
                                style="width:20%;padding: 5px 10px; text-align:center; border-top: 1px solid #000;border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">
                                Amount
                            </td>
                        </tr>

                        

                        
                            <tr class="item">
                                <td style="padding-left: 5px;  border-bottom: 1px solid #000;border-left: 1px solid #000;">
                                <?= $apt_time_slot ?>
                                </td>
                                <td style="text-align:center;border-bottom: 1px solid #000;border-left: 1px solid #000;">
                                <?= $apt_amount ?>
                                </td>
                                <td style="text-align:center;border-bottom: 1px solid #000;border-left: 1px solid #000;">
                                    1
                                </td>
                                <td style=" text-align:center;border-bottom: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">
                                    &nbsp;$&nbsp;<?= $apt_amount ?>
                                </td>
                            </tr>
                        
                        <tr class="item">
                            <td style="width:15%; text-align:right;font-weight: 600; padding-right:10px;
                           font-size: 14px;border-bottom: 1px solid #000;border-left: 1px solid #000;"
                                colspan="3">
                                Sub Total
                            </td>
                            <td
                                style="width:15%; text-align:center;font-weight: 600;
                           color: #273477;
                           font-size: 14px;border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">
                                &nbsp;$&nbsp;<?= $apt_amount ?>
                            </td>
                        </tr>
                        
                        <tr class="item">
                            <td style="width:15%; text-align:right;font-weight: 600; color: #273477; padding-right:10px; font-size: 14px;border-bottom: 1px solid #000;border-left: 1px solid #000;" colspan="3">
                                Total
                            </td>
                            <td style="width:15%; text-align:center;font-weight: 600; color: #273477; font-size: 14px;border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">
                                $&nbsp;<?= $apt_amount ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- <tr class="total">
                <td colspan="3" align="right" style="padding-right: 15px; color: #222222f7;">
                    <b>Registered No:  07955959</b>
                </td>
            </tr> -->
            <tr class="total">
                <td colspan="3" style="padding: 15px 15px 5px;color: #273477;text-transform: uppercase;font-weight: 700;font-size: 16px;">
                    <b>Other Comments :</b>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 2px 15px;">1. Total payment due upon receipt </td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 2px 15px;">2. Please include the invoice number on your cheque</td>
            </tr>
            
            <!-- <br> -->
            <!-- <tr class="total">
                <td colspan="3" style="padding: 15px 15px 5px;color: #273477;text-transform: uppercase;font-weight: 700;font-size: 16px;">
                    <b>Payment :</b>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 15px;" colspan="3"> BACS:- Acc: 43915034 Sort code:208916
                    Cheques payable to Sakoon Consulting Ltd
                </td>
            </tr> -->
            <tr style="text-align:center;">
                <td style="padding: 15px 15px 5px;color: #222222f7;    font-weight: 600;
                  line-height: 18px;"
                    colspan="3">
                    If you have any questions about this invoice,<br />please contact DWMS
                </td>
            </tr>
            <tr style="text-align:center;">
                <td style="padding: 5px 15px 15px;color:  #273477;    font-size: 18px;  font-weight: 600;
                  line-height: 18px;"
                    colspan="3">
                    Thank you
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
