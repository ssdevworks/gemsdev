<?php

include_once "config.php";
include_once "Taskmanager.class.php";

if($_SESSION['userId']==''){
    header("Location: login.php");
    exit;
}

//ini_set('display_errors','on'); error_reporting(E_ALL);
$taskObj     = new Taskmanager($_REQUEST);
$taskId      = $_REQUEST['taskId'] > 0 ? $_REQUEST['taskId'] : 0;
$taskDetails = $taskId > 0 ? $taskObj->getTaskDetails($taskId) : array();
if($_REQUEST['task_add']=='done')
{
    
   
    $checkErr = $taskObj->ValidateTask();
    if($checkErr=='')
    {
        $insTask= $taskObj->saveTaskDetails();
        
        header("Location: task_manager.php");
        exit();
    }
}

$ipFields['tasker']        = $_REQUEST['tasker']!='' ? $_REQUEST['tasker'] : $taskDetails['tasker'];
$ipFields['start_date']    = $_REQUEST['start_date']!='' ? $_REQUEST['start_date'] : $taskDetails['start_date'];
$ipFields['due_date']      = $_REQUEST['due_date']!='' ? $_REQUEST['due_date'] : $taskDetails['due_date'];
$ipFields['taskee']        = $_REQUEST['taskee']!='' ? $_REQUEST['taskee'] : $taskDetails['taskee'];
$ipFields['task_status']   = $_REQUEST['task_status']!='' ? $_REQUEST['task_status'] : $taskDetails['task_status'];
$ipFields['private']       = $_REQUEST['private']!='' ? $_REQUEST['private'] : $taskDetails['private'];
$ipFields['complete_date'] = $_REQUEST['complete_date']!='' ? $_REQUEST['complete_date'] : $taskDetails['complete_date'];
$ipFields['current_view']  = $_REQUEST['current_view']!='' ? $_REQUEST['current_view'] : $taskDetails['current_view'];
$ipFields['get_report']    = $_REQUEST['get_report']!='' ? $_REQUEST['get_report'] : $taskDetails['get_report'];
$ipFields['task_detail']   = $_REQUEST['task_detail']!='' ? $_REQUEST['task_detail'] : $taskDetails['task_detail'];
$ipFields['tasker_notes']  = $_REQUEST['tasker_notes']!='' ? $_REQUEST['tasker_notes'] : $taskDetails['tasker_notes'];
$ipFields['taskee_notes']  = $_REQUEST['taskee_notes']!='' ? $_REQUEST['taskee_notes'] : $taskDetails['taskee_notes'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8" />
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Add New - Task Manager - The Gems Solutions</title>
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/gems.css" />
<script src="js/jquery-1.10.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="jqueryui/css/south-street/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="jqueryui/js/jquery-1.10.2.js"></script>
	<script src="jqueryui/js/jquery-ui-1.10.4.custom.js"></script>
<link type="text/css" rel="stylesheet" href="customdropdown/jquery.selectBoxIt.css" />
<script src="customdropdown/jquery.selectBoxIt.min.js"></script>
 <script>
  $(function() {
$( "#due_date" ).datepicker();
$("#due_date_cal").click(function() {
  $("#due_date").datepicker("show");
});

$( "#start_date" ).datepicker();
$("#start_date_cal").click(function() {
  $("#start_date").datepicker("show");
});

$( "#complete_date" ).datepicker();
$("#complete_date_cal").click(function() {
  $("#complete_date").datepicker("show");
});
 $(".customSelect").selectBoxIt();
});


 </script>
 <style>
 .selectboxit-container .selectboxit-options {

    /* Set's the drop down options width to the same width as the drop down button */
    width: 175px;

  }
 </style>
</head>
<body>
<div class="headr-container">
	<div class="container">
		<div class="span3">
			<a href=""><img src="images/gems-logo.png" /></a>
		</div>
		<div class="span9">
			<div class="usr-profile">
				<div class="ph-block">
					<img src="images/usr-profile-img.png" />
				</div>
				<div class="rgt-block">
					<p>Matthew</p>
					<span>The Hasentree Club</span>
					<a href="logout.php">Logout</a>
				</div>
			</div>
			<ul>
				<li><a href="">Global</a></li>
				<li class="active"><a href="">Main</a></li>
				<li><a href="">Calendar</a></li>
				<li><a href="">Members</a></li>
				<li><a href="">Config</a></li>
				<li><a href="">Help</a></li>
			</ul>
		</div>
	</div>
	</div>
</div>
<div class="mem-container navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-inner">
			<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="lft-navi-block">
				<ul class="nav nav-collapse collapse">
					<li><a href="" class="dash"><span></span><p>Dashboard</p></a></li>
					<li><a href="" class="tee"><span></span><p>Tee and Play</p></a></li>
					<li><a href="" class="evnt"><span></span><p>Events</p></a></li>
					<li><a href="" class="ordr"><span></span><p>Order Tracker</p></a></li>
					<li><a href="" class="demo"><span></span><p>Demo Tracker</p></a></li>
					<li><a href="" class="taskmanagr active"><span></span><p>Task Manager</p></a></li>
					<li><a href="" class="exp"><span></span><p>Experience</p></a></li>
					<li><a href="" class="qury"><span></span><p>Member Query</p></a></li>
					<li><a href="" class="rpts"><span></span><p>Reports</p></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="content-container">
			<div class="profile-top-container">
				<div class="span6"><div class="prof-name"><a href="task_manager.php"  class="back-arw">Back</a><span>Task Manager</span></div></div>
				<div class="span6">
					<input type="button" class="grey-btn" value="Email" />
				</div>
			</div>
			<div class="clr"></div>
			<?php
                if($checkErr!=''){
                                
            ?>                    
                <div class="succ-msg">
                    &nbsp;&nbsp; 
                    <span><?php echo $checkErr;?></span>
                </div>
            <?php
                 }
             ?>  
             <div class="clr"></div>
			<form method="post" action="managetask.php" name="taskform">
				<input type="hidden" name="taskId" value="<?php echo $taskId;?>" />
				<div class="frm-container">
					<div class="addnew-blk1">
						<div class="frm-field">
							<label>Tasker</label>
							<input type="text" name="tasker" id="tasker" value="<?php if($ipFields['tasker']!=''){ echo $ipFields['tasker']; } ?>"  />
						</div>
						<div class="frm-field">
							<label>Start Date</label>
							<input type="text" name="start_date" id="start_date" value="<?php if($ipFields['start_date']!=''){ echo $ipFields['start_date']; } ?>"  />
							<a href="javascript:void(0)" id="start_date_cal" class="calc" src="images/calc.png"></a>
						</div>
						<div class="frm-field">
							<label>Taskee</label>
					
		   <select class="customSelect" name="taskee" id="taskee">
			<option value="0" <?php if ($ipFields['taskee']==''){?>selected="selected" <?php } ?>> Select</option>
			<?php foreach($TMPTASKER as $tskid => $tskVal){?>
			<option value="<?php echo $tskid;?>" <?php if ($ipFields['taskee']== $tskid){?>selected="selected" <?php } ?>><?php echo $tskVal;?></option>		
			<?php }?>
			</select>
			
						</div>
						<div class="frm-field">
							<label>Due Date</label>
							<input type="text" name="due_date" id="due_date" value="<?php if($ipFields['due_date']!=''){ echo $ipFields['due_date']; } ?>"  />
							<a href="javascript:void(0)" class="calc "  id="due_date_cal" src="images/calc.png"></a>
						</div>
						<div class="frm-field">
							<label>Task Status</label>
							<select name="task_status" id="task_status" class="customSelect">
							<option value="0" <?php if ($ipFields['task_status']==''){?>selected="selected" <?php } ?>>Select</option>
							<option value="1" <?php if ($ipFields['task_status']=='1'){?>selected="selected" <?php } ?> >Initiated</option>
							<option value="2"   <?php if ($ipFields['task_status']=='2'){?>selected="selected" <?php } ?> >In-Process</option>
							<option value="3"   <?php if ($ipFields['task_status']=='3'){?>selected="selected" <?php } ?> >Complete</option>
							<option value="4"  <?php if ($ipFields['task_status']=='4'){?>selected="selected" <?php } ?> >Cancelled</option>
							</select>
						</div>
						<div class="frm-field">
							<label>Private</label>
							<label class="chk"><input type="checkbox" name="private"  id="private" <?php if ($ipFields['private']=='1'){?>checked="checked" <?php } ?> value="1" />Mark to prevent task from showing on report</label>
						</div>
						<div class="frm-field">
							<label>Complete Date</label>
							<input type="text" name="complete_date" id="complete_date" value="<?php if($ipFields['complete_date']!=''){ echo $ipFields['complete_date']; } ?>"  />
							<a href="javascript:void(0)" class="calc" id="complete_date_cal" src="images/calc.png"></a>
						</div>
					
					</div>
					<div class="addnew-blk2">
						<div class="frm-field">
							<label>Task</label>
							<textarea name="task_detail" id="task_detail"><?php if($ipFields['task_detail']!=''){ echo $ipFields['task_detail']; } ?></textarea>
						</div>
						<div class="frm-field">
							<label>Tasker Notes</label>
							<textarea name="tasker_notes" id="tasker_notes"><?php if($ipFields['tasker_notes']!=''){ echo $ipFields['tasker_notes']; } ?></textarea>
						</div>
						<div class="frm-field">
							<label>Taskee Notes</label>
							<textarea name="taskee_notes" id="taskee_notes"><?php if($ipFields['taskee_notes']!=''){ echo $ipFields['taskee_notes']; } ?></textarea>
						</div>
					</div>
				</div>
				<input type="hidden"  name="task_add" value="done"/>
				<div class="btns-block">
					<!-- <input type="button" class="grey-btn" value="Delete Task" /> -->
					<?php if($taskId  >0) {?>
					<!--<input type="submit" class="grey-btn" name="update_access" value="Update Task" />-->
					 <a href="javascript:void(0);"   class="grey-btn"  onclick="document.taskform.submit();" >Update Task</a> 
					<?php } else{?>
						<!--<input type="submit" class="grey-btn" name="edit_access" value="Add Task" />-->
						 <a href="javascript:void(0);"   class="grey-btn"  onclick="document.taskform.submit();" >Add Task</a> 
					<?php }?>
					
				</div>
			</form>
		</div>
	</div>
</div>
<div class="main-footer">
	<div class="container">
		<div class="footmenu">
			<a href="">About Argus Intelligence</a> | <a href="">Licensing</a> | <a href="">Privacy Policy</a> | <a href="">Terms &amp; Conditions</a><br /><span>Copyright &copy; 2009 Argus Intelligence, LLC. All rights reserved.</span>
		</div>
		<img src="images/argus-logo2.png" />
	</div>
</div>
<div class="loginbg-container">
	<img src="images/login-wrapper02.jpg" />
</div>
</body>
</html>
