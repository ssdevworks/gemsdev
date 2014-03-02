<?php

include_once "config.php";

if($_REQUEST['access']=='Login'){
    
    $userObj = new User($_REQUEST);
    $checkErr= $userObj->loginValidateUser($_REQUEST);
    if($checkErr==''){
        $userDet= $userObj->getUserDetails($_REQUEST);
        
        if(count($userDet)>0){
            $_SESSION['userId']=$userDet['userid'];
            $_SESSION['email'] =$userDet['email'];
            header("Location: task_manager.php");
        }
    }
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Login - The Gems Solutions</title>
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<!--[if !IE]><link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.css"><![endif]-->
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/gems.css" />
<!--[if IE 9 ]><html class="ie9"> <![endif]-->
<script src="js/bootstrap.min.js"></script>
<!--<script>
alert (screen.width);
</script>-->
</head>
<body class="login">
<div class="login-container">
	<div class="container">
		<div class="span8">
			<ul class="topmenu-container">
				<li><a href="" class="home"><span></span><p>Home</p></a></li>
				<li><a href="" class="solutions"><span></span><p>Solutions</p></a></li>
				<li><a href="" class="benefits"><span></span><p>Benefits</p></a></li>
				<li><a href="" class="demo"><span></span><p>Demo</p></a></li>
				<li><a href="" class="faq"><span></span><p>FAQs</p></a></li>
				<li><a href="" class="contacts"><span></span><p>Contacts</p></a></li>
			</ul>
			<ul class="dashboard-container">
				<li><img src="images/dash-img1.png" /></li>
				<li><img src="images/dash-img2.png" /></li>
				<li><img src="images/dash-img3.png" /></li>
				<li><img src="images/dash-img4.png" /></li>
				<li><img src="images/dash-img5.png" /></li>
				<li><img src="images/dash-img6.png" /></li>
				<li><img src="images/dash-img7.png" /></li>
				<li><img src="images/dash-img8.png" /></li>
			</ul>
		</div>
		<div class="span4">
			<div class="cred-container">
				<img class="cred-logo" src="images/login-logo.png" />
				<form name="loginForm" action="login.php" method="post">
                    <?php
                        if($checkErr!=''){
                            ?>
                                <label class="err"><?php echo $checkErr; ?></label>
                            <?php
                        }
                    ?>
					<label>
						User Id
						<input type="text" name="userid" id="userid" value="<?php if($_REQUEST['userid']!=''){ echo $_REQUEST['userid']; } ?>" />
					</label>
					<label>
						Password
						<input type="password"  name="password" id="password" value="" />
					</label>
					<label class="rem">
						<input type="checkbox" id="remember" name="remember" value="1" />
						Remember me
					</label>
					<a class="forget" href="#">Forgot Password?</a>
					<input type="submit" name="access" value="Login" class="login-btn" />
				</form>
			</div>
		</div>
	</div>
</div>
<div class="login-footer">
	<div class="container">
		<div class="span8"><a href="">About Argus Intelligence</a> | <a href="">Licensing</a> | <a href="">Privacy Policy</a> | <a href="">Terms &amp; Conditions</a><br />
		<span>Copyright &copy; 2009 Argus Intelligence, LLC. All rights reserved.</span>
		</div>
		<div class="span4">
			<img src="images/argus-logo.png" />
		</div>
	</div>
</div>
<div class="loginbg-container">
	<img src="images/login-wrapper02.jpg" />
</div>
</body>
</html>
