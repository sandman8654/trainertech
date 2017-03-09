<!DOCTYPE html>
<html>
	<head>

		<title>TURNKEY INVESTMENTS | <?php echo strtoupper($pagetitle) ?></title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Bootstrap -->
		<link href="<?php echo base_url() ?>assets/theme/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url() ?>assets/theme/assets/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		

		<!--responsive menu-->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/default.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/component.css" />
		<script src="<?php echo base_url() ?>assets/theme/js/modernizr.custom.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/jquery.bxslider.css" />

		<!--Font Awesome-->
		<link href="<?php echo base_url() ?>assets/theme/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

		<!--Slider-->
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

		<!--page style-->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/theme/css/responsive.css"/>        
		

		 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery.js"></script>        
	</head>

	<body style="background-color:#FFF" <?php if(isset($bodyclass)) echo "class='$bodyclass'" ?>>
		<div id='srctoggle' style="height:200px; margin-top:2%; width:100%; background-color:#E0B705; position:absolute; z-index:9;">
			<?php echo form_open(base_url().'search', array('id'=>'header_search_form')); ?>
				<input type="text" name="s" value="<?php echo $this->input->post('s') ?>" style="margin-left:30%; margin-top:5%; height:50px; width:50%; ">
				<a href="#" id="src">
					<img style="margin-left:1%" src="<?php echo base_url() ?>assets/theme/img/search.png">
				</a>
			</form>
		</div>
		<header id="header">
			<div class="container">
				<div id="dl-menu" class="dl-menuwrapper">
					<button class="dl-trigger">Open Menu</button>
					<ul class="dl-menu">
						<li><a href="<?php echo base_url() ?>about">ABOUT</a></li>
						<li><a href="<?php echo base_url() ?>services">SERVICES</a></li>
						<li><a href="<?php echo base_url() ?>how_turnkey_works">HOW TURNKEY WORKS</a></li>
						<li><a href="<?php echo base_url() ?>listings">LISTINGS</a></li>
						<li><a href="<?php echo base_url() ?>invest">INVEST</a></li>
						<li><a href="<?php echo base_url() ?>news">NEWS</a></li>
						<li><a href="<?php echo base_url() ?>contactus">CONTACT US</a></li>
					</ul>
				</div>
				<a href="<?php echo base_url() ?>" class="logo"><img src="<?php echo base_url() ?>assets/theme/img/logo.png"></a>
				<div id="search">
					<form class="pull-right">                        
						<label id="open-search" class="icon-search" ></label>                       
					</form>                    
				</div>                
				<ul id="main-nav">
					<li <?php if($menuactive == 'about') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>about">ABOUT</a></li>
					<li <?php if($menuactive == 'services') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>services">SERVICES</a></li>
					<li <?php if($menuactive == 'how_turnkey_works') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>how_turnkey_works">HOW TURNKEY WORKS</a></li>
					
					<li <?php if($menuactive == 'listings') echo "class='current'"; ?> >
						<a id="listing" href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle" >LISTINGS</a>
						<ul id="listing_option" class="dropdown-menu" role="menu">
							<li><a href="<?php echo base_url() ?>listings/active">Active Listings</a></li>
							<li><a href="<?php echo base_url() ?>listings/rented">Rented Listings</a></li>
						</ul>
					</li>
					
					<li <?php if($menuactive == 'invest') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>invest">INVEST</a></li>
					<li <?php if($menuactive == 'news') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>news">NEWS</a></li>
					<li <?php if($menuactive == 'contactus') echo "class='current'"; ?> ><a href="<?php echo base_url() ?>contactus">CONTACT US</a></li>
				</ul>
			</div>
		</header>
		<style type="text/css">
		   /* .page-description{
				background-color: #fff;
			}*/

			#srctoggle{
				display: none;
			}

			#listing_option{
				float: left;
				background-color: #1A1A1A;
				margin-left: 53.4%;
				padding: 0px;
				margin-top: 0px;
				border-radius: 0px;
			}

			.dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus{
				background: transparent;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#listing").click(function(){
					$("#listing_option").toggle();
				});
				$("#src").click(function(event){
					event.preventDefault();
					$("#header_search_form").submit();
				});
			});
		</script>