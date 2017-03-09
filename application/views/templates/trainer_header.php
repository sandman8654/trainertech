<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo PROJECT_NAME ?> | Trainer</title>

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>assets/theme/assets/bootstrap/css/bootstrap.css" rel="stylesheet">

        <!-- Font awesome -->
        <link href="<?php echo base_url(); ?>assets/theme/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!--fullcalendar-->
    <link href="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/theme/assets/fullcalendar/fullcalendar.print.css" rel="stylesheet" media="print" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/assets/bootstrap-datetimepicker/css/datetimepicker.css" />

    <!--morris chart-->
    <link href="<?php echo base_url() ?>assets/theme/assets/morris/morris.css" rel="stylesheet" />

    <!--flot chart-->
    <link href="<?php echo base_url() ?>assets/theme/css/examples.css" rel="stylesheet" type="text/css">


        <!--responsive menu-->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/theme/assets/resp-menu/demo.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/theme/assets/resp-menu/jquery.mmenu.css" />

        <!--page style-->
        <link href="<?php echo base_url(); ?>assets/theme/css/style.css" rel="stylesheet">

        <link href="<?php echo base_url(); ?>assets/theme/css/responsive.css" rel="stylesheet">
        <!-- JS Files -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!--jQuery UI lib-->
        <script src="<?php echo base_url(); ?>assets/theme/js/jquery-ui.custom.min.js"></script>

        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo base_url(); ?>assets/theme/assets/bootstrap/js/bootstrap.min.js"></script>

        <!--left nav-->
        <script class="include" type="text/javascript" src="<?php echo base_url(); ?>assets/theme/js/jquery.dcjqaccordion.2.7.js"></script>


 

        <!--responsive menu-->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/theme/assets/resp-menu/jquery.mmenu.js"></script>
        <script type="text/javascript">
          $(function() {
            $('nav#menu').mmenu({
              classes: 'mm-light'
            });
          });
        </script>
        <style type="text/css">
          a.btn-blue{
             margin: 0 1px 5px !important;
          }
        </style>
        <!-- JS Files -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

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

                <a href="#" class="logo"><img src="<?php echo base_url(); ?>assets/theme/img/logo.png"></a>
                <a href="#menu" class="menu"></a>

                <ul class="header-menu">
                    <li><a href="<?php echo base_url(); ?>trainer/changePassword"><i class="fa fa-cog"></i>Change Password</a></li>
                    <li><a href="<?php echo base_url(); ?>profile/trainer"><i class="fa fa-user"></i>Profile</a></li>
                    <li><a href="<?php echo base_url() ?>trainer/logout"><i class="fa fa-sign-out"></i>Sign Out</a></li>
                </ul>

            </header>

            <!-- leftbar -->
            <?php $this->load->view('trainer/sidebarTrainer'); ?>
            <!-- leftbar -->
            <section class="contant">