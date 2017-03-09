<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title> <?php echo PROJECT_NAME ?> | Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url() ?>assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url() ?>assets/bootstrap/css/dashboard.css" rel="stylesheet">

    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url() ?>admin"><?php echo PROJECT_NAME ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo base_url() ?>admin">Dashboard</a></li>
            <li><a href="<?php echo base_url(); ?>settings/changePassword">Settings</a></li>
            <li><a href="<?php echo base_url(); ?>profile/admin">Profile</a></li>
            <li><a href="<?php echo base_url() ?>admin/logout">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>