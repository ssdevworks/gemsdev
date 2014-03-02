<?php

include_once "config.php";
include_once "Taskmanager.class.php";
//ini_set('display_errors','on'); error_reporting(E_ALL);
$userObj     = new User();

if($_REQUEST['user_update']=='yes')
{
    $checkErr = $userObj->validateUserDetails();
    if($checkErr=='')
    {
        $insTask= $userObj->updateUserDetails();        
    }
}
$userDetails = $userObj->getUserDetails($taskId);

$ipFields['firstname']      	= $_REQUEST['firstname']!='' ? $_REQUEST['firstname'] : $taskDetails['firstname'];
$ipFields['lastname']        	= $_REQUEST['lastname']!='' ? $_REQUEST['lastname'] : $taskDetails['lastname'];
$ipFields['member_id']    		= $_REQUEST['member_id']!='' ? $_REQUEST['member_id'] : $taskDetails['member_id'];
$ipFields['suffix']      		= $_REQUEST['suffix']!='' ? $_REQUEST['suffix'] : $taskDetails['suffix'];
$ipFields['nickname']        	= $_REQUEST['nickname']!='' ? $_REQUEST['nickname'] : $taskDetails['nickname'];
$ipFields['gender']   			= $_REQUEST['gender']!='' ? $_REQUEST['gender'] : $taskDetails['gender'];
$ipFields['dob']       			= $_REQUEST['dob']!='' ? $_REQUEST['dob'] : $taskDetails['dob'];
$ipFields['martial_status'] 	= $_REQUEST['martial_status']!='' ? $_REQUEST['martial_status'] : $taskDetails['martial_status'];
$ipFields['anniversary']  		= $_REQUEST['anniversary']!='' ? $_REQUEST['anniversary'] : $taskDetails['anniversary'];
$ipFields['membership_date']    = $_REQUEST['membership_date']!='' ? $_REQUEST['membership_date'] : $taskDetails['membership_date'];
$ipFields['type']   			= $_REQUEST['type']!='' ? $_REQUEST['type'] : $taskDetails['type'];
$ipFields['status']  			= $_REQUEST['status']!='' ? $_REQUEST['status'] : $taskDetails['status'];
$ipFields['ride_walk']  		= $_REQUEST['ride_walk']!='' ? $_REQUEST['ride_walk'] : $taskDetails['ride_walk'];
$ipFields['handicap']  			= $_REQUEST['handicap']!='' ? $_REQUEST['handicap'] : $taskDetails['handicap'];
$ipFields['last_lession']  		= $_REQUEST['last_lession']!='' ? $_REQUEST['last_lession'] : $taskDetails['last_lession'];
$ipFields['title']  			= $_REQUEST['title']!='' ? $_REQUEST['title'] : $taskDetails['title'];
$ipFields['undergrad']  		= $_REQUEST['undergrad']!='' ? $_REQUEST['undergrad'] : $taskDetails['undergrad'];
$ipFields['occupation']  		= $_REQUEST['occupation']!='' ? $_REQUEST['occupation'] : $taskDetails['occupation'];
$ipFields['note']  				= $_REQUEST['note']!='' ? $_REQUEST['note'] : $taskDetails['note'];

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
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="css/bootstrap-ie7fix.css"><![endif]-->
<!--[if IE 7 ]><html class="ie7"> <![endif]-->
<!--[if IE 8 ]><html class="ie8"> <![endif]-->
<!--[if IE 9 ]><html class="ie9"> <![endif]-->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
<![endif]-->
<script src="js/bootstrap.min.js"></script>
</head>
<body class="login">
<div class="headr-container">
	<div class="container">
		<div class="span3">
			<a href=""><img src="images/gems-logo.png" /></a>
		</div>
		<!-- <button type="button" class="navbar-inner" data-toggle="collapse" data-target="navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button> -->
		<div class="span9">
			<div class="usr-profile">
				<div class="ph-block">
					<img src="images/usr-profile-img.png" />
				</div>
				<div class="rgt-block">
					<p>Matthew</p>
					<span>The Hasentree Club</span>
					<a href="">Logout</a>
				</div>
			</div>
			<ul>
				<li><a href="">Global</a></li>
				<li><a href="">Main</a></li>
				<li><a href="">Calendar</a></li>
				<li class="active"><a href="">Members</a></li>
				<li><a href="">Config</a></li>
				<li><a href="">Help</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="mem-container">
	<div class="container">
		<div class="lft-navi-block">
			<ul>
				<li><a href="" class="home"><span></span><p>Home</p></a></li>
				<li><a href="" class="solutions"><span></span><p>Solutions</p></a></li>
				<li><a href="" class="benefits"><span></span><p>Benefits</p></a></li>
				<li><a href="" class="demo"><span></span><p>Demo</p></a></li>
				<li><a href="" class="faq"><span></span><p>FAQs</p></a></li>
				<li><a href="" class="contacts"><span></span><p>Contacts</p></a></li>
			</ul>
		</div>
		<div class="content-container">
			<div class="profile-top-container">
				<div class="span6"><div class="prof-name">Member Profile <span>Adams, Kristen  : 1019B</span></div></div>
				<div class="span6">
					<input type="button" class="grey-btn" value="Report" />
					<input type="button" class="grey-btn" value="Delete" />
					<input type="button" class="grey-btn active" value="Update" />
				</div>
			</div>
			<div class="clr"></div>
			<div class="tbl-container">
				<table cellpadding="0" cellspacing="1" border="0">
					<tr>
						<th>Category</th>
						<th>Overall</th>
						<th>Profile</th>
						<th>Locker</th>
						<th>Bag</th>
						<th>Clubs</th>
					</tr>
					<tr>
						<td>Preference Completeness</td>
						<td>70%</td>
						<td>87%</td>
						<td>0%</td>
						<td>93%</td>
						<td>100%</td>
					</tr>
				</table>
			</div>
			<form action="member_profile.php" method="post" name="memberForm" >
				
				<div class="frm-container">
					<div class="frm-blk1">
						<div class="frm-field">
							<label>Member #</label>
							<input type="text" value="1019B" name="member_id"  id="member_id"  value="<?php if($ipFields['member_id']!=''){ echo $ipFields['member_id']; } ?>" />
						</div>
						<div class="frm-field">
							<label>Salutation</label>
							<input type="text" value="Select" class="drpdwn" name="salutation"  id="salutation"   value="<?php if($ipFields['salutation']!=''){ echo $ipFields['salutation']; } ?>" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>First</label>
							<input type="text" name="firstname" id="firstname" value="Kristen"  value="<?php if($ipFields['firstname']!=''){ echo $ipFields['firstname']; } ?>"  />
						</div>
						<div class="frm-field">
							<label>Last</label>
							<input type="text"  name="lastname" id="lastname"  value="Adams"  value="<?php if($ipFields['lastname']!=''){ echo $ipFields['lastname']; } ?>"  />
						</div>
						<div class="frm-field">
							<label>Suffix</label>
							<input type="text" value="Select" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Nickname</label>
							<input type="text" name="nickname" id="nickname"  value="<?php if($ipFields['nickname']!=''){ echo $ipFields['nickname']; } ?>"  />
						</div>
						<div class="frm-field">
							<label>Gender</label>
							<input type="text" value="Male" class="drpdwn" name="gender"  id="gender" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Birthday</label>
							<input type="text" value="09-07-1986" name="dob"  id="dob"  value="<?php if($ipFields['dob']!=''){ echo $ipFields['dob']; } ?>"  />
							<a href="" class="calc" src="images/calc.png"></a>
						</div>
						<div class="frm-field">
							<label>Marital Status</label>
							<input type="text" value="Select" class="drpdwn" name="martial_status"  id="martial_status" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Anniversary</label>
							<input type="text" name="anniversary"  id="anniversary"  value="<?php if($ipFields['anniversary']!=''){ echo $ipFields['anniversary']; } ?>"  />
							<a href="" class="calc" src="images/calc.png"></a>
						</div>
					</div>
					<div class="frm-blk2">
						<div class="frm-field">
							<label>Membership Date</label>
							<input type="text" value="07-27-2006" name="membership_date"  id="membership_date"  value="<?php if($ipFields['membership_date']!=''){ echo $ipFields['membership_date']; } ?>"  />
							<a href="" class="calc" src="images/calc.png"></a>
						</div>
						<div class="frm-field">
							<label>Type</label>
							<input type="text" value="Member" name="type"  id="type" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Status</label>
							<input type="text" value="Active" name="status"  id="status" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Ride/Walk</label>
							<input type="text" value="Select" name="ride_walk"  name="ride_walk" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Handicap</label>
							<input type="text" value="Select"  name="handicap"  name="handicap" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Last Lesson</label>
							<input type="text"  name="last_lession"  name="last_lession"   value="<?php if($ipFields['last_lession']!=''){ echo $ipFields['last_lession']; } ?>"   />
							<a href="" class="calc" src="images/calc.png"></a>
						</div>
						<div class="frm-field">
							<label>Title</label>
							<input type="text"  name="title"  name="title"  value="<?php if($ipFields['title']!=''){ echo $ipFields['title']; } ?>"    />
						</div>
						<div class="frm-field">
							<label>Undergrad</label>
							<input type="text" name="undergrad" id="undergrad" value="Alverno College" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Occupation</label>
							<input type="text" value="Select" name="occupation"  id="occupation" class="drpdwn" />
							<span class="dwnarw"></span>
						</div>
						<div class="frm-field">
							<label>Note</label>
							<textarea name="note" id="note" >  <?php if($ipFields['note']!=''){ echo $ipFields['note']; } ?> </textarea>
						</div>
					</div>
					<div class="frm-blk3">
						<div class="img-block"><img src="" /></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="main-footer">
	<div class="container">
		<a href="">About Argus Intelligence</a> | <a href="">Licensing</a> | <a href="">Privacy Policy</a> | <a href="">Terms &amp; Conditions</a>Copyright &copy; 2009 Argus Intelligence, LLC. All rights reserved.
		<img src="images/argus-logo2.png" />
	</div>
</div>
<div class="loginbg-container">
	<img src="images/login-wrapper.png" />
</div>
</body>
</html>
