<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pre')) {

    function pre($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

}


if (!function_exists('return_data')) {

    function return_data($status = false, $message = "", $data = array(), $error = array()) {
        echo json_encode(array('status' => $status, 'message' => $message, 'data' => $data, 'error' => $error));
        die;
    }

}


if (!function_exists('post_check')) {

    function post_check() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(array('status' => false, 'message' => "Invalid input parameter.Please use post method.", 'data' => array(), 'error' => array()));
            die;
        }
    }

}

if (!function_exists('_send_email')) {
    function _send_email($to, $content, $sub)
    {
        
       //echo  $to;  echo $content; echo $sub; die;
		$content = '<!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
        
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="x-apple-disable-message-reformatting">
            <title></title>
            <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i" rel="stylesheet">
            <style>
            html,
            body {
                margin: 0 auto !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
                background: #f1f1f1;
            }
            
            {
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }
            
            div[style*="margin: 16px 0"] {
                margin: 0 !important;
            }
            
            table,
            td {
                mso-table-lspace: 0pt !important;
                mso-table-rspace: 0pt !important;
            }
            
            table {
                border-spacing: 0 !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                margin: 0 auto !important;
            }
            
            img {
                -ms-interpolation-mode: bicubic;
            }
            
            a {
                text-decoration: none;
            }
            
            .aBn {
                border-bottom: 0 !important;
                cursor: default !important;
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
            
            .a6S {
                display: none !important;
                opacity: 0.01 !important;
            }
            
            .g-img + div {
                display: none !important;
            }
            
            @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
                u ~ div .email-container {
                    min-width: 320px !important;
                }
            }
            
            @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
                u ~ div .email-container {
                    min-width: 375px !important;
                }
            }
            
            @media only screen and (min-device-width: 414px) {
                u ~ div .email-container {
                    min-width: 414px !important;
                }
            }
            </style>
            <style>
            .primary {
                background: #f3a333;
            }
            
            .bg_white {
                background: #ffffff;
            }
            
            .bg_light {
                background: #fafafa;
            }
            
            .bg_black {
                background: #3f4d67;
            }
            
            .bg_dark {
                background: rgba(0, 0, 0, .8);
            }
            
            .email-section {
                padding: 2.5em;
                padding-bottom: 0px;
            }
            
            .btn {
                padding: 10px 15px;
            }
            
            .btn.btn-primary {
                border-radius: 30px;
                background: #f3a333;
                color: #ffffff;
            }
            
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: "Playfair Display", serif;
                color: #000000;
                margin-top: 0;
            }
            
            body {
                font-family: "Montserrat", sans-serif;
                font-weight: 400;
                font-size: 15px;
                line-height: 1.2;
                color: rgba(0, 0, 0, .4);
            }
            
            a {
                color: #f3a333;
            }
            
            .logo h1 {
                margin: 0;
            }
            
            .logo h1 a {
                color: #000;
                font-size: 20px;
                font-weight: 700;
                text-transform: uppercase;
                font-family: "Montserrat", sans-serif;
            }
            
            .heading-section {}
            
            .heading-section h2 {
                color: #000000;
                font-size: 28px;
                margin-top: 0;
                line-height: 1;
            }
            
            .heading-section .subheading {
                margin-bottom: 20px !important;
                display: inline-block;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 2px;
                color: rgba(0, 0, 0, .4);
                position: relative;
            }
            
            .heading-section .subheading::after {
                position: absolute;
                left: 0;
                right: 0;
                bottom: -10px;
                content: "";
                width: 100%;
                height: 2px;
                background: #f3a333;
                margin: 0 auto;
            }
            
            .heading-section-white {
                color: #000;
            }
            
            .heading-section-white p {
                color: rgba(0, 0, 0, .4);
                line-height: 24px;
            }
            
            .heading-section-white h2 {
                font-size: 28px;
                font-family: line-height: 1;
                padding-bottom: 0;
            }
            
            .heading-section-white h2 {
                color: #000000;
            }
            
            .heading-section-white .subheading {
                margin-bottom: 0;
                display: inline-block;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 2px;
                color: rgba(255, 255, 255, .4);
            }
            
            .icon {
                text-align: center;
            }
            
            .icon img {}
            
            .footer {
                color: rgba(255, 255, 255, .5);
            }
            
            .footer .heading {
                color: #ffffff;
                font-size: 20px;
            }
            
            .footer ul {
                margin: 0;
                padding: 0;
            }
            
            .footer ul li {
                list-style: none;
                margin-bottom: 10px;
            }
            
            .footer ul li a {
                color: rgba(255, 255, 255, 1);
            }
            
            @media screen and (max-width: 500px) {
                .icon {
                    text-align: left;
                }
                .text-services {
                    padding-left: 0;
                    padding-right: 20px;
                    text-align: left;
                }
            }
            
            .logo img {
                width: 300px;
                padding-top: 20px;
            }
            
            .bg_dark {
                background: #031a6e;
            }
            
            .footer {
                padding: 0px 30px;
            }
            </style>
        </head>
        
        <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">
            <center style="width: 100%; background-color: #f1f1f1;">
                <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;"> &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp; </div>
                <div style="max-width: 600px; margin: 0 auto;" class="email-container">
                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                        <tr>
                            <td class="bg_dark logo" style="padding:0em 2.5em; text-align:center;background: #EEEEEE;">
                                <h1><a href="#" style="text-center;"><img src="https://ct.digitalnoticeboard.biz//uploads/curewell_therapies_logo-removebg-preview.png" style="width:300px;"></a></h1> </td>
                        </tr>
                        <tr>
                            <td class="bg_white" style="background-color:#fff;padding: 20px !important">
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr style="padding:0;">
                                        <td class="email-section"  style="padding:0;">
                                            <div class="heading-section heading-section-white">
                                                <h2 style="text-align:center;">Forgot Password OTP</h2>
                                                <p>Hi Dear,</p>
                                                <br/>
                                                <p style="text-align: center;">Forgot your password?</p>
                                                <p style="text-align: center;"> We received a request to reset the password for your account.
                                                </p>
                                                <p style="text-align: center;">Here is your OTP:</p>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg_white email-section" style="background-color:#fff;padding:0;">
                                            <div class="heading-section" style="text-align: center;margin-bottom:10px">
                                                <h2>'.$content.'</h2>
                                            </div>
                                            <p style="margin-top:10px">For any assistance please choose any of the option below:</p>
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td valign="top" width="50%">
                                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                            <tr>
                                                                <td class="text-services">
                                                                    <h3>Contact Us at</h3>
                                                                    <p>Curewell Therapies Apps
                                                                    <br/>Noida sec-63 A-BLOCK</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td valign="top" width="50%">
                                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                            <tr>
                                                                <td class="text-services">
                                                                    <h3>Email Or Call Us</h3>
                                                                    <p>Phone: +1 264 497 5881 Fax: +1 264 497 5872 Email: info@doctor.com</p>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                        <tr>
                            <td valign="middle" class="bg_black footer" style=" background: #EEEEEE; padding:0px 20px;">
                                <table>
                                    <tr>
                                        <td valign="top" width="33.333%">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="text-align: left; padding-right: 10px;">
                                                        <p style="color:#000;">&copy; 2022 All Rights Reserved</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td valign="top" width="33.333%">
                                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                <tr>
                                                    <td style="text-align: right; padding-left: 5px; padding-right: 5px;"> </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </center>
        </body>
        </html>';
		$ci =& get_instance();
		$ci->load->library('phpmailer_lib');
        $mail = $ci->phpmailer_lib->load();
        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'doctorappmipl2@gmail.com';
        $mail->Password = 'ttzwqpmrnyptknsx';
        $mail->SMTPSecure = 'tls';
        $mail->Port     = "587";
        $mail->setFrom('doctorappmipl2@gmail.com', 'Curewell Therapies');
        $mail->addAddress($to);
        $mail->Subject  = $sub;
        $mail->isHTML(true);
        $mail->Body = $content;
        if(!$mail->send()){
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }else{
            return true;
        }
	   
    }
}
if (!function_exists('_register_email')) {
    function _register_email($to)
    {
        $sub = "Thank you for registering at Curewell Therapies";
       //echo  $to;  echo $content; echo $sub; die;
		$content = '<!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <meta name="x-apple-disable-message-reformatting">
          <title>Welcome to Curewell Therapies</title>
        
          <style>
            table, td, div, h1, p {
              font-family: Arial, sans-serif;
            }
            @media screen and (max-width: 530px) {
              .unsub {
                display: block;
                padding: 8px;
                margin-top: 14px;
                border-radius: 6px;
                background-color: #555555;
                text-decoration: none !important;
                font-weight: bold;
              }
              .col-lge {
                max-width: 100% !important;
              }
            }
            @media screen and (min-width: 531px) {
              .col-sml {
                max-width: 27% !important;
              }
              .col-lge {
                max-width: 73% !important;
              }
             
            }
            a{
                color:#fff !important;
            }
          </style>
        </head>
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#eeeeee;">
          <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#eeeeee;">
            <table role="presentation" style="width:100%;border:none;border-spacing:0;">
              <tr>
                <td align="center" style="padding:0;">
                  <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#000;">
                    <tr>
                      <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                        <a href="https://ct.digitalnoticeboard.biz/" style="text-decoration:none;"><img src="https://ct.digitalnoticeboard.biz//uploads/curewell_therapies_logo-removebg-preview.png" style="width:300px;"></a>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:30px;background-color:#ffffff;">
                        <h1 style="margin-bottom:30px;text-align:center;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Welcome to Curewell Therapies</h1>
                        <p>Hi Dear,</p>
                        <p style="text-align:center">Thank you for signing up at Curewell Therapies - Dr. Sudhir Bhola</p>
                        <img src="https://ct.digitalnoticeboard.biz//uploads/profilepic.png" alt="" style="width:200px;border-radius:50%;display: block;margin-left: auto;margin-right: auto;width: 50%;">
                        <p style="text-align:center">The top sexologist in Delhi for male sexual disorders, Dr. Sudhir Bhola has steered thousands of patients towards a healthier path for over 30 years. With extensive knowledge of Ayurveda, he has formulated some of the most potent medications for major sexual problems.</p style="text-align:center">
                        <p style="text-align:center">Use our mobile applications for Android and iOS to manage and book your appointment easily and hassle-free. Get approval first and then pay to confirm it. You can visit our website to download mobile app suitable to your device type.</p>
                        <p style="text-align:center">We appreciate your feedback and remarks to make it more useful.</p>
                        <p>Thanks again </p>
                        <p>For any assistance please choose any of the option below:</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="background-color:#0b58aa;padding:30px;color:#fff">
                      <div style="width:50%;float:left;">
                      <h3>Contact Us at</h3>
                                                                            <p>Curewell Therapies Apps
                                                                            <br/>L-22/9, DLF Phase 2, Gurugram, Haryana 122002</p>
                                                                            </div>
                      <div style="width:50%;float:left">
                      <h3>Email Or Call Us</h3>
                                                                            <p>Phone: +91 9999118111</p><p> <p style="color:#fff"> Email: contact@drsudhirbhola.com</p>
                                                                            </div>
                       
                      </td>
                    </tr>
                    
                  </table>
                  <!--[if mso]>
                  </td>
                  </tr>
                  </table>
                  <![endif]-->
                </td>
              </tr>
            </table>
          </div>
        </body>
        </html>';
		$ci =& get_instance();
		$ci->load->library('phpmailer_lib');
        $mail = $ci->phpmailer_lib->load();
        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'doctorappmipl2@gmail.com';
        $mail->Password = 'ttzwqpmrnyptknsx';
        $mail->SMTPSecure = 'tls';
        $mail->Port     = "587";
        $mail->setFrom('doctorappmipl2@gmail.com', 'Curewell Therapies');
        $mail->addAddress($to);
        $mail->Subject  = $sub;
        $mail->isHTML(true);
        $mail->Body = $content;
        if(!$mail->send()){
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }else{
            return true;
        }
	   
    }
}
if (!function_exists('_send_fcm_notification')) {
    function _send_fcm_notification($deviceToken,$deviceType,$message,$data_addedd) {
            $message = array
            (
                'title'   => $data_addedd['title'],
                'body'     => $data_addedd['msg'],
            );
            $json_data = [
                "to" => $deviceToken,
                "notification" => $message,
                "data" => $data_addedd
            ];
            
            $notification_data = json_encode($json_data);
            //FCM API end-point
            $url = 'https://fcm.googleapis.com/fcm/send';
            $server_key = 'AAAA3PrYRQU:APA91bHamDe8dNAWgZx8f5cfPRncNY_UCmJ8ixrXZ00X804O6f8DafNrRksCufx5zqu4GzGTt6En52ISkboSrU4aUIXRhC6W_YNcF9L1ZNwS7FUPRREFR2zQ_GuE_yKt7z2SsajL3AED';
            
            //header with content_type api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key=' . $server_key,
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $notification_data);
            $result = curl_exec($ch);
            if ($result === false) {
                die('Oops! FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
           return true;


    }    
}

