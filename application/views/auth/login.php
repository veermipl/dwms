<!DOCTYPE html>
<html lang="en">

<head>
    <base href="<?php echo base_url(); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Rizvi">
    <meta name="keyword" content="Php, Hospital, Clinic, Management, Software, Php, CodeIgniter, Hms, Accounting">
    <link rel="shortcut icon" href="uploads/favicon.png">

    <title>Login - <?php echo $this->db->get('settings')->row()->system_vendor; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="common/css/bootstrap.min.css" rel="stylesheet">
    <link href="common/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="common/assets/fontawesome5pro/css/all.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="common/css/style.css" rel="stylesheet">
    <link href="common/css/style-responsive.css" rel="stylesheet" />
    <link href="common/extranal/css/auth.css" rel="stylesheet">

</head>

<body class="login-body">

    <div class="container">
        <form class="form-signin" method="post" action="auth/login">
            <h2 class="login form-signin-heading">
                <!-- <?php echo $this->db->get('settings')->row()->title; ?><br /><br /> -->
                <img alt="" src="uploads/login.png">
                <!-- <img src="<?php echo $this->db->get('settings')->row()->logo; ?>"> -->
            </h2>
            <div id="infoMessage"><?php echo $message; ?></div>
            <div class="login-wrap">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control c_tys" name="identity" placeholder="Enter Email" autofocus>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="left-a">Password <span class="text-danger">*</span></label>
                        <lable class="right-a">
                            <p><a data-toggle="modal" href="#myModal"> Forgot your password?</a></p>
                        </lable>
                        <input type="password" class="form-control c_tys" name="password" placeholder="Enter Password">
                    </div>
                </div>





                <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
            </div>
        </form>
    </div>









    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="auth/forgot_password">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Forgot Password ?</h4>
                    </div>

                    <div class="modal-body">
                        <p>Enter your e-mail address below to reset your password.</p>
                        <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        <input class="btn detailsbutton" type="submit" name="submit" value="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="common/js/jquery.js"></script>
    <script src="common/js/bootstrap.min.js"></script>


</body>

</html>