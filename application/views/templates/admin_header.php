<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo PROJECT_NAME ?> | Admin</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url() ?>assets/theme/assets/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Font awesome -->
    <link href="<?php echo base_url() ?>assets/theme/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!--fullcalendar-->
    <link href="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.print.css" rel="stylesheet" media="print" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/assets/bootstrap-datetimepicker/css/datetimepicker.css" />

    <!--morris chart-->
    <link href="<?php echo base_url() ?>assets/theme/assets/morris/morris.css" rel="stylesheet" />

    <!--flot chart-->
    <link href="<?php echo base_url() ?>assets/theme/css/examples.css" rel="stylesheet" type="text/css">

    <!--responsive menu-->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/theme/assets/resp-menu/demo.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/theme/assets/resp-menu/jquery.mmenu.css" />

    <!--page style-->
    <link href="<?php echo base_url() ?>assets/theme/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/theme/css/responsive.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!--jQuery UI lib-->
    <script src="<?php echo base_url() ?>assets/theme/js/jquery-ui.custom.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url() ?>assets/theme/assets/bootstrap/js/bootstrap.min.js"></script>

    <!--fullcalendar-->
    <script src="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.js"></script>

    <!--morris chart-->
    <script src="<?php echo base_url() ?>assets/theme/js/jquery.sparkline.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/theme/assets/morris/raphael-min.js" type="text/javascript"></script>

    <!--flot chart-->
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.flot.categories.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/dash-flot-set.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/theme/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

    <!--left nav-->
    <script class="include" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/jquery.dcjqaccordion.2.7.js"></script>

    <!--responsive menu-->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/theme/assets/resp-menu/jquery.mmenu.js"></script>
    <script type="text/javascript">
      $(function() {
        $('nav#menu').mmenu({
          classes: 'mm-light'
        });
      });
    </script>

    <!--page script-->
    <script class="include" type="text/javascript" src="<?php echo base_url() ?>assets/theme/js/script.js"></script>
    <script src="<?php echo base_url() ?>assets/theme/js/morris-script.js"></script>
    <style type="text/css">
      @media(width:320px){
        #page section.contant{
          min-height:335px;
        }
      }
      @media(width:360px){
        #page section.contant{
          min-height:495px;
        }
      }
      @media(width:768px){
        #page section.contant{
          min-height:859px;
        }
      }
      @media(width:800px){
        #page section.contant{
          min-height:1115px;
        }
      }
      @media(width:980px){
        #page section.contant{
          min-height:998px;
        }
      }
      @media(width:1280px){
        #page section.contant{
          min-height:318px;
        }
      }
      @media(width:1366px){
        #page section.contant{
          min-height:349px;
        }
      }
      @media(width:1920px){
        #page section.contant{
          min-height:618px;
        }
      }
    </style>
  </head>
  <body>

    <div id="page">

      <header id="header">

        <a href="#" class="logo"><img src="<?php echo base_url() ?>assets/theme/img/logo.png"></a>
        <a href="#menu" class="menu"></a>

        <ul class="header-menu">
          <li><a href="<?php echo base_url(); ?>settings/changePassword"><i class="fa fa-cog"></i>Settings</a></li>
          <li><a href="<?php echo base_url(); ?>profile/admin"><i class="fa fa-user"></i>Profile</a></li>
          <li><a href="<?php echo base_url() ?>admin/logout"><i class="fa fa-power-off"></i>Sign Out</a></li>
        </ul>

      </header>

      <aside>

        <div class="left-nav">
          <ul>
            <!-- <li><a href="<?php echo base_url() ?>exercise/all"><i class="fa fa-plus"></i>Manage Exercise</a></li> -->
            <li><a href="<?php echo base_url() ?>gallery/all"><i class="fa fa-image"></i>Image Gallery</a></li>
            <li><a href="<?php echo base_url() ?>plans/all"><i class="fa fa-tasks"></i>Manage Plans</a></li>
            <li><a href="<?php echo base_url() ?>manager/all"><i class="fa fa-user"></i>Manage Managers</a></li>
            <li><a href="<?php echo base_url() ?>trainer/all"><i class="fa fa-user"></i>Manage Trainers</a></li>
            <li><a href="<?php echo base_url() ?>trainee/all"><i class="fa fa-user"></i>Manage Trainee</a></li>
            <li><a href="<?php echo base_url() ?>admin/support"><i class="fa fa-support"></i>Support</a></li>
            <li><a href="<?php echo base_url() ?>admin/social_links"><i class="fa fa-list"></i> Social Links</a></li>
            <li><a style="display:none;"  href="<?php echo base_url() ?>app_slider/all"><i class="fa fa-image"></i> App Slider</a></li>
            <li><a href="<?php echo base_url() ?>page/all"><i class="fa fa-list"></i>Pages</a></li>
            <?php /* ?>
            <li class="multi-item">
              <a href="javascript:;" class="dcjq-parent"><i class="fa fa-user"></i>Add Trainee</a>
              <ul class="sub">
                <li class="multi-item">
                  <a href="javascript:;" class="dcjq-parent">Mustangs</a>
                  <ul class="sub">
                    <li><a href="#">- Quarterbacks</a></li>
                    <li><a href="#">- Runningbacks</a></li>
                    <li><a href="#">- O Linemen</a></li>
                    <li><a href="#">- Wide Recievers</a></li>
                    <li><a href="#">- Tight Ends</a></li>
                    <li><a href="#">- D Linemen</a></li>
                    <li><a href="#">- Cornerbacks</a></li>
                    <li><a href="#">- Safteys</a></li>
                  </ul>
                </li>
                <li><a href="#">Private Client</a></li>
                <li><a href="#">Add Group</a></li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-users"></i>Groups</a></li>
            <li><a href="#"><i class="fa fa-calendar"></i>Calendar</a></li>
            */ ?>
          </ul>
        </div>

      </aside>

      