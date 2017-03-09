<!doctype html>
<html>
<head>
	<title> Forget Password </title>
</head>
<body>
<h3>Hello, <?php echo $name ?> </h3>
<!-- <h3>New Password : <?php echo $new_password ?></h3> -->
<h3>
	<a href="<?php echo base_url().'forget_password/password_reset/'.$key ?>"> Click here to reset your password </a>
</h3>
</body>
</html>