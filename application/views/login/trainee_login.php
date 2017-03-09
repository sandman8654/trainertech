<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Mca ignou major project">
    <meta name="author" content="Prince">
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/images/favicon.png">

    <title> <?php echo PROJECT_NAME ?> | Trainee | Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url() ?>assets/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url() ?>assets/bootstrap/css/signin.css" rel="stylesheet">
    
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      .ci_alert{
        position: fixed !important;
        top: 0px !important;
        left: 0px !important;
        width: 100% !important;
        text-align: center !important;
      }
    </style>
  </head>

  <body>

    <div class="container">

      <?php alert(); ?>

      <?php echo form_open(cms_current_url(), array('class'=>"form-signin", 'role'=>"form")); ?>
        <h2 class="form-signin-heading">Please Login</h2>
        <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        <br><input type="password" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
        <br><a href="<?php echo base_url() ?>forget_password/trainee" style="float: right;">Forget Password ?</a>
      </form>

    </div>
  </body>
</html>
